@extends(frontView('layouts.app'))

@section('title', 'My Plans | Performance Health Support')
@section('meta_description',
'Explore and manage your personalised nutrition and training plans with Performance Health Support. Achieve your health
and performance goals with expert guidance from Australia’s leading sports nutritionists and coaches.')

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
            <div class="card-row">
                <label class="choose-plan-label" style="margin-bottom:8px;">Recommended plan</label>
                <div class="plan-cards-wrap">
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                            <div class="plan-title">Training Nutrition Plan</div>
                            <div class="plan-desc">
                                Optimise your training gains by eating with purpose. Perform at your peak with a
                                personalised meal plan tailored to you & your preferences - designed by Extreme Sports
                                Dietitian Kerry O’Bryan.
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                    class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ asset('front/images/circled-meal-2.svg') }}"
                                    class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />
                                <span>12 meals</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="window.location.href='{{ route('front.training.nutrition.plan') }}'">Learn
                            more</button>
                    </div>
                </div>
            </div>

            <div class="section-header">
                <h2>All plans</h2>
            </div>

            <div class="card-row">
                <label class="choose-plan-label" style="margin-bottom:8px;">Nutrition plans</label>
                <div class="plan-cards-wrap">
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                            <div class="plan-title">Competition Plan</div>
                            <div class="plan-desc">
                                Unlock your peak performance with a 24-hour Competition Nutrition Plan - Ensuring you’re
                                hydrated, fuelled & ON when it’s game time so that nutrition is never your weakness!
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                    class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ asset('front/images/circled-meal-2.svg') }}"
                                    class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />
                                <span>21 meals customised for you</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn
                            more</button>
                    </div>
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                            <div class="plan-title">Injury & Recovery Plan</div>
                            <div class="plan-desc">
                                Optimised nutrition to support soft tissue injury. Hold muscle, reduce
                                inflammation & limit fat gain with a
                                personalised plan that caters to where you're at. Faster recovery is the goal &
                                nutrition is too often overlooked!
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                    class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ asset('front/images/circled-meal-2.svg') }}"
                                    class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />
                                <span>21 meals customised for you</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn
                            more</button>
                    </div>
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                            <div class="plan-title">Pre & Post Surgery Plan</div>
                            <div class="plan-desc">
                                Poor nutritional status before surgery will delay your recovery. The Pre & Post Surgery
                                Nutrition Plan will ensure you are well organised with specific food, snacks &
                                supplements that will speed up healing, hold muscle, limit fat gain & get you back in
                                the game!
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                    class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ asset('front/images/circled-meal-2.svg') }}"
                                    class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />
                                <span>21 meals customised for you</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn
                            more</button>
                    </div>
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                            <div class="plan-title">Pre & Post Surgery Plan</div>
                            <div class="plan-desc">
                                Poor nutritional status before surgery will delay your recovery. The Pre & Post Surgery
                                Nutrition Plan will ensure you are well organised with specific food, snacks &
                                supplements that will speed up healing, hold muscle, limit fat gain & get you back in
                                the game!
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                    class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ asset('front/images/circled-meal-2.svg') }}"
                                    class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />
                                <span>21 meals customised for you</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn
                            more</button>
                    </div>
                </div>
            </div>

            <div class="card-row">
                <label class="choose-plan-label" style="margin-bottom:8px;">Consultations</label>
                <div class="plan-cards-wrap">
                    <div class="plan-card orange-card">
                        <div class="plan-card-wrapper">

                            <h3 class="card-title">Private consultations</h3>
                            <p class="card-text">
                                Get answers from a real-life expert coaching Elite Athletes and Olympians.
                                An in-depth session to review your current approach, identify key opportunities, and
                                give you practical, tailored strategies to reach your sporting goals. Get expert support
                                that meets you where you’re at, with relevant education and answers to the questions
                                that matter most.
                            </p>
                        </div>
                        <div class="consult-user-row">
                            <img src="{{ asset('front/images/virtual kez.webp') }}" class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" />

                            <span style="padding-left:0;">Kerry O’Bryan • 60 min</span>
                        </div>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank"
                            class="btn-learn-more ">Learn more</a>
                    </div>
                </div>
            </div>

        <!-- White colored cards uncomment and use where it need to be integrated -->
            <!-- <div class="card-row">
                <div class="plan-cards-wrap">
                    <div class="plan-card transparent-card">
                        <div class="plan-card-wrapper">
                            <div class="transparent-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M10.0007 10.8377C9.74694 10.8347 9.49841 10.9095 9.28852 11.0521C9.07863 11.1946 8.91744 11.3981 8.82666 11.635C8.73588 11.8719 8.71987 12.131 8.78077 12.3773C8.84168 12.6236 8.97658 12.8453 9.16732 13.0127V14.171C9.16732 14.392 9.25512 14.604 9.4114 14.7603C9.56768 14.9165 9.77964 15.0043 10.0007 15.0043C10.2217 15.0043 10.4336 14.9165 10.5899 14.7603C10.7462 14.604 10.834 14.392 10.834 14.171V13.0127C11.0247 12.8453 11.1596 12.6236 11.2205 12.3773C11.2814 12.131 11.2654 11.8719 11.1746 11.635C11.0839 11.3981 10.9227 11.1946 10.7128 11.0521C10.5029 10.9095 10.2544 10.8347 10.0007 10.8377ZM14.1673 7.50433H7.50065V5.83766C7.49944 5.34282 7.6451 4.85874 7.9192 4.44675C8.1933 4.03475 8.5835 3.71337 9.04039 3.52329C9.49727 3.33322 10.0003 3.28302 10.4857 3.37903C10.9712 3.47505 11.4172 3.71297 11.7673 4.06266C12.0806 4.38272 12.3047 4.77918 12.4173 5.21266C12.4447 5.31881 12.4927 5.41854 12.5586 5.50614C12.6245 5.59374 12.707 5.6675 12.8014 5.72322C12.8958 5.77894 13.0002 5.81551 13.1088 5.83086C13.2173 5.8462 13.3278 5.84002 13.434 5.81266C13.5401 5.7853 13.6399 5.7373 13.7275 5.6714C13.8151 5.60551 13.8888 5.523 13.9445 5.42859C14.0003 5.33419 14.0368 5.22973 14.0522 5.12119C14.0675 5.01265 14.0613 4.90215 14.034 4.79599C13.8441 4.075 13.4676 3.41677 12.9423 2.88766C12.3591 2.3063 11.6168 1.91077 10.8089 1.75103C10.0011 1.59129 9.1641 1.67449 8.40353 1.99012C7.64297 2.30576 6.99299 2.83969 6.53566 3.52447C6.07833 4.20926 5.83416 5.0142 5.83398 5.83766V7.50433C5.17094 7.50433 4.53506 7.76772 4.06622 8.23656C3.59738 8.7054 3.33398 9.34129 3.33398 10.0043V15.8377C3.33398 16.5007 3.59738 17.1366 4.06622 17.6054C4.53506 18.0743 5.17094 18.3377 5.83398 18.3377H14.1673C14.8304 18.3377 15.4662 18.0743 15.9351 17.6054C16.4039 17.1366 16.6673 16.5007 16.6673 15.8377V10.0043C16.6673 9.34129 16.4039 8.7054 15.9351 8.23656C15.4662 7.76772 14.8304 7.50433 14.1673 7.50433ZM15.0007 15.8377C15.0007 16.0587 14.9129 16.2706 14.7566 16.4269C14.6003 16.5832 14.3883 16.671 14.1673 16.671H5.83398C5.61297 16.671 5.40101 16.5832 5.24473 16.4269C5.08845 16.2706 5.00065 16.0587 5.00065 15.8377V10.0043C5.00065 9.78331 5.08845 9.57135 5.24473 9.41507C5.40101 9.25879 5.61297 9.17099 5.83398 9.17099H14.1673C14.3883 9.17099 14.6003 9.25879 14.7566 9.41507C14.9129 9.57135 15.0007 9.78331 15.0007 10.0043V15.8377Z"
                                        fill="#080808" />
                                </svg>
                                <h3 class="card-title">Training Nutrition Plan</h3>
                            </div>
                            <p class="card-text">
                                Optimise your training gains by eating with purpose. Perform at your peak with a
                                personalised meal plan tailored to you & your preferences - designed by Extreme Sports
                                Dietitian Kerry O’Bryan.
                            </p>
                        </div>
                        <div class="consult-user-row">
                            <img src="{{ asset('front/images/circled-meal-1.svg') }}" class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" />
                            <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />

                            <span style="padding-left:0;">21 meals customised for you</span>
                        </div>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank"
                            class="btn-learn-more ">View plan</a>
                    </div>
                </div>

                <div class="plan-cards-wrap">
                    <div class="plan-card transparent-card">
                        <div class="plan-card-wrapper">
                            <div class="transparent-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M10.0007 10.8377C9.74694 10.8347 9.49841 10.9095 9.28852 11.0521C9.07863 11.1946 8.91744 11.3981 8.82666 11.635C8.73588 11.8719 8.71987 12.131 8.78077 12.3773C8.84168 12.6236 8.97658 12.8453 9.16732 13.0127V14.171C9.16732 14.392 9.25512 14.604 9.4114 14.7603C9.56768 14.9165 9.77964 15.0043 10.0007 15.0043C10.2217 15.0043 10.4336 14.9165 10.5899 14.7603C10.7462 14.604 10.834 14.392 10.834 14.171V13.0127C11.0247 12.8453 11.1596 12.6236 11.2205 12.3773C11.2814 12.131 11.2654 11.8719 11.1746 11.635C11.0839 11.3981 10.9227 11.1946 10.7128 11.0521C10.5029 10.9095 10.2544 10.8347 10.0007 10.8377ZM14.1673 7.50433H7.50065V5.83766C7.49944 5.34282 7.6451 4.85874 7.9192 4.44675C8.1933 4.03475 8.5835 3.71337 9.04039 3.52329C9.49727 3.33322 10.0003 3.28302 10.4857 3.37903C10.9712 3.47505 11.4172 3.71297 11.7673 4.06266C12.0806 4.38272 12.3047 4.77918 12.4173 5.21266C12.4447 5.31881 12.4927 5.41854 12.5586 5.50614C12.6245 5.59374 12.707 5.6675 12.8014 5.72322C12.8958 5.77894 13.0002 5.81551 13.1088 5.83086C13.2173 5.8462 13.3278 5.84002 13.434 5.81266C13.5401 5.7853 13.6399 5.7373 13.7275 5.6714C13.8151 5.60551 13.8888 5.523 13.9445 5.42859C14.0003 5.33419 14.0368 5.22973 14.0522 5.12119C14.0675 5.01265 14.0613 4.90215 14.034 4.79599C13.8441 4.075 13.4676 3.41677 12.9423 2.88766C12.3591 2.3063 11.6168 1.91077 10.8089 1.75103C10.0011 1.59129 9.1641 1.67449 8.40353 1.99012C7.64297 2.30576 6.99299 2.83969 6.53566 3.52447C6.07833 4.20926 5.83416 5.0142 5.83398 5.83766V7.50433C5.17094 7.50433 4.53506 7.76772 4.06622 8.23656C3.59738 8.7054 3.33398 9.34129 3.33398 10.0043V15.8377C3.33398 16.5007 3.59738 17.1366 4.06622 17.6054C4.53506 18.0743 5.17094 18.3377 5.83398 18.3377H14.1673C14.8304 18.3377 15.4662 18.0743 15.9351 17.6054C16.4039 17.1366 16.6673 16.5007 16.6673 15.8377V10.0043C16.6673 9.34129 16.4039 8.7054 15.9351 8.23656C15.4662 7.76772 14.8304 7.50433 14.1673 7.50433ZM15.0007 15.8377C15.0007 16.0587 14.9129 16.2706 14.7566 16.4269C14.6003 16.5832 14.3883 16.671 14.1673 16.671H5.83398C5.61297 16.671 5.40101 16.5832 5.24473 16.4269C5.08845 16.2706 5.00065 16.0587 5.00065 15.8377V10.0043C5.00065 9.78331 5.08845 9.57135 5.24473 9.41507C5.40101 9.25879 5.61297 9.17099 5.83398 9.17099H14.1673C14.3883 9.17099 14.6003 9.25879 14.7566 9.41507C14.9129 9.57135 15.0007 9.78331 15.0007 10.0043V15.8377Z"
                                        fill="#080808" />
                                </svg>
                                <h3 class="card-title">Competition Plan</h3>
                            </div>
                            <p class="card-text">
                                Unlock your peak performance with a 24-hour Competition Nutrition Plan - Ensuring you’re
                                hydrated, fuelled & ON when it’s game time so that nutrition is never your weakness!
                            </p>
                        </div>
                        <div class="consult-user-row">
                            <img src="{{ asset('front/images/circled-meal-1.svg') }}" class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" />
                            <img src="{{ asset('front/images/circled-meal-1.svg') }}"
                                class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />

                            <span style="padding-left:0;">21 meals customised for you</span>
                        </div>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank"
                            class="btn-learn-more ">View plan</a>
                    </div>
                </div>
            </div> -->
        </section>
    </div>
</main>

<script>
$(document).ready(function() {
    $('.coming-soon-popup').click(function() {
        $('#comingSoonModal').modal('show');
    });
});
</script>
@endsection