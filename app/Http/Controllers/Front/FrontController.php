<?php

namespace App\Http\Controllers\Front;

use App\Models\Plan;
use App\Models\Requirement;
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
        return view('front.sub-home-page', compact('requirements','page', 'plans','disabledDay','organization','testimonials'));
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
}