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
        // $user = getUserBySlug($slug);
        // if (!$user) {
        //     return $this->jsonService->sendRequest(false, [
        //         'message' => 'User Not found!',
        //     ], 400);
        //     // return redirect('/booking');
        // }
        // $requirements = $this->requirement->getRequirementsByUser($user->id);
        $plans = \App\Models\Plan::all();
        //dd($plans);
        $page = \App\Models\Page::with('sections')->where('slug', 'home')->first();
        //dd($page->sections);
        // if(!$requirements || !$plans){
        //     return $this->jsonService->sendRequest(false, [
        //         'message' => 'Requirements & Plans Not found!',
        //     ], 400);
        //     // return redirect('/booking');
        // }
        $requirements = [];
       // $plans = [];
        // $bookingConfiguration = BookingConfiguration::getBookingConfiguration($user->id);
        // if(!$bookingConfiguration->selected_days 
        // || !$bookingConfiguration->selected_timerange 
        // || ($bookingConfiguration->selected_timerange 
        // && (!$bookingConfiguration->selected_timerange->startTime || !$bookingConfiguration->selected_timerange->endTime))){
        //     return $this->jsonService->sendRequest(false, [
        //         'message' => 'Booking configuration failed!',
        //     ], 400);
        //     // return redirect('/booking');
        // }
        
        // $days = [
        //     1 => 'monday',
        //     2 => 'tuesday',
        //     3 => 'wednesday',
        //     4 => 'thursday',
        //     5 => 'friday',
        //     6 => 'saturday',
        //     0 => 'sunday',
        // ];
        
        // $selectedDay = $bookingConfiguration->selected_days;
        // $selectedDayCount = [];

        // foreach($selectedDay as $day){
        //     $selectedDayCount[] = array_search($day, $days);
        // }

        // $disabledDay = [];
        // foreach($days as $key => $day){
        //     if(!in_array($key, $selectedDayCount)){
        //         $disabledDay[] = $key;
        //     }
        // }

        // $disabledDay = json_encode($disabledDay);
        $disabledDay = json_encode([]);
        // $organization = $user->getOrganizationImages();
        // $testimonials = $user->getTestimonials();
        $organization = [];
        $testimonials = [];
        // dd($testimonials);
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
        // dd($slug);
        // $user = getUserBySlug($slug);
        // if (!$user) {
        //     Session::flash('message', 'User not found.');
        // }

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
        // $user = getUserBySlug($slug);
        // if (!$user) {
        //     return $this->jsonService->sendRequest(false, [
        //         'message' => 'User Not found!',
        //     ], 400);
        //     // return redirect('/booking');
        // }
        // $requirements = $this->requirement->getRequirementsByUser($user->id);
        $plans = \App\Models\Plan::all();
        //dd($plans);
        $page = \App\Models\Page::with('sections')->where('slug', 'actionsport-nutrition-plan')->first();
        //dd($page->sections);
        // if(!$requirements || !$plans){
        //     return $this->jsonService->sendRequest(false, [
        //         'message' => 'Requirements & Plans Not found!',
        //     ], 400);
        //     // return redirect('/booking');
        // }
        $requirements = [];
       // $plans = [];
        // $bookingConfiguration = BookingConfiguration::getBookingConfiguration($user->id);
        // if(!$bookingConfiguration->selected_days 
        // || !$bookingConfiguration->selected_timerange 
        // || ($bookingConfiguration->selected_timerange 
        // && (!$bookingConfiguration->selected_timerange->startTime || !$bookingConfiguration->selected_timerange->endTime))){
        //     return $this->jsonService->sendRequest(false, [
        //         'message' => 'Booking configuration failed!',
        //     ], 400);
        //     // return redirect('/booking');
        // }
        
        // $days = [
        //     1 => 'monday',
        //     2 => 'tuesday',
        //     3 => 'wednesday',
        //     4 => 'thursday',
        //     5 => 'friday',
        //     6 => 'saturday',
        //     0 => 'sunday',
        // ];
        
        // $selectedDay = $bookingConfiguration->selected_days;
        // $selectedDayCount = [];

        // foreach($selectedDay as $day){
        //     $selectedDayCount[] = array_search($day, $days);
        // }

        // $disabledDay = [];
        // foreach($days as $key => $day){
        //     if(!in_array($key, $selectedDayCount)){
        //         $disabledDay[] = $key;
        //     }
        // }

        // $disabledDay = json_encode($disabledDay);
        $disabledDay = json_encode([]);
        // $organization = $user->getOrganizationImages();
        // $testimonials = $user->getTestimonials();
        $organization = [];
        $testimonials = [];
        // dd($testimonials);
        $isAuthenticated = Auth::check(); // Returns true if the user is logged in

        return view('front.sub-home-page', compact('requirements','page', 'plans','disabledDay','organization','testimonials','isAuthenticated'));
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
                        $redirectUrl = route('front.sub-home-page'); // Change this to the page you want
        
                        return response()->json([
                            'success' => true,
                            'redirect_url' => $redirectUrl,
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        // Update user details
        $user->name = ucfirst($request->first_name) . ' ' . ucfirst($request->last_name);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        // Check if password is provided and update it
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete the old profile image if it exists
            if ($user->profile_image) {
                \Storage::delete($user->profile_image);
            }

            // Store the new image
            $imagePath = $request->file('profile_image')->store('profile_images','public');
            $user->profile_image = $imagePath;
        }

        if ($request->hasFile('profile_image')) {
            // Handle the file upload and store it in the 'public/uploads/profile_images' directory
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/profile_images/' . $fileName;
            $file->move(public_path('uploads/profile_images'), $fileName);
        
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
}