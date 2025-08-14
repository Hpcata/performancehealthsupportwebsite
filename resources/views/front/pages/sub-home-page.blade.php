@extends(frontView('layouts.app'))

@section('title', 'Sports Nutrition Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .error-message {
        color: #dc3545 !important;
        font-size: 14px !important;
        margin-top: 8px !important;
        margin-bottom: 8px !important;
        padding: 8px 12px !important;
        background-color: #f8d7da !important;
        border: 1px solid #f5c6cb !important;
        border-radius: 4px !important;
        display: block !important;
    }

    .success-message {
        color: #155724 !important;
        font-size: 14px !important;
        margin-top: 8px !important;
        margin-bottom: 8px !important;
        padding: 8px 12px !important;
        background-color: #d4edda !important;
        border: 1px solid #c3e6cb !important;
        border-radius: 4px !important;
        display: block !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    /* Phone number input restrictions */
    #mobile_number {
        -webkit-appearance: none;
        -moz-appearance: textfield;
    }

    #mobile_number::-webkit-outer-spin-button,
    #mobile_number::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Ensure country code is visible */
    .phone-input-container .selected-code {
        color: #333 !important;
        font-weight: 500 !important;
    }
</style>

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
            backdrop-filter: blur(5px);
            /* Blur the backdrop */
            background-color: rgba(0, 0, 0, 0.3);
            /* Add slight transparency */
        }

        /* Ensure the modal and its content are not blurred */
        .modal-content {
            filter: none !important;
        }

        /* Blur the rest of the page when the modal is open */
        .blur-background {
            filter: blur(5px);
            /* Adjust the blur value */
            transition: filter 0.3s ease-in-out;
        }

        .purchase-now-btn {
            white-space: nowrap;
        }

        .coupon-link {
            text-decoration: none;
            cursor: pointer;
            color: #000;
            text-decoration: underline;
        }

        .coupon-link.active {
            color: #000;
            text-decoration: underline;
        }
    </style>

    @php
        $showHeader = !empty($user->front_logo) &&
            !empty($user->front_title) &&
            !empty($user->front_description) &&
            !empty($user->about_us_image);

        if ($isAuthenticated) {
            $user = Auth::user();
            $planIds = DB::table('payments')->where('email', $user->email)->where('status', 'succeeded')->pluck('plan_id')->toArray();
        } else {
            $planIds = [];
        }
    @endphp
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
                <div class="text-center modal-body">
                    <p>You have not purchased any plans.<br>Please purchase a plan.</p>

                    <div class="justify-content-center gap-3 d-grid mt-5">
                        <a href="{{ route('front.sub-home-page') }}#sport-plans" class="px-4 btn btn-primary btn-sm"
                            style="width: 300px;">View Plans</a>
                        <a href="{{ route('front.index') }}#bookingtypecontainer" class="px-4 btn btn-secondary"
                            style="width: 300px;">Book Consultation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_MAIN_BANNER && $section->enabled == 1) <!-- done -->
                <div id="heroCarouselDesktop" class="d-md-block carousel slide d-none" data-bs-ride="carousel" data-bs-interval="3000"
                    data-bs-wrap="true">
                    <div class="carousel-inner">
                        <!-- Slide 1 - Fitness/Nutrition Image (Desktop) -->
                        @if(isset($section->banner_image))
                            @foreach($section->banner_image as $key => $image)
                                <div class="carousel-item @if($key == 0) active @endif"
                                    style="background-image: url('{{ asset('storage/' . $image) }}')"></div>
                            @endforeach
                        @endif
                    </div>
                    <div class="container-homepage">
                        <div class="hero-content-fixed">
                            <h1 class="hero-title-homepage">{{ $section->title }}</h1>
                            {!! $section->content !!}
                        </div>
                    </div>
                    <!-- Chat Widget -->
                    <div class="chat-widget desktop-view mob-hide">
                        <div class="chat-avatar">
                            <img src="{{ frontAssets('images/virtual kez.svg') }}" alt="Virtual Kez Avatar" />
                        </div>
                        <div class="chat-bubble">
                            <span>Hi, I’m Virtual Kez. Try calling me for free!</span>
                            <img src="{{ frontAssets('images/bubble-arrow.svg') }}" alt="Virtual Kez Avatar" class="bubble-arrow" />
                        </div>
                    </div>
                </div>

                <!-- Mobile Carousel -->
                <div id="heroCarouselMobile" class="carousel slide d-md-none" data-bs-ride="carousel" data-bs-interval="3000"
                    data-bs-wrap="true">
                    <div class="carousel-inner">
                        @if(isset($section->image) && is_array($section->image) && count($section->image) > 0)
                            @foreach($section->image as $key => $image)
                                <div class="carousel-item @if($key == 0) active @endif"
                                    style="background-image: url('{{ asset('storage/' . $image) }}')">
                                </div>
                            @endforeach
                        @else
                            <!-- Fallback static images if no dynamic images are set -->
                            <div class="carousel-item active" style="background-image: url('images/slide-1-mob.webp')"></div>
                            <div class="carousel-item" style="background-image: url('images/slide-2-mob.webp')"></div>
                        @endif
                    </div>

                    <!-- Fixed Text Overlay -->
                    <div class="container-homepage">
                        <div class="hero-content-fixed">
                            <h1 class="hero-title-homepage">{{ $section->title }}</h1>
                            {!! $section->content !!}

                            <button class="ms-2 btn-white" onclick="openSingupFreePopup()">Sign up for free</button>
                        </div>
                    </div>
                </div>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_ABOUT_US && $section->enabled == 1)
                <!-- About Section -->
                <section class="about-section">
                    <div class="container-homepage">
                        <div class="about-content-wrapper">
                            <div class="about-text-content">
                                {!! $section->content !!}
                            </div>
                        </div>
                    </div>
                    <div class="about-image-container">
                        <img @if(isset($section->image[0]) && !empty($section->image[0]))
                        src="{{ asset('storage/' . $section->image[0]) }}" @endif alt="Kerry O'Bryan"
                            class="img-fluid about-image" />
                    </div>
                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_EAT_BETTER && $section->enabled == 1)
                @php
                    // Fetch the first banner image from section 1
                    $bannerImage = null;
                    if (isset($page->sections)) {
                        foreach ($page->sections as $sec) {
                            if ($sec->order == 3 && $sec->enabled == 1 && isset($sec->banner_image) && count($sec->banner_image) > 0) {
                                $bannerImage = asset('storage/' . $sec->banner_image[0]);
                                break;
                            }
                        }
                    }
                @endphp
                @if($bannerImage)
                    <style>
                        .food-section {
                            background-image: url('{{ $bannerImage }}');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                        }
                    </style>
                @endif

                <section class="food-section">
                    <div class="food-content">
                        <h2 class="food-title">{{ $section->title }}</h2>
                        {!! $section->content !!}

                        @if(!Auth::check())
                            <button class="btn-signup" id="show-new-signup-modal" onclick="openSingupFreePopup()">
                                Sign up
                            </button>
                        @endif
                    </div>

                    <!-- Custom Food Carousel -->
                    <div class="food-carousel-container">
                        <div class="food-carousel-track" id="foodCarouselTrack">
                            @if(isset($section->image))
                                @foreach($section->image as $image)
                                    <div class="food-card">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Healthy breakfast bowl with berries and granola" />
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Navigation Buttons -->
                        <button class="carousel-nav-btn prev-btn" id="prevBtn" style="display: none">
                            <span class="nav-icon">‹</span>
                        </button>
                        <button class="carousel-nav-btn next-btn" id="nextBtn" style="display: none">
                            <span class="nav-icon">›</span>
                        </button>
                    </div>

                    <!-- Chat Widget -->
                    <div class="chat-widget mobile-view web-hide">
                        <div class="chat-avatar">
                            <img src="{{ frontAssets('images/virtual kez.svg') }}" alt="Virtual Kez Avatar" />
                        </div>
                        <div class="chat-bubble">
                            <span>Hi, I’m Virtual Kez. Try calling me for free!</span>
                            <img src="{{ frontAssets('images/bubble-arrow.svg') }}" alt="Virtual Kez Avatar" class="bubble-arrow" />
                        </div>
                    </div>
                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_WHY_IT_WORKS && $section->enabled == 1)
                <section class="why-it-works-section">
                    <div class="container-homepage">
                        <div class="row">
                            <div class="col-12">
                                <h1>{{ $section->title }}</h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Tabs -->
                                <ul class="nav nav-tabs" id="whyTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="athletes-tab" data-bs-toggle="tab"
                                            data-bs-target="#athletes" type="button" role="tab">
                                            FOR ATHLETES
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="parents-tab" data-bs-toggle="tab" data-bs-target="#parents"
                                            type="button" role="tab">
                                            FOR PARENTS
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content" id="whyTabsContent">
                                    <div class="tab-pane fade show active" id="athletes" role="tabpanel">
                                        <p class="tab-description">
                                            Train like a Pro? Time to eat like one too. ATHLEAT makes performance nutrition simple,
                                            practical, and personalised to you. It’s your blueprint.
                                        </p>

                                        <div class="feature-grid">
                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/bulb.svg') }}" width="25" height="33" alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Know What to Eat - and Why</h3>
                                                    <p>
                                                        Understand how your choices impact energy, recovery, and long-term gains.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Gear Six.svg') }}" width="33" height="33"
                                                        alt="Gear Six" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Food Made Easy with Tools + Skills + Confidence</h3>
                                                    <p>
                                                        Fast, real food prep with tips, shortcuts, and practical know-how.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/dart.svg') }}" width="33" height="33" alt="dart" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Fuel Up Right</h3>
                                                    <p>
                                                        Understand how your choices effect - energy, strength, power, muscle, recovery, immunity AND long-term gains
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Medal.svg') }}" width="33" height="33"
                                                        alt="Balanced meal with lean protein and vegetables" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Built by a Pro</h3>
                                                    <p>
                                                        Created by  Kerry O’Bryan- Extreme Sports Dietition to Olympic gold medalists and  pro sports like NRL, Surfing, Skate, and BMX champions.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Phone.svg') }}" width="24" height="24"
                                                        alt="Phone" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Always Supported with 24/7 Kez Calls</h3>
                                                    <p>
                                                        Got questions? Kez is on call-your Health & Performance Co-pilot.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Gift.svg') }}" width="28" height="28" alt="gift" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Challenge for Rewards</h3>
                                                    <p>
                                                        Earn points, climb leaderboards, and get real prizes for showing up and learning like a boss.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">

                                                    <img src="{{ frontAssets('images/search.svg') }}" width="33" height="33"
                                                        alt="search" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Supplement smart</h3>
                                                    <p>
                                                        Use the scanner to check batch testing, safety, and learn if it's actually worth your money.
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Analytics.svg') }}" width="35" height="35"
                                                        alt="Balanced meal with lean protein and vegetables" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Accountability That Works</h3>
                                                    <p>
                                                        Track meals, upload pics and get progress feedback in our platform. *Coming Soon
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="parents" role="tabpanel">
                                        <p class="tab-description">
                                            Give your child the tools to thrive – in sport and in life. ATHLEAT drives athlete growth, performance, and well-being with expert Sports Dietitian guidance and practical systems for lifelong success.
                                        </p>

                                        <div class="feature-grid">
                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/bulb.svg') }}" width="25" height="33" alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Knowledge & Tools </h3>
                                                    <p>
                                                        Understand how your choices impact energy,ecovery, and long-term gains.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Calendar.svg') }}" width="25" height="33"
                                                        alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Planning Made Easy</h3>
                                                    <p>
                                                       Get organised with menu plans, shopping lists, the right snacks pre & post training
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Phone.svg') }}" width="25" height="33"
                                                        alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Kez On Call</h3>
                                                    <p>
                                                        Get 24/7 support with Virtual Kez Calls – from food to recovery advice.
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/information.svg') }}" width="25" height="33"
                                                        alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Avoid Misinformation</h3>
                                                    <p>
                                                        Cut through TikTok trends and unqualified advice.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/Gift.svg') }}" width="25" height="33" alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Rewards That Motivate</h3>
                                                    <p>
                                                        Quizzes and challenges with real prizes – delivered to your door.
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <img src="{{ frontAssets('images/dart.svg') }}" width="25" height="33" alt="bulb" />
                                                </div>
                                                <div class="feature-content">
                                                    <h3>Accountability That Works</h3>
                                                    <p>
                                                       Built-in tracking, meal photo uploads, and progress checks through our secure platform *coming soon
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4" style="position: relative;">
                                <div class="phone-mockup">
                                    <div class="phone-frame">
                                        <div class="phone-screen">
                                            <div class="scrollable-image-wrapper">
                                                @if(isset($section->banner_image[0]))
                                                    <img src="{{ asset('storage/' . $section->banner_image[0]) }}" alt="Phone Screen"
                                                        class="phone-screen-img" />
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <img src="{{ frontAssets('images/verticle-line.svg') }}" alt="Phone Screen"
                                    class="phone-vertical-line" />
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_CHOOSE_YOUR_PLAN && $section->enabled == 1)
                <section class="choose-plan-section" id="choose-plan-section">
                    <div class="container-homepage">
                        <h2 class="choose-plan-title">{{ $section->title }}</h2>
                        <p class="choose-plan-subtitle">{!! $section->content !!}</p>
                        <label class="choose-plan-label">Nutrition plans</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="plan-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42"
                                                fill="none">
                                                <path
                                                    d="M38.0625 11.1562H36.0938V9.1875C36.0938 8.10469 35.2078 7.21875 34.125 7.21875H32.1562C31.0734 7.21875 30.1875 8.10469 30.1875 9.1875V15.0938H25.5938V14.4375C25.5938 13.9059 25.3838 13.4072 25.0228 13.0528C24.6553 12.6787 24.1566 12.4688 23.625 12.4688C23.2706 12.4688 22.9491 12.5672 22.6603 12.7312C22.5816 12.6131 22.4963 12.495 22.3912 12.3966C22.0303 12.0225 21.5316 11.8125 21 11.8125C20.4947 11.8125 20.0353 12.0094 19.6875 12.3244C19.3266 11.9963 18.8738 11.8125 18.375 11.8125C17.6728 11.8125 17.0625 12.18 16.7147 12.7312C16.4259 12.5672 16.0978 12.4688 15.75 12.4688C14.6672 12.4688 13.7812 13.3547 13.7812 14.4375V15.0938H11.8125V9.1875C11.8125 8.10469 10.9266 7.21875 9.84375 7.21875H7.875C6.79219 7.21875 5.90625 8.10469 5.90625 9.1875V11.1562H3.9375C2.85469 11.1562 1.96875 12.0422 1.96875 13.125V23.625C1.96875 24.7078 2.85469 25.5938 3.9375 25.5938H5.90625V27.5625C5.90625 28.6453 6.79219 29.5312 7.875 29.5312H9.84375C10.9266 29.5312 11.8125 28.6453 11.8125 27.5625V22.9688H13.7812V24.9375C13.7812 25.0819 13.8272 25.2197 13.9125 25.3312L15.75 27.7791V32.8125C15.75 33.8953 16.6359 34.7812 17.7188 34.7812H22.9688C24.0516 34.7812 24.9375 33.8953 24.9375 32.8125V27.7791L26.775 25.3312C26.8603 25.2197 26.9062 25.0819 26.9062 24.9375V22.9688H30.1875V27.5625C30.1875 28.6453 31.0734 29.5312 32.1562 29.5312H34.125C35.2078 29.5312 36.0938 28.6453 36.0938 27.5625V25.5938H38.0625C39.1453 25.5938 40.0312 24.7078 40.0312 23.625V13.125C40.0312 12.0422 39.1453 11.1562 38.0625 11.1562ZM5.90625 24.2812H3.9375C3.57656 24.2812 3.28125 23.9859 3.28125 23.625V13.125C3.28125 12.7641 3.57656 12.4688 3.9375 12.4688H5.90625V24.2812ZM10.5 15.75V22.3125V27.5625C10.5 27.9234 10.2047 28.2188 9.84375 28.2188H7.875C7.51406 28.2188 7.21875 27.9234 7.21875 27.5625V24.9375V11.8125V9.1875C7.21875 8.82656 7.51406 8.53125 7.875 8.53125H9.84375C10.2047 8.53125 10.5 8.82656 10.5 9.1875V15.75ZM22.9688 14.4375C22.9688 14.0766 23.2641 13.7812 23.625 13.7812C23.8022 13.7812 23.9597 13.8469 24.0909 13.9847C24.2156 14.1028 24.2812 14.2603 24.2812 14.4375V17.0625C24.2812 17.4234 23.9859 17.7188 23.625 17.7188C23.2641 17.7188 22.9688 17.4234 22.9688 17.0625V14.4375ZM20.3438 13.7812C20.3438 13.4203 20.6391 13.125 21 13.125C21.4003 13.1644 21.6169 13.3875 21.6562 13.7812V17.0625C21.6562 17.4234 21.3609 17.7188 21 17.7188C20.6391 17.7188 20.3438 17.4234 20.3438 17.0625V13.7812ZM17.7188 14.4375V13.7812C17.7188 13.4203 18.0141 13.125 18.375 13.125C18.5522 13.125 18.7097 13.1906 18.8409 13.3284C18.9656 13.4466 19.0312 13.6041 19.0312 13.7812V17.0625C19.0312 17.4234 18.7359 17.7188 18.375 17.7188C18.0141 17.7188 17.7188 17.4234 17.7188 17.0625V14.4375ZM15.0938 14.4375C15.0938 14.0766 15.3891 13.7812 15.75 13.7812C15.9272 13.7812 16.0847 13.8469 16.2159 13.9847C16.3406 14.1028 16.4062 14.2603 16.4062 14.4375V17.0625C16.4062 17.4234 16.1109 17.7188 15.75 17.7188C15.3891 17.7188 15.0938 17.4234 15.0938 17.0625V14.4375ZM25.5938 24.7209L23.7563 27.1688C23.6709 27.2803 23.625 27.4181 23.625 27.5625V32.8125C23.625 33.1734 23.3297 33.4688 22.9688 33.4688H17.7188C17.3578 33.4688 17.0625 33.1734 17.0625 32.8125V27.5625C17.0625 27.4181 17.0166 27.2803 16.9312 27.1688L15.0938 24.7209V22.9688H20.4619C20.7309 23.73 21.4528 24.2812 22.3125 24.2812H23.625C23.9859 24.2812 24.2812 23.9859 24.2812 23.625C24.2812 23.2641 23.9859 22.9688 23.625 22.9688H22.3125C21.9516 22.9688 21.6562 22.6734 21.6562 22.3125C21.6562 22.1353 21.7219 21.9778 21.8597 21.8466C21.9778 21.7219 22.1353 21.6562 22.3125 21.6562H24.9375C25.2984 21.6562 25.5938 21.9516 25.5938 22.3125V24.7209ZM30.1875 21.6562H26.7881C26.5191 20.895 25.7972 20.3438 24.9375 20.3438H22.3125C21.7809 20.3438 21.2822 20.5538 20.9278 20.9147C20.7113 21.1247 20.5603 21.3806 20.4619 21.6562H11.8125V16.4062H13.7812V17.0625C13.7812 18.1453 14.6672 19.0312 15.75 19.0312C16.2553 19.0312 16.7147 18.8344 17.0625 18.5194C17.4103 18.8344 17.8697 19.0312 18.375 19.0312C18.8803 19.0312 19.3397 18.8344 19.6875 18.5194C20.0353 18.8344 20.4947 19.0312 21 19.0312C21.5053 19.0312 21.9647 18.8344 22.3125 18.5194C22.6603 18.8344 23.1197 19.0312 23.625 19.0312C24.7078 19.0312 25.5938 18.1453 25.5938 17.0625V16.4062H30.1875V21.6562ZM34.7812 11.8125V24.9375V27.5625C34.7812 27.9234 34.4859 28.2188 34.125 28.2188H32.1562C31.7953 28.2188 31.5 27.9234 31.5 27.5625V22.3125V15.75V9.1875C31.5 8.82656 31.7953 8.53125 32.1562 8.53125H34.125C34.4859 8.53125 34.7812 8.82656 34.7812 9.1875V11.8125ZM38.7188 23.625C38.7188 23.9859 38.4234 24.2812 38.0625 24.2812H36.0938V12.4688H38.0625C38.4234 12.4688 38.7188 12.7641 38.7188 13.125V23.625Z"
                                                    fill="#080808" />
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Training Nutrition Plan</h3>
                                        <p class="card-text">
                                            Optimise your training gains by eating with purpose. Perform
                                            at your peak with a personalised meal plan tailored to you &
                                            your preferences - designed by Extreme Sports Dietitian Kerry
                                            O'Bryan.
                                        </p>
                                    </div>
                                    <a href="{{ route('front.training.nutrition.plan') }}" class="btn-signup">Learn more</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="plan-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42"
                                                fill="none">
                                                <g clip-path="url(#clip0_2730_7210)">
                                                    <path
                                                        d="M26.7124 22.2748C26.4105 22.2748 26.1415 22.0583 26.089 21.7498C26.0299 21.402 26.2596 21.0739 26.6009 21.0083C28.1627 20.7261 29.5146 20.0633 30.6237 19.033C34.2002 15.7386 34.2396 9.77328 34.2396 9.51734C34.2396 9.47141 34.1937 9.42547 34.1412 9.42547H30.5515C30.1971 9.42547 29.9149 9.14328 29.9149 8.78891C29.9149 8.43453 30.1971 8.15234 30.5515 8.15234H34.1412C34.8893 8.15234 35.5062 8.76266 35.5127 9.51078C35.5127 10.213 35.4012 16.362 31.4899 19.9714C30.1971 21.1723 28.6287 21.9402 26.824 22.2683C26.7912 22.2748 26.7518 22.2748 26.7124 22.2748ZM17.2887 22.2617C17.2493 22.2617 17.2034 22.2552 17.164 22.2486C15.3987 21.9139 13.8565 21.1461 12.5768 19.9714C8.67211 16.3686 8.56055 10.213 8.56055 9.52391C8.56055 8.76922 9.17742 8.15891 9.93211 8.15891H13.5152C13.8696 8.15891 14.1518 8.44109 14.1518 8.79547C14.1518 9.14984 13.8696 9.43203 13.5152 9.43203H9.93211C9.87961 9.43203 9.83367 9.47797 9.83367 9.52391C9.83367 9.77328 9.87305 15.7452 13.443 19.0395C14.539 20.0502 15.8646 20.713 17.3937 21.0017C17.6955 21.0542 17.9252 21.3167 17.9252 21.6317C17.9252 21.9795 17.643 22.2617 17.2887 22.2617Z"
                                                        fill="#080808" />
                                                    <path
                                                        d="M21.8636 24.575C21.332 24.575 20.807 24.5094 20.3083 24.3847C12.8861 22.4816 12.8008 7.38781 12.8008 6.74469C12.8008 6.57406 12.8664 6.41656 12.9845 6.29187C13.1092 6.16719 13.2667 6.10156 13.4373 6.10156H30.6311C30.9855 6.10156 31.2677 6.38375 31.2677 6.73813C31.2677 7.37469 31.1823 22.3175 23.7798 24.3453C23.2417 24.4897 22.6577 24.5684 22.0408 24.5684C21.9817 24.575 21.9227 24.575 21.8636 24.575ZM14.087 7.37469C14.1855 10.2228 14.9336 21.6941 20.6233 23.1509C21.063 23.2625 21.5289 23.315 22.0211 23.3019C22.5395 23.3019 23.012 23.2428 23.4386 23.1247C29.1283 21.5628 29.8764 10.2097 29.9748 7.38125H14.087V7.37469Z"
                                                        fill="#080808" />
                                                    <path
                                                        d="M20.4647 30.7403C20.1103 30.7403 19.8281 30.4581 19.8281 30.1037V23.7709C19.8281 23.4166 20.1103 23.1344 20.4647 23.1344C20.8191 23.1344 21.1012 23.4166 21.1012 23.7709V30.1037C21.1012 30.4581 20.8125 30.7403 20.4647 30.7403ZM23.6081 30.7403C23.2537 30.7403 22.9716 30.4581 22.9716 30.1037V23.7381C22.9716 23.3837 23.2537 23.1016 23.6081 23.1016C23.9625 23.1016 24.2447 23.3837 24.2447 23.7381V30.1037C24.2447 30.4581 23.9559 30.7403 23.6081 30.7403Z"
                                                        fill="#080808" />
                                                    <path
                                                        d="M25.5895 32.6752C25.2351 32.6752 24.9529 32.393 24.9529 32.0386V30.9689C24.9529 30.8377 24.861 30.7458 24.7298 30.7458H19.342C19.2107 30.7458 19.1188 30.8442 19.1188 30.9689V32.0386C19.1188 32.393 18.8366 32.6752 18.4823 32.6752C18.1279 32.6752 17.8457 32.393 17.8457 32.0386V30.9689C17.8457 30.142 18.5151 29.4727 19.342 29.4727H24.7298C25.5698 29.4727 26.226 30.1289 26.226 30.9689V32.0386C26.226 32.3864 25.9438 32.6752 25.5895 32.6752Z"
                                                        fill="#080808" />
                                                    <path
                                                        d="M29.2385 35.2873C28.8841 35.2873 28.602 35.0052 28.602 34.6508V34.2177C28.602 33.3645 27.9129 32.6755 27.0663 32.6755H17.0126C16.1595 32.6755 15.4704 33.3711 15.4704 34.2177V34.6508C15.4704 35.0052 15.1882 35.2873 14.8338 35.2873C14.4795 35.2873 14.1973 35.0052 14.1973 34.6508V34.2177C14.1973 32.6623 15.4638 31.4023 17.0126 31.4023H27.0598C28.6085 31.4023 29.8685 32.6689 29.8685 34.2177V34.6508C29.8751 34.9986 29.5863 35.2873 29.2385 35.2873Z"
                                                        fill="#080808" />
                                                    <path
                                                        d="M30.6107 42.0001H13.4629C13.1085 42.0001 12.8263 41.7179 12.8263 41.3635V35.6804C12.8263 34.7616 13.5745 34.0135 14.4932 34.0135H29.5804C30.4991 34.0135 31.2473 34.7616 31.2473 35.6804V41.3635C31.2473 41.7179 30.9651 42.0001 30.6107 42.0001ZM14.0995 40.727H29.9741V35.6804C29.9741 35.4638 29.797 35.2866 29.5804 35.2866H14.4932C14.2766 35.2866 14.0995 35.4638 14.0995 35.6804V40.727ZM10.3523 6.18852C10.1488 6.18852 9.95195 6.09008 9.83383 5.91945L8.89539 4.5807L7.38602 3.95727C7.16289 3.86539 7.01852 3.66852 6.99227 3.43227C6.96602 3.20258 7.07102 2.97289 7.26133 2.84164L8.60008 1.9032L9.22352 0.393828C9.30883 0.177266 9.51227 0.0263284 9.74195 7.84369e-05C9.97164 -0.0261716 10.2013 0.0788284 10.3326 0.269141L11.271 1.60789L12.7804 2.23133C12.997 2.31664 13.1479 2.52008 13.1741 2.74977C13.2004 2.97945 13.0954 3.20914 12.9051 3.34039L11.5598 4.27883L10.9363 5.7882C10.851 6.00477 10.6476 6.1557 10.4179 6.18195C10.3982 6.18195 10.372 6.18852 10.3523 6.18852ZM8.94133 3.22227L9.55164 3.47164C9.6632 3.51758 9.76164 3.59633 9.83383 3.69477L10.2079 4.23289L10.4573 3.62258C10.5032 3.51102 10.582 3.41258 10.6804 3.34039L11.2185 2.96633L10.6082 2.71695C10.4966 2.67102 10.3982 2.59227 10.326 2.49383L9.95195 1.9557L9.70258 2.56602C9.65664 2.67758 9.57789 2.77602 9.47945 2.8482L8.94133 3.22227ZM33.5245 6.18852C33.321 6.18852 33.1241 6.09008 33.006 5.91945L32.0676 4.5807L30.5582 3.95727C30.3416 3.87195 30.1907 3.66852 30.1645 3.43883C30.1382 3.20914 30.2432 2.97945 30.4335 2.8482L31.7723 1.9032L32.3957 0.393828C32.481 0.177266 32.6845 0.0263284 32.9141 7.84369e-05C33.1438 -0.0261716 33.3735 0.0788284 33.5048 0.269141L34.4432 1.60789L35.9526 2.23133C36.1691 2.31664 36.3201 2.52008 36.3463 2.74977C36.3726 2.97945 36.2676 3.20914 36.0773 3.34039L34.7385 4.27883L34.1151 5.7882C34.0298 6.00477 33.8263 6.1557 33.5966 6.18195C33.5704 6.18195 33.5507 6.18852 33.5245 6.18852ZM32.1135 3.22227L32.7238 3.47164C32.8354 3.51758 32.9338 3.59633 33.006 3.69477L33.3801 4.23289L33.6295 3.62258C33.6754 3.51102 33.7541 3.41258 33.8526 3.34039L34.3907 2.96633L33.7804 2.71695C33.6688 2.67102 33.5704 2.59227 33.4982 2.49383L33.1241 1.9557L32.8748 2.56602C32.8288 2.67758 32.7501 2.77602 32.6516 2.8482L32.1135 3.22227ZM10.3523 28.6848C10.1488 28.6848 9.95195 28.5863 9.83383 28.4157L8.89539 27.077L7.38602 26.4535C7.16945 26.3682 7.01852 26.1648 6.99227 25.9351C6.96602 25.7054 7.07102 25.4757 7.26133 25.3445L8.60008 24.406L9.22352 22.8966C9.30883 22.6801 9.51227 22.5291 9.74195 22.5029C9.97164 22.4766 10.2013 22.5816 10.3326 22.772L11.271 24.1107L12.7804 24.7341C12.997 24.826 13.1479 25.0229 13.1741 25.2526C13.2004 25.4823 13.0954 25.712 12.9051 25.8432L11.5663 26.7816L10.9429 28.291C10.8576 28.5076 10.6541 28.6585 10.4245 28.6848C10.3982 28.6848 10.372 28.6848 10.3523 28.6848ZM8.94133 25.7185L9.55164 25.9679C9.6632 26.0138 9.76164 26.0926 9.83383 26.191L10.2079 26.7291L10.4573 26.1188C10.5032 26.0073 10.582 25.9088 10.6804 25.8366L11.2185 25.4626L10.6082 25.2132C10.4966 25.1673 10.3982 25.0885 10.326 24.9901L9.95195 24.452L9.70258 25.0623C9.65664 25.1738 9.57789 25.2723 9.47945 25.3445L8.94133 25.7185ZM32.6976 29.4854C32.4941 29.4854 32.2973 29.387 32.1791 29.2163L31.2407 27.8776L29.7313 27.2541C29.5148 27.1688 29.3638 26.9654 29.3376 26.7357C29.3113 26.506 29.4163 26.2763 29.6066 26.1451L30.9454 25.2066L31.5688 23.6973C31.6541 23.4807 31.8576 23.3298 32.0873 23.3035C32.317 23.2773 32.5466 23.3823 32.6779 23.5726L33.6163 24.9113L35.1257 25.5348C35.3423 25.6201 35.4932 25.8235 35.5195 26.0532C35.5457 26.2829 35.4407 26.5126 35.2504 26.6438L33.9116 27.5823L33.2882 29.0916C33.2029 29.3082 32.9995 29.4591 32.7698 29.4854C32.7435 29.4854 32.7173 29.4854 32.6976 29.4854ZM31.2866 26.5191L31.897 26.7685C32.0085 26.8145 32.107 26.8932 32.1791 26.9916L32.5532 27.5298L32.8026 26.9195C32.8485 26.8079 32.9273 26.7095 33.0257 26.6373L33.5638 26.2632L32.9535 26.0138C32.842 25.9679 32.7435 25.8891 32.6713 25.7907L32.2973 25.2526L32.0479 25.8629C32.002 25.9745 31.9232 26.0729 31.8248 26.1451L31.2866 26.5191Z"
                                                        fill="#080808" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2730_7210">
                                                        <rect width="42" height="42" fill="white" transform="translate(0.666016)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Competition Plan</h3>
                                        <p class="card-text">
                                            Unlock your peak performance with a 24-hour Competition
                                            Nutrition Plan - Ensuring you’re hydrated, fuelled & ON when
                                            it’s game time so that nutrition is never your weakness!
                                        </p>
                                    </div>
                                    <button class="btn-signup" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="plan-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42"
                                                fill="none">
                                                <path
                                                    d="M35.1329 3.96459H25.7138C25.5603 3.07993 25.0994 2.27785 24.4123 1.69976C23.7253 1.12167 22.8562 0.804688 21.9584 0.804688C21.0605 0.804688 20.1914 1.12167 19.5044 1.69976C18.8174 2.27785 18.3565 3.07993 18.203 3.96459H8.78906C8.61501 3.96459 8.44809 4.03373 8.32502 4.15681C8.20195 4.27988 8.13281 4.4468 8.13281 4.62084V39.6836C8.13281 39.8577 8.20195 40.0246 8.32502 40.1477C8.44809 40.2707 8.61501 40.3399 8.78906 40.3399H35.1329C35.307 40.3399 35.4739 40.2707 35.5969 40.1477C35.72 40.0246 35.7892 39.8577 35.7892 39.6836V4.62084C35.7892 4.4468 35.72 4.27988 35.5969 4.15681C35.4739 4.03373 35.307 3.96459 35.1329 3.96459ZM15.9205 7.75838C15.9502 7.8558 15.9857 7.95135 16.0268 8.0445C16.0406 8.07731 16.0492 8.11013 16.0642 8.14359C16.129 8.28126 16.2056 8.41301 16.2933 8.53734C16.3189 8.57409 16.3497 8.60297 16.3773 8.64103C16.443 8.72759 16.514 8.81 16.5899 8.88778C16.6306 8.92847 16.6739 8.96456 16.7172 9.00263C16.7895 9.06716 16.8653 9.12763 16.9443 9.18375C16.9935 9.21853 17.044 9.24938 17.0959 9.28219C17.1785 9.33216 17.264 9.3773 17.3518 9.41738C17.4056 9.44231 17.4581 9.46791 17.5139 9.48891C17.613 9.52595 17.7142 9.55685 17.8171 9.58144C17.867 9.59391 17.9155 9.61031 17.9667 9.62016C18.1251 9.65059 18.2859 9.66641 18.4471 9.66741H25.4755C25.8962 9.675 26.3121 9.57778 26.6858 9.38451C27.0596 9.19124 27.3793 8.90799 27.6162 8.56031C27.6379 8.52881 27.6582 8.49469 27.6818 8.46516C27.7523 8.35568 27.8146 8.24118 27.8682 8.12259C27.8741 8.10881 27.882 8.09634 27.8873 8.08256C27.9329 7.97597 27.971 7.86633 28.0014 7.75444H31.8773V36.5513H12.0408V7.75838H15.9205ZM21.958 2.11791C22.6221 2.12103 23.2581 2.38672 23.727 2.85699C24.1959 3.32727 24.4598 3.96395 24.461 4.62806C24.4598 4.75103 24.4506 4.8738 24.4334 4.99556C24.4209 5.08833 24.4283 5.1827 24.4552 5.27235C24.4822 5.362 24.528 5.44485 24.5896 5.51534C24.6512 5.58582 24.7272 5.64231 24.8124 5.68102C24.8976 5.71973 24.9901 5.73975 25.0838 5.73975H25.4729C25.5597 5.73988 25.6463 5.74845 25.7315 5.76534C26.0214 5.82439 26.2829 5.97968 26.4735 6.20602C26.6641 6.43235 26.7726 6.71645 26.7815 7.01222C26.7782 7.03847 26.7762 7.06538 26.7756 7.09163C26.7697 7.4191 26.6403 7.73226 26.4133 7.96838C26.1624 8.21488 25.8253 8.35379 25.4736 8.35556H18.4464C18.1056 8.35675 17.7778 8.22494 17.5327 7.98816C17.2876 7.75139 17.1445 7.42832 17.1339 7.08769C17.1335 7.06246 17.1317 7.03727 17.1287 7.01222C17.1353 6.76291 17.2125 6.52058 17.3512 6.31331C17.4709 6.13349 17.634 5.9867 17.8254 5.88647C18.0169 5.78623 18.2304 5.73578 18.4464 5.73975H18.8349C18.9311 5.73978 19.0262 5.71866 19.1133 5.67789C19.2004 5.63711 19.2775 5.57767 19.3392 5.50379C19.4008 5.4299 19.4454 5.34338 19.4698 5.25033C19.4943 5.15729 19.498 5.06001 19.4807 4.96538C19.4606 4.85209 19.4507 4.73722 19.4512 4.62216C19.4543 3.95804 19.7198 3.32209 20.1899 2.85297C20.66 2.38386 21.2965 2.11963 21.9607 2.11791H21.958ZM34.4767 39.0274H9.44531V5.27709H16.5052C16.4528 5.33484 16.4134 5.39981 16.3668 5.46084C16.3202 5.52188 16.2756 5.57372 16.2355 5.63409C16.1842 5.71886 16.1377 5.8065 16.0964 5.89659C16.0669 5.95631 16.0308 6.01341 16.0078 6.07444C15.9693 6.17725 15.9375 6.28248 15.9127 6.38944C15.9074 6.40847 15.9002 6.42619 15.8956 6.44522H11.3845C11.2105 6.44522 11.0436 6.51436 10.9205 6.63743C10.7974 6.7605 10.7283 6.92742 10.7283 7.10147V37.2076C10.7283 37.3816 10.7974 37.5486 10.9205 37.6716C11.0436 37.7947 11.2105 37.8638 11.3845 37.8638H32.5309C32.7049 37.8638 32.8718 37.7947 32.9949 37.6716C33.118 37.5486 33.1871 37.3816 33.1871 37.2076V7.10213C33.1871 6.92808 33.118 6.76116 32.9949 6.63809C32.8718 6.51502 32.7049 6.44588 32.5309 6.44588H28.0185C28.0185 6.43538 28.0119 6.42488 28.0093 6.41438C27.9844 6.30307 27.9524 6.19347 27.9135 6.08625C27.8925 6.03244 27.861 5.98453 27.8367 5.93269C27.7961 5.83962 27.7499 5.74911 27.6983 5.66166C27.6654 5.60784 27.6228 5.56191 27.586 5.51138C27.5328 5.43295 27.4752 5.35759 27.4134 5.28563L27.4056 5.27513H34.4767V39.0274Z"
                                                    fill="#080808" />
                                                <path
                                                    d="M20.7956 20.58H23.7205V17.951H26.3554V15.0196H23.7205V12.3906H20.7956V15.0196H18.166V17.951H20.7956V20.58Z"
                                                    fill="#080808" />
                                                <path
                                                    d="M20.2827 24.6548C20.2827 24.8288 20.3519 24.9957 20.4749 25.1188C20.598 25.2419 20.7649 25.311 20.939 25.311H29.6317C29.8057 25.311 29.9726 25.2419 30.0957 25.1188C30.2188 24.9957 30.2879 24.8288 30.2879 24.6548C30.2879 24.4807 30.2188 24.3138 30.0957 24.1907C29.9726 24.0677 29.8057 23.9985 29.6317 23.9985H20.939C20.7649 23.9985 20.598 24.0677 20.4749 24.1907C20.3519 24.3138 20.2827 24.4807 20.2827 24.6548ZM20.939 27.5029H28.1249C28.299 27.5029 28.4659 27.4338 28.5889 27.3107C28.712 27.1876 28.7812 27.0207 28.7812 26.8467C28.7812 26.6726 28.712 26.5057 28.5889 26.3826C28.4659 26.2595 28.299 26.1904 28.1249 26.1904H20.939C20.7649 26.1904 20.598 26.2595 20.4749 26.3826C20.3519 26.5057 20.2827 26.6726 20.2827 26.8467C20.2827 27.0207 20.3519 27.1876 20.4749 27.3107C20.598 27.4338 20.7649 27.5029 20.939 27.5029ZM29.6317 30.4869H20.939C20.7649 30.4869 20.598 30.556 20.4749 30.6791C20.3519 30.8022 20.2827 30.9691 20.2827 31.1431C20.2827 31.3172 20.3519 31.4841 20.4749 31.6072C20.598 31.7302 20.7649 31.7994 20.939 31.7994H29.6317C29.8057 31.7994 29.9726 31.7302 30.0957 31.6072C30.2188 31.4841 30.2879 31.3172 30.2879 31.1431C30.2879 30.9691 30.2188 30.8022 30.0957 30.6791C29.9726 30.556 29.8057 30.4869 29.6317 30.4869ZM28.1249 32.6807H20.939C20.7649 32.6807 20.598 32.7499 20.4749 32.8729C20.3519 32.996 20.2827 33.1629 20.2827 33.337C20.2827 33.511 20.3519 33.6779 20.4749 33.801C20.598 33.9241 20.7649 33.9932 20.939 33.9932H28.1249C28.299 33.9932 28.4659 33.9241 28.5889 33.801C28.712 33.6779 28.7812 33.511 28.7812 33.337C28.7812 33.1629 28.712 32.996 28.5889 32.8729C28.4659 32.7499 28.299 32.6807 28.1249 32.6807ZM13.7812 28.337H17.6886C17.8626 28.337 18.0295 28.2679 18.1526 28.1448C18.2757 28.0217 18.3448 27.8548 18.3448 27.6807V25.3117L19.5523 24.4947C19.6252 24.447 19.6878 24.3853 19.7366 24.3132C19.7854 24.2411 19.8194 24.1601 19.8366 24.0747C19.8537 23.9893 19.8537 23.9014 19.8366 23.8161C19.8194 23.7307 19.7854 23.6496 19.7366 23.5775C19.6878 23.5054 19.6252 23.4438 19.5523 23.3961C19.4794 23.3485 19.3978 23.3158 19.3122 23.3C19.2265 23.2842 19.1386 23.2856 19.0536 23.3042C18.9685 23.3227 18.888 23.358 18.8167 23.4079L18.3369 23.7327C18.3277 23.5668 18.2555 23.4107 18.135 23.2962C18.0144 23.1818 17.8548 23.1178 17.6886 23.1172H13.7812C13.6072 23.1172 13.4403 23.1863 13.3172 23.3094C13.1941 23.4325 13.125 23.5994 13.125 23.7734V27.6807C13.125 27.8548 13.1941 28.0217 13.3172 28.1448C13.4403 28.2679 13.6072 28.337 13.7812 28.337ZM17.0323 27.0245H14.4375V25.6766L15.2152 26.6852C15.3171 26.8175 15.4656 26.9059 15.6304 26.9325C15.7953 26.959 15.9641 26.9218 16.1024 26.8283L17.0323 26.1989V27.0245ZM17.0323 24.4297V24.6147L15.878 25.3963L15.3996 24.7755C15.2934 24.6381 15.1371 24.5484 14.9649 24.526C14.7927 24.5036 14.6186 24.5504 14.4808 24.6561C14.4631 24.6699 14.4546 24.6896 14.4388 24.704V24.4303L17.0323 24.4297ZM13.7812 34.8463H17.6886C17.8626 34.8463 18.0295 34.7772 18.1526 34.6541C18.2757 34.5311 18.3448 34.3641 18.3448 34.1901V30.2854C18.3448 30.1114 18.2757 29.9444 18.1526 29.8214C18.0295 29.6983 17.8626 29.6292 17.6886 29.6292H13.7812C13.6072 29.6292 13.4403 29.6983 13.3172 29.8214C13.1941 29.9444 13.125 30.1114 13.125 30.2854V34.1901C13.125 34.3641 13.1941 34.5311 13.3172 34.6541C13.4403 34.7772 13.6072 34.8463 13.7812 34.8463ZM14.4375 30.939H17.0323V33.5338H14.4375V30.939Z"
                                                    fill="#080808" />
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Injury & Recovery Nutrition Plan</h3>
                                        <p class="card-text">
                                            Optimised nutrition to support soft tissue injury. Hold
                                            muscle, reduce inflammation & limit fat gain with a
                                            personalised plan that caters to where you're at. Faster
                                            recovery is the goal & nutrition is too often overlooked!
                                        </p>
                                    </div>
                                    <button class="btn-signup" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                                </div>
                            </div>
                            <div class="col-md-4 web-hide">
                                <div class="plan-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42"
                                                fill="none">
                                                <path
                                                    d="M21 15.0938H24.9375C25.1115 15.0938 25.2785 15.0246 25.4015 14.9015C25.5246 14.7785 25.5938 14.6115 25.5938 14.4375V12.4688H27.5625C27.7365 12.4688 27.9035 12.3996 28.0265 12.2765C28.1496 12.1535 28.2188 11.9865 28.2188 11.8125V7.875C28.2188 7.70095 28.1496 7.53403 28.0265 7.41096C27.9035 7.28789 27.7365 7.21875 27.5625 7.21875H25.5938V5.25C25.5938 5.07595 25.5246 4.90903 25.4015 4.78596C25.2785 4.66289 25.1115 4.59375 24.9375 4.59375H21C20.826 4.59375 20.659 4.66289 20.536 4.78596C20.4129 4.90903 20.3438 5.07595 20.3438 5.25V7.21875H18.375C18.201 7.21875 18.034 7.28789 17.911 7.41096C17.7879 7.53403 17.7188 7.70095 17.7188 7.875V11.8125C17.7188 11.9865 17.7879 12.1535 17.911 12.2765C18.034 12.3996 18.201 12.4688 18.375 12.4688H20.3438V14.4375C20.3438 14.6115 20.4129 14.7785 20.536 14.9015C20.659 15.0246 20.826 15.0938 21 15.0938ZM19.0312 11.1562V8.53125H21C21.174 8.53125 21.341 8.46211 21.464 8.33904C21.5871 8.21597 21.6562 8.04905 21.6562 7.875V5.90625H24.2812V7.875C24.2812 8.04905 24.3504 8.21597 24.4735 8.33904C24.5965 8.46211 24.7635 8.53125 24.9375 8.53125H26.9062V11.1562H24.9375C24.7635 11.1562 24.5965 11.2254 24.4735 11.3485C24.3504 11.4715 24.2812 11.6385 24.2812 11.8125V13.7812H21.6562V11.8125C21.6562 11.6385 21.5871 11.4715 21.464 11.3485C21.341 11.2254 21.174 11.1562 21 11.1562H19.0312ZM39.375 21.6562H17.5612L9.68625 16.3209C9.61515 16.2719 9.53508 16.2374 9.45062 16.2194C9.36616 16.2014 9.27898 16.2002 9.19406 16.2159C9.10938 16.232 9.02871 16.2647 8.95664 16.312C8.88458 16.3593 8.82254 16.4203 8.77406 16.4916L7.2975 18.6638C7.24913 18.7352 7.21534 18.8156 7.19807 18.9001C7.1808 18.9847 7.18038 19.0719 7.19684 19.1566C7.2133 19.2413 7.24631 19.322 7.29399 19.394C7.34167 19.4659 7.40307 19.5277 7.47469 19.5759L14.4375 24.2812H6.5625V12.4688C6.5625 12.2947 6.49336 12.1278 6.37029 12.0047C6.24722 11.8816 6.0803 11.8125 5.90625 11.8125H2.625C2.45095 11.8125 2.28403 11.8816 2.16096 12.0047C2.03789 12.1278 1.96875 12.2947 1.96875 12.4688V36.75C1.96875 36.924 2.03789 37.091 2.16096 37.214C2.28403 37.3371 2.45095 37.4062 2.625 37.4062H5.90625C6.0803 37.4062 6.24722 37.3371 6.37029 37.214C6.49336 37.091 6.5625 36.924 6.5625 36.75V31.5H35.4375V36.75C35.4375 36.924 35.5066 37.091 35.6297 37.214C35.7528 37.3371 35.9197 37.4062 36.0938 37.4062H39.375C39.549 37.4062 39.716 37.3371 39.839 37.214C39.9621 37.091 40.0312 36.924 40.0312 36.75V22.3125C40.0312 22.1385 39.9621 21.9715 39.839 21.8485C39.716 21.7254 39.549 21.6562 39.375 21.6562ZM8.78719 18.8606L9.52875 17.7712L16.9837 22.8572C17.0952 22.9293 17.225 22.968 17.3578 22.9688H38.7188V24.2812H16.7475L8.78719 18.8606ZM5.25 36.0938H3.28125V13.125H5.25V36.0938ZM6.5625 30.1875V25.5938H35.4375V30.1875H6.5625ZM38.7188 36.0938H36.75V25.5938H38.7188V36.0938Z"
                                                    fill="#080808" />
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Pre & Post Surgery Nutrition Plan</h3>
                                        <p class="card-text">
                                            Poor nutritional status before surgery will delay your
                                            recovery. The Pre & Post Surgery Nutrition Plan will ensure
                                            you are well organised with specific food, snacks &
                                            supplements that will speed up healing, hold muscle, limit fat
                                            gain & get you back in the game!
                                        </p>
                                    </div>
                                    <button class="btn-signup" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                                </div>
                            </div>
                        </div>
                        <label class="choose-plan-label">Consults</label>
                        <div class="row">
                            <div class="col-md-4 mob-hide">
                                <div class="plan-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42"
                                                fill="none">
                                                <path
                                                    d="M21 15.0938H24.9375C25.1115 15.0938 25.2785 15.0246 25.4015 14.9015C25.5246 14.7785 25.5938 14.6115 25.5938 14.4375V12.4688H27.5625C27.7365 12.4688 27.9035 12.3996 28.0265 12.2765C28.1496 12.1535 28.2188 11.9865 28.2188 11.8125V7.875C28.2188 7.70095 28.1496 7.53403 28.0265 7.41096C27.9035 7.28789 27.7365 7.21875 27.5625 7.21875H25.5938V5.25C25.5938 5.07595 25.5246 4.90903 25.4015 4.78596C25.2785 4.66289 25.1115 4.59375 24.9375 4.59375H21C20.826 4.59375 20.659 4.66289 20.536 4.78596C20.4129 4.90903 20.3438 5.07595 20.3438 5.25V7.21875H18.375C18.201 7.21875 18.034 7.28789 17.911 7.41096C17.7879 7.53403 17.7188 7.70095 17.7188 7.875V11.8125C17.7188 11.9865 17.7879 12.1535 17.911 12.2765C18.034 12.3996 18.201 12.4688 18.375 12.4688H20.3438V14.4375C20.3438 14.6115 20.4129 14.7785 20.536 14.9015C20.659 15.0246 20.826 15.0938 21 15.0938ZM19.0312 11.1562V8.53125H21C21.174 8.53125 21.341 8.46211 21.464 8.33904C21.5871 8.21597 21.6562 8.04905 21.6562 7.875V5.90625H24.2812V7.875C24.2812 8.04905 24.3504 8.21597 24.4735 8.33904C24.5965 8.46211 24.7635 8.53125 24.9375 8.53125H26.9062V11.1562H24.9375C24.7635 11.1562 24.5965 11.2254 24.4735 11.3485C24.3504 11.4715 24.2812 11.6385 24.2812 11.8125V13.7812H21.6562V11.8125C21.6562 11.6385 21.5871 11.4715 21.464 11.3485C21.341 11.2254 21.174 11.1562 21 11.1562H19.0312ZM39.375 21.6562H17.5612L9.68625 16.3209C9.61515 16.2719 9.53508 16.2374 9.45062 16.2194C9.36616 16.2014 9.27898 16.2002 9.19406 16.2159C9.10938 16.232 9.02871 16.2647 8.95664 16.312C8.88458 16.3593 8.82254 16.4203 8.77406 16.4916L7.2975 18.6638C7.24913 18.7352 7.21534 18.8156 7.19807 18.9001C7.1808 18.9847 7.18038 19.0719 7.19684 19.1566C7.2133 19.2413 7.24631 19.322 7.29399 19.394C7.34167 19.4659 7.40307 19.5277 7.47469 19.5759L14.4375 24.2812H6.5625V12.4688C6.5625 12.2947 6.49336 12.1278 6.37029 12.0047C6.24722 11.8816 6.0803 11.8125 5.90625 11.8125H2.625C2.45095 11.8125 2.28403 11.8816 2.16096 12.0047C2.03789 12.1278 1.96875 12.2947 1.96875 12.4688V36.75C1.96875 36.924 2.03789 37.091 2.16096 37.214C2.28403 37.3371 2.45095 37.4062 2.625 37.4062H5.90625C6.0803 37.4062 6.24722 37.3371 6.37029 37.214C6.49336 37.091 6.5625 36.924 6.5625 36.75V31.5H35.4375V36.75C35.4375 36.924 35.5066 37.091 35.6297 37.214C35.7528 37.3371 35.9197 37.4062 36.0938 37.4062H39.375C39.549 37.4062 39.716 37.3371 39.839 37.214C39.9621 37.091 40.0312 36.924 40.0312 36.75V22.3125C40.0312 22.1385 39.9621 21.9715 39.839 21.8485C39.716 21.7254 39.549 21.6562 39.375 21.6562ZM8.78719 18.8606L9.52875 17.7712L16.9837 22.8572C17.0952 22.9293 17.225 22.968 17.3578 22.9688H38.7188V24.2812H16.7475L8.78719 18.8606ZM5.25 36.0938H3.28125V13.125H5.25V36.0938ZM6.5625 30.1875V25.5938H35.4375V30.1875H6.5625ZM38.7188 36.0938H36.75V25.5938H38.7188V36.0938Z"
                                                    fill="#080808" />
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Pre & Post Surgery Nutrition Plan</h3>
                                        <p class="card-text">
                                            Poor nutritional status before surgery will delay your
                                            recovery. The Pre & Post Surgery Nutrition Plan will ensure
                                            you are well organised with specific food, snacks &
                                            supplements that will speed up healing, hold muscle, limit fat
                                            gain & get you back in the game!
                                        </p>
                                    </div>
                                    <button class="btn-signup" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="plan-card orange-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42"
                                                fill="none">
                                                <path
                                                    d="M34.9036 19.7928C32.3343 19.719 27.0637 18.7468 26.3845 26.3032C26.2719 26.6687 25.235 28.0104 24.3192 29.0565C23.9301 29.4564 23.7837 30.0599 23.9373 30.638C24.2766 31.9164 25.4024 31.7769 25.991 31.8176C25.992 31.8911 25.9927 31.9686 25.9927 32.049V34.5936C25.9848 35.6213 26.8386 36.9042 29.3218 36.7598L29.919 40.1615C29.9448 40.3087 30.0216 40.442 30.136 40.5381C30.2504 40.6342 30.395 40.6868 30.5444 40.6868H38.6248C38.7096 40.6868 38.7934 40.6698 38.8715 40.6368C38.9495 40.6039 39.0202 40.5557 39.0794 40.495C39.1385 40.4344 39.185 40.3625 39.2159 40.2837C39.2469 40.2048 39.2618 40.1205 39.2598 40.0358C39.223 38.5544 39.2338 34.3996 39.7201 32.9861C40.3714 31.0889 41.3538 31.2431 41.3538 26.6024C41.3535 22.5077 38.0391 19.8831 34.9036 19.7928ZM39.2214 31.0803C38.9995 31.4672 38.7482 31.9056 38.5185 32.5736C37.9873 34.1188 37.96 37.8473 37.9745 39.3747H31.0707L30.593 36.6607C30.926 36.6289 31.2811 36.5912 31.6495 36.5469C33.282 36.3497 33.5625 35.6554 33.7591 34.7681C33.8503 34.3563 34.1541 33.6801 33.4792 33.4625C32.7527 33.2302 32.6175 34.0492 32.5191 34.4932C32.3868 35.089 32.3665 35.1806 31.4973 35.2856C30.1595 35.447 28.9973 35.5202 28.4861 35.5202C27.9119 35.5179 27.2583 35.0762 27.2619 34.5991V32.0496C27.2618 31.7646 27.2558 31.4796 27.2438 31.1948C27.2366 31.0393 27.1725 30.8918 27.0637 30.7804C26.9548 30.669 26.8089 30.6015 26.6535 30.5908L25.2833 30.4956C25.1481 30.4195 25.0706 30.1271 25.261 29.9086C26.3185 28.7027 27.591 27.1205 27.6467 26.4367C28.0661 21.6825 30.1143 20.39 35.2566 21.083C37.8856 21.4371 40.0824 24.0286 40.0824 26.6034C40.0833 29.5775 39.684 30.2738 39.2214 31.0803ZM1.97852 26.6024C1.97852 31.2434 2.96092 31.0892 3.61225 32.9861C4.09853 34.4 4.10936 38.5544 4.07261 40.0358C4.07051 40.1206 4.08538 40.2048 4.11634 40.2837C4.1473 40.3626 4.19373 40.4345 4.25289 40.4951C4.31206 40.5558 4.38276 40.604 4.46084 40.6369C4.53892 40.6699 4.62279 40.6868 4.70753 40.6868H12.7879C13.0964 40.6868 13.3602 40.4654 13.4133 40.1615L14.0105 36.7598C16.4935 36.9039 17.3476 35.6209 17.3397 34.5936V32.049C17.3397 31.9686 17.3403 31.8908 17.3413 31.8176C17.9297 31.7766 19.0558 31.9161 19.3951 30.638C19.5483 30.0599 19.402 29.4564 19.0131 29.0565C18.0973 28.0104 17.0605 26.6687 16.9479 26.3032C16.2687 18.7468 10.998 19.7187 8.4288 19.7925C5.29291 19.8831 1.97852 22.5077 1.97852 26.6024ZM3.24869 26.6024C3.24869 24.0279 5.46025 21.5342 8.07442 21.082C12.9707 20.2355 15.265 21.6818 15.6843 26.4357C15.7401 27.1195 17.0125 28.7018 18.0701 29.9076C18.2604 30.1261 18.1833 30.4182 18.0478 30.4946L16.6775 30.5898C16.356 30.6121 16.102 30.872 16.0872 31.1939C16.0872 31.1939 16.0692 31.581 16.0692 32.0486V34.5982C16.0728 35.0749 15.4195 35.5169 14.845 35.5192C14.3337 35.5192 13.1715 35.4457 11.8337 35.2846C10.9645 35.1796 10.9442 35.0884 10.812 34.4922C10.7135 34.0482 10.5783 33.2292 9.85188 33.4615C9.17692 33.6791 9.48044 34.3553 9.57198 34.7671C9.76886 35.6547 10.0491 36.349 11.6815 36.5459C12.0503 36.5902 12.405 36.6283 12.7381 36.6598L12.2603 39.3737H5.35656C5.371 37.8463 5.34409 34.1178 4.81253 32.5726C4.58317 31.9049 4.33183 31.4662 4.10969 31.0793C3.64802 30.2738 3.24869 29.5772 3.24869 26.6024ZM13.4629 15.0938V18.7031C13.4629 19.2668 14.252 19.5202 14.6517 19.1225L18.6007 15.4143L25.0408 15.4156L28.663 19.1041C29.0531 19.5254 29.8691 19.2819 29.8691 18.7031V15.0938C32.4495 14.5717 35.3203 12.0268 35.3203 8.25234C35.3203 4.41984 32.8183 1.3125 28.9661 1.3125H14.9874C10.4346 1.3125 8.01175 4.48416 8.01175 8.25234C8.01175 11.5572 10.2584 14.4277 13.4629 15.0938ZM14.5303 2.61516L29.2129 2.61647C32.002 2.61647 34.0501 5.43244 34.0501 8.25267C34.0501 11.884 30.7721 13.8121 29.0826 13.9342C28.7502 13.9578 28.5566 14.1045 28.5566 14.4375V17.0625L25.783 14.34C25.6629 14.2104 25.4667 14.1025 25.2902 14.1025L18.3848 14.1094C18.2171 14.1094 18.0032 14.2035 17.884 14.3217L14.7754 17.0625V14.4375C14.7754 14.1173 14.6005 13.9266 14.2829 13.8866C11.4318 13.5289 9.28192 11.1067 9.28192 8.25234C9.28192 5.66508 11.04 2.61516 14.5303 2.61516Z"
                                                    fill="#080808" />
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Consultations (1 on 1)</h3>
                                        <p class="card-text">
                                            An in-depth session to review your current approach, identify
                                            key opportunities, and give you practical, tailored strategies
                                            to reach your sporting goals. Get expert support that meets
                                            you where you’re at, with relevant education and answers to
                                            the questions that matter most.
                                        </p>
                                    </div>
                                    <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank" class="btn-signup">Learn more</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="plan-card white-card">
                                    <div class="plan-card-wrapper">
                                        <div class="plan-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42"
                                                fill="none">
                                                <g clip-path="url(#clip0_2730_7283)">
                                                    <path
                                                        d="M42.2807 32.4679L41.6343 28.5042C41.6121 26.5633 40.3407 24.9096 38.5171 24.4576L37.2169 23.9523C37.2084 23.9483 37.1997 23.945 37.1907 23.9424L35.4491 23.2722L35.3409 23.2304C35.1091 23.1415 34.909 22.9859 34.7657 22.7832C34.6225 22.5805 34.5426 22.3399 34.5361 22.0918V22.0475L34.5419 22.0434C35.2136 21.4694 35.7541 20.7577 36.1267 19.9565C36.6287 19.8212 37.0496 19.4717 37.3375 18.9426C37.5549 18.5278 37.6876 18.0738 37.728 17.6071C37.7398 17.4052 37.7037 17.2033 37.6226 17.0179C37.5415 16.8326 37.4176 16.6691 37.2612 16.5407C37.116 13.1118 34.7182 10.3761 31.793 10.3761C30.4551 10.3761 29.209 10.9618 28.2443 11.9413C27.374 8.35566 24.5948 5.72656 21.3094 5.72656C18.0798 5.72656 15.2612 8.32941 14.3687 11.9552C13.409 10.9708 12.1588 10.3761 10.7938 10.3761C8.07284 10.3761 5.73659 12.7993 5.36007 16.0133C5.33546 16.2257 5.31905 16.4407 5.31167 16.6556C5.10495 16.8836 4.86952 17.2298 4.90152 17.6055C4.97862 18.6211 5.49378 19.662 6.47816 19.9237C6.86617 20.7649 7.44305 21.5051 8.1639 22.0869L8.17538 22.0959C8.17177 22.3362 8.0929 22.5692 7.94986 22.7623C7.80682 22.9554 7.60683 23.0987 7.37804 23.1721L7.35179 23.1812L5.61683 23.748L5.56269 23.7652L4.04347 24.2615C2.25683 24.7406 1.00913 26.3919 0.990265 28.2999L0.341398 32.4729C0.327644 32.5606 0.333072 32.6503 0.357308 32.7357C0.381544 32.8211 0.424012 32.9003 0.481786 32.9677C0.53956 33.0352 0.611268 33.0893 0.691968 33.1264C0.772669 33.1634 0.860445 33.1825 0.949249 33.1824H7.6955L7.32718 35.5613C7.31371 35.649 7.31934 35.7386 7.34369 35.8239C7.36803 35.9092 7.41052 35.9883 7.46824 36.0556C7.52595 36.123 7.59754 36.1771 7.67811 36.2143C7.75867 36.2514 7.84631 36.2707 7.93503 36.2709H34.733C34.8222 36.2709 34.9103 36.2515 34.9912 36.214C35.0722 36.1766 35.144 36.122 35.2017 36.054C35.2595 35.986 35.3017 35.9063 35.3256 35.8204C35.3495 35.7345 35.3544 35.6444 35.34 35.5564L34.9512 33.1824H41.6778C41.7669 33.1823 41.8549 33.1628 41.9358 33.1253C42.0167 33.0878 42.0884 33.0332 42.1461 32.9652C42.2039 32.8973 42.2461 32.8177 42.2701 32.7318C42.294 32.646 42.299 32.5559 42.2848 32.4679H42.2807ZM34.806 25.2262L35.4007 24.5757L35.9364 24.7816L33.6822 27.3155C33.4793 26.9709 33.2389 26.6499 32.9652 26.3582C33.6725 26.1602 34.3102 25.768 34.806 25.2262ZM31.8529 25.2763C31.7231 25.274 31.5937 25.2622 31.4657 25.241C31.1482 25.0835 30.8157 24.9585 30.4731 24.8677C30.2365 24.7201 30.024 24.5369 29.8431 24.3247L29.515 23.9375C29.8876 23.6383 30.1614 23.2338 30.3008 22.7768C30.7798 22.9878 31.2967 23.0994 31.8201 23.1049C32.3675 23.1023 32.9083 22.9859 33.4082 22.7628C33.5523 23.2585 33.8477 23.6967 34.2531 24.0162L33.902 24.4002C33.6425 24.6839 33.3253 24.9088 32.9718 25.0599C32.6182 25.211 32.2365 25.2848 31.8521 25.2763H31.8529ZM31.7922 11.6107C33.6018 11.6107 35.1505 13.0224 35.76 15.0043C34.7207 14.0068 33.642 13.3932 31.8734 13.5105C30.8168 13.4449 29.8767 13.9207 29.3444 14.3907C29.2397 14.4829 29.1426 14.5833 29.054 14.6909C28.9395 14.4156 28.7558 14.1746 28.5208 13.9912C28.5159 13.8509 28.5085 13.7123 28.4986 13.5737C29.291 12.3719 30.483 11.6107 31.7914 11.6107H31.7922ZM28.6471 17.0411C28.6939 16.955 28.7357 16.8689 28.7743 16.7836L29.2763 16.5645C29.449 16.4887 29.597 16.3661 29.7037 16.2106C29.8104 16.055 29.8715 15.8728 29.88 15.6843C29.9079 15.43 30.7775 14.6598 31.825 14.7402C31.8559 14.7422 31.8868 14.7422 31.9177 14.7402C33.8159 14.6015 34.6288 15.5145 36.1013 17.1724L36.1382 17.2134C36.2054 17.3135 36.3004 17.3919 36.4114 17.439C36.445 17.4606 36.4743 17.4885 36.4975 17.521C36.4681 17.8112 36.3847 18.0933 36.2514 18.3528C36.0463 18.7285 35.8191 18.7802 35.6665 18.7868C35.5484 18.7916 35.4342 18.8304 35.3376 18.8985C35.241 18.9665 35.1661 19.0611 35.1218 19.1707C34.8252 19.914 34.3539 20.5749 33.7478 21.0976C33.7043 21.1345 33.6584 21.1706 33.6116 21.205C33.5661 21.2296 33.5242 21.2602 33.4869 21.2961C33.0007 21.6542 32.4156 21.8531 31.8119 21.8654C31.277 21.8654 30.711 21.6726 30.1712 21.3068C30.1471 21.2865 30.121 21.2686 30.0933 21.2534C30.0342 21.2116 29.976 21.1714 29.9202 21.1238C29.2748 20.6003 28.7762 19.9183 28.4732 19.1444C28.4295 19.0333 28.3544 18.9373 28.2571 18.8681C28.1598 18.7989 28.0445 18.7595 27.9252 18.7548C27.6209 18.7425 27.433 18.5128 27.31 18.2544C27.8604 18.0624 28.3141 17.6506 28.6463 17.0411H28.6471ZM16.0315 25.4034L15.7854 25.1089L15.5246 24.7914L16.591 24.442L16.7025 24.4051L17.5442 25.3895C18.2762 26.2499 19.272 26.8445 20.3767 27.0809C20.7004 27.1507 21.0301 27.1886 21.3611 27.1941H21.453C21.6874 27.1958 21.9218 27.1815 22.1543 27.1515C23.3407 26.9878 24.4325 26.4147 25.2412 25.5314L25.2945 25.4731C25.3191 25.4649 25.3437 25.4584 25.3683 25.4518L25.4036 25.4411L26.5578 25.0638L27.0811 24.8932L27.5282 24.7471L27.3174 24.9842L26.8892 25.4666L22.9935 29.847L21.4858 31.5426L21.2774 31.7772L21.1429 31.6132L19.5892 29.7264L16.0315 25.4034ZM17.9707 21.3904C17.9733 21.7702 17.8548 22.1409 17.6325 22.4489C17.4103 22.7568 17.0958 22.986 16.7345 23.1032L16.705 23.1131L15.2588 23.5856L14.4532 23.2755L14.345 23.2337C14.1134 23.1447 13.9134 22.989 13.7703 22.7863C13.6272 22.5836 13.5474 22.3431 13.541 22.0951V22.0516L13.5468 22.0467C14.2181 21.4724 14.7582 20.7608 15.1308 19.9598C15.5275 19.8476 15.8759 19.6074 16.1217 19.2765C16.588 20.0266 17.178 20.6922 17.8666 21.2452C17.8994 21.2715 17.933 21.2977 17.9675 21.3273L17.9707 21.3904ZM19.1323 22.0418C19.8215 22.3822 20.5785 22.563 21.3471 22.5709C22.1492 22.5654 22.939 22.3742 23.6547 22.0122C23.819 22.8224 24.2959 23.5353 24.9819 23.9966L24.6038 24.4067L24.3396 24.6963C24.2127 24.8356 24.0756 24.9653 23.9295 25.0843L23.8704 25.1319C23.8073 25.1819 23.7425 25.2295 23.6768 25.2738C23.644 25.2968 23.6112 25.3189 23.5776 25.3402C23.4363 25.4323 23.2888 25.5145 23.1362 25.5863L23.06 25.6216L22.9828 25.6552C22.9558 25.6675 22.9279 25.679 22.9008 25.6897C22.1442 25.9899 21.3125 26.0455 20.5227 25.8487C19.7328 25.6518 19.0245 25.2124 18.4974 24.5921L17.8887 23.8907C18.5238 23.4562 18.9678 22.7943 19.129 22.0418H19.1323ZM27.118 23.5872L26.0516 23.1771C25.6959 23.041 25.3886 22.8024 25.1686 22.4915C24.9487 22.1806 24.8261 21.8113 24.8162 21.4306L24.8113 21.2666L24.9016 21.1919C25.5381 20.6526 26.0852 20.0158 26.5225 19.3052C26.7644 19.6099 27.0971 19.8294 27.4724 19.9319C27.861 20.7727 28.4378 21.5129 29.1582 22.0951L29.1705 22.1049C29.1661 22.3449 29.0869 22.5775 28.9439 22.7703C28.801 22.9631 28.6014 23.1064 28.3731 23.1804L28.3469 23.1894L27.118 23.5872ZM15.3662 13.3382V13.3136C15.801 9.69031 18.3497 6.96113 21.3045 6.96113C24.0993 6.96113 26.4528 9.33102 27.109 12.5245C25.5406 10.8757 24.0558 9.80516 21.4144 9.98152C20.0404 9.88965 18.8173 10.5073 18.1258 11.1209C17.6049 11.5828 17.2989 12.1053 17.2603 12.5975L15.4163 13.4014L15.3523 13.4293C15.3589 13.4022 15.3621 13.3702 15.3662 13.3382ZM14.8101 15.1749C14.8248 15.1528 14.8429 15.1298 14.8617 15.106C14.9126 15.0453 14.9717 14.9797 15.0316 14.9214L15.9011 14.5416L17.8009 13.7131C17.998 13.6264 18.1669 13.4864 18.2886 13.3089C18.4104 13.1313 18.4801 12.9233 18.49 12.7082C18.49 12.609 18.5942 12.3588 18.9453 12.0471C19.4325 11.6148 20.3587 11.1373 21.3693 11.2169C21.4002 11.219 21.4311 11.219 21.462 11.2169C23.9385 11.0356 25.091 12.0987 26.8588 14.0683L27.328 14.595L27.3912 14.6655C27.462 14.775 27.5657 14.8591 27.6873 14.9059C27.7347 14.938 27.7789 14.9745 27.8194 15.015C27.8498 15.0405 27.8745 15.0721 27.892 15.1076C27.9095 15.1432 27.9194 15.1821 27.9211 15.2217C27.9017 15.4398 27.8611 15.6554 27.7997 15.8656C27.7421 16.0693 27.6629 16.2662 27.5635 16.453L27.5495 16.4776C27.182 17.1338 26.7341 17.1519 26.5848 17.1584C26.512 17.1617 26.4404 17.1778 26.3732 17.206C26.2981 17.237 26.2299 17.2828 26.1727 17.3405C26.1155 17.3983 26.0704 17.4669 26.0401 17.5423C26.0019 17.6375 25.9608 17.7318 25.9171 17.8254C25.5012 18.7553 24.8849 19.5819 24.1124 20.2461C24.046 20.3011 23.9771 20.356 23.9065 20.4102C23.8489 20.4391 23.7966 20.4773 23.7515 20.5234C22.9927 21.059 22.1609 21.3437 21.3414 21.3437C20.5687 21.3437 19.7549 21.0672 18.983 20.5422C18.9508 20.5147 18.9159 20.4905 18.8788 20.47H18.8739C18.7919 20.4118 18.7098 20.3511 18.6335 20.2896C17.8018 19.6166 17.1427 18.7549 16.7107 17.7761C16.6714 17.6875 16.6344 17.5981 16.5992 17.5087C16.5654 17.422 16.5122 17.3441 16.4437 17.2811C16.3751 17.2181 16.2931 17.1717 16.2038 17.1453C16.1546 17.1299 16.1035 17.1211 16.052 17.1191C15.518 17.0969 15.1997 16.7089 15.011 16.2873C14.8744 15.9649 14.7865 15.624 14.7502 15.2758C14.767 15.2404 14.787 15.2066 14.8101 15.1749ZM10.7905 11.6131C12.1301 11.6131 13.3269 12.3859 14.1046 13.5917C14.0898 13.7714 14.0808 13.9527 14.0759 14.1331C14.0259 14.1856 13.9725 14.2439 13.9209 14.3079C13.1063 13.7336 12.1769 13.426 10.8717 13.513C9.81519 13.4473 8.87511 13.9231 8.34355 14.3932C7.9621 14.7311 7.72503 15.1159 7.66433 15.4916L6.60941 15.9493C6.98347 13.4621 8.75288 11.6107 10.7922 11.6107L10.7905 11.6131ZM7.4773 19.1485C7.43362 19.0374 7.35855 18.9414 7.26125 18.8722C7.16395 18.803 7.04862 18.7636 6.92933 18.7589C6.33296 18.7343 6.16972 17.8729 6.1328 17.5989C6.17307 17.5429 6.21778 17.4903 6.26651 17.4414L8.27628 16.5645C8.44897 16.4887 8.59703 16.3661 8.70371 16.2106C8.8104 16.055 8.87147 15.8728 8.88003 15.6843C8.90792 15.43 9.77827 14.6598 10.825 14.7402C10.8559 14.7422 10.8868 14.7422 10.9177 14.7402C12.0596 14.6581 12.8044 14.9534 13.5427 15.5654C13.6715 16.503 14.1612 17.8057 15.3228 18.215C15.3014 18.2634 15.2793 18.311 15.2539 18.3569C15.0488 18.7326 14.8224 18.7843 14.669 18.7909C14.5509 18.7958 14.4367 18.8346 14.3402 18.9027C14.2436 18.9708 14.1686 19.0652 14.1243 19.1748C13.8277 19.9181 13.3564 20.579 12.7503 21.1017C12.7068 21.1378 12.6625 21.173 12.6166 21.2075C12.5698 21.2326 12.5267 21.2641 12.4886 21.301C12.0027 21.659 11.4178 21.8576 10.8143 21.8695C10.2803 21.8695 9.71347 21.6767 9.1737 21.3109C9.14791 21.2896 9.12048 21.2704 9.09167 21.2534C9.03425 21.2141 8.97765 21.1714 8.92269 21.1279C8.27781 20.6042 7.7798 19.9222 7.4773 19.1485ZM13.2498 24.0121L12.9512 24.3403L12.1949 24.5864C11.6534 24.729 11.1384 24.958 10.67 25.2648C9.9593 25.2041 9.30133 24.8653 8.83902 24.3222L8.51089 23.935C8.88386 23.6362 9.15779 23.2316 9.29675 22.7743C9.77571 22.9854 10.2926 23.0971 10.816 23.1024C11.3633 23.0999 11.9042 22.9834 12.4041 22.7604C12.549 23.2554 12.8446 23.693 13.2498 24.0121ZM7.35507 24.4764L7.90386 25.1212C8.34306 25.6372 8.90823 26.0308 9.54448 26.2639C9.30071 26.5505 9.08661 26.8611 8.90546 27.1909L6.81448 24.6536L7.35507 24.4764ZM8.3862 28.5001C8.27206 28.9482 8.21257 29.4084 8.20901 29.8708L7.88663 31.952H5.48886V29.0037C5.48886 28.8406 5.42404 28.6841 5.30866 28.5687C5.19328 28.4533 5.03679 28.3885 4.87362 28.3885C4.71045 28.3885 4.55397 28.4533 4.43859 28.5687C4.32321 28.6841 4.25839 28.8406 4.25839 29.0037V31.952H1.66702L2.21089 28.4418C2.21583 28.4106 2.21829 28.3791 2.21827 28.3475C2.21827 26.9735 3.10257 25.7816 4.36831 25.451L4.40359 25.4403L5.55777 25.063L8.3862 28.4993V28.5001ZM30.017 35.0363V31.0931C30.017 30.9299 29.9522 30.7734 29.8368 30.658C29.7214 30.5427 29.565 30.4778 29.4018 30.4778C29.2386 30.4778 29.0821 30.5427 28.9668 30.658C28.8514 30.7734 28.7866 30.9299 28.7866 31.0931V35.0363H13.8815V30.8109C13.8815 30.6477 13.8167 30.4912 13.7013 30.3759C13.5859 30.2605 13.4294 30.1957 13.2662 30.1957C13.1031 30.1957 12.9466 30.2605 12.8312 30.3759C12.7158 30.4912 12.651 30.6477 12.651 30.8109V35.0363H8.65362L8.94073 33.1824L9.13187 31.952L9.4321 30.0136C9.43674 29.9826 9.43893 29.9513 9.43866 29.92V29.7814C9.45525 29.2519 9.56637 28.7296 9.76679 28.2392C10.0407 27.5583 10.4868 26.9602 11.0612 26.5034C11.4934 26.1608 11.992 25.9116 12.5255 25.7717C12.5378 25.7717 12.5493 25.7652 12.5616 25.7619L13.628 25.4141L14.2744 25.2024L14.4155 25.3739L15.2038 26.3312L19.8271 31.9487L20.7828 33.1102C20.7883 33.1174 20.7944 33.1243 20.8008 33.1307C20.8098 33.1406 20.8189 33.1504 20.8287 33.1595C20.8349 33.1664 20.8418 33.1727 20.8492 33.1783C20.864 33.1914 20.8787 33.2038 20.8943 33.2152L20.9394 33.2456L20.946 33.2497L20.9911 33.2735C21.0144 33.2849 21.0385 33.2945 21.0633 33.3022C21.0759 33.3071 21.0888 33.3113 21.1019 33.3145L21.1363 33.3219H21.1552L21.1995 33.3276H21.3094C21.3196 33.3271 21.3298 33.3257 21.3398 33.3235L21.3676 33.3194L21.3931 33.3137C21.4084 33.3104 21.424 33.3063 21.4398 33.3014C21.4554 33.2964 21.4702 33.2907 21.4858 33.2841H21.4932C21.5019 33.2807 21.5104 33.2766 21.5186 33.2718C21.5334 33.2656 21.5476 33.2582 21.5612 33.2497C21.5746 33.2423 21.5874 33.234 21.5998 33.2251L21.6211 33.2095C21.6282 33.2056 21.6348 33.2009 21.6408 33.1955L21.6613 33.1775C21.6761 33.1636 21.6908 33.1488 21.7048 33.1332C21.7051 33.1319 21.7051 33.1304 21.7048 33.1291L21.7212 33.1102L22.7532 31.9503L25.2444 29.1489L25.9196 28.3902L27.6668 26.4214L28.4945 25.4912L28.5306 25.4502L29.967 26.003L29.9785 26.0071C30.3124 26.1924 30.6717 26.3278 31.0449 26.4091C31.8156 26.8355 32.4305 27.4964 32.8003 28.2958C33.0761 28.8924 33.2191 29.5417 33.2195 30.1989C33.2198 30.2319 33.2225 30.2648 33.2277 30.2974L33.4968 31.9487L33.6978 33.1791L33.9996 35.033L30.017 35.0363ZM38.3645 31.952V29.2121C38.3645 29.0489 38.2997 28.8925 38.1843 28.7771C38.0689 28.6617 37.9125 28.5969 37.7493 28.5969C37.5861 28.5969 37.4296 28.6617 37.3143 28.7771C37.1989 28.8925 37.1341 29.0489 37.1341 29.2121V31.952H34.7551L34.4615 30.1473C34.4572 29.6092 34.3777 29.0744 34.2252 28.5583L37.166 25.2541L38.1176 25.62C38.1444 25.6299 38.1718 25.6381 38.1996 25.6446C39.4998 25.953 40.4087 27.1498 40.4087 28.5559C40.4088 28.5888 40.4112 28.6217 40.4161 28.6543L40.9542 31.952H38.3645Z"
                                                        fill="#080808" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2730_7283">
                                                        <rect width="42" height="42" fill="white" transform="translate(0.333984)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        <h3 class="card-title">Clubs and Group bookings</h3>
                                        <p class="card-text">
                                            Want to share the knowledge with the rest of your sports club?
                                            Contact us for club deals and group bookings.
                                        </p>
                                    </div>
                                    <button class="btn-signup" onclick="scrollToContact()">Learn more</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_FIND_YOUR_SPORT && $section->enabled == 1)
                <!-- sport nutrition Section -->
                <section class="sport-nutrition-promo">
                    <div class="sport-nutrition-promo__container">
                        <div class="sport-nutrition-promo__half--left sport-nutrition-promo__half">
                            <div class="sport-nutrition-promo__overlay"></div>
                            <form action="#" id="sport-form">
                                <div class="sport-nutrition-promo__content">
                                    <h2 class="sport-nutrition-promo__title">{{ $section->title }}</h2>
                                    <p class="sport-nutrition-promo__desc">{!! $section->content !!}</p>
                                    <div class="sport-nutrition-promo__form">
                                        <div class="custom-select-wrapper">
                                        <select name="sport" id="sport" required data-custom="true">
                                            <option value="">Select Category</option>
                                            @foreach($sportCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="custom-select-arrow">
                                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#3b3b3b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                         <div class="vertical-line"></div>
                                        <div class="custom-select-wrapper">
                                            <select name="sport_game" id="sport_game" required data-custom="true">
                                                <option value="">Sport</option>
                                            </select>
                                            <div class="custom-select-arrow">
                                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#3b3b3b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="vertical-line"></div>
                                        <div class="custom-select-wrapper">
                                            <select name="state" required data-custom="true">
                                                <option value="">State</option>
                                                <option value="New South Wales">New South Wales (NSW)</option>
                                                <option value="Victoria">Victoria (VIC)</option>
                                                <option value="Queensland">Queensland (QLD)</option>
                                                <option value="South Australia">South Australia (SA)</option>
                                                <option value="Western Australia">Western Australia (WA)</option>
                                                <option value="Tasmania">Tasmania (TAS)</option>
                                                <option value="Australian Capital Territory">Australian Capital Territory (ACT)</option>
                                                <option value="Northern Territory">Northern Territory (NT)</option>
                                            </select>
                                            <div class="custom-select-arrow">
                                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#3b3b3b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn-signup" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            style="margin-left: 8px">
                                            <path
                                                d="M22.7073 21.293L17.032 15.6178C18.3089 14.0241 19.0032 12.0421 19 10C19 5.0295 14.9707 1 10 1C5.0295 1 1 5.0295 1 10C1 14.9705 5.0295 19 10 19C12.125 19 14.078 18.2635 15.6178 17.0317L21.293 22.707C21.3857 22.8 21.4959 22.8738 21.6173 22.924C21.7386 22.9743 21.8687 23.0001 22 23C22.1978 23 22.3911 22.9414 22.5556 22.8315C22.72 22.7216 22.8482 22.5655 22.9239 22.3828C22.9996 22.2001 23.0194 21.999 22.9808 21.805C22.9423 21.6111 22.8471 21.4329 22.7073 21.293ZM10 17C6.134 17 3 13.866 3 10C3 6.134 6.134 3 10 3C13.8663 3 17 6.134 17 10C17 13.866 13.8663 17 10 17Z"
                                                fill="white" />
                                        </svg>
                                        Search
                                    </button>
                                </div>
                            </form>
                            <img @if(!empty($section->banner_image[0])) src="{{ asset('storage/' . $section->banner_image[0]) }}" @endif
                                alt="Find your sport" class="sport-nutrition-promo__bg--left sport-nutrition-promo__bg" />
                        </div>
                        <div class="sport-nutrition-promo__half--right sport-nutrition-promo__half">
                            <div class="sport-nutrition-promo__overlay"></div>
                            <div class="sport-nutrition-promo__content">
                                <h2 class="sport-nutrition-promo__title">
                                    TAKE THE<br />NUTRITION QUIZ
                                </h2>
                                <p class="sport-nutrition-promo__desc">
                                    Think you’ve nailed your nutrition? Let’s put it to the test. Take
                                    the quiz to test your nutrition knowledge and discover how to fuel
                                    smarter—whether for performance, recovery, or everyday energy.
                                </p>
                                <button class="btn-signup" data-bs-toggle="modal" data-bs-target="#quizModal">Start the quiz</button>
                            </div>
                            <img @if(!empty($section->banner_image[1])) src="{{ asset('storage/' . $section->banner_image[1]) }}" @endif
                                alt="Nutrition quiz" class="sport-nutrition-promo__bg--right sport-nutrition-promo__bg" />
                        </div>
                    </div>
                </section>

            @endif
            @if($section->section_type == \App\Models\Section::TYPE_REAL_STORIES && $section->enabled == 1)
                <!-- testimonial slider section -->
                {{-- Make this section dynamic --}}
                <section class="testimonial-section">
                    <div class="container-homepage">
                        <h2 class="text-md-start text-center section-title">REAL STORIES. REAL RESULTS.</h2>

                        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($testimonials as $key => $testimonial)
                                    <!-- Testimonial {{ $key + 1 }} -->
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <div class="d-flex flex-column flex-md-row align-items-center testimonial-card">
                                            <div class="me-md-5 mb-4 mb-md-0 testimonial-image-wrapper">
                                                @php
                                                    $testimonialImage = $testimonial->testimonialImage ? asset('storage' . $testimonial->testimonialImage->path . '/' . $testimonial->testimonialImage->name) : null;
                                                @endphp
                                                <img src="{{ $testimonialImage }}" alt="{{ $testimonial->name }}"
                                                    class="rounded-3 img-fluid" />
                                            </div>
                                            <div class="text-md-start text-center testimonial-content">
                                                <div class="quote-icon web">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36"
                                                        fill="none">
                                                        <path
                                                            d="M31.2649 7.26638C31.0583 6.94451 30.7012 6.75 30.319 6.75H22.5C20.6389 6.75 19.125 8.26391 19.125 10.125V18C19.125 19.8611 20.6389 21.375 22.5 21.375H24.7336C23.6888 24.4007 22.7967 25.8497 19.8281 27.0867C19.3327 27.2933 19.0524 27.8206 19.158 28.3469C19.2634 28.8721 19.7248 29.25 20.261 29.25H20.2633C26.4563 29.239 30.0136 26.7561 32.5361 20.6873C33.3413 18.7811 33.75 16.741 33.75 14.625C33.75 11.3708 32.7656 9.59873 31.2649 7.26638ZM13.444 6.75H5.625C3.76391 6.75 2.25 8.26391 2.25 10.125V18C2.25 19.8611 3.76391 21.375 5.625 21.375H7.85858C6.81379 24.4007 5.92166 25.8497 2.95312 27.0867C2.45767 27.2933 2.17744 27.8206 2.28296 28.3469C2.38837 28.8721 2.84985 29.25 3.38602 29.25H3.38828C9.58129 29.239 13.1386 26.7561 15.6611 20.6873C16.4663 18.7811 16.875 16.741 16.875 14.625C16.875 11.3708 15.8906 9.59873 14.3899 7.26638C14.1833 6.94451 13.8263 6.75 13.444 6.75Z"
                                                            fill="#080808" />
                                                    </svg>
                                                </div>
                                                <p class="quote-text">
                                                    <span class="quote-icon mobile">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36"
                                                            fill="none">
                                                            <path
                                                                d="M31.2649 7.26638C31.0583 6.94451 30.7012 6.75 30.319 6.75H22.5C20.6389 6.75 19.125 8.26391 19.125 10.125V18C19.125 19.8611 20.6389 21.375 22.5 21.375H24.7336C23.6888 24.4007 22.7967 25.8497 19.8281 27.0867C19.3327 27.2933 19.0524 27.8206 19.158 28.3469C19.2634 28.8721 19.7248 29.25 20.261 29.25H20.2633C26.4563 29.239 30.0136 26.7561 32.5361 20.6873C33.3413 18.7811 33.75 16.741 33.75 14.625C33.75 11.3708 32.7656 9.59873 31.2649 7.26638ZM13.444 6.75H5.625C3.76391 6.75 2.25 8.26391 2.25 10.125V18C2.25 19.8611 3.76391 21.375 5.625 21.375H7.85858C6.81379 24.4007 5.92166 25.8497 2.95312 27.0867C2.45767 27.2933 2.17744 27.8206 2.28296 28.3469C2.38837 28.8721 2.84985 29.25 3.38602 29.25H3.38828C9.58129 29.239 13.1386 26.7561 15.6611 20.6873C16.4663 18.7811 16.875 16.741 16.875 14.625C16.875 11.3708 15.8906 9.59873 14.3899 7.26638C14.1833 6.94451 13.8263 6.75 13.444 6.75Z"
                                                                fill="#080808" />
                                                        </svg>
                                                    </span>
                                                    {!! $testimonial->review !!}
                                                </p>
                                                <p class="mb-1 author-name fw-bold">{{ $testimonial->name }}</p>
                                                <p class="text-muted author-title">
                                                    {{ $testimonial->designation }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Carousel Controls for Desktop -->
                            <button class="d-md-flex carousel-control-prev d-none" type="button" data-bs-target="#testimonialCarousel"
                                data-bs-slide="prev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 9 14" fill="none">
                                    <path
                                        d="M0.748587 6.23192C0.323512 6.65699 0.323512 7.34732 0.748587 7.77239L6.18955 13.2134C6.61462 13.6384 7.30495 13.6384 7.73002 13.2134C8.1551 12.7883 8.1551 12.098 7.73002 11.6729L3.0576 7.00046L7.72662 2.32803C8.1517 1.90295 8.1517 1.21263 7.72662 0.787556C7.30155 0.362481 6.61122 0.362481 6.18615 0.787556L0.745186 6.22852L0.748587 6.23192Z"
                                        fill="#3B3B3B" />
                                </svg>
                            </button>
                            <button class="d-md-flex carousel-control-next d-none" type="button" data-bs-target="#testimonialCarousel"
                                data-bs-slide="next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 9 14" fill="none">
                                    <path
                                        d="M8.25141 7.76808C8.67649 7.34301 8.67649 6.65268 8.25141 6.22761L2.81045 0.786644C2.38538 0.361568 1.69505 0.361568 1.26998 0.786644C0.844903 1.21172 0.844903 1.90204 1.26998 2.32712L5.9424 6.99954L1.27338 11.672C0.848303 12.097 0.848303 12.7874 1.27338 13.2124C1.69845 13.6375 2.38878 13.6375 2.81385 13.2124L8.25481 7.77148L8.25141 7.76808Z"
                                        fill="#3B3B3B" />
                                </svg>
                            </button>

                            <!-- Carousel Controls for Mobile -->
                            <div class="d-flex justify-content-center mt-4 carousel-controls-mobile d-md-none">
                                <button class="carousel-control-prev-mobile" type="button" data-bs-target="#testimonialCarousel"
                                    data-bs-slide="prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="16" viewBox="0 0 9 16" fill="none">
                                        <path
                                            d="M0.714963 7.13591C0.236753 7.61412 0.236753 8.39073 0.714963 8.86894L6.83605 14.99C7.31426 15.4682 8.09087 15.4682 8.56908 14.99C9.04729 14.5118 9.04729 13.7352 8.56908 13.257L3.3126 8.00051L8.56525 2.74403C9.04346 2.26582 9.04346 1.48921 8.56525 1.011C8.08704 0.532791 7.31043 0.532791 6.83222 1.011L0.711138 7.13208L0.714963 7.13591Z"
                                            fill="#3B3B3B" />
                                    </svg>
                                </button>
                                <button class="ms-3 carousel-control-next-mobile" type="button" data-bs-target="#testimonialCarousel"
                                    data-bs-slide="next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="16" viewBox="0 0 9 16" fill="none">
                                        <path
                                            d="M8.28504 8.86409C8.76325 8.38588 8.76325 7.60927 8.28504 7.13106L2.16395 1.00997C1.68574 0.531765 0.909132 0.531765 0.430923 1.00997C-0.0472868 1.48818 -0.0472868 2.2648 0.430923 2.74301L5.6874 7.99949L0.434748 13.256C-0.0434614 13.7342 -0.0434614 14.5108 0.434748 14.989C0.912958 15.4672 1.68957 15.4672 2.16778 14.989L8.28886 8.86792L8.28504 8.86409Z"
                                            fill="#3B3B3B" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Testimonial Carousel Initialization Script -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Initialize the testimonial carousel
                                    var testimonialCarousel = new bootstrap.Carousel(document.getElementById('testimonialCarousel'), {
                                        interval: 3000,
                                        wrap: true,
                                        keyboard: true,
                                        pause: 'hover',
                                        touch: true
                                    });

                                    // Ensure controls work properly
                                    document.querySelectorAll('[data-bs-target="#testimonialCarousel"]').forEach(function(control) {
                                        control.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            if (this.getAttribute('data-bs-slide') === 'prev') {
                                                testimonialCarousel.prev();
                                            } else {
                                                testimonialCarousel.next();
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_PARTNERS && $section->enabled == 1)
                <!-- trusted partners section -->
                <section class="py-5 partners-section">
                    <div class="container-homepage">
                        <h2 class="mb-5 text-md-start text-center section-title">
                            {!! $section->title !!}
                        </h2>
                    </div>


                    <div class="slider-container">
                        <div class="slide-left logo-row">
                            <!-- Duplicate content for seamless loop -->
                            @if(!empty($section->banner_image) && is_array($section->banner_image))
                                @foreach($section->banner_image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                                @foreach($section->banner_image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="slide-right logo-row">
                            <!-- Duplicate content for seamless loop -->
                            @if(!empty($section->image) && is_array($section->image))
                                @foreach($section->image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                                @foreach($section->image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </section>
            @endif
        @endforeach
    @endif

    {{-- Include modal file --}}
    @include('front.pages.partials.quiz-modal')

    <!-- contact section -->
    <section class="py-5 contact-section" id="contact-section">
        <div class="container-homepage">
            <div class="justify-content-center row">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row align-items-center contact-card">
                        <div class="p-4 p-md-5 contact-form-wrapper">
                            <h2 class="mb-3 contact-title">GET IN TOUCH</h2>
                            <p class="mb-4 contact-description">Not sure where to start? Reach out - we're here to help and
                                will get back to you as soon as we can.</p>
                            <form id="query-form">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="query-name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="query-email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">Mobile number (optional)</label>
                                    <input type="tel" class="form-control" id="query-phone">
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label">Message <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="query-message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn-signup" id="submit-query">Send message</button>
                            </form>
                        </div>
                        <div class="d-md-flex align-items-center justify-content-center phone-mockup-wrapper d-none">
                            <img src="{{ frontAssets('images/mockup.webp') }}" alt="Mobile App Interface" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleLink = document.getElementById('toggle-coupon-link');
            const couponDetails = document.getElementById('coupon-details');
            const promoInput = document.getElementById('promo-code');
            const promoMessage = document.getElementById('promo-message');

            if (toggleLink) {
                toggleLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    const isHidden = couponDetails.classList.contains('d-none');

                    couponDetails.classList.toggle('d-none');

                    toggleLink.textContent = isHidden ? 'Remove a Coupon Code' : 'Add a Coupon Code';

                    if (!isHidden) {
                        promoInput.value = '';
                        if (promoMessage) {
                            promoMessage.textContent = '';
                        }
                        document.getElementById('payment-details').style.removeProperty('display');
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

        // iOS detection and fixes
        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) ||
                   (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        }

        // iOS-specific select improvements
        function enhanceSelectForIOS() {
            if (isIOS()) {
                const selects = document.querySelectorAll('.sport-nutrition-promo__form select[data-custom="true"]');
                selects.forEach(select => {
                    // Ensure proper touch handling
                    select.addEventListener('touchstart', function(e) {
                        e.stopPropagation();
                    }, { passive: true });

                    // Prevent zoom on focus
                    select.addEventListener('focus', function() {
                        this.style.fontSize = '16px';
                    });

                    // Handle blur to reset if needed
                    select.addEventListener('blur', function() {
                        // Small delay to ensure proper handling
                        setTimeout(() => {
                            if (this.value === '') {
                                this.style.fontSize = '16px';
                            }
                        }, 100);
                    });

                    // Improve change event handling for iOS
                    select.addEventListener('change', function() {
                        // Force reflow to ensure proper rendering
                        this.style.transform = 'translateZ(0)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 10);
                    });
                });
            }
        }

        $(document).ready(function () {
            // Initialize iOS enhancements
            enhanceSelectForIOS();

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
                            let options = '<option value="">Select Sport Game</option>';
                            if (Array.isArray(response)) {
                                response.forEach(function (game) {
                                    options += `<option value="${game.name}">${game.name}</option>`;
                                });
                            }
                            $('#sport_game').html(options);

                            // Re-enhance the new select for iOS
                            if (isIOS()) {
                                enhanceSelectForIOS();
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

            $("#sport-form").submit(function (e) {
                e.preventDefault();

                // iOS-specific form handling
                if (isIOS()) {
                    // Force blur on all inputs to ensure proper value capture
                    $(this).find('select').blur();
                    // Small delay to ensure iOS has processed the blur
                    setTimeout(() => {
                        processFormSubmission();
                    }, 100);
                } else {
                    processFormSubmission();
                }
            });

            function processFormSubmission() {
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
            };

            // Handle user details submission
            $("#userDetailsForm").submit(function (e) {
                // please add loader here
                $('#submitUserDetails').prop('disabled', true);
                $('#submitUserDetails').html('<i class="fa fa-spinner fa-spin"></i> Please wait...');

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
                        $('#submitUserDetails').prop('disabled', false);
                        $('#submitUserDetails').html('Submit');
                        alert("Thank you! We will send you relevant nutrition information.");
                        $("#confirmationModal").modal("hide");
                        $("#sport-form")[0].reset();
                    },
                    error: function () {
                        $('#submitUserDetails').prop('disabled', false);
                        $('#submitUserDetails').html('Submit');
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
                    success: function (response) {
                        console.log('Quiz start response:', response); // Debug log
                        if (response.success) {
                            currentQuizId = response.quiz_id;
                            console.log('Quiz started with ID:', currentQuizId); // Debug log
                            $('#TakeTestModel').modal('show');
                        } else {
                            alert('Error starting quiz: ' + response.message);
                        }
                    },
                    error: function (xhr) {
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
                if (cardBody) {
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
                                const foodNameElem = row.querySelector("td:first-child");
                                const selectedAnswer = row.querySelector("input[type='radio']:checked");

                                if (foodNameElem && selectedAnswer) {
                                    const foodName = foodNameElem.textContent.trim();
                                    const answerValue = parseFloat(selectedAnswer.value) || 0;
                                    const optionType = selectedAnswer.dataset.option || null;
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

                            const label = labelElem.textContent.trim();
                            const answerValue = parseFloat(radio.value) || 0;
                            const optionType = radio.dataset.option || null;
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

                            const labelText = labelElem.textContent.trim();
                            const checkboxVal = parseFloat(checkbox.value) || 0;
                            const optionType = checkbox.dataset.option || null;
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
                    success: function (response) {
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
                                const foodNameElem = row.querySelector("td:first-child");
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
                console.log('step - ', stepIndex);
                const modalTitle = document.getElementById('testModalLabel');
                if (modalTitle) {
                    if (stepIndex < 5) {
                        modalTitle.textContent = "Nutrition Knowledge Questions";
                    } else if (stepIndex == 5) {
                        modalTitle.textContent = "Sports Nutrition Principles";
                    } else if (stepIndex == 6) {
                        modalTitle.textContent = "Sports Nutrition Principles";
                    } else if (stepIndex == 7) {
                        modalTitle.textContent = "Supplement Knowledge";
                    } else if (stepIndex == 8) {
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
                            step: currentStep + 1,
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
                                success: function (response) {
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
                                success: function (response) {
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

            $('#TakeTestModel').on('hidden.bs.modal', function () {
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
                            if (data.freeTest) {
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
                                    data: JSON.stringify({ userId, name, email, testData, totalAnswerCount }),
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
                        success: function (response) {
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

        $(document).ready(function () {
            $("#submit-query").click(function (e) {
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
                    success: function (response) {
                        if (response.status === "success") {
                            alert("Your query has been submitted successfully!");
                            $("#query-form")[0].reset(); // Clear the form
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
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
                $(document).ready(function () {
                    // var stripe = Stripe('pk_test_51QI09cHWqn47bqTGYhGZIsiPSerWujjQgoHf4g0JwygrNt1OMC3RtEnMIjiEWbc8hiaN4umn4TD5zB8sBQEqcjzY0071a4RbUv');
                    var stripe = Stripe('pk_live_51Pfz1YLSisFoEruHvHpdQQZLynQoR3x6BDuBgpb84zTK3EnTlROWMjxVpZhrp1rLmaqCJbusOUNHUoTKBLK7CXru00CkS5tVbt');
                    var elements = stripe.elements();
                    var style = {
                        base: {
                            color: '#32325d',
                            border: '1px solid #32325d',
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
                    card.on('change', function (event) {
                        var displayError = document.getElementById('card-errors');
                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    $('#purchaseModal').on('hidden.bs.modal', function () {
                        $('#payment-form')[0].reset();
                        $('#card-errors').text('');
                        // Reset coupon UI
                        const toggleLink = document.getElementById('toggle-coupon-link');
                        const couponDetails = document.getElementById('coupon-details');
                        const promoInput = document.getElementById('promo-code');
                        const promoMessage = document.getElementById('promo-message');

                        if (couponDetails && !couponDetails.classList.contains('d-none')) {
                            couponDetails.classList.add('d-none');
                        }

                        if (toggleLink) {
                            toggleLink.textContent = 'Add a Coupon Code';
                        }

                        if (promoInput) {
                            promoInput.value = '';
                        }

                        if (promoMessage) {
                            promoMessage.textContent = '';
                        }
                    });

                    // Event listener for the 'Purchase Now' button
                    $('body').on('click', '.purchase-now-btn', function () {

                        var planId = $(this).data('plan-id');
                        var price = $(this).data('plan-price');

                        $('#purchaseModalLabel').text('Purchase ' + $(this).closest('.spot-plan-box').find('h5').text() + ' ($' + price + ')');
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
                                $('#name').val('{{ Auth::user()->first_name }} {{Auth::user()->last_name }}');
                                $('#emailId').val('{{ Auth::user()->email }}');
                                $('#phone').val('{{ Auth::user()->phone ?? "" }}');
                                $('#signed-in-email').text('{{ Auth::user()->email }}');
                            @endif

                                }

                        $('#purchaseModal').modal('show');

                        // Handle the form submission
                        $('#payment-form').off('submit').on('submit', function (event) {
                            event.preventDefault();

                            $('#submit').prop('disabled', true);

                            let discountCode = $('#promo-code').val();
                            console.log(discountCode);
                            let discount = $('#discount').val();
                            let email = $('#emailId').val();
                            let name = $('#name').val();
                            let phone = $('#phone').val();
                            console.log('Email:', email);
                            console.log('Name:', name);
                            console.log('Phone:', phone);

                            if (discount == 100.00) {
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
                                            $('#purchaseModal').modal('hide');
                                            $('#submit').prop('disabled', false);
                                            var user_id = response.data.user_id;
                                            var payment_id = response.data.payment_id;

                                            if (response.data.submit_questionnaire) {
                                                if (response.redirect_url) {
                                                    var redirectUrlWithUserId = response.redirect_url + '?id=' + payment_id + '&user_id=' + user_id;
                                                    setTimeout(function () {
                                                        window.location.href = redirectUrlWithUserId;
                                                    }, 3000);
                                                } else {
                                                    alert('Error: Redirect url not found.');
                                                }
                                            } else {
                                                $('#thankYouModal').modal('show');
                                            }
                                        } else {
                                            if (response.message == 'You have already purchased this plan. Please login to your account to manage your plans.') {
                                                alert('You have already purchased this plan. Please login to your account to manage your plans.');
                                                $('#purchaseModal').modal('hide');

                                                $('html, body').animate({
                                                    scrollTop: $('#nutrition-login-section').offset().top
                                                }, 500);
                                            } else {
                                                alert('Payment failed: ' + response.message);
                                            }
                                            $('#submit').prop('disabled', false);
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        $('#submit').prop('disabled', false);

                                        let message = '';

                                        if (xhr.status === 422) {
                                            const errors = xhr.responseJSON.errors;
                                            message += '<ul class="mb-1">';
                                            $.each(errors, function (key, value) {
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
                            } else {
                                stripe.createPaymentMethod({
                                    type: 'card',
                                    card: card,
                                    billing_details: {
                                        name: $('#name').val(),
                                        email: $('#emailId').val(),
                                        phone: $('#phone').val(),
                                    },
                                }).then(function (result) {
                                    if (result.error) {
                                        cardErrors.textContent = result.error.message;
                                        $('#submit').prop('disabled', false);
                                    } else {
                                        $.ajax({
                                            url: '{{ route("process.payment") }}',
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
                                            success: function (response) {
                                                if (response.success) {

                                                    $('#purchaseModal').modal('hide');
                                                    $('#submit').prop('disabled', false);
                                                    if (response.data.submit_questionnaire) {

                                                        var user_id = response.data.user_id;
                                                        var payment_id = response.data.payment_id;

                                                        if (response.redirect_url) {

                                                            var redirectUrlWithUserId = response.redirect_url + '?id=' + payment_id + '&user_id=' + user_id;
                                                            setTimeout(function () {
                                                                window.location.href = redirectUrlWithUserId;
                                                            }, 3000);
                                                        }
                                                    } else {
                                                        $('#thankYouModal').modal('show');
                                                    }
                                                } else {

                                                    if (response.message == 'You have already purchased this plan. Please login to your account to manage your plans.') {
                                                        alert('You have already purchased this plan. Please login to your account to manage your plans.');
                                                        $('#purchaseModal').modal('hide');

                                                        $('html, body').animate({
                                                            scrollTop: $('#nutrition-login-section').offset().top
                                                        }, 500);
                                                    } else {
                                                        alert('Payment failed: ' + response.message);
                                                    }
                                                    $('#submit').prop('disabled', false);

                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                $('#submit').prop('disabled', false);

                                                let message = '';

                                                if (xhr.status === 422) {
                                                    const errors = xhr.responseJSON.errors;
                                                    message += '<ul class="mb-1">';
                                                    $.each(errors, function (key, value) {
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
                                    let msg = '';

                                    if (data.valid) {
                                        if (data.type == 'percentage') {
                                            msg = `Coupon code applied! ${data.discount}% discount.`;
                                            if (data.discount === "100.00" || data.discount == 100.00) {
                                                $('#discount').val(data.discount);
                                                $('#payment-details').hide();
                                            } else {
                                                $('#discount').val(data.discount);
                                                $('#payment-details').show();
                                            }
                                        } else {
                                            msg = `Coupon code applied! $${data.discount} discount.`;
                                        }
                                        $('#discount').val(data.discount);
                                        document.getElementById('promo-message').textContent = msg;
                                        document.getElementById('promo-message').classList.add('text-success');
                                        document.getElementById('promo-message').classList.remove('text-danger');
                                    } else {
                                        document.getElementById('promo-message').textContent = data.message;
                                        document.getElementById('promo-message').classList.add('text-danger');
                                        document.getElementById('promo-message').classList.remove('text-success');
                                    }
                                })
                                .catch(error => {
                                    alert('Error: ', error);
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

        $('#login-form').submit(function (event) {
            event.preventDefault();

            $('#login-submit').prop('disabled', true);

            // Get the form data
            var email = $('#login-email').val();
            var password = $('#login-password').val();

            $.ajax({
                url: '{{ route("front.login") }}',
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        if (response.message == 'Plan not purchased.') {
                            alert('Please complete your profile.');
                        }
                        window.location.href = response.redirect_url;
                    }
                }, error: function (xhr) {
                    var response = xhr.responseJSON;

                    if (response.message) {
                        if (response.message == 'CSRF token mismatch.') {
                            $('#login-error').text('Your session has expired. Please reload the page and login again.');
                        } else {
                            $('#login-error').text(response.message);
                        }
                    } else {
                        $('#login-error').text('Something went wrong. Please try again.');
                    }

                    $('#login-submit').prop('disabled', false);
                }
            });
        });

        $('#front-page-login-form').submit(function (event) {
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
                success: function (response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                }, error: function (xhr) {
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
        $('#show-login-modal').click(function (e) {
            e.preventDefault();
            $('#purchaseModal').modal('hide');
            $('#loginModal').modal('show');
        });

        // Show Sign-Up Modal when clicking "Sign Up" link in the Sign-In Modal
        $('#show-signup-modal').click(function (e) {
            e.preventDefault();
            $('#loginModal').modal('hide');
            $('#registerModal').modal('hide');
            $('#purchaseModal').modal('show');
        });

        $(document).ready(function () {
            $('body').on('click', '#forgot-password', function (e) {
                e.preventDefault();

                $('#forgotPasswordModal').modal('show');
                $('#forgotPasswordForm').reset();
            });
        });

        // Update the back button click handler
        $('.back-btn').click(function () {
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
        $('.next-btn').click(function () {
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
        $('.show-step-btn').click(function () {
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
                    success: function (response) {
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
                    error: function (xhr) {
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

    <script>
        // Initialize carousels with smooth transitions
        document.addEventListener("DOMContentLoaded", function () {
            const desktopCarousel = document.getElementById("heroCarouselDesktop");
            const mobileCarousel = document.getElementById("heroCarouselMobile");

            // Add fade effect class to both carousels
            desktopCarousel.classList.add("carousel-fade");
            mobileCarousel.classList.add("carousel-fade");

            // Function to setup carousel events
            function setupCarousel(carousel) {
                // Ensure continuous looping
                carousel.addEventListener("slide.bs.carousel", function (e) {
                    // Smooth transition effect
                    const activeItem = carousel.querySelector(".carousel-item.active");
                    const nextItem = e.relatedTarget;

                    // Add smooth transition
                    nextItem.style.transition = "opacity 0.8s ease-in-out";
                });

                // Auto-restart carousel when it reaches the end
                carousel.addEventListener("slid.bs.carousel", function (e) {
                    const items = carousel.querySelectorAll(".carousel-item");
                    const activeIndex = Array.from(items).indexOf(e.relatedTarget);

                    // Ensure continuous loop
                    if (activeIndex === items.length - 1) {
                        setTimeout(() => {
                            bootstrap.Carousel.getInstance(carousel).to(0);
                        }, 3000);
                    }
                });
            }

            // Setup both carousels
            setupCarousel(desktopCarousel);
            setupCarousel(mobileCarousel);
        });


        class FoodCarousel {
            constructor() {
                this.track = document.getElementById("foodCarouselTrack");
                this.cards = Array.from(this.track.children);
                this.currentIndex = 0;
                this.isMobile = window.innerWidth <= 768;

                // Set card width based on screen size
                this.cardWidth = this.isMobile ? 290 : 400; // 280px card + 10px margin on mobile, 380px + 20px on desktop

                this.init();
            }

            init() {
                // Calculate how many cards fit in viewport
                this.calculateVisibleCards();

                // Create infinite loop with clones
                this.setupInfiniteLoop();

                // Set initial position
                this.updatePosition(true);

                // Add event listeners
                document
                    .getElementById("prevBtn")
                    .addEventListener("click", () => this.prev());
                document
                    .getElementById("nextBtn")
                    .addEventListener("click", () => this.next());

                // Auto-slide with different timing for mobile
                this.startAutoSlide();

                // Keyboard navigation
                document.addEventListener("keydown", (e) => {
                    if (e.key === "ArrowLeft") this.prev();
                    if (e.key === "ArrowRight") this.next();
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    this.handleResize();
                });


            }

            handleResize() {
                const wasMobile = this.isMobile;
                this.isMobile = window.innerWidth <= 768;
                this.cardWidth = this.isMobile ? 290 : 400;

                // Only recalculate if mobile state changed
                if (wasMobile !== this.isMobile) {
                    this.calculateVisibleCards();
                    this.updatePosition(true);
                }
            }

            calculateVisibleCards() {
                const viewportWidth = window.innerWidth;
                const cardWidth = this.cardWidth;

                // Calculate how many full cards fit in viewport
                this.visibleCards = Math.floor(viewportWidth / cardWidth);

                // Ensure at least 2 cards are visible on mobile, 3 on desktop for better infinite loop
                const minCards = this.isMobile ? 2 : 3;
                if (this.visibleCards < minCards) this.visibleCards = minCards;


            }

            setupInfiniteLoop() {
                // Clone cards for infinite loop
                const originalCards = [...this.cards];

                // Add clones at the end
                originalCards.forEach((card) => {
                    const clone = card.cloneNode(true);
                    this.track.appendChild(clone);
                });

                // Add clones at the beginning
                originalCards.forEach((card) => {
                    const clone = card.cloneNode(true);
                    this.track.insertBefore(clone, this.track.firstChild);
                });

                // Update cards array to include clones
                this.cards = Array.from(this.track.children);
                this.originalCardCount = originalCards.length;


            }

            updatePosition(noAnimation = false) {
                const viewportWidth = window.innerWidth;
                const cardWidth = this.cardWidth;

                // Calculate center position
                const totalCardsWidth = this.visibleCards * cardWidth;
                const centerOffset = (viewportWidth - totalCardsWidth) / 2;

                // Calculate position with clones offset
                const translateX =
                    centerOffset -
                    this.currentIndex * cardWidth -
                    this.originalCardCount * cardWidth;

                if (noAnimation) {
                    this.track.style.transition = "none";
                } else {
                    // Use faster transition for better responsiveness
                    const transitionDuration = this.isMobile ? "0.3s" : "0.25s";
                    this.track.style.transition = `transform ${transitionDuration} ease-in-out`;

                }

                this.track.style.transform = `translateX(${translateX}px)`;

            }

            next() {

                this.currentIndex++;

                // Check if we need to loop
                if (this.currentIndex >= this.originalCardCount) {
                    // Reset to beginning without animation
                    setTimeout(() => {
                        this.track.style.transition = "none";
                        this.currentIndex = 0;
                        this.updatePosition(true);
                    }, this.isMobile ? 300 : 250);
                }

                this.updatePosition();
            }

            prev() {

                this.currentIndex--;

                // Check if we need to loop
                if (this.currentIndex < 0) {
                    // Reset to end without animation
                    setTimeout(() => {
                        this.track.style.transition = "none";
                        this.currentIndex = this.originalCardCount - 1;
                        this.updatePosition(true);
                    }, this.isMobile ? 300 : 250);
                }

                this.updatePosition();
            }

            startAutoSlide() {
                // Use different timing for mobile vs desktop
                const interval = this.isMobile ? 1500 : 1200; // 1.5 seconds on mobile, 1.2 on desktop

                this.autoSlideInterval = setInterval(() => {
                    this.next();
                }, interval);
            }

            stopAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                }
            }
        }

        // Initialize when DOM is ready
        document.addEventListener("DOMContentLoaded", () => {
            // Force refresh the carousel if it already exists
            if (window.foodCarousel) {
                window.foodCarousel.stopAutoSlide();
            }

            window.foodCarousel = new FoodCarousel();
        });

        document.querySelectorAll(".meal-tab").forEach((tab) => {
            tab.addEventListener("click", function () {
                // Remove active class from all tabs
                document
                    .querySelectorAll(".meal-tab")
                    .forEach((t) => t.classList.remove("active"));
                // Add active class to clicked tab
                this.classList.add("active");

                // You can add logic here to change the food items based on selected tab
                // For demo purposes, we'll just change the tab appearance
            });
        });

        // Add smooth scrolling to food scroll container
        const foodScroll = document.querySelector(".food-scroll");
        if (foodScroll) {
            foodScroll.style.scrollBehavior = "smooth";
        }

        $('#show-new-signup-modal').click(function (e) {
            e.preventDefault(); // remove alert for now
            if ($('#signupModalathlete').length) {
                $('#signupModalathlete').modal('hide');
            }
            $('#signupModalathlete').modal('show');
        });
    </script>
@endsection

{{-- push modal.js file --}}
@push('scripts')
    <script>
        window.quizConfig = {
            startQuizUrl: "{{ route('front.quiz.start') }}",
            saveStepUrl: "{{ route('front.quiz.save-step') }}",
            completeUrl: "{{ route('front.quiz.complete') }}",
            abandonUrl: "{{ route('front.quiz.abandon') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    <script src="{!! frontAssets('js/quiz-modal.js') !!}"></script>
@endpush