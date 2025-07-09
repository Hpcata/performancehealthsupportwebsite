<?php
namespace App\Http\Controllers\Front;

use Stripe\Stripe;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Payment;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Mail\PlanPurchaseMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrePlanDetailsSubmitMail;

class PaymentController extends Controller
{
    //public function processPayment(Request $request)
    // {
    //     // Validate incoming request data
    //     $validated = $request->validate([
    //         'payment_method_id' => 'nullable|string',
    //         'plan_id' => 'required|integer',
    //         'price' => 'nullable|numeric',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'nullable|string|max:20',
    //         'password' => 'required|string|min:8', // New password validation
    //         'coupon_code' => 'nullable'
    //     ]);

    //     DB::beginTransaction(); // Start a database transaction

    //     try {
    //         Log::debug('Stripe payment flow started.', ['request' => $request->all()]);

    //         Stripe::setApiKey(config('services.stripe.secret'));

    //         $coupon = null;
    //         $discount = 0;
    //         $user = null;

    //         Log::debug('Checking if user exists by email', ['email' => $validated['email']]);

    //         $user = User::where('email', $validated['email'])->first();
    //         $isNewUser = false;           
    //         if (!$user) {
    //             Log::debug('User not found. Creating new user.');

    //             $firstName = explode(' ', $validated['name'])[0];
    //             $lastName = explode(' ', $validated['name'])[1] ?? '';

    //             $user = User::create([
    //                 'name' => $validated['name'],
    //                 'first_name' => $firstName,
    //                 'last_name' => $lastName,
    //                 'email' => $validated['email'],
    //                 'phone' => $validated['phone'],
    //                 'password' => Hash::make($validated['password']),
    //             ]);

    //             $isNewUser = true;
    //             Log::debug('New user created.', ['user_id' => $user->id]);
    //         }

    //         $submitQuestionnaire = false;

    //         if ($isNewUser) {
    //             $submitQuestionnaire = true;
    //         } else {
    //             $userPrePlan = \App\Models\UserPrePlan::where('user_id', $user->id)->first();
    //             if (!$userPrePlan) {
    //                 $submitQuestionnaire = true;
    //             }
    //         }

    //         $payment = Payment::where('plan_id', $validated['plan_id'])->where('user_id', $user->id)->first();
    //         if ($payment) {
    //             Log::debug('Duplicate plan purchase attempt.', ['user_id' => $user->id, 'plan_id' => $validated['plan_id']]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'You have already purchased this plan. Please login to your account to manage your plans.',
    //             ]);
    //         }

    //         if (!empty($validated['coupon_code'])) {
    //             Log::debug('Checking coupon code.', ['coupon_code' => $validated['coupon_code']]);

    //             $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])
    //                 ->where('status', true)
    //                 ->where('start_date', '<=', now())
    //                 ->where('end_date', '>=', now())
    //                 ->where('max_uses', '>', 0)
    //                 ->first();

    //             if ($coupon) {
    //                 Log::debug('Coupon found.', ['coupon_id' => $coupon->id]);

    //                 $userUsageCount = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
    //                     ->where('user_id', $user->id)
    //                     ->count();

    //                 if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
    //                     Log::debug('User has already used the coupon maximum times.');
    //                     return response()->json([
    //                         'valid' => false,
    //                         'message' => 'You have already used this coupon the maximum allowed times.',
    //                     ]);
    //                 }

    //                 if ($coupon->type == 'percentage' && $coupon->value == 100.00) {
    //                     $discount = "full";
    //                 } elseif ($coupon->type == 'percentage') {
    //                     $discount = ($validated['price'] * $coupon->value) / 100;
    //                 } elseif ($coupon->type == 'fixed') {
    //                     $discount = $coupon->value;
    //                 }

    //                 Log::debug('Discount calculated.', ['discount' => $discount]);
    //             } else {
    //                 Log::debug('Invalid or expired coupon code.');
    //                 return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
    //             }
    //         }

    //         $finalPrice = ($discount == "full") ? 0 : max(0, $validated['price'] - $discount);
    //         Log::debug('Final price after discount.', ['final_price' => $finalPrice]);

