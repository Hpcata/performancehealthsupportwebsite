@extends(frontView('layouts.app'))

@section('title', 'Competition Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_COMPETITION_MAIN_BANNER && $section->enabled == 1) <!-- done -->
                @php
                    $bannerImage = '';
                    if(isset($section->banner_image[0])) {
                        $bannerImage = $section->banner_image[0];
                    }
                @endphp
                <div class="hero-section-landing"
                    style="background-image: url('{{ webAssets('storage/' . $bannerImage) }}')">
                    <div class="container-homepage">
                        <div class="hero-content-fixed">
                            <h1 class="hero-title-landing">Competition </br>Plan</h1>
                            <button class="btn-signup">Purchase plan</button>
                        </div>
                    </div>
                </div>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_COMPETE_AT_YOUR_PEAK && $section->enabled == 1) <!-- done -->
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

            @if($section->section_type == \App\Models\Section::TYPE_COMPETITION_PLAN_INCLUSIONS && $section->enabled == 1) <!-- done -->
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
            @if($section->section_type == \App\Models\Section::TYPE_COMPETITION_PLAN_INTERESTS && $section->enabled == 1) <!-- done -->
                <section class="recommended-plans-section">
                    <div class="container">
                        <h2 class="section-title">{{ $section->title }}</h2>
                        {!! $section->content !!}

                        <div class="cards-wrapper">
                            <div class="card">
                                <img src="{{ frontAssets('images/training-nutrition-plan/trophy.svg') }}" alt="trophy" class="web-hide card-logo"
                                    width="36" height="36" />
                                <h3 class="card-title">Training Nutrition Plan</h3>
                                <p class="card-description">Train to perform at your peak with a personalised meal plan tailored to your sport and lifestyle - designed by Extreme Sports Dietitian Kerry O'Bryan.</p>
                                <button class="btn-signup">Learn more</button>
                                <img src="{{ frontAssets('images/training-nutrition-plan/card-1.webp') }}" alt="Competition Plan"
                                    class="left-card-image card-image">
                            </div>

                            <div class="right-card card">
                                <img src="{{ frontAssets('images/training-nutrition-plan/insurance.svg') }}" alt="trophy"
                                    class="web-hide card-logo" width="36" height="36" />
                                <h3 class="card-title">Injury & Recovery Plan</h3>
                                <p class="card-description">A targeted mix of healing meals, expert tips, and supplement guidance to
                                    speed recovery, reduce inflammation, and rebuild strength.</p>
                                <button class="btn-signup">Learn more</button>
                                <img src="{{ frontAssets('images/training-nutrition-plan/card-2.webp') }}" alt="Injury & Recovery Plan"
                                    class="right-card-image card-image">
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @endif
@endsection