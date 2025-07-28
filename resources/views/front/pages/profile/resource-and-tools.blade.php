@extends(frontView('layouts.app'))

@section('title', 'Best Sports Nutritionist & Dietitians Australia | Kerry O’Bryan')
@section('meta_description',
    'Performance Health Support offers expert care from top sports nutritionists, strength
    coaches, and sports dietitians in Australia to boost health and performance.')

@section('content')
   <!-- Main Content -->
   <main class="main">
        <div class="container">


            <!-- Resources and Tools -->
            <section class="resources" style="margin-top: 56px;">
                <div class="section-header">
                    <h2>Resources and tools</h2>
                    <a href="#" class="see-all">See all</a>
                </div>
                <div class="resources-custom-grid">
                    <div class="resource-card-custom resource-supplement">
                        <img src="images/supplement.png" class="resource-bg-img" alt="Supplement scanner background" />
                        <div class="icon-bg">
                            <img src="images/camera.svg" class="resource-bg-img"
                                alt="Camera icon for supplement scanner" />
                        </div>
                        <div class="resource-title">Supplement scanner</div>
                    </div>

                    <div class="resource-card-custom resource-chat">
                        <img src="images/kez.png" class="resource-bg-img" alt="Chat resource background" />
                        <div class="icon-bg">
                            <img src="images/chat.svg" class="resource-bg-img" alt="Chat icon for virtual Kez" />
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

            </section>






            <section class="training-plan" style="margin-bottom: 0;">
                <div class="section-header">
                    <h2>Level-Up Library</h2>
                    <a href="/training-plan" class="see-all">See all</a>
                </div>
                <div class="slider-wrapper" style="position:relative;">

                    <div class="challenge-cards horizontal-scroll tabs"
                        style="overflow-x:auto;scroll-behavior:smooth;" id="meal-cards-wrapper">
                        <button class="tab active" data-tab="All content" aria-label="All content">All
                            content</button>
                        <button class="tab" data-tab="Training" aria-label="Training">Training</button>
                        <button class="tab" data-tab="Supplements" aria-label="Supplements">Supplements</button>

                    </div>
                    <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;"
                        id="meal-cards-wrapper">
                        <div class="resources-custom-grid grid-2">
                            <div class="resource-card-custom resource-video">
                                <div class="video-thumb-container"
                                    onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                                    <img src="images/video-bg.webp" class="video-thumb"
                                        alt="Video thumbnail for whey protein post-training" />
                                    <div class="video-icon-overlay">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                            viewBox="0 0 38 38" fill="none">
                                            <path
                                                d="M19 0C8.52 0 0 8.52 0 19C0 29.48 8.52 38 19 38C29.48 38 38 29.48 38 19C38 8.52 29.48 0 19 0ZM25.88 20.12L15.64 26.92C14.76 27.52 13.6 26.88 13.6 25.8V12.16C13.6 11.12 14.76 10.48 15.64 11.04L25.88 17.88C26.68 18.44 26.68 19.56 25.88 20.12Z"
                                                fill="white" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <div class="video-title">
                                      Understand why whey protein post-training results in better muscle gain 
                                    </div>
                                    <div class="video-meta">
                                        <span>
                                            <img src="images/Clock.webp" class="clock-img" alt="Clock icon"
                                                width="16" height="16" /></span><span>5 min • Video</span>
                                    </div>
                                </div>
                            </div>
                            <div class="resource-card-custom resource-video">
                                <div class="video-thumb-container"
                                    onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                                    <img src="images/second.png" class="video-thumb"
                                        alt="Video thumbnail for whey protein post-training" />
                                    <div class="video-icon-overlay">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                            viewBox="0 0 38 38" fill="none">
                                            <path
                                                d="M19 0C8.52 0 0 8.52 0 19C0 29.48 8.52 38 19 38C29.48 38 38 29.48 38 19C38 8.52 29.48 0 19 0ZM25.88 20.12L15.64 26.92C14.76 27.52 13.6 26.88 13.6 25.8V12.16C13.6 11.12 14.76 10.48 15.64 11.04L25.88 17.88C26.68 18.44 26.68 19.56 25.88 20.12Z"
                                                fill="white" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <div class="video-title">
                                       Your performance can increase by 8% when eating right - here’s why
                                    </div>
                                    <div class="video-meta">
                                        <span>
                                            <img src="images/Clock.webp" class="clock-img" alt="Clock icon"
                                                width="16" height="16" /></span><span>5 min • Video</span>
                                    </div>
                                </div>
                            </div>

                           <div class="resource-card-custom resource-video">
                                <div class="video-thumb-container"
                                    onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                                    <img src="images/third.png" class="video-thumb"
                                        alt="Video thumbnail for whey protein post-training" />
                                    <div class="video-icon-overlay">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                            viewBox="0 0 38 38" fill="none">
                                            <path
                                                d="M19 0C8.52 0 0 8.52 0 19C0 29.48 8.52 38 19 38C29.48 38 38 29.48 38 19C38 8.52 29.48 0 19 0ZM25.88 20.12L15.64 26.92C14.76 27.52 13.6 26.88 13.6 25.8V12.16C13.6 11.12 14.76 10.48 15.64 11.04L25.88 17.88C26.68 18.44 26.68 19.56 25.88 20.12Z"
                                                fill="white" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <div class="video-title">
                                       What supplement brands should choose trustworthy?
                                    </div>
                                    <div class="video-meta">
                                        <span>
                                            <img src="images/Clock.webp" class="clock-img" alt="Clock icon"
                                                width="16" height="16" /></span><span>5 min • Video</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </main> 
@endsection