    //         if ($finalPrice <= 0) {
    //             Log::debug('Full discount applied. Skipping Stripe.');

    //             $paymentId = DB::table('payments')->insertGetId([
    //                 'user_id' => $user->id,
    //                 'plan_id' => $validated['plan_id'],
    //                 'price' => 0,
    //                 'name' => $validated['name'],
    //                 'email' => $validated['email'],
    //                 'phone' => $validated['phone'],
    //                 'payment_intent_id' => null,
    //                 'status' => 'discount_applied',
    //                 'coupon_code' => $validated['coupon_code'] ?? null,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             if ($coupon) {
    //                 $coupon->increment('usage_count');
    //                 \App\Models\CouponUsage::create([
    //                     'coupon_id' => $coupon->id,
    //                     'user_id' => $user->id,
    //                 ]);
    //             }
                
    //             DB::commit();
    //             Log::debug('Payment saved with full discount.', ['payment_id' => $paymentId]);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Payment processed successfully with full discount!',
    //                 'data' => ['user_id' => $user->id, 'payment_id' => $paymentId, 'submit_questionnaire' => $submitQuestionnaire],
    //                 'redirect_url' => route('front.pre-plan-details')
    //             ]);
    //         }

    //         Log::debug('Creating Stripe payment intent.', ['amount' => $finalPrice * 100]);

    //         $paymentIntent = PaymentIntent::create([
    //             'amount' => $finalPrice * 100,
    //             'currency' => 'aud',
    //             'payment_method' => $validated['payment_method_id'],
    //             'confirmation_method' => 'manual',
    //             'confirm' => true,
    //             'return_url' => route('payment.success'),
    //         ]);

    //         Log::debug('Stripe PaymentIntent created.', ['status' => $paymentIntent->status]);

    //         if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
    //             DB::rollBack();
    //             Log::debug('Payment requires additional action.');
    //             return response()->json([
    //                 'requires_action' => true,
    //                 'payment_intent_client_secret' => $paymentIntent->client_secret,
    //             ]);
    //         } elseif ($paymentIntent->status === 'succeeded') {
    //             $paymentId = DB::table('payments')->insertGetId([
    //                 'user_id' => $user->id,
    //                 'plan_id' => $validated['plan_id'],
    //                 'price' => $finalPrice,
    //                 'name' => $validated['name'],
    //                 'email' => $validated['email'],
    //                 'phone' => $validated['phone'],
    //                 'payment_intent_id' => $paymentIntent->id,
    //                 'status' => $paymentIntent->status,
    //                 'coupon_code' => null,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             if ($coupon) {
    //                 $coupon->increment('usage_count');
    //                 \App\Models\CouponUsage::create([
    //                     'coupon_id' => $coupon->id,
    //                     'user_id' => $user->id,
    //                 ]);
    //             }

    //             DB::commit();
    //             Log::debug('Payment succeeded and saved.', ['payment_id' => $paymentId]);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Payment processed successfully!',
    //                 'data' => ['user_id' => $user->id, 'payment_id' => $paymentId, 'submit_questionnaire' => $submitQuestionnaire],
    //                 'redirect_url' => route('front.pre-plan-details')
    //             ]);
    //         } else {
    //             DB::rollBack();
    //             Log::debug('Payment failed.', ['status' => $paymentIntent->status]);
    //             return response()->json(['success' => false, 'message' => 'Payment failed.']);
    //         }

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Payment error caught in catch block: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    //         return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
    //     }

    // }

