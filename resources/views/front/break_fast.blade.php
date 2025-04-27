@extends(frontView('layouts.app'))

@section('title', $userMealTime->mealTime->name)

@section('content')
<!-- <style>
    .navbar-nav .nav-link.active {
        background-color: #649ef7; /* Black background */
        color: #fff !important;  /* White text for better contrast */
        border-radius: 5px;      /* Optional: Rounded corners for a smoother look */
        padding: 8px 15px;  
        border: 2px solid #649ef7 !important;
    }
</style> -->
<div class="section nutrition-plan-hero pt-5 pb-3"> 
    <div class="container">
        <div class="row align-items-top">
            <div class="col-md-6 col-lg-5">
                <div class="nutrition-plan-text">
                    <h1> <span class="text-primary">Healthy {{ $userMealTime->mealTime->title }} Meals</span></h1>
                    <p>Boost your energy and health with the right supplements!</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 ms-lg-auto">
                
                <div class="mt-2 mealtime-btn-list">
                    <ul class="">
                        @if($userPlan->userMealTimes->count())
                            @foreach($userPlan->userMealTimes as $plan)
                            <li class="m-2" style="">
                                <a class="@if($userMealTime->mealTime->id == $plan->mealTime->id) active btn btn-outline-primary btn-sm  text-white @else bg-white btn btn-outline-secondary text-black  @endif" aria-current="page" href="{{ route('front.meal-time.details', ['id' => $plan->mealTime->id, 'plan_id' => $userPlan->id]) }}" >{{ $plan->mealTime->title }}</a>
                            </li>
                            
                            @endforeach
                        @endif
                    </ul>
                           
                </div>
            </div>
        </div>
        <div class="plan-buttons-link">
            <div class="d-flex flex-wrap align-items-center">
                <!-- <a href="{{ route('front.plans.details', ['id' => $userPlan->plan_id, 'user_id' => $userPlan->user_id]) }}">View Plan</a> -->
                <a href="javascript:void(0);" class="ms-0 print-plan-btn " data-user-id="{{ $userPlan->user_id}}" data-plan-id="{{ $userPlan->plan_id}}">Print Plan</a>
                <a href="#" class="" data-bs-toggle="modal" data-bs-target="#ShoppingModal" id="fetchAllMeals">Shopping List</a>
                <a href="{{ route('front.plans.details', ['id' => $userPlan->plan_id, 'user_id' => $userPlan->user_id]) }}" class="btn btn-primary ms-auto text-white border-0">Back</a>
            </div>
        </div>
    </div>
    
</div>
    
<div class="section bg-white pt-3 mb-5" id="nextSection">
    <div class="container">
        <div class="main-category-list mt-4">
            <div class="row g-0">
                @foreach($userMealTime->userCategories as $item)
                <div class="col-md-3">
                    <div class="nutrition-plan-box h-100 d-flex flex-column">
                        <figure>
                            <img src="{!! asset('private/public/storage/' . $item->category->image) !!} " alt="">
                        </figure>
                        <h5 class="mb-3">{{ $item->category->title }}</h5>
                        <a href="javascript:void(0)" class="btn btn-primary view-details-btn mt-auto" data-category-id="{{ $item->category->id }}" data-user-category-id="{{ $item->id }}" data-category-name="{{ $item->category->title }}">View Details</a>
                        <!-- <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button> -->
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="mealModel" tabindex="-1" aria-labelledby="subcategoryItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealModalLabel">Title</h5>
                    <button type="button" class="btn-close meal-modal-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mealModelLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Subcategories Content -->
                    <div id="mealModelContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="mealItemModel" tabindex="-1" aria-labelledby="mealItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealItemsModalLabel">Title</h5>
                    <button type="button" class="btn-close meal-item-model-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mealItemsLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Subcategories Content -->
                    <div id="mealItemsContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="itemSwapModal" tabindex="-1" aria-labelledby="swapItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsSwapModalLabel">Title</h5>
                    <button type="button" class="btn-close item-swap-modal-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="itemsSwapLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- items swap Content -->
                    <div id="itemsSwapContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="apply-changes-btn btn btn-primary" data-user-item-id="" data-user-meal-id="">Apply Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Preview Modal -->
    <div class="modal" id="planPreviewModal" tabindex="-1" aria-labelledby="planPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customise your meals before you PRINT plan.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="plan-preview-body">
                    <div class="text-center">Loading preview...</div>
                </div>
                <div class="modal-footer">
                    <form id="downloadPdfForm" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="user_id" value="">
                        <button type="submit" class="btn btn-success">Download PDF</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!-- </div>/#wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

