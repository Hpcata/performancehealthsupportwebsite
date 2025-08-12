@extends(frontView('layouts.app'))

@section('title', 'My Plans | Performance Health Support')
@section('meta_description',
    'Explore and manage your personalised nutrition and training plans with Performance Health Support. Achieve your health and performance goals with expert guidance from Australia’s leading sports nutritionists and coaches.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/profile-my-plan.css') }}">
@endpush

@section('content')

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Resources and Tools -->
            <section class="resources my-plans-main">
                <div class="section-header">
                    <h2>My Plans</h2>
                </div>
                <div class="consults-plans-grid">
                    <div class="no-plan-container">
                        <img src="{{ asset('front/images/my-plan/vector.svg') }}" alt="No Plan Yet" class="no-plan-image" />
                        <h2 class="no-plan-title">Uh-oh! You don't have a plan yet.</h2>
                        <p class="no-plan-description">
                            Get ahead of your competition by signing up for a plan below.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Recommended plan -->
            <section class="optimize-performance x">
                <div class="section-header">
                    <h2>Recommended plan</h2>
                </div>

                <div class="card-row">
                    <label class="choose-plan-label web-hidden">Nutrition plans</label>
                    <div class="plan-cards-wrap">
                        <div class="plan-card">
                            <div class="plan-card-wrapper">
                                <div class="plan-icon">
                                    <img src="{{ asset('front/images/my-plan/fitness.svg') }}" alt="No Plan Yet" class="    " />
                                </div>
                                <h3 class="card-title">Training Nutrition Plan</h3>
                                <p class="card-text">
                                    Optimise your training gains by eating with purpose. Perform
                                    at your peak with a personalised meal plan tailored to you &
                                    your preferences - designed by Extreme Sports Dietitian Kerry
                                    O'Bryan.
                                </p>
                            </div>
                            <a href="/training-nutrition-plan" class="btn-learn-more">Learn more</a>
                        </div>
                        <div class="plan-card">
                            <div class="plan-card-wrapper">
                                <div class="plan-icon">
                                    <img src="{{ asset('front/images/my-plan/Game winner trophy.svg') }}" alt="No Plan Yet" class=" " />
                                </div>
                                <h3 class="card-title">Competition Plan</h3>
                                <p class="card-text">
                                    Unlock your peak performance with a 24-hour Competition Nutrition Plan - Ensuring
                                    you’re hydrated, fuelled & ON when it’s game time so that nutrition is never your
                                    weakness!
                                </p>
                            </div>
                            <button class="btn-learn-more" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                        </div>
                        <div class="plan-card ">
                            <div class="plan-card-wrapper">
                                <div class="plan-icon">
                                    <img src="{{ asset('front/images/my-plan/health insurance.svg') }}" alt="No Plan Yet" class="   " />
                                </div>
                                <h3 class="card-title">Injury & Recovery Nutrition Plan</h3>
                                <p class="card-text">
                                    Optimised nutrition to support soft tissue injury. Hold muscle, reduce inflammation
                                    & limit fat gain with a personalised plan that caters to where you're at. Faster
                                    recovery is the goal & nutrition is too often overlooked!
                                </p>
                            </div>
                            <button class="btn-learn-more" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                        </div>
                    </div>
                    <label class="choose-plan-label web-hidden">Consults</label>
                    <div class="plan-cards-wrap">
                        <div class="plan-card mobile-hidden">
                            <div class="plan-card-wrapper">
                                <div class="plan-icon">
                                    <img src="{{ asset('front/images/my-plan/Bed.svg') }}" alt="No Plan Yet" class="    " />
                                </div>
                                <h3 class="card-title">Pre & Post Surgery Nutrition Plan</h3>
                                <p class="card-text">
                                    Poor nutritional status before surgery will delay your recovery. The Pre & Post
                                    Surgery Nutrition Plan will ensure you are well organised with specific food, snacks
                                    & supplements that will speed up healing, hold muscle, limit fat gain & get you back
                                    in the game!
                                </p>
                            </div>
                            <button class="btn-learn-more"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                        </div>
                        <div class="plan-card orange-card">
                            <div class="plan-card-wrapper">
                                <div class="plan-icon">
                                    <img src="{{ asset('front/images/my-plan/Speak.svg') }}" alt="No Plan Yet" class="  " />
                                </div>
                                <h3 class="card-title">Consultations (1 on 1)</h3>
                                <p class="card-text">
                                    An in-depth session to review your current approach, identify key opportunities, and
                                    give you practical, tailored strategies to reach your sporting goals. Get expert
                                    support that meets you where you’re at, with relevant education and answers to the
                                    questions that matter most.
                                </p>
                            </div>
                            <button class="btn-learn-more coming-soon-popup">Learn more</button>
                        </div>
                        <div class="plan-card white-card">
                            <div class="plan-card-wrapper">
                                <div class="plan-icon">
                                    <img src="{{ asset('front/images/my-plan/Team.svg') }}" alt="No Plan Yet" class="   " />
                                </div>
                                <h3 class="card-title">Clubs and Group bookings</h3>
                                <p class="card-text">
                                    Want to share the knowledge with the rest of your sports club? Contact us for club
                                    deals and group bookings.
                                </p>
                            </div>
                            <button class="btn-learn-more" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('.coming-soon-popup').click(function() {
                $('#comingSoonModal').modal('show');
            });
        });
             // Learn more tooltip functionality
        function showLearnMoreTooltip(button, planType) {
            // Remove any existing learn more tooltips
            const existingTooltip = document.querySelector('.learn-more-tooltip');
            if (existingTooltip) {
                existingTooltip.remove();
            }

            // Create tooltip element
            const tooltip = document.createElement('div');
            tooltip.className = 'learn-more-tooltip';
            tooltip.textContent = `${planType} `;

            // Position tooltip above the button
            const buttonRect = button.getBoundingClientRect();
            tooltip.style.position = 'fixed';
            tooltip.style.top = (buttonRect.top - 45) + 'px';
            tooltip.style.left = (buttonRect.left + buttonRect.width / 2 - 80) + 'px';
            tooltip.style.zIndex = '9999';

            // Add tooltip to body
            document.body.appendChild(tooltip);

            // Auto-hide tooltip after 3 seconds
            setTimeout(() => {
                const tooltipToRemove = document.querySelector('.learn-more-tooltip');
                if (tooltipToRemove) {
                    tooltipToRemove.remove();
                }
            }, 3000);
        }

        // Add CSS for tooltip
        const tooltipStyle = document.createElement('style');
        tooltipStyle.textContent = `
            .coming-soon-tooltip {
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: tooltipFadeIn 0.3s ease-out;
            white-space: nowrap;
          }

          .coming-soon-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #333;
          }

          .learn-more-tooltip {
             background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: tooltipFadeIn 0.3s ease-out;
            white-space: nowrap;
          }

          .learn-more-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #333;
          }

          @keyframes tooltipFadeIn {
            from {
              opacity: 0;
              transform: translateY(20px);
            }
            to {
              opacity: 1;
              transform: translateY(0);
            }
          }
            `;
        document.head.appendChild(tooltipStyle);
    </script>
@endsection