    public function processPayment(Request $request)
    {
        $isGuest = !auth()->guard('web')->check();

        // Define validation rules
        $rules = [
            'plan_id' => 'required|integer',
            'price' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => $isGuest ? 'required|string|min:8' : 'nullable',
            'coupon_code' => 'nullable'
        ];

        // Conditionally require payment_method_id only if price is > 0
        if (!$request->has('coupon_code') || ($request->price > 0)) {
            $rules['payment_method_id'] = 'nullable';
        } else {
            $rules['payment_method_id'] = 'nullable';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            Log::debug('Stripe payment flow started.', ['request' => $request->all()]);
            Stripe::setApiKey(config('services.stripe.secret'));

            $coupon   = null;
            $discount = 0;
            $user     = null;

            Log::debug('Checking if user exists by email', ['email' => $validated['email']]);
            $user = User::where('email', $validated['email'])->first();
            $isNewUser = false;

            if (!$user) {
                $firstName = explode(' ', $validated['name'])[0];
                $lastName  = explode(' ', $validated['name'])[1] ?? '';

                $user = User::create([
                    'name'       => $validated['name'],
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $validated['email'],
                    'phone'      => $validated['phone'],
                    'password'   => Hash::make($validated['password']),
                ]);

                $isNewUser = true;
                Log::debug('New user created.', ['user_id' => $user->id]);
            }

            $submitQuestionnaire = $isNewUser || !\App\Models\UserPrePlan::where('user_id', $user->id)->exists();

            $existingPayment = Payment::where('plan_id', $validated['plan_id'])
                ->where('user_id', $user->id)
                ->first();

            if ($existingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already purchased this plan. Please login to your account to manage your plans.',
                ]);
            }

