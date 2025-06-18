<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Hash;
use Str;
use Auth;
use App\Models\UserPlan;
use App\Mail\PlanPurchaseMail;
use App\Mail\PrePlanDetailsSubmitMail;
use App\Models\Payment;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'payment_method_id' => 'nullable|string',
            'plan_id' => 'required|integer',
            'price' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8', // New password validation
            'coupon_code' => 'nullable'
        ]);

        DB::beginTransaction(); // Start a database transaction

        try {
            // dd($request->all());
            // Initialize Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Stripe::setApiKey('sk_test_51QI09cHWqn47bqTG2jBxRszIld9Jh0XITRvFvDLPCpmgQUjls75dfoSw5IBBZiqXZkVz7yVgHLYInFBHN76eeZ9W0071DUatdf');

            // Initialize coupon variables
            $coupon = null;
            $discount = 0;
            $user = null;

            // Check if the user exists by email
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                // If user doesn't exist, create a new user
                $firstName = explode(' ', $validated['name'])[0]; // First name from full name
                $lastName = explode(' ', $validated['name'])[1] ?? ''; // Last name from full name

                // Create new user with hashed password
                $user = User::create([
                    'name' => $validated['name'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($validated['password']), // Store hashed password
                ]);
            }

            $payment = Payment::where('plan_id', $validated['plan_id'])->where('user_id', $user->id)->first();
            if ($payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already purchased this plan. Please login to your account to manage your plans.',
                    
                ]);
            }

            // Now that we have the user, check for coupon code
            if ($validated['coupon_code']) {
                $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])
                    ->where('status', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('max_uses', '>', 0)
                    ->first();

                // If coupon is found, apply discount
                if ($coupon) {
                    // Check if the user has already used the coupon more than allowed
                    $userUsageCount = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
                        ->where('user_id', $user->id)
                        ->count();

                    if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
                        return response()->json([
                            'valid' => false,
                            'message' => 'You have already used this coupon the maximum allowed times.',
                        ]);
                    }

                    // Apply discount based on coupon type
                    if ($coupon->type == 'percentage' && $coupon->value == 100.00) {
                        $discount = "full";
                    } elseif($coupon->type == 'percentage') {
                        $discount = ($validated['price'] * $coupon->value) / 100;
                    } elseif ($coupon->type == 'fixed') {
                        $discount = $coupon->value;
                    }
                } else {
                    // Return error if coupon is invalid or expired
                    return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
                }
            }

            if($discount == "full") {
                $finalPrice = 0;
            }else {
                $finalPrice = max(0, $validated['price'] - $discount);
            }

            // If the discount fully covers the price, skip payment process
            if ($finalPrice <= 0) {
                // Create a payment record
                $paymentId = DB::table('payments')->insertGetId([
                    'user_id' => $user->id,
                    'plan_id' => $validated['plan_id'],
                    'price' => 0, // Price is 0 due to full discount
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'payment_intent_id' => null, // No payment intent since Stripe is skipped
                    'status' => 'discount_applied',
                    'coupon_code' => isset($validated['coupon_code']) ? $validated['coupon_code'] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Track coupon usage
                if ($coupon) {
                    $coupon->increment('usage_count');
                    \App\Models\CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $user->id,
                    ]);
                }

                DB::commit(); // Commit the transaction

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully with full discount!',
                    'data' => ['user_id' => $user->id, 'payment_id' => $paymentId],
                    'redirect_url' => route('front.pre-plan-details') // Redirect to user's dashboard
                ]);
            }

            // If the price isn't fully discounted, proceed with Stripe payment
            $paymentIntent = PaymentIntent::create([
                'amount' => $finalPrice * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('payment.success'), // Optional for redirect methods
            ]);

            // Handle payment requires additional action
            if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                DB::rollBack(); // Rollback transaction in case of additional actions
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ]);
            } elseif ($paymentIntent->status === 'succeeded') {
                // Save the payment details in the payments table
                $paymentId = DB::table('payments')->insertGetId([
                    'user_id' => $user->id,
                    'plan_id' => $validated['plan_id'],
                    'price' => $finalPrice,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'payment_intent_id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'coupon_code' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Track coupon usage if used
                if ($coupon) {
                    $coupon->increment('usage_count');
                    \App\Models\CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $user->id,
                    ]);
                }

                DB::commit(); // Commit the transaction

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully!',
                    'data' => ['user_id' => $user->id, 'payment_id' => $paymentId],
                    'redirect_url' => route('front.pre-plan-details') // Redirect to user's dashboard
                ]);
            } else {
                DB::rollBack(); // Rollback transaction if payment fails
                return response()->json(['success' => false, 'message' => 'Payment failed.']);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of any exception
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }

    // public function processPayment(Request $request)
    // {
    //     // Validate incoming request data
    //     $validated = $request->validate([
    //         'payment_method_id' => 'required|string',
    //         'plan_id' => 'required|integer',
    //         'price' => 'required|numeric',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'nullable|string|max:20',
    //         'password' => 'nullable|string|min:8', // New password validation
    //         'coupon_code' => 'nullable'
    //     ]);

    //     DB::beginTransaction(); // Start a database transaction

    //     try {
    //         // Initialize Stripe
    //         Stripe::setApiKey('sk_test_51QI09cHWqn47bqTG2jBxRszIld9Jh0XITRvFvDLPCpmgQUjls75dfoSw5IBBZiqXZkVz7yVgHLYInFBHN76eeZ9W0071DUatdf');

    //         // Initialize coupon variables
    //         $coupon = null;
    //         $discount = 0;
    //         $user = null;

    //         // Check if the user exists by email
    //         $user = User::where('email', $validated['email'])->first();

    //         if (!$user) {
    //             // If user doesn't exist, create a new user
    //             $firstName = explode(' ', $validated['name'])[0]; // First name from full name
    //             $lastName = explode(' ', $validated['name'])[1] ?? ''; // Last name from full name

    //             // Create new user with hashed password
    //             $user = User::create([
    //                 'name' => $validated['name'],
    //                 'first_name' => $firstName,
    //                 'last_name' => $lastName,
    //                 'email' => $validated['email'],
    //                 'phone' => $validated['phone'],
    //                 'password' => Hash::make($validated['password']), // Store hashed password
    //             ]);
    //         }

    //         // Now that we have the user, check for coupon code
    //         if ($validated['coupon_code']) {
    //             $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])
    //                 ->where('status', true)
    //                 ->where('start_date', '<=', now())
    //                 ->where('end_date', '>=', now())
    //                 ->where('max_uses', '>', 0)
    //                 ->first();

    //             // If coupon is found, apply discount
    //             if ($coupon) {
    //                 // Check if the user has already used the coupon more than allowed
    //                 $userUsageCount = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
    //                     ->where('user_id', $user->id)
    //                     ->count();

    //                 if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
    //                     return response()->json([
    //                         'valid' => false,
    //                         'message' => 'You have already used this coupon the maximum allowed times.',
    //                     ]);
    //                 }

    //                 // Apply discount based on coupon type
    //                 if ($coupon->type == 'percentage') {
    //                     $discount = ($validated['price'] * $coupon->value) / 100;
    //                 } elseif ($coupon->type == 'fixed') {
    //                     $discount = $coupon->value;
    //                 }
    //             } else {
    //                 // Return error if coupon is invalid or expired
    //                 return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
    //             }
    //         }

    //         // Minimum allowed charge amount for Stripe (in cents)
    //         $minimumAmount = 50; // 50 cents (for USD)

    //         $finalPrice = max(0, $validated['price'] - $discount);
    //         // If final price is below the minimum amount, set it to the minimum amount
    //         $paymentAmount = $finalPrice * 100 < $minimumAmount * 100 ? $minimumAmount * 100 : $finalPrice * 100;

    //         // Create a PaymentIntent with Stripe
    //         $paymentIntent = PaymentIntent::create([
    //             'amount' => $paymentAmount, // Amount in cents
    //             'currency' => 'usd',
    //             'payment_method' => $validated['payment_method_id'],
    //             'confirmation_method' => 'manual',
    //             'confirm' => true,
    //             'return_url' => route('payment.success'), // Optional for redirect methods
    //         ]);

    //         // Handle payment requires additional action
    //         if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
    //             DB::rollBack(); // Rollback transaction in case of additional actions
    //             return response()->json([
    //                 'requires_action' => true,
    //                 'payment_intent_client_secret' => $paymentIntent->client_secret,
    //             ]);
    //         } elseif ($paymentIntent->status === 'succeeded') {

    //             // Save the payment details in the payments table
    //             $paymentId = DB::table('payments')->insertGetId([
    //                 'user_id' => $user->id,
    //                 'plan_id' => $validated['plan_id'],
    //                 'price' => $paymentAmount,
    //                 'name' => $validated['name'],
    //                 'email' => $validated['email'],
    //                 'phone' => $validated['phone'],
    //                 'payment_intent_id' => $paymentIntent->id,
    //                 'status' => $paymentIntent->status,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             // Track coupon usage if used
    //             if ($coupon) {
    //                 // Decrement max_uses and increment usage_count for the coupon
    //                 // $coupon->decrement('max_uses');
    //                 $coupon->increment('usage_count');
    //                 \App\Models\CouponUsage::create([
    //                     'coupon_id' => $coupon->id,
    //                     'user_id' => $user->id,
    //                 ]);
    //             }

    //             DB::commit(); // Commit the transaction if everything is successful

    //             // Send the redirect URL in the response
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Payment processed successfully!',
    //                 'data' => ['user_id' => $user->id, 'payment_id' => $paymentId],
    //                 'redirect_url' => route('front.pre-plan-details') // Redirect to user's dashboard
    //             ]);

    //         } else {
    //             DB::rollBack(); // Rollback transaction if payment fails
    //             return response()->json(['success' => false, 'message' => 'Payment failed.']);
    //         }

    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Rollback transaction in case of any exception
    //         Log::error('Payment error: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
    //     }
    // }

    public function paymentSuccess()
    {
        return view('payment.success'); // Add a success page view
    }

    public function prePlanDetails(Request $request)
    {
        // dd($request->all());
        $userId = $request->user_id;
        $paymentId = $request->id;
        return view('front.pre_plan_details', compact('userId', 'paymentId'));
    }

    public function prePlanDetailsSave(Request $request)
    {

        $user_id = $request->user_id ?? null;
        $payment_id = $request->payment_id ?? null;
        $questions = $request->input('questions', []);
        $answers = $request->input('ans', []);
        // dd($request->all());
        // Step 2: Prepare data for insertion
        $dataToInsert = [];
        DB::beginTransaction(); // Start a database transaction
        try {

            $prePlanId = DB::table('user_pre_plans')->insertGetId([
                'payment_id' => $payment_id,
                'user_id' => $user_id,
                'dob' => $request->dob,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'culture' => $request->race_ethnicity_culture,
                'referredBy' => $request->referredBy,
                'other' => $request->other
            ]);

            foreach ($questions as $section => $sectionQuestions) {
                $formattedSection = ucwords(str_replace('_', ' ', $section)); // Format section name
                foreach ($sectionQuestions as $key => $questionText) {
                    $questionAnswers = $answers[$section][$key] ?? null;
            
                    if (is_array($questionText)) {
                        foreach ($questionText as $qsnkey => $subQuestionText) {
                            $subQuestionAnswers = $questionAnswers[$qsnkey] ?? null;
            
                            if (!is_null($subQuestionAnswers)) {
                                // Ensure subQuestionAnswers is valid JSON
                                $subQuestionAnswers = is_array($subQuestionAnswers)
                                    ? json_encode($subQuestionAnswers, JSON_THROW_ON_ERROR)
                                    : json_encode((string) $subQuestionAnswers, JSON_THROW_ON_ERROR);
                            }
            
                            $dataToInsert[] = [
                                'user_pre_plan_id' => $prePlanId,
                                'form_name' => $formattedSection,
                                'form_slug' => $section,
                                'question' => $subQuestionText,
                                'answer' => $subQuestionAnswers,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    } else {
                        // Ensure questionAnswers is valid JSON
                        $questionAnswers = isset($answers[$section][$key]) 
                            ? (is_array($answers[$section][$key])
                                ? json_encode($answers[$section][$key], JSON_THROW_ON_ERROR)
                                : json_encode((string) $answers[$section][$key], JSON_THROW_ON_ERROR))
                            : null;
            
                        $dataToInsert[] = [
                            'user_pre_plan_id' => $prePlanId,
                            'form_name' => $formattedSection,
                            'form_slug' => $section,
                            'question' => $questionText,
                            'answer' => $questionAnswers,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            
            // Step 3: Insert data into a single table
            DB::table('pre_plan_details')->insert($dataToInsert);

            DB::commit(); // Commit the transaction if everything is successful

            // **Handle File Upload**
            if ($request->hasFile('ans.medical_history.blood_test_file')) {
                $file = $request->file('ans.medical_history.blood_test_file');
                
                $filePath = $file->store('preplan_files', 'public');

                DB::table('pre_plan_question_files')->insert([
                    'user_pre_plan_id' => $prePlanId,
                    'form_slug' => 'medical_history',
                    'question' => 'Have you recently had a blood test?',
                    'file_path' => $filePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // **Handle Multiple File Uploads**
            if ($request->hasFile('ans.physical_measures.bodycomposition')) {
                foreach ($request->file('ans.physical_measures.bodycomposition') as $file) {
                    if ($file->isValid()) {
                        
                        $filePath = $file->store('preplan_files', 'public');

                        DB::table('pre_plan_question_files')->insert([
                            'user_pre_plan_id' => $prePlanId,
                            'form_slug' => 'physical_measures',
                            'question' => 'Have you recently undertaken a body composition assessment (measure of muscle, body fat)?',
                            'file_path' => $filePath,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            $payment = \App\Models\Payment::with('user')->where('id',$payment_id)->first();
            $email = $payment->user->email;
            $planName = \App\Models\Plan::where('id', $payment->plan_id)->first()->name;
            $user = $payment->user;

            Mail::to($email)->send(new PlanPurchaseMail($user, $planName));

            $adminEmail = 'kerry@performancehealthsupport.com'; // Set admin email address
            Mail::to($adminEmail)->send(new PrePlanDetailsSubmitMail($user, $planName));  // passing 'true' to indicate it's an admin

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully!',
                'redirect_url' => route('front.sub-home-page') // Redirect to user's dashboard
            ]);

        }  catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack(); // Rollback transaction in case of any exception
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
        }

    }

    public function getRaceEthnicityCultureOptions(Request $request)
    {
        try {
            // Initialize Guzzle HTTP client
            $client = new Client([
                'base_uri' => 'https://api.openai.com/v1/',
                'headers' => [
                    'Authorization' => 'Bearer '. config('services.openai.key'),
                    'Content-Type' => 'application/json',
                ],
            ]);
            
            // Define the request payload for OpenAI
            $payload = [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert data generator.'],
                    ['role' => 'user', 'content' => 'List the top 20 most common races, ethnicities, and cultures in Australia, formatted as JSON with "value" and "label" fields for each item.'],
                ],
            ];

            // Make the POST request to OpenAI API
            $response = $client->post('chat/completions', [
                'json' => $payload,
            ]);

            // Parse the response
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract content from OpenAI response
            $content = $responseBody['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                return response()->json(['error' => 'Failed to retrieve content from OpenAI.'], 500);
            }

            // Convert content to an associative array
            $options = json_decode($content, true);

            // Validate JSON and limit to top 20 options
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($options)) {
                return response()->json(['error' => 'Invalid AI response format.'], 500);
            }

            $options = array_slice($options, 0, 20);

            return response()->json(['options' => $options]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
