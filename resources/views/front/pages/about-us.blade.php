@extends(frontView('layouts.app'))

@section('title', 'Expert in Athlete Performance & Nutrition Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_ABOUT_US_BANNER && $section->enabled == 1) <!-- done -->

            @endif
            @if($section->section_type == \App\Models\Section::TYPE_ATHLETE_NUTRITION_FOCUS && $section->enabled == 1) <!-- done -->

            @endif
            @if($section->section_type == \App\Models\Section::TYPE_ABOUT_KERRY_INTRO && $section->enabled == 1) <!-- done -->

            @endif
            @if($section->section_type == \App\Models\Section::TYPE_ABOUT_BOOKING && $section->enabled == 1) <!-- done -->

            @endif
            @if($section->section_type == \App\Models\Section::TYPE_ATHLETES_WE_WORK_WITH && $section->enabled == 1) <!-- done -->

            @endif
        @endforeach
    @endif
@endsection
