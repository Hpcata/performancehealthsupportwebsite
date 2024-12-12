@extends(frontView('layouts.app'))

@section('title', $mealtime->name)

@section('content')

    <div class="section nutrition-plan-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="nutrition-plan-text">
                        <h1>Nutrition Supplements for a <span class="text-primary">Healthy {{ $mealtime->name }}</span></h1>
                        <p>Boost your energy and health with the right supplements!</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 ms-lg-auto">
                    <div class="nutrition-plan-img-box">
                        <div class="go-bottom-link">
                            <figure class="top-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M50,45V0H3v0.1h2.1C29.9,0.1,50,20.2,50,45z" fill="#fafafa"/>
                                </svg>
                            </figure>
                            <a href="#nextSection" class="btn btn-primary">
                                <svg width="71" height="72" viewBox="0 0 71 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M50.8233 17.2911C49.0555 17.2911 47.7297 18.6169 47.7297 20.3847L47.7297 43.6603L22.2444 18.175C20.9186 17.1438 18.8562 17.1438 17.6777 18.3223C16.4992 19.5008 16.4992 21.5632 17.6777 22.7417L43.3103 48.3744L20.0347 48.3744C18.2669 48.3744 16.9411 49.7002 16.9411 51.4679C16.9411 53.2357 18.2669 54.5615 20.0347 54.5615H50.9706C51.2653 54.5615 51.7072 54.4142 52.1491 54.2669C52.4438 54.2669 52.7384 53.9723 53.033 53.6777C53.3276 53.383 53.6223 53.0884 53.7696 52.6465C53.9169 52.2045 54.0642 51.7626 54.0642 51.4679L54.0642 20.532C53.9169 18.9116 52.4438 17.4384 50.8233 17.2911Z" fill="white"/>
                                </svg>                                    
                            </a>
                            <figure class="bottom-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M50,45V0H3v0.1h2.1C29.9,0.1,50,20.2,50,45z" fill="#fafafa"/>
                                </svg>
                            </figure>
                        </div>
                        <div class="nutrition-plan-img">
                            <figure>
                                <img src="{!! frontAssets('images/nutrition-supplements-02.jpg') !!}" alt="">
                            </figure>
                        </div>
                        <div class="nutrition-bottom-box">
                            <figure class="top-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M0,5v45h47v-0.1h-2.1C20.1,49.9,0,29.8,0,5z" fill="#fafafa"/>
                                </svg>
                            </figure>
                            <div class="nutrition-athlete-box">
                                <figure>
                                <img src="{!! frontAssets('images/kerry-oBryan.jpg') !!}" alt="">
                                </figure>
                                <div class="nutrition-athlete-info">
                                    <h5>Ellie Shiloh</h5>
                                    <p>National Athlete</p>
                                </div>
                            </div>
                            <figure class="bottom-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M0,5v45h47v-0.1h-2.1C20.1,49.9,0,29.8,0,5z" fill="#fafafa"/>
                                </svg>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section bg-white" id="nextSection">
        <div class="container">
            @foreach($mealtime->categories as $category)
            <div class="d-flex flex-wrap align-items-center">
                <h2 class="m-0 border-end pe-3">{{ $category->title }}</h2>
                <p class="ms-3 m-0"><small>Lorem Lipsum</small></p>
            </div>
            <div class="main-category-list mt-4">
                <div class="row g-0">
                    @foreach($category->subcategories as $item)
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="{!! asset('private/public/storage/' . $item->image) !!} " alt="">
                            </figure>
                            <h5 class="mb-3">{{ $item->title }}</h5>
                            <a href="javascript:void(0)" class="btn btn-primary view-details-btn mt-auto" data-category-id="{{ $item->id }}" data-category-name="{{ $item->title }}">View Details</a>
                            <!-- <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button> -->
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- <div class="section">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center">
                <h2 class="m-0 border-end pe-3">Sweet</h2>
                <p class="ms-3 m-0"><small>Lorem Lipsum</small></p>
            </div>
            <div class="main-category-list mt-4">
                <div class="row g-0">
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="https://img.freepik.com/premium-photo/white-plate-has-sliced-toast-with-peanut-butter-bananas-style-high-detailed_921860-182845.jpg" alt="">
                            </figure>
                            <h5 class="mb-3">Peanut Butter Toast</h5>
                            <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="https://img.freepik.com/premium-photo/bowl-greek-yogurt-topped-with-honey-plain-white-bg_1264082-8980.jpg" alt="">
                            </figure>
                            <h5 class="mb-3">Greek Yogurt</h5>
                            <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="https://img.freepik.com/premium-photo/chia-pudding-parfait-topped-with-diced-mango-ready-eating_1346034-181045.jpg" alt="">
                            </figure>
                            <h5 class="mb-3">Chia Seed</h5>
                            <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="https://img.freepik.com/premium-photo/bowl-oatmeal-with-apple-slices-cinnamon-top_1091925-24869.jpg" alt="">
                            </figure>
                            <h5 class="mb-3">Oatmeal with Cinnamon</h5>
                            <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="https://img.freepik.com/premium-photo/bowl-fruit-salad-with-strawberries-mint_1297893-8898.jpg" alt="">
                            </figure>
                            <h5 class="mb-3">Smoothie Bowl</h5>
                            <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="https://img.freepik.com/premium-photo/stack-pancakes-with-syrup-glass-syrup_905510-27404.jpg" alt="">
                            </figure>
                            <h5 class="mb-3">Wheat Pancakes</h5>
                            <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <!-- Modal -->
    <div class="modal fade" id="mealModel" tabindex="-1" aria-labelledby="mealModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealModalLabel">Scramble Eggs</h5>
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
                    <h5 class="modal-title" id="mealItemsModalLabel">Coffee, and smoothie</h5>
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
    <div class="modal fade" id="itemSwapModal" tabindex="-1" aria-labelledby="itemsSwapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsSwapModalLabel">Banana</h5>
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
                    <button type="button" class="apply-changes-btn btn btn-primary">Apply Changes</button>
                </div>
            </div>
        </div>
    </div>
