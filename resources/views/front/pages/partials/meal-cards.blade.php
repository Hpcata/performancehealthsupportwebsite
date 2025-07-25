@php $mealCount = count($meals); @endphp
@if(count($meals) > 0)
    @if($mealCount > 1)
        <div class="scroll-arrow-left" aria-label="Scroll left">
            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none">
                <path d="M6 11L1 6L6 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    @endif

    @foreach($meals as $meal)
        <div class="challenge-card">
                <img
                    src="{{ webAssets('storage/' . ($meal->meal->image ?? 'food1.webp')) }}"
                    alt="{{ $meal->meal->title ?? 'Meal' }}"
                    width="600"
                    height="400"
                />
                <h3>{{ $meal->meal->title ?? 'Untitled Meal' }}</h3>
                <div class="quick-view-overlay">{{ $meal->meal->description ?? '' }}</div>
            </div>

    @endforeach 
    @if($mealCount > 2)
            <div class="scroll-arrow-right" aria-label="Scroll right">
                <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none" style="transform: rotate(180deg);">
                    <path d="M6 11L1 6L6 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
    @endif
@else
    <p>No meals available.</p>
@endif