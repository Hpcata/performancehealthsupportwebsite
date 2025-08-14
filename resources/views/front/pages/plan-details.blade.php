@extends(frontView('layouts.app'))

@section('title', 'Training Nutrition Plan | 2LS Performance Support')
@section('meta_description', 'Performance Health Support offers expert care from top sports nutritionists, strength coaches, and sports dietitians in Australia to boost health and performance.')

@section('content')

<main class="main">
     <!-- Loader -->
    <!-- <div id="loader" class="d-none">
        <div class="box" id="loader1"></div>
        <div class="box" id="loader2"></div>
        <div class="box" id="loader3"></div>
        <div class="box" id="loader4"></div>
        <div class="box" id="loader5"></div>
    </div> -->
    <!-- Hero Banner -->
    <div class="hero-container">
        <div class="hero-section">
            @if (!empty($sportGameData['sport_image']))
                <div class="hero-background" style="background-image: url('{{ webAssets('storage/' . $sportGameData['sport_image']) }}')" >
                    <div class="hero-overlay"></div>
                </div>
            @else
                <div class="hero-background" style="background-image: url('{{ frontAssets('images/bannerimg.png') }}');" >
                    <div class="hero-overlay"></div>
                </div>
            @endif
            <div class="hero-content">
                <div class="hero-bottom">
                    <h1 class="hero-title">Training Nutrition Plan</h1>

                    <div class="hero-top">
                        <p class="hero-subtitle-plan">{{ isset($sportGameData['sport_image']) ? $sportGameData['sport_name'] : '' }}</p>
                        <a href="#" class="view-all-link"> View all plans </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="button-wrapper">
            <button class="btn btn-share ">
                <a href="#" class="ms-0 print-plan-btn" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}" style="text-decoration:none; color:#3b3b3b">View plan</a>
            </button>
            <button class="btn-outline btn" id="shoppingList" data-bs-toggle="modal" data-bs-target="#shoppingListModal">Shopping list</button>
            <button class="btn btn-share coming-soon-popup" type="button" id="">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                    <g clip-path="url(#clip0_3008_7695)">
                        <path d="M0.888672 8.50124V14.3194C0.888672 14.7052 1.07597 15.0752 1.40937 15.3479C1.74277 15.6207 2.19495 15.774 2.66645 15.774H13.3331C13.8046 15.774 14.2568 15.6207 14.5902 15.3479C14.9236 15.0752 15.1109 14.7052 15.1109 14.3194V8.50124M11.5553 4.13761L7.99978 1.22852M7.99978 1.22852L4.44423 4.13761M7.99978 1.22852V10.6831" stroke="#3B3B3B" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_3008_7695">
                        <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                        </clipPath>
                    </defs>
                </svg>
                    Share
            </button>
            
        </div>    
        <!-- <div class="dropdown action-buttons">
            <button class="btn btn-share dropdown-toggle" type="button" id="shareDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                <g clip-path="url(#clip0_3008_7695)">
                    <path d="M0.888672 8.50124V14.3194C0.888672 14.7052 1.07597 15.0752 1.40937 15.3479C1.74277 15.6207 2.19495 15.774 2.66645 15.774H13.3331C13.8046 15.774 14.2568 15.6207 14.5902 15.3479C14.9236 15.0752 15.1109 14.7052 15.1109 14.3194V8.50124M11.5553 4.13761L7.99978 1.22852M7.99978 1.22852L4.44423 4.13761M7.99978 1.22852V10.6831" stroke="#3B3B3B" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                    <clipPath id="clip0_3008_7695">
                    <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                    </clipPath>
                </defs>
            </svg>
                Share
            </button>
            <ul class="dropdown-menu share-dropdown" aria-labelledby="shareDropdown" style="min-width: 270px;">
                <li>
                    <div class="d-flex align-items-center justify-content-between px-3 py-2 share-dropdown-header">
                        <span>Share</span>
                        <button class="btn-close" data-bs-toggle="dropdown" aria-label="Close"></button>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <div class="d-flex align-items-center share-dropdown-item">
                        <img src="{{ frontAssets('images/dialog/Artboard.svg') }}" alt="Invite" class="me-2 share-dropdown-icon" />
                        Invite a friend, parent or club
                    </div>
                </li>
                <li>
                    <div class="d-flex align-items-center share-dropdown-item">
                        <img src="{{ frontAssets('images/dialog/download.svg') }}" alt="Download" class="me-2 share-dropdown-icon" />
                        <a href="#" class="ms-0 print-plan-btn" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}" style="text-decoration:none; color:#3b3b3b">Download plan</a>
                    </div>
                </li>
            </ul>
            <button class="btn-outline btn" id="shoppingList" data-bs-toggle="modal" data-bs-target="#shoppingListModal">Shopping list</button>
        </div> -->
        <!-- Meal Sections -->
        <section aria-label="Meal Plan Categories">
            <!-- Sweet Breakfast -->
            @if ($userPlans->isNotEmpty())
                @foreach ($userPlans as $userPlan)
                    @if ($userPlan->userCategories->isNotEmpty())
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
                                        ->where('user_sub_category_id', $subCategory->id);

                                    $mealCount = $subCategory->userMeals
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_category_id', $userCategory->id)
                                        ->where('user_sub_category_id', $subCategory->id)
                                        ->count();
                                @endphp

                                @if ($mealCount > 0)
                                    @if(isset($subCategory->subCategory))

                                        <section class="challenges" aria-label="Meal Plan Categories">
                                            <div class="section-header">
                                                <h2>{{ $subCategory->subCategory->title ?? '' }} ({{ $mealCount }})</h2>
                                            </div>
                                            <div class="slider-wrapper" style="position:relative;">
                                            <button class="left-arrow slider-arrow" >
                                                <svg width="18" height="24" viewBox="0 0 18 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <polyline points="14,4 4,16 14,28" stroke="#080808" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                                <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;">
                                                    @foreach ($meals as $meal)
                                                        <div class="challenge-card clickable quick-view-btn"
                                                            data-title="{{ $meal->meal->title }}"
                                                            data-plan-id="{{ $userPlan->id }}"
                                                            data-meal-id="{{ $meal->id }}"
                                                            data-user-id="{{ $user->id }}"
                                                            data-sub-category-id="{{ $subCategory->id }}"
                                                            data-category-id="{{ $userCategory->id }}"
                                                            data-user-plan-id="{{ $userPlan->id }}">
                                                            <img
                                                                src="{{ webAssets('storage/'.$meal->meal->image) }}"
                                                                alt="{{ $meal->meal->title }}"
                                                                height="252"
                                                                width="160" />
                                                            <h3>{{ $meal->meal->title }}</h3>
                                                            <div class="quick-view-overlay">
                                                                <span style="swap-btn-overlay">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" viewBox="0 0 18 18"
                                                                        fill="none">
                                                                        <path
                                                                            d="M1 5.50117H14L12.4 6.70117C12.2949 6.77996 12.2064 6.87867 12.1395 6.99167C12.0726 7.10467 12.0286 7.22974 12.0101 7.35974C11.9915 7.48975 11.9987 7.62213 12.0313 7.74935C12.0639 7.87656 12.1212 7.99611 12.2 8.10117C12.2931 8.22536 12.4139 8.32616 12.5528 8.39559C12.6916 8.46502 12.8448 8.50117 13 8.50117C13.2164 8.50117 13.4269 8.43099 13.6 8.30117L17.6 5.30117C17.7223 5.20784 17.8214 5.08756 17.8897 4.94967C17.9579 4.81178 17.9934 4.66001 17.9934 4.50617C17.9934 4.35232 17.9579 4.20055 17.8897 4.06266C17.8214 3.92478 17.7223 3.80449 17.6 3.71117L13.74 0.711166C13.5305 0.548057 13.2647 0.474862 13.0013 0.507681C12.7378 0.540499 12.4981 0.676645 12.335 0.886166C12.1719 1.09569 12.0987 1.36142 12.1315 1.62491C12.1643 1.8884 12.3005 2.12806 12.51 2.29117L14.08 3.50117H1C0.734784 3.50117 0.48043 3.60652 0.292893 3.79406C0.105357 3.9816 0 4.23595 0 4.50117C0 4.76638 0.105357 5.02074 0.292893 5.20827C0.48043 5.39581 0.734784 5.50117 1 5.50117ZM17 12.5012H4L5.6 11.3012C5.81217 11.142 5.95244 10.9051 5.98995 10.6426C6.02746 10.38 5.95913 10.1133 5.8 9.90117C5.64087 9.68899 5.40397 9.54872 5.14142 9.51122C4.87887 9.47371 4.61217 9.54204 4.4 9.70117L0.4 12.7012C0.277693 12.7945 0.178568 12.9148 0.110337 13.0527C0.0421059 13.1905 0.00660944 13.3423 0.00660944 13.4962C0.00660944 13.65 0.0421059 13.8018 0.110337 13.9397C0.178568 14.0776 0.277693 14.1978 0.4 14.2912L4.26 17.2912C4.43455 17.4266 4.64905 17.5005 4.87 17.5012C5.02272 17.5008 5.17332 17.4655 5.31026 17.3979C5.4472 17.3303 5.56684 17.2322 5.66 17.1112C5.82239 16.9027 5.89567 16.6384 5.86381 16.3761C5.83196 16.1138 5.69756 15.8747 5.49 15.7112L3.92 14.5012H17C17.2652 14.5012 17.5196 14.3958 17.7071 14.2083C17.8946 14.0207 18 13.7664 18 13.5012C18 13.2359 17.8946 12.9816 17.7071 12.7941C17.5196 12.6065 17.2652 12.5012 17 12.5012Z"
                                                                            fill="#ffffff" />
                                                                    </svg>
                                                                    Smart Swap
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                               <button class="right-arrow slider-arrow">
                                                    <svg width="18" height="24" viewBox="0 0 18 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <polyline points="4,4 14,16 4,28" stroke="#080808" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </section>

                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            @endif
        </section>

        <!-- Plate Breakdown and Training Load -->
        <section
            aria-label="Plate Breakdown and Training Load"
            style="margin-top: 2rem">
            <div class="section-header">
                <h2>Main Meal Plate Portions</h2>
            </div>
            <p>
                Your carb and veggie portions vary by meal type and training 
                load for peak performance. Your protein, however, stays the 
                same. Select your training load below to view: 
            </p>
            <div class="dropdown dropdown-container training-load-dropdown">
                <!-- <label class="dropdown-label">Training load</label> -->
                <button class="btn custom-dropdown-button dropdown-toggle" type="button" id="trainingLoadDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="custom-dropdown-content">
                        <div class="custom-dropdown-content-inner">
                            <div class="custom-dropdown-title">Low</div>
                            <div class="custom-dropdown-subtitle">Low load, rest and recovery days</div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none">
                            <path d="M1 1.5L6 6.5L11 1.5" stroke="#3B3B3B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="trainingLoadDropdown">
                    <li>
                        <div class="custom-dropdown-option selected" data-value="low" data-image="{{ webAssets('front/images/low-load.png') }}">
                            <div class="option-title">Low</div>
                            <div class="option-subtitle">Low load, rest and recovery days</div>
                        </div>
                    </li>
                    <li>
                        <div class="custom-dropdown-option" data-value="moderate" data-image="{{ webAssets('front/images/medium-load.png') }}">
                            <div class="option-title">Medium </div>
                            <div class="option-subtitle">Training days</div>
                        </div>
                    </li>
                    <li>
                        <div class="custom-dropdown-option" data-value="high" data-image="{{ webAssets('front/images/high-load.svg') }}">
                            <div class="option-title">High </div>
                            <div class="option-subtitle">Competition or heavy training days</div>
                        </div>
                    </li>
                </ul>
            </div>

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
                    class="plate-img"
                    id="plate-img"/>
                <ul style="list-style: none; padding-left: 0; font-size: 1rem">
                     <li class="list-w-image">
                        <img
                            src="{{ webAssets('front/images/boiled egg.svg') }}"
                            alt="Plate like this image"
                            style="width: 32px; height: auto"
                            width="32"
                            height="33" />
                        <div>
                            <span style="color: #A60015; font-weight: bold">Protein: Repair foods</span>
                            <br />Keep one-quarter of the plate for protein. Choose soft, lean sources like boiled eggs, tofu, fish, or skinless chicken.
                        </div>
                    </li>
                    <li class="list-w-image">
                        <img
                            src="{{ webAssets('front/images/Bread.svg') }}"
                            alt="Plate like this image"
                            style="width: 32px; height: auto"
                            width="32"
                            height="33" />
                        <div>
                            <span style="color: #967500; font-weight: bold">Carbs: Fuel</span>
                            <br />Choose easily digestible carbs like boiled potatoes, white rice, or white bread. Avoid high-fiber or heavy grains before surgery.
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
                            <span style="color: #3E8E00; font-weight: bold">Fruit and vegetables: Protect</span>
                            <br />Lightly cooked or peeled vegetables are easier on the gut. Go for colorful but low-fiber options like carrots, zucchini, or bell peppers.
                        </div>
                    </li>
                   
                </ul>
            </div>
        </section>
    </div>
