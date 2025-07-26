@extends(frontView('layouts.app'))

@section('title', 'Best Sports Nutritionist & Dietitians Australia | Kerry O’Bryan')
@section('meta_description', 'Performance Health Support offers expert care from top sports nutritionists, strength coaches, and sports dietitians in Australia to boost health and performance.')

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
              
                  

    <div class="challenge-cards horizontal-scroll" id="meal-cards-wrapper">
        <p>Loading meals...</p>
    </div>



                <!-- <div class="position-relative challenge-cards horizontal-scroll" id="meal-cards-wrapper">
                    <p>Loading meals...</p>
                </div> -->
            </div>
        </section>

        @endif
        <!-- Challenges -->
        <section class="challenges">
            <div class="section-header">
                <h2>Challenges</h2>
                <!-- <a href="/challenges" class="see-all">See all</a> -->
            </div>
            <div class="challenge-cards horizontal-scroll">
             
                <div class="challenge-card clickable hover-card coming-soon-popup">
                    <img
                        src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1.webp') }}"
                        alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                    <h3>Eat, Snap, Repeat: 3-Day Food Awareness Sprint</h3>
                   
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
                    
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <span>30</span>
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
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                            class="consult-avatar"
                            alt="Kerry O'Bryan, expert coach avatar" />
                        <span style="padding-left:0">Kerry O'Bryan • 60 min</span>
                    </div>
                    <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank" class="text-decoration-none btn-consult">Book consult</a>
                </div>
            </div>
            <div class="consults-plans-grid">
                <div class="plan-card-custom plan-competition">
                    <div class="">
                        <div class="plan-title">Competition Plan</div>
                        <div class="plan-desc">
                            Unlock your best performance with a fully customised 24-hour competition day meal plan—designed to fuel you from the night before through recovery, tailored to your sport, your preferences, and your game-day goals.
                        </div>
                    </div>
                    <div class="">
                        <div class="consult-user-row">
                            <img
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                                class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" />
                            <img
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                                class="consult-avatar overlap1"
                                alt="Kerry O'Bryan, expert coach avatar" />
                            <span>21 meals • 18 Nutrition tips</span>
                        </div>
                        <!-- <div class="plan-meta">
                            <i class="fa-solid fa-utensils"></i> 21 meals • 18 Nutrition
                            tips
                        </div> -->
                        <button class="btn-consult">Learn more</button>
                    </div>
                </div>
                <div class="plan-card-custom plan-injury">
                    <div class="plan-title">Injury</div>
                    <div class="plan-desc">
                        Add the Injury Recovery Upgrade to your Sports Training Plan—a targeted selection of healing-focused meals, expert tips, and supplement guidance to accelerate recovery, reduce inflammation, and get you back to full strength, faster—all built on a food-first approach.
                    </div>
                    <div class="consult-user-row">
                        <img
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                            class="consult-avatar"
                            alt="Kerry O'Bryan, expert coach avatar" />
                        <img
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                            class="consult-avatar overlap1"
                            alt="Kerry O'Bryan, expert coach avatar" />
                        <span>21 meals • 18 Nutrition tips</span>
                    </div>
                    <button class="btn-consult">Learn more</button>
                </div>
            </div>
        </section>

        <!-- Surfing Videos -->
        <section class="surfing-videos">
            <h2>What's hot in... Surfing</h2>
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
</main>

<script>
    const getMealsRoute = @json(route('front.get-profile-meals', ['plan' => 'PLAN_ID', 'category' => 'CATEGORY_ID']));
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');
        const contentWrapper = document.getElementById('meal-cards-wrapper');

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
</script>


<!-- Coming Soon Modal -->
<div class="modal" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonLabel" aria-hidden="true">
    <div class="modal-dialog modal-confirm modal-coming-soon modal-dialog-centered">
        <div class="modal-content">
            <div class="justify-content-center modal-header">
                <div class="icon-box">
                    <i class="fas fa-clock"></i>
                </div>
                <button class="dialog-close" style="top: -20px; right: -20px;" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z" fill="#3B3B3B"/>
                    </svg>
                </button>
            </div>
            <div class="text-center modal-body">
                <h4>Coming Soon!</h4>
                <p>This feature is coming soon.</p>
            </div>
        </div>
    </div>
</div>

@endsection