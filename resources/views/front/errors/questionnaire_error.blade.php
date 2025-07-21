@extends(frontView('layouts.app'))

@section('title', 'Performance Dietitian | Strength & Conditioning Coach')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5 mb-5">
                    <div class="card-header">{{ __('Questionnaire Error') }}</div>

                    <div class="card-body">
                        <p>{{ $message ?? 'An unknown error occurred.' }}</p>
                        <a href="{{ route('front.index') }}" class="btn btn-primary">{{ __('Go Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection