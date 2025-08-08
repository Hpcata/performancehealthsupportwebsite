@extends(frontView('layouts.app'))

@section('title', 'Sports Nutrition Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@section('content')
<div class="col-lg-6">
    <div class="score-meter-box">
        <div class="score-meter-text">
            <span class="meter-text-01">Needs <br>work </span>
            <span class="meter-text-02">Pretty <br>ordinary</span>
            <span class="meter-text-03">Not bad</span>
            <span class="meter-text-04">Good</span>
        </div>
        <div class="score-meter-box-frame">
            <svg version="1.1" x="0px" y="0px" viewBox="0 0 500 243" style="enable-background:new 0 0 500 243;" xml:space="preserve">
                <path d="M0,0v243h500V0H0z M474.7,233.7h-79.1c-4.9,0-9.2-3.6-9.9-8.5c-9.6-65.5-66.1-115.9-134.3-115.9s-124.6,50.3-134.3,115.9c-0.7,4.9-4.9,8.5-9.9,8.5H28.2c-5.9,0-10.5-5.1-10-11c11.3-119,111.4-212,233.2-212s221.9,93.1,233.2,212C485.2,228.6,480.6,233.7,474.7,233.7z" fill="#ffffff"/>
            </svg>
            <div class="bgradient-bg" style="background: conic-gradient(from -1.65deg at 48.15% 84.72%, #FF9500 -33.16deg, #FFDE48 31.45deg, #03741B 91.78deg, #CF080A 265.07deg, #FF9500 326.84deg, #FFDE48 391.45deg);"></div>
        </div>
        <span class="meter-arrow nutrition-result" style="transform: rotate(75deg);">
            <svg version="1.1" x="0px" y="0px" viewBox="0 0 133 22" style="enable-background:new 0 0 133 22;" xml:space="preserve">
                <path d="M91.8,0.4L3.4,8.7c-2.5,0.2-2.5,3.8,0,4.1l88.4,8.9c20.5-0.4,12.7-0.4,20.5-0.4c11.8,0,19.2,1.6,19.2-10.1c0-11.8-10-10.2-21.7-10.3C101.9,0.8,112,0.9,91.8,0.4z"/>
            </svg>
        </span>
    </div>
    <h4 class="text-center mt-4">General Nutrition <br>Knowledge</h4>
    <h3 class="text-center mt-1 text-black nutrition-percentage">40%</h3>
</div>


<script>
    const nutritionDegree = 5.14285714;
    const nutritionMaxTotal = 35;
    const nutritionFormCount = 10;
    const nutritionTotalDegree = Math.max(0, nutritionFormCount * nutritionDegree); // Ensure non-negative
    const nutritionPercentage = Math.max(0, (nutritionFormCount / nutritionMaxTotal) * 100);

    document.querySelectorAll('.nutrition-percentage').forEach(el => {
        el.textContent = Math.round(nutritionPercentage) + "%";
    });
    document.querySelectorAll('.meter-arrow.nutrition-result').forEach(el => {
        el.style.transform = 'rotate(' + nutritionTotalDegree + 'deg)';
    });
</script>
@endsection