</main>

<!-- Bootstrap Modal for Download Plan (keep your content inside) -->
<div class="modal" id="print-plan-modal" tabindex="-1" aria-labelledby="printPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #d8d8d8;">
                <h5 class="modal-title" id="printPlanModalLabel">Download Plan</h5>
                <button type="button" class="meal-item-modal-close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0;    overflow: auto;">
                <div style="flex: 1 1 auto; padding: 16px 16px 0 16px;">
                    <div id="pdf-preview" style="width: 100%; height: 100%; display: flex; justify-content: center;" class="downloadplan-inner-content">

                    </div>
                </div>

            </div>
             <div class="modal-footer" style="text-align: end; padding: 12px 16px; border-top: 1px solid #d8d8d8; border-radius:0 0 12px 12px; background-color:#fff;">
                <button id="download-plan-btn" class="btn btn-primary" onclick="downloadPDF()">
                    Download Plan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Shopping List -->
<div class="modal" id="shoppingListModal" tabindex="-1" aria-labelledby="shoppingListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header" style="border-bottom: 1px solid #d8d8d8;">
        <h5 class="modal-title" id="shoppingListModalLabel">Shopping Lists</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <!-- Your existing shopping list content will be injected here by JS -->
        <div id="shopping-list-content-container">
            <!-- Content loaded via AJAX or JS -->
        </div>
        </div>
        <div class="modal-footer" style="text-align: end; padding: 12px 16px; border-top: 1px solid #d8d8d8; background-color:#fff; border-radius:0 0 12px 12px; ">
        <button id="print-shopping-list" class="btn btn-primary">Print Shopping List</button>
        </div>
    </div>
    </div>