            // Apply coupon logic
            if (!empty($validated['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])
                    ->where('status', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('max_uses', '>', 0)
                    ->first();

                if ($coupon) {
                    $userUsageCount = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
                        ->where('user_id', $user->id)
                        ->count();

                    if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
                        return response()->json([
                            'valid'   => false,
                            'message' => 'You have already used this coupon the maximum allowed times.',
                        ]);
                    }

                    if ($coupon->type === 'percentage' && $coupon->value == 100.00) {
                        $discount = 'full';
                    } elseif ($coupon->type === 'percentage') {
                        $discount = ($validated['price'] * $coupon->value) / 100;
                    } elseif ($coupon->type === 'fixed') {
                        $discount = $coupon->value;
                    }

                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
                }
            }

            $finalPrice = ($discount === 'full') ? 0 : max(0, $validated['price'] - $discount);
            Log::debug('Final price after discount.', ['final_price' => $finalPrice]);

            if ($finalPrice <= 0) {
                Log::debug('Full discount applied. Skipping Stripe.');

                $paymentId = DB::table('payments')->insertGetId([
                    'user_id'           => $user->id,
                    'plan_id'           => $validated['plan_id'],
                    'price'             => 0,
                    'name'              => $validated['name'],
                    'email'             => $validated['email'],
                    'phone'             => $validated['phone'],
                    'payment_intent_id' => null,
                    'status'            => 'discount_applied',
                    'coupon_code'       => $validated['coupon_code'] ?? null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                if ($coupon) {
                    $coupon->increment('usage_count');
                    \App\Models\CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id'   => $user->id,
                    ]);
                }

                DB::commit();
                Log::debug('Payment saved with full discount.', ['payment_id' => $paymentId]);

                return response()->json([
                    'success'      => true,
                    'message'      => 'Payment processed successfully with full discount!',
                    'data'         => ['user_id' => $user->id, 'payment_id' => $paymentId],
                    'redirect_url' => route('front.pre-plan-details'),
                ]);
            }

            $paymentIntentId = null;
            $status = 'discount_applied';

            // If payment is required, create Stripe payment intent
            if ($finalPrice > 0) {
                Log::debug('Creating Stripe payment intent.', ['amount' => $finalPrice * 100]);

                $paymentIntent = PaymentIntent::create([
                    'amount'              => $finalPrice * 100,
                    'currency'            => 'aud',
                    'payment_method'      => $validated['payment_method_id'],
                    'confirmation_method' => 'manual',
                    'confirm'             => true,
                    'return_url'          => route('front.payment.success'),
                ]);

                Log::debug('Stripe PaymentIntent created.', ['status' => $paymentIntent->status]);

                if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                    DB::rollBack();
                    Log::debug('Payment requires additional action.');
                    return response()->json([
                        'requires_action'              => true,
                        'payment_intent_client_secret' => $paymentIntent->client_secret,
                    ]);
                } elseif ($paymentIntent->status === 'succeeded') {
                    $paymentId = DB::table('payments')->insertGetId([
                        'user_id'           => $user->id,
                        'plan_id'           => $validated['plan_id'],
                        'price'             => $finalPrice,
                        'name'              => $validated['name'],
                        'email'             => $validated['email'],
                        'phone'             => $validated['phone'],
                        'payment_intent_id' => $paymentIntent->id,
                        'status'            => $paymentIntent->status,
                        'coupon_code'       => null,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    if ($coupon) {
                        $coupon->increment('usage_count');
                        \App\Models\CouponUsage::create([
                            'coupon_id' => $coupon->id,
                            'user_id'   => $user->id,
                        ]);
                    }

                    DB::commit();
                    Log::debug('Payment succeeded and saved.', ['payment_id' => $paymentId]);

                    return response()->json([
                        'success'      => true,
                        'message'      => 'Payment processed successfully!',
                        'data'         => ['user_id' => $user->id, 'payment_id' => $paymentId],
                        'redirect_url' => route('front.pre-plan-details'),
                    ]);
                } else {
                    DB::rollBack();
                    Log::debug('Payment failed.', ['status' => $paymentIntent->status]);
                    return response()->json(['success' => false, 'message' => 'Payment failed.']);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }

    public function paymentSuccess()
    {
        return view('front.payment.success'); // Add a success page view
    }

    public function prePlanDetails(Request $request)
    {
        $userId    = $request->user_id;
        $paymentId = $request->id;
        // Retrieve the user's pre-plan details
        $prePlan = DB::table('user_pre_plans')
            ->where('user_id', $userId)
            ->where('payment_id', $paymentId)
            ->first();

        // Retrieve all steps completed by the user
        $completedSteps = DB::table('pre_plan_details')
            ->where('user_pre_plan_id', $prePlan->id ?? null)
            ->max('step');

        // Determine the next step
        if ($completedSteps < 9) {
            $nextStep = $completedSteps + 1;
        } else {
            $nextStep = 9;
        }

        // Retrieve data for all steps to pre-fill the form
        $stepData = DB::table('pre_plan_details')
            ->where('user_pre_plan_id', $prePlan->id ?? null)
            ->get()
            ->groupBy('step');
        return view('front.pre_plan_details', compact('userId', 'paymentId', 'nextStep', 'stepData'));
    }

    public function prePlanDetailsSave(Request $request)
    {
        $user_id    = $request->user_id ?? null;
        $payment_id = $request->payment_id ?? null;
        $questions  = $request->input('questions', []);
        $answers    = $request->input('ans', []);
        $step       = $request->input('step');
        $stepFill   = $request->input('step_fill') == true ? 1 : 0;

        DB::beginTransaction();
        try {
            $prePlanId = DB::table('user_pre_plans')
                ->where('user_id', $user_id)
                ->where('payment_id', $payment_id)
                ->value('id');
            if (! $prePlanId) {
                $prePlanId = DB::table('user_pre_plans')->insertGetId([
                    'payment_id' => $payment_id,
                    'user_id'    => $user_id,
                    'dob'        => $request->ans['personal_details']['dob'] ?? null,
                    'occupation' => $request->ans['personal_details']['occupation'] ?? null,
                    'address'    => $request->ans['personal_details']['address'] ?? null, // Fixed the double $$ here
                    'culture'    => null,
                    'referredBy' => $request->ans['personal_details']['referredBy'] ?? null,
                    'other'      => $request->other ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Remove old data for this step
            if ($step !== null) {
                DB::table('pre_plan_details')
                    ->where('user_pre_plan_id', $prePlanId)
                    ->where('step', $step)
                    ->delete();
            }

            $dataToInsert = [];

            foreach ($questions as $section => $sectionQuestions) {
                $formattedSection = ucwords(str_replace('_', ' ', $section));
                foreach ($sectionQuestions as $key => $questionText) {
                    $questionAnswers = $answers[$section][$key] ?? null;

                    if (is_array($questionText)) {
                        foreach ($questionText as $qsnkey => $subQuestionText) {
                            $subQuestionAnswers = $questionAnswers[$qsnkey] ?? null;

                            if (! is_null($subQuestionAnswers)) {
                                $subQuestionAnswers = is_array($subQuestionAnswers)
                                ? json_encode($subQuestionAnswers, JSON_THROW_ON_ERROR)
                                : json_encode((string) $subQuestionAnswers, JSON_THROW_ON_ERROR);
                            }

                            $dataToInsert[] = [
                                'user_pre_plan_id' => $prePlanId,
                                'form_name'        => $formattedSection,
                                'form_slug'        => $section,
                                'question'         => $subQuestionText,
                                'answer'           => $subQuestionAnswers,
                                'step'             => $step,
                                'step_fill'        => $stepFill,
                                'created_at'       => now(),
                                'updated_at'       => now(),
                            ];
                        }
                    } else {
                        $questionAnswers = isset($answers[$section][$key])
                        ? (is_array($answers[$section][$key])
                            ? json_encode($answers[$section][$key], JSON_THROW_ON_ERROR)
                            : json_encode((string) $answers[$section][$key], JSON_THROW_ON_ERROR))
                        : null;

                        $dataToInsert[] = [
                            'user_pre_plan_id' => $prePlanId,
                            'form_name'        => $formattedSection,
                            'form_slug'        => $section,
                            'question'         => $questionText,
                            'answer'           => $questionAnswers,
                            'step'             => $step,
                            'step_fill'        => $stepFill,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ];
                    }
                }
            }

            DB::table('pre_plan_details')->insert($dataToInsert);

            DB::commit();

            $payment = \App\Models\Payment::with('user')->where('id',$payment_id)->first();
            $email = $payment->user->email;
            $planName = \App\Models\Plan::where('id', $payment->plan_id)->first()->name;
            $user = $payment->user;
            // try {
            //     Mail::to($email)->send(new PlanPurchaseMail($user, $planName));
    
            //     $adminEmail = 'kerry@performancehealthsupport.com'; // Set admin email address
            //     Mail::to($adminEmail)->send(new PrePlanDetailsSubmitMail($user, $planName));  // passing 'true' to indicate it's an admin
            // } catch (\Exception $e) {
            //     Log::error('Error saving step: ' . $e->getMessage());
            // }

            return response()->json([
                'success'      => true,
                'message'      => 'Step data saved successfully!',
                'redirect_url' => $step == 9 ? route('front.sub-home-page') : null, // example redirect after last step
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving step: ' . $e->getMessage());
            // return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getRaceEthnicityCultureOptions(Request $request)
    {
        try {
            // Initialize Guzzle HTTP client
            $client = new Client([
                'base_uri' => 'https://api.openai.com/v1/',
                'headers'  => [
                    'Authorization' => 'Bearer ' . config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
            ]);

            // Define the request payload for OpenAI
            $payload = [
                'model'    => 'gpt-4',
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

            if (! $content) {
                return response()->json(['error' => 'Failed to retrieve content from OpenAI.'], 500);
            }

            // Convert content to an associative array
            $options = json_decode($content, true);

            // Validate JSON and limit to top 20 options
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($options)) {
                return response()->json(['error' => 'Invalid AI response format.'], 500);
            }

            $options = array_slice($options, 0, 20);

            return response()->json(['options' => $options]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function questionnaireSendMail(Request $request)
    {
        $userId = $request->input('user_id');
        $paymentId = $request->input('payment_id');
        $user = User::find($userId);
        $payment = Payment::find($paymentId);
        $plan = $payment->plan;

        if (!$user || !$payment || !$plan) {
            return response()->json(['success' => false, 'message' => 'Invalid data.']);
        }

        try {
            Mail::to($user->email)->send(new PlanPurchaseMail($user, $plan->name));
            Mail::to('kerry@performancehealthsupport.com')->send(new PrePlanDetailsSubmitMail($user, $plan->name));

            return response()->json([
                'success' => true,
                'message' => 'Mail sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Mail send failed.']);
        }
    }
}
