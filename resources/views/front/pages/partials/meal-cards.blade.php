@php $mealCount = count($meals); @endphp
@if(count($meals) > 0)

    @foreach($meals as $meal)
        @if($loop->index >= 3)
            @break
        @endif
        <!-- Meal Card -->
        <div class="challenge-card">
            <img
                src="{{ $meal['image'] }}"
                alt="{{ $meal['name'] ?? 'Meal' }}"
                width="600"
                height="400"
            />
            <h3>{{ $meal['name'] ?? 'Untitled Meal' }}</h3>
            <div class="quick-view-overlay">{{ $meal['description'] ?? '' }}</div>
        </div>
    @endforeach
@else
    <p>No meals available.</p>
@endif