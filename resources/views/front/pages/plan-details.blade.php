@extends(frontView('layouts.app'))

@section('title', 'Sports Training Plan | 2LS Performance Support')
@section('meta_description', 'Performance Health Support offers expert care from top sports nutritionists, strength coaches, and sports dietitians in Australia to boost health and performance.')

@section('content')

<main class="main">
    <!-- Hero Banner -->
    <div class="hero-container">
        <div class="hero-section">
            <div class="hero-background">
                <div class="hero-overlay"></div>
            </div>

            <div class="hero-content">
                <div class="hero-bottom">
                    <h1 class="hero-title">Sports Training Plan</h1>

                    <div class="hero-top">
                        <p class="hero-subtitle-plan">Surfing</p>
                        <a href="#" class="view-all-link"> View all plans </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="action-buttons">
            <button class="btn btn-share">
                <img
                    src="images/share-icon.svg"
                    alt="share-icon"
                    class="share-icon" />
                Share
            </button>

            <button class="btn-outline btn">Shopping list</button>
        </div>
        <!-- Meal Sections -->
        <section aria-label="Meal Plan Categories">
            <!-- Sweet Breakfast -->
            @foreach ($userPlans as $userPlan)
                @foreach ($userPlan->userCategories as $userCategory)
                    @php
                        $validSubCategories = $userCategory->userSubCategories->filter(function ($subCategory) use ($userPlan, $userCategory) {
                            return $subCategory->userMeals
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_category_id', $userCategory->id)
                                ->where('user_sub_category_id', $subCategory->id)
                                ->isNotEmpty();
                        });
                    @endphp

                    @foreach ($validSubCategories as $subCategory)
                        @php
                            $meals = $subCategory->userMeals
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_category_id', $userCategory->id)
                                ->where('user_sub_category_id', $subCategory->id)
                                ->take(3);
                            $mealCount = $subCategory->userMeals
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_category_id', $userCategory->id)
                                ->where('user_sub_category_id', $subCategory->id)
                                ->count();
                        @endphp

                        @if ($mealCount > 0)
                            <section class="challenges" aria-label="Meal Plan Categories">
                                <div class="section-header">
                                    <h2>{{ $subCategory->subCategory->title }} ({{ $mealCount }})</h2>
                                    <a href="{{ route('front.meal-time.details', ['id' => $userCategory->id, 'plan_id' => $userPlan->id]) }}" class="see-all">See all</a>
                                </div>
                                <div class="challenge-cards">
                                    @foreach ($meals as $meal)
                                        <div class="challenge-card clickable">
                                            <img
                                                src="{{ frontAssets('images/food1.webp') }}"
                                                alt="{{ $meal->meal->title }}"
                                                height="252"
                                                width="160" />
                                            <h3>{{ $meal->meal->title }}</h3>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        </section>

        <!-- Plate Breakdown and Training Load -->
        <section
            aria-label="Plate Breakdown and Training Load"
            style="margin-top: 2rem">
            <div class="section-header">
                <h2>Plate like this...</h2>
            </div>
            <p>
                Your carb and veggie portions vary by meal type and training load
                for peak performance. Protein stays the same. See the ideal ratios
                and what foods to choose below.
            </p>
            <div class="input-wrap">
                <label for="training-load-select" style="font-weight: 600">Training load</label>
                <div class="select-wrapper">
                    <select id="training-load-select" class="two-line-select">
                        <option value="low">
                            Low – Low load, rest and recovery days
                        </option>
                        <option value="medium">Medium – Moderate training</option>
                        <option value="high">High – Intense training</option>
                    </select>
                    <img
                        src="{{ webAssets('front/images/arrow-down.svg') }}"
                        alt="Arrow"
                        class="select-arrow" />
                </div>
            </div>
            <div
                style="
              display: flex;
              align-items: center;
              gap: 1.5rem;
              flex-wrap: wrap;
            ">
                <img
                    src="{{ webAssets('front/images/plate.webp') }}"
                    alt="Plate like this image"
                    style="width: 100%"
                    width="806"
                    height="590"
                    class="plate-img" />
                <ul style="list-style: none; padding-left: 0; font-size: 1rem">
                    <li class="list-w-image">
                        <img
                            src="{{ webAssets('front/images/Bread.svg') }}"
                            alt="Plate like this image"
                            style="width: 32px; height: auto"
                            width="32"
                            height="33" />
                        <div>
                            <span style="color: #967500; font-weight: bold">Carbs: Fuel</span>
                            <br />Get your carbs from bread or cereal at breakfast.
                        </div>
                    </li>

                    <li class="list-w-image">
                        <img
                            src="{{ webAssets('front/images/apple.svg') }}"
                            alt="Plate like this image"
                            style="width: 32px; height: auto"
                            width="32"
                            height="33" />
                        <div>
                            <span style="color: #3e8e00; font-weight: bold">Fruit and vegetables: Protect</span>
                            <br />A quarter must be colourful fruit and vegetables.
                        </div>
                    </li>
                    <li class="list-w-image">
                        <img
                            src="{{ webAssets('front/images/boiled egg.svg') }}"
                            alt="Plate like this image"
                            style="width: 32px; height: auto"
                            width="32"
                            height="33" />
                        <div>
                            <span style="color: #a60015; font-weight: bold">Protein: Repair foods</span>
                            <br />One quarter stays protein. Try eggs or dairy.
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</main>

@endsection