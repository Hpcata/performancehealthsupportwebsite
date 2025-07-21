@extends(frontView('layouts.app'))

@section('title', 'Training Nutrition Plan | 2LS Performance Support')
@section('meta_description', 'Performance Health Support offers expert care from top sports nutritionists, strength coaches, and sports dietitians in Australia to boost health and performance.')

@section('content')
@if (!empty($sportGameData['sport_image']))
    <style>
        .hero-background {
            background-image: url('{{ webAssets("storage/" . $sportGameData['sport_image']) }}') !important;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
            max-width: 100%;
            position: absolute;
            right: 0;
            border-radius: 0 0 36px 0;
            width: 100%;
        }
    </style>
@endif
<main class="main">
    <!-- Hero Banner -->
    <div class="hero-container">
        <div class="hero-section">
            <div class="hero-background">
                <div class="hero-overlay"></div>
            </div>

            <div class="hero-content">
                <div class="hero-bottom">
                    <h1 class="hero-title">Training Nutrition Plan</h1>

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
                    src="{{ frontAssets('images/images/share-icon.svg') }}"
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
                                    <!-- <a href="{{ route('front.meal-time.details', ['id' => $userCategory->id, 'plan_id' => $userPlan->id]) }}" class="see-all">Scroll for More</a> -->

                                     <!-- <label  class="see-all" style="text-decoration:none;">Scroll for More</label> -->
                                </div>
                             
                                   
                                @if ($meals->count() > 1)
    <div class="horizontal-scroll-arrow-wrapper" style="position: relative;">
        <button class="scroll-arrow-left" aria-label="Scroll left"><svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none">
  <path d="M6 11L1 6L6 1" stroke="#626262" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg></button>
        <div class="challenge-cards horizontal-scroll" id="meal-scroll-{{ $subCategory->id }}">
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
        <button class="scroll-arrow-right" aria-label="Scroll right"><svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none">
  <path d="M1 1L6 6L1 11" stroke="#626262" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg></button>
    </div>
@else
    <div class="challenge-cards horizontal-scroll" id="meal-scroll-{{ $subCategory->id }}">
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
@endif
                               
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
                <h2>Main Meal Plate Portions</h2>
            </div>
            <p>
                Your carb and veggie portions vary by meal type and training load
                for peak performance. Protein stays the same. See the ideal ratios
                and what foods to choose below.
            </p>
             <div class="dropdown-container">
        <label class="dropdown-label">Training load</label>
        <button class="dropdown-button" id="dropdownButton">
            <div class="dropdown-content">
                <div class="dropdown-title">Low</div>
                <div class="dropdown-subtitle">Low load, rest and recovery days</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none">
  <path d="M1 1.80078L6 6.80078L11 1.80078" stroke="#3B3B3B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
        </button>
        <div class="dropdown-menu" id="dropdownMenu">
            <div class="dropdown-option selected" data-value="low">
                <div class="option-title">Low</div>
                <div class="option-subtitle">Low load, rest and recovery days</div>
            </div>
            <div class="dropdown-option" data-value="moderate">
                <div class="option-title">Moderate</div>
                <div class="option-subtitle">Balanced training and recovery</div>
            </div>
            <div class="dropdown-option" data-value="high">
                <div class="option-title">High</div>
                <div class="option-subtitle">Intense training, peak performance</div>
            </div>
            <div class="dropdown-option" data-value="peak">
                <div class="option-title">Peak</div>
                <div class="option-subtitle">Maximum load, competition ready</div>
            </div>
        </div>
    </div>

            <!-- <div class="input-wrap">
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
            </div> -->
            <div
                style="
              display: flex;
              align-items: start;
              gap: 1.5rem;
              flex-wrap: wrap;
              flex-direction:column;
            ">
                <img
                    src="{{ webAssets('front/images/low-load.png') }}"
                    alt="Plate like this image"
                    
                    width="318"
                    height="350"
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
    <script>
        class CustomDropdown {
            constructor(buttonId, menuId) {
                this.button = document.getElementById(buttonId);
                this.menu = document.getElementById(menuId);
                this.options = this.menu.querySelectorAll('.dropdown-option');
                this.isOpen = false;
                
                this.init();
            }

            init() {
                // Toggle dropdown on button click
                this.button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggle();
                });

                // Handle option selection
                this.options.forEach(option => {
                    option.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.selectOption(option);
                    });
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', () => {
                    if (this.isOpen) {
                        this.close();
                    }
                });

                // Handle keyboard navigation
                this.button.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.toggle();
                    } else if (e.key === 'Escape') {
                        this.close();
                    }
                });
            }

            toggle() {
                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }
            }

            open() {
                this.isOpen = true;
                this.button.classList.add('open');
                this.menu.classList.add('open');
                this.button.setAttribute('aria-expanded', 'true');
            }

            close() {
                this.isOpen = false;
                this.button.classList.remove('open');
                this.menu.classList.remove('open');
                this.button.setAttribute('aria-expanded', 'false');
            }

            selectOption(selectedOption) {
                // Remove selected class from all options
                this.options.forEach(option => {
                    option.classList.remove('selected');
                });

                // Add selected class to clicked option
                selectedOption.classList.add('selected');

                // Update button content
                const title = selectedOption.querySelector('.option-title').textContent;
                const subtitle = selectedOption.querySelector('.option-subtitle').textContent;
                
                this.button.querySelector('.dropdown-title').textContent = title;
                this.button.querySelector('.dropdown-subtitle').textContent = subtitle;

                // Close dropdown
                this.close();

                // Trigger custom event
                const event = new CustomEvent('dropdownChange', {
                    detail: {
                        value: selectedOption.dataset.value,
                        title: title,
                        subtitle: subtitle
                    }
                });
                this.button.dispatchEvent(event);
            }
        }

        // Initialize dropdown
        const dropdown = new CustomDropdown('dropdownButton', 'dropdownMenu');

        // Listen for selection changes
        document.getElementById('dropdownButton').addEventListener('dropdownChange', (e) => {
            console.log('Selected:', e.detail);
        });
    </script>
   
@endsection