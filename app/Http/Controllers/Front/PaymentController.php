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
        $userId = User::where('email', $request->email)->value('id');

        // Define validation rules
        $rules = [
            'plan_id' => 'required|integer',
            'price' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => ($isGuest && !$userId) ? 'required|string|min:8' : 'nullable',
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

            $coupon = null;
            $discount = 0;
            $user = User::where('email', $validated['email'])->first();
            $isNewUser = false;

            if (!$user) {
                $firstName = explode(' ', $validated['name'])[0];
                $lastName = explode(' ', $validated['name'])[1] ?? '';

                $user = User::create([
                    'name' => $validated['name'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($validated['password']),
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
                            'valid' => false,
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

            // If payment required but no payment method provided
            if ($finalPrice > 0 && empty($validated['payment_method_id'])) {
                return response()->json(['success' => false, 'message' => 'Payment method is required.']);
            }

            $paymentIntentId = null;
            $status = 'discount_applied';
            
            // If payment is required, create Stripe payment intent
            if ($finalPrice > 0) {
                Log::debug('Creating Stripe payment intent.', ['amount' => $finalPrice * 100]);

                $paymentIntent = PaymentIntent::create([
                    'amount' => $finalPrice * 100,
                    'currency' => 'aud',
                    'payment_method' => $validated['payment_method_id'],
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('payment.success'),
                ]);

                Log::debug('Stripe PaymentIntent created.', ['status' => $paymentIntent->status]);

                if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                    DB::rollBack();
                    return response()->json([
                        'requires_action' => true,
                        'payment_intent_client_secret' => $paymentIntent->client_secret,
                    ]);
                } elseif ($paymentIntent->status !== 'succeeded') {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Payment failed.']);
                }

                $paymentIntentId = $paymentIntent->id;
                $status = $paymentIntent->status;
            }

            // Save payment record (always, regardless of discount)
            $paymentId = DB::table('payments')->insertGetId([
                'user_id' => $user->id,
                'plan_id' => $validated['plan_id'],
                'price' => $finalPrice,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'payment_intent_id' => $paymentIntentId,
                'status' => $status,
                'coupon_code' => $validated['coupon_code'] ?? null,
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

            DB::commit();
            Log::debug('Payment processed successfully.', ['payment_id' => $paymentId]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully!',
                'data' => [
                    'user_id' => $user->id,
                    'payment_id' => $paymentId,
                    'submit_questionnaire' => $submitQuestionnaire
                ],
                'redirect_url' => route('front.pre-plan-details')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
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
        $userId = $request->user_id;
        $paymentId = $request->id;
        // dd($request->all());
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
        if($completedSteps < 9) {
            $nextStep = $completedSteps + 1;
        }else {
            $nextStep = 9;
        }

        // Retrieve data for all steps to pre-fill the form
        $stepData = DB::table('pre_plan_details')
            ->where('user_pre_plan_id', $prePlan->id ?? null)
            ->get()
            ->groupBy('step');
        
        $sportCategories = SportCategory::all();
        // dd($nextStep);
        return view('front.pre_plan_details', compact('userId', 'paymentId', 'nextStep', 'stepData', 'sportCategories'));
    }

    public function prePlanDetailsSave(Request $request)
    {
        $user_id = $request->user_id ?? null;
        $payment_id = $request->payment_id ?? null;
        $questions = $request->input('questions', []);
        $answers = $request->input('ans', []);
        $step = $request->input('step');
        $stepFill = $request->input('step_fill') == true ? 1 : 0;

        DB::beginTransaction();
        // dd($request->all());
        try {
            $prePlanId = DB::table('user_pre_plans')
                ->where('user_id', $user_id)
                ->where('payment_id', $payment_id)
                ->value('id');
            // dd($prePlanId );
            if (!$prePlanId) {
                $prePlanId = DB::table('user_pre_plans')->insertGetId([
                    'payment_id' => $payment_id,
                    'user_id' => $user_id,
                    'dob' => $request->ans['personal_details']['dob'] ?? null,
                    'occupation' => $request->ans['personal_details']['occupation'] ?? null,
                    'address' => $request->ans['personal_details']['postcode'] ?? null, // Fixed the double $$ here
                    'culture' => null,
                    'referredBy' => $request->ans['personal_details']['referredBy'] ?? null,
                    'other' => $request->other ?? null,
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

                            if (!is_null($subQuestionAnswers)) {
                                $subQuestionAnswers = is_array($subQuestionAnswers)
                                    ? json_encode($subQuestionAnswers, JSON_THROW_ON_ERROR)
                                    : json_encode((string)$subQuestionAnswers, JSON_THROW_ON_ERROR);
                            }

                            $dataToInsert[] = [
                                'user_pre_plan_id' => $prePlanId,
                                'form_name' => $formattedSection,
                                'form_slug' => $section,
                                'question' => $subQuestionText,
                                'answer' => $subQuestionAnswers,
                                'step' => $step,
                                'step_fill' => $stepFill,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    } else {
                        $questionAnswers = isset($answers[$section][$key])
                            ? (is_array($answers[$section][$key])
                                ? json_encode($answers[$section][$key], JSON_THROW_ON_ERROR)
                                : json_encode((string)$answers[$section][$key], JSON_THROW_ON_ERROR))
                            : null;

                        $dataToInsert[] = [
                            'user_pre_plan_id' => $prePlanId,
                            'form_name' => $formattedSection,
                            'form_slug' => $section,
                            'question' => $questionText,
                            'answer' => $questionAnswers,
                            'step' => $step,
                            'step_fill' => $stepFill,
                            'created_at' => now(),
                            'updated_at' => now(),
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
                'success' => true,
                'message' => 'Step data saved successfully!',
                'redirect_url' => $step == 9 ? route('front.sub-home-page') : null // example redirect after last step
            ]);

        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            Log::error('Error saving step: ' . $e->getMessage());
            // return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
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
