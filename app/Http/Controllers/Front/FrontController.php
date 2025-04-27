<?php

namespace App\Http\Controllers\Front;

use App\Models\Plan;
use App\Models\User;
use App\Services\UrlService;
use Illuminate\Http\Request;
use App\Services\JsonService;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\QueryRequest;
use App\Mail\QueryGenerated;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Query;
use App\Models\Blog;
use Exception;
use Hash;
use App\Models\UserPlan;
use Illuminate\Support\Facades\File;
use Validator;
use App\Models\Questionnaire;
use App\Models\WeightTracking;
use App\Models\Payment;
use App\Models\SportTracking;
use App\Models\UserPrePlan;
use App\Models\PrePlanDetail;
use App\Models\GoalHistory;
use App\Mail\SportInterestMail;
use Carbon\Carbon;

class FrontController extends Controller
{

    protected $requirement, $plan, $urlService, $jsonService, $stripeService;
    
    public function __construct()
    {
        // $this->requirement = new Requirement;
        // $this->plan = new Plan;
        $this->urlService = new UrlService;
        $this->jsonService = new JsonService;
        $this->stripeService = new StripeService;
    }

    public function index()
    {
        
        $plans = \App\Models\Plan::all();
        //dd($plans);
        $page = \App\Models\Page::with('sections')->where('slug', 'home')->first();
       
        $requirements = [];
    
        $disabledDay = json_encode([]);
       
        $organization = [];
        $testimonials = [];
        return view('front.index', compact('requirements','page', 'plans','disabledDay','organization','testimonials'));
    }

    public function save(QueryRequest $request)
    {
        try {
            $user = getUserBySlug($request->slug);
            if (!$user) {
                Session::flash('message', 'User not found.');
            }
            $postData = $request->only('name', 'email', 'mobile_number', 'message');
            $postData['user_id'] = $user->id;

            $query = Query::create($postData);

            Session::flash('confirmmsg', 'Thank you for your message. We will get back to you soon.');

            Mail::send(new QueryGenerated($user, $query));

            return redirect(route('booking'));
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Whoops! something went wrong.');
        }
    }

    public function blog()
    {
        $blogs = \App\Models\Blog::where('is_published', 1)->get();
        return view('front.blog', compact('blogs'));
    }

    public function blogDetails($id)
    {
        $blog = Blog::findOrFail($id);

        // $user = getUserBySlug($slug);
        // if (!$user) {
        //     Session::flash('message', 'User not found.');
        // }

        // Get related blogs based on tags
        $relatedBlogs = Blog::whereHas('tags', function ($query) use ($blog) {
            $query->whereIn('tags.id', $blog->tags->pluck('id'));
        })->where('id', '!=', $blog->id)->limit(5)->get();

        return view('front.blog-details', compact('blog', 'relatedBlogs'));
    }

