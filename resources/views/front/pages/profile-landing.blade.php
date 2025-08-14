@extends(frontView('layouts.app'))

@section('title', 'Best Sports Nutritionist & Dietitians Australia | Kerry O’Bryan')
@section('meta_description',
    'Performance Health Support offers expert care from top sports nutritionists, strength
    coaches, and sports dietitians in Australia to boost health and performance.')

@section('content')
    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="welcome-card hover-card">
                    <div class="welcome-message" style="position: relative;">
                        <h2>Welcome back legend! How's your week going?</h2>
                        <div class="welcome-row">
                            <a href="#" class="start-chat" id="start-chat-link">Start chat</a>
                            <span class="assistant-name">Kerry O'Bryan Virtual</span>
                        </div>
                        <img src="{{ frontAssets('images/profile.svg') }}" alt="Profile" class="profile-avatar-overlap" />
                        <div class="welcome-arrow"></div>
                    </div>

                </div>

            </section>

            @if (!isset($userPlan?->plan) && $userPlan?->free_user && $userPlan?->free_user_plan)
                <section class="challenges">
                    <div class="section-header">
                        <h2>My Nutrition Plan</h2>
                        <a href="{{ route('front.my-plans') }}" class="see-all">Purchase Plan</a>
                    </div>
                    <div class="slider-container">
                        <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth; position:relative;">
                            <div class="purchase-plan-overlay">
                                <a href="{{ route('front.my-plans') }}" class="purchase-plan-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21"
                                        viewBox="0 0 22 21" fill="none">
                                        <path
                                            d="M21.573 8.84212L15.7828 6.88626L13.6206 0.419575C13.5798 0.29811 13.4973 0.191804 13.3854 0.116281C13.2734 0.040758 13.1378 9.0512e-07 12.9986 9.0512e-07C12.8594 9.0512e-07 12.7238 0.040758 12.6119 0.116281C12.4999 0.191804 12.4174 0.29811 12.3766 0.419575L10.2151 6.88626L4.42424 8.84212C4.29972 8.8843 4.19232 8.96025 4.11649 9.05975C4.04065 9.15926 4 9.27757 4 9.39878C4 9.51998 4.04065 9.63829 4.11649 9.7378C4.19232 9.83731 4.29972 9.91325 4.42424 9.95543L10.2125 11.9107L12.3746 18.5745C12.4144 18.6974 12.4967 18.8052 12.6091 18.8819C12.7216 18.9586 12.8582 19 12.9986 19C13.139 19 13.2756 18.9586 13.3881 18.8819C13.5005 18.8052 13.5828 18.6974 13.6226 18.5745L15.7854 11.9107L21.5736 9.95543C21.6987 9.9137 21.8067 9.8379 21.8829 9.73829C21.9592 9.63868 22.0001 9.52008 22 9.39857C21.9999 9.27705 21.9589 9.15849 21.8825 9.05896C21.8062 8.95943 21.6981 8.88373 21.573 8.84212Z"
                                            fill="#CCACFF" />
                                        <path
                                            d="M8.69123 16.2497L7.23038 15.7091L6.66196 13.6249C6.6256 13.4911 6.54623 13.373 6.43608 13.2888C6.32593 13.2045 6.19114 13.1589 6.05249 13.1589C5.91384 13.1589 5.77905 13.2045 5.6689 13.2888C5.55875 13.373 5.47938 13.4911 5.44302 13.6249L4.87459 15.7091L3.41375 16.2497C3.29288 16.2946 3.18864 16.3754 3.11502 16.4812C3.04141 16.5871 3.00195 16.7129 3.00195 16.8418C3.00195 16.9707 3.04141 17.0966 3.11502 17.2024C3.18864 17.3083 3.29288 17.3891 3.41375 17.4339L4.86701 17.9727L5.43986 20.2602C5.47408 20.3968 5.55296 20.518 5.66395 20.6046C5.77495 20.6912 5.9117 20.7383 6.05249 20.7383C6.19328 20.7383 6.33003 20.6912 6.44102 20.6046C6.55202 20.518 6.63089 20.3968 6.66512 20.2602L7.23796 17.9727L8.69123 17.4339C8.8121 17.3891 8.91634 17.3083 8.98995 17.2024C9.06357 17.0966 9.10302 16.9707 9.10302 16.8418C9.10302 16.7129 9.06357 16.5871 8.98995 16.4812C8.91634 16.3754 8.8121 16.2946 8.69123 16.2497Z"
                                            fill="#F5B266" />
                                        <path
                                            d="M5.95184 2.56593L4.45816 2.0133L3.90553 0.519616C3.86078 0.398536 3.78001 0.294072 3.67409 0.220294C3.56817 0.146515 3.44219 0.106963 3.31311 0.106963C3.18402 0.106963 3.05804 0.146515 2.95212 0.220294C2.8462 0.294072 2.76543 0.398536 2.72069 0.519616L2.16742 2.0133L0.67437 2.56593C0.553291 2.61068 0.448828 2.69145 0.37505 2.79737C0.301271 2.90329 0.261719 3.02927 0.261719 3.15835C0.261719 3.28744 0.301271 3.41342 0.37505 3.51934C0.448828 3.62526 0.553291 3.70603 0.67437 3.75077L2.16742 4.3034L2.72069 5.79709C2.76516 5.91844 2.84582 6.02321 2.95178 6.09723C3.05773 6.17125 3.18386 6.21094 3.31311 6.21094C3.44235 6.21094 3.56848 6.17125 3.67444 6.09723C3.78039 6.02321 3.86106 5.91844 3.90553 5.79709L4.45816 4.3034L5.95184 3.75077C6.07292 3.70603 6.17739 3.62526 6.25116 3.51934C6.32494 3.41342 6.36449 3.28744 6.36449 3.15835C6.36449 3.02927 6.32494 2.90329 6.25116 2.79737C6.17739 2.69145 6.07292 2.61068 5.95184 2.56593Z"
                                            fill="#A2C5FA" />
                                    </svg>
                                    Purchase a personalised plan
                                </a>
                            </div>
                            <div class="fade-full"></div>
                            <div class="challenge-card clickable hover-card">
                                <img src="{{ frontAssets('images/personalised-1.webp') }}" alt="personalised-1" />
                                <h3>Creamy oats topped with banana, berries, and chia for lasting energy.</h3>
                            </div>
                            <div class="challenge-card clickable hover-card">
                                <img src="{{ frontAssets('images/personalised-2.webp') }}" alt="personalised-1" />
                                <h3>Char-grilled chicken with crisp salad in a soft pita pocket.</h3>
                            </div>
                            <div class="challenge-card clickable hover-card">
                                <img src="{{ frontAssets('images/personalised-3.webp') }}" alt="personalised-1" />
                                <h3>Grilled chicken with rice, beans, corn, avocado, and fresh veg.</h3>
                            </div>
                            <div class="challenge-card clickable hover-card">
                                <img src="{{ frontAssets('images/personalised-4.webp') }}" alt="personalised-1" />
                                <h3>Glazed tofu with broccoli, carrots, and capsicum over brown rice.</h3>
                            </div>
                        </div>
                    </div>
                </section>
            @elseif($payment && $payment->plan_id && $userPlan->is_mail_sent == 0)
                <section class="challenges">
                    <div class="section-header">
                        <h2>My Nutrition Plan</h2>
                    </div>
                    <div class="slider-container">
                        <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth; position:relative;">
                            <div class="plan-placeholder-container">
                                <div class="plan-placeholder-content">
                                    <div class="plan-placeholder-icon">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="60"
                                            height="60"
                                            viewBox="0 0 22 21"
                                            fill="none"
                                            class="pulse-animation"
                                        >
                                        <path
                                            d="M21.573 8.84212L15.7828 6.88626L13.6206 0.419575C13.5798 0.29811 13.4973 0.191804 13.3854 0.116281C13.2734 0.040758 13.1378 9.0512e-07 12.9986 9.0512e-07C12.8594 9.0512e-07 12.7238 0.040758 12.6119 0.116281C12.4999 0.191804 12.4174 0.29811 12.3766 0.419575L10.2151 6.88626L4.42424 8.84212C4.29972 8.8843 4.19232 8.96025 4.11649 9.05975C4.04065 9.15926 4 9.27757 4 9.39878C4 9.51998 4.04065 9.63829 4.11649 9.7378C4.19232 9.83731 4.29972 9.91325 4.42424 9.95543L10.2125 11.9107L12.3746 18.5745C12.4144 18.6974 12.4967 18.8052 12.6091 18.8819C12.7216 18.9586 12.8582 19 12.9986 19C13.139 19 13.2756 18.9586 13.3881 18.8819C13.5005 18.8052 13.5828 18.6974 13.6226 18.5745L15.7854 11.9107L21.5736 9.95543C21.6987 9.9137 21.8067 9.8379 21.8829 9.73829C21.9592 9.63868 22.0001 9.52008 22 9.39857C21.9999 9.27705 21.9589 9.15849 21.8825 9.05896C21.8062 8.95943 21.6981 8.88373 21.573 8.84212Z"
                                            fill="#CCACFF"
                                            />
                                        <path
                                            d="M8.69123 16.2497L7.23038 15.7091L6.66196 13.6249C6.6256 13.4911 6.54623 13.373 6.43608 13.2888C6.32593 13.2045 6.19114 13.1589 6.05249 13.1589C5.91384 13.1589 5.77905 13.2045 5.6689 13.2888C5.55875 13.373 5.47938 13.4911 5.44302 13.6249L4.87459 15.7091L3.41375 16.2497C3.29288 16.2946 3.18864 16.3754 3.11502 16.4812C3.04141 16.5871 3.00195 16.7129 3.00195 16.8418C3.00195 16.9707 3.04141 17.0966 3.11502 17.2024C3.18864 17.3083 3.29288 17.3891 3.41375 17.4339L4.86701 17.9727L5.43986 20.2602C5.47408 20.3968 5.55296 20.518 5.66395 20.6046C5.77495 20.6912 5.9117 20.7383 6.05249 20.7383C6.19328 20.7383 6.33003 20.6912 6.44102 20.6046C6.55202 20.518 6.63089 20.3968 6.66512 20.2602L7.23796 17.9727L8.69123 17.4339C8.8121 17.3891 8.91634 17.3083 8.98995 17.2024C9.06357 17.0966 9.10302 16.9707 9.10302 16.8418C9.10302 16.7129 9.06357 16.5871 8.98995 16.4812C8.91634 16.3754 8.8121 16.2946 8.69123 16.2497Z"
                                            fill="#F5B266"
                                            />
                                        <path
                                            d="M5.95184 2.56593L4.45816 2.0133L3.90553 0.519616C3.86078 0.398536 3.78001 0.294072 3.67409 0.220294C3.56817 0.146515 3.44219 0.106963 3.31311 0.106963C3.18402 0.106963 3.05804 0.146515 2.95212 0.220294C2.8462 0.294072 2.76543 0.398536 2.72069 0.519616L2.16742 2.0133L0.67437 2.56593C0.553291 2.61068 0.448828 2.69145 0.37505 2.79737C0.301271 2.90329 0.261719 3.02927 0.261719 3.15835C0.261719 3.28744 0.301271 3.41342 0.37505 3.51934C0.448828 3.62526 0.553291 3.70603 0.67437 3.75077L2.16742 4.3034L2.72069 5.79709C2.76516 5.91844 2.84582 6.02321 2.95178 6.09723C3.05773 6.17125 3.18386 6.21094 3.31311 6.21094C3.44235 6.21094 3.56848 6.17125 3.67444 6.09723C3.78039 6.02321 3.86106 5.91844 3.90553 5.79709L4.45816 4.3034L5.95184 3.75077C6.07292 3.70603 6.17739 3.62526 6.25116 3.51934C6.32494 3.41342 6.36449 3.28744 6.36449 3.15835C6.36449 3.02927 6.32494 2.90329 6.25116 2.79737C6.17739 2.69145 6.07292 2.61068 5.95184 2.56593Z"
                                            fill="#A2C5FA"
                                            />
                                        </svg>
                                        <div class="clock-icon">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="plan-placeholder-title">
                                        Your personalized plan is being prepared
                                    </h3>
                                    <p class="plan-placeholder-message">
                                        Our nutrition experts are crafting your custom meal plan. It
                                        will be ready in <span class="highlight">24-48 hours</span>.
                                    </p>
                                    <div class="plan-placeholder-progress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @elseif ($userPlan && $userPlan->is_mail_sent == 1 && $userPlan->mail_sent_at != null)
                <section class="training-plan">
                    @if (isset($userPlan->plan))
                        <div class="section-header">
                            <h2>{{ isset($userPlan->plan) ? $userPlan->plan->name : '' }}</h2>
                            <a href="{{ route('front.plans.details', ['id' => $userPlan->plan->id, 'user_id' => $userPlan->user->id]) }}"
                                class="see-all">See Plan</a>
                        </div>
                    @else
                        <div class="section-header">
                            <h2></h2>
                            <a href="" class="see-all">See Plan</a>
                        </div>
                    @endif
                    {{-- Tabs --}}
                    <div class="tabs">
                        @php $firstTab = true; @endphp
                        @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userCategory)
                            @php
                                $category = $userCategory->category;
                                $hasValidMeal = $userCategory
                                    ->userSubCategories()
                                    ->where('user_plan_id', $userPlan->id)
                                    ->whereHas('userMeals', function ($q) use ($userPlan, $userCategory) {
                                        $q->where('user_plan_id', $userPlan->id)->where(
                                            'user_category_id',
                                            $userCategory->id,
                                        );
                                    })
                                    ->exists();
                            @endphp

                            @if ($hasValidMeal && $category)
                                <button class="tab {{ $firstTab ? 'active' : '' }}" data-category-id="{{ $category->id }}"
                                    data-plan-id="{{ $userPlan->id }}">
                                    {{ $category->title }}
                                </button>
                                @php $firstTab = false; @endphp
                            @endif
                        @endforeach
                    </div>

                    <div class="tab-content challenges">
                        <div class="slider-wrapper" style="position:relative;">
                            <button class="left-arrow slider-arrow">
                                <svg width="18" height="24" viewBox="0 0 18 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="14,4 4,16 14,28" stroke="#080808" stroke-width="3" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;"
                                id="meal-cards-wrapper">
                                <p>Loading meals...</p>
                            </div>
                            <button class="right-arrow slider-arrow">
                                <svg width="18" height="24" viewBox="0 0 18 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="4,4 14,16 4,28" stroke="#080808" stroke-width="3" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </section>
            @endif

            <!-- Challenges -->
            <section class="challenges">
                <div class="section-header">
                    <h2>Challenges</h2>
                    <!-- <label class="see-all" style="cursor:pointer;" onclick="showLearnMoreTooltip(this, 'Coming Soon')">See all</label> -->
                    <a href="#" class="see-all coming-soon-popup">See all</a>
                </div>
                <div class="slider-container">

                    <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;position:relative;">
                        @if ($userPlan && $userPlan->free_user)
                            <div class="purchase-plan-overlay">
                                <button class="purchase-plan-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21"
                                        viewBox="0 0 22 21" fill="none">
                                        <path
                                            d="M21.573 8.84212L15.7828 6.88626L13.6206 0.419575C13.5798 0.29811 13.4973 0.191804 13.3854 0.116281C13.2734 0.040758 13.1378 9.0512e-07 12.9986 9.0512e-07C12.8594 9.0512e-07 12.7238 0.040758 12.6119 0.116281C12.4999 0.191804 12.4174 0.29811 12.3766 0.419575L10.2151 6.88626L4.42424 8.84212C4.29972 8.8843 4.19232 8.96025 4.11649 9.05975C4.04065 9.15926 4 9.27757 4 9.39878C4 9.51998 4.04065 9.63829 4.11649 9.7378C4.19232 9.83731 4.29972 9.91325 4.42424 9.95543L10.2125 11.9107L12.3746 18.5745C12.4144 18.6974 12.4967 18.8052 12.6091 18.8819C12.7216 18.9586 12.8582 19 12.9986 19C13.139 19 13.2756 18.9586 13.3881 18.8819C13.5005 18.8052 13.5828 18.6974 13.6226 18.5745L15.7854 11.9107L21.5736 9.95543C21.6987 9.9137 21.8067 9.8379 21.8829 9.73829C21.9592 9.63868 22.0001 9.52008 22 9.39857C21.9999 9.27705 21.9589 9.15849 21.8825 9.05896C21.8062 8.95943 21.6981 8.88373 21.573 8.84212Z"
                                            fill="#CCACFF" />
                                        <path
                                            d="M8.69123 16.2497L7.23038 15.7091L6.66196 13.6249C6.6256 13.4911 6.54623 13.373 6.43608 13.2888C6.32593 13.2045 6.19114 13.1589 6.05249 13.1589C5.91384 13.1589 5.77905 13.2045 5.6689 13.2888C5.55875 13.373 5.47938 13.4911 5.44302 13.6249L4.87459 15.7091L3.41375 16.2497C3.29288 16.2946 3.18864 16.3754 3.11502 16.4812C3.04141 16.5871 3.00195 16.7129 3.00195 16.8418C3.00195 16.9707 3.04141 17.0966 3.11502 17.2024C3.18864 17.3083 3.29288 17.3891 3.41375 17.4339L4.86701 17.9727L5.43986 20.2602C5.47408 20.3968 5.55296 20.518 5.66395 20.6046C5.77495 20.6912 5.9117 20.7383 6.05249 20.7383C6.19328 20.7383 6.33003 20.6912 6.44102 20.6046C6.55202 20.518 6.63089 20.3968 6.66512 20.2602L7.23796 17.9727L8.69123 17.4339C8.8121 17.3891 8.91634 17.3083 8.98995 17.2024C9.06357 17.0966 9.10302 16.9707 9.10302 16.8418C9.10302 16.7129 9.06357 16.5871 8.98995 16.4812C8.91634 16.3754 8.8121 16.2946 8.69123 16.2497Z"
                                            fill="#F5B266" />
                                        <path
                                            d="M5.95184 2.56593L4.45816 2.0133L3.90553 0.519616C3.86078 0.398536 3.78001 0.294072 3.67409 0.220294C3.56817 0.146515 3.44219 0.106963 3.31311 0.106963C3.18402 0.106963 3.05804 0.146515 2.95212 0.220294C2.8462 0.294072 2.76543 0.398536 2.72069 0.519616L2.16742 2.0133L0.67437 2.56593C0.553291 2.61068 0.448828 2.69145 0.37505 2.79737C0.301271 2.90329 0.261719 3.02927 0.261719 3.15835C0.261719 3.28744 0.301271 3.41342 0.37505 3.51934C0.448828 3.62526 0.553291 3.70603 0.67437 3.75077L2.16742 4.3034L2.72069 5.79709C2.76516 5.91844 2.84582 6.02321 2.95178 6.09723C3.05773 6.17125 3.18386 6.21094 3.31311 6.21094C3.44235 6.21094 3.56848 6.17125 3.67444 6.09723C3.78039 6.02321 3.86106 5.91844 3.90553 5.79709L4.45816 4.3034L5.95184 3.75077C6.07292 3.70603 6.17739 3.62526 6.25116 3.51934C6.32494 3.41342 6.36449 3.28744 6.36449 3.15835C6.36449 3.02927 6.32494 2.90329 6.25116 2.79737C6.17739 2.69145 6.07292 2.61068 5.95184 2.56593Z"
                                            fill="#A2C5FA" />
                                    </svg>
                                    Subscribe
                                </button>
                            </div>
                            <div class="fade-full"></div>
                        @endif
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/challenges-card-img-3.webp') }}"
                                alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                            <h3>Eat, Snap, Repeat: 3-Day Food Awareness Sprint</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>30</span>
                            </div>
                        </div>
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1 (1).webp') }}"
                                alt="Fat Loss Protein and Fats Diet Plan thumbnail" />
                            <h3>What do you really know about Supplements - Take Quiz</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/challenges-card-img-2.webp') }}"
                                alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                            <h3>Are You Eating for the Win? Take this Quiz</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>30</span>
                            </div>
                        </div>
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/challenges-card-img-4.webp') }}"
                                alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                            <h3>Which of these foods could actually make you faster? 3 min read</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>30</span>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Resources and Tools -->
            <section class="resources">
                <div class="section-header">
                    <h2>Resources and tools</h2>
                    <a href="#" class="see-all coming-soon-popup">See all</a>
                </div>
                <div class="resources-custom-grid">
                    <div class="cursor-pointer resource-card-custom resource-supplement hover-card scanner-btn" id="scanner-btn">
                        <img src="{{ frontAssets('images/cardbg.webp') }}" class="resource-bg-img"
                            alt="Supplement scanner background" />
                        <div class="icon-bg">
                            <img src="{{ frontAssets('images/camera.svg') }}" class="resource-bg-img"
                                alt="Camera icon for supplement scanner" />
                        </div>
                        <div class="resource-title">Supplement scanner</div>
                    </div>

                    <div class="cursor-pointer resource-card-custom resource-chat hover-card"
                        id="chat-to-virtual-kez-btn">
                        <img src="{{ frontAssets('images/cardimg-2.webp') }}" class="resource-bg-img"
                            alt="Chat resource background" />
                        <div class="icon-bg">
                            <img src="{{ frontAssets('images/chat.svg') }}" class="resource-bg-img"
                                alt="Chat icon for virtual Kez" />
                        </div>
                        <div class="resource-title">Chat to Virtual Kez</div>
                    </div>
                    <div class="resource-card-custom resource-tip">
                        <div class="tip-title">Kez's Tip of the Day</div>
                        <div class="tip-text">
                            “Supplements can support your goals, but they’re not shortcuts. Prioritise food first, and always choose batch-tested products to reduce your risk.”
                        </div>
                    </div>
                </div>
                <div class="resources-custom-grid grid-2">
                    <div class="resource-card-custom resource-video clickable hover-card" onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                        <div class="video-thumb-container"
                            onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                            <img src="{{ frontAssets('images/video-bg.webp') }}" class="video-thumb"
                                alt="Video thumbnail for whey protein post-training" />
                            <div class="video-icon-overlay">
                                 <img
                                src="{{ frontAssets('images/play.svg') }}"
                                class="video-thumb"
                                alt="play icon" />
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="video-title">
                                Understand why whey protein post-training results in better muscle gain
                            </div>
                            <div class="video-meta">
                                <span>
                                    <img src="{{ frontAssets('images/Clock.webp') }}" class="clock-img" alt="Clock icon"
                                        width="16" height="16" /></span><span>5 min • Video</span>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card-custom resource-video clickable hover-card" onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                        <div class="video-thumb-container">
                            <img src="{{ frontAssets('images/gym.webp') }}" class="video-thumb"
                                alt="Gym video thumbnail" />
                        </div>
                        <div class="video-info">
                            <div class="video-title">
                                Understand why whey protein post-training results in better muscle gain
                            </div>
                            <div class="video-meta">
                                <span>
                                    <img src="{{ frontAssets('images/Clock.webp') }}" class="clock-img" alt="Clock icon"
                                        width="16" height="16" /></span><span>5 min • Short read</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Optimize Performance -->
            <section class="optimize-performance">
                <div class="section-header">
                    <h2>Optimise your performance</h2>
                    <a href="/my-plans" class="see-all">All Plans</a>
                </div>

                @php
                    $mealCount = isset($userPlan->userMeals) ? $userPlan->userMeals->count() : 0;
                    $userPlan = $userPlan ?? null;

                    $latestMealImages = isset($userPlan->userMeals)
                        ? $userPlan
                            ->userMeals()
                            ->with('meal')
                            ->latest()
                            ->get()
                            ->map(function ($userMeal) {
                                return $userMeal->meal?->image ? asset('storage/' . $userMeal->meal->image) : null;
                            })
                            ->filter(function ($image) {
                                return !empty($image); // filters out null and empty strings
                            })
                            ->take(2)
                            ->values()
                            ->toArray()
                        : [];

                    $mealImage1 = $latestMealImages[0] ?? frontAssets('images/sports-training/fooditem1.webp');
                    $mealImage2 = $latestMealImages[1] ?? frontAssets('images/sports-training/fooditem6.webp');
                @endphp
                  <label class="plan-subtitle-mob">Nutrition plans</label>
                <div class="consults-plans-grid">
                    <div class="plan-card-custom plan-competition">
                        <div class="">
                            <div class="plan-title">Competition Plan</div>
                            <div class="plan-desc">
                                Unlock your best performance with a fully customised 24-hour competition day meal
                                plan—designed to you from the night before through recovery, tailored to your sport,
                                your preferences, and your game-day goals.
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ $mealImage1 }}" class="consult-avatar"
                                    alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ $mealImage2 }}" class="consult-avatar overlap1"
                                    alt="Kerry O'Bryan, expert coach avatar" />
                                <span>{{ $mealCount }} meals • 18 Nutrition tips</span>
                            </div>
                             </div>
                            <button class="btn-consult"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>

                    </div>
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                        <div class="plan-title">Injury</div>
                        <div class="plan-desc">
                            Add the Injury Recovery Upgrade to your Sports Training Plan—a targeted selection of
                            healing-focused meals, expert tips, and supplement guidance to accelerate recovery, reduce
                            inflammation, and get you back to full strength, faster—all built on a food-first approach.
                        </div>
                        <div class="consult-user-row">
                            <img src="{{ $mealImage1 }}" class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" />
                            <img src="{{ $mealImage2 }}" class="consult-avatar overlap1"
                                alt="Kerry O'Bryan, expert coach avatar" />
                            <span>{{ $mealCount }} meals • 18 Nutrition tips</span>
                        </div>
                        </div>
                        <button class="btn-consult"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                    </div>
                </div>


                 <label class="plan-subtitle-mob">Consultations</label>
                <div class="consults-plans-grid grid-1">
                    <div class="consultation-card-custom">
                        <div class="consult-title">Private Consultations</div>
                        <div class="consult-desc">
                            Live, one-on-one access to Extreme Sports Expert, trusted by Olympians and Pro Athletes.
                        </div>
                        <div class="consult-user-row">
                            <img src="https://booking.biohealthpassport.com.au/public/uploads/hero01.png"
                                class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" style="border:none;" />
                            <span style="padding-left:0">Kerry O'Bryan • 60 min</span>
                        </div>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank"
                            class="text-decoration-none btn-consult">Book consult</a>
                    </div>
                </div>
            </section>

            <!-- Surfing Videos -->
            <!-- <section class="surfing-videos">
                <div class="section-header">
                    <h2>What's hot in... Surfing</h2>
                </div>

                <div class="video-grid">
                    <div class="video-card">
                        <div class="video-thumbnail hover-card">
                            <div class="video-player" id="video-player-1">
                                <img src="{{ frontAssets('images/instaimg1.webp') }}" alt="Surfing video thumbnail"
                                    class="video-backdrop" width="372" height="249" />
                                <video style="display: none">
                                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4" />
                                </video>
                                <button class="play-btn" aria-label="Play video" onclick="playVideoInCard(1)">
                                   <img
                                src="{{ frontAssets('images/play.svg') }}"
                                class="video-thumb"
                                alt="play icon" style="width:38px; height:38px;"/>
                                </button>
                                <label class="insta-text">Watch on Instagram</label>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="top">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=30&h=30&fit=crop&crop=face"
                                    alt="Channel avatar for surfboard_co" class="channel-avatar" />
                                <div class="channel-name">
                                    <div class="channel-name-main">
                                        <label class="insta-handle-name">surfboard_co</label>
                                        <img src="{{ frontAssets('images/verified.svg') }}" alt="Verified badge" width="16" height="16" />
                                    </div>
                                    <label>Turnstile . LIGHT DESIGN</label>
                                </div>
                            </div>
                            <div class="video-details">
                                <p class="truncate-one-line">
                                    Learn about the best surfboard techniques for beginners and
                                    pros alike.
                                </p>
                                <div class="insta-like-wrapper">
                                    <img src="{{ frontAssets('images/like.webp') }}" alt="Like icon" width="20"
                                        height="18" style="width:20px;" />
                                    <span class="likes">892 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="video-card">
                        <div class="video-thumbnail hover-card">
                            <div class="video-player" id="video-player-2">
                                <img src="{{ frontAssets('images/instaimg2.webp') }}" alt="Surfing video thumbnail"
                                    class="video-backdrop" width="372" height="249" />
                                <video style="display: none">
                                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4" />
                                </video>
                                <button class="play-btn" aria-label="Play video" onclick="playVideoInCard(2)">
                                    <img
                                src="{{ frontAssets('images/play.svg') }}"
                                class="video-thumb"
                                alt="play icon" style="width:38px; height:38px;"/>
                                </button>
                                <label class="insta-text">Watch on Instagram</label>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="top">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=30&h=30&fit=crop&crop=face"
                                    alt="Channel avatar for surfboard_co" class="channel-avatar" />
                                <div class="channel-name">
                                    <div class="channel-name-main">
                                        <label class="insta-handle-name">surfboard_co</label>
                                        <img src="{{ frontAssets('images/verified.svg') }}" alt="Verified badge" width="16" height="16" />
                                    </div>
                                    <label>Turnstile . LIGHT DESIGN</label>
                                </div>
                            </div>
                            <div class="video-details">
                                <p class="truncate-one-line">
                                    Learn about the best surfboard techniques for beginners and
                                    pros alike.
                                </p>
                                <div class="insta-like-wrapper">
                                    <img src="{{ frontAssets('images/like.webp') }}" alt="Like icon" width="20"
                                        height="18" style="width:20px;" />
                                    <span class="likes">892 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="video-card">
                        <div class="video-thumbnail hover-card">
                            <div class="video-player" id="video-player-3">
                                <img src="{{ frontAssets('images/instaimg1.webp') }}" alt="Surfing video thumbnail"
                                    class="video-backdrop" width="372" height="249" />
                                <video style="display: none">
                                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4" />
                                </video>
                                <button class="play-btn" aria-label="Play video" onclick="playVideoInCard(3)">
                                    <img
                                src="{{ frontAssets('images/play.svg') }}"
                                class="video-thumb"
                                alt="play icon" style="width:38px; height:38px;"/>
                                </button>
                                <label class="insta-text">Watch on Instagram</label>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="top">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=30&h=30&fit=crop&crop=face"
                                    alt="Channel avatar for surfboard_co" class="channel-avatar" />
                                <div class="channel-name">
                                    <div class="channel-name-main">
                                        <label class="insta-handle-name">surfboard_co</label>
                                        <img src="{{ frontAssets('images/verified.svg') }}" alt="Verified badge" width="16" height="16" />
                                    </div>
                                    <label>Turnstile . LIGHT DESIGN</label>
                                </div>
                            </div>
                            <div class="video-details">
                                <p class="truncate-one-line">
                                    Learn about the best surfboard techniques for beginners and
                                    pros alike.
                                </p>
                                <div class="insta-like-wrapper">
                                    <img src="{{ frontAssets('images/like.webp') }}" alt="Like icon" width="20"
                                        height="18" style="width:20px;" />
                                    <span class="likes">892 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->
        </div>
        @include('front.modal.shopping-list')
        @include('front.modal.print-shopping-list')
        @include('front.modal.meal-detail')
        @include('front.modal.smart-swap')
        @include('front.modal.smart-swap-items')
    </main>

    <!-- Custom Congrats Modal -->
    <div class="modal" id="customCongratsModal" tabindex="-1" aria-labelledby="customCongratsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="p-0 modal-body">
                    <div class="recipe-dialog">
                        <button class="dialog-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </button>
                        <div class="dialog-content">
                            <div class="dialog-main-view">
                                <div class="dialog-header" style="position:relative;">
                                    <div class="custom-popup-main-row">
                                        <div class="custom-popup-text-section">
                                            <div class="custom-popup-text">
                                                <h3>Congrats, you're in!</h3>
                                                <p>You've just taken the first step toward smarter fuel, stronger
                                                    performance, and better results. We're stoked to have you - let's get
                                                    started. 🚀</p>
                                                <h4>Your quiz score as promised </h4>
                                            </div>
                                        </div>
                                        <div class="custom-popup-gauge-section">
                                            <div class="custom-popup-gauge">
                                                <div class="score-meter-box">
                                                    <div class="score-meter-text">
                                                        <span class="meter-text-01">Needs <br>work </span>
                                                        <span class="meter-text-02">Pretty <br>ordinary</span>
                                                        <span class="meter-text-03">Not bad</span>
                                                        <span class="meter-text-04">Good</span>
                                                    </div>
                                                    <div class="score-meter-box-frame">
                                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 500 243"
                                                            style="enable-background:new 0 0 500 243;"
                                                            xml:space="preserve">
                                                            <path
                                                                d="M0,0v243h500V0H0z M474.7,233.7h-79.1c-4.9,0-9.2-3.6-9.9-8.5c-9.6-65.5-66.1-115.9-134.3-115.9s-124.6,50.3-134.3,115.9c-0.7,4.9-4.9,8.5-9.9,8.5H28.2c-5.9,0-10.5-5.1-10-11c11.3-119,111.4-212,233.2-212s221.9,93.1,233.2,212C485.2,228.6,480.6,233.7,474.7,233.7z"
                                                                fill="#ffffff" />
                                                        </svg>
                                                        <div class="bgradient-bg"
                                                            style="background: conic-gradient(from -1.65deg at 48.15% 84.72%, #FF9500 -33.16deg, #FFDE48 31.45deg, #03741B 91.78deg, #CF080A 265.07deg, #FF9500 326.84deg, #FFDE48 391.45deg);">
                                                        </div>
                                                    </div>
                                                    <span class="meter-arrow nutrition-result"
                                                        style="transform: rotate(90deg);">
                                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 133 22"
                                                            style="enable-background:new 0 0 133 22;"
                                                            xml:space="preserve">
                                                            <path
                                                                d="M91.8,0.4L3.4,8.7c-2.5,0.2-2.5,3.8,0,4.1l88.4,8.9c20.5-0.4,12.7-0.4,20.5-0.4c11.8,0,19.2,1.6,19.2-10.1c0-11.8-10-10.2-21.7-10.3C101.9,0.8,112,0.9,91.8,0.4z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <h4 class="mt-4">General Nutrition Knowledge
                                                </h4>
                                                <h3 class="mt-1 text-black nutrition-percentage">--%
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>If you have any gaps, we are here to help and will add some tips and info into the
                                    Level-up library for you.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const user = @json($userPlan);
        const userId = user ? user.user_id : 0;
        const isFreeUser = user ? user.free_user : 0;
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const contentWrapper = document.getElementById('meal-cards-wrapper');
            const scrollContainer = document.getElementById("meal-cards-wrapper");
            const leftArrow = document.querySelector(".left-arrow");
            const rightArrow = document.querySelector(".right-arrow");
            const scrollAmount = 300;

            // Show arrows only if 4 or more cards exist
            function updateArrowVisibility() {
                const cards = scrollContainer.querySelectorAll(".challenge-card");
                const shouldShowArrows = cards.length > 4;

                if (shouldShowArrows) {
                    leftArrow.style.display = 'block';
                    rightArrow.style.display = 'block';
                } else {
                    leftArrow.style.display = 'none';
                    rightArrow.style.display = 'none';
                }
            }

            // Scroll behavior
            leftArrow.addEventListener("click", () => {
                scrollContainer.scrollBy({
                    left: -scrollAmount,
                    behavior: "smooth"
                });
            });

            rightArrow.addEventListener("click", () => {
                scrollContainer.scrollBy({
                    left: scrollAmount,
                    behavior: "smooth"
                });
            });

            // Call once after load
            updateArrowVisibility();

            function loadMeals(planId, categoryId) {
                contentWrapper.innerHTML = '<p>Loading meals...</p>';

                // Laravel route with placeholders
                const baseUrl = @json(route('front.get-profile-meals', ['plan' => 'PLAN_ID', 'category' => 'CATEGORY_ID']));
                const fetchUrl = baseUrl.replace('PLAN_ID', planId).replace('CATEGORY_ID', categoryId);

                fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Fetch failed');
                        return response.text();
                    })
                    .then(html => {
                        contentWrapper.innerHTML = html;
                        // 🔥 Important: Wait for DOM to update, then check arrows
                        requestAnimationFrame(() => {
                            updateArrowVisibility();
                        });
                    })
                    .catch(() => {
                        contentWrapper.innerHTML = '<p>Error loading meals.</p>';
                    });
            }

            // Click event for each tab
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const categoryId = this.dataset.categoryId;
                    const planId = this.dataset.planId;
                    loadMeals(planId, categoryId);
                });
            });

            // 🔥 Load meals for the first tab by default
            const firstTab = document.querySelector('.tab.active');
            if (firstTab) {
                loadMeals(firstTab.dataset.planId, firstTab.dataset.categoryId);
            }

            $('#start-chat-link, #chat-to-virtual-kez-btn').click(function() {
                $('#delphi-bubble-trigger').click();
            });

            $('.scanner-btn').click(function() {
                location.href = "https://phenomenal-torrone-cee914.netlify.app/";
            });

            // This is now handled in the DOMContentLoaded event below
            // to ensure proper backdrop management
        });

        function showLoader() {
            $('#loader').removeClass('d-none');
        }

        function hideLoader() {
            $('#loader').addClass('d-none');
        }

        $(document).ready(function() {
            // Open Bootstrap modal on meal click
            $('body').on('click', '.quick-view-btn', function() {
                if (isFreeUser == 1) {
                    return;
                }

                const user_meal_id = $(this).data('meal-id');
                const user_plan_id = $(this).data('user-plan-id');
                const user_sub_category_id = $(this).data('sub-category-id');
                const user_category_id = $(this).data('category-id');
                showLoader();

                $.ajax({
                    url: "{{ route('front.meal.details') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_meal_id,
                        user_plan_id,
                        user_sub_category_id,
                        user_category_id
                    },
                    success: function(response) {
                        const meal = response.meal;

                        // 🖼️ Set meal title and description
                        $('#recipeDialogModal .modal-body .dialog-header h2').text(meal.meal
                            .title || 'Meal');
                        $('#recipeDialogModal .modal-body .dialog-header p').text(meal.meal
                            .description || '');

                        const imageUrl = meal.meal.image ?
                            `{{ asset('storage') }}/` + meal.meal.image :
                            `{{ asset('front/images/placeholder.png') }}`;
                        $('#recipeDialogModal .modal-body .dialog-img').attr('src', imageUrl);

                        // 🥣 Ingredients
                        let ingredientsHtml = '';
                        meal.user_items.forEach(function(userItem) {
                            const item = userItem.item;
                            if (!item) return;

                            const selectedUnits = item.selected_qty_unit || [];
                            const selected = selectedUnits.find(u => u.checked) || null;

                            let qty = '';
                            let unit = '';

                            if (selected) {
                                qty = selected.qty;
                                unit = selected.unit?.trim();
                            } else {
                                qty = item.qty;
                                unit = item.unit?.trim();
                            }

                            const noSpaceUnits = ['g', 'ml', 'mL'];
                            const space = noSpaceUnits.includes(unit) ? '' : ' ';

                            ingredientsHtml +=
                                `<li>${qty}${space}${unit} ${item.title}</li>`;
                        });

                        if (response.isFreeUser) {
                            $('#recipeDialogModal .modal-body .smart-swap-btn').hide();
                        } else {
                            $('#recipeDialogModal .modal-body .smart-swap-btn').show();
                        }

                        $('#recipeDialogModal .modal-body ul').html(ingredientsHtml);

                        // 📝 Instructions / Note
                        if (meal.meal.note && meal.meal.note.trim() !== '') {
                            $('#recipeDialogModal .modal-body .note').html(
                                `<strong>Note:</strong> ${meal.meal.note}`
                            ).show();
                            $('#recipeDialogModal .modal-body h3:contains("Instructions")')
                                .show();
                        } else {
                            $('#recipeDialogModal .modal-body .note').hide();
                            $('#recipeDialogModal .modal-body h3:contains("Instructions")')
                                .hide();
                        }
                        // $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();

                        // 🔢 Nutrition Info
                        $('#recipeDialogModal .modal-body .nutrition-info').html(`
                            <span style="color: #a60015">●  <span style="color:rgba(59, 59, 59, 1)">Protein: ${(Number(response.totalProtein) || 0).toFixed(2)} g</span></span>
                            <span style="color: #3e8e00">●  <span style="color:rgba(59, 59, 59, 1)">Carb: ${(Number(response.totalCarbs) || 0).toFixed(2)} g</span></span>
                            <span style="color: #0077b6">●  <span style="color:rgba(59, 59, 59, 1)">Fat: ${(Number(response.totalFats) || 0).toFixed(2)} g</span></span>
                            <span style="color: #967500">●  <span style="color:rgba(59, 59, 59, 1)">Energy: ${(Number(response.totalEnergy) || 0).toFixed(2)} kJ</span></span>
                        `);

                        // Set data attributes for Smart Swap
                        $('#recipeDialogModal .modal-body .smart-swap-btn')
                            .attr('data-meal-id', user_meal_id)
                            .attr('data-user-plan-id', user_plan_id)
                            .attr('data-sub-category-id', user_sub_category_id)
                            .attr('data-category-id', user_category_id)
                            .attr('data-meal-name', meal.meal.title);

                        // 👁️ Show Bootstrap modal
                        const modal = new bootstrap.Modal(document.getElementById(
                            'recipeDialogModal'));
                        modal.show();
                        hideLoader();
                    },
                    error: function() {
                        $('#errormodalmain').modal('show');
                        hideLoader();
                    }
                });
            });

            $(document).on('hide.bs.modal', '#recipeDialogModal', function() {
                // Clear the modal content when it is closed
                $('#recipeDialogModal .modal-body .dialog-header h2').text('');
                $('#recipeDialogModal .modal-body .dialog-header p').text('');
                $('#recipeDialogModal .modal-body .dialog-img').attr('src', '');
                $('#recipeDialogModal .modal-body ul').empty();
                $('#recipeDialogModal .modal-body .note').hide();
                $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
                $('#recipeDialogModal .modal-body .nutrition-info').empty();
                $('.modal-backdrop').remove();
            });

            $(document).on('click', '.meal-item-btn', function() {
                const $btn = $(this);

                const meal_id = $btn.attr('data-meal-id');
                const meal_name = $btn.attr('data-meal-name');
                const user_meal_id = $btn.attr('data-meal-id');
                const userPlanId = $btn.attr('data-user-plan-id');
                const userSubCategoryId = $btn.attr('data-sub-category-id');
                const userCategoryId = $btn.attr('data-category-id');

                $('#recipeDialogModal').modal('hide');
                mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId,
                    userCategoryId);
            });

            function mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId,
                userCategoryId) {
                const modalEl = $('#mealItemModel');
                const modal = new bootstrap.Modal(modalEl[0]);
                modal.show();

                const $mealItemsModalLabel = $('.swap-title'); // Set meal name here
                const $mealItemsContainer = $('.swap-list'); // Container for item cards
                const $mealItemsLoadingSpinner = $(
                '#mealItemsLoadingSpinner'); // Optional: add loading spinner if you want

                if (!user_meal_id || !meal_name) {
                    console.error('Invalid meal data.');
                    return;
                }

                $mealItemsModalLabel.text(meal_name);
                $mealItemsContainer.empty();

                // Optional: show spinner
                // $mealItemsLoadingSpinner.show();

                $.ajax({
                    url: '{{ route('front.meals.items', ':mealId') }}'
                        .replace(':mealId', meal_id) +
                        `?user_meal_id=${user_meal_id}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.items && data.items.length > 0) {
                            $.each(data.items, function(index, item) {
                                let displayQty = '';
                                let selectedUnits = [];

                                try {
                                    selectedUnits = typeof item.selected_qty_unit === 'string' ?
                                        JSON.parse(item.selected_qty_unit) :
                                        Array.isArray(item.selected_qty_unit) ?
                                        item.selected_qty_unit :
                                        [];
                                } catch (e) {
                                    console.warn('Failed to parse selected_qty_unit for item:',
                                        item.name, e);
                                }

                                const checkedUnits = selectedUnits.filter(u =>
                                    u.checked === true || u.checked === "true" || u
                                    .checked === 1 || u.checked === "1"
                                );

                                if (checkedUnits.length > 0) {
                                    const formattedUnits = checkedUnits.map(u => {
                                        let qtyText = u.qty?.toString().trim() || '';
                                        const unitText = (u.unit || '').trim();
                                        const needsSpace = !["g", "ml", "mL"].includes(
                                            unitText.toLowerCase());

                                        const numericQty = Number(qtyText);
                                        if (!isNaN(numericQty)) {
                                            qtyText = numericQty % 1 === 0 ? numericQty
                                                .toFixed(0) : numericQty.toFixed(1);
                                        }

                                        return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                }

                                if (!displayQty && item.qty && item.unit) {
                                    const unit = item.unit.toString();
                                    const needsSpace = !["g", "ml", "mL"].includes(unit
                                        .toLowerCase());
                                    displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;
                                }

                                const itemCard = `
                                    <div class="swap-item">
                                        <img src="${item.image}" alt="${item.name}" class="swap-item-img" />
                                        <div class="flex-wrapper">

                                        <div class="swap-item-info">
                                            <div class="swap-item-name">${item.name}</div>
                                            <div class="swap-item-qty"><b>Qty :</b> ${displayQty}</div>
                                        </div>
                                        <div class="swap-item-actions">
                                            ${item.swapItems?.length > 0 ? `
                                                    <button class="smart-swap-btn item-swap-btn"
                                                        data-item-id="${item.id}"
                                                        data-item-name="${item.name}"
                                                        data-user-item-id="${item.user_item_id}"
                                                        data-user-meal-id="${item.user_meal_id}"
                                                        data-user-plan-id="${userPlanId}"
                                                        data-sub-category-id="${userSubCategoryId}"
                                                        data-user-category-id="${userCategoryId}">
                                                        <img src="{{ frontAssets('images/dialog/swap.svg') }}" style="width: 18px; vertical-align: middle; margin-right: 4px;" />
                                                        <span>Swap</span>
                                                    </button>` : ''}
                                            ${item.description ? `
                                                    <button class="smart-swap-btn" data-bs-toggle="tooltip" title="${item.description}">
                                                        <img src="{{ frontAssets('images/dialog/Info.svg') }}" alt="Info" style="width: 24px; vertical-align: middle" />
                                                    </button>` : ''}
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                `;

                                $mealItemsContainer.append(itemCard);
                            });
                            $('[data-bs-toggle="tooltip"]').tooltip();

                        } else {
                            $mealItemsContainer.html(
                                '<p class="text-center">No foods available in this meal.</p>');
                        }

                        // Optional: hide spinner
                        // $mealItemsLoadingSpinner.hide();
                    },
                    error: function() {
                        $mealItemsContainer.html(
                            '<p class="text-danger text-center">Failed to load foods.</p>');
                        // $mealItemsLoadingSpinner.hide();
                    }
                });
            }

            $(document).on('click', '.meal-item-modal-close', function() {
                const modalEl = $('#mealItemModel')[0];
                const modalInstance = bootstrap.Modal.getInstance(modalEl);

                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    // fallback if instance wasn't created by Bootstrap JS
                    const newModal = new bootstrap.Modal(modalEl);
                    newModal.hide();
                }
            });

            $(document).on('click', '.item-swap-btn', function() {
                const itemId = $(this).data('item-id');
                const itemName = $(this).data('item-name');
                const userItemId = $(this).data('user-item-id');
                const userMealId = $(this).data('user-meal-id');
                const userPlanId = $(this).data('user-plan-id');
                const userSubCategoryId = $(this).data('sub-category-id');
                const userCategoryId = $(this).data('user-category-id');

                if (!itemId || !itemName) {
                    console.error('Invalid item data.');
                    return;
                }

                $('.apply-changes-btn').attr('data-user-item-id', userItemId);
                $('.apply-changes-btn').attr('data-user-meal-id', userMealId);
                $('.apply-changes-btn').attr('data-user-plan-id', userPlanId);
                $('.apply-changes-btn').attr('data-user-sub-category-id', userSubCategoryId);
                $('.apply-changes-btn').attr('data-user-category-id', userCategoryId);

                // Show the modal first
                const modal = new bootstrap.Modal(document.getElementById('smartSwapModal'));
                modal.show();

                // Update the title in the modal
                $('#smartSwapModalLabel').text(`Swap: ${itemName}`);

                // Clear existing items
                const $swapList = $('#smartSwapModal .swap-list');
                $swapList.html(`
                    <div class="py-4 text-center">
                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                    </div>
                `);

                // Perform AJAX request to fetch swap items
                $.ajax({
                    url: '{{ route('front.items.swap-items', ':id') }}'.replace(':id', itemId) +
                        `?user_meal_id=${userMealId}&user_item_id=${userItemId}&user_plan_id=${userPlanId}&sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (!data || !data.items || !data.items.length) {
                            $swapList.html(
                                '<p class="text-muted text-center">No swap items available.</p>'
                                );
                            return;
                        }

                        // ✅ Helper: Format Qty Unit String
                        function formatQtyUnit(unitsArray, fallbackQty, fallbackUnit) {
                            if (!unitsArray || !unitsArray.length) {
                                return formatUnitText(fallbackQty, fallbackUnit);
                            }

                            const checked = unitsArray.find(u => u.checked);
                            if (checked) {
                                return formatUnitText(checked.qty, checked.unit);
                            }

                            return formatUnitText(fallbackQty, fallbackUnit);
                        }

                        function formatUnitText(qty, unit) {
                            qty = qty ?? '1';
                            unit = (unit || '').trim();

                            if (!unit) return qty;

                            const compactUnits = ['g', 'ml', 'mL'];
                            if (compactUnits.includes(unit)) {
                                return `${qty}${unit}`;
                            }

                            return `${qty} ${unit}`;
                        }

                        // ✅ Build Main Item HTML
                        const item = data.item;
                        const mainQtyText = formatQtyUnit(item.selected_qty_unit, item.qty, item
                            .unit);
                        let mainItem = `
                            <div class="swap-item" id="mainSwapItem" data-item-id="${item.id}" style="border-bottom: none">
                                <img src="${data.item_image}" alt="${data.item_name}" class="swap-item-img"/>
                                <div class="flex-wrapper">
                                <div class="swap-item-info">
                                    <div class="swap-item-name">${data.item_name}</div>
                                    <div class="swap-item-qty"><b>Qty:</b> ${mainQtyText}</div>
                                </div>
                                <div class="swap-item-actions">
                                    ${item.description ? `
                                            <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${item.description}">
                                                <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                                            </button>
                                        ` : ''}
                                </div>
                                </div>
                            </div>

                            <div class="swap-item swap-item-h3"><h3>Swap with</h3></div>
                        `;

                        // ✅ Build Swap Items HTML
                        let swapItemsHTML = '';
                        data.items.forEach(function(swapItem) {
                            const swapQtyText = formatQtyUnit(swapItem
                                .selected_qty_unit, swapItem.swap_item_qty, swapItem
                                .swap_item_unit);

                            swapItemsHTML += `
                                <div class="swap-item">
                                    <img src="${swapItem.swap_item_image}" alt="${swapItem.swap_item_name}" class="swap-item-img"/>
                                    <div class="flex-wrapper">
                                    <div class="swap-item-info">
                                        <div class="swap-item-name">${swapItem.swap_item_name}</div>
                                        <div class="swap-item-qty"><b>Qty:</b> ${swapQtyText}</div>
                                    </div>
                                    <div class="swap-item-actions">
                                        <button class="smart-swap-btn swap-btn" data-swap-item-id="${swapItem.swap_item_id}">
                                            <img src="{{ frontAssets('images/dialog/swap.svg') }}" style="width: 18px; margin-right: 4px;" /><span>Swap</span>
                                        </button>
                                        ${swapItem.swap_item_description ? `
                                                <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${swapItem.swap_item_description}">
                                                    <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                                                </button>
                                            ` : ''}
                                    </div>
                                    </div>
                                </div>
                            `;
                        });

                        // ✅ Inject into DOM
                        $swapList.html(mainItem + swapItemsHTML);

                        // ✅ Enable Bootstrap 5 tooltips
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('#mealItemModel').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        $swapList.html(
                            '<p class="text-danger text-center">Failed to load swap items.</p>'
                            );
                        console.error('Error loading swap items:', error);
                    }
                });

            });

            $('#mealItemModel').on('hidden.bs.modal', function() {
                $('.modal-backdrop').remove();
                $('#mealItemsContainer').empty();
                $('#mealItemsLoadingSpinner').hide();
            });


            let currentMainItem = null;
            let swaps = []; // Array to hold latest swap pair

            $(document).on('click', '.swap-btn', function() {
                const $clickedSwap = $(this).closest('.swap-item');
                const swapItemId = $(this).data('swap-item-id');

                const $mainItem = $('#mainSwapItem'); // ✅ SELECTS THE MAIN ITEM CORRECTLY NOW
                const mainItemId = $mainItem.data(
                'item-id'); // You must set this in HTML: data-item-id="${item.id}"

                // Capture current main item details if not already stored
                if (!currentMainItem) {
                    currentMainItem = {
                        id: mainItemId,
                        name: $mainItem.find('.swap-item-name').text(),
                        qty: $mainItem.find('.swap-item-qty').text().replace('Qty:', '').trim(),
                        description: $mainItem.find('.info-btn').attr('data-bs-original-title') || '',
                        image: $mainItem.find('img.swap-item-img').attr('src')
                    };
                }

                // Get clicked swap item details
                const swapItem = {
                    id: swapItemId,
                    name: $clickedSwap.find('.swap-item-name').text(),
                    qty: $clickedSwap.find('.swap-item-qty').text().replace('Qty:', '').trim(),
                    description: $clickedSwap.find('.info-btn').attr('data-bs-original-title') || '',
                    image: $clickedSwap.find('img.swap-item-img').attr('src')
                };

                // === Update Main Item UI ===
                $mainItem.find('.swap-item-name').text(swapItem.name);
                $mainItem.find('.swap-item-qty').html('<b>Qty:</b> ' + swapItem.qty);
                $mainItem.find('img.swap-item-img').attr('src', swapItem.image);
                $mainItem.find('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $mainItem.find('.info-btn').remove();

                if (swapItem.description) {
                    $mainItem.find('.swap-item-actions').append(`
                        <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${swapItem.description}">
                            <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                        </button>
                    `);
                }

                // === Replace clicked swap item with the original main item ===
                const revertedHTML = `
                    <img src="${currentMainItem.image}" alt="${currentMainItem.name}" class="swap-item-img"/>
                    <div class="flex-wrapper">
                    <div class="swap-item-info">
                        <div class="swap-item-name">${currentMainItem.name}</div>
                        <div class="swap-item-qty"><b>Qty:</b> ${currentMainItem.qty}</div>
                    </div>
                    <div class="swap-item-actions">
                        <button class="smart-swap-btn swap-btn" data-swap-item-id="${currentMainItem.id}">
                            <img src="{{ frontAssets('images/dialog/swap.svg') }}" style="width: 18px; margin-right: 4px;" /><span>Swap</span>
                        </button>
                        ${currentMainItem.description ? `
                                <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${currentMainItem.description}">
                                    <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                                </button>` : ''}
                    </div>
                    </div>
                `;

                $clickedSwap.html(revertedHTML);

                // ✅ Update swap tracking (replace last entry if already swapped)
                swaps = [{
                    main_id: swapItem.id,
                    swap_id: currentMainItem.id,
                    user_item_id: currentMainItem.id // or use some real user_item_id if needed
                }];

                // Update the reference for next potential swap
                currentMainItem = swapItem;

                // Reinitialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            $('#smartSwapModal').on('hidden.bs.modal', function() {
                currentMainItem = null;
                swaps = []; // Reset swaps array
                $('#smartSwapModalLabel').text(''); // Clear modal title
                $('#smartSwapModal .swap-list').empty(); // Clear HTML inside modal
                $('.modal-backdrop').remove();
            });

            // Apply Swap Changes functionality
            $(document).on('click', '.apply-changes-btn', function() {
                // Send all swaps to the server
                const userItemId = $(this).attr('data-user-item-id');
                const userMealId = $(this).attr('data-user-meal-id');
                const userPlanId = $(this).attr('data-user-plan-id');
                const userSubCategoryId = $(this).attr('data-user-sub-category-id');
                const userCategoryId = $(this).attr('data-user-category-id');

                $.ajax({
                    url: "{{ route('front.items.swaps') }}", // Laravel route to handle the request
                    method: "GET",
                    data: {
                        swaps: swaps,
                        meal_id: userMealId,
                        user_item_id: userItemId,
                        user_meal_id: userMealId,
                        user_category_id: userCategoryId,
                        user_sub_category_id: userSubCategoryId,
                        user_plan_id: userPlanId,
                        user_id: userId,
                    },
                    success: function(response) {
                        // Handle success response
                        swaps = [];
                        currentMainItem = null;

                        if (response.success) {
                            $('#smartSwapModal').modal('hide');
                            var meal_id = response.data['meal_id'];
                            var meal_name = response.data['meal_name'];
                            var user_meal_id = response.data['user_meal_id'];
                            mealItemModelReload(meal_id, meal_name, user_meal_id,
                                userSubCategoryId, userPlanId, userCategoryId);
                        } else {
                            $('#errormodalmain .modal-body').html(
                                `<h4>Ooops!</h4><p>${response.message}</p>`);
                            $('#errormodalmain').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        if (xhr.status === 422) {
                            // Laravel-style validation error handling
                            let errors = xhr.responseJSON?.errors;
                            let messageHtml = '';

                            if (errors) {
                                // fallback if error messages not formatted
                                messageHtml =
                                    '<h4>Ooops!</h4><p>Invalid swap. Please check and try again.</p>';
                            }

                            $('#errormodalmain .modal-body').html(messageHtml);
                        } else {
                            // Generic fallback for other HTTP errors
                            $('#errormodalmain .modal-body').html(
                                '<h4>Ooops!</h4>	<p>Invalid swap. Please try again later.</p>'
                                );
                        }

                        $('#errormodalmain').modal('show');
                    }
                });
            });
        });
    </script>
    <script>
        // Function to open quiz outcome modal
        function openCustomCongratsModal() {
            const modal = new bootstrap.Modal(document.getElementById('customCongratsModal'));
            modal.show();

            // Automatically fetch nutrition score if quiz ID is available
            const quizId = getQuizIdFromStorage();
            if (quizId) {
                fetchAndDisplayNutritionScore(quizId);
            } else {
                // Set default display if no quiz ID is available
                setDefaultNutritionDisplay();
            }
        }

        // Function to close the modal
        function closeCustomCongratsModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('customCongratsModal'));
            if (modal) {
                modal.hide();
            }
        }

        // Function to get quiz ID from session storage
        function getQuizIdFromStorage() {
            // Try to get from completed quiz ID first
            let quizId = sessionStorage.getItem('completed_quiz_id');

            // If still not found, try from quiz state
            if (!quizId) {
                const quizState = sessionStorage.getItem('quiz_state');
                if (quizState) {
                    try {
                        const state = JSON.parse(quizState);
                        quizId = state.quizId;
                    } catch (e) {
                        console.error('Error parsing quiz state:', e);
                    }
                }
            }

            return quizId;
        }

        // Function to fetch and display nutrition score
        function fetchAndDisplayNutritionScore(quizId) {
            if (!quizId) {
                console.log('No quiz ID provided, setting default display');
                setDefaultNutritionDisplay();
                return;
            }

            // Show loading state
            const percentageElement = document.querySelector('.nutrition-percentage');
            const arrowElement = document.querySelector('.nutrition-result');

            if (percentageElement) {
                percentageElement.textContent = 'Loading...';
            }

            if (arrowElement) {
                arrowElement.style.transform = 'rotate(90deg)'; // Reset to default position
            }

            // Make AJAX request to get nutrition score using jQuery
            $.ajax({
                url: "{{ route('front.quiz.nutrition-score') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {
                    quiz_id: quizId
                },
                success: function(response) {
                    if (response.success && response.nutrition_score) {
                        // Update percentage
                        if (percentageElement) {
                            percentageElement.textContent = response.nutrition_percentage + '%';
                        }

                        // Update arrow rotation
                        if (arrowElement) {
                            arrowElement.style.transform = `rotate(${response.arrow_rotation}deg)`;
                        }

                        // Update feedback if available
                        if (response.feedback) {
                            const feedbackElement = document.querySelector('.nutrition-feedback');
                            if (feedbackElement) {
                                feedbackElement.textContent = response.feedback;
                            }
                        }
                    } else {
                        console.error('Error fetching nutrition score:', response.message);
                        setDefaultNutritionDisplay();
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching nutrition score:', xhr.responseText);
                    setDefaultNutritionDisplay();
                }
            });
        }

        // Function to set default nutrition display
        function setDefaultNutritionDisplay() {
            const percentageElement = document.querySelector('.nutrition-percentage');
            const arrowElement = document.querySelector('.nutrition-result');

            if (percentageElement) {
                percentageElement.textContent = '--%';
            }

            if (arrowElement) {
                arrowElement.style.transform = 'rotate(90deg)'; // Default position
            }
        }

        // Function to open modal with nutrition score
        function openCustomCongratsModalWithScore() {
            // First open the modal
            openCustomCongratsModal();

            // Then fetch and display the nutrition score
            fetchAndDisplayNutritionScore();
        }

        // Optional: Add event listener for when modal is hidden
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('customCongratsModal');
            modal.addEventListener('hidden.bs.modal', function() {
                // Any cleanup code can go here
                console.log('Modal closed');
            });

            // Check if user has completed a quiz and show modal automatically
            checkAndShowQuizResults();
        });

        // Function to check if user has completed a quiz and show results
        function checkAndShowQuizResults() {
            const quizId = sessionStorage.getItem('completed_quiz_id');
            if (quizId) {
                // Check if this is a completed quiz by looking for nutrition score
                $.ajax({
                    url: "{{ route('front.quiz.nutrition-score') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: {
                        quiz_id: quizId
                    },
                    success: function(response) {
                        if (response.success && response.nutrition_score) {
                            // User has completed a quiz, show the modal
                            setTimeout(() => {
                                openCustomCongratsModal();
                            }, 1000); // Small delay to ensure page is fully loaded
                        } else {
                            console.log('Quiz not completed or no nutrition score found');
                            // Set default display if no nutrition data
                            setDefaultNutritionDisplay();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error checking quiz completion:', xhr.responseText);
                        // Set default display on error
                        setDefaultNutritionDisplay();
                    }
                });
            } else {
                console.log('No quiz ID found in storage');
                // Set default display when no quiz data is available
                setDefaultNutritionDisplay();
            }
        }
    </script>
   <script>
    // Example binding
    document.querySelectorAll('.learn-more-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            showLearnMoreTooltip(this, 'Pro Plan', e);
        });
    });

    // Add CSS to fix modal backdrop issues
    const modalBackdropFix = document.createElement('style');
    modalBackdropFix.textContent = `
        .modal-backdrop.show {
            opacity: 0.5 !important;
        }
        .modal-backdrop.fade {
            opacity: 0 !important;
        }
        .modal-backdrop:not(.show) {
            opacity: 0 !important;
            pointer-events: none !important;
        }
    `;
    document.head.appendChild(modalBackdropFix);

    // Fix for coming soon modal close button
    document.addEventListener('DOMContentLoaded', function() {
        const comingSoonModal = document.getElementById('comingSoonModal');
        const comingSoonCloseBtn = comingSoonModal?.querySelector('.coming-soon-close');

        if (comingSoonCloseBtn) {
            // Remove the data-bs-dismiss attribute to prevent conflicts
            comingSoonCloseBtn.removeAttribute('data-bs-dismiss');

            comingSoonCloseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Force hide the modal and remove backdrop
                comingSoonModal.style.display = 'none';
                comingSoonModal.classList.remove('show');
                document.body.classList.remove('modal-open');

                // Restore body scroll
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Remove all modal backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => {
                    backdrop.remove();
                });

                // Also try Bootstrap method as backup
                if (typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(comingSoonModal);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        }

        // Also handle the modal opening to ensure proper backdrop management
        document.querySelectorAll('.coming-soon-popup').forEach(function(card) {
            card.addEventListener('click', function(e) {
                var comingSoonModal = document.getElementById('comingSoonModal');
                if (comingSoonModal && typeof bootstrap !== 'undefined') {
                    e.preventDefault();

                    // Remove any existing backdrops first
                    const existingBackdrops = document.querySelectorAll('.modal-backdrop');
                    existingBackdrops.forEach(backdrop => backdrop.remove());

                    var modal = new bootstrap.Modal(comingSoonModal);
                    modal.show();
                }
            });
        });

        // Add event listener for modal hidden event to ensure body scroll is restored
        if (comingSoonModal) {
            comingSoonModal.addEventListener('hidden.bs.modal', function() {
                // Restore body scroll when modal is hidden
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                document.body.classList.remove('modal-open');
            });
        }
    });

    </script>

@endsection
