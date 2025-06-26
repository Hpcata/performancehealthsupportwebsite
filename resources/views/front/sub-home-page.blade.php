@extends(frontView('layouts.app'))

@section('title', '$page->title')

@section('content')
    <style>
        #thankYouModal .modal-content {
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        #thankYouModal .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #thankYouModal .modal-body {
            padding: 2rem;
        }
        #thankYouModal .btn {
            border-radius: 25px;
            font-weight: 600;
        }

        /* Blur the background when the modal is open */
        .modal-backdrop {
            backdrop-filter: blur(5px); /* Blur the backdrop */
            background-color: rgba(0, 0, 0, 0.3); /* Add slight transparency */
        }

        /* Ensure the modal and its content are not blurred */
        .modal-content {
            filter: none !important;
        }
        
        /* Blur the rest of the page when the modal is open */
        .blur-background {
            filter: blur(5px); /* Adjust the blur value */
            transition: filter 0.3s ease-in-out;
        }
        .purchase-now-btn {
            white-space: nowrap;
        }
        .coupon-link           { text-decoration:none; cursor:pointer; color:#000; text-decoration:underline;}
        .coupon-link.active    { color:#000; text-decoration:underline; }

    </style>
    @php
        $showHeader = !empty($user->front_logo) && 
                    !empty($user->front_title) && 
                    !empty($user->front_description) && 
                    !empty($user->about_us_image);

        if($isAuthenticated){
            $user = Auth::user();
            $planIds = DB::table('payments')->where('email', $user->email)->where('status', 'succeeded')->pluck('plan_id')->toArray();
        }else {
            $planIds = [];
        }
    @endphp
    <?php // dd($page); ?>
    @if(session('error') == 'Plan not purchased.')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var planModal = new bootstrap.Modal(document.getElementById('planModal'));
                planModal.show();
            });
        </script>
    @elseif(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Modal Structure -->
    <div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>You have not purchased any plans.<br>Please purchase a plan.</p>

                    <div class="d-grid justify-content-center gap-3 mt-5">
                        <a href="{{ route('front.sub-home-page') }}#sport-plans" class="btn btn-primary btn-sm px-4" style="width: 300px;">View Plans</a>
                        <a href="{{ route('front.index') }}#bookingtypecontainer" class="btn btn-secondary px-4" style="width: 300px;">Book Consultation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->type == 'section-1' && $section->enabled == 1)
                <div class="section nutrition-page-banner pt-md-5" style="background-image: url(private/public/front/images/hero-img-03.webp);">
                    <div class="container">
                        <div class="text-center">
                            <h1 class="text-white mt-md-3">Sports Nutrition Plans</h1>
                        </div>
                        <div class="text-center banner-text mt-auto pt-5">
                            {!! $section->content !!}
                            <a href="#" class="btn btn-primary" id="takeFreeTest">
                                <span class="me-1">Take Free Quiz</span>
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                    <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if($section->type == 'section-2' && $section->enabled == 1)
                <div class="section power-peak-row bg-white">
                    <div class="container">
                        <div class="row align-items-center g-4">
                            <div class="col-md-6">
                                <div class="">  
                                    <figure class="m-0">
                                        <img class="w-100" src="{!! frontAssets('images/about-new.webp') !!}" alt="">
                                    </figure>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="about-content">
                                    <h2>{{ $section->title }}</h2>
                                    <p>{!! $section->content !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach 
    @endif

    <div class="section find-spot-row" style="background-image: url(private/public/front/images/female-athlete.webp);">
        <div class="container">
            <div class="h1 text-center text-white">Find Your Sport</div>
            <div class="spot-search">
                <form action="#" id="sport-form">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="form-select-box">
                                <select class="form-control" name="sport" id="sport" required>
                                    <option value="">Select Your Sport</option>
                                    <option value="action_sports">Action Sports</option>
                                    <option value="contact_sports">Contact Sports</option>
                                    <option value="endurance_sports">Endurance Sports</option>
                                    <option value="ball_sports">Ball Sports</option>
                                    <option value="combat_sports">Combat Sports</option>
                                    <option value="equestrian_sports">Equestrian Sports</option>
                                    <option value="motor_sports">Motor Sports</option>
                                    <option value="target_sports">Target Sports</option>
                                    <option value="water_sports">Water Sports</option>
                                    <option value="winter_sports">Winter Sports</option>
                                    <option value="disability_sports">Disability Sports</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-4 select-middle">
                            <div class="form-select-box">
                                <select class="form-control" name="state" required>
                                    <option value="">Choose Your State</option>
                                    <option value="New South Wales">New South Wales (NSW)</option>
                                    <option value="Victoria">Victoria (VIC)</option>
                                    <option value="Queensland">Queensland (QLD)</option>
                                    <option value="South Australia">South Australia (SA)</option>
                                    <option value="Western Australia">Western Australia (WA)</option>
                                    <option value="Tasmania">Tasmania (TAS)</option>
                                    <option value="Australian Capital Territory">Australian Capital Territory (ACT)</option>
                                    <option value="Northern Territory">Northern Territory (NT)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 select-middle">
                            <div class="form-select-box">
                                <select class="form-control" name="sport_game" id="sport_game" required>
                                    <option value="">Select Your Sport Game</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;">
                                        <path d="M23.6,22l-4.4-4.4c1.5-1.8,2.4-4.2,2.4-6.7c0-6-4.8-10.8-10.8-10.8C4.8,0,0,4.8,0,10.8c0,6,4.8,10.8,10.8,10.8c2.5,0,4.9-0.9,6.7-2.4l4.4,4.4c0.2,0.2,0.5,0.4,0.8,0.4c0.3,0,0.6-0.1,0.8-0.4C24.1,23.2,24.1,22.4,23.6,22z M2.4,10.8c0-4.6,3.8-8.4,8.4-8.4c4.6,0,8.4,3.8,8.4,8.4c0,2.3-0.9,4.4-2.4,5.9c0,0,0,0,0,0c0,0,0,0,0,0c-1.5,1.5-3.6,2.4-5.9,2.4C6.2,19.2,2.4,15.4,2.4,10.8z"></path>
                                    </svg>
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="section py-5" id="sport-plans">
        <div class="container">
            <h2 class="heading mb-3 d-flex">
                <div class="mt-2 h2 text-nowrap">Nutrition plans built by <br> Sports Nutrition expert, <br>
                    Kerry O'Bryan
                </div>
                <span class="border-heading"></span>
            </h2>
            <div class="spot-plan-row">
                <div class="row g-4">
                @foreach($plans as $plan)
                    <div class="col-lg-3 col-md-6">
                        <div class="spot-plan-box">
                            <h5>{{ $plan->name }}</h5>
                            <h6 class="text-center mb-3">{{ $plan->subtitle }}</h6>
                            <div class="spot-plan-img-box">
                                <figure>
                                    @if($plan->image)
                                    <img src="{{ asset('private/public/storage/' . $plan->image) }}" alt="">
                                    @else
                                    <img src="{!! frontAssets('images/about-new.webp') !!}" alt="">
                                    @endif
                                </figure>
                                <div class="spot-plan-info-box">
                                    {!! htmlspecialchars_decode($plan->description) !!}
                                </div>
                            </div>
                            <!-- <a href="#" class="btn btn-primary">Purchase Now: $200</a> -->
                            <div data-aos="fade-up" class="aos-init aos-animate">
                                @if($isAuthenticated && in_array($plan->id, $planIds))
                                    <?php
                                        $userPlan = \App\Models\UserPlan::where('user_id', Auth::user()->id)->where('plan_id', $plan->id)->where('status', 'active')->first();
                                        $isPlanCreated = $userPlan ? true : false;
                                    ?>
                                    <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => Auth::user()->id]) }}" class="btn btn-primary mt-2 w-100 @if(!$isPlanCreated) disabled @endif" @if(!$isPlanCreated) style="pointer-events: none; opacity: 0.5;" @endif>
                                        <span class="me-1">View Details </span>
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">

                                            <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>

                                            <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                        </svg>
                                    </a>
                                    @elseif(!$isAuthenticated && in_array($plan->id, $planIds))
                                        <div data-aos="fade-up" class="aos-init aos-animate">
                                            <button type="button" class="btn btn-primary mt-2 w-100" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#loginModal">
                                                View Details
                                            </button>
                                        </div>
                                    @else
                                        <div data-aos="fade-up" class="aos-init aos-animate">
                                            <button type="button" class="btn btn-primary mt-2 w-100 purchase-now-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#purchaseModal"
                                                    data-plan-id="{{ $plan->id }}" 
                                                    data-plan-name="{{ $plan->name }}" 
                                                    data-plan-price="{{ $plan->price }}">
                                                <span class="full-text">Purchase Now: ${{ $plan->price }}</span>
                                                <span class="mobile-text d-none">Purchase Now:<br>${{ $plan->price }}</span>
                                            </button>
                                        </div>
                                    @endif
                            </div>  
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="section bg-white py-5" id="sample-plan-section">
        <div class="container">
            <h2 class="heading mb-md-5 d-flex">
                <div class="mt-2 h2">Nutrition Sample Plan</div>
                <span class="border-heading"></span>
            </h2>
            <div class="row g-4 justify-content-center">
                <div class="col-xl-10 col-lg-11">
                    <div class="nutrition-login">
                        <div class="mb-3">
                            <!-- <h3>Purchase Now</h3> -->
                            <!-- <a href="{{ route('front.sample-plan') }}" class="btn btn-primary">View Sample Plan</a> -->
                        </div>
                        <div class="row flex-row-reverse align-items-center">
                            <div class="col-md-6">
                                <div class="nutrition-login-images p-0">
                                    <div class="nutrition-login-big">
                                        <figure class="w-100">
                                            <img src="{{ frontAssets('images/your-purchased-plan-0001.webp') }}" alt="">
                                        </figure>
                                    </div>
                                    <!-- <div class="nutrition-login-small">
                                        <figure>
                                            <img src="{{ frontAssets('images/nutrition-login-plans-small.png') }}" alt="">
                                        </figure>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="nutrition-login-form mt-4 mt-md-0">
                                    <h3>Optimise Your
                                    Health & Performance</h3>
                                    <p>Personalised nutrition and fitness plans to help you stay strong, energised, and at your best every day.</p>
                                    <button type="button" class="btn btn-primary sample-plan-modal" data-bs-toggle="modal"    data-bs-target="#sample-plan-modal" >
                                        View More Features
                                    </button>

                                    <div class="modal fade" id="sample-plan-modal" tabindex="-1" aria-labelledby="smplPlnModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="smplPlnModalLabel">View More Features</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createProfile" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            Create a Profile
                                                        </button>
                                                        </h2>
                                                        <div id="createProfile" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Create a Profile</h3>
                                                                            <p>Your specific health profile data is used to customise a nutrition plan based on individual training and competition needs.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/create-profile-img-01.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#goals" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            Goals
                                                        </button>
                                                        </h2>
                                                        <div id="goals" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Goals</h3>
                                                                            <p>Select nutrition related goals (improved sports performance/recovery, muscle gain, weight loss, injury repair etc). Progress is tracked with key actions.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/goal-img-01.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#history" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            History
                                                        </button>
                                                        </h2>
                                                        <div id="history" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>History</h3>
                                                                            <p>Nutrition + Medical History 
                                                                                Dietary preferences, supplement use, and medical screening information are recorded. 
                                                                                You can also create your own 'BioHealth Passport' where blood test results and scans etc can be uploaded.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/intake-img-01.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#foodPreferences" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            Food Preferences
                                                        </button>
                                                        </h2>
                                                        <div id="foodPreferences" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Food Preferences</h3>
                                                                            <p>You choose foods that you like which are then used to design relevant meals  and snacks that align with your performance goals.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/food-preferences-img-001.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#purchasePlan" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            Your Purchased Plan
                                                        </button>
                                                        </h2>
                                                        <div id="purchasePlan" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Your Purchased Plan</h3>
                                                                            <p>Displays the nutrition or fitness plan a subscribed to, including details on duration, customization options, and renewal status. Helps manage their plan effectively.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/your-purchased-plan-0001.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#smartSwaps" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            Smart Swaps
                                                        </button>
                                                        </h2>
                                                        <div id="smartSwaps" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Smart Swaps</h3>
                                                                            <p>You can swap ingredients within meals to add variety to your plan whilst being coached with nutrition tips.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/customise-your-plan-01.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#selectShopingList" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            Select Shopping List
                                                        </button>
                                                        </h2>
                                                        <div id="selectShopingList" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Select Shopping List</h3>
                                                                            <p>A grocery list is created based on your custom meal plan to make shopping and eating much easier.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/select-shopping-list-001.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#printShoppingList" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Print Shopping List
                                                        </button>
                                                        </h2>
                                                        <div id="printShoppingList" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Print Shopping List</h3>
                                                                            <p>Print Your Grocery Shopping List.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/print-shopping-list-001.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#printSummaryPlan" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Print Your Summary Plan
                                                        </button>
                                                        </h2>
                                                        <div id="printSummaryPlan" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Print Your Summary Plan</h3>
                                                                            <p>Print Your Plan and get cracking on hitting your goals!</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/print-your-summery-plan-002.jpeg') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#competitionPlan" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Competition + Injury / Surgery Plans
                                                        </button>
                                                        </h2>
                                                        <div id="competitionPlan" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5 col-lg-4">
                                                                        <div class="sample-content">
                                                                            <h3>Competition + Injury / Surgery Plans</h3>
                                                                            <p>Whether you're getting organised for a comp or recovering from injury, a custom nutrition plan will ensure your nutrition is optimised for peak performance or fast healing.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7 col-lg-8">
                                                                        <div class="sample-img">
                                                                            <figure>
                                                                                <img src="{{ frontAssets('images/competition-plan-01.webp') }}" alt="">
                                                                            </figure>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section bg-white py-5" id="nutrition-login-section">
        <div class="container">
            <h2 class="heading mb-md-5 d-flex">
                <div class="mt-2 h2">Already Purchased?</div>
                <span class="border-heading"></span>
            </h2>
            <div class="row g-4 justify-content-center">
                <div class="col-xl-10 col-lg-11">
                    <div class="nutrition-login">
                        <div class="mb-3">
                            <!-- <h3>Purchase Now</h3> -->
                            <!-- <a href="{{ route('front.sample-plan') }}" class="btn btn-primary">View Sample Plan</a> -->
                        </div>
                        <div class="row flex-row-reverse align-items-center">
                            <div class="col-md-6">
                                <div class="nutrition-login-images p-0">
                                    <div class="nutrition-login-big">
                                        <figure class="w-100">
                                            <img src="{{ frontAssets('images/nutrition-login-plans-big.webp') }}" alt="">
                                        </figure>
                                    </div>
                                    <!-- <div class="nutrition-login-small">
                                        <figure>
                                            <img src="{{ frontAssets('front/images/nutrition-login-plans-small.png') }}" alt="">
                                        </figure>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="nutrition-login-form mt-4 mt-md-0">
                                    <h3>Sign In</h3>
                                    <form action="#" id="front-page-login-form">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" id="front-page-login-email">
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" id="front-page-login-password">
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" id="forgot-password">Forgot Password?</a>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Sign In</button>
                                        </div>
                                    </form>
                                    </br>
                                
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nutrition-login-book card-body p-0">
                        <div class="card border-0 shadow-none overflow-hidden mt-3 talk-expert-box">
                            <div class="card-body p-4">
                                <div class="p-md-3 row">
                                    <div class="col-lg-6">
                                        <h6>Powered by BioHealth<span>Passport</span></h6>
                                        <figure>
                                            <img src="https://booking.biohealthpassport.com.au/public/uploads/front_logo/1727981512_1727875441_logo.png" alt="">
                                        </figure>
                                        <h3>Get answers from a real-life expert. Not a chat bot.</h3>
                                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-white">Book Now 
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.4165 15.5827L15.5832 6.41602M15.5832 6.41602H6.4165M15.5832 6.41602V15.5827" stroke="#124E4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="kerry-info-box">
                                            <figure>
                                                <img src="{{ frontAssets('images/hero01.webp') }}" alt="">
                                            </figure>
                                            <div class="kerry-info">
                                                <h5>Kerry O'Bryan</h5>
                                                <p>MNutr&amp;Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                                                <p>(Dietitian /Sports Scientist/Strength &amp; Conditioning Coach)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-3 pt-3 pb-0" data-aos="fade-up" data-aos-delay="100">
       
    </div>

    <section class="section pb-3 pt-4 my-3 testimonial-section-main-div">
        <div class="col-lg-12 text-center mb-5" data-aos="fade-up" style="text-align: center !important;">
            <h2 class="heading mb-5  d-flex align-items-center justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div class="border-heading-top position-relative"></div>
                <div class="text-nowrap">Testimonials</div>
                <div class="border-heading d-block position-relative using-my-heading mt-0"></div>
            </h2>
            <div class="heading-content" data-aos="fade-up">
                <h2><span class="d-block">What Clients are Saying?</span></h2>
                <p>People around the world are embracing inside tracker's data driven approach to health span
                    optimization.</p>
            </div>
        </div>
        <div class="col-12 mx-auto">
            <div class="row">
                
                <div class="col-xl-6 col-lg-8 col-md-10 px-md-0 px-4 mx-auto position-relative">
                    <div class="wide-slider-testimonial-wrap-two">
                        <div class="wide-slider-testimonial-two">
                           
                        </div>
                    </div>
                    <div id="prevnext-testimonial-one">
                        <span class="prev me-1" data-controls="prev">
                            <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0.521729 8.70303C-0.144938 8.31813 -0.144939 7.35588 0.521728 6.97098L12.2446 0.202802C12.9112 -0.182099 13.7446 0.299026 13.7446 1.06883L13.7446 14.6052C13.7446 15.375 12.9112 15.8561 12.2446 15.4712L0.521729 8.70303Z"
                                    fill="#649EF7" />
                            </svg>
                        </span>
                        <span class="next" data-controls="next">
                            <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.4783 8.70303C14.1449 8.31813 14.1449 7.35588 13.4783 6.97098L1.75545 0.202802C1.08878 -0.182099 0.255445 0.299026 0.255445 1.06883L0.255445 14.6052C0.255445 15.375 1.08878 15.8561 1.75544 15.4712L13.4783 8.70303Z"
                                    fill="#649EF7" />
                            </svg>
                        </span>
                    </div>
                </div>
               
                <div class="col-12 text-center mt-5" style="text-align: center !important;">

                </div>
            </div>
        </div>
    </section>
    
    <section class="section pb-3 pt-4 my-3 testimonial-section-main-div">
        <div class="col-lg-12 text-center mb-5" data-aos="fade-up" style="text-align: center !important;">
            <h2 class="heading mb-5 d-flex align-items-center justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div class="border-heading-top position-relative"></div>
                <div class="text-nowrap">LinkedIn Feed</div>
                <div class="border-heading d-block position-relative using-my-heading mt-0"></div>
            </h2>
        </div>
       <div class="col-12 mx-auto">
            <div class="row">
                <div class="col-xl-6 col-lg-8 col-md-10 px-md-0 px-4 mx-auto position-relative">
                    <div class="wide-slider-testimonial-wrap-two">
                        <!-- Bootstrap Carousel for Multiple LinkedIn Feeds -->
                        <div id="linkedin-carousel" class="carousel slide linkedin-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner justify-content-center align-items-center w-100">
                                <!-- LinkedIn Feed 1 -->
                                <div class="carousel-item active" id="linkedin-feed-1"></div>
                                <!-- LinkedIn Feed 2 -->
                                <div class="carousel-item" id="linkedin-feed-2"></div>
                                <!-- More items if needed -->
                            </div>
                            <!-- Carousel Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#linkedin-carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#linkedin-carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section py-5 client-sec">
        <div class="container">
            <div class="col-lg-12" data-aos="fade-up">
                <h2 class="heading mb-3  d-flex align-items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-nowrap">Teams and organisations<br>
                        who I've worked with
                    </div>

                    <span class="border-heading"></span>
                </h2>
            </div>
           
        </div>
        <article class="wrapper">
            <div class="marquee-main">
               
                <div class="marquee" id="marquee-top">
                    <div class="marquee__group"></div>
                    <div aria-hidden="true" class="marquee__group"></div>
                </div>

                <div class="marquee marquee--reverse" id="marquee-bottom">
                    <div class="marquee__group"></div>
                    <div aria-hidden="true" class="marquee__group"></div>
                </div>

            </div>
        </article>
    </div>

    <div class="section section-contact pb-0 pt-0" id="contact">
        <div class="container">
            <div class="contact-wrap position-relative">
                <h2 class="heading mb-5  d-flex align-items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-nowrap">Have more questions<br>about to getting started?</div>
                    <span class="border-heading"></span>
                </h2>
                <div class="row justify-content-between align-items-start">

                    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                        <p>Let us know your concerns and we will get back to you with</p>
                        <form id="query-form" >
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="query-name" placeholder="Your name">
                                <label for="floatingInput">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="query-email" placeholder="name@example.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="query-phone" placeholder="Mobile number">
                                <label for="floatingInput">Mobile number</label>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="query-message" rows="3"></textarea>
                                <label for="floatingTextarea">What is your question?</label>
                            </div>


                            <!-- <div class="col-12">
                                <input type="submit" value="Send Message" class="btn btn-primary">
                            </div> -->
                            <p class="my-4" data-aos="fade-up" data-aos-delay="200"><a href="#" class="btn btn-primary" id="submit-query">Submit
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white" />
                                        <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
                <img src="{!! frontAssets('images/contact.webp') !!}" alt="Image" class="img-fluid img-contact">

            </div>

        </div>
    </div>

    <!-- Sign-Up Modal (Purchase Modal) -->
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Purchase Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Sign In Link -->
                    <div class="mb-3" id="already-signed-in">
                        <small>Already have an account? <a href="#" id="show-login-modal">Sign In</a></small>
                    </div>
                   
                    <!-- User info form -->
                    <form id="payment-form">
                        <div id="registration-details">
                            <h6 class="mb-3" style="font-weight: 800;">Create Account</h6>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" >
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailId" >
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" >
                            </div>

                            <!-- New Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" >
                                <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                            </div>

                            <!-- Divider -->
                            <hr class="my-4">
                        </div>

                        <div id="signed-in-details" class="d-none">
                            <p class="mb-2" style="font-size: 15px;" >Signed In as</p>
                            <!-- <div class="mb-3"> -->
                                <p id="signed-in-email" style="font-weight: 500;"></p>
                            <!-- </div> -->
                            <hr>
                        </div>

                        <h6 class="mb-2" style="font-weight: 800;">Payment Details</h6>
                        
                        <div class="mb-3 mt-3">
                            <small>
                                <a href="#" id="toggle-coupon-link" class="coupon-link">Add a Coupon Code</a>
                            </small>
                        </div>
                        <!-- Promo Code Section -->
                        <div class="mb-3 d-none" id="coupon-details">
                            <label for="promo-code" class="form-label">Coupon Code</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control h-auto" id="promo-code" placeholder="Enter coupon code">
                                <input type="hidden" class="form-control" id="discount">
                                <button type="button" class="btn btn-primary" id="apply-promo-code">Apply</button>
                            </div>
                            <small id="promo-message" class="form-text"></small>
                        </div>
                        <div id="payment-details">
                            <!-- Stripe Payment Card Section -->
                            <div class="mb-3">
                                <label for="card-element" class="form-label">Credit or Debit Card</label>
                                <div id="card-element" class="border rounded p-3" style="background-color: #f9f9f9;">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <!-- <button type="button" id="view-sample-plan" class="btn btn-primary w-100 mt-3">
                            View Sample Plan
                        </button> -->
                        <button type="submit" id="submit" class="btn btn-primary w-100 mt-3">
                            Purchase
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sign-In Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div id="login-error" class="text-danger"></div> <!-- This will display the error message -->
                    <!-- Sign In Form -->
                    <form id="login-form">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="login-password" required>
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" id="login-submit" class="btn btn-primary w-100 mt-3">
                            Sign In
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-3 text-center">
                        <small>Don't have an account? <a href="#" id="show-signup-modal">Sign Up</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="testLoginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div id="login-error" class="text-danger"></div> <!-- This will display the error message -->
                    <!-- Sign In Form -->
                    <form id="test-login-form">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="test-login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="test-login-password" required>
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" id="login-submit" class="btn btn-primary w-100 mt-3">
                            Sign In
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-3 text-center">
                        <small>Don't have an account? <a href="#" class="register-link">Sign Up</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- User info form -->
                    <form id="register-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="register-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="register-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="register-phone" required>
                        </div>

                        <!-- New Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="register-password" required>
                            <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                        </div>

                        <button type="submit" id="submit" class="btn btn-primary w-100 mt-3">
                            Sign Up
                        </button>
                    </form>

                    <!-- Sign In Link -->
                    <div class="mt-3 text-center">
                        <small>Already have an account? <a href="#" class="login-link">Sign In</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Thank You Modal -->
    <div class="modal" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="icon-container mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="modal-title mb-2" id="thankYouModalLabel">Thank You!</h2>
                    <p class="mb-2" id="thankYouMessage">Your payment was successful.</p>
                    <p class="mb-2">Your plan will be created by Kez and sent via email in the coming days.</p>
                    <!-- <a href="#" id="planUrlLink" class="btn btn-primary mt-2">Order Your Personalised Plan</a> -->

                    <button type="button" class="btn btn-primary w-50" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="samplePlanModal" tabindex="-1" aria-labelledby="samplePlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="samplePlanModalLabel">Sample Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="samplePlanModalBody">
                    <!-- Plan details will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="TakeTestModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"         aria-labelledby="TakeTestModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header flex-column align-items-start pe-5">
                    <h4 class="modal-title mb-1" id="testModalLabel">Nutrition Knowledge Questions</h4>
                    <!-- <p>Answers to the 4 questions below will assist in providing targeted information. </p> -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="steps-list mb-4">
                        <div class="wizard-inner">
                            <a class="tab-steps active" href="#"><span class="round-tab">1</span> <i>Step 1</i></a>
                            <a class="tab-steps" href="#"><span class="round-tab">2</span> <i>Step 2</i></a>
                            <a class="tab-steps" href="#"><span class="round-tab">3</span> <i>Step 3</i></a>
                            <a class="tab-steps" href="#"><span class="round-tab">4</span> <i>Step 4</i></a>
                            <a class="tab-steps" href="#" id="step-5"><span class="round-tab">5</span> <i>Step 5</i></a>
                            <a class="tab-steps" href="#" id="step-6"><span class="round-tab">6</span> <i>Step 6</i></a>
                            <a class="tab-steps" href="#" id="step-7"><span class="round-tab">7</span> <i>Step 7</i></a>
                            <a class="tab-steps" href="#" id="step-8"><span class="round-tab">8</span> <i>Step 8</i></a>
                            <a class="tab-steps" href="#" id="step-9"><span class="round-tab">9</span> <i>Step 9</i></a>
                        </div>
                    </div>

                    <div class="tab-main-box">
                        <div class="step-tab-box nutrition-form" id="div1" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">1. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">carbohydrate</strong>? <span>(Select one answer per food)</span></h5>
                                    <span class="ms-2 general-error-message text-danger"> </span>
                                </div>
                                    <input type="hidden" name="questions[nutrition-Q-1]" value="Do you think these foods are high or low in carbohydrate?" />
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-center">High</th>
                                                    <th class="text-center">Low</th>
                                                    <th class="text-center">Unsure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Chicken</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-1][chicken]" value="0" id="Chicken-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-1][chicken]" value="1" id="Chicken-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][chicken]" value="0" id="Chicken-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Baked beans</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-1][baked_beans]" value="1" id="Bakedbeans-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-1][baked_beans]" value="0" id="Bakedbeans-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][baked_beans]" value="0" id="Bakedbeans-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Grain bread</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-1][grain_bread]" value="1" id="GrainBread-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-1][grain_bread]" value="0" id="GrainBread-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][grain_bread]" value="0" id="GrainBread-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Avocado</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-1][avocado]" value="0" id="Avocado-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-1][avocado]" value="1" id="Avocado-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][avocado]" value="0" id="Avocado-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Weet-bix</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-1][weet_bix]" value="1" id="Weet-bix-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-1][weet_bix]" value="0" id="Weet-bix-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][weet_bix]" value="0" id="Weet-bix-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Fruit yoghurt</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-1][fruit_yoghurt]" value="1" id="FruitYoghurt-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-1][fruit_yoghurt]" value="0" id="FruitYoghurt-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][fruit_yoghurt]" value="0" id="FruitYoghurt-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Crumpets</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-1][crumpets]" value="1" id="Crumpets-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-1][crumpets]" value="0" id="Crumpets-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][crumpets]" value="0" id="Crumpets-3">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Cream</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-1][cream]" value="0" id="Cream-1">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-1][cream]" value="1" id="Cream-2">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-1][cream]" value="0" id="Cream-3">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                    <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="2">Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div2" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                    <div class="d-flex align-items-center">
                                        <h5 class="m-0">2. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">protein</strong>?
                                        <span>(Select one answer per food)</span></h5>
                                        <span class="ms-2 general-error-message text-danger"> </span>
                                    </div>
                                    <input type="hidden" name="questions[nutrition-Q-2]" value="Do you think these foods are high or low in protein?" />
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-center">High</th>
                                                    <th class="text-center">Low</th>
                                                    <th class="text-center">Unsure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Salmon</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-2][salmon]" value="1" id="Salmon-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-2][salmon]" value="0" id="Salmon-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][salmon]" value="0" id="Salmon-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Baked beans</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-2][baked_beans]" value="1" id="Bakedbeans-11"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-2][baked_beans]" value="0" id="Bakedbeans-12"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][baked_beans]" value="0" id="Bakedbeans-13"></td>
                                                </tr>
                                                <tr>
                                                    <td>Fruit</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-2][fruit]" value="0" id="Fruit-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-2][fruit]" value="1" id="Fruit-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][fruit]" value="0" id="Fruit-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Hummus</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-2][hummus]" value="0" id="Hummus-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-2][hummus]" value="1" id="Hummus-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][hummus]" value="0" id="Hummus-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cornflakes cereal</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-2][cornflakes_cereal]" value="0" id="CornflakesCereal-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-2][cornflakes_cereal]" value="1" id="CornflakesCereal-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][cornflakes_cereal]" value="0" id="CornflakesCereal-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Almonds</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-2][almonds]" value="1" id="Almonds-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-2][almonds]" value="0" id="Almonds-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][almonds]" value="0" id="Almonds-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Flavoured milk</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="1" type="radio" name="ans[nutrition-Q-2][flavoured_milk]" value="1" id="FlavouredMilk-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="0" type="radio" name="ans[nutrition-Q-2][flavoured_milk]" value="0" id="FlavouredMilk-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][flavoured_milk]" value="0" id="FlavouredMilk-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Ice cream</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-2][ice_cream]" value="0" id="IceCream-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-2][ice_cream]" value="1" id="IceCream-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][ice_cream]" value="0" id="IceCream-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Almond/oat milk</td>
                                                    <td class="text-center"><input class="form-check-input" data-option="High" data-correct="0" type="radio" name="ans[nutrition-Q-2][almond_oat_milk]" value="0" id="Almond-oat-milk-1"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Low" data-correct="1" type="radio" name="ans[nutrition-Q-2][almond_oat_milk]" value="1" id="Almond-oat-milk-2"></td>
                                                    <td class="text-center"><input class="form-check-input" data-option="Unsure" data-correct="0" type="radio" name="ans[nutrition-Q-2][almond_oat_milk]" value="0" id="Almond-oat-milk-3"></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                    <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="1" >Back</button>
                                    <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="3">Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div3" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                    <div class="d-flex align-items-center">
                                        <h5 class="m-0">3. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">fat</strong>? 
                                        <span>(Select one answer per food)</span></h5>
                                        <span class="ms-2 general-error-message text-danger"> </span>
                                        <input type="hidden" name="questions[nutrition-Q-3]" value="Do you think these foods are high or low in fat?" />
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">High</th>
                                                <th class="text-center">Low</th>
                                                <th class="text-center">Unsure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Avocado</td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][avocado]" value="1"
                                                        id="Avocado-11" data-option="High"  data-correct="1">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][avocado]" value="0"
                                                        id="Avocado-12" data-option="Low"   data-correct="0">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][avocado]" value="0"
                                                        id="Avocado-13" data-option="Unsure" data-correct="0">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Baked beans</td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][baked_beans]" value="0"
                                                        id="BakedBeans-21" data-option="High" data-correct="0">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][baked_beans]" value="1"
                                                        id="BakedBeans-22" data-option="Low"  data-correct="1">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][baked_beans]" value="0"
                                                        id="BakedBeans-23" data-option="Unsure" data-correct="0">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Cottage cheese</td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][cottage_cheese]" value="0"
                                                        id="CottageCheese-1" data-option="High" data-correct="0">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][cottage_cheese]" value="1"
                                                        id="CottageCheese-2" data-option="Low"  data-correct="1">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][cottage_cheese]" value="0"
                                                        id="CottageCheese-3" data-option="Unsure" data-correct="0">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Peanut butter</td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][peanut_butter]" value="1"
                                                        id="PeanutButter-1" data-option="High" data-correct="1">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][peanut_butter]" value="0"
                                                        id="PeanutButter-2" data-option="Low"  data-correct="0">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][peanut_butter]" value="0"
                                                        id="PeanutButter-3" data-option="Unsure" data-correct="0">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Crumpets</td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][crumpets]" value="0"
                                                        id="Crumpets-1" data-option="High" data-correct="0">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][crumpets]" value="1"
                                                        id="Crumpets-2" data-option="Low"  data-correct="1">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][crumpets]" value="0"
                                                        id="Crumpets-3" data-option="Unsure" data-correct="0">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Cheddar/Tatsy cheese</td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][cheddar_tatsy_cheese]" value="1"
                                                        id="CheddarTatsyCheese-1" data-option="High" data-correct="1">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][cheddar_tatsy_cheese]" value="0"
                                                        id="CheddarTatsyCheese-2" data-option="Low"  data-correct="0">
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="radio"
                                                        name="ans[nutrition-Q-3][cheddar_tatsy_cheese]" value="0"
                                                        id="CheddarTatsyCheese-3" data-option="Unsure" data-correct="0">
                                                </td>
                                            </tr>
                                        </tbody>

                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                    <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="2" >Back</button>
                                    <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="4">Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div4" style="display: none;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">4. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">healthy fats</strong>? <span>(Select one answer per food)</span></h5>
                                    <span class="ms-2 general-error-message text-danger"> </span>
                                </div>
                                    <input type="hidden" name="questions[nutrition-Q-4]" value="Do you think these foods are high or low in healthy fat?" />
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-center">High</th>
                                                    <th class="text-center">Low</th>
                                                    <th class="text-center">Unsure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Butter</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][butter]" value="0" id="Butter-1" data-option="High" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][butter]" value="1" id="Butter-2" data-option="Low" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][butter]" value="0" id="Butter-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>Extra virgin olive oil</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][extra_virgin_olive_oil]" value="1" id="OliveOil-1" data-option="High" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][extra_virgin_olive_oil]" value="0" id="OliveOil-2" data-option="Low" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][extra_virgin_olive_oil]" value="0" id="OliveOil-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>Whole milk</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][whole_milk]" value="0" id="WholeMilk-1" data-option="High" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][whole_milk]" value="1" id="WholeMilk-2" data-option="Low" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][whole_milk]" value="0" id="WholeMilk-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>Potato crisps</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][potato_crisps]" value="0" id="PotatoCrisps-1" data-option="High" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][potato_crisps]" value="1" id="PotatoCrisps-2" data-option="Low" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][potato_crisps]" value="0" id="PotatoCrisps-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>Salmon</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][salmon]" value="1" id="Salmon-1" data-option="High" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][salmon]" value="0" id="Salmon-2" data-option="Low" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][salmon]" value="0" id="Salmon-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>Dark chocolate</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][dark_chocolate]" value="0" id="DarkChocolate-1" data-option="High" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][dark_chocolate]" value="1" id="DarkChocolate-2" data-option="Low" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][dark_chocolate]" value="0" id="DarkChocolate-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>Macadamia nuts</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][macadamia_nuts]" value="1" id="MacadamiaNuts-1" data-option="High" data-correct="1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][macadamia_nuts]" value="0" id="MacadamiaNuts-2" data-option="Low" data-correct="0"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][macadamia_nuts]" value="0" id="MacadamiaNuts-3" data-option="Unsure" data-correct="0"></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                    <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="3" >Back</button>
                                    <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="5" >Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div5" style="display: none;">
                            <div class="card">
                                <div class="card">
                                    <div class="p-3 card-header bg-white">
                                    <div class="d-flex align-items-center">
                                        <h5 class="m-0">5. Which of these foods has the most iron?</h5>
                                        <span class="ms-2 general-error-message text-danger"> </span>
                                        </div>
                                        <input type="hidden" name="questions[nutrition-Q-5]" value="Which of these foods has the most iron?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2">
                                            <!-- First Row -->
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food1">
                                                    <label class="form-check-label" for="food1">
                                                        Spinach, cooked, 1/2 cup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food2">
                                                    <label class="form-check-label" for="food2">
                                                        Brown rice, cooked, 1 cup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="1" id="food3">
                                                    <label class="form-check-label" for="food3">
                                                        Grilled steak, 130g
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row px-2">
                                            <!-- Second Row -->
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food4">
                                                    <label class="form-check-label" for="food4">
                                                        Tuna, small tin, 90g
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food5">
                                                    <label class="form-check-label" for="food5">
                                                        Almonds/cashews, ~30 nuts
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food6">
                                                    <label class="form-check-label" for="food6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Question 6 -->
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">6. Approximately how many decisions do we make every day about what we eat? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-6]" value="Approximately how many decisions do we make every day about what we eat?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="decision1" name="ans[nutrition-Q-6][every_day_decisions_eat]">
                                                    <label class="form-check-label" for="decision1">50-100</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="decision2" name="ans[nutrition-Q-6][every_day_decisions_eat]">
                                                    <label class="form-check-label" for="decision2">100-150</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="decision3" name="ans[nutrition-Q-6][every_day_decisions_eat]">
                                                    <label class="form-check-label" for="decision3">150-200</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="1" data-option="Correct" data-correct="1" id="decision4" name="ans[nutrition-Q-6][every_day_decisions_eat]">
                                                    <label class="form-check-label" for="decision4">Over 200</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Unsure" data-correct="0" id="decision5" name="ans[nutrition-Q-6][every_day_decisions_eat]">
                                                    <label class="form-check-label" for="decision5">Unsure</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question 7 -->
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">7. Which of the following is NOT a 'Macronutrient'? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-7]" value="Which of the following is NOT a 'Macronutrient'?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="1" data-option="Correct" data-correct="1" id="Macronutrien1" name="ans[nutrition-Q-7][macronutrient]">
                                                    <label class="form-check-label" for="Macronutrien1">Iron</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="Macronutrien2" name="ans[nutrition-Q-7][macronutrient]">
                                                    <label class="form-check-label" for="Macronutrien2">Carbohydrate</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="Macronutrien3" name="ans[nutrition-Q-7][macronutrient]">
                                                    <label class="form-check-label" for="Macronutrien3">Protein</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="Macronutrien4" name="ans[nutrition-Q-7][macronutrient]">
                                                    <label class="form-check-label" for="Macronutrien4">Alcohol</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="Macronutrien5" name="ans[nutrition-Q-7][macronutrient]">
                                                    <label class="form-check-label" for="Macronutrien5">Fat</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Unsure" data-correct="0" id="Macronutrien6" name="ans[nutrition-Q-7][macronutrient]">
                                                    <label class="form-check-label" for="Macronutrien6">Unsure</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Question 8 -->
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">8. Which of these foods has the most calcium? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-8]" value="Which of these foods has the most calcium?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="calciu1" name="ans[nutrition-Q-8][most_calcium]">
                                                    <label class="form-check-label" for="calciu1">Baby spinach, 1 cup</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="1" data-option="Correct" data-correct="1" id="calcium2" name="ans[nutrition-Q-8][most_calcium]">
                                                    <label class="form-check-label" for="calcium2">Firm tofu, 100g</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="calciu3" name="ans[nutrition-Q-8][most_calcium]">
                                                    <label class="form-check-label" for="calciu3">Tuna, small tin, 90 g</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="calcium4" name="ans[nutrition-Q-8][most_calcium]">
                                                    <label class="form-check-label" for="calcium4">Almonds, 1/2 cup</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Unsure" data-correct="0" id="calcium5" name="ans[nutrition-Q-8][most_calcium]">
                                                    <label class="form-check-label" for="calcium5">Unsure</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question 9 -->
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">9. Which of these foods has the most fibre? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-9]" value="Which of these foods has the most fibre?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="fibre1" name="ans[nutrition-Q-9][most_fibre]">
                                                    <label class="form-check-label" for="fibre1">Banana, 1 large</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="1" data-option="Correct" data-correct="1" id="fibre2" name="ans[nutrition-Q-9][most_fibre]">
                                                    <label class="form-check-label" for="fibre2">Raw oats, 1/2 cup</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="fibre3" name="ans[nutrition-Q-9][most_fibre]">
                                                    <label class="form-check-label" for="fibre3">Cashews, 1 handful</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Wrong" data-correct="0" id="fibre4" name="ans[nutrition-Q-9][most_fibre]">
                                                    <label class="form-check-label" for="fibre4">Broccoli, 1/2 cup</label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="0" data-option="Unsure" data-correct="0" id="fibre5" name="ans[nutrition-Q-9][most_fibre]">
                                                    <label class="form-check-label" for="fibre5">Unsure</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                        <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="4">Back</button>
                                        <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="6">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box sports-form" id="div6" style="display: none;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                    <div class="d-flex align-items-center">
                                        <h5 class="m-0">1. Compared to a non-athlete, how much total protein (per day) can an athlete need?</h5>
                                        <span class="text-danger general-error-message"></span>
                                    </div>
                                    <input type="hidden" name="questions[sports-nutrition-Q-1]" value="Compared to a non-athlete, how much total protein (per day) can an athlete need?" />
                                </div>
                                <div class="card-body p-0">
                                    <div class="row px-2">
                                        <div class="col-md-6">
                                            <!-- Left Column -->
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein1" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="protein1">
                                                        A very similar amount
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="1" id="protein2" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="protein2">
                                                        Up to 2 times (2x) more
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein3" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="protein3">
                                                        3-4 times (3-4x) more
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Right Column -->
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein4" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="protein4">
                                                        5 times (5x) more
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein5" data-option="Unsure" data-correct="0">
                                                    <label class="form-check-label" for="protein5">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">2. Which of the following are signs that you are not eating enough to meet your training needs? </h5>
                                        <input type="hidden" name="questions[sports-nutrition-Q-2]" value="Which of the following are signs that you are not eating enough to meet your training needs?" />
                                    </div>
                                    <div class="row px-2">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed1" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="diagnosed1">
                                                        Loss of appetite
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed2" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="diagnosed2">
                                                        More injuries and/or illness
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed3" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="diagnosed3">
                                                        Poor performance or recovery
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed4" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="diagnosed4">
                                                        Weight loss
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed5" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="diagnosed5">
                                                        Menstrual cycle changes (if not on the pill)
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="0" name="ans[sports-nutrition-Q-2][]" id="diagnosed6" data-option="Unsure" data-correct="0">
                                                    <label class="form-check-label" for="diagnosed6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">3. On a heavy training day (training twice a day or high-intensity workouts) which foods should be increased? </h5>
                                        <input type="hidden" name="questions[sports-nutrition-Q-3]" value="On a heavy training day (training twice a day or high-intensity workouts) which foods should be increased?" />
                                    </div>
                                    <div class="row px-2">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest1" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="bloodTest1">
                                                        Protein-based foods like dairy, eggs, meat, tofu
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest2" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="bloodTest2">
                                                        Take away foods
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest3" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="bloodTest3">
                                                        Lollies, chips and chocolate bars
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="1" id="bloodTest4" data-option="Correct" data-correct="1">
                                                    <label class="form-check-label" for="bloodTest4">
                                                        Carbohydrate-based foods like rice, pasta, bread
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest5" data-option="Wrong" data-correct="0">
                                                    <label class="form-check-label" for="bloodTest5">
                                                        Fat-containing foods like avocado, nuts
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest6" data-option="Unsure" data-correct="0">
                                                    <label class="form-check-label" for="bloodTest6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                    <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="5" >Back</button>
                                    <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="7" >Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box sports-form" id="div7" style="display: none;">
                            <div class="card">
                                <div class="card">
                                    <div class="p-3 card-header bg-white">
                                        <div class="d-flex align-items-center">
                                            <h5 class="m-0">4. What is the most important role for 'Protein' in the body?</h5>
                                            <span class="text-danger general-error-message"></span>
                                        </div>
                                        <input type="hidden" name="questions[sports-nutrition-Q-4]" value="What is the most important role for 'Protein' in the body?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2">
                                            <!-- Question 4 -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest1" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest1">Fuel for low to moderate intensity exercise</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest2" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest2">Fuel for moderate to high intensity exercise</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest3" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest3">Delivery of oxygen to muscles</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="1" id="bodyTest4" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="bodyTest4">Muscle growth and repair</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest5" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest5">A healthy digestive system</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest6" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest6">Strong bones</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest7" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest7">Hydration</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest8" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="bodyTest8">Unsure</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Question 5 -->
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">5. Which of the following statements about the role of carbohydrates is NOT correct?</h5>
                                            <input type="hidden" name="questions[sports-nutrition-Q-5]" value="Which of the following statements about the role of carbohydrates is NOT correct?" />
                                        </div>
                                        <div class="row px-2">
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="-1" id="carbTest1" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="carbTest1">Support decision making</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="-1" id="carbTest2" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="carbTest2">Helping maintain competition performance levels</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="-1" id="carbTest3" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="carbTest3">Assists fuelling and recovery from training sessions</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="1" id="carbTest4" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="carbTest4">Major factor for gaining body fat</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="1" id="carbTest5" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="carbTest5">Increases inflammation in the body</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="0" id="carbTest6" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="carbTest6">Unsure</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Question 6 -->
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">6. What main fuels do muscles use during training?</h5>
                                            <input type="hidden" name="questions[sports-nutrition-Q-6]" value="What main fuels do muscles use during training?" />
                                        </div>
                                        <div class="row px-2">
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="-1" id="training1" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="training1">Protein</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="1" id="training2" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="training2">Carbs</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="1" id="training3" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="training3">Fat</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="-1" id="training4" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="training4">Iron</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="-1" id="training5" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="training5">Water</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="0" id="training6" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="training6">Unsure</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Question 7 -->
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">7. Which statements about iron are correct?</h5>
                                            <input type="hidden" name="questions[sports-nutrition-Q-7]" value="Which statements about iron are correct?" />
                                        </div>
                                        <div class="row px-2">
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="1" id="statement1" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="statement1">Females need over twice the amount of iron per day as men</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="1" id="statement2" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="statement2">Vegetarian athletes are higher risk of low iron as plants less iron in the food and it's harder to absorb</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="1" id="statement3" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="statement3">Female athletes are higher risk of low iron due to losing extra iron through periods</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="-1" id="statement4" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="statement4">Iron deficiency improves over time as the athlete matures</label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="0" id="statement5" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="statement5">Unsure</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                        <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="6">Back</button>
                                        <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="8">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Updated div with corrected data-option and data-correct -->
                        <div class="step-tab-box supplement-form" id="div8" style="display: none;">
                            <div class="card">
                                <div class="card">
                                    <div class="p-3 card-header bg-white">
                                        <div class="d-flex align-items-center">
                                            <h5 class="m-0">1. Which of the following statements about 'supplements' are true?</h5>
                                            <span class="text-danger general-error-message"></span>
                                        </div>
                                        <input type="hidden" name="questions[supplements-Q-1]" value="Which of the following statements about 'supplements' are true?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="-1" name="ans[supplements-Q-1][]" id="supplements1" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="supplements1">
                                                            All athletes should use supplements to perform at their best 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="-1" name="ans[supplements-Q-1][]" id="supplements2" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="supplements2">
                                                            It is not possible to consume enough nutrients through eating food alone (without supplements)
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="1" name="ans[supplements-Q-1][]" id="supplements3" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="supplements3">
                                                            Athletes should check with a Sports Dietitian before taking supplements                                                
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="1" name="ans[supplements-Q-1][]" id="supplements4" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="supplements4">
                                                            Eating a wide range of foods provides most athletes with the vitamins and minerals they need 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="-1" name="ans[supplements-Q-1][]" id="supplements5" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="supplements5">
                                                            Most supplements available in Australia are safe for athletes to use 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="0" name="ans[supplements-Q-1][]" id="supplements6" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="supplements6">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Question 2 -->
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">2. When choosing a supplement, you should?</h5>
                                            <input type="hidden" name="questions[supplements-Q-2]" value="When choosing a supplement, you should?" />
                                        </div>
                                        <div class="row px-2">
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes1" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="athletes1">
                                                            Use supplements used by professional athletes
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes2" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="athletes2">
                                                            Check with a mate for their opinion
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="1" id="athletes3" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="athletes3">
                                                            Choose a product that has had third party batch testing
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes4" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="athletes4">
                                                            Check with a naturopath
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes5" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="athletes5">
                                                            Ask staff at the local supplement store 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="0" id="athletes6" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="athletes6">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Question 3 -->
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">3. Regarding vitamin and minerals supplements, which statements are true?</h5>
                                            <input type="hidden" name="questions[supplements-Q-3]" value="Regarding vitamin and minerals supplements, which statements are true?" />
                                        </div>
                                        <div class="row px-2">
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="-1" id="vitamin1" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="vitamin1">
                                                            They are safe for all athletes to use 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="1" id="vitamin2" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="vitamin2">
                                                            Can assist athletes to correct a deficiency diagnosed by a Medical professional
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="-1" id="vitamin3" data-option="Wrong" data-correct="0">
                                                        <label class="form-check-label" for="vitamin3">
                                                            Vegetarians and vegans are not at risk of vitamin and mineral deficiences
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="1" id="vitamin4" data-option="Correct" data-correct="1">
                                                        <label class="form-check-label" for="vitamin4">
                                                            May be recommended for international competition where food variety is limited
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="0" id="vitamin5" data-option="Unsure" data-correct="0">
                                                        <label class="form-check-label" for="vitamin5">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                        <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="7">Back</button>
                                        <button id="next" type="button" class="btn btn-primary ms-auto submit-free-test">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-tab-box" id="div9" style="display: none;">
                            <div class="">
                                <div class="align-items-center flex-column mb-4 row">
                                    <div class="col-lg-6">
                                        <div class="score-meter-box">
                                            <div class="score-meter-text">
                                                <span class="meter-text-01">Needs <br>work </span>
                                                <span class="meter-text-02">Pretty <br>ordinary</span>
                                                <span class="meter-text-03">Not bad</span>
                                                <span class="meter-text-04">Good</span>
                                            </div>
                                            <div class="score-meter-box-frame">
                                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 500 243" style="enable-background:new 0 0 500 243;" xml:space="preserve">
                                                    <path d="M0,0v243h500V0H0z M474.7,233.7h-79.1c-4.9,0-9.2-3.6-9.9-8.5c-9.6-65.5-66.1-115.9-134.3-115.9s-124.6,50.3-134.3,115.9c-0.7,4.9-4.9,8.5-9.9,8.5H28.2c-5.9,0-10.5-5.1-10-11c11.3-119,111.4-212,233.2-212s221.9,93.1,233.2,212C485.2,228.6,480.6,233.7,474.7,233.7z" fill="#ffffff"/>
                                                </svg>
                                                <div class="bgradient-bg" style="background: conic-gradient(from -1.65deg at 48.15% 84.72%, #FF9500 -33.16deg, #FFDE48 31.45deg, #03741B 91.78deg, #CF080A 265.07deg, #FF9500 326.84deg, #FFDE48 391.45deg);"></div>
                                            </div>
                                            <span class="meter-arrow nutrition-result" style="transform: rotate(75deg);">
                                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 133 22" style="enable-background:new 0 0 133 22;" xml:space="preserve">
                                                    <path d="M91.8,0.4L3.4,8.7c-2.5,0.2-2.5,3.8,0,4.1l88.4,8.9c20.5-0.4,12.7-0.4,20.5-0.4c11.8,0,19.2,1.6,19.2-10.1c0-11.8-10-10.2-21.7-10.3C101.9,0.8,112,0.9,91.8,0.4z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <h4 class="text-center mt-4">General Nutrition <br>Knowledge</h4>
                                        <h3 class="text-center mt-1 text-black nutrition-percentage">40%</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="score-meter-main score-meter-locked supplement-plan">
                                            <div class="score-meter-box score-meter-out score-meter-box-3">
                                                <div class="score-meter-text">
                                                    <span class="meter-text-01">Likely at <br> risk</span>
                                                    <span class="meter-text-02">Pretty <br>ordinary</span>
                                                    <span class="meter-text-03">Decent</span>
                                                    <span class="meter-text-04">Nice</span>
                                                </div>
                                                <div class="score-meter-box-frame">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 500 243" style="enable-background:new 0 0 500 243;" xml:space="preserve">
                                                        <path d="M0,0v243h500V0H0z M474.7,233.7h-79.1c-4.9,0-9.2-3.6-9.9-8.5c-9.6-65.5-66.1-115.9-134.3-115.9s-124.6,50.3-134.3,115.9c-0.7,4.9-4.9,8.5-9.9,8.5H28.2c-5.9,0-10.5-5.1-10-11c11.3-119,111.4-212,233.2-212s221.9,93.1,233.2,212C485.2,228.6,480.6,233.7,474.7,233.7z" fill="#ffffff"/>
                                                    </svg>
                                                    <div class="bgradient-bg" style="background: conic-gradient(from -1.65deg at 48.15% 84.72%, #FF9500 -33.16deg, #FFDE48 31.45deg, #03741B 91.78deg, #CF080A 265.07deg, #FF9500 326.84deg, #FFDE48 391.45deg);"></div>
                                                </div>
                                                <span class="meter-arrow supplement-result" style="transform: rotate(120deg);">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 133 22" style="enable-background:new 0 0 133 22;" xml:space="preserve">
                                                        <path d="M91.8,0.4L3.4,8.7c-2.5,0.2-2.5,3.8,0,4.1l88.4,8.9c20.5-0.4,12.7-0.4,20.5-0.4c11.8,0,19.2,1.6,19.2-10.1c0-11.8-10-10.2-21.7-10.3C101.9,0.8,112,0.9,91.8,0.4z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <span class="score-lock">
                                                <svg class="score-lock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
                                                <g>
                                                    <circle class="st0" cx="400" cy="567.2" r="54.7"></circle>
                                                    <path class="st0" d="M621.2,326.9V219.3c0-120.2-97.8-217.9-217.9-217.9C279.5,1.3,178.8,102,178.8,225.7v101.2c-59.5,1.2-107.3,49.7-107.3,109.5v255.5c0,60.5,49,109.5,109.5,109.5h438c60.5,0,109.5-49,109.5-109.5V436.4C728.5,376.6,680.6,328.1,621.2,326.9z M255.5,225.7c0-81.5,66.3-147.8,147.8-147.8c77.9,0,141.3,63.4,141.3,141.3v104H255.5V225.7z M655.5,691.8c0,20.2-16.3,36.5-36.5,36.5H181c-20.2,0-36.5-16.3-36.5-36.5V436.4c0-20.2,16.3-36.5,36.5-36.5h42.8h352.3H619c20.2,0,36.5,16.3,36.5,36.5V691.8z"></path>
                                                </g>
                                                </svg>
                                                <svg class="score-unlock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
                                                <g>
                                                    <circle cx="400" cy="566.5" r="54.4"></circle>
                                                    <path d="M617.8,327.5H271.9c-7.3-18-14.2-37.7-19.4-58.2c-9.4-37.5-12.3-74.3-3.9-105.5c7.9-29.6,26.4-56.8,65.8-76.5c39.4-19.8,72.2-18.4,100.6-7.1c30.1,12,57.9,36.2,82.3,66.1c5,6.1,9.8,12.4,14.3,18.7c12.7,17.6,37.6,22.4,54.6,8.9c14.4-11.3,18.1-31.7,7.6-46.7c-6.3-9-13.1-18-20.3-26.8C525.2,65.5,487.9,31,441.9,12.7c-47.7-19-102.1-19.5-160.1,9.7c-58,29.1-90.1,73.1-103.3,122.7c-12.8,47.9-7.3,98.4,3.7,142c3.5,13.9,7.7,27.5,12.2,40.4h-12.1c-60.1,0-108.9,48.8-108.9,108.9v254.1c0,60.1,48.8,108.9,108.9,108.9h435.6c60.1,0,108.9-48.8,108.9-108.9V436.4C726.7,376.2,677.9,327.5,617.8,327.5zM654.1,690.5c0,20-16.3,36.3-36.3,36.3H182.2c-20,0-36.3-16.3-36.3-36.3V436.4c0-20,16.3-36.3,36.3-36.3h435.6c20,0,36.3,16.3,36.3,36.3V690.5z"></path>
                                                </g>
                                                </svg>
                                            </span>
                                            <h4 class="text-center mt-4">Supplement Nutrition Knowledge</h4>
                                            <h3 class="text-center mt-1 text-black supplement-percentage d-none"></h3>

                                            <div class="text-center mt-4">
                                                <!-- <a href="javascript:void(0);" class="btn btn-dark unlock-result" data-type="supplement">
                                                    <svg width="21" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="supplement-lock">
                                                        <path d="M8.16667 12.834V8.16732C8.16667 6.62022 8.78125 5.13649 9.87521 4.04253C10.9692 2.94857 12.4529 2.33398 14 2.33398C15.5471 2.33398 17.0308 2.94857 18.1248 4.04253C19.2188 5.13649 19.8333 6.62022 19.8333 8.16732V12.834M5.83333 12.834H22.1667C23.4553 12.834 24.5 13.8787 24.5 15.1673V23.334C24.5 24.6226 23.4553 25.6673 22.1667 25.6673H5.83333C4.54467 25.6673 3.5 24.6226 3.5 23.334V15.1673C3.5 13.8787 4.54467 12.834 5.83333 12.834Z" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg> 
                                                    <svg width="21" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="supplement-unlock d-none">
                                                    <path d="M19.8333 8.16732C19.8333 6.62022 19.2188 5.13649 18.1248 4.04253C17.0308 2.94857 15.5471 2.33398 14 2.33398C12.4529 2.33398 10.9692 2.94857 9.87521 4.04253C8.78125 5.13649 8.16667 6.62022 8.16667 8.16732V10M5.83333 12.834H22.1667C23.4553 12.834 24.5 13.8787 24.5 15.1673V23.334C24.5 24.6226 23.4553 25.6673 22.1667 25.6673H5.83333C4.54467 25.6673 3.5 24.6226 3.5 23.334V15.1673C3.5 13.8787 4.54467 12.834 5.83333 12.834Z" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    Unlock Results
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="score-meter-main score-meter-locked sport-plan">
                                            <div class="score-meter-box score-meter-out score-meter-box-2">
                                                <div class="score-meter-text">
                                                    <span class="meter-text-01">Untapped <br>potential</span>
                                                    <span class="meter-text-02">Much to <br>learn</span>
                                                    <span class="meter-text-03">Ok</span>
                                                    <span class="meter-text-04">Good <br>start</span>
                                                </div>
                                                <div class="score-meter-box-frame">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 500 243" style="enable-background:new 0 0 500 243;" xml:space="preserve">
                                                        <path d="M0,0v243h500V0H0z M474.7,233.7h-79.1c-4.9,0-9.2-3.6-9.9-8.5c-9.6-65.5-66.1-115.9-134.3-115.9s-124.6,50.3-134.3,115.9c-0.7,4.9-4.9,8.5-9.9,8.5H28.2c-5.9,0-10.5-5.1-10-11c11.3-119,111.4-212,233.2-212s221.9,93.1,233.2,212C485.2,228.6,480.6,233.7,474.7,233.7z" fill="#ffffff"/>
                                                    </svg>
                                                    <div class="bgradient-bg" style="background: conic-gradient(from -1.65deg at 48.15% 84.72%, #FF9500 -33.16deg, #FFDE48 31.45deg, #03741B 91.78deg, #CF080A 265.07deg, #FF9500 326.84deg, #FFDE48 391.45deg);"></div>
                                                </div>
                                                <span class="meter-arrow sport-result" style="transform: rotate(120deg);">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 133 22" style="enable-background:new 0 0 133 22;" xml:space="preserve">
                                                        <path d="M91.8,0.4L3.4,8.7c-2.5,0.2-2.5,3.8,0,4.1l88.4,8.9c20.5-0.4,12.7-0.4,20.5-0.4c11.8,0,19.2,1.6,19.2-10.1c0-11.8-10-10.2-21.7-10.3C101.9,0.8,112,0.9,91.8,0.4z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <span class="score-lock">
                                                <svg class="score-lock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
                                                <g>
                                                    <circle class="st0" cx="400" cy="567.2" r="54.7"></circle>
                                                    <path class="st0" d="M621.2,326.9V219.3c0-120.2-97.8-217.9-217.9-217.9C279.5,1.3,178.8,102,178.8,225.7v101.2c-59.5,1.2-107.3,49.7-107.3,109.5v255.5c0,60.5,49,109.5,109.5,109.5h438c60.5,0,109.5-49,109.5-109.5V436.4C728.5,376.6,680.6,328.1,621.2,326.9z M255.5,225.7c0-81.5,66.3-147.8,147.8-147.8c77.9,0,141.3,63.4,141.3,141.3v104H255.5V225.7z M655.5,691.8c0,20.2-16.3,36.5-36.5,36.5H181c-20.2,0-36.5-16.3-36.5-36.5V436.4c0-20.2,16.3-36.5,36.5-36.5h42.8h352.3H619c20.2,0,36.5,16.3,36.5,36.5V691.8z"></path>
                                                </g>
                                                </svg>
                                                <svg class="score-unlock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
                                                <g>
                                                    <circle cx="400" cy="566.5" r="54.4"></circle>
                                                    <path d="M617.8,327.5H271.9c-7.3-18-14.2-37.7-19.4-58.2c-9.4-37.5-12.3-74.3-3.9-105.5c7.9-29.6,26.4-56.8,65.8-76.5c39.4-19.8,72.2-18.4,100.6-7.1c30.1,12,57.9,36.2,82.3,66.1c5,6.1,9.8,12.4,14.3,18.7c12.7,17.6,37.6,22.4,54.6,8.9c14.4-11.3,18.1-31.7,7.6-46.7c-6.3-9-13.1-18-20.3-26.8C525.2,65.5,487.9,31,441.9,12.7c-47.7-19-102.1-19.5-160.1,9.7c-58,29.1-90.1,73.1-103.3,122.7c-12.8,47.9-7.3,98.4,3.7,142c3.5,13.9,7.7,27.5,12.2,40.4h-12.1c-60.1,0-108.9,48.8-108.9,108.9v254.1c0,60.1,48.8,108.9,108.9,108.9h435.6c60.1,0,108.9-48.8,108.9-108.9V436.4C726.7,376.2,677.9,327.5,617.8,327.5zM654.1,690.5c0,20-16.3,36.3-36.3,36.3H182.2c-20,0-36.3-16.3-36.3-36.3V436.4c0-20,16.3-36.3,36.3-36.3h435.6c20,0,36.3,16.3,36.3,36.3V690.5z"></path>
                                                </g>
                                                </svg>
                                            </span>
                                            <h4 class="text-center mt-4">Sports Nutrition Knowledge</h4>
                                            <h3 class="text-center mt-1 text-black sports-percentage d-none"></h3>

                                            <div class="text-center mt-4">
                                                <!-- <a href="javascript:void(0);" class="btn btn-dark unlock-result" data-type="sport">
                                                    <svg width="21" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="sport-lock">
                                                        <path d="M8.16667 12.834V8.16732C8.16667 6.62022 8.78125 5.13649 9.87521 4.04253C10.9692 2.94857 12.4529 2.33398 14 2.33398C15.5471 2.33398 17.0308 2.94857 18.1248 4.04253C19.2188 5.13649 19.8333 6.62022 19.8333 8.16732V12.834M5.83333 12.834H22.1667C23.4553 12.834 24.5 13.8787 24.5 15.1673V23.334C24.5 24.6226 23.4553 25.6673 22.1667 25.6673H5.83333C4.54467 25.6673 3.5 24.6226 3.5 23.334V15.1673C3.5 13.8787 4.54467 12.834 5.83333 12.834Z" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <svg width="21" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="sport-unlock d-none">
                                                    <path d="M19.8333 8.16732C19.8333 6.62022 19.2188 5.13649 18.1248 4.04253C17.0308 2.94857 15.5471 2.33398 14 2.33398C12.4529 2.33398 10.9692 2.94857 9.87521 4.04253C8.78125 5.13649 8.16667 6.62022 8.16667 8.16732V10M5.83333 12.834H22.1667C23.4553 12.834 24.5 13.8787 24.5 15.1673V23.334C24.5 24.6226 23.4553 25.6673 22.1667 25.6673H5.83333C4.54467 25.6673 3.5 24.6226 3.5 23.334V15.1673C3.5 13.8787 4.54467 12.834 5.83333 12.834Z" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>  
                                                    Unlock Results
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6 align-items-center d-flex flex-column justify-content-center mt-4 mt-lg-0">
                                        <div>
                                            <div class="d-lg-block text-center">
                                                <a href="{{ route('front.sub-home-page') }}#sport-plans" class="btn btn-primary d-block">
                                                    Purchase Plan
                                                </a>
                                            </div>
                                            <span class="d-block text-center my-2 my-lg-3">OR</span>
                                            <div class="d-lg-block text-center">
                                                <a href="{{ route('front.sample-plan') }}" class="btn btn-primary d-block">
                                                    View Sample Plan
                                                </a>
                                            </div>
                                        </div>
                                    </div> -->
                                </div> 
                                <div class="nutrition-login-book">
                                    <div class="card border-0 shadow-none overflow-hidden mt-3 talk-expert-box" style="background:#5e96e8">
                                        <div class="card-body p-4">
                                            <div class="p-md-3 row align-items-center">
                                                <div class="col-lg-7">
                                                    <h3>Sports Nutrition Plans</h3>
                                                    <!-- <figure>
                                                        <img src="https://booking.biohealthpassport.com.au/public/uploads/front_logo/1727981512_1727875441_logo.png" alt="">
                                                    </figure> -->
                                                    <p>Displays the nutrition or fitness plan a subscribed to, including details on duration, customization options, and renewal status. Helps manage their plan effectively.</p>
                                                    <a href="{{ route('front.sub-home-page') }}#sport-plans" class="btn btn-white" target="_blank">Purchase Plan
                                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.4165 15.5827L15.5832 6.41602M15.5832 6.41602H6.4165M15.5832 6.41602V15.5827" stroke="#124E4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="">
                                                        <figure class="m-auto" style="max-width: inherit;">
                                                            <img src="{!! frontAssets('images/purchase-plan-image.webp') !!}" class="img-fluid" alt="">
                                                        </figure>
                                                        <!-- <div class="kerry-info">
                                                            <h5>Kerry O'Bryan</h5>
                                                            <p>MNutr&amp;Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                                                            <p>(Dietitian /Sports Scientist/Strength &amp; Conditioning Coach)</p>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nutrition-login-book">
                                    <div class="card border-0 shadow-none overflow-hidden mt-3 talk-expert-box" style="background:#6c757d">
                                        <div class="card-body p-4">
                                            <div class="p-md-3 row align-items-center">
                                                <div class="col-lg-7">
                                                    <h3>Optimise Your Health & Performance</h3>
                                                    <!-- <figure>
                                                        <img src="https://booking.biohealthpassport.com.au/public/uploads/front_logo/1727981512_1727875441_logo.png" alt="">
                                                    </figure> -->
                                                    <p>Personalised nutrition and fitness plans to help you stay strong, energised, and at your best every day.</p>
                                                    <a href="{{ route('front.sub-home-page') }}#sample-plan-section" class="btn btn-white" target="_blank">Sample Plan
                                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.4165 15.5827L15.5832 6.41602M15.5832 6.41602H6.4165M15.5832 6.41602V15.5827" stroke="#124E4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="">
                                                        <figure class="m-auto" style="max-width: inherit;">
                                                            <img src="{!! frontAssets('images/your-purchased-plan-0001.webp') !!}" class="img-fluid" alt="">
                                                        </figure>
                                                        <!-- <div class="kerry-info">
                                                            <h5>Kerry O'Bryan</h5>
                                                            <p>MNutr&amp;Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                                                            <p>(Dietitian /Sports Scientist/Strength &amp; Conditioning Coach)</p>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nutrition-login-book card-body p-0">
                                    <div class="card border-0 shadow-none overflow-hidden mt-3 talk-expert-box">
                                        <div class="card-body p-4">
                                            <div class="p-md-3 row">
                                                <div class="col-lg-6">
                                                    <h6>Powered by BioHealth<span>Passport</span></h6>
                                                    <figure>
                                                        <img src="https://booking.biohealthpassport.com.au/public/uploads/front_logo/1727981512_1727875441_logo.png" class="img-fluid" alt="">
                                                    </figure>
                                                    <h3>Get answers from a real-life expert. Not a chat bot.</h3>
                                                    <a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-white" target="_blank">Book Now 
                                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.4165 15.5827L15.5832 6.41602M15.5832 6.41602H6.4165M15.5832 6.41602V15.5827" stroke="#124E4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="kerry-info-box">
                                                        <figure>
                                                            <img src="https://booking.biohealthpassport.com.au/public/uploads/hero01.webp" alt="" class="img-fluid">
                                                        </figure>
                                                        <div class="kerry-info">
                                                            <h5>Kerry O'Bryan</h5>
                                                            <p>MNutr&amp;Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                                                            <p>(Dietitian /Sports Scientist/Strength &amp; Conditioning Coach)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                    <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="8">Back</button>
                                    <!-- <button id="next" type="button" class="btn btn-primary ms-auto last-step">Next</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Get your results instantly. </h5>
                    <button type="button" class="btn-close detail-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="detailsForm">
                        <input type="hidden" id="formType" name="formType">

                        <div class="mb-3">
                            <label for="name" class="form-label">First Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="mainSport" class="form-label">Main Sport:</label>
                            <input type="text" class="form-control" id="mainSport" name="mainSport" required>
                        </div> -->
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile:</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div> -->
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirmation & Email Capture Modal -->
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thank You for Your Interest!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmationMessage"></p>
                    <p>We'd love to send you some nutrition information specific to your sport. Please enter your details below:</p>
                    <form id="userDetailsForm">
                        <div class="form-group mt-3">
                            <label for="userName"><strong>Name:</strong></label>
                            <input type="text" id="userName" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="userEmail"><strong>Email:</strong></label>
                            <input type="email" id="userEmail" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" id="submitUserDetails">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal show" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="forgotPasswordForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Enter your email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content" style="z-index: 1100;">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Validation Errors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="errorModalBody">
                <!-- Error messages will be injected here -->
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <script>
        setTimeout(function () {
            document.getElementById("linkedin-feed-1").innerHTML = `
                <iframe src="https://www.linkedin.com/embed/feed/update/urn:li:share:7143854792111501312" 
                        height="867" 
                        width="504" 
                        allowfullscreen="" 
                        title="LinkedIn Feed 1">
                </iframe>
            `;
            document.getElementById("linkedin-feed-2").innerHTML = `
                <iframe src="https://www.linkedin.com/embed/feed/update/urn:li:share:7143431925322383360" 
                        height="729" 
                        width="504" 
                        frameborder="0" 
                        allowfullscreen="" 
                        title="Embedded post">
                </iframe>
            `;
        }, 5000);

        document.addEventListener('DOMContentLoaded', function () {
            const toggleLink = document.getElementById('toggle-coupon-link');
            const couponDetails = document.getElementById('coupon-details');
            const promoInput = document.getElementById('promo-code');
            const promoMessage = document.getElementById('promo-message');

            if (toggleLink) {
                toggleLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    const isHidden = couponDetails.classList.contains('d-none');

                    // Toggle coupon section visibility
                    couponDetails.classList.toggle('d-none');

                    // Update link text
                    toggleLink.textContent = isHidden ? 'Remove a Coupon Code' : 'Add a Coupon Code';

                    // If hiding, clear input and promo message
                    if (!isHidden) {
                        promoInput.value = '';
                        if (promoMessage) {
                            promoMessage.style.display = 'none'; // or promoMessage.innerHTML = ''
                        }
                    }
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const purchaseModal = document.getElementById('purchaseModal');

            purchaseModal.addEventListener('hidden.bs.modal', function () {
                // Reset the form inside the modal
                document.getElementById('payment-form').reset();
                
                // Reset Stripe card element (if applicable)
                if (typeof stripe !== "undefined" && typeof card !== "undefined") {
                    card.clear();
                }

                // Clear any validation messages or applied promo codes
                document.getElementById('promo-message').textContent = "";
                document.getElementById('discount').value = "";
                document.getElementById('coupon-details').classList.add('d-none'); // Hide coupon details
                document.getElementById('toggle-coupon-link').classList.remove('active'); // Reset link style
                document.getElementById('payment-details').style.removeProperty('display');

            });
        });

        $('#registerModal').on('hidden.bs.modal', function () {
            console.log("Register modal closed");
            // Perform any additional actions on close
            $('#TakeTestModel').removeClass('blur-background');

        });

        $('#detailsModal').on('hidden.bs.modal', function () {
            console.log("Details modal closed");
            // Perform any additional actions on close
            $('#TakeTestModel').removeClass('blur-background');
        });

        $('#errorModal').on('hidden.bs.modal', function () {
            $('#purchaseModal').removeClass('blur-background');

        });

        $(document).ready(function () {
            $('.sample-plan-modal').on('click', function () {
                $('#sample-plan-modal').modal('show');
            });
        });
        $(document).ready(function () {
            $("#sport").change(function () {
                let selectedSport = $(this).val();
                let sportGameSelect = $("#sport_game");

                if (selectedSport) {
                    $.ajax({
                        url: "{{ route('front.get-sports-games') }}", // Replace with your actual route URL to,
                        type: "GET",
                        data: { category: selectedSport },
                        dataType: "json",
                        success: function (response) {
                            sportGameSelect.html('<option value="">Select Your Sport Game</option>'); // Reset dropdown

                            if (Array.isArray(response)) {
                                $.each(response, function (index, game) {
                                    sportGameSelect.append(`<option value="${game}">${game}</option>`);
                                });
                            }
                        },
                        error: function (xhr) {
                            console.error("Error fetching sports games:", xhr.responseText);
                        }
                    });
                } else {
                    sportGameSelect.html('<option value="">Select Your Sport Game</option>');
                }
            });

            $("#sport-form").submit(function(e) {
                e.preventDefault();

                // Get user-selected values
                var sport = $("#sport").val();
                var state = $("select[name='state']").val();
                var sportGame = $("#sport_game").val();

                // Validate selections
                if (!sport || !state || !sportGame) {
                    alert("Please select a sport, state, and game.");
                    return;
                }

                // Generate confirmation message
                var message = `Thank you for submitting your interest in ${sport.replace('_', ' ')} under the game of ${sportGame} in ${state}.`;
                $("#confirmationMessage").text(message);

                // Show the modal
                $("#confirmationModal").modal("show");
            });
            
            // Handle user details submission
            $("#userDetailsForm").submit(function (e) {
                e.preventDefault();
                
                $('submitUserDetails').prop('disabled', true);

                var userName = $("#userName").val();
                var userEmail = $("#userEmail").val();
                var sport = $("#sport").val();
                var state = $("select[name='state']").val();
                var sportGame = $("#sport_game").val();

                if (!userName || !userEmail) {
                    alert("Please enter your name and email.");
                    return;
                }

                // Send data via AJAX (Modify backend URL accordingly)
                $.ajax({
                    url: "{{ route('front.sport-search') }}", // Backend endpoint to save details
                    method: "POST",
                    data: {
                        name: userName,
                        email: userEmail,
                        sport: sport,
                        sport_game: sportGame,
                        state: state,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        alert("Thank you! We will send you relevant nutrition information.");
                        $("#confirmationModal").modal("hide");
                        $("#sport-form")[0].reset();
                    },
                    error: function () {
                        alert("Error saving your details. Please try again.");
                    }
                });
            });

            // Reset form when modal is closed
            $("#confirmationModal").on("hidden.bs.modal", function () {
                $("#sport-form")[0].reset(); // Reset the search form
            });
        });

        $(document).ready(function () {
            let currentQuizId = null;
            // Show the modal on clicking the start test button
            $('#takeFreeTest').on('click', function () {
                // Track quiz click
                $.ajax({
                    url: "{{ route('front.quiz.start') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Quiz start response:', response); // Debug log
                        if (response.success) {
                            currentQuizId = response.quiz_id;
                            console.log('Quiz started with ID:', currentQuizId); // Debug log
                            $('#TakeTestModel').modal('show');
                        } else {
                            alert('Error starting quiz: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error starting quiz:', xhr.responseText);
                        alert('Error starting quiz. Please try again.');
                    }
                });
            });

            $('.unlock-result').on('click', function () {
                let type = $(this).data('type'); // Get data-type (sports or supplement)

                $('#formType').val(type); // Store type in the hidden input field

                $('#TakeTestModel').addClass('blur-background'); // Add blur effect
                $('#detailsModal').modal('show'); // Show modal
                $('.sport-plan .unlock-result').removeClass('btn-dark').addClass('btn-primary');
                $('.supplement-plan .unlock-result').removeClass('btn-dark').addClass('btn-primary');

            });

            $('.detail-modal-close').on('click', function () {
                $('#detailsModal').modal('hide');
                $('#TakeTestModel').removeClass('blur-background'); // Remove blur
                $type = $('#formType').val();
                $('.sport-plan .unlock-result').removeClass('btn-primary').addClass('btn-dark');
                $('.supplement-plan .unlock-result').removeClass('btn-primary').addClass('btn-dark');
                // if (type === 'sport') {
                //     $('.sport-plan .unlock-result').removeClass('btn-primary').addClass('btn-dark');
                // }
                // if (type === 'supplement') {
                //     $('.supplement-plan .unlock-result').removeClass('btn-primary').addClass('btn-dark');
                // }
            })
            let totalAnswerCount = 0;  // Initialize totalAnswerCount to 0 globally for the entire script

            // Initialize all the necessary variables
            const stepCircles = document.querySelectorAll('.tab-steps');
            const stepTabs = document.querySelectorAll(".step-tab-box");
            const showStepButtons = document.querySelectorAll('.showStepTab');
            const submitButton = document.querySelector(".submit-free-test");
            const registerModal = $("#registerModal");
            const loginModal = $("#testLoginModal");
            const registerForm = $("#register-form");
            const loginForm = $("#test-login-form");
            const loginLink = $(".login-link");
            const registerLink = $(".register-link");
            const currentModal = $("#TakeTestModel");

            // Initialize totalAnswerCounts for each form with default values of 0
            // let totalAnswerCounts = {
            //     'nutrition-form': 0,
            //     'sports-form': 0,
            //     'supplement-form': 0
            // };

            let currentStep = 0;  // Track the active step index
            const stepsData = {};  // Object to store all steps data

            // Initially, show only the first step-tab-box
            stepTabs.forEach((tab, index) => {
                tab.style.display = index === 0 ? "block" : "none";
            });

            // Function to validate fields in the current step
            function validateStep(stepIndex) {
                const stepTab = stepTabs[stepIndex]; // Get current step tab
                const inputs = stepTab.querySelectorAll('input, textarea, select');
                let isValid = true;
                const errorMessage = "* Please select an answer for this question.";

                // Loop through each input to validate
                inputs.forEach(input => {
                    // Reset border color before validation
                    input.style.border = "";

                    if (input.type === "radio" || input.type === "checkbox") {
                        // Validation for radio/checkbox inputs
                        const groupName = input.name;
                        const checkedInput = document.querySelector(`input[name="${groupName}"]:checked`);

                        if (!checkedInput) {
                            isValid = false;
                            // Apply red border to all unchecked inputs in the group
                            document.querySelectorAll(`input[name="${groupName}"]`).forEach(el => {
                                el.style.border = "1px solid red";
                            });
                        } else {
                            // Reset border for valid radio/checkbox group
                            document.querySelectorAll(`input[name="${groupName}"]`).forEach(el => {
                                el.style.border = "";
                            });
                        }
                    } else if (input.type === "text" || input.type === "textarea" || input.tagName === "SELECT") {
                        // Validation for text inputs, textarea, and select elements
                        if (input.value.trim() === "") {
                            input.style.border = "1px solid red";
                            isValid = false;
                        } else {
                            input.style.border = "";
                        }
                    }
                });

                // Display error message if validation fails
                const cardBody = stepTab.querySelector('.card-body');
                if(cardBody) {
                    let errorMessageSpan = cardBody.querySelector('.general-error-message');
                    if (!errorMessageSpan) {
                        // Create error message span if not present
                        errorMessageSpan = document.createElement("span");
                        errorMessageSpan.className = "text-danger general-error-message m-3";
                        cardBody.appendChild(errorMessageSpan);
                    }

                    errorMessageSpan.textContent = errorMessage;
                    errorMessageSpan.style.display = isValid ? "none" : "block";
                }
                return isValid;
            }

            function collectStepData(currentStep) {
                console.log(currentStep);
                const form = document.querySelector(`#div${currentStep}`);
                console.log(form);
                if (!form) return {};

                const stepData = JSON.parse(localStorage.getItem("testStepsData")) || {};
                const formClass = Array.from(form.classList).find(cls => cls.endsWith('-form'));
                console.log(stepData);
                console.log(formClass);
                if (!formClass) {
                    console.error('Form class not found for step:', currentStep);
                    return {};
                }

                if (!stepData[formClass]) {
                    stepData[formClass] = {};
                }

                const questionInputs = form.querySelectorAll("input[type='hidden'][name^='questions']");
                
                questionInputs.forEach(questionInput => {
                    const questionText = questionInput.value;
                    if (!questionText) return;

                    stepData[formClass][questionText] = {};

                    // ─────────────────────────────────────────
                    // 1. TABLE-based Questions (with radios)
                    // ─────────────────────────────────────────
                    const cardContainer = questionInput.closest(".card");
                    if (cardContainer) {
                        const table = cardContainer.querySelector("table");
                        if (table) {
                            const rows = table.querySelectorAll("tbody tr");

                            rows.forEach(row => {
                                const foodNameElem   = row.querySelector("td:first-child");
                                const selectedAnswer = row.querySelector("input[type='radio']:checked");

                                if (foodNameElem && selectedAnswer) {
                                    const foodName    = foodNameElem.textContent.trim();
                                    const answerValue = parseFloat(selectedAnswer.value) || 0;
                                    const optionType  = selectedAnswer.dataset.option || null;
                                    const correctFlag = parseInt(selectedAnswer.dataset.correct || 0);

                                    stepData[formClass][questionText][foodName] = {
                                        value: answerValue,
                                        option: optionType,
                                        correct: correctFlag
                                    };

                                    updateAnswerCount(formClass, answerValue);
                                }
                            });
                        }
                    }

                    // ─────────────────────────────────────────
                    // 2. Regular Radio Buttons (outside tables)
                    // ─────────────────────────────────────────
                    form.querySelectorAll(`input[type="radio"][name^='ans[${questionInput.name.replace("questions[", "").replace("]", "")}]']:checked`)
                        .forEach(radio => {
                            const labelElem = radio.closest(".form-check")?.querySelector("label") || form.querySelector(`label[for="${radio.id}"]`);
                            if (!labelElem) return;

                            const label       = labelElem.textContent.trim();
                            const answerValue = parseFloat(radio.value) || 0;
                            const optionType  = radio.dataset.option || null;
                            const correctFlag = parseInt(radio.dataset.correct || 0);

                            stepData[formClass][questionText][label] = {
                                value: answerValue,
                                option: optionType,
                                correct: correctFlag
                            };

                            updateAnswerCount(formClass, answerValue);
                        });

                    // ─────────────────────────────────────────
                    // 3. Checkbox Handling (if used)
                    // ─────────────────────────────────────────
                    form.querySelectorAll(`input[type="checkbox"][name^='ans[${questionInput.name.replace("questions[", "").replace("]", "")}]']:checked`)
                        .forEach(checkbox => {
                            const labelElem = checkbox.closest("label") || form.querySelector(`label[for="${checkbox.id}"]`);
                            if (!labelElem) return;

                            const labelText   = labelElem.textContent.trim();
                            const checkboxVal = parseFloat(checkbox.value) || 0;
                            const optionType  = checkbox.dataset.option || null;
                            const correctFlag = parseInt(checkbox.dataset.correct || 0);

                            stepData[formClass][questionText][labelText] = {
                                value: checkboxVal,
                                option: optionType,
                                correct: correctFlag
                            };

                            updateAnswerCount(formClass, checkboxVal);
                        });
                });

                console.log(stepData);
                localStorage.setItem("testStepsData", JSON.stringify(stepData));

                console.log(`Step Data Collected for ${formClass}:`, stepData);
                // Track progress
                $.ajax({
                    url: "{{ route('front.track.quiz.progress') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        stepData: stepData,
                        currentStep: currentStep
                    },
                    success: function(response) {
                        console.log('Quiz progress tracked');
                    }
                });

                return stepData;
            }

            function collectCurrentStepData(currentStep) {
                console.log(currentStep);
                const form = document.querySelector(`#div${currentStep}`);
                if (!form) return {};

                const formClass = Array.from(form.classList).find(cls => cls.endsWith('-form'));
                if (!formClass) {
                    console.error('Form class not found for step:', currentStep);
                    return {};
                }

                const currentStepData = {};
                currentStepData[formClass] = {};

                const questionInputs = form.querySelectorAll("input[type='hidden'][name^='questions']");
                questionInputs.forEach(questionInput => {
                    const questionText = questionInput.value;
                    if (!questionText) return;

                    currentStepData[formClass][questionText] = {};

                    // ─ Table-based answers ─
                    const cardContainer = questionInput.closest(".card");
                    if (cardContainer) {
                        const table = cardContainer.querySelector("table");
                        if (table) {
                            const rows = table.querySelectorAll("tbody tr");
                            rows.forEach(row => {
                                const foodNameElem   = row.querySelector("td:first-child");
                                const selectedAnswer = row.querySelector("input[type='radio']:checked");

                                if (foodNameElem && selectedAnswer) {
                                    const foodName = foodNameElem.textContent.trim();
                                    currentStepData[formClass][questionText][foodName] = {
                                        value: parseFloat(selectedAnswer.value) || 0,
                                        option: selectedAnswer.dataset.option || null,
                                        correct: parseInt(selectedAnswer.dataset.correct || 0)
                                    };
                                }
                            });
                        }
                    }

                    // ─ Normal radio buttons ─
                    form.querySelectorAll(`input[type="radio"][name^='ans[${questionInput.name.replace("questions[", "").replace("]", "")}]']:checked`)
                        .forEach(radio => {
                            const labelElem = radio.closest(".form-check")?.querySelector("label") || form.querySelector(`label[for="${radio.id}"]`);
                            if (!labelElem) return;

                            const label = labelElem.textContent.trim();
                            currentStepData[formClass][questionText][label] = {
                                value: parseFloat(radio.value) || 0,
                                option: radio.dataset.option || null,
                                correct: parseInt(radio.dataset.correct || 0)
                            };
                        });

                    // ─ Checkboxes ─
                    form.querySelectorAll(`input[type="checkbox"][name^='ans[${questionInput.name.replace("questions[", "").replace("]", "")}]']:checked`)
                        .forEach(checkbox => {
                            const labelElem = checkbox.closest("label") || form.querySelector(`label[for="${checkbox.id}"]`);
                            if (!labelElem) return;

                            const label = labelElem.textContent.trim();
                            currentStepData[formClass][questionText][label] = {
                                value: parseFloat(checkbox.value) || 0,
                                option: checkbox.dataset.option || null,
                                correct: parseInt(checkbox.dataset.correct || 0)
                            };
                        });
                });
                console.log(currentStepData);
                return currentStepData;
            }

            const totalAnswerCounts = {}; 

            // Helper function to update totalAnswerCount based on selected answer value (radio or checkbox)
            function updateAnswerCount(formClass, value) {
                if (!totalAnswerCounts[formClass]) {
                    totalAnswerCounts[formClass] = 0; // Initialize if not set
                }

                console.log(`Before Update: ${formClass} =`, totalAnswerCounts[formClass], `Value =`, value);

                if (value === 1 || value === 0.5 || value === -1) {
                    totalAnswerCounts[formClass] += value;
                }

                console.log(`After Update: ${formClass} =`, totalAnswerCounts[formClass]);
            }

            function updateModalTitle(stepIndex) {
                const titles = [
                    "Nutrition Knowledge Questions",  // Step 0
                    "Sports Nutrition Principles",   // Step 1
                    "Supplement Knowledge",          // Step 2
                ];
                console.log('step - ',stepIndex);
                const modalTitle = document.getElementById('testModalLabel');
                if (modalTitle) {
                    if (stepIndex < 5) {
                        modalTitle.textContent = "Nutrition Knowledge Questions";
                    }else if (stepIndex == 5 ){
                        modalTitle.textContent = "Sports Nutrition Principles";
                    }else if(stepIndex == 6){
                        modalTitle.textContent = "Sports Nutrition Principles";
                    } else if (stepIndex == 7 ) {
                        modalTitle.textContent = "Supplement Knowledge";
                    } else if (stepIndex == 8 ) {
                        modalTitle.textContent = "Your Results";  // Default title for other steps
                    }
                }
            }

            function updateMeterArrows(type) {
                const maxTotal = 5;
                const degree = 180 / maxTotal;
                let totalDegree = Math.max(0, totalAnswerCounts['supplement-form'] * degree); // Ensure non-negative
                const percentage = Math.max(0, (totalAnswerCounts['supplement-form'] / maxTotal) * 100);
                
                $('.supplement-percentage').text(Math.round(percentage) + "%");
                $('.score-meter-box-3').removeClass('score-meter-out');
                $('.meter-arrow.supplement-result').css('transform', 'rotate(' + totalDegree + 'deg)');

                const maxTotal2 = 15;
                const degree2 = 180 / maxTotal2;
                let totalDegree2 = Math.max(0, totalAnswerCounts['sports-form'] * degree2); // Ensure non-negative
                const percentage2 = Math.max(0, (totalAnswerCounts['sports-form'] / maxTotal2) * 100);
                
                $('.sports-percentage').text(Math.round(percentage2) + "%");
                $('.score-meter-box-2').removeClass('score-meter-out');
                $('.meter-arrow.sport-result').css('transform', 'rotate(' + totalDegree2 + 'deg)');

                let nutritiondegree = 5.14285714;
                let nutritiontotalDegree = Math.max(0, totalAnswerCounts['nutrition-form'] * nutritiondegree); // Ensure non-negative
                const nutritionmaxTotal = 35;
                const nutritionpercentage = Math.max(0, (totalAnswerCounts['nutrition-form'] / nutritionmaxTotal) * 100);
                
                $('.nutrition-percentage').text(Math.round(nutritionpercentage) + "%");
                $('.meter-arrow.nutrition-result').css('transform', 'rotate(' + nutritiontotalDegree + 'deg)');
            }

            // Event listener for step navigation buttons (previous/next steps)
            showStepButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetStep = parseInt(button.getAttribute('target'), 10) - 1;

                    if (targetStep > currentStep && !validateStep(currentStep)) {
                        console.log("Validation failed for step:", currentStep);
                        return; // Stop progression if validation fails
                    }
                    console.log(currentStep);
                    // Collect data for the current step
                   // ✅ 1. Collect data from current step
                    const stepJson = collectStepData(currentStep);
                    const currentStepJson = collectCurrentStepData(currentStep + 1);
                    // console.log(currentStepJson);
                    // ✅ 2. Send to backend
                    $.ajax({
                        url: "{{ route('front.quiz.save-step') }}",
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: {
                            quiz_id: currentQuizId,
                            step: currentStep+1,
                            stepData: JSON.stringify(currentStepJson)
                        },
                        success: function (response) {
                            console.log('Step saved successfully:', response);
                        },
                        error: function (xhr) {
                            console.error('Failed to save step:', xhr.responseText);
                        }
                    });
                    // Update modal title dynamically based on the step
                    updateModalTitle(targetStep);

                    // Update active step indicators
                    stepCircles.forEach((step, index) => {
                        step.classList.toggle('active', index <= targetStep);
                    });

                    // Toggle the visibility of step tabs
                    stepTabs.forEach((tab, index) => {
                        tab.style.display = index === targetStep ? "block" : "none";
                    });

                    currentStep = targetStep;

                    console.log("Current step set to:", currentStep + 1);
                    console.log("Scrolling to top...");

                    setTimeout(() => {
                        const modalBody = $('#TakeTestModel .modal-body');  // Target the modal body specifically
                        modalBody.scrollTop(0); // Scroll to the top of the modal body
                    }, 100);// Adjust the delay as needed  

                    $('.supplement-plan .unlock-result').removeClass('d-none');
                    $('.sport-plan .unlock-result').removeClass('d-none');
                });
            });

            submitButton.addEventListener("click", () => {
                currentStep = 8;
                updateModalTitle(currentStep);

                if (!validateStep(currentStep)) {
                    return; // Stop submission if validation fails
                }

                const stepsData = {};
                Object.keys(totalAnswerCounts).forEach(form => totalAnswerCounts[form] = 0); // Reset total counts per form

                // Collect step data for all steps
                for (let step = 1; step <= 8; step++) {
                    const stepData = collectStepData(step);

                    localStorage.setItem(`step-${step}-data`, JSON.stringify(stepData));
                    stepsData[`step-${step}`] = stepData;
                }

                const step8Data = collectCurrentStepData(8); // returns a minimal object
                console.log("Collected Step 8 Data:", step8Data);

                $.ajax({
                    url: "{{ route('front.quiz.save-step') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {
                        quiz_id: currentQuizId,
                        step: 8,
                        stepData: JSON.stringify(step8Data)
                    },
                    success: function (response) {
                        console.log('Step saved successfully:', response);
                    },
                    error: function (xhr) {
                        console.error('Failed to save step:', xhr.responseText);
                    }
                });
                // Save all data to local storage
                // localStorage.setItem("testStepsData", JSON.stringify(stepsData));
                localStorage.setItem("totalAnswerCounts", JSON.stringify(totalAnswerCounts));

                $('#detailsModal').modal('show');
                $('#TakeTestModel').addClass('blur-background'); // Add blur effect
            });

            function handleCredentialResponse(response) {
                console.log("Google User Token:", response.credential);

                // First, verify Google login
                fetch('{{ route("front.google.check-login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ token: response.credential })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("User Data:", data);

                    if (data.status == "logged_in") {
                        // Track completion
                        $.ajax({
                            url: "{{ route('front.track.quiz.completion') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: {
                                userId: user_id,
                                email: $('#email').val()
                            },
                            success: function(response) {
                                console.log('Quiz completion tracked');
                            }
                        });
                        // Google login successful, now send questionnaire data
                        sendQuestionnaireData(data.user_id);
                    } else {
                        alert("Login failed, Please try again.");
                        console.error("Google Login Failed");
                    }
                })
                .catch(error => console.error("Error in Google Login:", error));
            }

            function sendQuestionnaireData(user_id) {
                const stepsData = JSON.parse(localStorage.getItem("testStepsData"));
                const totalAnswerCounts = JSON.parse(localStorage.getItem("totalAnswerCounts"));
                console.log(stepsData);
                console.log(user_id);
                if (!stepsData || !totalAnswerCounts) {
                    console.error("No questionnaire data found!");
                    return;
                }

                fetch('{{ route("front.submit-free-test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        userId: user_id,
                        testData: stepsData,
                        totalAnswerCount: totalAnswerCounts
                    })
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        console.log("Questionnaire Data Saved Successfully:", response);
                        
                        // Move to the next step after saving data
                        $('#div9').css('display', 'block');
                        $('#div8').css('display', 'none');
                        $('#step-9').addClass('active');
                        alert(response.message);
                        updateMeterArrows('nutrition-form');
                        localStorage.removeItem("testStepsData");
                        localStorage.removeItem("totalAnswerCounts");
                    } else {
                        alert(response.message);
                        console.error("Failed to Save Questionnaire Data:", response.message);
                        localStorage.removeItem("testStepsData");
                        localStorage.removeItem("totalAnswerCounts");
                    }
                })
                .catch(error => console.error("Error Saving Questionnaire Data:", error));
            }

            $('#detailsForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                
                let type = $('#formType').val() // Retrieve stored type (sports or supplement)
                let email = $('#detailsForm').find('#email').val();
                let name = $('#detailsForm').find('#name').val();
                let phone = $('#detailsForm').find('#mobile').val();
                let password = null;

                const testData = JSON.parse(localStorage.getItem("testStepsData"));
                const totalAnswerCount = JSON.parse(localStorage.getItem("totalAnswerCounts"));

                // Prepare data for submission
                const registrationData = {
                    name,
                    email,
                    password,
                    phone
                };

                // Simulate API request to register the user
                $.ajax({
                    url: "{{ route('front.register') }}",
                    method: "POST",
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify(registrationData),
                    success: function (data) {
                        if (data.success) {
                            const userId = data.user.id;
                            
                            $.ajax({
                                url: "{{ route('front.quiz.complete') }}",
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    quiz_id: currentQuizId,
                                    user_id: userId,
                                    totalAnswerCounts: totalAnswerCounts
                                },
                                success: function(response) {
                                    console.log('Quiz completion tracked');
                                }
                            });

                            $('#div9').css('display', 'block');
                            $('#div8').css('display', 'none');
                            $('#step-9').addClass('active');

                            updateMeterArrows('nutrition-form');

                            $('.sport-plan .score-lock').addClass('d-none');
                            $('.sports-percentage').removeClass('d-none');

                            // Hide sport-lock and show sport-unlock
                            $('.sport-plan .sport-lock').addClass('d-none');
                            $('.sport-plan .sport-unlock').removeClass('d-none');
                            $('.sport-plan .unlock-result').addClass('d-none');

                            // Hide supplement-lock and show supplement-unlock
                            $('.supplement-plan .supplement-lock').addClass('d-none');
                            $('.supplement-plan .score-lock').addClass('d-none');
                            $('.supplement-plan .supplement-unlock').removeClass('d-none');
                            $('.supplement-percentage').removeClass('d-none');
                            $('.supplement-plan .unlock-result').addClass('d-none');
                            // Clear localStorage and close the modal
                            localStorage.removeItem("testStepsData");
                            localStorage.removeItem("totalAnswerCounts");
                            $('#detailsModal').modal('hide'); // Close the register modal
                            $('#TakeTestModel').removeClass('blur-background');
                              
                        } else {
                            alert(data.message);

                            // Clear localStorage and close the modal
                            localStorage.removeItem("testStepsData");
                            localStorage.removeItem("totalAnswerCounts");
                            $('#detailsModal').modal('hide');
                            $('#TakeTestModel').removeClass('blur-background');
                            // loginModal.modal('show');

                        }
                    },
                    error: function () {
                        alert("Error registering.");
                    }
                });
            })

            $('#TakeTestModel').on('hidden.bs.modal', function() {
                if (currentQuizId) {
                    $.ajax({
                        url: "{{ route('front.quiz.abandon') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            quiz_id: currentQuizId
                        }
                    });
                }
            });
            
            // Login form submit handler
            loginForm.submit(function (event) {
                event.preventDefault();

                const testData = JSON.parse(localStorage.getItem("testStepsData"));
                const totalAnswerCount = JSON.parse(localStorage.getItem("totalAnswerCounts"));

                // Capture the login form data (email, password)
                const email = $("#test-login-email").val();
                const password = $("#test-login-password").val();
                console.log(email);
                console.log(password);
                // Prepare data for login submission
                const loginData = {
                    email,
                    password
                };

                // Simulate API request to log the user in
                $.ajax({
                    url: "{{ route('front.login') }}",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(loginData),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.success) {
                            const userId = data.user.id;
                            const name = data.user.name;
                            // Handle success (store user data, etc.)
                            if(data.freeTest) {
                                alert("Your free test has already been taken. so your results sent to your email.");
                                // Clear localStorage and close the modal
                                localStorage.removeItem("testStepsData");
                                localStorage.removeItem("totalAnswerCounts");

                                loginModal.modal('hide'); // Close the register modal
                                showThankYouModal();
                            } else {
                                $.ajax({
                                    url: "{{ route('front.submit-free-test') }}",
                                    method: "POST",
                                    contentType: "application/json",
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    data: JSON.stringify({ userId, name, email, testData ,totalAnswerCount}),
                                    success: function () {
                                        // alert("Registration and Test Data Submission Successful!");

                                        // Clear localStorage and close the modal
                                        localStorage.removeItem("testStepsData");
                                        localStorage.removeItem("totalAnswerCounts");

                                        loginModal.modal('hide'); // Close the register modal
                                        showThankYouModal();
                                    },
                                    error: function () {
                                        alert("Error submitting test data.");
                                    }
                                });
                            }

                            // Close the login modal
                            loginModal.modal('hide');
                        } else {
                            alert("Login failed.");
                        }
                    },
                    error: function () {
                        alert("Error logging in.");
                    }
                });
            });

            // Switch to the login modal from the register modal
            loginLink.click(function () {
                registerModal.modal('hide');
                loginModal.modal('show');
            });

            // Switch to the register modal from the login modal
            registerLink.click(function () {
                loginModal.modal('hide');
                registerModal.modal('show');
            });

            // Add this after your existing registration modal code
            $('#registerModal').on('hidden.bs.modal', function () {
                // Check if we have test data in localStorage (indicating quiz was completed)
                const testData = JSON.parse(localStorage.getItem("testStepsData"));
                if (testData) {
                    // Track completion without email
                    $.ajax({
                        url: "{{ route('front.track.quiz.completion') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            userId: null,
                            email: null
                        },
                        success: function(response) {
                            console.log('Quiz completion tracked (modal closed)');
                        }
                    });
                }
                $('#TakeTestModel').removeClass('blur-background');
            });

            function showThankYouModal() {
                // Set dynamic content
                const thankYouMessage = "We make around 300 food decisions a day... to perform at your best order your Personalised plan today.";
                const planUrl = "https://performancehealthsupport.com/action-sport-nutrition-plan";
                // Set the modal message
                $('#thankYouMessage').text(thankYouMessage);
                
                // Set the URL for the plan button dynamically
                $('#planUrlLink').attr('href', planUrl); // Set the plan URL dynamically
                $('#thankYouModal').modal('show');
            }
        });

        $(document).ready(function() {
            $("#submit-query").click(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Capture form data
                let name = $("#query-name").val().trim();
                let email = $("#query-email").val().trim();
                let phone = $("#query-phone").val().trim();
                let message = $("#query-message").val().trim();
                let _token = "{{ csrf_token() }}";

                // Basic validation
                if (name === "" || email === "" || phone === "" || message === "") {
                    alert("Please fill in all fields.");
                    return;
                }

                // AJAX request
                $.ajax({
                    url: "{{ route('front.submit-query') }}", // Laravel route
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        phone: phone,
                        message: message,
                        _token: _token // CSRF Token
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Your query has been submitted successfully!");
                            $("#query-form")[0].reset(); // Clear the form
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Something went wrong. Please try again.");
                    }
                });
            });
        });
    
        setTimeout(function () {
            const script = document.createElement('script');
            script.src = "https://js.stripe.com/v3/";
            script.onload = function () {
                // Add this JavaScript code to your page
                $(document).ready(function() {
                    // var stripe = Stripe('pk_test_51QI09cHWqn47bqTGYhGZIsiPSerWujjQgoHf4g0JwygrNt1OMC3RtEnMIjiEWbc8hiaN4umn4TD5zB8sBQEqcjzY0071a4RbUv');
                    var stripe = Stripe('pk_live_51Pfz1YLSisFoEruHvHpdQQZLynQoR3x6BDuBgpb84zTK3EnTlROWMjxVpZhrp1rLmaqCJbusOUNHUoTKBLK7CXru00CkS5tVbt');
                    var elements = stripe.elements();
                    var style = {
                        base: {
                            color: '#32325d',
                            border:'1px solid #32325d',
                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    };

                    // Create card element
                    var card = elements.create('card', { style: style });
                    var cardErrors = document.getElementById('card-errors');
                    card.mount('#card-element');

                    // Handle card input changes
                    card.on('change', function(event) {
                        var displayError = document.getElementById('card-errors');
                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    $('#purchaseModal').on('hidden.bs.modal', function () {
                        $('#payment-form')[0].reset(); // Reset the form
                        $('#card-errors').text('');    // Clear Stripe errors
                    });
                    
                    // Event listener for the 'Purchase Now' button
                    $('body').on('click', '.purchase-now-btn', function () {
                        // alert('Payment button clicked');
                        // e.preventDefault();

                        var planId = $(this).data('plan-id');  // Get the plan ID
                        var price = $(this).data('plan-price');     // Get the plan price (if needed)
                        
                        // Update modal title with plan name (optional)
                        $('#purchaseModalLabel').text('Purchase ' + $(this).closest('.spot-plan-box').find('h5').text() + ' ($' + price + ')');
                        // Check if the user is authenticated
                        const isAuthenticated = @json(Auth::guard('web')->check());
                        var userId = {{ Auth::check() ? Auth::user()->id : 'null' }};
                        console.log('Authenticated and not admin, User ID:', userId);

                        var isAdmin = {{ Auth::check() && Auth::user()->is_superadmin == 1 ? 'true' : 'false' }};
                        if (isAuthenticated && !isAdmin) {
                            console.log('Authenticated and not admin');

                            $('#registration-details').hide();
                            $('#payment-details').show();
                            $('#signed-in-details').removeClass('d-none');
                            $('#already-signed-in').addClass('d-none');
                            @if(Auth::check())
                                $('#name').val('{{ Auth::user()->first_name }} {{Auth::user()->last_name }}');  // Pre-fill name field
                                $('#emailId').val('{{ Auth::user()->email }}');  // Pre-fill email field
                                $('#phone').val('{{ Auth::user()->phone ?? "" }}');  // Pre-fill phone field, use empty string if null
                                $('#signed-in-email').text('{{ Auth::user()->email }}');  // Pre-fill signed-in email field
                            @endif
                            // Show the modal
                        }
                        console.log(isAuthenticated);
                        console.log(isAdmin);
                        // Show the modal
                        $('#purchaseModal').modal('show');

                        // Handle the form submission
                        $('#payment-form').off('submit').on('submit', function(event) {
                            event.preventDefault();

                            // Disable the submit button to prevent multiple clicks
                            $('#submit').prop('disabled', true);

                            // Create a PaymentMethod with Stripe's API
                            let discountCode = $('#promo-code').val();
                            console.log(discountCode);
                            let discount = $('#discount').val();
                            let email = $('#emailId').val();
                            let name = $('#name').val();
                            let phone = $('#phone').val();
                            console.log('Email:', email);
                            console.log('Name:', name);
                            console.log('Phone:', phone);
                            
                            if(discount == 100.00) {
                                $.ajax({
                                    url: '{{ route("process.payment") }}',
                                    method: 'POST',
                                    data: {
                                        plan_id: planId,
                                        price: price,
                                        name: $('#name').val(),
                                        email: $('#emailId').val(),
                                        phone: $('#phone').val(),
                                        password: $('#password').val(),
                                        coupon_code: discountCode,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function (response) {
                                        if (response.success) {
                                            // Close the modal
                                            $('#purchaseModal').modal('hide');
                                            $('#submit').prop('disabled', false);
                                            var user_id = response.data.user_id;
                                            var payment_id = response.data.payment_id;

                                            if(response.data.submit_questionnaire) {
                                                // Redirect the user if a URL is provided
                                                if (response.redirect_url) {
                                                    var redirectUrlWithUserId = response.redirect_url + '?id=' + payment_id + '&user_id=' + user_id;
                                                    setTimeout(function () {
                                                        window.location.href = redirectUrlWithUserId;
                                                    }, 3000);
                                                }else {
                                                    alert('Error: Redirect url not found.');
                                                }
                                            } else {
                                                $('#thankYouModal').modal('show');
                                            }
                                        } else {
                                            // Show error message for failed payment
                                            if(response.message == 'You have already purchased this plan. Please login to your account to manage your plans.') {
                                                alert('You have already purchased this plan. Please login to your account to manage your plans.');
                                                $('#purchaseModal').modal('hide');

                                                $('html, body').animate({
                                                    scrollTop: $('#nutrition-login-section').offset().top
                                                }, 500); // 1000ms for smooth scrolling
                                            } else {
                                                alert('Payment failed: ' + response.message);
                                            }
                                            $('#submit').prop('disabled', false);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        $('#submit').prop('disabled', false); // Re-enable the submit button

                                        let message = '';

                                        if (xhr.status === 422) {
                                            // Laravel validation error
                                            const errors = xhr.responseJSON.errors;
                                            message += '<ul>';
                                            $.each(errors, function(key, value) {
                                                message += `<li style="color: red;">${value[0]}</li>`;
                                            });
                                            message += '</ul>';
                                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                            message = `<p style="color: red;">${xhr.responseJSON.message}</p>`;
                                        } else {
                                            message = `<p style="color: red;">Unexpected Error (${xhr.status}): ${error}</p>`;
                                        }

                                        $('#errorModalBody').html(message);
                                        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                                        errorModal.show();
                                        $('#purchaseModal').addClass('blur-background');

                                    }

                                });
                            }else {
                                stripe.createPaymentMethod({
                                    type: 'card',
                                    card: card,
                                    billing_details: {
                                        name: $('#name').val(),
                                        email: $('#emailId').val(),
                                        phone: $('#phone').val(),
                                    },
                                }).then(function(result) {
                                    if (result.error) {
                                        // Display error in the card element
                                        cardErrors.textContent = result.error.message;
                                        $('#submit').prop('disabled', false);
                                    } else {
                                        // Call the server to create the PaymentIntent
                                        $.ajax({
                                            url: '{{ route("process.payment") }}', // Define the route to process the payment
                                            method: 'POST',
                                            data: {
                                                payment_method_id: result.paymentMethod.id,
                                                plan_id: planId,
                                                price: price,
                                                name: name,
                                                email: email,
                                                phone: phone,
                                                password: $('#password').val(),
                                                coupon_code: discountCode,
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    // Handle successful payment
                                                    // alert('Payment successful!');
                                                    $('#purchaseModal').modal('hide');
                                                    // $('#thankYouModal').modal('show');
                                                    $('#submit').prop('disabled', false);
                                                    if(response.data.submit_questionnaire) {
                                                        
                                                        var user_id = response.data.user_id;  // Assuming the backend sends the user_id
                                                        var payment_id = response.data.payment_id;  // Assuming the backend sends the user_id

                                                        // Check if there's a redirect URL provided
                                                        if (response.redirect_url) {

                                                            var redirectUrlWithUserId = response.redirect_url + '?id=' + payment_id +'&user_id='+ user_id;
                                                            // Redirect the user to the provided URL after a delay (optional)
                                                            setTimeout(function() {
                                                                window.location.href = redirectUrlWithUserId;
                                                            }, 3000); // 3-second delay before redirecting (adjust as needed)
                                                        }
                                                        // $('#submit').prop('disabled', true);
                                                    } else {
                                                        $('#thankYouModal').modal('show');
                                                    }
                                                } else {
                                                    // Show error message for failed payment
                                                    if(response.message == 'You have already purchased this plan. Please login to your account to manage your plans.') {
                                                        alert('You have already purchased this plan. Please login to your account to manage your plans.');
                                                        $('#purchaseModal').modal('hide');

                                                        $('html, body').animate({
                                                            scrollTop: $('#nutrition-login-section').offset().top
                                                        }, 500); // 1000ms for smooth scrolling
                                                    } else {
                                                        alert('Payment failed: ' + response.message);
                                                    }
                                                    $('#submit').prop('disabled', false);

                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                $('#submit').prop('disabled', false); // Re-enable the submit button

                                                let message = '';

                                                if (xhr.status === 422) {
                                                    // Laravel validation error
                                                    const errors = xhr.responseJSON.errors;
                                                    message += '<ul>';
                                                    $.each(errors, function(key, value) {
                                                        message += `<li style="color: red;">${value[0]}</li>`;
                                                    });
                                                    message += '</ul>';
                                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                                    message = `<p style="color: red;">${xhr.responseJSON.message}</p>`;
                                                } else {
                                                    message = `<p style="color: red;">Unexpected Error (${xhr.status}): ${error}</p>`;
                                                }

                                                $('#errorModalBody').html(message);
                                                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                                                errorModal.show();
                                                $('#purchaseModal').addClass('blur-background');

                                            }

                                        });
                                    }
                                });
                            }
                        });
                        
                        $('#view-sample-plan').click(function () {
                            // var planId = $(this).data('plan-id');
                            
                            $('#samplePlanModalLabel').text('Loading...');
                            $('#samplePlanModalBody').html('<p>Loading details...</p>');
                            $('#samplePlanModal').modal('show');

                            $.ajax({
                                url: '{{ route("front.get-default-plan-details", ":id") }}'.replace(':id', planId),
                                method: 'GET',
                                success: function (response) {
                                    if (response.error) {
                                        $('#samplePlanModalBody').html('<p>' + response.error + '</p>');
                                        return;
                                    }

                                    // Build the modal content for main plan
                                    const mainPlan = response.mainPlan;
                                    let modalContent = `<h5>${mainPlan.name}</h5>`;
                                    // modalContent += `<p>Price: $${mainPlan.price}</p>`;
                                    modalContent += buildMealTimeHtml(mainPlan.mealTimes);

                                    // Build the modal content for subPlans
                                    if (response.subPlans.length > 0) {
                                        modalContent += `<h5></h5>`;
                                        response.subPlans.forEach(function (subPlan) {
                                            modalContent += `<div class="mt-3"><h6>Sub Plan: ${subPlan.name}</h6>`;
                                            modalContent += `<p>Price: $${subPlan.price}</p>`;
                                            modalContent += buildMealTimeHtml(subPlan.mealTimes);
                                            modalContent += `</div>`;
                                        });
                                    }

                                    $('#samplePlanModalLabel').text('Plan Details: ' + mainPlan.name);
                                    $('#samplePlanModalBody').html(modalContent);
                                },
                                error: function () {
                                    $('#samplePlanModalBody').html('<p>Error fetching plan details. Please try again later.</p>');
                                }
                            });
                        });

                        // Function to build HTML for mealTimes, categories, meals, and items
                        function buildMealTimeHtml(mealTimes) {
                            let html = `<ul>`;
                            mealTimes.forEach(function (mealTime) {
                                html += `<li><strong>${mealTime.title}</strong> (Meal Time)<ul>`;

                                mealTime.categories.forEach(function (category) {
                                    html += `<li><strong>${category.name}</strong> (Category)<ul>`;

                                    category.meals.forEach(function (meal) {
                                        html += `<li><strong>${meal.name}</strong> (Meal)<ul>`;

                                        meal.items.forEach(function (item) {
                                            html += `<li>${item.name} (Food)<ul>`;

                                            item.swapItems.forEach(function (swapItem) {
                                                html += `<li>${swapItem.name} (Swap Food)</li>`;
                                            });

                                            html += `</ul></li>`;
                                        });

                                        html += `</ul></li>`;
                                    });

                                    html += `</ul></li>`;
                                });

                                html += `</ul></li>`;
                            });
                            html += `</ul>`;
                            return html;
                        }

                        document.getElementById('apply-promo-code').addEventListener('click', function () {
                            const promoCode = document.getElementById('promo-code').value.trim();

                            if (promoCode === '') {
                                // Show error if promo code is empty
                                document.getElementById('promo-message').textContent = 'Please enter a coupon code.';
                                document.getElementById('promo-message').classList.add('text-danger');
                                document.getElementById('promo-message').classList.remove('text-success');
                                return;
                            }

                            // AJAX request to validate promo code
                            fetch('{{ route("validate.coupon.code") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ code: promoCode, plan_id: planId })
                            })
                            .then(response => response.json())
                            .then(data => {
                                let msg = ''; // Declare the message variable outside the conditional blocks

                                if (data.valid) {
                                    if(data.type == 'percentage') {
                                        msg = `Coupon code applied! ${data.discount}% discount.`;
                                        if(data.discount === "100.00" || data.discount == 100.00) {
                                            $('#discount').val(data.discount);
                                            $('#payment-details').hide();
                                        }
                                    }else {
                                        msg = `Coupon code applied! $${data.discount} discount.`;
                                    }
                                    $('#discount').val(data.discount);
                                    // Promo code is valid
                                    document.getElementById('promo-message').textContent = msg;
                                    document.getElementById('promo-message').classList.add('text-success');
                                    document.getElementById('promo-message').classList.remove('text-danger');
                                } else {
                                    // Promo code is invalid or expired
                                    document.getElementById('promo-message').textContent = data.message;
                                    document.getElementById('promo-message').classList.add('text-danger');
                                    document.getElementById('promo-message').classList.remove('text-success');
                                }
                            })
                            .catch(error => {
                                alert('Error: ', error);
                                console.error('Error:', error);
                                document.getElementById('promo-message').textContent = 'Something went wrong. Please try again.';
                                document.getElementById('promo-message').classList.add('text-danger');
                                document.getElementById('promo-message').classList.remove('text-success');
                            });
                        });
                    });
                });
            };
            script.onerror = function () {
                console.error("Failed to load Stripe.js");
            };
            document.head.appendChild(script);
        }, 5000);

        // Submit Login Form
        $('#login-form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting the normal way

            // Disable the Submit Button to avoid multiple clicks
            $('#login-submit').prop('disabled', true);

            // Get the form data
            var email = $('#login-email').val();
            var password = $('#login-password').val();

            // Send the data to the backend for validation
            $.ajax({
                url: '{{ route("front.login") }}', // This is the route for handling login (update with your actual route if different)
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}' // CSRF token for protection
                },
                success: function(response) {
                    if (response.success) {
                        if(response.message == 'Plan not purchased.') {
                            alert('Please complete your profile.');
                        }
                        // If login is successful, redirect to the given URL
                        window.location.href = response.redirect_url;
                    }
                },error: function(xhr) {
                    var response = xhr.responseJSON;

                    // Show error messages for validation errors
                    if (response.message) {
                        if(response.message == 'CSRF token mismatch.') {
                            $('#login-error').text('Your session has expired. Please reload the page and login again.'); 
                        }else {
                            $('#login-error').text(response.message); // Display error message in #login-error div
                        }
                    } else {
                        $('#login-error').text('Something went wrong. Please try again.'); // General error message
                    }

                    $('#login-submit').prop('disabled', false); // Re-enable submit button
                }
            });
        });

        $('#front-page-login-form').submit(function(event) {
            event.preventDefault();

            var email = $('#front-page-login-email').val();
            var password = $('#front-page-login-password').val();

            $.ajax({
                url: '{{ route("front.login") }}',
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                },error: function(xhr) {
                    var response = xhr.responseJSON;

                    if (response.message) {
                        alert(response.message);
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                }
            });
        });

        // Show Sign-In Modal when clicking "Sign In" link in the Sign-Up Modal
        $('#show-login-modal').click(function(e) {
            e.preventDefault(); // Prevent default link action
            $('#purchaseModal').modal('hide'); // Hide the sign-up modal
            $('#loginModal').modal('show'); // Show the sign-in modal
        });

        // Show Sign-Up Modal when clicking "Sign Up" link in the Sign-In Modal
        $('#show-signup-modal').click(function(e) {
            e.preventDefault(); // Prevent default link action
            $('#loginModal').modal('hide'); // Hide the sign-in modal
            $('#registerModal').modal('hide'); // Show the sign-up modal
            $('#purchaseModal').modal('show'); // Show the sign-up modal
        });

        $(document).ready(function() {
            $('body').on('click', '#forgot-password', function(e) {
                e.preventDefault();
                
                $('#forgotPasswordModal').modal('show'); // Show the modal
                $('#forgotPasswordForm').reset();
            });

            // $('#forgotPasswordForm').on('submit', function(e) {
            //     e.preventDefault();

            //     $.ajax({
            //         url: "{{ route('front.password.request') }}",
            //         method: 'POST',
            //         data: {
            //             email: $('#email').val(),
            //             _token: '{{ csrf_token() }}'
            //         },
            //         success: function(response) {
            //             alert('Password reset link has been sent to your email.');
            //             $('#forgotPasswordModal').modal('hide');
            //         },
            //         error: function(xhr) {
            //             alert('Failed to send reset link. Please check your email address.');
            //         }
            //     });
            // });
        });

        // Update the back button click handler
        $('.back-btn').click(function() {
            if (currentStep > 0) {
                const prevStep = currentStep - 1;
                updateModalTitle(prevStep);
                stepCircles.forEach((step, index) => {
                    step.classList.toggle('active', index <= prevStep);
                });
                stepTabs.forEach((tab, index) => {
                    tab.style.display = index === prevStep ? "block" : "none";
                });
                currentStep = prevStep;

                // Scroll to top
                const modalBody = $('#TakeTestModel .modal-body');
                modalBody.scrollTop(0);
            }
        });

        // Next button click handler - only handles navigation
        $('.next-btn').click(function() {
            const targetStep = currentStep + 1;
            
            // Update UI
            updateModalTitle(targetStep);
            stepCircles.forEach((step, index) => {
                step.classList.toggle('active', index <= targetStep);
            });
            stepTabs.forEach((tab, index) => {
                tab.style.display = index === targetStep ? "block" : "none";
            });
            currentStep = targetStep;

            // Scroll to top
            const modalBody = $('#TakeTestModel .modal-body');
            modalBody.scrollTop(0);
        });

        // Show step button click handler - handles saving data
        $('.show-step-btn').click(function() {
            const targetStep = parseInt($(this).data('step')) - 1;
            const stepData = {};
            const rows = document.querySelectorAll(`#div${currentStep} .row`);
            
            rows.forEach(row => {
                const selectedRadio = row.querySelector('input[type="radio"]:checked');
                if (selectedRadio) {
                    const foodName = row.querySelector('.food-name').textContent.trim();
                    const option = selectedRadio.value;
                    const correct = selectedRadio.getAttribute('data-correct');
                    const value = selectedRadio.getAttribute('data-value');
                    const formSlug = selectedRadio.getAttribute('data-form-slug');
                    
                    stepData[foodName] = {
                        value: value,
                        option: option,
                        correct: correct,
                        form_slug: formSlug
                    };
                }
            });
            
            console.log('Current step data:', stepData);
            
            // Check if stepData has any answers
            if (Object.keys(stepData).length > 0) {
                console.log('Saving step data with quiz ID:', currentQuizId);
                
                // Save to localStorage
                localStorage.setItem(`step-${currentStep}-data`, JSON.stringify(stepData));
                
                // Update total answer counts
                Object.keys(stepData).forEach(key => {
                    const answer = stepData[key];
                    if (answer.correct === 1) {
                        const formType = Array.from(stepTabs[currentStep].classList).find(cls => cls.endsWith('-form'));
                        if (formType) {
                            totalAnswerCounts[formType] = (totalAnswerCounts[formType] || 0) + 1;
                        }
                    }
                });
                
                // Save total counts
                localStorage.setItem("totalAnswerCounts", JSON.stringify(totalAnswerCounts));

                // Get question text from hidden inputs
                const form = document.querySelector(`#div${currentStep}`);
                const questionInputs = form.querySelectorAll("input[type='hidden'][name^='questions']");
                const questions = Array.from(questionInputs).map(input => input.value);

                // Format data for database
                const formattedAnswers = questions.map((questionText, index) => ({
                    question: questionText,
                    question_index: index + 1,
                    step: currentStep + 1,
                    form_slug: Object.values(stepData)[0].form_slug,
                    answer: stepData
                }));

                // Save to database
                $.ajax({
                    url: "{{ route('front.quiz.save-step') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        quiz_id: currentQuizId,
                        step: currentStep + 1,
                        questions: questions,
                        answers: formattedAnswers
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Step data saved successfully');
                            // Only update UI after successful save
                            updateModalTitle(targetStep);
                            stepCircles.forEach((step, index) => {
                                step.classList.toggle('active', index <= targetStep);
                            });
                            stepTabs.forEach((tab, index) => {
                                tab.style.display = index === targetStep ? "block" : "none";
                            });
                            currentStep = targetStep;

                            // Scroll to top
                            const modalBody = $('#TakeTestModel .modal-body');
                            modalBody.scrollTop(0);
                        } else {
                            console.error('Error saving step:', response.message);
                            alert('Error saving step: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error saving step:', xhr.responseText);
                        alert('Error saving step. Please try again.');
                    }
                });
            } else {
                // If no answers, just navigate
                updateModalTitle(targetStep);
                stepCircles.forEach((step, index) => {
                    step.classList.toggle('active', index <= targetStep);
                });
                stepTabs.forEach((tab, index) => {
                    tab.style.display = index === targetStep ? "block" : "none";
                });
                currentStep = targetStep;

                // Scroll to top
                const modalBody = $('#TakeTestModel .modal-body');
                modalBody.scrollTop(0);
            }
        });
    </script>

@endsection
