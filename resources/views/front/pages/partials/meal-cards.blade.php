@if(count($meals) > 0)
    @foreach($meals as $meal)
        <div class="meal-card">
            <img
                src="{{ frontAssets('images/' . ($meal->meal->image ?? 'food1.webp')) }}"
                alt="{{ $meal->meal->title ?? 'Meal' }}"
                width="600"
                height="400"
            />
            <h3>{{ $meal->meal->title ?? 'Untitled Meal' }}</h3>
        </div>
    @endforeach
@else
    <p>No meals available.</p>
@endif