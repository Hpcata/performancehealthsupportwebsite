@extends(frontView('layouts.app'))

@section('title', 'Training Nutrition Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_TRAINING_PLAN_MAIN_BANNER && $section->enabled == 1) <!-- done -->
               
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_BUILT_FOR_REAL_RESULT && $section->enabled == 1) <!-- done -->
               
            @endif

            @if($section->section_type == \App\Models\Section::PLAN_INCLUSIONS && $section->enabled == 1) <!-- done -->
               
            @endif

            @if($section->section_type == \App\Models\Section::PLAN_INTERESTS && $section->enabled == 1) <!-- done -->
               
            @endif  
        @foreach
    @endif

@endsection