    public function subHomePage()
    {
        // Step 1: Get all sub_plan_ids from plan_sub_plans table
        $subPlanIds = \DB::table('plan_sub_plans')->pluck('sub_plan_id')->toArray();

        // Step 2: Retrieve all plans that are NOT sub-plans
        $plans = \App\Models\Plan::whereNotIn('id', $subPlanIds)->get();

        // dd($plans);
        $page = \App\Models\Page::with('sections')->where('slug', 'actionsport-nutrition-plan')->first();
        
        $requirements = [];
       
        $disabledDay = json_encode([]);
      
        $organization = [];
        $testimonials = [];
        $isAuthenticated = Auth::check(); // Returns true if the user is logged in

        return view('front.sub-home-page', compact('requirements','page', 'plans','disabledDay','organization','testimonials','isAuthenticated'));
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $firstName = explode(' ', $request->input('name'))[0]; // First name from full name
        $lastName = explode(' ', $request->input('name'))[1] ?? ''; // Last name from full name

        $existingUser = User::where('email', $request->input('email'))->first();

        if ($existingUser) {
            $freeTest = Questionnaire::where('email', $request->input('email'))->first();
            if ($freeTest) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted the test.',
                    'user' => $existingUser,
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'User with this email already exists.',
                'user' => $existingUser,
            ]);
        }

        $user = User::create([
            'name' => $request->input('name'), // Full name of the admin user.
            'first_name' => $firstName, // First name of the admin user.
            'last_name' => $lastName, // Last name of the admin user.
            'email' => $request->input('email'), // Email of the admin user.
            'password' => Hash::make($request->input('password')), // Hashed password of the admin user.
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please login.',
            'user' => $user,
        ]);
    }

    // Handle Login Request
    public function login(Request $request)
    {
        // dd($request->all());
        // Validate the email and password
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Find the user by email
        $user = User::where('email', $validated['email'])->first();

        // Check if user exists and password matches
        if ($user && Hash::check($validated['password'], $user->password)) {
            // The user is authenticated, log them in

            $planIds = DB::table('payments')->where('email', $user->email)->where('status', 'succeeded')->orWhere('status','discount_applied')->pluck('plan_id')->toArray();
            if ($planIds) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    if (!Auth::user()->isSuperAdmin()) {
                        $redirectUrl = route('front.profile', ['id' => $user->id]); // Change this to the page you want
                        $freeTest = Questionnaire::where('email', $validated['email'])->first();

                        if($freeTest) {
                            \Mail::to($validated['email'])->send(new \App\Mail\FreeTestResultMail($user));
                        }
                        return response()->json([
                            'success' => true,
                            'redirect_url' => $redirectUrl,
                            'message' => 'Login successful.',
                            'user' => $user
                        ]);
                    }
            
                    Auth::logout();
                    // return back()->withErrors(['Unauthorized access for this role.']);
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access for this role.',
                    ], 401);
                } // Auth::login($user);
    
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You have not purchased any plan yet. Please purchase a plan first.',
                ], 401);
            }
        }

        // If user doesn't exist or password doesn't match
        return response()->json([
            'success' => false,
            'message' => 'Oops! Your email or password is incorrect. Please try again.',
        ], 401);
    }

    // Logout for admin users
    public function logout(Request $request)
    {
        // Check if the user is an admin
        if (Auth::user() && Auth::user()->is_superadmin == 0) {
            // Logout the admin
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the CSRF token
            $request->session()->regenerateToken();

            // Redirect to the admin login page
            return redirect()->route('front.index');
        }

        // If not admin, redirect to home
        return redirect()->route('front.index')->with('error', 'Unauthorized access.');
    }

    public function getProfileDetails(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if($request->ajax()) {

            return response()->json([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_image' => $user->profile_image ? asset('private/public/'.$user->profile_image) : null,
            ]);

        } else {

            $purchasedplans = Payment::where('user_id', $user->id)->pluck('plan_id')->toArray();
            if(!$purchasedplans) {
                $purchasedplans = [];
            }
            $plans = Plan::all();
            $payment = Payment::where('user_id', $user->id)->first();
            $preplanDetails = [];

            if(!$payment) {
                return redirect()->back()->with('error', 'You have not purchased any plan yet. Please purchase a plan first.');
            }

            $userPrePlan = UserPrePlan::where('user_id', $user->id)->where('payment_id', $payment->id)->first();

            $prePlans = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) { 
                $query->where('form_slug', 'physical_measures')
                        ->whereIn('question', ['Height (cm):', 'Current body weight (kg) (if known):']); 
            }])->where('user_id', $user->id)->get();

           // Extracting prePlanDetails (Height and Current body weight)
            $preplanDetails = [];

            foreach ($prePlans as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $preplanDetails[] = [
                        $detail->question => $detail->answer ,
                    ];
                }
            }
            
            $physicalMeasures = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) { 
                $query->where('form_slug', 'physical_measures')
                        ->whereIn('question', ['Height (cm):', 'Current body weight (kg) (if known):']); 
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
    
            // Extracting prePlanDetails (Height and Current body weight)
            $profileDetails = [
                'Name' => $user->name,
                'Profile Image' => $user->profile_image ?? '', // Ensure there's a fallback image
            ];
        
            foreach ($physicalMeasures as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $profileDetails[$detail->question] = trim($detail->answer, '"');
                }
            }
    
            $nutritionGoals = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
                $query->where('form_slug', 'nutrition_goals')
                        ->whereIn('question', ['Which of the following nutrition related goals are you interested in working on?','What is your biggest nutrition challenge?']); 
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
    
            $nutritionGoalsDetails = [];
            foreach ($nutritionGoals as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $decodedAnswer = json_decode($detail->answer, true); // Convert JSON string to array
                    if (is_array($decodedAnswer)) {
                        $nutritionGoalsDetails[$detail->question] = implode(', ', $decodedAnswer); // Convert array to string
                    } else {
                        $nutritionGoalsDetails[$detail->question] = trim($detail->answer, '"'); // Keep as it is if not an array
                    }
                }
            }
    
            $intakeDetails = [];
            $medicalHistories = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
                $query->where('form_slug', 'medical_history')
                        ->whereIn('question', ['List any dietary vitamins or supplements you are currently taking (if any):', 'Provide details of any prescription medications (if taking any):']);
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
    
            foreach ($medicalHistories as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $intakeDetails[$detail->question] = [
                        'answer'     => trim($detail->answer, '"'),
                        'start_date' => $detail->start_date ?? $detail->created_at->format('Y-m-d'),
                        'end_date'   => $detail->end_date ?? null,

                    ];
                }
            }
    
            $diateryDetails = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
                $query->where('form_slug', 'dietary_information')
                        ->whereIn('question', ['List your favourite foods?', 'Do you avoid/dislike any foods? List below']);
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
    
            foreach ($diateryDetails as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $intakeDetails[$detail->question] = [
                        'answer'     => trim($detail->answer, '"'),
                        'start_date' => $detail->start_date ?? $detail->created_at->format('Y-m-d'),
                        'end_date'   => $detail->end_date ?? null,
                    ];
                }
            }

            $trainingIntencity = [];
            $trainingDetails = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
                $query->where('form_slug', 'physical_activity_and_exercise')
                        ->where('question', 'How many days per week and at what intensity do you normally train for your sport?');
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
            foreach($trainingDetails as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $trainingIntencity[] = json_decode($detail->answer,true);
                }
            }

            $reports = [];

            $prePlanReports = \App\Models\UserPrePlan::with(['PrePlanQuesionFile' => function($query) {
                $query->whereIn('form_slug', ['physical_measures', 'medical_history']);
            }])->where('user_id', $user->id)
            ->where('payment_id', $payment->id)
            ->get();

            foreach($prePlanReports as $prePlan) {
                foreach ($prePlan->PrePlanQuesionFile as $detail) {
                    $reports[$detail->form_slug][] = [
                        'file_path' => asset('private/storage/app/public/' . $detail->file_path),
                        'report_name' => $detail->file_name,
                        'date' => \Carbon\Carbon::parse($detail->created_at)->format('d-m-Y')
                    ];
                }
            }

            // dd($intakeDetails);
            return view ('front.profile', compact('user', 'purchasedplans', 'plans', 'preplanDetails', 'profileDetails', 'nutritionGoalsDetails', 'intakeDetails', 'trainingIntencity','reports','userPrePlan'));
        }
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->user_id);

        // Validate the request
        // $request->validate([
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        //     'password' => 'nullable|min:8',
        //     'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        // ]);

        // // Update user details
        // $user->name = ucfirst($request->first_name) . ' ' . ucfirst($request->last_name);
        // $user->first_name = $request->first_name;
        // $user->last_name = $request->last_name;
        // $user->email = $request->email;

        // // Check if password is provided and update it
        // if ($request->filled('password')) {
        //     $user->password = \Hash::make($request->password);
        // }

        // Handle profile image upload
        // if ($request->hasFile('profile_image')) {
        //     // Delete the old profile image if it exists
        //     if ($user->profile_image) {
        //         \Storage::delete($user->profile_image);
        //     }

        //     // Store the new image
        //     $imagePath = $request->file('profile_image')->store('profile_images','public');
        //     $user->profile_image = $imagePath;
        // }
        if ($request->filled('name')) {
            $user->name = $request->name;
            $user->first_name = explode(' ', $request->name)[0] ?? ('');
            $user->last_name = explode(' ', $request->name)[1] ?? ('');
        }

        if ($request->hasFile('profile_image')) {
            // Handle the file upload and store it in the 'public/uploads/profile_images' directory
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/profile_images/' . $fileName;
            $directoryPath = public_path('uploads/profile_images');
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }
    
            // Move the file to the directory
            $file->move($directoryPath, $fileName);
        
            // Optionally delete the old image if it exists
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }
        
            // Save the new profile image path in the database
            $user->profile_image = $filePath;
        }

        // Save the user
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user' => $user,
        ]);
    }

    public function getCompetitionPlanDetails($id)
    {
        if (!Auth::user()) {
            return redirect()->route('front.sub-home-page');
        }

        $user = User::findOrFail($id);

        $userPlans = UserPlan::with('plan', 
            'userMealTimes.userCategories.userMeals.userItems')
            ->where('user_id', $id) // Ensure user_id is always applied
            ->get();
        $prePlanDetails = [];
        $preplan = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
            $query->where('form_slug', 'physical_measures');
        }])->where('user_id', $id)->first();
        //    dd($preplan->prePlanDetails);
        return view('front.competition-plan.index', compact('userPlans', 'user'));

    }

    public function getAllMeals()
    {
        // Assuming you have a relationship `items` defined on the `Meal` model
        
        $meals = \App\Models\Meal::with('items','items.category','userMealItems')->get();

        // $userPlans = UserPlan::with('plan', 
        //     'userMealTimes.userCategories.userMeals.userItems')
        //     ->where('user_id', Auth::user()->id) // Ensure user_id is always applied
        //     ->get();
        
        // $selectedItems = []; // To store pre-selected user items
        // foreach ($userPlans as $userPlan) {
        //     foreach ($userPlan->userMealTimes as $mealTime) {
        //         foreach ($mealTime->userMeals as $userMeal) {
        //             $mealId = $userMeal->meal_id;
        //             // Store user items
        //             $selectedItems[$mealId] = $userMeal->userItems->pluck('item_id')->toArray();
        //         }
        //     }
        // }

        // Return JSON response
        return response()->json([
            'meals' => $meals,
            // 'selectedItems' => $selectedItems,
        ]);
    }

    public function freeTestSave(Request $request)
    {
        // Validate the incoming test data
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id', // Ensure user ID exists in the database
            'testData' => 'required|array', // Test data should be an array
            // 'email' => 'required', // Test data should be an array
            'totalAnswerCount' => 'required|array', // Ensure total counts are an array
        ]);

        // If validation fails, return a 422 error with validation messages
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Retrieve the user by ID
        $user = User::find($request->userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $existingSubmission = Questionnaire::where('email', $user->email)->first();

        if ($existingSubmission) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted the test.'
            ], 400);
        }

        $nutritionScore  = $request->totalAnswerCount['nutrition-form'] ?? 0;
        $sportsScore     = $request->totalAnswerCount['sports-form'] ?? 0;
        $supplementScore = $request->totalAnswerCount['supplement-form'] ?? 0;

        // Generate feedback based on score ranges
        $nutritionFeedback  = $this->getFeedbackMessage($nutritionScore, 'nutrition-form');
        $sportsFeedback     = $this->getFeedbackMessage($sportsScore, 'sports-form');
        $supplementFeedback = $this->getFeedbackMessage($supplementScore, 'supplement-form');

        $user->nutrition_score      = $nutritionScore;
        $user->nutrition_feedback   = $nutritionFeedback;
        $user->sports_score         = $sportsScore;
        $user->sports_feedback      = $sportsFeedback;
        $user->supplement_score     = $supplementScore;
        $user->supplement_feedback = $supplementFeedback;
        $user->save();

        // Loop through the test data and insert each question and answer into the `questionnaire` table
        foreach ($request->testData as $question => $answer) {
            // dd($question);
            $questionnaire = new Questionnaire();
            $questionnaire->user_id = $user->id;
            $questionnaire->name    = $user->name;
            $questionnaire->email   = $user->email;
            $questionnaire->phone   = $request->phone;  // Assuming 'phone' is part of the user
            $questionnaire->question = $question;  // Store the question text
            $questionnaire->answer   = json_encode($answer);      // Store the corresponding answer
            $questionnaire->save(); // Save the data to the table
        }

        // Return success response
        return response()->json(['success' => true, 'message' => 'Test data submitted successfully']);
    
    }

    private function getFeedbackMessage($score, $category)
    {
        switch ($category) {
            case 'nutrition-form': // Score out of 35
                if ($score <= 19) return 'Needs work';
                if ($score <= 25) return 'Pretty ordinary';
                if ($score <= 31) return 'Not bad';
                // if ($score <= 35) return 'Good';
                return 'Good';

            case 'sports-form': // Score out of 9
                if ($score <= 4) return 'Untapped potential';
                if ($score <= 8) return 'Much to learn';
                if ($score <= 11) return 'Ok';
                return 'Good start';

            case 'supplement-form': // Score out of 6
                if ($score <= 2) return 'Likely at risk';
                if ($score <= 3) return 'Pretty ordinary';
                if ($score <= 4) return 'Decent';
                return 'Nice';

            default:
                return 'No feedback available';
        }
    }

    public function updateFoodQuantity(Request $request)
    {
        // dd($request->all());
        $userItem = \App\Models\UserItem::where('id', $request->user_item_id)
                                ->first();

        $userItem->qty = $request->qty;
        $userItem->save();

        $userMeal = \App\Models\UserMeal::with('userItems')->where('id',$userItem->user_meal_id)->first();
        $userPlan = \App\Models\UserPlan::where('id', $userMeal->user_plan_id)->where('status', 'active')->first();
        $userItemMeal = \App\Models\UserItemMeal::where('user_id', $userPlan->user_id)->where('meal_id', $userMeal->meal_id)->where('item_id', $userItem->item_id)->first();

        $userItemMeal->qty = $request->qty;
        $userItemMeal->save();

        return response()->json([
            'success' => true,
            'message' => 'Food quantity updated successfully!',
            'userItem' => $userItem,
        ]);
    }

    public function validateCouponCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'plan_id' => 'nullable|exists:plans,id',
        ]);
    
        $promoCode = $request->input('code');
        $planId = $request->input('plan_id'); // Get the plan ID
        $currentDateTime = \Carbon\Carbon::now();
    
        // Fetch the coupon with active status and matching code
        $coupon = \App\Models\Coupon::where('code', $promoCode)
            ->where('status', 1) // Active status
            ->first();
    
        if ($coupon) {
            // Check if the coupon is within the valid date range
            if ($currentDateTime->lt($coupon->start_date) || $currentDateTime->gt($coupon->end_date)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Coupon is not valid at this time.',
                ]);
            }
            
            // Check if the coupon is applicable to the selected plan
            $isPlanApplicable = $coupon->plans()->where('plans.id', $planId)->exists();
            if (!$isPlanApplicable) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This coupon is not applicable to the selected plan.',
                ]);
            }
            
            // Check the max_uses limit
            if ($coupon->max_uses > 0 && $coupon->max_uses <= $coupon->usage_count) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Coupon usage limit has been reached.',
                ]);
            }
    
            // // Check uses_per_user limit
            if(Auth::user() && !Auth::user()->isSuperAdmin()) {
                $userUsageCount = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
                    ->where('user_id', $request->user()->id)
                    ->count();
        
                if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'You have already used this coupon.',
                    ]);
                }
            }
    
            // Coupon is valid
            return response()->json([
                'valid' => true,
                'type' => $coupon->type,
                'discount' => $coupon->value,
            ]);
        }
    
        // If no valid coupon was found
        return response()->json([
            'valid' => false,
            'message' => 'Invalid coupon code.',
        ]);
    }
    
    public function fetchWeightData(Request $request)
    {
        $userId = $request->user_id;
        // Fetch physical measure weight from pre plan details
        $physicalMeasures = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
            $query->where('form_slug', 'physical_measures')
                ->where('question', 'Current body weight (kg) (if known):');
        }])->where('user_id', $userId)->first();

        // Extract the answer if available
        $prePlanWeight = optional($physicalMeasures->prePlanDetails->first())->answer ?? null;

        $physicalMeasures = \App\Models\UserPrePlan::with(['prePlanDetails' => function($query) {
            $query->where('form_slug', 'physical_measures')
                ->where('question', 'Current body weight (kg) (if known):');
        }])->where('user_id', $userId)->first();

        // Extract the answer if available
        $prePlanWeight = optional($physicalMeasures->prePlanDetails->first())->answer ?? null;

        $weightData = WeightTracking::where('user_id', $userId)
            ->latest('date')
            ->first(['weight', 'weight_goal', 'date']); // Fetch the latest entry
       
        return response()->json([
            'latest_weight_tracking' => $weightData,
            'current_weight' => $prePlanWeight,
        ]);    
    }

    public function saveWeight(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'date' => 'required|date',
            'user_id' => 'required|integer',
        ]);

       // Check for an existing record
        $existingRecord = WeightTracking::where('user_id', $request->user_id)
        ->where('date', $request->date)
        ->first();

        if ($existingRecord) {
            // Update the existing record
            $existingRecord->update([
                'weight' => $request->weight,
                'weight_goal' => $request->weight_goal,
            ]);

            return response()->json(['success' => true, 'message' => 'Weight updated successfully']);

        } else {
            // Create a new record if none exists
            WeightTracking::create([
                'user_id' => $request->user_id,
                'weight' => $request->weight,
                'weight_goal' => $request->weight_goal,
                'date' => $request->date,
            ]);

            return response()->json(['success' => true, 'message' => 'Weight recorded successfully']);
        }
    }

    // public function fetchWeights(Request $request)
    // {
    //     $filter = $request->filter; // e.g., '1W', '1M', etc.
    //     $userId = $request->user_id; 
    //     $startDate = now(); // Current date as the end of the range
    //     $endDate = null;    // To calculate the starting point of the range
    
    //     // Determine the date range based on the filter
    //     switch ($filter) {
    //         case '1W':
    //             $endDate = now()->subWeek();
    //             break;
    //         case '2W':
    //             $endDate = now()->subWeek(2);
    //             break;
    //         case '1M':
    //             $endDate = now()->subMonth();
    //             break;
    //         case '3M':
    //             $endDate = now()->subMonths(3);
    //             break;
    //         case '6M':
    //             $endDate = now()->subMonths(6);
    //             break;
    //         case '1Y':
    //             $endDate = now()->subYear();
    //             break;
    //         case 'ALL':
    //             $endDate = null; // For "ALL", no end date filter is applied
    //             break;
    //         default:
    //             return response()->json(['error' => 'Invalid filter'], 400);
    //     }
    
    //     $weight = WeightTracking::where('user_id', $userId)
    //         ->when($endDate, function ($query) use ($startDate, $endDate) {
    //             return $query->whereBetween('date', [$endDate, $startDate]);
    //         })
    //         ->orderBy('date', 'asc')
    //         ->get(['date', 'weight','weight_goal']);

    //     // Fetch weights between the calculated date range
    //     $weights = WeightTracking::where('user_id', $userId)
    //     ->when($endDate, function ($query) use ($startDate, $endDate) {
    //         return $query->whereBetween('date', [$endDate, $startDate]);
    //     })
    //     ->orderBy('date', 'asc')
    //     ->get(['date', 'weight'])
    //     ->groupBy(function ($item) {
    //         return \Carbon\Carbon::parse($item->date)->format('F'); // Group by month name
    //     })
    //     ->map(function ($items, $month) {
    //         return [
    //             'month' => $month,
    //             'weights' => $items->map(function ($item) {
    //                 return [
    //                     'date' => \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
    //                     'weight' => $item->weight,
    //                 ];
    //             }),
    //             // 'average_weight' => $items->avg('weight'), // Average weight for the month
    //         ];
    //     })
    //     ->values();
    
    //     // Get the start and goal weight
    //     $startWeight = $weight->first()->weight;
    //     $goalWeight = $weight->last()->weight_goal; // Use the 'weight_goal' field from the last record
    //     if($goalWeight > $startWeight) {
    //         $weightDiff = $startWeight - $goalWeight; // Calculate the difference
    //     }else {
    //         $weightDiff = $goalWeight - $startWeight; // Calculate the difference
    //     }

    //     // Return all the necessary data for the chart and modal
    //     return response()->json([
    //         'success' => true,
    //         'filter' => $filter,
    //         'weights' => $weights,
    //         'start_weight' => $startWeight,
    //         'goal_weight' => $goalWeight,
    //         'weight_diff' => $weightDiff
    //     ]);
    // }
    
    public function fetchWeights(Request $request)
    {
        $filter = $request->filter; // e.g., '1W', '1M', etc.
        $userId = $request->user_id; 
        $startDate = now(); // Current date as the end of the range
        $endDate = null;    // To calculate the starting point of the range

        // Set the timezone to ensure consistency (you can replace 'UTC' with your local timezone if needed)
        $timezone = 'UTC'; // Change this to your desired timezone if necessary
        $startDate = $startDate->setTimezone($timezone)->startOfDay(); // Set timezone and strip time

        // Determine the date range based on the filter
        switch ($filter) {
            case '1W':
                $endDate = now()->subWeek();  // 1 week ago from today
                break;
            case '2W':
                $endDate = now()->subWeeks(2);
                break;
            case '1M':
                $endDate = now()->subMonth();
                break;
            case '3M':
                $endDate = now()->subMonths(3);
                break;
            case '6M':
                $endDate = now()->subMonths(6);
                break;
            case '1Y':
                $endDate = now()->subYear();
                break;
            case 'ALL':
                $endDate = WeightTracking::where('user_id', $userId)->orderBy('date', 'asc')->value('date');
                $endDate = Carbon::parse($endDate)->setTimezone($timezone)->startOfDay(); // Ensure endDate has the correct timezone
                break;
            default:
                return response()->json(['error' => 'Invalid filter'], 400);
        }

        // Set the timezone for the endDate to ensure proper comparison
        $currentDate = $endDate->copy()->setTimezone($timezone)->startOfDay(); // Ensure $currentDate is in the same timezone and start of the day
        $allDates = collect();

        // Generate a complete list of dates between $endDate and $startDate
        while ($currentDate <= $startDate) {
            $allDates->push($currentDate->format('Y-m-d')); // Add date in 'Y-m-d' format
            $currentDate = $currentDate->addDay(); // Move to the next day
        }

        // Fetch weights from the database
        $weightsData = WeightTracking::where('user_id', $userId)
            ->when($endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$endDate, $startDate]);
            })
            ->orderBy('date', 'asc')
            ->get(['date', 'weight', 'weight_goal'])
            ->keyBy('date'); // Key by date for easy lookup
        // Map weights to the complete list of dates
        $allWeights = $allDates->map(function ($date) use ($weightsData) {
            return [
                'date' => \Carbon\Carbon::parse($date)->format('d/m/Y'), // Format for response
                'weight' => $weightsData->has($date) ? $weightsData[$date]->weight : null // Use null if no weight exists for the date
            ];
        });
        
        // Group by month for the response
        // $groupedWeights = $allWeights->groupBy(function ($item) {
        //     return \Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('F'); // Group by month name
        // })->map(function ($items, $month) {
        //     return [
        //         'month' => $month,
        //         'weights' => $items
        //     ];
        // })->values();
        $groupedWeights = $allWeights->groupBy(function ($item) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('F Y'); // Group by "Month Year"
        })->map(function ($items, $monthYear) {
            return [
                'month' => $monthYear, // Now includes both month and year
                'weights' => $items
            ];
        })->values();
        // dd($groupedWeights );
        // Calculate start and goal weights
        $startWeight = $weightsData->first() ? $weightsData->first()->weight : null;
        $goalWeight = $weightsData->last() ? $weightsData->last()->weight_goal : null;
    
        // Calculate weight difference
        $weightDiff = null;
        if ($startWeight !== null && $goalWeight !== null) {
            $weightDiff = abs($startWeight - $goalWeight);
        }
    
        // dd($groupedWeights);
        // Return all the necessary data for the chart and modal
        return response()->json([
            'success' => true,
            'filter' => $filter,
            'weights' => $groupedWeights,
            'start_weight' => $startWeight,
            'goal_weight' => $goalWeight,
            'weight_diff' => $weightDiff
        ]);
    }
    
    public function getSportsGames(Request $request) 
    {
        $category = $request->input('category'); // Get selected sport category

        if (!$category) {
            return response()->json(['error' => 'Invalid category'], 400);
        }

        // Get sports games from config/sports.php
        $sports = config('sports.' . $category, []);

        return response()->json($sports);
    }

    public function sportSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'sport' => 'required|string',
            'state' => 'required|string',
            'sport_game' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Save data to database
        $interest = new SportTracking();
        $interest->name = $request->name;
        $interest->email = $request->email;
        $interest->sport = ucwords(str_replace('_', ' ', $request->sport));;
        $interest->state = $request->state;
        $interest->sport_game = $request->sport_game;
        $interest->ip_address = $request->ip(); // Track user IP
        $interest->save();

        // Send email with sport-specific nutrition info
        Mail::to($request->email)->send(new SportInterestMail($interest));

        return response()->json(['message' => 'Thank you! We will send you relevant nutrition information.'], 200);
    }

    public function samplePlan(Request $request)
    {
        $isAuthenticated = "";

        $page = \App\Models\Page::with('sections')->where('slug', 'sample-plan')->first();
        
        if (!$page) {
            return redirect()->route('front.index')->with('error', 'Page not found.');
        }

        // $intakeDetails = array_merge($intakeDetails, $diateryDetail);
        return view('front.sample-plan', compact('page', 'isAuthenticated'));
    }

    public function updateSamplePlanDetails(Request $request)
    {
        $formName = $request->form_name;
        $answer = $request->answer;
        $question = $request->question;
        $userId = $request->user_id;
        $type = $request->type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $mainAns = $request->main_ans;
        // dd(json_encode($answer));
        $payment = Payment::where('user_id', $userId)->first();
        $prePlan = \App\Models\UserPrePlan::where('payment_id', $payment->id)
        ->where('user_id', $userId)
        ->first();
        // dd($prePlan);
        $prePlanDetail =  \App\Models\PrePlanDetail::where('form_slug', $formName)
                ->where('question', $question)
                ->where('user_pre_plan_id', $prePlan->id)
                ->first(); 

        if($prePlanDetail){
            if($type != "height") {
                GoalHistory::create([
                    'user_id' => $userId,
                    'payment_id' => $payment->id,
                    'type' => $type,
                    'question' => $prePlanDetail->question,
                    'answer' => $prePlanDetail->answer,
                    'start_date'=> $prePlanDetail->start_date,
                    'end_date' => $prePlanDetail->end_date
                ]);
            }
            
            $prePlanDetail->update([
                'answer' => json_encode($answer),
                'start_date' => $startDate,
                'end_date' => $endDate                     
            ]);

        } else {
            // Create a new record if none exists
            $userPrePlan = UserPrePlan::firstOrCreate([
                'user_id' => $userId,
                'payment_id' => $payment->id,
            ]);

            PrePlanDetail::create([
                'user_pre_plan_id' => $userPrePlan->id,
                'form_slug' => 'nutrition_goals',
                'question' => $question,
                'answer' => json_encode($answer),
                'start_date' => $startDate,
                'end_date' => $endDate   
            ]);
        }
        // // Ensure answer is stored as valid JSON
        // if (is_array($answer)) {
        //     $prePlanDetail->answer = json_encode([$answer], JSON_UNESCAPED_UNICODE);
        // } else {
        //     $prePlanDetail->answer = json_encode($answer, JSON_UNESCAPED_UNICODE);
        // }

        // $prePlanDetail->save();
    
        return response()->json(['success' => true, 'message' => 'Answer updated successfully']);
    }

    public function updateGoals(Request $request)
    {
        $userId = $request->user_id;
        $type = $request->input('type'); // "goal" or "challenge"
        $question = $type == "goal" ? 
            "Which of the following nutrition related goals are you interested in working on?" : 
            "What is your biggest nutrition challenge?";
        
        $answer = $request->input('answer'); // New answer input
        $payment = \App\Models\Payment::where('user_id', $userId)->first();

        // Find the latest record
        $prePlanDetail = PrePlanDetail::where('form_slug', 'nutrition_goals')
            ->where('question', $question)
            ->whereHas('userPrePlan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();

        // Move old record to history if exists
        if ($prePlanDetail) {
            GoalHistory::create([
                'user_id' => $userId,
                'payment_id' => $payment->id,
                'type' => $type,
                'question' => $prePlanDetail->question,
                'answer' => $prePlanDetail->answer,
            ]);

            // Update with new record
            $prePlanDetail->update(['answer' => json_encode($answer)]);
        } else {
            // Create a new record if none exists
            $userPrePlan = UserPrePlan::firstOrCreate([
                'user_id' => $userId,
                'payment_id' => $payment->id,
            ]);

            PrePlanDetail::create([
                'user_pre_plan_id' => $userPrePlan->id,
                'form_slug' => 'nutrition_goals',
                'question' => $question,
                'answer' => json_encode($answer),
            ]);
        }

        return response()->json(['success' => true, 'message' => ucfirst($type) . ' updated successfully']);
    }

    public function getPastGoals(Request $request)
    {
        $userId = $request->user_id;
        $type = $request->input('type'); // "goal" or "challenge"
        $pastItems = GoalHistory::where('user_id', $userId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($pastItems);
    }

    public function submitQuery(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // Save to database
        $query = new Query();
        $query->name = $request->name;
        $query->email = $request->email;
        $query->mobile_number = $request->phone;
        $query->message = $request->message;
        $query->save();

        // Return JSON response
        return response()->json(['status' => 'success', 'message' => 'Query submitted successfully!']);
    }

    public function uploadReport(Request $request)
    {
        $request->validate([
            'file.*' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Validate multiple files
            'report_type' => 'required',
            'user_pre_plan_id' => 'required',
            'report_name' => 'required',
        ]);

        $reportType = $request->input('report_type');
        $reportName = $request->input('report_name');
        $userPrePlanId = $request->input('user_pre_plan_id');

        // Define the question based on report type
        $question = ($reportType == 'medical_history') 
            ? 'Have you recently had a blood test?' 
            : 'Have you recently undertaken a body composition assessment (measure of muscle, body fat)?';

        $uploadedFiles = []; // To store uploaded file details

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                // Store the file
                $path = $file->store('preplan_files', 'public');

                // Save to database
                $prePlanFile = \App\Models\PrePlanQuesionFile::create([
                    'user_pre_plan_id' => $userPrePlanId,
                    'form_slug' => $reportType,
                    'question' => $question,
                    'file_path' => $path,
                    'file_name' => $reportName,
                ]);

                // Add to response array
                $uploadedFiles[] = [
                    'file_path' => $path,
                    'data' => $prePlanFile,
                ];
            }
        }
        
        return response()->json([
            "message" => "Files uploaded successfully!",
            "uploaded_files" => $uploadedFiles
        ]);
    }

    public function deleteReport(Request $request)
    {
        $file = $request->input('file');
        
        // Path to the file in storage
        $filePath = storage_path('app/public/' . $file);

        // Check if file exists
        if (file_exists($filePath)) {
            // Delete the file
            unlink($filePath);

            // Optionally, delete the file record from the database
            DB::table('pre_plan_question_files')->where('file_path', 'like', '%'.$file)->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function checkGoogleLogin(Request $request) 
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Token is missing']);
        }
    
        // Verify token using Google's OAuth2 API
        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $token
        ]);
    
        if ($response->successful()) {
            $userData = $response->json();

            // Extract User Data
            $name = $userData['name'];
            $email = $userData['email'];
           
            $firstName = explode(' ', $name)[0]; // First name from full name
            $lastName = explode(' ', $name)[1]; // Last name from full name

            // Example: Storing in "users" table
            $user = \App\Models\User::updateOrCreate(
                ['email' => $email], // Search by email
                [
                    'name' => $name,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ]
            );

            return response()->json([
                'status' => 'logged_in',
                'user_id' => $user->id
            ]);

        } else {
            return response()->json(['status' => 'not_logged_in', 'user_id' => null]);
        }
    }

    public function unlockFreeTestResult(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'user_id' => $user->id,
                'message' => 'User found'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'user_id' => null,
                'message' => 'Your email is not registered'
            ]);
        }
    }
}