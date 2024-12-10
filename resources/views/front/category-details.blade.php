@extends(frontView('layouts.app'))

@section('title', $category->title)

@section('content')
<div class="section pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="heading mb-4">{{ $plan->name }}</h2>
                <p class="mb-4">{{ $plan->description }}</p>

                <!-- Plan Image -->
                @if($plan->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->name }}" class="img-fluid">
                    </div>
                @endif

                <!-- Categories Section -->
                @if($plan->categories->count())
                    <h3 class="mb-4 text-center">Categories</h3>
                    <div class="row">
                        @foreach($plan->categories as $category)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <!-- Category Image -->
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/300x200?text=No+Image" alt="{{ $category->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    @endif

                                    <!-- Category Content -->
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-center">{{ $category->name }}</h5>
                                        <p class="card-text text-muted text-center">
                                            {{ Str::limit($category->description, 100, '...') }}
                                        </p>
                                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary mt-auto">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No categories available for this plan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