<script>
    const user = @json($userPlan);
    const userId = user.user_id;
    $(document).ready(function () {
        $('body').on('click', '.meal-modal-close', function () {
            $('#mealModel').modal('hide');
        })
        $('body').on('click', '.meal-item-model-close', function () {
            $('#mealItemModel').modal('hide');
            $('#mealModel').modal('show');
        })

        $('.item-swap-modal-close').on('click', function () {
            $('#itemSwapModal').modal('hide');
            $('#mealItemModel').modal('show');

        })

        const $mealModel = $('#mealModel');
        const $mealModalLabel = $('#mealModalLabel');
        const $mealModelContainer = $('#mealModelContainer');
        const $mealModelLoadingSpinner = $('#mealModelLoadingSpinner');

        const $mealItemsModal = $('#mealItemModel');
        const $mealItemsModalLabel = $('#mealItemsModalLabel');
        const $mealItemsContainer = $('#mealItemsContainer');
        const $mealItemsLoadingSpinner = $('#mealItemsLoadingSpinner');

        const $itemSwapModel = $('#itemSwapModal');
        const $itemsSwapModalLabel = $('#itemsSwapModalLabel');
        const $itemsSwapContainer = $('#itemsSwapContainer');
        const $itemsSwapLoadingSpinner = $('#itemsSwapLoadingSpinner');

        // Handle click event to fetch subcategories
        $('body').on('click', '.view-details-btn', function () {
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).data('category-name');
            const userCategoryId = $(this).data('user-category-id');
            if (!categoryId || !categoryName) {
                console.error('Invalid category data.');
                return;
            }

            // Update modal title
            $mealModalLabel.text(categoryName);

            // Clear previous subcategories and show loading spinner
            $mealModelContainer.empty().hide();
            $mealModelLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.category.meals', ':id') }}'.replace(':id', categoryId) + `?user_category_id=${userCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.meals && data.meals.length > 0) {
                        // Populate subcategories into the modal
                        $.each(data.meals, function (index, meal) {
                            const mealCard = `
                            <div class="col-sm-6 col-lg-4">
                            <div class="nutrition-plan-box h-100 d-flex flex-column">
                                <figure>
                                    <img src="${meal.image}" alt="">
                                </figure>
                                <h5 class="mb-3">${meal.name}</h5>
                                <button type="button" class="view-items-btn btn btn-primary mt-auto" data-user-meal-id="${meal.user_meal_id}" data-meal-id="${meal.id}" 
                                data-meal-name="${meal.name}">View Details</button>
                            </div>
                        </div>`;
                            $mealModelContainer.append(mealCard);
                        });
                    } else {
                        $mealModelContainer.html('<p class="text-center">No meals available.</p>');
                    }

                    // Hide loading spinner and show subcategories
                    $mealModelLoadingSpinner.hide();
                    $mealModelContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching meals:', error);
                    $mealModelContainer.html('<p class="text-center text-danger">Failed to load meals.</p>');
                    $mealModelLoadingSpinner.hide();
                    $mealModelContainer.show();
                }
            });

            // Show the modal
            $mealModel.modal('show');
        });

        // Handle click event to fetch meal items
        $('body').on('click', '.view-items-btn', function () {
            const mealId = $(this).data('meal-id');
            const mealName = $(this).data('meal-name');
            const userMealId = $(this).data('user-meal-id');
            // console.log(submealId, submealName);
            if (!mealId || !mealName) {
                console.error('Invalid meal data.');
                return;
            }

            currentMealId = mealId;
            currentMealName = mealName;

            // Update modal title
            $mealItemsModalLabel.text(mealName);

            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.meals.items', ':mealId') }}'.replace(':mealId', mealId) + `?user_meal_id=${userMealId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate items into the modal
                        $.each(data.items, function (index, item) {
                            const unit = item.unit ? item.unit.toString() : '';
                            const needsSpace = !["g", "ml"].includes(unit.toLowerCase());
                            let displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;

                            if (item.selected_qty_unit && Array.isArray(item.selected_qty_unit)) {
                                // const checkedUnits = item.selected_qty_unit.filter(q => q.checked === "true");

                                // if (checkedUnits.length) {
                                    const formattedUnits = item.selected_qty_unit.map(unit => {
                                        const parsedQty = (unit.qty);
                                        const qtyFormatted = (!isNaN(parsedQty) && parsedQty % 1 === 0)
                                            ? (parsedQty)
                                            : unit.qty;

                                        const innerUnit = unit.unit ? unit.unit.toString().toLowerCase() : '';
                                        const space = ["g", "ml"].includes(innerUnit) ? '' : ' ';
                                        return `${qtyFormatted}${space}${unit.unit}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                // }
                            }

                            console.log("displayQty:", displayQty);
                            let infoButton = '';

                            if (item.description) {
                                infoButton = `<button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${item.description}" 
                                    data-item-id="${item.id}" 
                                    data-item-name="${item.name}">
                                    <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                        <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                        <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                    </svg>
                                    Info
                                </button>`;
                            }
                            const swapButton = item.swapItems && item.swapItems.length > 0
                                ? `
                                    <button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}">
                                        <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                            <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                        </svg>
                                        Swap
                                    </button>`
                                : '';
                            const itemCard = `
                                <div class="category-swap-list-box">
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${item.image}" alt="">
                                        </figure>
                                        <div class="info-tootlip">
                                            <p>Food Details</p>
                                            <ul>
                                                <li>Protein: ${item.protein}g</li>
                                                <li>Carbs: ${item.carbs}g</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="align-items-center d-flex m-0 mt-2">
                                            <strong class="me-2 text-nowrap">Qty :</strong>
                                            <span class="m-0">${displayQty}</span>
                                        </p>
                                    </div>
                                    <div class="category-swap-btn">
                                        ${infoButton}
                                        ${swapButton}
                                    </div>
                                </div>`;
                            $mealItemsContainer.append(itemCard);
                        });
                    } else {
                        $mealItemsContainer.html('<p class="text-center">No foods available in this meals.</p>');
                    }

                    // Hide loading spinner and show items
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching meal items:', error);
                    $mealItemsContainer.html('<p class="text-center text-danger">Failed to load foods.</p>');
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                }
            });

            // Show the modal
            $('#mealModel').modal('hide');
            $mealItemsModal.modal('show');
        });

        function formatQty(qty) {
            if (typeof qty === 'string' && /^\d+\/\d+$/.test(qty)) {
                return qty; // keep fraction as-is
            }

            const floatVal = parseFloat(qty);
            if (isNaN(floatVal)) return qty;

            return floatVal % 1 === 0 ? floatVal.toFixed(0) : floatVal.toFixed(1);
        }

        // Handle click event to feth swap items
        $('body').on('click', '.item-swap-btn', function () {
            $('#mealItemModel').modal('hide');
            const itemId = $(this).data('item-id');
            const itemName = $(this).data('item-name');
            const userItemId = $(this).data('user-item-id');
            const userMealId = $(this).data('user-meal-id');
            if (!itemId || !itemName) {
                console.error('Invalid item data.');
                return;
            }
            
            $('.apply-changes-btn').attr('data-user-item-id', userItemId);
            $('.apply-changes-btn').attr('data-user-meal-id', userMealId);

            // Update modal title
            $itemsSwapModalLabel.text(itemName);

            // Clear previous subcategories and show loading spinner
            $itemsSwapContainer.empty().hide();
            $itemsSwapLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.items.swap-items', ':id') }}'.replace(':id', itemId) + `?user_meal_id=${userMealId}&user_item_id=${userItemId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    const $itemsSwapContainer = $('#itemsSwapContainer');
                    $itemsSwapContainer.empty();

                    if (data.items && data.items.length > 0) {
                        // MAIN ITEM HTML
                        let mainItemHTML = `
                            <div class="main-item-box category-swap-list-box">
                                <div class="category-swap-img">
                                    <figure>
                                        <img class="img-thumbnail main-item-img" data-main-id="${data.item_id}" src="${data.item_image}" alt="">
                                    </figure>
                                    <figcaption>${data.item_name}</figcaption>
                               
                                    ${data.item.description ? `
                                            <button class="btn btn-primary rounded-pill py-1 px-4 d-flex align-items-center m-1"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="${data.item.description}"
                                                data-item-id="${data.item_id}"
                                                data-item-name="${data.item_name}">
                                                <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                    <path d="M8 7.8C7.6 7.8 7.3 8.1 7.3 8.5V11.4C7.3 11.8 7.6 12.1 8 12.1C8.4 12.1 8.7 11.8 8.7 11.4V8.5C8.7 8.1 8.4 7.8 8 7.8Z" fill="white"/>
                                                    <path d="M8 4.9C7.6 4.9 7.3 5.2 7.3 5.6C7.3 6 7.6 6.3 8 6.3C8.4 6.3 8.7 6 8.7 5.6C8.7 5.2 8.4 4.9 8 4.9Z" fill="white"/>
                                                </svg>
                                                Info
                                            </button>
                                    ` : ''
                                    } </div>
                            </div>`;

                        // SWAP ITEMS HTML
                        let swapItemsHTML = `<div class="swap-items-container">`;

                        $.each(data.items, function (index, swapitem) {
                            swapItemsHTML += `
                                <div class="category-item-swap category-swap-list-box swap-item" data-swap-id="${swapitem.swap_item_id}">
                                    <div class="category-swap-btn mx-auto">
                                        <button class="swap-button btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" 
                                            data-swap-item-id="${swapitem.swap_item_id}" 
                                            data-swap-item-name="${swapitem.swap_item_name}" 
                                            data-swap-item-img="${swapitem.swap_item_image}" 
                                            data-swap-item-protein="${swapitem.swap_item_protein}" 
                                            data-swap-item-carbs="${swapitem.swap_item_carbs}"
                                            data-user-item-id="${userItemId}">
                                            <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                            <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                        </svg>
                                            Swap
                                        </button>
                                    </div>
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${swapitem.swap_item_image}" alt="">
                                        </figure>
                                        <figcaption>${swapitem.swap_item_name}</figcaption>
                                   
                                    ${swapitem.swap_item_description ? `
                                            <button class="btn btn-primary rounded-pill py-1 px-4 d-flex align-items-center m-1"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="${swapitem.swap_item_description}"
                                                data-item-id="${swapitem.swap_item_id}"
                                                data-item-name="${swapitem.swap_item_name}">
                                                <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                    <path d="M8 7.8C7.6 7.8 7.3 8.1 7.3 8.5V11.4C7.3 11.8 7.6 12.1 8 12.1C8.4 12.1 8.7 11.8 8.7 11.4V8.5C8.7 8.1 8.4 7.8 8 7.8Z" fill="white"/>
                                                    <path d="M8 4.9C7.6 4.9 7.3 5.2 7.3 5.6C7.3 6 7.6 6.3 8 6.3C8.4 6.3 8.7 6 8.7 5.6C8.7 5.2 8.4 4.9 8 4.9Z" fill="white"/>
                                                </svg>
                                                Info
                                            </button>
                                        ` : ''
                                    } </div>
                                </div>`;
                        });

                        swapItemsHTML += `</div>`;

                        $itemsSwapContainer.append(`
                            <div class="d-flex justify-content-between row">
                                <div class="col-4 p-2">${mainItemHTML}</div>
                                <div class="col-8 p-2">${swapItemsHTML}</div>
                            </div>
                        `);
                    } else {
                        $itemsSwapContainer.html('<p class="text-center">No swap items available.</p>');
                    }

                    $('#itemsSwapLoadingSpinner').hide();
                    $itemsSwapContainer.show();
                    $('[data-bs-toggle="tooltip"]').tooltip(); // Initialize Bootstrap tooltip
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching swap items:', error);
                    $itemsSwapContainer.html('<p class="text-center text-danger">Failed to load swap items.</p>');
                    $('#itemsSwapLoadingSpinner').hide();
                    $itemsSwapContainer.show();
                }
            });

            // Show the modal
            $itemSwapModel.modal('show');
        });

        // Store the swaps in memory for applying changes

        // // When the swap button is clicked
        // $('body').on('click', '.swap-button', function () {

        //     // Reset the swaps array to an empty array
        //     swaps = [];
        //     // Get the current container of the swap button
        //     const categoryItemSwap = $(this).closest('.category-item-swap');
            
        //     // Extract the data attributes (main and swap item ids)
        //     const mainItemId = categoryItemSwap.data('main-id');
        //     const swapItemId = categoryItemSwap.data('swap-id');

        //     // Displaying the current IDs for debugging (you can remove this later)
        //     console.log('Main Item ID:', mainItemId);
        //     console.log('Swap Item ID:', swapItemId);

        //     // Assuming you're storing the item data in a swaps array or similar object for later use:
        //     const existingSwapIndex = swaps.findIndex(swap => swap.main_id === mainItemId);
        //     console.log('Existing Swap Index:', existingSwapIndex);
        //     //If the swap exists, update it. Otherwise, push a new swap.
        //     // if (existingSwapIndex !== -1) {
        //     //     swaps[existingSwapIndex] = { main_id: swapItemId, swap_id: mainItemId };
        //     // } else {
        //     //     swaps.push({ main_id: swapItemId, swap_id: mainItemId });
        //     // }
        //     swaps.push({ main_id: swapItemId, swap_id: mainItemId });

        //     // Update the UI (swap images and names)
        //     const mainItemName = categoryItemSwap.find('figcaption:first').text();
        //     const mainItemImage = categoryItemSwap.find('img:first').attr('src');
        //     const swapItemName = categoryItemSwap.find('figcaption:last').text();
        //     const swapItemImage = categoryItemSwap.find('img:last').attr('src');

        //     // Swapping the content dynamically
        //     categoryItemSwap.find('figcaption:first').text(swapItemName);
        //     categoryItemSwap.find('img:first').attr('src', swapItemImage);
        //     categoryItemSwap.find('figcaption:last').text(mainItemName);
        //     categoryItemSwap.find('img:last').attr('src', mainItemImage);

        //     // Optional: Close the modal if it's used for this action
        // //    $('#swapModal').modal('hide');
        // });

        // Store the current swap in memory
        let activeSwap = null;
        let swaps = [];

        $('body').on('click', '.swap-button', function () {
            swaps = [];

            // Reset previous swap if exists
            if (activeSwap) {
                resetPreviousSwap(activeSwap);
                activeSwap = null; // Clear active swap after reset
            }

            // Get the clicked swap item container
            const swapItemContainer = $(this).closest('.category-item-swap');

            // Extract swap item details
            const swapItemId = $(this).data('swap-item-id');
            const swapItemName = $(this).data('swap-item-name');
            const swapItemImage = $(this).data('swap-item-img');
            const swapItemProtein = $(this).data('swap-item-protein');
            const swapItemCarbs = $(this).data('swap-item-carbs');

            let user_item_id = $(this).data('user-item-id');

            // Get the main item container
            const mainItemContainer = $('.main-item-box');
            const mainItemImageElem = mainItemContainer.find('.main-item-img');
            const mainItemNameElem = mainItemContainer.find('figcaption');
            const mainItemProteinElem = mainItemContainer.find('.main-item-protein');
            const mainItemCarbsElem = mainItemContainer.find('.main-item-carbs');

            // Store current main item details
            const mainItemId = mainItemImageElem.attr('data-main-id');
            const mainItemName = mainItemNameElem.text();
            const mainItemImage = mainItemImageElem.attr('src');
            const mainItemProtein = mainItemProteinElem.text();
            const mainItemCarbs = mainItemCarbsElem.text();

            console.log('Main Item Before Swap:', mainItemName, mainItemImage);
            console.log('Swap Item Before Swap:', swapItemName, swapItemImage);

            // Swap main item with the selected swap item
            mainItemImageElem.attr('src', swapItemImage);
            mainItemNameElem.text(swapItemName);
            mainItemProteinElem.text(`Protein: ${swapItemProtein}g`);
            mainItemCarbsElem.text(`Carbs: ${swapItemCarbs}g`);

            // Store original swap item details before replacing it
            const originalSwapItemImage = swapItemContainer.find('img').attr('src');
            const originalSwapItemName = swapItemContainer.find('figcaption').text();
            const originalSwapItemProtein = swapItemContainer.find('.info-tootlip ul li:nth-child(1)').text();
            const originalSwapItemCarbs = swapItemContainer.find('.info-tootlip ul li:nth-child(2)').text();

            // Swap the selected swap item with the previous main item
            swapItemContainer.find('img').attr('src', mainItemImage);
            swapItemContainer.find('figcaption').text(mainItemName);
            swapItemContainer.find('.info-tootlip ul').html(`
                <li>${mainItemProtein}</li>
                <li>${mainItemCarbs}</li>
            `);

            console.log('Main Item After Swap:', swapItemName, swapItemImage);
            console.log('Swap Item After Swap:', mainItemName, mainItemImage);

            // Store swap data for tracking & reset handling
            activeSwap = {
                mainContainer: mainItemContainer,
                swapContainer: swapItemContainer,
                mainItemId: mainItemId,
                swapItemId: swapItemId,
                mainItemName: mainItemName,
                mainItemImage: mainItemImage,
                mainItemProtein: mainItemProtein,
                mainItemCarbs: mainItemCarbs,
                originalSwapItemImage: originalSwapItemImage,
                originalSwapItemName: originalSwapItemName,
                originalSwapItemProtein: originalSwapItemProtein,
                originalSwapItemCarbs: originalSwapItemCarbs
            };

            swaps.push({
                main_id: swapItemId,
                swap_id: mainItemId,
                user_item_id: user_item_id
            });

            console.log('Swaps:', swaps);
        });

        // Function to reset previous swap properly
        function resetPreviousSwap(swapData) {
            if (!swapData) return;

            console.log('Resetting previous swap:', swapData);

            // Restore original main item details
            swapData.mainContainer.find('.main-item-img').attr('src', swapData.mainItemImage);
            swapData.mainContainer.find('figcaption').text(swapData.mainItemName);
            swapData.mainContainer.find('.main-item-protein').text(swapData.mainItemProtein);
            swapData.mainContainer.find('.main-item-carbs').text(swapData.mainItemCarbs);

            // Restore original swap item details
            swapData.swapContainer.find('img').attr('src', swapData.originalSwapItemImage);
            swapData.swapContainer.find('figcaption').text(swapData.originalSwapItemName);
            swapData.swapContainer.find('.info-tootlip ul').html(`
                <li>${swapData.originalSwapItemProtein}</li>
                <li>${swapData.originalSwapItemCarbs}</li>
            `);
        }

        // Apply Changes functionality
        $('body').on('click', '.apply-changes-btn', function () {
            // Send all swaps to the server
            const userItemId = $(this).data('user-item-id');
            const userMealId = $(this).data('user-meal-id');
            console.log(userItemId);
            console.log('123');
            console.log(swaps);
            $.ajax({
                url: "{{ route('front.items.swaps') }}", // Laravel route to handle the request
                method: "GET",
                data: {
                    swaps: swaps,
                    meal_id: currentMealId,
                    user_item_id: userItemId,
                    user_meal_id: userMealId,
                    user_id: userId,
                    // headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                },
                success: function (response) {
                    // Handle success response
                    console.log(response);
                    swaps = [];
                    $('#itemSwapModal').modal('hide');
                    if(response.success){
                        // alert("Swaps applied successfully!");
                        var meal_id = response.data['meal_id'];
                        var meal_name = response.data['meal_name'];
                        var user_meal_id = response.data['user_meal_id'];
                        mealItemModelReload(meal_id, meal_name, user_meal_id);
                    }
                    
                    // $('#mealItemModel').modal('show');
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error("Failed to apply swaps:", error);
                    alert("Failed to apply changes. Please try again.");
                }
            });
        });

        function mealItemModelReload(meal_id, meal_name, userMealId){
            console.log(meal_id, meal_name);
            // Update modal title
            $mealItemsModalLabel.text(meal_name);
            console.log('122');
            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.meals.items', ':mealId') }}'.replace(':mealId', meal_id) + `?user_meal_id=${userMealId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate items into the modal
                        $.each(data.items, function (index, item) {
                            const unit = item.unit ? item.unit.toString() : '';
                            const needsSpace = !["g", "ml"].includes(unit.toLowerCase());
                            let displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;

                            if (item.selected_qty_unit && Array.isArray(item.selected_qty_unit)) {
                                // const checkedUnits = item.selected_qty_unit.filter(q => q.checked === "true");

                                // if (checkedUnits.length) {
                                    const formattedUnits = item.selected_qty_unit.map(unit => {
                                        const parsedQty = (unit.qty);
                                        const qtyFormatted = (!isNaN(parsedQty) && parsedQty % 1 === 0)
                                            ? (parsedQty)
                                            : unit.qty;

                                        const innerUnit = unit.unit ? unit.unit.toString().toLowerCase() : '';
                                        const space = ["g", "ml"].includes(innerUnit) ? '' : ' ';
                                        return `${qtyFormatted}${space}${unit.unit}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                // }
                            }

                            console.log("displayQty:", displayQty);
                            let infoButton = '';

                            if (item.description) {
                                infoButton = `<button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${item.description}" 
                                    data-item-id="${item.id}" 
                                    data-item-name="${item.name}">
                                    <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                        <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                        <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                    </svg>
                                    Info
                                </button>`;
                            }
                            const swapButton = item.swapItems && item.swapItems.length > 0
                                ? `
                                    <button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}">
                                        <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                            <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                        </svg>
                                        Swap
                                    </button>`
                                : '';
                            const itemCard = `
                                <div class="category-swap-list-box">
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${item.image}" alt="">
                                        </figure>
                                        <div class="info-tootlip">
                                            <p>Food Details</p>
                                            <ul>
                                                <li>Protein: ${item.protein}g</li>
                                                <li>Carbs: ${item.carbs}g</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="align-items-center d-flex m-0 mt-2">
                                            <strong class="me-2 text-nowrap">Qty :</strong>
                                            <span class="m-0">${displayQty}</span>
                                        </p>
                                    </div>
                                    <div class="category-swap-btn">
                                        ${infoButton}
                                        ${swapButton}
                                    </div>
                                </div>`;
                            $mealItemsContainer.append(itemCard);
                        });
                    } else {
                        $mealItemsContainer.html('<p class="text-center">No foods available in this meals.</p>');
                    }

                    // Hide loading spinner and show items
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching subcategory items:', error);
                    $mealItemsContainer.html('<p class="text-center text-danger">Failed to load items.</p>');
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                }
            });

            // Show the modal
            // $('#mealModel').modal('hide');
            $mealItemsModal.modal('show');
        }
        $('#swapModal').on('hidden.bs.modal', function () {
            swaps = [];
            console.log('Swaps array cleared on modal close:', swaps);
        });

        // $('#swapModal').on('hidden.bs.modal', function () {
        //     $('#mealModel').modal('show');

        // });
    });

    $(document).ready(function () {
        $(".print-plan-btn").click(function () {
            const planId = $(this).data("plan-id");
            const userId = $(this).data("user-id");
            // Set form action for download button
            $("#downloadPdfForm").attr("action", "{{ route('plans.generatePdf', ':id') }}".replace(':id', planId));

            $("#downloadPdfForm input[name='user_id']").val(userId);

            // Load the preview content from the controller
            $("#plan-preview-body").html('<div class="text-center">Loading preview...</div>');
            fetch("{{ route('plans.preview', ':id') }}".replace(':id', planId) + "?user_id=" + userId)
            .then(res => res.text())
                .then(html => {
                    console.log(html);
                    $("#plan-preview-body").html(html);
                    $("#planPreviewModal").modal("show"); // ✅ show modal
                    console.log('modal show');
                })
                .catch(err => {
                    $("#plan-preview-body").html('<div class="text-danger">Error loading preview</div>');
                });
        });
    });

    $(document).on('change', '#selectAllCheckbox', function () {
        let isChecked = $(this).is(':checked'); // Check if "Select All" is checked

        // Toggle all checkboxes based on the state of "Select All"
        $('.meal-item-checkbox').prop('checked', isChecked);
    });

    $(document).on('change', '.meal-item-checkbox', function () {
        let allItems = $('.meal-item-checkbox'); // All item checkboxes
        let allChecked = allItems.length === allItems.filter(':checked').length; // Check if all are selected

        // Set the global "Select All" checkbox state
        $('#selectAllCheckbox').prop('checked', allChecked);
    });

    $(document).on('click', '#fetchAllMeals', function () {
        // Show loader or clear previous content
        $('#ShoppingModal .modal-body').html('<p>Loading...</p>');

        // Fetch all meals with items
        $.ajax({
            url: '{{ route("front.get.meals.items") }}', // Adjust URL if needed
            method: 'GET',
            success: function (response) {
                let meals = response.meals;
                // let selectedItems = response.selectedItems;
                let modalContent = '';
                modalContent += `<div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                        <label class="form-check-label" for="printPlanCheckbox">Select All</label>
                                    </div>`;                
                // Loop through each meal
                meals.forEach(meal => {
                    modalContent += `<div class="ingredient-list">
                                        <input type="checkbox" class="form-check-input mt-3 mx-3 meal-checkbox" id="id="mealCheckbox${meal.id}"">
                                        <h2 class="d-inline-block px-0" style="border-bottom:none;">${meal.title}</h2>
                                        <hr class="m-0">
                                        <ul>`;
                    
                    // Loop through each item in the meal
                    meal.items.forEach(item => {
                        // let isChecked = selectedItems[meal.id] && selectedItems[meal.id].includes(item.id) ? 'checked' : '';

                        modalContent += `<li>
                                            <div class="ingredient-info">
                                                <div class="form-check">
                                                    <input class="form-check-input meal-item-checkbox" type="checkbox" value="${item.id}" id="Check${item.id}">
                                                    <input type="hidden" id="category" value="${item.category?.name || ''}">
                                                    <label class="form-check-label" for="Check${item.id}">
                                                        <div class="ingredient-img">
                                                            <figure>
                                                                <img src="{{ asset('private/public/storage') }}/${item.image ? item.image : '' }" alt="${item.title}">
                                                            </figure>
                                                        </div>
                                                    </label>
                                                </div>
                                                <span>${item.title}</span>
                                            </div>
                                            <span class="quantity"><strong>QTY:</strong> ${item.pivot.item_qty} ${item.pivot.item_qty_unit}</span>
                                        </li>`;
                    });

                    modalContent += `</ul></div>`;
                });

                // Update modal content
                $('#ShoppingModal .modal-body').html(modalContent);
            },
            error: function (xhr) {
                console.error('Error fetching meals:', xhr);
                $('#ShoppingModal .modal-body').html('<p>Error loading data.</p>');
            }
        });
    });

    $(document).on('change', '.meal-checkbox', function () {
        const mealContainer = $(this).closest('.ingredient-list'); // Find the relevant meal container
        const isChecked = $(this).is(':checked'); // Check if "Meal Checkbox" is selected

        // Select/Deselect all meal items within this meal's container
        mealContainer.find('.meal-item-checkbox').prop('checked', isChecked);
    });
   
    $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
        let aggregatedItems = {};

        // Collect all checked items
        $('#ShoppingModal .meal-item-checkbox:checked').each(function () {
            const listItem = $(this).closest('li');  // Correct reference for each item
            const itemName = listItem.find('.ingredient-info span').text().trim() || "Unknown Item";
            const quantityText = listItem.find('.quantity').text().trim() || "QTY: 0";
            const category = listItem.find('input[type="hidden"]#category').val().trim() || "Uncategorized";

            // Extract quantity and unit with better regex logic
            const quantityMatch = quantityText.match(/QTY:\s*([\d\/.]+)\s*([a-zA-Z]*)/i);
            let rawQuantity = quantityMatch && quantityMatch[1] ? quantityMatch[1] : "0";
            let unit = quantityMatch && quantityMatch[2] ? quantityMatch[2].trim() : '';

            // Correct conversion for fractional values
            let quantity = 0;
            if (rawQuantity.includes('/')) {
                const [numerator, denominator] = rawQuantity.split('/').map(Number);
                quantity = numerator / denominator;
            } else {
                quantity = parseFloat(rawQuantity);
            }

            // Ensure category exists in the aggregated structure
            if (!aggregatedItems[category]) {
                aggregatedItems[category] = {};
            }

            // Aggregate quantities within the category
            if (aggregatedItems[category][itemName]) {
                aggregatedItems[category][itemName].quantity += quantity;
                aggregatedItems[category][itemName].unit = unit;
            } else {
                aggregatedItems[category][itemName] = { quantity, unit };
            }
        });

        // Generate the HTML for the aggregated list by category
        let printListContent = '';
        for (let [category, items] of Object.entries(aggregatedItems)) {
            printListContent += `<h6>${category}</h6><ul style="list-style-type: none;">`;  // Removed dot style
            for (let [itemName, data] of Object.entries(items)) {
                printListContent += `
                    <li style="margin: 0;">
                        <!-- Right tick mark icon added here -->
                        <span style="margin-right: 2px; font-size: 18px; color: green;">&#10003;</span>  
                        ${itemName} <strong>| QTY:</strong> ${data.quantity} ${data.unit}
                    </li>
                `;
            }
            printListContent += `</ul><br/>`;
        }

        // Populate the print modal with the aggregated list
        $('#ShippingPrintModal .print-list').html(printListContent);
    });

    $(document).on('click', '#ShippingPrintModal .btn-primary', function () {
        // Get the content of the print list
        const content = $('#ShippingPrintModal .print-list').html();
        // Create a container to format the content for PDF
        const pdfContainer = `
            <div style="font-family: Arial, sans-serif; padding: 10px; max-width: 600px; margin: auto;">
                <h3 style="text-align: center;">Shopping List</h3><hr>
                <ul style="list-style: none; padding: 0;">
                    ${content}
                </ul>
            </div>
        `;

        // Use html2pdf to generate the PDF
        const options = {
            margin: 1,
            filename: 'shopping_list.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(options).from(pdfContainer).save();
    });


    function updateQuantity(inputElement) {
        const newQty = inputElement.value; // Get the new quantity value
        const itemId = inputElement.getAttribute('data-item-id'); // Get the item ID from the data attribute
        const userItemId = inputElement.getAttribute('data-user-item-id'); // Get the item ID from the data attribute

        // Make an AJAX request to update the quantity in the backend
        fetch('{{ route("front.food-quantity-update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                item_id: itemId,
                qty: newQty,
                user_item_id: userItemId,
                user_id: userId
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Quantity updated successfully!');
            } else {
                alert('Failed to update quantity. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            alert('An error occurred. Please try again.');
        });
    }

</script>

@endsection