</div>

@include('front.modal.shopping-list')
@include('front.modal.print-shopping-list')
@include('front.modal.meal-detail')
@include('front.modal.smart-swap')
@include('front.modal.smart-swap-items')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    const user = @json($userPlan);
    const userId = user.user_id;
    const userPlanId = user.id;
    const assetBaseUrl = "{{ asset('storage') }}";

    // Ensure userId and userPlanId are already defined globally

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownOptions = document.querySelectorAll('.custom-dropdown-option');
        const plateImg = document.getElementById('plate-img'); // Get the image element

        dropdownOptions.forEach(option => {
            option.addEventListener('click', function () {
                // Remove 'selected' class from all options
                dropdownOptions.forEach(opt => opt.classList.remove('selected'));

                // Add 'selected' class to clicked option
                this.classList.add('selected');

                // Update button text
                const title = this.querySelector('.option-title').textContent;
                const subtitle = this.querySelector('.option-subtitle').textContent;

                const dropdown = this.closest('.dropdown');
                dropdown.querySelector('.custom-dropdown-title').textContent = title;
                dropdown.querySelector('.custom-dropdown-subtitle').textContent = subtitle;

                // Optionally update hidden input if needed
                const value = this.getAttribute('data-value');
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                if (hiddenInput) {
                    hiddenInput.value = value;
                }

                // Update plate image based on selection
                const imageSrc = this.getAttribute('data-image');
                if (plateImg && imageSrc) {
                    plateImg.src = imageSrc;
                }

                // Close the Bootstrap dropdown after selection
                const toggleEl = document.getElementById('trainingLoadDropdown');
                if (typeof bootstrap !== 'undefined' && toggleEl) {
                    const dd = bootstrap.Dropdown.getOrCreateInstance(toggleEl);
                    dd.hide();
                }
            });
        });
    });

    function showLoader() {
        $('#loader').removeClass('d-none');
    }
    function hideLoader() {
         $('#loader').addClass('d-none');
    }

    $(document).ready(function() {
        // Bind once
        $(document).on('click', '#shoppingList', function() {
            const shoppingListModal = document.getElementById('shoppingListModal');
            showLoader();

            // Inject content
            const contentContainer = $('#shoppingListModal .modal-body');
            // contentContainer.html('<p>Loading...</p>');

            $.ajax({
                url: '{{ route("front.get.meals.items") }}' + `?user_id=${userId}&user_plan_id=${userPlanId}`,
                method: 'GET',
                success: function(response) {
                    const meals = response.meals;
                    if (!meals || meals.length === 0) {
                        contentContainer.html('<p>No meal foods found for this plan.</p>');
                        hideLoader();
                        return;
                    }
                    let modalContent = `
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                            <label class="form-check-label" for="selectAllCheckbox">Select All</label>
                        </div>
                    `;

                    meals.forEach(meal => {
                        // Meal-level checkbox
                        modalContent += `
                            <div class="mb-2 form-check">
                                <input type="checkbox" class="form-check-input meal-checkbox" id="meal-${meal.meal_id}-checkbox" data-meal-id="${meal.meal_id}">
                                <label class="form-check-label" for="meal-${meal.meal_id}-checkbox" style="font-weight: bold; font-size: 1.2rem;">${meal.meal_title}</label>
                            </div>
                        `;

                        modalContent += `<div class="mb-3 card"><div class="p-3 card-body"><ul class="mb-0 list-unstyled meal-items" data-meal-id="${meal.meal_id}">`;

                        meal.items.forEach(item => {
                            let selectedQtyUnits = [];
                            try {
                                selectedQtyUnits = item.selected_qty_unit ? JSON.parse(item.selected_qty_unit) : [];
                                if (!Array.isArray(selectedQtyUnits)) selectedQtyUnits = [];
                            } catch (e) {
                                selectedQtyUnits = [];
                            }

                            const checkedUnits = selectedQtyUnits.filter(u =>
                                u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                            );

                            let qtyText = '';
                            if (checkedUnits.length > 0) {
                                qtyText = checkedUnits.map(unitObj => {
                                    let rawQty = unitObj.qty;
                                    let unit = unitObj.unit;
                                    let qty = isNaN(rawQty) ? rawQty : parseFloat(rawQty);

                                    if (typeof rawQty === 'string' && rawQty.includes('/')) {
                                        const parts = rawQty.split('/');
                                        if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                                            qty = parseFloat(parts[0]) / parseFloat(parts[1]);
                                        }
                                    }

                                    if (['g', 'ml', 'mL'].includes(unit)) {
                                        return `${Math.round(qty)}${unit}`;
                                    }

                                    let displayQty = rawQty;
                                    const rounded = Math.round(qty * 100) / 100;
                                    if (rounded === 0.25) displayQty = '¼';
                                    else if (rounded === 0.5) displayQty = '½';
                                    else if (rounded === 0.75) displayQty = '¾';

                                    return `${displayQty} ${unit}`;
                                }).join(' or ');
                            } else {
                                let qty = parseFloat(item.qty);
                                let unit = item.unit;
                                let displayQty = qty;

                                const rounded = Math.round(qty * 100) / 100;
                                if (rounded === 0.25) displayQty = '¼';
                                else if (rounded === 0.5) displayQty = '½';
                                else if (rounded === 0.75) displayQty = '¾';

                                qtyText = ['g', 'ml', 'mL'].includes(unit) ?
                                    `${Math.round(qty)}${unit}` :
                                    `${displayQty} ${unit}`;
                            }

                            modalContent += `
                                <li class="d-flex align-items-center mb-3">
                                    <input class="me-3 form-check-input item-checkbox" type="checkbox" id="item-${item.id}" data-meal-id="${meal.meal_id}">
                                    <input type="hidden" id="category" value="${item.category}">
                                    <img src="${assetBaseUrl}/${item.image || ''}" alt="${item.title}" class="me-3" style="width:50px;height:50px;object-fit:cover;border-radius:4px;background-color:#f1f1f1;">
                                    <div>
                                        <span style="font-weight: 600; color: #4b5c6b;">${item.title}</span><br>
                                        <span style="font-size: 0.97rem;"><b>QTY:</b> ${qtyText}</span>
                                    </div>
                                </li>
                            `;
                        });

                        modalContent += `</ul></div></div>`;
                    });

                    contentContainer.html(modalContent);

                    // === Checkbox Logic ===
                    // Select All
                    $('#selectAllCheckbox').on('change', function() {
                        const isChecked = $(this).is(':checked');
                        $('.meal-checkbox, .item-checkbox').prop('checked', isChecked);
                    });

                    // Meal checkbox controls items
                    $('.meal-checkbox').on('change', function() {
                        const mealId = $(this).data('meal-id');
                        const isChecked = $(this).is(':checked');
                        $(`.item-checkbox[data-meal-id="${mealId}"]`).prop('checked', isChecked);
                    });

                    // Item checkbox updates meal checkbox
                    $('.item-checkbox').on('change', function() {
                        const mealId = $(this).data('meal-id');
                        const items = $(`.item-checkbox[data-meal-id="${mealId}"]`);
                        const allChecked = items.length === items.filter(':checked').length;
                        $(`#meal-${mealId}-checkbox`).prop('checked', allChecked);
                    });
                    hideLoader();

                },
                error: function(xhr) {
                    console.error('Error fetching meals:', xhr);
                    contentContainer.html('<p>Error loading data.</p>');
                    hideLoader();

                }
            });
        });

        $(document).on('click', '#print-shopping-list', function() {
            let aggregatedItems = {};

            // Loop through checked checkboxes inside shopping list modal
            $('#shoppingListModal .item-checkbox:checked').each(function() {
                const listItem = $(this).closest('li');

                const itemName = listItem.find('span').first().text().trim() || "Unnamed";
                const category = listItem.find('input[type="hidden"]#category').val()?.trim() || "Uncategorized";

                const qtyContainer = listItem.find('div > span:contains("QTY:")');
                if (qtyContainer.length === 0) return;

                const fullText = qtyContainer.text().trim();
                const qtyTextMatch = fullText.match(/QTY:\s*(.+)/i);
                if (!qtyTextMatch) return;

                const qtyText = qtyTextMatch[1];
                const qtyParts = qtyText.split(" or ").map(part => part.trim());

                qtyParts.forEach(part => {
                    const match = part.match(/^([\d¼½¾/.]+)\s*([a-zA-Z\s]+)$/);
                    if (!match) return;

                    let qtyRaw = match[1].trim();
                    let unit = match[2].trim();

                    const unicodeFractions = {
                        '¼': 0.25,
                        '½': 0.5,
                        '¾': 0.75
                    };
                    let qty = unicodeFractions[qtyRaw] ?? null;

                    if (qty === null) {
                        if (qtyRaw.includes('/')) {
                            const [num, den] = qtyRaw.split('/').map(Number);
                            if (!isNaN(num) && !isNaN(den) && den !== 0) {
                                qty = num / den;
                            }
                        } else {
                            qty = parseFloat(qtyRaw);
                        }
                    }

                    if (!qty || isNaN(qty)) return;

                    if (!aggregatedItems[category]) aggregatedItems[category] = {};
                    if (!aggregatedItems[category][itemName]) aggregatedItems[category][itemName] = {};
                    if (!aggregatedItems[category][itemName][unit]) aggregatedItems[category][itemName][unit] = 0;

                    aggregatedItems[category][itemName][unit] += qty;
                });
            });

            // Build final HTML content in new style
            let printHtml = '';

            for (let [category, items] of Object.entries(aggregatedItems)) {
                printHtml += `
                    <div style="margin-bottom: 12px">
                        <span style="font-size: 14px; font-weight: 600; color: #3b3b3b;">${category}</span>
                        <ul style="margin: 6px 0 0 0; list-style: none">
                `;

                for (let [itemName, unitMap] of Object.entries(items)) {
                    const qtyText = Object.entries(unitMap).map(([unit, total]) => {
                        const rounded = Math.round(total * 100) / 100;
                        if (rounded === 0.25) return `¼ ${unit}`;
                        if (rounded === 0.5) return `½ ${unit}`;
                        if (rounded === 0.75) return `¾ ${unit}`;
                        if (['g', 'ml', 'mL'].includes(unit)) return `${Math.round(total)}${unit}`;
                        return `${rounded} ${unit}`;
                    }).join(' or ');

                    printHtml += `
                    <li style="display: flex; align-items: center; font-size: 14px; font-weight: 400; color: #3b3b3b; line-height: 1.4; padding: 4px 0;">
                        <span style="display: inline-block; width: 16px; height: 16px; border: 1px solid #3b3b3b; margin-right: 8px; box-sizing: border-box;"></span>
                        <span style="flex: 1;">${qtyText} ${itemName}</span>
                    </li>`;
                }

                printHtml += '</ul></div>';
            }

            if (printHtml.trim() === '') {
                printHtml = '<p>No items selected.</p>';
            }

            $('#print-shopping-list-modal #shopping-list-content').html(printHtml);
            $('#print-shopping-list-modal').fadeIn();

            const printShoppingListModal = document.getElementById('print-shopping-list-modal');
            const printShoppingListClose = document.getElementById('print-shopping-list-close');

            // Show modal
            printShoppingListModal.style.display = 'block';

            // Close modal handler (only bind once)
            printShoppingListClose.onclick = () => {
                printShoppingListModal.style.display = 'none';
            };

            printShoppingListModal.onclick = (e) => {
                if (e.target === printShoppingListModal) {
                    printShoppingListModal.style.display = 'none';
                }
            };
        });

        $(document).on('click', '#download-pdf', function() {
            showLoader();
            const content = document.querySelector('#print-shopping-list-modal #shopping-list-content');

            if (!content || content.innerHTML.trim() === '') {
                $('#errormodalmain').modal('show');
                return;
            }

            // Create a temporary container with a header and the shopping list content
            const container = document.createElement('div');
            container.innerHTML = `
                <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; color: #222;">
                    <h2 style="text-align: center; margin-bottom: 20px;">Shopping List</h2>
                    ${content.innerHTML}
                </div>
            `;
            // console.log(container.innerHTML);
            // PDF generation options
            // const options = {
            //     margin: [0.5], // top, right, bottom, left (in inches)
            //     filename: 'shopping_list.pdf',
            //     html2canvas: {
            //         scale: 2
            //     },
            //     jsPDF: {
            //         unit: 'in',
            //         format: 'letter',
            //         orientation: 'portrait'
            //     }
            // };

            // Generate and download the PDF
            // html2pdf().set(options).from(container).save();
              html2pdf().from(container).set({
                margin: 0.5,
                filename: 'shopping_list.pdf',
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            }).save();
            hideLoader();
            // Close the modal after download
            $('#print-shopping-list-modal').fadeOut();
            // Reset the modal content
            $('#print-shopping-list-modal #shopping-list-content').html('');
            // Hide the modal
            $('#shoppingListModal').modal('hide');

        });

        $(document).on('click', ".print-plan-btn", function () {
            showLoader();
            const planId = $(this).data("plan-id");
            const userId = $(this).data("user-id");

            // ✅ Bootstrap 5 modal instance
            const printPlanModalEl = document.getElementById('print-plan-modal');
            const printPlanModal = new bootstrap.Modal(printPlanModalEl);

            printPlanModal.show(); // ✅ Show the modal
            showLoader();
            // ✅ Reset preview content with loading text
            $("#pdf-preview").html(`<div class="py-4 text-center">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>`);

            // ✅ Fetch preview content via AJAX and inject
            fetch("{{ route('plans.preview', ':id') }}".replace(':id', planId) + "?user_id=" + userId)
                .then(res => {
                    if (!res.ok) throw new Error("Failed to load preview");
                    return res.text();
                })
                .then(html => {
                    $("#pdf-preview").html(html); // ✅ Inject fetched HTML
                })
                .catch(err => {
                    $("#pdf-preview").html('<div class="py-4 text-danger">Error loading preview</div>');
                });

                hideLoader();
        });

        $('#print-plan-modal').on('hide.bs.modal', function () {
            window.location.reload(); // Reload page to reset state
        });

        $('#shoppingListModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();
            $(this).find('.modal-body').html(''); // Clear modal content
        });

    });

    window.logoBase64 = 'data:image/jpg;base64,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'; // Replace with base64 logo image

    function downloadPDF() {
        showLoader();
        const element = document.getElementById("pdf-content");
        const images = element.querySelectorAll("img");

        const promises = Array.from(images).map(img => {
            return toDataURL(img.src).then(dataUrl => {
                img.setAttribute("src", dataUrl);
            }).catch(err => {
                console.warn("Image failed to load as base64:", img.src);
            });
        });

        Promise.all(promises).then(() => {

            // Set margins (in inches: 1in = 25.4mm = 72pt)
            const topMargin = 0.3; // ~15mm
            const bottomMargin = 1.0; // ~18mm (footer + buffer)
            const leftRightMargin = 0.3;

            html2pdf()
                .set({
                    margin: [topMargin, leftRightMargin, bottomMargin, leftRightMargin],
                    filename: 'print-plan.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
                            pagebreak: { mode: ['css', 'legacy'] } // <-- Add this line

                })
                .from(element)
                .toPdf()
                .get('pdf')
                .then(pdf => {
                    const totalPages = pdf.internal.getNumberOfPages();
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();

                    // Footer settings
                    const footerHeight = 0.5; // in inches
                    const footerY = pageHeight - bottomMargin + 0.15; // 0.15in above page bottom

                    // Logo
                    const logoWidth = 1.2;
                    const logoHeight = 0.2;
                    const logoX = 0.5;
                    const logoY = footerY + (footerHeight - logoHeight) / 2;

                    // Circle (page number)
                    const circleRadius = 0.15;
                    const circleCenterX = pageWidth / 2;
                    const circleCenterY = footerY + footerHeight / 2;

                    // Right-side text
                    const dateText = `Nutrition Training Plan | ${new Date().toLocaleDateString('en-GB')}`;
                    const dateFontSize = 9; // smaller font
                    const dateColor = "#649ef7"; // blue
                    const dateX = pageWidth - 0.5;
                    const dateY = circleCenterY + 0.04; // align with circle

                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);

                        // Draw a white rectangle to clear the footer area
                        pdf.setFillColor(255, 255, 255);
                        pdf.rect(
                            0,
                            pageHeight - bottomMargin,
                            pageWidth,
                            footerHeight,
                            'F'
                        );

                        // Add logo (left, vertically centered)
                        if (window.logoBase64) {
                            pdf.addImage(window.logoBase64, 'PNG', logoX, logoY, logoWidth, logoHeight);
                        }

                        // Draw blue circle for page number (center)
                        // pdf.setDrawColor(0, 116, 217); // blue border (optional)
                        // pdf.setFillColor(0, 116, 217); // blue fill
                        // pdf.circle(circleCenterX, circleCenterY, circleRadius, 'F');

                        // Page number in white, centered in the circle
                        pdf.setTextColor(0, 116, 217);
                        pdf.setFontSize(11);
                        pdf.setFont(undefined, 'bold');

                        // Center vertically and horizontally
                        pdf.text(`${i}`, circleCenterX, circleCenterY, { align: 'center', baseline: 'middle' });

                        // Date text (right, blue, smaller font, vertically centered)
                        pdf.setTextColor(0, 116, 217); // blue
                        pdf.setFontSize(dateFontSize);
                        pdf.setFont(undefined, 'normal');
                        pdf.text(dateText, dateX, dateY, { align: 'right', baseline: 'middle' });
                    }
                })
                .save();
        });
        hideLoader();
    }

    // Helper to convert images to base64
    function toDataURL(url) {
        return fetch(url, {
                mode: 'cors'
            })
            .then(response => response.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }

    function toDataURL(url) {
        return fetch(url, {
                mode: 'cors'
            })
            .then(response => response.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }

    $(document).ready(function () {
        // Open Bootstrap modal on meal click
        $('.quick-view-btn').on('click', function () {
            const user_meal_id = $(this).data('meal-id');
            const user_plan_id = $(this).data('user-plan-id');
            const user_sub_category_id = $(this).data('sub-category-id');
            const user_category_id = $(this).data('category-id');
            showLoader();

            $.ajax({
                url: "{{ route('front.meal.details') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_meal_id,
                    user_plan_id,
                    user_sub_category_id,
                    user_category_id
                },
                success: function (response) {
                    const meal = response.meal;

                    // 🖼️ Set meal title and description
                    $('#recipeDialogModal .modal-body .dialog-header h2').text(meal.meal.title || 'Meal');
                    $('#recipeDialogModal .modal-body .dialog-header p').text(meal.meal.description || '');

                    const imageUrl = meal.meal.image
                        ? `{{ asset('storage') }}/` + meal.meal.image
                        : `{{ asset('front/images/placeholder.png') }}`;
                    $('#recipeDialogModal .modal-body .dialog-img').attr('src', imageUrl);

                    // 🥣 Ingredients
                    let ingredientsHtml = '';
                    meal.user_items.forEach(function (userItem) {
                        const item = userItem.item;
                        if (!item) return;

                        const selectedUnits = item.selected_qty_unit || [];
                        const selected = selectedUnits.find(u => u.checked) || null;

                        let qty = '';
                        let unit = '';

                        if (selected) {
                            qty = selected.qty;
                            unit = selected.unit?.trim();
                        } else {
                            qty = item.qty;
                            unit = item.unit?.trim();
                        }

                        const noSpaceUnits = ['g', 'ml', 'mL'];
                        const space = noSpaceUnits.includes(unit) ? '' : ' ';

                        ingredientsHtml += `<li>${qty}${space}${unit} ${item.title}</li>`;
                    });

                    $('#recipeDialogModal .modal-body ul').html(ingredientsHtml);

                    // 📝 Instructions / Note
                    if (meal.meal.note && meal.meal.note.trim() !== '') {
                        $('#recipeDialogModal .modal-body .note').html(
                            `<strong>Note:</strong> ${meal.meal.note}`
                        ).show();
                        $('#recipeDialogModal .modal-body h3:contains("Instructions")').show();
                    } else {
                        $('#recipeDialogModal .modal-body .note').hide();
                        $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
                    }
                    // $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();

                    // 🔢 Nutrition Info
                    $('#recipeDialogModal .modal-body .nutrition-info').html(`
                        <span style="color: #a60015">●  <span style="color:rgba(59, 59, 59, 1)">Protein: ${(Number(response.totalProtein) || 0).toFixed(2)} g</span></span>
                        <span style="color: #3e8e00">●  <span style="color:rgba(59, 59, 59, 1)">Carb: ${(Number(response.totalCarbs) || 0).toFixed(2)} g</span></span>
                        <span style="color: #0077b6">●  <span style="color:rgba(59, 59, 59, 1)">Fat: ${(Number(response.totalFats) || 0).toFixed(2)} g</span></span>
                        <span style="color: #967500">●  <span style="color:rgba(59, 59, 59, 1)">Energy: ${(Number(response.totalEnergy) || 0).toFixed(2)} kJ</span></span>
                    `);

                    // Set data attributes for Smart Swap
                    $('#recipeDialogModal .modal-body .smart-swap-btn')
                        .attr('data-meal-id', user_meal_id)
                        .attr('data-user-plan-id', user_plan_id)
                        .attr('data-sub-category-id', user_sub_category_id)
                        .attr('data-category-id', user_category_id)
                        .attr('data-meal-name', meal.meal.title);

                    // 👁️ Show Bootstrap modal
                    const modal = new bootstrap.Modal(document.getElementById('recipeDialogModal'));
                    modal.show();
                    hideLoader();
                },
                error: function () {
                    $('#errormodalmain').modal('show');
                    hideLoader();
                }
            });
        });

        $(document).on('hide.bs.modal', '#smartSwapModal', function () {
            currentMainItem = null;
            swaps = []; // Reset swaps array
            $('#smartSwapModalLabel').text(''); // Clear modal title
            $('#smartSwapModal .swap-list').empty(); // Clear HTML inside modal
            $('.modal-backdrop').remove();
        });
    });

    $(document).on('hide.bs.modal', '#recipeDialogModal', function () {
        // Clear the modal content when it is closed
        $('#recipeDialogModal .modal-body .dialog-header h2').text('');
        $('#recipeDialogModal .modal-body .dialog-header p').text('');
        $('#recipeDialogModal .modal-body .dialog-img').attr('src', '');
        $('#recipeDialogModal .modal-body ul').empty();
        $('#recipeDialogModal .modal-body .note').hide();
        $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
        $('#recipeDialogModal .modal-body .nutrition-info').empty();
        $('.modal-backdrop').remove();
    });

    $(document).on('click', '.meal-item-btn', function () {
        const $btn = $(this);

        const meal_id = $btn.attr('data-meal-id');
        const meal_name = $btn.attr('data-meal-name');
        const user_meal_id = $btn.attr('data-meal-id');
        const userPlanId = $btn.attr('data-user-plan-id');
        const userSubCategoryId = $btn.attr('data-sub-category-id');
        const userCategoryId = $btn.attr('data-category-id');

        $('#recipeDialogModal').modal('hide');
        mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId);
    });

    function mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId) {
        const modalEl = $('#mealItemModel');
        const modal = new bootstrap.Modal(modalEl[0]);
        modal.show();

        const $mealItemsModalLabel = $('.swap-title'); // Set meal name here
        const $mealItemsContainer = $('.swap-list'); // Container for item cards
        const $mealItemsLoadingSpinner = $('#mealItemsLoadingSpinner'); // Optional: add loading spinner if you want

        if (!user_meal_id || !meal_name) {
            console.error('Invalid meal data.');
            return;
        }

        $mealItemsModalLabel.text(meal_name);
        $mealItemsContainer.empty();

        // Optional: show spinner
        // $mealItemsLoadingSpinner.show();

        $.ajax({
            url: '{{ route('front.meals.items', ':mealId') }}'
                .replace(':mealId', meal_id) +
                `?user_meal_id=${user_meal_id}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.items && data.items.length > 0) {
                    $.each(data.items, function (index, item) {
                        let displayQty = '';
                        let selectedUnits = [];

                        try {
                            selectedUnits = typeof item.selected_qty_unit === 'string'
                                ? JSON.parse(item.selected_qty_unit)
                                : Array.isArray(item.selected_qty_unit)
                                    ? item.selected_qty_unit
                                    : [];
                        } catch (e) {
                            console.warn('Failed to parse selected_qty_unit for item:', item.name, e);
                        }

                        const checkedUnits = selectedUnits.filter(u =>
                            u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                        );

                        if (checkedUnits.length > 0) {
                            const formattedUnits = checkedUnits.map(u => {
                                let qtyText = u.qty?.toString().trim() || '';
                                const unitText = (u.unit || '').trim();
                                const needsSpace = !["g", "ml", "mL"].includes(unitText.toLowerCase());

                                const numericQty = Number(qtyText);
                                if (!isNaN(numericQty)) {
                                    qtyText = numericQty % 1 === 0 ? numericQty.toFixed(0) : numericQty.toFixed(1);
                                }

                                return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                            });

                            displayQty = formattedUnits.join(' or ');
                        }

                        if (!displayQty && item.qty && item.unit) {
                            const unit = item.unit.toString();
                            const needsSpace = !["g", "ml", "mL"].includes(unit.toLowerCase());
                            displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;
                        }

                        const itemCard = `
                            <div class="swap-item">
                                <img src="${item.image}" alt="${item.name}" class="swap-item-img" />
                                <div class="flex-wrapper">

                                <div class="swap-item-info">
                                    <div class="swap-item-name">${item.name}</div>
                                    <div class="swap-item-qty"><b>Qty :</b> ${displayQty}</div>
                                </div>
                                <div class="swap-item-actions">
                                    ${item.swapItems?.length > 0 ? `
                                        <button class="smart-swap-btn item-swap-btn"
                                            data-item-id="${item.id}"
                                            data-item-name="${item.name}"
                                            data-user-item-id="${item.user_item_id}"
                                            data-user-meal-id="${item.user_meal_id}"
                                            data-user-plan-id="${userPlanId}"
                                            data-sub-category-id="${userSubCategoryId}"
                                            data-user-category-id="${userCategoryId}">
                                            <img src="{{ frontAssets('images/dialog/swap.svg') }}" style="width: 18px; vertical-align: middle; margin-right: 4px;" />
                                            <span>Swap</span>
                                        </button>` : ''}
                                    ${item.description ? `
                                        <button class="smart-swap-btn" data-bs-toggle="tooltip" title="${item.description}">
                                            <img src="{{ frontAssets('images/dialog/Info.svg') }}" alt="Info" style="width: 24px; vertical-align: middle" />
                                        </button>` : ''}
                                </div>
                                 </div>
                                </div>
                            </div>
                        `;

                        $mealItemsContainer.append(itemCard);
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip();

                } else {
                    $mealItemsContainer.html('<p class="text-center">No foods available in this meal.</p>');
                }

                // Optional: hide spinner
                // $mealItemsLoadingSpinner.hide();
            },
            error: function () {
                $mealItemsContainer.html('<p class="text-danger text-center">Failed to load foods.</p>');
                // $mealItemsLoadingSpinner.hide();
            }
        });
    }

    $(document).on('click', '.meal-item-modal-close', function () {
        const modalEl = $('#mealItemModel')[0];
        const modalInstance = bootstrap.Modal.getInstance(modalEl);

        if (modalInstance) {
            modalInstance.hide();
        } else {
            // fallback if instance wasn't created by Bootstrap JS
            const newModal = new bootstrap.Modal(modalEl);
            newModal.hide();
        }
    });

    $(document).on('click', '.item-swap-btn', function () {
        const itemId = $(this).data('item-id');
        const itemName = $(this).data('item-name');
        const userItemId = $(this).data('user-item-id');
        const userMealId = $(this).data('user-meal-id');
        const userPlanId = $(this).data('user-plan-id');
        const userSubCategoryId = $(this).data('sub-category-id');
        const userCategoryId = $(this).data('user-category-id');

        if (!itemId || !itemName) {
            console.error('Invalid item data.');
            return;
        }

        $('.apply-changes-btn').attr('data-user-item-id', userItemId);
        $('.apply-changes-btn').attr('data-user-meal-id', userMealId);
        $('.apply-changes-btn').attr('data-user-plan-id', userPlanId);
        $('.apply-changes-btn').attr('data-user-sub-category-id', userSubCategoryId);
        $('.apply-changes-btn').attr('data-user-category-id', userCategoryId);

        // Show the modal first
        const modal = new bootstrap.Modal(document.getElementById('smartSwapModal'));
        modal.show();

        // Update the title in the modal
        $('#smartSwapModalLabel').text(`Swap: ${itemName}`);

        // Clear existing items
        const $swapList = $('#smartSwapModal .swap-list');
        $swapList.html(`
            <div class="py-4 text-center">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        `);

        // Perform AJAX request to fetch swap items
        $.ajax({
            url: '{{ route('front.items.swap-items', ':id') }}'.replace(':id', itemId) +
                `?user_meal_id=${userMealId}&user_item_id=${userItemId}&user_plan_id=${userPlanId}&sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (!data || !data.items || !data.items.length) {
                    $swapList.html('<p class="text-muted text-center">No swap items available.</p>');
                    return;
                }

                // ✅ Helper: Format Qty Unit String
                function formatQtyUnit(unitsArray, fallbackQty, fallbackUnit) {
                    if (!unitsArray || !unitsArray.length) {
                        return formatUnitText(fallbackQty, fallbackUnit);
                    }

                    const checked = unitsArray.find(u => u.checked);
                    if (checked) {
                        return formatUnitText(checked.qty, checked.unit);
                    }

                    return formatUnitText(fallbackQty, fallbackUnit);
                }

                function formatUnitText(qty, unit) {
                    qty = qty ?? '1';
                    unit = (unit || '').trim();

                    if (!unit) return qty;

                    const compactUnits = ['g', 'ml', 'mL'];
                    if (compactUnits.includes(unit)) {
                        return `${qty}${unit}`;
                    }

                    return `${qty} ${unit}`;
                }

                // ✅ Build Main Item HTML
                const item = data.item;
                const mainQtyText = formatQtyUnit(item.selected_qty_unit, item.qty, item.unit);
                let mainItem = `
                    <div class="swap-item" id="mainSwapItem" data-item-id="${item.id}" style="border-bottom: none">
                        <img src="${data.item_image}" alt="${data.item_name}" class="swap-item-img"/>
                         <div class="flex-wrapper">
                        <div class="swap-item-info">
                            <div class="swap-item-name">${data.item_name}</div>
                            <div class="swap-item-qty"><b>Qty:</b> ${mainQtyText}</div>
                        </div>
                        <div class="swap-item-actions">
                            ${item.description ? `
                                <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${item.description}">
                                    <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                                </button>
                            ` : ''}
                        </div>
                        </div>
                    </div>

                    <div class="swap-item swap-item-h3"><h3>Swap with</h3></div>
                `;

                // ✅ Build Swap Items HTML
                let swapItemsHTML = '';
                data.items.forEach(function (swapItem) {
                    const swapQtyText = formatQtyUnit(swapItem.selected_qty_unit, swapItem.swap_item_qty, swapItem.swap_item_unit);

                    swapItemsHTML += `
                        <div class="swap-item">
                            <img src="${swapItem.swap_item_image}" alt="${swapItem.swap_item_name}" class="swap-item-img"/>
                             <div class="flex-wrapper">
                            <div class="swap-item-info">
                                <div class="swap-item-name">${swapItem.swap_item_name}</div>
                                <div class="swap-item-qty"><b>Qty:</b> ${swapQtyText}</div>
                            </div>
                            <div class="swap-item-actions">
                                <button class="smart-swap-btn swap-btn" data-swap-item-id="${swapItem.swap_item_id}">
                                    <img src="{{ frontAssets('images/dialog/swap.svg') }}" style="width: 18px; margin-right: 4px;" /><span>Swap</span>
                                </button>
                                ${swapItem.swap_item_description ? `
                                    <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${swapItem.swap_item_description}">
                                        <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                                    </button>
                                ` : ''}
                            </div>
                             </div>
                        </div>
                    `;
                });

                // ✅ Inject into DOM
                $swapList.html(mainItem + swapItemsHTML);

                // ✅ Enable Bootstrap 5 tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('#mealItemModel').modal('hide');
            },
            error: function (xhr, status, error) {
                $swapList.html('<p class="text-danger text-center">Failed to load swap items.</p>');
                console.error('Error loading swap items:', error);
            }
        });

    });

    $('#mealItemModel').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
        $('#mealItemsContainer').empty();
        $('#mealItemsLoadingSpinner').hide();
    });

    let currentMainItem = null;
    let swaps = []; // Array to hold latest swap pair

    $(document).on('click', '.swap-btn', function () {
        const $clickedSwap = $(this).closest('.swap-item');
        const swapItemId = $(this).data('swap-item-id');

        const $mainItem = $('#mainSwapItem'); // ✅ SELECTS THE MAIN ITEM CORRECTLY NOW
        const mainItemId = $mainItem.data('item-id'); // You must set this in HTML: data-item-id="${item.id}"

        // Capture current main item details if not already stored
        if (!currentMainItem) {
            currentMainItem = {
                id: mainItemId,
                name: $mainItem.find('.swap-item-name').text(),
                qty: $mainItem.find('.swap-item-qty').text().replace('Qty:', '').trim(),
                description: $mainItem.find('.info-btn').attr('data-bs-original-title') || '',
                image: $mainItem.find('img.swap-item-img').attr('src')
            };
        }

        // Get clicked swap item details
        const swapItem = {
            id: swapItemId,
            name: $clickedSwap.find('.swap-item-name').text(),
            qty: $clickedSwap.find('.swap-item-qty').text().replace('Qty:', '').trim(),
            description: $clickedSwap.find('.info-btn').attr('data-bs-original-title') || '',
            image: $clickedSwap.find('img.swap-item-img').attr('src')
        };

        // === Update Main Item UI ===
        $mainItem.find('.swap-item-name').text(swapItem.name);
        $mainItem.find('.swap-item-qty').html('<b>Qty:</b> ' + swapItem.qty);
        $mainItem.find('img.swap-item-img').attr('src', swapItem.image);
        $mainItem.find('[data-bs-toggle="tooltip"]').tooltip('dispose');
        $mainItem.find('.info-btn').remove();

        if (swapItem.description) {
            $mainItem.find('.swap-item-actions').append(`
                <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${swapItem.description}">
                    <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                </button>
            `);
        }

        // === Replace clicked swap item with the original main item ===
        const revertedHTML = `
            <img src="${currentMainItem.image}" alt="${currentMainItem.name}" class="swap-item-img"/>
             <div class="flex-wrapper">
            <div class="swap-item-info">
                <div class="swap-item-name">${currentMainItem.name}</div>
                <div class="swap-item-qty"><b>Qty:</b> ${currentMainItem.qty}</div>
            </div>
            <div class="swap-item-actions">
                <button class="smart-swap-btn swap-btn" data-swap-item-id="${currentMainItem.id}">
                    <img src="{{ frontAssets('images/dialog/swap.svg') }}" style="width: 18px; margin-right: 4px;" /><span>Swap</span>
                </button>
                ${currentMainItem.description ? `
                    <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${currentMainItem.description}">
                        <img src="{{ frontAssets('images/dialog/Info.svg') }}" style="width: 18px" />
                    </button>` : ''}
            </div>
             </div>
        `;

        $clickedSwap.html(revertedHTML);

        // ✅ Update swap tracking (replace last entry if already swapped)
        swaps = [{
            main_id: swapItem.id,
            swap_id: currentMainItem.id,
            user_item_id: currentMainItem.id // or use some real user_item_id if needed
        }];

        // Update the reference for next potential swap
        currentMainItem = swapItem;

        // Reinitialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    // Apply Swap Changes functionality
    $('body').on('click', '.apply-changes-btn', function () {
        // Send all swaps to the server
        const userItemId = $(this).data('user-item-id');
        const userMealId = $(this).data('user-meal-id');
        const userPlanId = $(this).data('user-plan-id');
        const userSubCategoryId = $(this).data('user-sub-category-id');
        const userCategoryId = $(this).data('user-category-id');

        $.ajax({
            url: "{{ route('front.items.swaps') }}", // Laravel route to handle the request
            method: "GET",
            data: {
                swaps: swaps,
                meal_id: userMealId,
                user_item_id: userItemId,
                user_meal_id: userMealId,
                user_category_id: userCategoryId,
                user_sub_category_id: userSubCategoryId,
                user_plan_id: userPlanId,
                user_id: userId,
            },
            success: function (response) {
                // Handle success response
                swaps = [];
                currentMainItem = null;

                if(response.success){
                    $('#smartSwapModal').modal('hide');
                    var meal_id = response.data['meal_id'];
                    var meal_name = response.data['meal_name'];
                    var user_meal_id = response.data['user_meal_id'];
                    mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId);
                } else {
                    $('#errormodalmain .modal-body').html(`<h4>Ooops!</h4><p>${response.message}</p>`);
                    $('#errormodalmain').modal('show');
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                if (xhr.status === 422) {
                    // Laravel-style validation error handling
                    let errors = xhr.responseJSON?.errors;
                    let messageHtml = '';

                    if (errors) {
                        // fallback if error messages not formatted
                        messageHtml = '<h4>Ooops!</h4><p>Invalid swap. Please check and try again.</p>';
                    }

                    $('#errormodalmain .modal-body').html(messageHtml);
                } else {
                    // Generic fallback for other HTTP errors
                    $('#errormodalmain .modal-body').html('<h4>Ooops!</h4>	<p>Invalid swap. Please try again later.</p>');
                }

                $('#errormodalmain').modal('show');
            }
        });
    });

    $('#errormodalmain').on('hidden.bs.modal', function () {
        $(this).find('.modal-body').html('');
        $('.modal-backdrop').remove();
    });
    $('#shoppingListModal').on('hidden.bs.modal', function () {
        $(this).find('.modal-body').html('');
        $('.modal-backdrop').remove();
    });

    document.addEventListener('DOMContentLoaded', function() {
        var inviteDiv = document.querySelector('.share-dropdown-item');
        if(inviteDiv) {
            inviteDiv.addEventListener('click', function(e) {
                e.preventDefault();
                var comingSoonModal = new bootstrap.Modal(document.getElementById('comingSoonModal'));
                comingSoonModal.show();
            });
        }
    });
    // card slider functionality
    $(document).ready(function() {
        // For each slider-wrapper (handles multiple carousels if present)
        $('.slider-wrapper').each(function(idx) {
            var $wrapper = $(this);
            var $scroll = $wrapper.find('.challenge-cards');
            var $cards = $scroll.find('.challenge-card');
            var cardWidth = $cards.length ? $cards.outerWidth(true) : 200;

            $wrapper.find('.left-arrow').on('click', function(e) {
                e.preventDefault();
                var before = $scroll.scrollLeft();
                $scroll.animate({ scrollLeft: before - cardWidth }, 300, function() {
                    var after = $scroll.scrollLeft();
                    console.log(`[Slider ${idx}] Left arrow clicked. ScrollLeft before: ${before}, after: ${after}`);
                });
            });

            $wrapper.find('.right-arrow').on('click', function(e) {
                e.preventDefault();
                var before = $scroll.scrollLeft();
                $scroll.animate({ scrollLeft: before + cardWidth }, 300, function() {
                    var after = $scroll.scrollLeft();
                    console.log(`[Slider ${idx}] Right arrow clicked. ScrollLeft before: ${before}, after: ${after}`);
                });
            });
        });
    });
    $(document).ready(function() {
        $('.slider-wrapper').each(function() {
            var $wrapper = $(this);
            var $cards = $wrapper.find('.challenge-card');
            if ($cards.length <= 4) {
                $wrapper.find('.slider-arrow').hide();
            } else {
                $wrapper.find('.slider-arrow').show();
            }
        });
    });
</script>
@endsection