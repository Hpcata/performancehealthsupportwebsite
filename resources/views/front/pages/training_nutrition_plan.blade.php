@extends(frontView('layouts.app'))

@section('title', 'Training Nutrition Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalized athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_TRAINING_PLAN_MAIN_BANNER && $section->enabled == 1) <!-- done -->
                    @php
            $bannerImage = '';
            if (isset($section->banner_image[0])) {
                $bannerImage = $section->banner_image[0];
            }
                    @endphp
                    <div class="hero-section-landing"
                        style="background-image: url('{{ webAssets('storage/' . $bannerImage) }}')">
                        <div class="container-homepage">
                            <div class="hero-content-fixed">
                                <h1 class="hero-title-landing">{{ $section->title }}</h1>
                                <button class="btn-signup purchase-now-btn"
                                    data-plan-id="{{ $planDetails->id }}"
                                    data-plan-name="{{ $planDetails->name }}"
                                    data-plan-price="{{ $planDetails->price }}">
                                    Purchase plan
                                </button>
                            </div>
                        </div>
                    </div>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_BUILT_FOR_REAL_RESULT && $section->enabled == 1) <!-- done -->
                <section class="about-section training-nutrition-landing">
                    <div class="container-homepage">
                        <div class="about-content-wrapper">
                            {!! $section->content !!}
                        </div>
                    </div>
                    <div class="about-image-container">
                        @if(isset($section->image[0]) && !empty($section->image[0]))
                            <img src="{{ asset('storage/' . $section->image[0]) }}" alt="about" class="img-fluid about-image" />
                        @endif
                    </div>
                </section>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_PLAN_INCLUSIONS && $section->enabled == 1) <!-- done -->
                @php
            $backgroundImage = '';
            if (isset($section->banner_image[0])) {
                $backgroundImage = asset('storage/' . $section->banner_image[0]);
            }
                @endphp
                <section class="plan-inclusion-section" style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class="container-homepage">
                        <div class="title-content">
                            <h2 class="title">{{ $section->title }}</h2>
                        </div>
                        {!! $section->content !!}
                    </div>
                </section>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_PLAN_INTERESTS && $section->enabled == 1) <!-- done -->
                <section class="recommended-plans-section">
                    <div class="container-homepage">
                        <h2 class="section-title">{{ $section->title }}</h2>
                        {!! $section->content !!}

                        <div class="cards-wrapper">
                            <div class="card">
                                <img src="{{ frontAssets('images/training-nutrition-plan/trophy.svg') }}" alt="trophy" class="web-hide card-logo"
                                    width="36" height="36" />
                                <h3 class="card-title">Competition Plan</h3>
                                <p class="card-description">A personalised 24-hour competition plan designed to fuel peak
                                    performance when it matters most.</p>
                                <button class="btn-signup" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                                <img src="{{ frontAssets('images/training-nutrition-plan/card-1.webp') }}" alt="Competition Plan"
                                    class="left-card-image card-image">
                            </div>

                            <div class="right-card card">
                                <img src="{{ frontAssets('images/training-nutrition-plan/insurance.svg') }}" alt="trophy"
                                    class="web-hide card-logo" width="36" height="36" />
                                <h3 class="card-title">Injury & Recovery Plan</h3>
                                <p class="card-description">A targeted mix of healing meals, expert tips, and supplement guidance to
                                    speed recovery, reduce inflammation, and rebuild strength.</p>
                                <button class="btn-signup" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                                <img src="{{ frontAssets('images/training-nutrition-plan/card-2.webp') }}" alt="Injury & Recovery Plan"
                                    class="right-card-image card-image">
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @endif

    @include('front.pages.partials.purchase-plan-register')
    @include('front.pages.partials.purchase-plan-login')

@endsection

@push('scripts')
    <script>
        window.purchasePlanConfig = {
            paymentUrl: "{{ route('process.payment') }}",
            validateCouponCodeUrl: "{{ route('validate.coupon.code') }}",
            getDefaultPlanDetailsUrl: "{{ route('front.get-default-plan-details', ':id') }}",
            csrfToken: "{{ csrf_token() }}",
            stripeKey: "{{ config('services.stripe.public_key') }}",
            isAuthenticated: @json(Auth::guard('web')->check()),
            userId: {{ Auth::check() ? Auth::user()->id : 'null' }},
            isAdmin: {{ Auth::check() && Auth::user()->is_superadmin == 1 ? 'true' : 'false' }},
            userData: @json(Auth::check() ? [
                'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? ''
            ] : null),
            env: "{{ env('APP_ENV') }}"
        };
    </script>

    <script src="{!! frontAssets('js/purchase-plan.js') !!}"></script>
@endpush

