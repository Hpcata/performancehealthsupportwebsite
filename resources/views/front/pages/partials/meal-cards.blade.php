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
            <!-- <div class="quick-view-overlay">{{ $meal['description'] ?? '' }}</div> -->

             <div class="quick-view-overlay"><span style="padding: 12px;
    border-radius: 12px;
    background-color: #0d6efd;
    font-weight: 700;
    cursor:pointer;">Quick View</span></div>
        </div>
    @endforeach
    
@else
    <p>No meals available.</p>
@endif