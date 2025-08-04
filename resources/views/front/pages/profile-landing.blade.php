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
                    <div class="welcome-message">
                        <h2>Welcome back legend! How's your week going?</h2>
                        <div class="welcome-row">
                            <a href="#" class="start-chat" id="start-chat-link">Start chat</a>
                            <span class="assistant-name">Kerry O'Bryan Virtual</span>
                        </div>
                    </div>
                    <img
                        src="{{ frontAssets('images/profile.svg') }}"
                        alt="Profile"
                        class="profile-avatar-overlap" />
                </div>
                <div class="welcome-arrow"></div>
            </section>
            @if(isset($userPlan->plan))
                <!-- Sports Training Plan -->
                <section class="training-plan">
                    @if(isset($userPlan->plan))
                    <div class="section-header">
                        <h2>{{ isset($userPlan->plan) ? $userPlan->plan->name : '' }}</h2>
                        <a href="{{ route('front.plans.details', ['id' => $userPlan->plan->id, 'user_id' => $userPlan->user->id]) }}" class="see-all">See Plan</a>
                    </div>
                    @else
                    <div class="section-header">
                        <h2></h2>
                        <a href="#" class="see-all">See Plan</a>
                    </div>
                    @endif
                    {{-- Tabs --}}
                    <div class="tabs">
                        @php $firstTab = true; @endphp
                        @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userCategory)
                            @php
                            $category = $userCategory->category;
                            $hasValidMeal = $userCategory->userSubCategories()
                                ->where('user_plan_id', $userPlan->id)
                                ->whereHas('userMeals', function ($q) use ($userPlan, $userCategory) {
                                    $q->where('user_plan_id', $userPlan->id)
                                        ->where('user_category_id', $userCategory->id);
                                })->exists();
                            @endphp

                            @if ($hasValidMeal && $category)
                                <button
                                    class="tab {{ $firstTab ? 'active' : '' }}"
                                    data-category-id="{{ $category->id }}"
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
                                <svg width="18" height="24" viewBox="0 0 18 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="14,4 4,16 14,28" stroke="#080808" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;"  id="meal-cards-wrapper">
                                <p>Loading meals...</p>
                            </div>
                            <button class="right-arrow slider-arrow">
                                <svg width="18" height="24" viewBox="0 0 18 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="4,4 14,16 4,28" stroke="#080808" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </section>
            @endif

            @if(!isset($userPlan?->plan) && $userPlan?->free_user && $userPlan?->free_user_plan)
                <section class="challenges">
                    <div class="section-header">
                        <h2>My Nutrition Plan</h2>
                        <a href="#" class="see-all">Purchase Plan</a>
                    </div>

                    <div class="slider-container">
                        <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;">
                            @if(isset($userPlan->free_user_plan) && count($userPlan->free_user_plan))
                                @foreach($userPlan->free_user_plan as $plan)
                                    <div class="challenge-card clickable hover-card coming-soon-popup">
                                        @php
                                            $imgSrc = !empty($plan['image']) 
                                                ? (Str::startsWith($plan['image'], 'http') ? $plan['image'] : asset('storage/' . $plan['image']))
                                                : frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1.webp');
                                        @endphp
                                        <img
                                            src="{{ $imgSrc }}"
                                            alt="{{ $plan['name'] }} thumbnail" />
                                        <h3>{{ $plan['name'] }}</h3>
                                        <div class="purchase-plan-overlay">
                                            <button class="purchase-plan-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
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
                                            </button>
                                        </div>
                                        <div class="fade-full"></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            <!-- Challenges -->
            <section class="challenges">
                <div class="section-header">
                    <h2>Challenges</h2>
                    <!-- <a href="/challenges" class="see-all">See all</a> -->
                </div>
                <div class="slider-container">

                <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;">
                    <div class="challenge-card clickable hover-card coming-soon-popup">
                        <img
                            src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1.webp') }}"
                            alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                        <h3>Eat, Snap, Repeat: 3-Day Food Awareness Sprint</h3>

                        @if($userPlan && $userPlan->free_user)
                            <div class="purchase-plan-overlay">
                            <button class="purchase-plan-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
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

                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span>30</span>
                        </div>
                    </div>
                    <div class="challenge-card clickable hover-card coming-soon-popup">
                        <img
                            src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1 (1).webp') }}"
                            alt="Fat Loss Protein and Fats Diet Plan thumbnail" />
                        <h3>Fat VS. Protein quiz: Take this quiz and learn</h3>
                        @if($userPlan && $userPlan->free_user)
                            <div class="purchase-plan-overlay">
                            <button class="purchase-plan-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
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

                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span>10</span>
                        </div>
                    </div>
                    <div class="challenge-card clickable hover-card coming-soon-popup">
                        <img
                            src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1.webp') }}"
                            alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                        <h3>Eat, Snap, Repeat: 3-Day Food Awareness Sprint</h3>
                        @if($userPlan && $userPlan->free_user)
                            <div class="purchase-plan-overlay">
                            <button class="purchase-plan-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
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
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span>30</span>
                        </div>
                    </div>
                     <div class="challenge-card clickable hover-card coming-soon-popup">
                        <img
                            src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1.webp') }}"
                            alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                        <h3>Eat, Snap, Repeat: 3-Day Food Awareness Sprint</h3>
                        @if($userPlan && $userPlan->free_user)
                            <div class="purchase-plan-overlay">
                            <button class="purchase-plan-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
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
                    <!-- <a href="#" class="see-all">See all</a> -->
                </div>
                <div class="resources-custom-grid">
                    <div class="cursor-pointer resource-card-custom resource-supplement hover-card" id="scanner-btn">
                        <img
                            src="{{ frontAssets('images/cardbg.webp') }}"
                            class="resource-bg-img"
                            alt="Supplement scanner background" />
                        <div class="icon-bg">
                            <img
                                src="{{ frontAssets('images/camera.svg') }}"
                                class="resource-bg-img"
                                alt="Camera icon for supplement scanner" />
                        </div>
                        <div class="resource-title">Supplement scanner</div>
                    </div>

                    <div class="cursor-pointer resource-card-custom resource-chat hover-card" id="chat-to-virtual-kez-btn">
                        <img
                            src="{{ frontAssets('images/cardimg-2.webp') }}"
                            class="resource-bg-img"
                            alt="Chat resource background" />
                        <div class="icon-bg">
                            <img
                                src="{{ frontAssets('images/chat.svg') }}"
                                class="resource-bg-img"
                                alt="Chat icon for virtual Kez" />
                        </div>
                        <div class="resource-title">Chat to Virtual Kez</div>
                    </div>
                    <div class="resource-card-custom resource-tip">
                        <div class="tip-title">Kez's Tip of the Day</div>
                        <div class="tip-text">
                            "Focus on proper paddle technique for quicker wave entry."
                        </div>
                    </div>
                </div>
                <div class="resources-custom-grid grid-2">
                    <div class="resource-card-custom resource-video clickable hover-card">
                        <div
                            class="video-thumb-container"
                            onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                            <img
                                src="{{ frontAssets('images/video-bg.webp') }}"
                                class="video-thumb"
                                alt="Video thumbnail for whey protein post-training" />
                            <div class="video-icon-overlay">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="video-title">
                                Understand why whey protein post-training results in better
                                muscle gain
                            </div>
                            <div class="video-meta">
                                <span>
                                    <img
                                        src="{{ frontAssets('images/Clock.webp') }}"
                                        class="clock-img"
                                        alt="Clock icon"
                                        width="16"
                                        height="16" /></span><span>5 min • Video</span>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card-custom resource-video clickable hover-card">
                        <div class="video-thumb-container">
                            <img
                                src="{{ frontAssets('images/gym.webp') }}"
                                class="video-thumb"
                                alt="Gym video thumbnail" />
                        </div>
                        <div class="video-info">
                            <div class="video-title">
                                Understand why whey protein post-training results in better
                                muscle gain
                            </div>
                            <div class="video-meta">
                                <span>
                                    <img
                                        src="{{ frontAssets('images/Clock.webp') }}"
                                        class="clock-img"
                                        alt="Clock icon"
                                        width="16"
                                        height="16" /></span><span>5 min • Video</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Optimize Performance -->
            <section class="optimize-performance">
                <div class="section-header">
                    <h2>Optimise your performance</h2>
                    <a href="#" class="see-all">All Plans</a>
                </div>
                <div class="consults-plans-grid grid-1">
                    <div class="consultation-card-custom">
                        <div class="consult-title"></div>
                        <div class="consult-desc">
                            Get answers from a real-life expert coaching Elite Athletes and
                            Olympians.
                        </div>
                        <div class="consult-user-row">
                            <img
                                src="https://booking.biohealthpassport.com.au/public/uploads/hero01.png"
                                class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" 
                                style="border:none;"/>
                            <span style="padding-left:0">Kerry O'Bryan • 60 min</span>
                        </div>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank" class="text-decoration-none btn-consult">Book consult</a>
                    </div>
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

                <div class="consults-plans-grid">
                    <div class="plan-card-custom plan-competition">
                        <div class="">
                            <div class="plan-title">Competition Plan</div>
                            <div class="plan-desc">
                                Unlock your best performance with a fully customised 24-hour competition day meal
                                plan—designed to fuel you from the night before through recovery, tailored to your sport,
                                your preferences, and your game-day goals.
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ $mealImage1 }}" class="consult-avatar"
                                    alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ $mealImage2 }}" class="consult-avatar overlap1"
                                    alt="Kerry O'Bryan, expert coach avatar" />
                                <span>{{ $mealCount }} meals • 18 Nutrition tips</span>
                            </div>
                            <button class="btn-consult">Learn more</button>
                        </div>
                    </div>
                    <div class="plan-card-custom plan-injury">
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
                            <span>{{ $mealCount }}  meals • 18 Nutrition tips</span>
                        </div>
                        <button class="btn-consult">Learn more</button>
                    </div>
                </div>
            </section>

            <!-- Surfing Videos -->
            <section class="surfing-videos">
                <div class="section-header">
                   <h2>What's hot in... Surfing</h2>
                </div>
               
                <div class="video-grid">
                    <div class="video-card">
                        <div class="video-thumbnail hover-card">
                            <div class="video-player" id="video-player-1">
                                <img
                                    src="{{ frontAssets('images/instaimg1.webp') }}"
                                    alt="Surfing video thumbnail"
                                    class="video-backdrop"
                                    width="372"
                                    height="249" />
                                <video style="display: none">
                                    <source
                                        src="https://www.w3schools.com/html/mov_bbb.mp4"
                                        type="video/mp4" />
                                </video>
                                <button class="play-btn" aria-label="Play video" onclick="playVideoInCard(1)">
                                    <i class="fas fa-play"></i>
                                </button>
                                <label class="insta-text">Watch on Instagram</label>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="top">
                                <img
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=30&h=30&fit=crop&crop=face"
                                    alt="Channel avatar for surfboard_co"
                                    class="channel-avatar" />
                                <div class="channel-name">
                                    <div class="channel-name-main">
                                        <label class="insta-handle-name">surfboard_co</label>
                                        <img src="{{ frontAssets('images/verified.webp') }}" alt="Verified badge" width="16" height="16" />
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
                                    <img src="{{ frontAssets('images/like.webp') }}" alt="Like icon" width="20" height="18" style="width:20px;" />
                                    <span class="likes">892 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="video-card">
                        <div class="video-thumbnail hover-card">
                            <div class="video-player" id="video-player-2">
                                <img
                                    src="{{ frontAssets('images/instaimg2.webp') }}"
                                    alt="Surfing video thumbnail"
                                    class="video-backdrop"
                                    width="372"
                                    height="249" />
                                <video style="display: none">
                                    <source
                                        src="https://www.w3schools.com/html/mov_bbb.mp4"
                                        type="video/mp4" />
                                </video>
                                <button class="play-btn" aria-label="Play video" onclick="playVideoInCard(2)">
                                    <i class="fas fa-play"></i>
                                </button>
                                <label class="insta-text">Watch on Instagram</label>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="top">
                                <img
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=30&h=30&fit=crop&crop=face"
                                    alt="Channel avatar for surfboard_co"
                                    class="channel-avatar" />
                                <div class="channel-name">
                                    <div class="channel-name-main">
                                        <label class="insta-handle-name">surfboard_co</label>
                                        <img src="{{ frontAssets('images/verified.webp') }}" alt="Verified badge" width="16" height="16" />
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
                                    <img src="{{ frontAssets('images/like.webp') }}" alt="Like icon" width="20" height="18" style="width:20px;" />
                                    <span class="likes">892 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="video-card">
                        <div class="video-thumbnail hover-card">
                            <div class="video-player" id="video-player-3">
                                <img
                                    src="{{ frontAssets('images/instaimg1.webp') }}"
                                    alt="Surfing video thumbnail"
                                    class="video-backdrop"
                                    width="372"
                                    height="249" />
                                <video style="display: none">
                                    <source
                                        src="https://www.w3schools.com/html/mov_bbb.mp4"
                                        type="video/mp4" />
                                </video>
                                <button class="play-btn" aria-label="Play video" onclick="playVideoInCard(3)">
                                    <i class="fas fa-play"></i>
                                </button>
                                <label class="insta-text">Watch on Instagram</label>
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="top">
                                <img
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=30&h=30&fit=crop&crop=face"
                                    alt="Channel avatar for surfboard_co"
                                    class="channel-avatar" />
                                <div class="channel-name">
                                    <div class="channel-name-main">
                                        <label class="insta-handle-name">surfboard_co</label>
                                        <img src="{{ frontAssets('images/verified.webp') }}" alt="Verified badge" width="16" height="16" />
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
                                    <img src="{{ frontAssets('images/like.webp') }}" alt="Like icon" width="20" height="18" style="width:20px;" />
                                    <span class="likes">892 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('front.modal.shopping-list')
        @include('front.modal.print-shopping-list')
        @include('front.modal.meal-detail')
        @include('front.modal.smart-swap')
        @include('front.modal.smart-swap-items')
    </main>
    
    <script>
        const user = @json($userPlan);
        const userId = user.user_id;
        const isFreeUser = user.free_user;
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
                scrollContainer.scrollBy({ left: -scrollAmount, behavior: "smooth" });
            });

            rightArrow.addEventListener("click", () => {
                scrollContainer.scrollBy({ left: scrollAmount, behavior: "smooth" });
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

            $('#start-chat-link, #chat-to-virtual-kez-btn').click(function(){
                $('#delphi-bubble-trigger').click();
            });

            $('#scanner-btn').click(function(){
                location.href = "https://phenomenal-torrone-cee914.netlify.app/";
            });

            document.querySelectorAll('.coming-soon-popup').forEach(function(card) {
                card.addEventListener('click', function(e) {
                    var comingSoonModal = document.getElementById('comingSoonModal');
                    if (comingSoonModal && typeof bootstrap !== 'undefined') {
                        e.preventDefault();
                        var modal = new bootstrap.Modal(comingSoonModal);
                        modal.show();
                    }
                });
            });
        });
        
        function showLoader() {
            $('#loader').removeClass('d-none');
        }
        function hideLoader() {
            $('#loader').addClass('d-none');
        }

        $(document).ready(function () {
            // Open Bootstrap modal on meal click
            $('body').on('click', '.quick-view-btn', function () {
                if(isFreeUser == 1) {
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
                    success: function (response) {
                        const meal = response.meal;

                        // 🖼️ Set meal title and description
                        $('#recipeDialogModal .modal-body .dialog-header h2').text(meal.meal.title || 'Meal');
                        $('#recipeDialogModal .modal-body .dialog-header p').text(meal.meal.description || '');

                        const imageUrl = meal.meal.image
                            ? `{{ asset('storage') }}/` + meal.meal.image
                            : `{{ asset('front/images/placeholder.png') }}`;
                        $('#recipeDialogModal .modal-body .dialog-img').attr('src', imageUrl);

                        // 🥣 Ingredients
                        let ingredientsHtml = '';
                        meal.user_items.forEach(function (userItem) {
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

                            ingredientsHtml += `<li>${qty}${space}${unit} ${item.title}</li>`;
                        });

                        if(response.isFreeUser) {
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
                            $('#recipeDialogModal .modal-body h3:contains("Instructions")').show();
                        } else {
                            $('#recipeDialogModal .modal-body .note').hide();
                            $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
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
                        const modal = new bootstrap.Modal(document.getElementById('recipeDialogModal'));
                        modal.show();
                        hideLoader();
                    },
                    error: function () {
                        $('#errormodalmain').modal('show');
                        hideLoader();
                    }
                });
            });
       
            $(document).on('hide.bs.modal', '#recipeDialogModal', function () {
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

            $(document).on('click', '.meal-item-btn', function () {
                const $btn = $(this);

                const meal_id = $btn.attr('data-meal-id');
                const meal_name = $btn.attr('data-meal-name');
                const user_meal_id = $btn.attr('data-meal-id');
                const userPlanId = $btn.attr('data-user-plan-id');
                const userSubCategoryId = $btn.attr('data-sub-category-id');
                const userCategoryId = $btn.attr('data-category-id');

                $('#recipeDialogModal').modal('hide');
                mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId);
            });

            function mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId) {
                const modalEl = $('#mealItemModel');
                const modal = new bootstrap.Modal(modalEl[0]);
                modal.show();

                const $mealItemsModalLabel = $('.swap-title'); // Set meal name here
                const $mealItemsContainer = $('.swap-list'); // Container for item cards
                const $mealItemsLoadingSpinner = $('#mealItemsLoadingSpinner'); // Optional: add loading spinner if you want

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
                    success: function (data) {
                        if (data.items && data.items.length > 0) {
                            $.each(data.items, function (index, item) {
                                let displayQty = '';
                                let selectedUnits = [];

                                try {
                                    selectedUnits = typeof item.selected_qty_unit === 'string'
                                        ? JSON.parse(item.selected_qty_unit)
                                        : Array.isArray(item.selected_qty_unit)
                                            ? item.selected_qty_unit
                                            : [];
                                } catch (e) {
                                    console.warn('Failed to parse selected_qty_unit for item:', item.name, e);
                                }

                                const checkedUnits = selectedUnits.filter(u =>
                                    u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                                );

                                if (checkedUnits.length > 0) {
                                    const formattedUnits = checkedUnits.map(u => {
                                        let qtyText = u.qty?.toString().trim() || '';
                                        const unitText = (u.unit || '').trim();
                                        const needsSpace = !["g", "ml", "mL"].includes(unitText.toLowerCase());

                                        const numericQty = Number(qtyText);
                                        if (!isNaN(numericQty)) {
                                            qtyText = numericQty % 1 === 0 ? numericQty.toFixed(0) : numericQty.toFixed(1);
                                        }

                                        return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                }

                                if (!displayQty && item.qty && item.unit) {
                                    const unit = item.unit.toString();
                                    const needsSpace = !["g", "ml", "mL"].includes(unit.toLowerCase());
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
                                                    <span>Smart swap</span>
                                                </button>` : ''}
                                            ${item.description ? `
                                                <button class="smart-swap-btn" data-bs-toggle="tooltip" title="${item.description}">
                                                    <img src="{{ frontAssets('images/dialog/Info.svg') }}" alt="Info" style="width: 18px; vertical-align: middle" />
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
                            $mealItemsContainer.html('<p class="text-center">No foods available in this meal.</p>');
                        }

                        // Optional: hide spinner
                        // $mealItemsLoadingSpinner.hide();
                    },
                    error: function () {
                        $mealItemsContainer.html('<p class="text-danger text-center">Failed to load foods.</p>');
                        // $mealItemsLoadingSpinner.hide();
                    }
                });
            }

            $(document).on('click', '.meal-item-modal-close', function () {
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

            $(document).on('click', '.item-swap-btn', function () {
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
                    success: function (data) {
                        if (!data || !data.items || !data.items.length) {
                            $swapList.html('<p class="text-muted text-center">No swap items available.</p>');
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
                        const mainQtyText = formatQtyUnit(item.selected_qty_unit, item.qty, item.unit);
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
                        data.items.forEach(function (swapItem) {
                            const swapQtyText = formatQtyUnit(swapItem.selected_qty_unit, swapItem.swap_item_qty, swapItem.swap_item_unit);

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
                    error: function (xhr, status, error) {
                        $swapList.html('<p class="text-danger text-center">Failed to load swap items.</p>');
                        console.error('Error loading swap items:', error);
                    }
                });

            });

            $('#mealItemModel').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $('#mealItemsContainer').empty();
                $('#mealItemsLoadingSpinner').hide();
            });


            let currentMainItem = null;
            let swaps = []; // Array to hold latest swap pair

            $(document).on('click', '.swap-btn', function () {
                const $clickedSwap = $(this).closest('.swap-item');
                const swapItemId = $(this).data('swap-item-id');

                const $mainItem = $('#mainSwapItem'); // ✅ SELECTS THE MAIN ITEM CORRECTLY NOW
                const mainItemId = $mainItem.data('item-id'); // You must set this in HTML: data-item-id="${item.id}"

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

            $('#smartSwapModal').on('hidden.bs.modal', function () {
                currentMainItem = null;
                swaps = []; // Reset swaps array
                $('#smartSwapModalLabel').text(''); // Clear modal title
                $('#smartSwapModal .swap-list').empty(); // Clear HTML inside modal
                $('.modal-backdrop').remove();
            });

            // Apply Swap Changes functionality
            $(document).on('click', '.apply-changes-btn', function () {
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
                    success: function (response) {
                        // Handle success response
                        swaps = [];
                        currentMainItem = null;

                        if(response.success){
                            $('#smartSwapModal').modal('hide');
                            var meal_id = response.data['meal_id'];
                            var meal_name = response.data['meal_name'];
                            var user_meal_id = response.data['user_meal_id'];
                            mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId);
                        } else {
                            $('#errormodalmain .modal-body').html(`<h4>Ooops!</h4><p>${response.message}</p>`);
                            $('#errormodalmain').modal('show');
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        if (xhr.status === 422) {
                            // Laravel-style validation error handling
                            let errors = xhr.responseJSON?.errors;
                            let messageHtml = '';

                            if (errors) {
                                // fallback if error messages not formatted
                                messageHtml = '<h4>Ooops!</h4><p>Invalid swap. Please check and try again.</p>';
                            }

                            $('#errormodalmain .modal-body').html(messageHtml);
                        } else {
                            // Generic fallback for other HTTP errors
                            $('#errormodalmain .modal-body').html('<h4>Ooops!</h4>	<p>Invalid swap. Please try again later.</p>');
                        }

                        $('#errormodalmain').modal('show');
                    }
                });
            });
        });

    </script>
@endsection
