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
use App\Models\Questionnaire;
use App\Models\WeightTracking;
use App\Models\Payment;
use App\Models\SportTracking;
use App\Models\UserPrePlan;
use App\Models\PrePlanDetail;
use App\Models\GoalHistory;
use App\Mail\SportInterestMail;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;
use App\Mail\SportInterestMailAdmin;
use Illuminate\Support\Facades\Validator;
use App\Services\ActivityTracker;
use App\Models\TrackingType;
use App\Models\SportCategory;
use App\Models\UserItem;
use App\Models\UserItemMeal;
use App\Models\UserMeal;
use App\Models\Page;
use App\Models\Coupon;
use App\Models\UserCategory;
use App\Models\PrePlanQuesionFile;
use App\Models\Flag;
use App\Models\CouponUsage;

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
        $requirements = [];
    
        $disabledDay = json_encode([]);
       
        $organization = [];
        $testimonials = [];
        return view('front.pages.index', compact('requirements','disabledDay','organization','testimonials'));
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
        $blogs = Blog::where('is_published', 1)->get();
        return view('front.pages.blog.blog', compact('blogs'));
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

        return view('front.pages.blog.blog-details', compact('blog', 'relatedBlogs'));
    }

    public function subHomePage()
    {
        // Step 1: Get all sub_plan_ids from plan_sub_plans table
        $subPlanIds = \DB::table('plan_sub_plans')->pluck('sub_plan_id')->toArray();

        // Step 2: Retrieve all plans that are NOT sub-plans
        $plans = Plan::whereNotIn('id', $subPlanIds)->get();

        // dd($plans);
        $page = Page::with('sections')->where('slug', 'actionsport-nutrition-plan')->first();
        
        $requirements = [];
       
        $disabledDay = json_encode([]);
      
        $organization = [];
        $testimonials = [];
        $isAuthenticated = Auth::check(); // Returns true if the user is logged in
        $sportCategories = SportCategory::all();

        return view('front.pages.sub-home-page', compact('requirements','page', 'plans','disabledDay','organization','testimonials','isAuthenticated', 'sportCategories'));
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $firstName = explode(' ', $request->input('name'))[0]; // First name from full name
        $lastName = explode(' ', $request->input('name'))[1] ?? ''; // Last name from full name

        $existingUser = User::where('email', $request->input('email'))->first();

        if ($existingUser) {
            // $freeTest = Questionnaire::where('email', $request->input('email'))->first();
            // if ($freeTest) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You have already submitted the test.',
            //         'user' => $existingUser,
            //     ]);
            // }
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

        $click = ActivityTracker::click('user_account_create', $user->id);
        ActivityTracker::log(
            TrackingType::ACCOUNT_CREATED,$user->id,
            [   
                'email' => $user->email,
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'user_id' => $user->id,
            ]
         );

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please login.',
            'user' => $user,
        ]);
    }

    // Handle Login Request
    public function login(Request $request)
    {
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
                if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    if (!Auth::guard('web')->user()->isSuperAdmin()) {
                        $redirectUrl = route('front.profile', ['id' => $user->id]); // Change this to the page you want
                        $click = ActivityTracker::click('user_logged_in', $user->id);

                        // Log in trackings with click reference
                        ActivityTracker::log(TrackingType::USER_LOGGED_IN, $user->id, [
                            'user_click_id' => $click->id,
                            'section_element_id' => $click->section_element_id,
                            'user_id' => $user->id,
                            'login_time' => now()->toDateTimeString(),
                        ]);
                        return response()->json([
                            'success' => true,
                            'redirect_url' => $redirectUrl,
                            'message' => 'Login successful.',
                            'user' => $user
                        ]);
                    }
            
                    Auth::guard('web')->logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access for this role.',
                    ], 500);
                }
    
            } else {
                $redirectUrl = route('front.profile', ['id' => $user->id]);
                return response()->json([
                    'success' => 'success',
                    'redirect_url' => $redirectUrl,
                    'user' => $user,
                    'message' => 'Plan not purchased.',
                ]);
            }
        }

        // If user doesn't exist or password doesn't match
        return response()->json([
            'success' => false,
            'message' => 'Oops! Your email or password is incorrect. Please try again.',
        ], 500);
    }

    // Logout for admin users
    public function logout(Request $request)
    {
        // Only logout from web guard (frontend)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user(); // ✅ Define $user before using

            // Log the click and tracking BEFORE logout
            $click = ActivityTracker::click('link_logged_out', $user->id);

            ActivityTracker::log(TrackingType::USER_LOGGED_OUT, $user->id, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'logout_time' => now()->toDateTimeString(),
            ]);

            Auth::guard('web')->logout();

            // Invalidate only the web session
            $request->session()->forget('web');

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            return redirect()->route('front.index')->with('success', 'You have been logged out successfully.');
        }

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
                'profile_image' => $user->profile_image ? webAssets($user->profile_image) : null,
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
                return redirect()->back()->with('error', 'Plan not purchased.');
            }

            $userPrePlan = UserPrePlan::where('user_id', $user->id)->where('payment_id', $payment->id)->first();

            $prePlans = UserPrePlan::with(['prePlanDetails' => function($query) { 
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
            
            $physicalMeasures = UserPrePlan::with(['prePlanDetails' => function($query) { 
                $query->where('form_slug', 'physical_measures')
                        ->whereIn('question', ['Height (cm):', 'Current body weight (kg) (if known):']); 
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
    
            // Extracting prePlanDetails (Height and Current body weight)
            $profileDetails = [
                'Name' => $user->name,
                'Profile Image' => $user->profile_image ?? '', // Ensure there's a fallback image
                'Sport' => $userPrePlan->occupation ?? '',
            ];
        
            foreach ($physicalMeasures as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $profileDetails[$detail->question] = trim($detail->answer, '"');
                }
            }
    
            $nutritionGoals = UserPrePlan::with(['prePlanDetails' => function($query) {
                $query->where('form_slug', 'nutrition_goals')
                        ->whereIn('question', ['Which of these do you want help with?',"What's your biggest nutrition challenge?"]); 
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
            $medicalHistories = UserPrePlan::with(['prePlanDetails' => function($query) {
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
    
            $diateryDetails = UserPrePlan::with(['prePlanDetails' => function($query) {
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
            $trainingDetails = UserPrePlan::with(['prePlanDetails' => function($query) {
                $query->where('form_slug', 'physical_activity_and_exercise')
                        ->where('question', 'On average, how many days per week do you train, and at what intensity?');
            }])->where('user_id', $user->id)->where('payment_id', $payment->id)->get();
            foreach($trainingDetails as $prePlan) {
                foreach ($prePlan->prePlanDetails as $detail) {
                    $trainingIntencity[] = json_decode($detail->answer,true);
                }
            }

            $reports = [];

            $prePlanReports = UserPrePlan::with(['PrePlanQuesionFile' => function($query) {
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

            $profileSetUp = 0 ;
            if($userPrePlan) {
                $completedSteps = DB::table('pre_plan_details')
                ->where('user_pre_plan_id', $userPrePlan->id ?? null)
                ->max('step');
                
                if($completedSteps == 9) {
                    $profileSetUp = 1;
                }
            }

            $adminView = $request->input('admin_view') == 1 ? true : false;
            
            return view ('front.pages.profile', compact('user', 'purchasedplans', 'plans', 'preplanDetails', 'profileDetails', 'nutritionGoalsDetails', 'intakeDetails', 'trainingIntencity','reports','userPrePlan', 'payment', 'profileSetUp', 'adminView'));
        }
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->user_id);
        $rules = []; // Initialize the $rules array
        // dd($request->all());
        if ($request->has('name')) {
            $rules['name'] = 'string|max:255';
        }

        if ($request->type === 'profile_image') {
            $rules['profile_image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $sectionElement = null;
        if ($request->filled('name')) {
            $user->name = $request->name;
            $user->first_name = explode(' ', $request->name)[0] ?? ('');
            $user->last_name = explode(' ', $request->name)[1] ?? ('');

            $sectionElement = 'update_profile_name';
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

            $sectionElement = 'update_profile_image';
        }

        // Save the user
        $user->save();

        $click = ActivityTracker::click($sectionElement, $user->id);

        // Log in trackings with click reference
        ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $user->id, [
            'user_click_id' => $click->id,
            'section_element_id' => $click->section_element_id,
        ]);
        
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
        $preplan = UserPrePlan::with(['prePlanDetails' => function($query) {
            $query->where('form_slug', 'physical_measures');
        }])->where('user_id', $id)->first();

        return view('front.pages.competition-plan.index', compact('userPlans', 'user'));
    }

    public function getAllMeals(Request $request)
    {
        $userId = $request->user_id;

        $userPlan = UserPlan::with([
            'userCategories.category:id,title',
            'userCategories.userSubCategories.subCategory:id,title',
            'userCategories.userSubCategories.userMeals' => function ($q) use ($userId) {
                $q->with([
                    'meal' => function ($mealQuery) use ($userId) {
                        $mealQuery->with(['userMealItems' => function ($q2) use ($userId) {
                            $q2->wherePivot('user_id', $userId)
                                ->select('items.id', 'items.title', 'items.image', 'items.category_id')
                                ->withPivot('qty', 'unit', 'selected_qty_unit')
                                ->with('category:id,name');
                        }]);
                    },
                    'userItems.item' => function ($q3) {
                        $q3->select('id', 'title', 'image', 'category_id')->with('category:id,name');
                    }
                ]);
            }
        ])
        ->where('id', $request->user_plan_id)
        ->first();

        $result = [];

        foreach ($userPlan->userCategories as $mealTime) {
            $userCategoryId = $mealTime->id;

            foreach ($mealTime->userSubCategories->where('user_plan_id', $userPlan->id) as $category) {
                $userSubCategoryId = $category->id;

                foreach (
                    $category->userMeals
                        ->where('user_plan_id', $userPlan->id)
                        ->where('user_category_id', $userCategoryId)
                        ->where('user_sub_category_id', $userSubCategoryId)
                    as $userMeal
                ) {
                    $meal = $userMeal->meal;

                    $userItemMap = $userMeal->userItems
                        ->where('user_plan_id', $userPlan->id)
                        ->where('user_category_id', $userCategoryId)
                        ->where('user_sub_category_id', $userSubCategoryId)
                        ->pluck('id')
                        ->flip();

                    $items = [];
                    foreach ($meal->userMealItems as $mealItem) {
                        if (!$userItemMap->has($mealItem->id)) continue;

                        $items[] = [
                            'id' => $mealItem->id,
                            'title' => $mealItem->title,
                            'image' => $mealItem->image,
                            'qty' => $mealItem->pivot->qty,
                            'unit' => $mealItem->pivot->unit,
                            'selected_qty_unit' => $mealItem->pivot->selected_qty_unit,
                            'category' => $mealItem->category->name ?? null,
                        ];
                    }

                    if (count($items)) {
                        $result[] = [
                            'meal_time_id' => $mealTime->category->id,
                            'meal_time_title' => $mealTime->category->title,
                            'category_id' => $category->subCategory->id,
                            'category_title' => $category->subCategory->title,
                            'meal_id' => $meal->id,
                            'meal_title' => $meal->title,
                            'items' => $items
                        ];
                    }
                }
            }
        }

        return response()->json(['meals' => $result]);
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

        $nutritionScore  = $request->totalAnswerCount['nutrition-form'] ?? 0;
        $sportsScore     = $request->totalAnswerCount['sports-form'] ?? 0;
        $supplementScore = $request->totalAnswerCount['supplement-form'] ?? 0;

        // Generate feedback based on score ranges
        $nutritionFeedback  = $this->getFeedbackMessage($nutritionScore, 'nutrition-form');
        $sportsFeedback     = $this->getFeedbackMessage($sportsScore, 'sports-form');
        $supplementFeedback = $this->getFeedbackMessage($supplementScore, 'supplement-form');

        $questionnaire = new Questionnaire();
        $questionnaire->user_id = $user->id;
        $questionnaire->name    = $user->name;
        $questionnaire->email   = $user->email;
        $questionnaire->phone   = $request->phone;
        $questionnaire->question = 'free-test';
        $questionnaire->answer   = json_encode($request->testData);
        $questionnaire->nutrition_score      = $nutritionScore;
        $questionnaire->nutrition_feedback   = $nutritionFeedback;
        $questionnaire->sports_score         = $sportsScore;
        $questionnaire->sports_feedback      = $sportsFeedback;
        $questionnaire->supplement_score     = $supplementScore;
        $questionnaire->supplement_feedback = $supplementFeedback;
        $questionnaire->save();

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
        $userItem = UserItem::where('id', $request->user_item_id)->first();

        $userItem->qty = $request->qty;
        $userItem->save();

        $userMeal = UserMeal::with('userItems')->where('id',$userItem->user_meal_id)->first();
        $userPlan = UserPlan::where('id', $userMeal->user_plan_id)->where('status', 'active')->first();
        $userItemMeal = UserItemMeal::where('user_id', $userPlan->user_id)->where('meal_id', $userMeal->meal_id)->where('item_id', $userItem->item_id)->first();

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
        try {
            $request->validate([
                'code' => 'required|string|max:255',
                'plan_id' => 'nullable|exists:plans,id',
            ]);

            $promoCode = $request->input('code');
            $planId = $request->input('plan_id');
            $currentDateTime = \Carbon\Carbon::now();

            Log::info('Validating coupon code', [
                'code' => $promoCode,
                'plan_id' => $planId,
                'user_id' => optional($request->user())->id
            ]);

            $coupon = Coupon::where('code', $promoCode)
                ->where('status', 1)
                ->first();

            if (!$coupon) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid coupon code.',
                ]);
            }

            if ($currentDateTime->lt($coupon->start_date) || $currentDateTime->gt($coupon->end_date)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Coupon is not valid at this time.',
                ]);
            }

            $isPlanApplicable = $coupon->plans()->where('plans.id', $planId)->exists();
            if (!$isPlanApplicable) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This coupon is not applicable to the selected plan.',
                ]);
            }

            if ($coupon->max_uses > 0 && $coupon->usage_count >= $coupon->max_uses) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Coupon usage limit has been reached.',
                ]);
            }

            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                    ->where('user_id', Auth::id())
                    ->count();

                if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'You have already used this coupon.',
                    ]);
                }
            }

            if ($coupon->type === 'percentage' && $coupon->value == 100.00) {
                $discount = 'full';
                $sectionElement = 'coupon_full_discount';
                $couponType = TrackingType::FREE_PLAN_COUPON;
            } elseif ($coupon->type === 'percentage') {
                $discount = $coupon->value / 100;
                $sectionElement = 'coupon_percentage_discount';
                $couponType = TrackingType::COUPON_APPLIED;
            } elseif ($coupon->type === 'fixed') {
                $discount = $coupon->value;
                $sectionElement = 'coupon_fixed_discount';
                $couponType = TrackingType::COUPON_APPLIED;
            }

            $user = Auth::guard('web')->user();
            $userId = null;

            if($user) {
                $userId = $user->id;
            }

            // $click = ActivityTracker::click($sectionElement, $userId);

            // ActivityTracker::log($couponType, $userId, [
            //     'user_click_id' => $click->id,
            //     'section_element_id' => $click->section_element_id,
            //     'coupon_code' => $promoCode,
            //     'coupon_id' => $coupon->id,
            //     'discount' => $discount,
            //     'plan_id' => $planId,
            // ]);

            return response()->json([
                'valid' => true,
                'type' => $coupon->type,
                'discount' => $coupon->value,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            Log::error('Error validating coupon code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => optional($request->user())->id,
            ]);
            return response()->json([
                'valid' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
    public function fetchWeightData(Request $request)
    {
        $userId = $request->user_id;
        $physicalMeasures = UserPrePlan::with(['prePlanDetails' => function($query) {
            $query->where('form_slug', 'physical_measures')
                ->where('question', 'Current body weight (kg) (if known):');
        }])->where('user_id', $userId)->first();

        $prePlanWeight = optional($physicalMeasures->prePlanDetails->first())->answer ?? null;

        $physicalMeasures = UserPrePlan::with(['prePlanDetails' => function($query) {
            $query->where('form_slug', 'physical_measures')
                ->where('question', 'Current body weight (kg) (if known):');
        }])->where('user_id', $userId)->first();

        $prePlanWeight = optional($physicalMeasures->prePlanDetails->first())->answer ?? null;

        $weightData = WeightTracking::where('user_id', $userId)
            ->latest('date')
            ->first(['weight', 'weight_goal', 'date']);
       
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

        $existingRecord = WeightTracking::where('user_id', $request->user_id)
        ->where('date', $request->date)
        ->first();

        if ($existingRecord) {
            $existingRecord->update([
                'weight' => $request->weight,
                'weight_goal' => $request->weight_goal,
            ]);

            return response()->json(['success' => true, 'message' => 'Weight updated successfully']);

        } else {
            WeightTracking::create([
                'user_id' => $request->user_id,
                'weight' => $request->weight,
                'weight_goal' => $request->weight_goal,
                'date' => $request->date,
            ]);

            return response()->json(['success' => true, 'message' => 'Weight recorded successfully']);
        }

        $click = ActivityTracker::click('button_save_profile_weight', $request->user_id);

        ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $request->user_id, [
            'user_click_id' => $click->id,
            'section_element_id' => $click->section_element_id,
            'weight' => $request->weight,
            'weight_goal' => $request->weight_goal,
            'date' => $request->date,
            'user_id' => $request->user_id,
        ]);
    }

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
        
        $groupedWeights = $allWeights->groupBy(function ($item) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('F Y'); // Group by "Month Year"
        })->map(function ($items, $monthYear) {
            return [
                'month' => $monthYear, // Now includes both month and year
                'weights' => $items
            ];
        })->values();
        // Calculate start and goal weights
        $startWeight = $weightsData->first() ? $weightsData->first()->weight : null;
        $goalWeight = $weightsData->last() ? $weightsData->last()->weight_goal : null;
    
        // Calculate weight difference
        $weightDiff = null;
        if ($startWeight !== null && $goalWeight !== null) {
            $weightDiff = abs($startWeight - $goalWeight);
        }
    
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
        $categoryId = $request->input('category');

        if (!$categoryId) {
            return response()->json([], 400);
        }

        $category = SportCategory::with('games')->find($categoryId);
        if (!$category) {
            return response()->json([], 404);
        }

        // Return an array of games (id + name)
        $games = $category->games->map(function ($game) {
            return [
                'id' => $game->id,
                'name' => $game->name,
            ];
        });
        return response()->json($games);
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
        Mail::to(config('constants.admin_email'))->send(new SportInterestMailAdmin($interest));
        // Mail::to('kartikvadhaiya6656@gmail.com')->send(new SportInterestMailAdmin($interest));

        return response()->json(['message' => 'Thank you! We will send you relevant nutrition information.'], 200);
    }

    public function samplePlan(Request $request)
    {
        $isAuthenticated = "";

        $page = Page::with('sections')->where('slug', 'sample-plan')->first();
        
        if (!$page) {
            return redirect()->route('front.index')->with('error', 'Page not found.');
        }

        // $intakeDetails = array_merge($intakeDetails, $diateryDetail);
        return view('front.pages.sample-plan', compact('page', 'isAuthenticated'));
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
        $payment = Payment::where('user_id', $userId)->first();
        $prePlan = UserPrePlan::where('payment_id', $payment->id)
        ->where('user_id', $userId)
        ->first();
       
        $prePlanDetail =  PrePlanDetail::where('form_slug', $formName)
                ->where('question', $question)
                ->where('user_pre_plan_id', $prePlan->id)
                ->first(); 
        
        if($prePlanDetail){
            if ($type == 'supplement-edit' || $type == 'medication-edit') {
                $preplanAnswers = array_map('trim', explode(',', json_decode($prePlanDetail->answer)));
                $startDates = array_map('trim', explode(',', $prePlanDetail->start_date));
                $endDates = array_map('trim', explode(',', $prePlanDetail->end_date));
                $count = count($preplanAnswers);
            
                // If startDates are empty or contain all nulls, set all to created_at
                if (empty($prePlanDetail->start_date) || collect($startDates)->every(fn($date) => empty($date) || strtolower($date) === 'null')) {
                    $createdDate = $prePlanDetail->created_at->format('Y-m-d');
                    $startDates = array_fill(0, $count, $createdDate);
                } else {
                    $startDates = array_pad($startDates, $count, null);
                }
            
                $endDates = array_pad($endDates, $count, null);
                // Find index of the edited answer
                $index = array_search($answer, $preplanAnswers);
            
                if ($index !== false) {
                    if (!empty($startDate)) {
                        $startDates[$index] = $startDate;
                    }
            
                    if (!empty($endDate)) {
                        $endDates[$index] = $endDate;
                    }
                }
                
                $prePlanDetail->update([
                    'answer' => json_encode(implode(', ', $preplanAnswers)),
                    'start_date' => implode(', ', $startDates),
                    'end_date' => implode(', ', $endDates)
                ]);

                if($type == 'supplement-edit') {
                    $sectionElement = 'button_update_supplement';
                } elseif($type == 'medication-edit') {
                    $sectionElement = 'button_update_medications';
                }
                $click = ActivityTracker::click($sectionElement, $request->user_id);

                ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $request->user_id, [
                    'user_click_id' => $click->id,
                    'section_element_id' => $click->section_element_id,
                    'type' => $type,
                    'question' => $question,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'main_ans' => $mainAns,
                    'form_name' => $formName,
                    'payment_id' => $payment->id,
                    'user_pre_plan_id' => $prePlan->id,
                    'user_id' => $request->user_id,
                ]);
                
            }elseif ($type == 'supplement' || $type == 'medication') {
               
                $preplanAnswers = array_map('trim', explode(',', json_decode($prePlanDetail->answer)));
                $startDates = array_map('trim', explode(',', $prePlanDetail->start_date));
                $endDates = array_map('trim', explode(',', $prePlanDetail->end_date));
            
                $answer = trim($request->answer);
                $startDate = $request->start_date;
                $endDate = $request->end_date;
            
                $currentDate = now()->format('Y-m-d');
                
                foreach ($preplanAnswers as $index => $item) {
                    $itemEndDate = $endDates[$index] ?? null;
                   
                    if ($itemEndDate && $itemEndDate < $currentDate) {
                        // Archive expired item in GoalHistory
                        GoalHistory::create([
                            'user_id' => $userId,
                            'payment_id' => $payment->id,
                            'type' => $type,
                            'question' => $prePlanDetail->question,
                            'answer' => $item,
                            'start_date' => $startDates[$index] ?? $prePlanDetail->created_at,
                            'end_date' => $itemEndDate
                        ]);
            
                        // Remove from arrays
                        unset($preplanAnswers[$index]);
                        unset($startDates[$index]);
                        unset($endDates[$index]);
                    }
                }
            
                // Reindex arrays
                $preplanAnswers = array_values($preplanAnswers);
                $startDates = array_values($startDates);
                $endDates = array_values($endDates);
            
                // Add new entry
                $preplanAnswers[] = $answer;
                $startDates[] = $startDate ?? $prePlanDetail->created_at;
                $endDates[] = $endDate ?? null;
            
                $prePlanDetail->update([
                    'answer' => json_encode(implode(', ', $preplanAnswers)),
                    'start_date' => implode(', ', $startDates),
                    'end_date' => implode(', ', $endDates)
                ]);

                if($type == 'supplement') {
                    $sectionElement = 'button_save_supplement';
                } elseif($type == 'medication') {
                    $sectionElement = 'button_save_medications';
                }
                $click = ActivityTracker::click($sectionElement, $request->user_id);

                ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $request->user_id, [
                    'user_click_id' => $click->id,
                    'section_element_id' => $click->section_element_id,
                    'type' => $type,
                    'question' => $question,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'answer' => $answer,
                    'form_name' => $formName,
                    'payment_id' => $payment->id,
                    'user_pre_plan_id' => $prePlan->id,
                    'user_id' => $request->user_id,
                ]);
            }elseif ($type == 'height') {
                
                $prePlanDetail->update([
                    'answer' => json_encode($answer),
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);
                $click = ActivityTracker::click('button_update_height', $request->user_id);

                ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $request->user_id, [
                    'user_click_id' => $click->id,
                    'section_element_id' => $click->section_element_id,
                    'type' => $type,
                    'question' => $question,
                    'answer' => $answer,
                    'form_name' => $formName,
                    'payment_id' => $payment->id,
                    'user_pre_plan_id' => $prePlan->id,
                    'user_id' => $request->user_id,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);
            }
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

            $click = ActivityTracker::click('button_save_nutrition_goal', $request->user_id);

            ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $request->user_id, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'type' => $type,
                'question' => $question,
                'answer' => $answer,
                'form_name' => 'nutrition_goals',
                'payment_id' => $payment->id,
                'user_pre_plan_id' => $userPrePlan->id,
                'user_id' => $request->user_id,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }
       
        return response()->json(['success' => true, 'message' => 'Answer updated successfully']);
    }

    public function updateGoals(Request $request)
    {
        $userId = $request->user_id;
        $type = $request->input('type');
        $question = $type == "goal" ? 
            "Which of these do you want help with?" : 
            "What's your biggest nutrition challenge?";
        
        $answer = $request->input('answer');
        $payment = Payment::where('user_id', $userId)->first();

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
                'form_name' => 'Nutrition Goals',
                'user_pre_plan_id' => $userPrePlan->id,
                'form_slug' => 'nutrition_goals',
                'question' => $question,
                'answer' => json_encode($answer),
            ]);
        }

        if($type == 'goal') {
            $sectionElement = 'button_save_nutrition_goals';
        } else {
            $sectionElement = 'button_save_nutrition_challenges';
        }

        $click = ActivityTracker::click($sectionElement, $request->user_id);

        ActivityTracker::log(TrackingType::PROFILE_DETAILS_EDIT, $request->user_id, [
            'user_click_id' => $click->id,
            'section_element_id' => $click->section_element_id,
            'type' => $type,
            'question' => $question,
            'answer' => json_encode($answer),
            'date' => $request->date,
            'user_id' => $request->user_id,
        ]);

        return response()->json(['success' => true, 'message' => ucfirst($type) . ' updated successfully']);
    }

    public function getPastGoals(Request $request)
    {
        $userId = $request->user_id;
        $type = $request->input('type');
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
            'file.*' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
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
                $prePlanFile = PrePlanQuesionFile::create([
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
            $user = User::updateOrCreate(
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

        $user = User::where('email', $request->email)->first();

        if ($user) {

            $nutritionScore  = $request->totalAnswerCounts['nutrition-form'] ?? 0;
            $sportsScore     = $request->totalAnswerCounts['sports-form'] ?? 0;
            $supplementScore = $request->totalAnswerCounts['supplement-form'] ?? 0;

            // Generate feedback based on score ranges
            $nutritionFeedback  = $this->getFeedbackMessage($nutritionScore, 'nutrition-form');
            $sportsFeedback     = $this->getFeedbackMessage($sportsScore, 'sports-form');
            $supplementFeedback = $this->getFeedbackMessage($supplementScore, 'supplement-form');

            $questionnaire = new Questionnaire();
            $questionnaire->user_id = $user->id;
            $questionnaire->name    = $user->name;
            $questionnaire->email   = $user->email;
            $questionnaire->phone   = $request->phone;
            $questionnaire->question = 'free-test';
            $questionnaire->answer   = json_encode($request->testData);
            $questionnaire->nutrition_score      = $nutritionScore;
            $questionnaire->nutrition_feedback   = $nutritionFeedback;
            $questionnaire->sports_score         = $sportsScore;
            $questionnaire->sports_feedback      = $sportsFeedback;
            $questionnaire->supplement_score     = $supplementScore;
            $questionnaire->supplement_feedback = $supplementFeedback;
            $questionnaire->save();

            return response()->json([
                'status' => 'success',
                'user_id' => $user->id,
                'message' => 'User found'
            ]);
        } else {
            $firstName = explode(' ', $request->input('name'))[0]; // First name from full name
            $lastName = explode(' ', $request->input('name'))[1] ?? ''; // Last name from full name

            $user = User::create([
                'name' => $request->input('name'), // Full name of the admin user.
                'first_name' => $firstName, // First name of the admin user.
                'last_name' => $lastName, // Last name of the admin user.
                'email' => $request->input('email'), // Email of the admin user.
                'password' => Hash::make($request->input('password')), // Hashed password of the admin user.
            ]);
            
            $nutritionScore  = $request->totalAnswerCounts['nutrition-form'] ?? 0;
            $sportsScore     = $request->totalAnswerCounts['sports-form'] ?? 0;
            $supplementScore = $request->totalAnswerCounts['supplement-form'] ?? 0;

            // Generate feedback based on score ranges
            $nutritionFeedback  = $this->getFeedbackMessage($nutritionScore, 'nutrition-form');
            $sportsFeedback     = $this->getFeedbackMessage($sportsScore, 'sports-form');
            $supplementFeedback = $this->getFeedbackMessage($supplementScore, 'supplement-form');

            $questionnaire = new Questionnaire();
            $questionnaire->user_id = $user->id;
            $questionnaire->name    = $user->name;
            $questionnaire->email   = $user->email;
            $questionnaire->phone   = $request->phone;
            $questionnaire->question = 'free-test';
            $questionnaire->answer   = json_encode($request->testData);
            $questionnaire->nutrition_score      = $nutritionScore;
            $questionnaire->nutrition_feedback   = $nutritionFeedback;
            $questionnaire->sports_score         = $sportsScore;
            $questionnaire->sports_feedback      = $sportsFeedback;
            $questionnaire->supplement_score     = $supplementScore;
            $questionnaire->supplement_feedback = $supplementFeedback;
            $questionnaire->save();

            return response()->json([
                'status' => 'success',
                'user_id' => $user->id,
                'message' => 'User found'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'user_id' => null,
            'message' => 'Something went wrong, please try again later.'
        ]);
    }

    public function getFoodItems($key)
    {
        // Find flag by name
        $flag = Flag::where('name', $key)->with('items')->first();

        if (!$flag) {
            return response()->json([]);
        }

        // Extract item names as a simple array
        $items = $flag->items->map(function ($item) {
            return [
                'name' => $item->title,
                'image' => webAssets('storage/' . $item->image)
            ];
        });
    
        return response()->json($items);
    }

    public function setUserSession($id)
    {
        // Optional: restrict only for admin if needed
        // if (!Auth::guard('admin')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        $user = User::findOrFail($id);
        // dd($user);
        // Set user session
        Auth::guard('web')->login($user);

        // Respond with URL instead of redirect
        return response()->json([
            'success' => true,
            'redirect_url' => route('front.profile', ['id' => $id]) . '?admin_view=1'
        ]);
    }

    public function updateSport(Request $request)
    {
        $request->validate([
            'sport' => 'required|string|max:255',
            'sport_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
    
        $userPrePlan = UserPrePlan::where('user_id', $request->user_id)->where('payment_id', $request->payment_id)->first();
        
        $userPrePlan->occupation = $request->sport;

        if ($request->hasFile('sport_image')) {
            $file = $request->file('sport_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/sport_images/' . $fileName;
            $directoryPath = public_path('uploads/sport_images');

            // Create directory if not exists
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }

            // Move new image
            $file->move($directoryPath, $fileName);

            // Delete old image if exists
            if ($userPrePlan->sport_image && file_exists(public_path($userPrePlan->sport_image))) {
                unlink(public_path($userPrePlan->sport_image));
            }

            // Save new image path
            $userPrePlan->sport_image = $filePath;
        }
        $userPrePlan->save();

        return response()->json([
            'success' => true,
            'message' => 'Sport updated successfully!',
            'userPrePlan' => $userPrePlan,
        ]);
    }

    public function getProfile(Request $request, $userId)
    {
        try {
            $paymentId = Payment::where('user_id', $userId)->value('id');

            if (!$paymentId) {
                return redirect()->back()->with('error', 'Plan not purchased.');
            }

            $userPlan = UserPlan::with([
                'plan',
                'userCategories.userSubCategories.userMeals.userItems'
            ])
            ->where('user_id', $userId)
            ->first();

            return view('front.pages.profile-landing', compact('userPlan'));
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching user profile: ' . $e->getMessage());

            // Redirect back with a generic error message
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function getMeals($planId, $categoryId)
    {
        $userCategory = UserCategory::where('user_plan_id', $planId)
            ->where('id', $categoryId)
            ->first();

        if (!$userCategory) {
            return '<p>No meals found.</p>';
        }

        $meals = [];

        foreach ($userCategory->userSubCategories->where('user_plan_id', $planId) as $subCategory) {
            foreach ($subCategory->userMeals->where('user_plan_id', $planId)
                        ->where('user_category_id', $userCategory->id)
                        ->where('user_sub_category_id', $subCategory->id) as $meal) {
                if (count($meals) < 3) {
                    $meals[] = $meal;
                }
            }
            if (count($meals) >= 3) break;
        }

        return view('front.pages.partials.meal-cards', compact('meals'))->render();
    }

}