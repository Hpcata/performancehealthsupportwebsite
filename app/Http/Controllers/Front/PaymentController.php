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

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'payment_method_id' => 'required|string',
            'plan_id' => 'required|integer',
            'price' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8', // New password validation
        ]);

        DB::beginTransaction(); // Start a database transaction

        try {
            // Initialize Stripe
            Stripe::setApiKey('sk_test_51QI09cHWqn47bqTG2jBxRszIld9Jh0XITRvFvDLPCpmgQUjls75dfoSw5IBBZiqXZkVz7yVgHLYInFBHN76eeZ9W0071DUatdf');

            // Create a PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['price'] * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('payment.success'), // Optional for redirect methods
            ]);

            // Check if the payment requires additional action
            if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                DB::rollBack(); // Rollback transaction in case of additional actions
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ]);
            } elseif ($paymentIntent->status === 'succeeded') {

                // Check if user exists with the given email
                $user = User::where('email', $validated['email'])->first();

                if ($user) {
                    // If user exists, verify the password
                    if (!Hash::check($validated['password'], $user->password)) {
                        DB::rollBack(); // Rollback transaction if password is incorrect
                        return response()->json(['success' => false, 'message' => 'Invalid credentials.']);
                    }
                    // Log the user in
                    // Auth::login($user);
                } else {
                    // If the user doesn't exist, create a new user
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
                    // Log the newly created user in
                    // Auth::login($user);
                }

                // Save the payment details
                $paymentId = DB::table('payments')->insertGetId([
                    'user_id' => $user->id,
                    'plan_id' => $validated['plan_id'],
                    'price' => $validated['price'],
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'payment_intent_id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit(); // Commit the transaction if everything is successful

                // Send the redirect URL in the response
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

            $prePalnId = DB::table('user_pre_plans')->insertGetId([
                'payment_id' => $payment_id,
                'user_id' => $user_id,
                'dob' => $request->dob,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'referredBy' => $request->referredBy,
                'other' => $request->other
            ]);

            foreach ($questions as $section => $sectionQuestions) {
                // dd($section);
                $formattedSection = ucwords(str_replace('_', ' ', $section));
                foreach ($sectionQuestions as $key => $questionText) {
                    // Retrieve the corresponding answers for the question
                    $questionAnswers = isset($answers[$section][$key]) 
                        ? json_encode($answers[$section][$key]) // Convert answers to JSON format
                        : null;

                    // Add a row of data
                    $dataToInsert[] = [
                        'user_pre_plan_id' => $prePalnId,
                        'form_name' => $formattedSection,
                        'form_slug' => $section, // Combine section and key for indexing
                        'question' => $questionText,
                        'answer' => $questionAnswers,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Step 3: Insert data into a single table
            DB::table('pre_plan_details')->insert($dataToInsert);

            DB::commit(); // Commit the transaction if everything is successful

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
}
