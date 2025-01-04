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
        $plans = \App\Models\Plan::all();
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

            $planIds = DB::table('payments')->where('email', $user->email)->where('status', 'succeeded')->pluck('plan_id')->toArray();
            if ($planIds) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    if (!Auth::user()->isSuperAdmin()) {
                        $redirectUrl = route('front.competition-plan-details', ['id' => $user->id]); // Change this to the page you want
        
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
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function getProfileDetails($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'profile_image' => $user->profile_image ? asset('private/public/'.$user->profile_image) : null,
        ]);
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
        
        $meals = \App\Models\Meal::with('items')->get();

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
            'email' => 'required', // Test data should be an array
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

        $existingSubmission = Questionnaire::where('email', $request->email)->first();

        if ($existingSubmission) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted the test.'
            ], 400);
        }

        // Loop through the test data and insert each question and answer into the `questionnaire` table
        foreach ($request->testData as $question => $answer) {
            // dd($question);
            $questionnaire = new Questionnaire();
            $questionnaire->user_id = $user->id;
            $questionnaire->name = $user->name;
            $questionnaire->email = $user->email;
            $questionnaire->phone = $request->phone;  // Assuming 'phone' is part of the user
            $questionnaire->question = $question;  // Store the question text
            $questionnaire->answer = json_encode($answer);      // Store the corresponding answer
            $questionnaire->save(); // Save the data to the table
        }

        // Return success response
        return response()->json(['success' => true, 'message' => 'Test data submitted successfully']);
    
    }

    public function updateFoodQuantity(Request $request)
    {
        // dd($request->all());
        $userItem = \App\Models\UserItem::where('id', $request->user_item_id)
                                ->first();

        $userItem->qty = $request->qty;
        $userItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Food quantity updated successfully!',
            'userItem' => $userItem,
        ]);
    }
}