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
use App\Models\UserPlan;
use App\Mail\PlanPurchaseMail;
use App\Mail\PrePlanDetailsSubmitMail;
use App\Models\Payment;
use GuzzleHttp\Client;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Plan;
use App\Models\SportCategory;
use App\Models\UserPrePlan;
use App\Models\PrePlanDetail;

class PaymentController extends Controller
{
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
                $coupon = Coupon::where('code', $validated['coupon_code'])
                    ->where('status', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('max_uses', '>', 0)
                    ->first();

                if ($coupon) {
                    $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
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
            // if ($finalPrice > 0 && empty($validated['payment_method_id'])) {
            //     return response()->json(['success' => false, 'message' => 'Payment method is required.']);
            // }

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
                CouponUsage::create([
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

    public function paymentSuccess()
    {
        return view('payment.success'); // Add a success page view
    }

    public function prePlanDetails(Request $request)
    {
        $userId = $request->user_id;
        $paymentId = $request->id;

        // Only select required columns (e.g., 'id') from user_pre_plans
        $prePlan = DB::table('user_pre_plans')
            ->select('id')  // Only select 'id' if that's all you use
            ->where('user_id', $userId)
            ->where('payment_id', $paymentId)
            ->first();

        $userPrePlanId = $prePlan->id ?? null;

        // Get the max step completed (single value, efficient)
        $completedSteps = DB::table('pre_plan_details')
            ->where('user_pre_plan_id', $userPrePlanId)
            ->max('step');

        // Determine the next step
        $nextStep = ($completedSteps < 9) ? $completedSteps + 1 : 9;

        // Fetch only needed columns for stepData
        $stepData = DB::table('pre_plan_details')
            ->select('id', 'step', 'field_name', 'field_value') // Specify only needed columns
            ->where('user_pre_plan_id', $userPrePlanId)
            ->get()
            ->groupBy('step');

        $sportCategories = SportCategory::select('id', 'name')->get(); // If you only need id and name

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

        try {
            // Step 1: Create or find UserPrePlan
            $prePlan = UserPrePlan::firstOrCreate(
                ['user_id' => $user_id, 'payment_id' => $payment_id],
                [
                    'dob' => $answers['personal_details']['dob'] ?? null,
                    'occupation' => $answers['personal_details']['occupation'] ?? null,
                    'address' => $answers['personal_details']['postcode'] ?? null,
                    'referredBy' => $answers['personal_details']['referredBy'] ?? null,
                    'other' => $request->other,
                ]
            );

            // Step 2: Delete existing data for that step
            if ($step !== null) {
                PrePlanDetail::where('user_pre_plan_id', $prePlan->id)
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
                                'user_pre_plan_id' => $prePlan->id,
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
                            'user_pre_plan_id' => $prePlan->id,
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

            // Step 4: Bulk insert
            \App\Models\PrePlanDetail::insert($dataToInsert);

            DB::commit();

            // Optional email logic (you can re-enable if needed)
            // $payment = \App\Models\Payment::with('user')->find($payment_id);
            // $email = $payment->user->email ?? null;
            // $planName = optional($payment->plan)->name ?? null;
            // Mail::to($email)->send(new PlanPurchaseMail($payment->user, $planName));

            return response()->json([
                'success' => true,
                'message' => 'Step data saved successfully!',
                'redirect_url' => $step == 9 ? route('front.sub-home-page') : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving step: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
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