<!-- </div>/#wrapper -->
<script>
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
                url: '{{ route('front.subcategory.meals', ':id') }}'.replace(':id', categoryId),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.meals && data.meals.length > 0) {
                        // Populate subcategories into the modal
                        $.each(data.meals, function (index, meal) {
                            const subcategoryCard = `
                            <div class="col-sm-6 col-lg-4">
                            <div class="nutrition-plan-box h-100 d-flex flex-column">
                                <figure>
                                    <img src="${meal.image}" alt="">
                                </figure>
                                <h5 class="mb-3">${meal.name}</h5>
                                <button type="button" class="view-items-btn btn btn-primary mt-auto" data-subcategory-id="${meal.id}" 
                                data-subcategory-name="${meal.name}">View Details</button>
                            </div>
                        </div>`;
                            $mealModelContainer.append(subcategoryCard);
                        });
                    } else {
                        $mealModelContainer.html('<p class="text-center">No subcategories available.</p>');
                    }

                    // Hide loading spinner and show subcategories
                    $mealModelLoadingSpinner.hide();
                    $mealModelContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching subcategories:', error);
                    $mealModelContainer.html('<p class="text-center text-danger">Failed to load subcategories.</p>');
                    $mealModelLoadingSpinner.hide();
                    $mealModelContainer.show();
                }
            });

            // Show the modal
            $mealModel.modal('show');
        });

        let currentMealId = null;
        let currentMealName = null;
        // Handle click event to fetch subcategory items
        $('body').on('click', '.view-items-btn', function () {
            const subcategoryId = $(this).data('subcategory-id');
            const subcategoryName = $(this).data('subcategory-name');
            console.log(subcategoryId, subcategoryName);
            if (!subcategoryId || !subcategoryName) {
                console.error('Invalid meal data.');
                return;
            }

            currentMealId = subcategoryId;
            currentMealName = subcategoryName;

            // Update modal title
            $mealItemsModalLabel.text(subcategoryName);

            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.meals.items', ':mealId') }}'.replace(':mealId', subcategoryId),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate items into the modal
                        $.each(data.items, function (index, item) {
                            const itemCard = `<div class="category-swap-list-box">
                                    <figure>
                                        <img class="img-thumbnail" src="${item.image}" alt="">
                                    </figure>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="m-0">120 Calories</p>
                                    </div>
                                    <div class="category-swap-btn">
                                        <button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top" data-item-id="${item.id}" data-item-name="${item.name}">
                                            <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                                <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                            </svg>
                                            Info
                                        </button>
                                        <button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1"  data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}">
                                            <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                                <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                            </svg>
                                            Swap
                                        </button>
                                    </div>
                                </div>`;
                            $mealItemsContainer.append(itemCard);
                        });
                    } else {
                        $mealItemsContainer.html('<p class="text-center">No items available.</p>');
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
            $('#mealModel').modal('hide');
            $mealItemsModal.modal('show');
        });

        // Item swap button click event
        // Handle click event to fetch items
        $('body').on('click', '.item-swap-btn', function () {
            $('#mealItemModel').modal('hide');
            const itemId = $(this).data('item-id');
            const itemName = $(this).data('item-name');

            if (!itemId || !itemName) {
                console.error('Invalid item data.');
                return;
            }

            // Update modal title
            $itemsSwapModalLabel.text(itemName);

            // Clear previous subcategories and show loading spinner
            $itemsSwapContainer.empty().hide();
            $itemsSwapLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.items.swap-items', ':id') }}'.replace(':id', itemId),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate subcategories into the modal
                        var itemId = data.item_id
                        var itemName = data.item_name
                        var itemImage = data.item_image
                        $.each(data.items, function (index, swapitem) {
                            const swapItemsCard = `
                                <div class="category-item-swap category-swap-list-box" data-main-id="${itemId}" data-swap-id="${swapitem.swap_item_id}">
                                    <figure>
                                        <img class="img-thumbnail" src="${itemImage}" alt="">
                                        <figcaption>${itemName}</figcaption>
                                    </figure>
                                    <div class="category-swap-btn mx-auto">
                                        <button class="swap-button btn btn-primary rounded-pill py-2 d-flex align-items-center m-1">
                                            <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                                <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                            </svg>
                                            Swap
                                        </button>
                                    </div>
                                    <figure>
                                        <img class="img-thumbnail" src="${swapitem.swap_item_image}" alt="">
                                        <figcaption>${swapitem.swap_item_name}</figcaption>
                                    </figure>
                                </div>`;
                            $itemsSwapContainer.append(swapItemsCard);
                        });
                    } else {
                        $itemsSwapContainer.html('<p class="text-center">No subcategories available.</p>');
                    }

                    // Hide loading spinner and show subcategories
                    $itemsSwapLoadingSpinner.hide();
                    $itemsSwapContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching swap items:', error);
                    $itemsSwapContainer.html('<p class="text-center text-danger">Failed to load swap items.</p>');
                    $itemsSwapLoadingSpinner.hide();
                    $itemsSwapContainer.show();
                }
            });

            // Show the modal
            $itemSwapModel.modal('show');
        });

        // Store the swaps in memory for applying changes
        let swaps = [];

        // Swap button functionality for dynamic rows
        // When the swap button is clicked
        $('body').on('click', '.swap-button', function () {
            // Get the current container of the swap button
            const categoryItemSwap = $(this).closest('.category-item-swap');
            
            // Extract the data attributes (main and swap item ids)
            const mainItemId = categoryItemSwap.data('main-id');
            const swapItemId = categoryItemSwap.data('swap-id');

            // Displaying the current IDs for debugging (you can remove this later)
            console.log('Main Item ID:', mainItemId);
            console.log('Swap Item ID:', swapItemId);

            // Assuming you're storing the item data in a swaps array or similar object for later use:
            const existingSwapIndex = swaps.findIndex(swap => swap.main_id === mainItemId);

            // If the swap exists, update it. Otherwise, push a new swap.
            if (existingSwapIndex !== -1) {
                swaps[existingSwapIndex] = { main_id: swapItemId, swap_id: mainItemId };
            } else {
                swaps.push({ main_id: swapItemId, swap_id: mainItemId });
            }

            // Update the UI (swap images and names)
            const mainItemName = categoryItemSwap.find('figcaption:first').text();
            const mainItemImage = categoryItemSwap.find('img:first').attr('src');
            const swapItemName = categoryItemSwap.find('figcaption:last').text();
            const swapItemImage = categoryItemSwap.find('img:last').attr('src');

            // Swapping the content dynamically
            categoryItemSwap.find('figcaption:first').text(swapItemName);
            categoryItemSwap.find('img:first').attr('src', swapItemImage);
            categoryItemSwap.find('figcaption:last').text(mainItemName);
            categoryItemSwap.find('img:last').attr('src', mainItemImage);

            // Optional: Close the modal if it's used for this action
        //    $('#swapModal').modal('hide');
        });

        // Apply Changes functionality
        $('body').on('click', '.apply-changes-btn', function () {
            // Send all swaps to the server
            $.ajax({
                url: "{{ route('front.items.swaps') }}", // Laravel route to handle the request
                method: "GET",
                data: {
                    swaps: swaps,
                    meal_id: currentMealId,
                    // headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                },
                success: function (response) {
                    // Handle success response
                    console.log(response);
                    swaps = [];
                    $('#itemSwapModal').modal('hide');
                    if(response.success){
                        alert("Swaps applied successfully!");
                        var meal_id = response.data['meal_id'];
                        var meal_name = response.data['meal_name'];
                        mealItemModelReload(meal_id, meal_name);
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
        function mealItemModelReload(meal_id, meal_name){
            console.log(meal_id, meal_name);
            // Update modal title
            $mealItemsModalLabel.text(meal_name);

            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.meals.items', ':mealId') }}'.replace(':mealId', meal_id),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate items into the modal
                        $.each(data.items, function (index, item) {
                            const itemCard = `<div class="category-swap-list-box">
                                    <figure>
                                        <img class="img-thumbnail" src="${item.image}" alt="">
                                    </figure>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="m-0">120 Calories</p>
                                    </div>
                                    <div class="category-swap-btn">
                                        <button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top" data-item-id="${item.id}" data-item-name="${item.name}">
                                            <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                                <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                            </svg>
                                            Info
                                        </button>
                                        <button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1"  data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}">
                                            <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                                <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                            </svg>
                                            Swap
                                        </button>
                                    </div>
                                </div>`;
                            $mealItemsContainer.append(itemCard);
                        });
                    } else {
                        $mealItemsContainer.html('<p class="text-center">No items available.</p>');
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

</script>
@endsection