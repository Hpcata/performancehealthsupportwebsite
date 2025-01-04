@extends(frontView('layouts.app'))

@section('title', 'Competition Nutrition Plan')

@section('content')
<style>
    .edit-icon {
        background: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        cursor: pointer;
    }
    .edit-icon i {
        font-size: 1rem;
        color: #000;
    }
    .site-footer{
        margin-top: 0 !important;
    }
</style>

    <div class="section py-5 bg-light">
        <div class="container">
            <div class="heading-content">
                <!-- <h3>Competition Nutrition Plan</h3> -->
                <h3>{{ $userPlans->first()->plan->name }}</h3>
                <p>This timeline guides your nutrition plan starting 12 hours before  competition. Based on your competition time, you’ll know what  meals/snacks to eat before and after to maximize performance and  recovery</p>
            </div>
        </div>
    </div>
    <div class="section pt-0 pb-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body nutrition-profile-info">
                            <div class="nutrition-profile-img">
                                <figure>
                                    <img src="{{ asset('private/public/' . $user->profile_image) }}" alt="">
                                </figure>
                                <button class="btn btn-light edit-icon" data-bs-toggle="modal" data-bs-target="#editImageModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <h4 class="text-center">{{ $user->name }} 
                                <button class="btn btn-light edit-icon" data-bs-toggle="modal" data-bs-target="#editNameModal" style="margin-left: 10px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </h4>
                            <ul>
                                <li>Sport: Track & Field</li>
                                <li>Weight: 60kg</li>
                                <li>Height: 180cm</li>
                                <li>Daily Calorie Goal: 3,200</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="nutrition-profile-top">
                        <form action="#">
                            <input type="text" class="form-control" placeholder="Enter your competition time (e.g., 10:00 AM)">
                        </form>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#TimelineModal">Summary</button>
                        <button type="button" class="btn btn-primary" id="fetchAllMeals" data-bs-toggle="modal" data-bs-target="#ShippingModal">Shopping List</button>
                    </div>
                    <div class="nutrition-schedule-list">
                    @foreach($userPlans as $userPlan)
                        @if($userPlan->userMealTimes->count())
                            @foreach($userPlan->userMealTimes as $userMealTime)
                                <div class="card border-0 shadow-sm rounded-3 mt-4">
                                    <div class="card-body">
                                        <h4>{{ $userMealTime->mealtime->title }}</h4>
                                        <div class="plan-list">
                                            @foreach($userMealTime->userMeals as $userMeal)
                                            <div class="plan-list-box">
                                                <div class="plan-list-img">
                                                    <figure>
                                                        <img src="{{ asset('private/public/storage/' . $userMeal->meal->image) }}" alt="">
                                                    </figure>
                                                </div>
                                                <div class="plan-list-info">
                                                    <h5>{{ $userMeal->meal->title }}</h5>
                                                    <ul>
                                                        <li>Carbs: {{ $userMeal->meal->totalCarbs() }}g</li>
                                                        <li>Protein: {{ $userMeal->meal->totalProtein() }}g</li>
                                                    </ul>
                                                </div>
                                                <div class="plan-list-btn">
                                                    <button type="button" class="view-items-btn btn btn-primary mt-auto" data-user-meal-id="{{ $userMeal->id}}" data-meal-id="{{ $userMeal->meal->id}}" 
                                                    data-meal-name="{{ $userMeal->meal->title}}" data-plan-id="{{ $userPlan->id}}">View Details</button>
                                                    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#swapModal">View details</button> -->
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>  
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editImageModalLabel">Edit Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editImageForm" method="POST" enctype="multipart/form-data" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 text-center">
                            <img id="imagePreview" src="{{ asset('private/public/' . $user->profile_image) }}" alt="Current Profile Image" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                        </div>
                        <div class="mb-3">
                            <label for="profileImageInput" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" id="profileImageInput" name="profile_image" accept="image/*">
                            <input type="hidden" class="form-control" id="profileId" name="id" value="{{ $user->id }}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitProfileUpdate()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNameModalLabel">Edit Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNameForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="profileNameInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="profileNameInput" name="name" value="{{ $user->name }}">
                            <input type="hidden" class="form-control" id="profileId" name="id" value="{{ $user->id }}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitProfileUpdate()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mealItemModel" tabindex="-1" aria-labelledby="subcategoryItemsModalLabel" aria-hidden="true">
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

    <div class="modal fade" id="ShippingModal" tabindex="-1" aria-labelledby="ShippingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ShippingModalLabel">Shopping List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ingredient-list">
                        
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <a href="#" class="btn btn-primary m-0 w-100 text-center rounded-0" data-bs-target="#ShippingPrintModal" data-bs-toggle="modal">Print Plan Now</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ShippingPrintModal" tabindex="-1" aria-labelledby="ShippingPrintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ShippingPrintModalLabel">Shopping List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="print-list">
                        <ul>

                        </ul>
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" class="btn btn-primary m-0 w-100 text-center rounded-0" data-bs-dismiss="modal">Print</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script>
    const user = @json($user);
    const userId = user.id;
    
    document.getElementById('profileImageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function submitProfileUpdate() {
        const formData = new FormData();
        const profileIdInput = document.getElementById('profileId');
        // Add profile image if it exists
        const profileImageInput = document.getElementById('profileImageInput');
        if (profileImageInput.files[0]) {
            formData.append('profile_image', profileImageInput.files[0]);
        }

        // Add name
        const profileNameInput = document.getElementById('profileNameInput');
        if (profileNameInput.value) {
            formData.append('name', profileNameInput.value);
        }

        formData.append('user_id', profileIdInput.value);

        // Send AJAX request
        fetch("{{ route('front.profile.update') }}", {
            method: 'POST', // or 'PUT' if using PUT method
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI elements based on server response
                if (data.new_image_url) {
                    document.getElementById('currentProfileImage').src = data.new_image_url;
                }
                if (data.new_name) {
                    document.querySelector('h4.text-center').textContent = data.new_name;
                }
                alert('Profile updated successfully!');
                // Close all modals
                // Close all modals
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                });

                // Reload the page to reflect all updates (optional)
                setTimeout(() => {
                    location.reload();
                }, 500); 
            } else {
                alert('Error updating profile');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    $(document).ready(function () {
     
        $('body').on('click', '.meal-item-model-close', function () {
            $('#mealItemModel').modal('hide');
            $('#mealModel').modal('show');
        })

        $('.item-swap-modal-close').on('click', function () {
            $('#itemSwapModal').modal('hide');
            $('#mealItemModel').modal('show');

        })

        const $mealItemsModal = $('#mealItemModel');
        const $mealItemsModalLabel = $('#mealItemsModalLabel');
        const $mealItemsContainer = $('#mealItemsContainer');
        const $mealItemsLoadingSpinner = $('#mealItemsLoadingSpinner');

        const $itemSwapModel = $('#itemSwapModal');
        const $itemsSwapModalLabel = $('#itemsSwapModalLabel');
        const $itemsSwapContainer = $('#itemsSwapContainer');
        const $itemsSwapLoadingSpinner = $('#itemsSwapLoadingSpinner');

        // Handle click event to fetch meal items
        $('body').on('click', '.view-items-btn', function () {
            const mealId = $(this).data('meal-id');
            const mealName = $(this).data('meal-name');
            const userMealId = $(this).data('user-meal-id');
            const userPlanId = $(this).data('user-plan-id');
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
                url: '{{ route("front.meals.items", ":mealId") }}'.replace(':mealId', mealId) + `?user_meal_id=${userMealId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate items into the modal
                        $.each(data.items, function (index, item) {
                            const itemCard = `<div class="category-swap-list-box">
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${item.image}" alt="">
                                        </figure>
                                        <div class="info-tootlip">
                                            <p>Food Details</p>
                                            <ul>
                                                <li>Protien: ${item.protien}g</li>
                                                <li>Carbs: ${item.carbs}g</li>
                                            </ul>
                                        </div>                                        
                                    </div>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="align-items-center d-flex m-0 mt-2"><strong class="me-2 text-nowrap">Qty : </strong><input type="text" class="form-control form-control-sm" value="${item.qty}" onchange="updateQuantity(this)" data-item-id="${item.id}"  data-user-item-id="${item.user_item_id}"/></p>
                                    </div>
                                    <div class="category-swap-btn">
                                        <button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="${item.description}" data-item-id="${item.id}" data-item-name="${item.name}">
                                            <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                                <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                            </svg>
                                            Info
                                        </button>
                                        <button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1"  data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}">
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
                        $mealItemsContainer.html('<p class="text-center">No items available in this meals.</p>');
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
                url: '{{ route("front.items.swap-items", ":id") }}'.replace(':id', itemId) + `?user_meal_id=${userMealId}&user_item_id=${userItemId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        console.log(data.items);
                        // Populate subcategories into the modal
                        var itemId = data.item_id
                        var itemName = data.item_name
                        var itemImage = data.item_image
                        var userItemId = data.user_item_id

                        $.each(data.items, function (index, swapitem) {
                            const swapItemsCard = `
                                <div class="category-item-swap category-swap-list-box" data-main-id="${itemId}" data-swap-id="${swapitem.swap_item_id}">
                                    <figure>
                                        <img class="img-thumbnail" src="${itemImage}" alt="">
                                        <figcaption>${itemName}</figcaption>
                                    </figure>
                                    <div class="category-swap-btn mx-auto">
                                        <button class="swap-button btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-user-item-id="${userItemId}">
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
                        $itemsSwapContainer.html('<p class="text-center">No swap items available.</p>');
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

        // Store the current swap in memory
        let activeSwap = null;
        let swaps = [];

        // When the swap button is clicked
        $('body').on('click', '.swap-button', function () {
            swaps = [];
            // Get the current container of the swap button
            const categoryItemSwap = $(this).closest('.category-item-swap');
            
            // Extract the data attributes (main and swap item IDs)
            const mainItemId = categoryItemSwap.data('main-id');
            const swapItemId = categoryItemSwap.data('swap-id');

            let user_item_id = $(this).data('user-item-id');
            // Debugging logs
            console.log('Main Item ID:', mainItemId);
            console.log('Swap Item ID:', swapItemId);
            console.log('user Item ID:', user_item_id);

            // Reverse the previous swap if there is one
            if (activeSwap && activeSwap.mainContainer && activeSwap.swapContainer) {
                const { mainContainer, swapContainer } = activeSwap;

                // Restore the original content
                const prevMainItemName = swapContainer.find('figcaption:last').text();
                const prevMainItemImage = swapContainer.find('img:last').attr('src');
                const prevSwapItemName = mainContainer.find('figcaption:first').text();
                const prevSwapItemImage = mainContainer.find('img:first').attr('src');

                // Reverse the swap in the DOM
                mainContainer.find('figcaption:first').text(prevMainItemName);
                mainContainer.find('img:first').attr('src', prevMainItemImage);
                swapContainer.find('figcaption:last').text(prevSwapItemName);
                swapContainer.find('img:last').attr('src', prevSwapItemImage);

                console.log('Previous swap reversed');
            }

            // Perform the new swap
            const mainItemName = categoryItemSwap.find('figcaption:first').text();
            const mainItemImage = categoryItemSwap.find('img:first').attr('src');
            const swapItemName = categoryItemSwap.find('figcaption:last').text();
            const swapItemImage = categoryItemSwap.find('img:last').attr('src');

            // Swap the content dynamically
            categoryItemSwap.find('figcaption:first').text(swapItemName);
            categoryItemSwap.find('img:first').attr('src', swapItemImage);
            categoryItemSwap.find('figcaption:last').text(mainItemName);
            categoryItemSwap.find('img:last').attr('src', mainItemImage);

            // Store the current swap details for reversing later
            activeSwap = {
                mainContainer: categoryItemSwap,
                swapContainer: categoryItemSwap,
                mainId: mainItemId,
                swapId: swapItemId
            };

           swaps.push({ main_id: swapItemId, swap_id: mainItemId, user_item_id:user_item_id});

        });

        // Apply Changes functionality
        $('body').on('click', '.apply-changes-btn', function () {
            // Send all swaps to the server
            const userItemId = $(this).data('user-item-id');
            const userMealId = $(this).data('user-meal-id');
           
            $.ajax({
                url: "{{ route('front.items.swaps') }}", // Laravel route to handle the request
                method: "GET",
                data: {
                    swaps: swaps,
                    meal_id: currentMealId,
                    user_item_id: userItemId,
                    user_meal_id: userMealId
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

            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route("front.meals.items", ":mealId") }}'.replace(':mealId', meal_id) + `?user_meal_id=${userMealId}`,
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
                                        <button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="${item.description}" data-item-id="${item.id}" data-item-name="${item.name}">
                                            <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                                <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                            </svg>
                                            Info
                                        </button>
                                        <button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1"  data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}">
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
                        $mealItemsContainer.html('<p class="text-center">No foods available.</p>');
                    }

                    // Hide loading spinner and show items
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching meal foods:', error);
                    $mealItemsContainer.html('<p class="text-center text-danger">Failed to load foods.</p>');
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
    })

    $(document).on('click', '#fetchAllMeals', function () {
        // Show loader or clear previous content
        $('#ShippingModal .modal-body').html('<p>Loading...</p>');

        // Fetch all meals with items
        $.ajax({
            url: '{{ route("front.get.meals.items") }}', // Adjust URL if needed
            method: 'GET',
            success: function (response) {
                let meals = response.meals;
                // let selectedItems = response.selectedItems;
                let modalContent = '';                
                // Loop through each meal
                meals.forEach(meal => {
                    modalContent += `<div class="ingredient-list">
                                        <h2>${meal.title}</h2>
                                        <ul>`;
                    
                    // Loop through each item in the meal
                    meal.items.forEach(item => {
                        // let isChecked = selectedItems[meal.id] && selectedItems[meal.id].includes(item.id) ? 'checked' : '';

                        modalContent += `<li>
                                            <div class="ingredient-info">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="${item.id}" id="Check${item.id}">
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
                                            <span class="quantity"><strong>QTY:</strong> ${item.qty ? item.qty : 'N/A'}</span>
                                        </li>`;
                    });

                    modalContent += `</ul></div>`;
                });

                // Update modal content
                $('#ShippingModal .modal-body').html(modalContent);
            },
            error: function (xhr) {
                console.error('Error fetching meals:', xhr);
                $('#ShippingModal .modal-body').html('<p>Error loading data.</p>');
            }
        });
    });

    $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
        let aggregatedItems = {};

        // Collect all checked items
        $('#ShippingModal .form-check-input:checked').each(function () {
            const itemName = $(this).closest('.ingredient-info').find('span').text().trim();
            const quantityText = $(this).closest('li').find('.quantity').text().trim();
            let quantityMatch = quantityText.match(/QTY:\s*(\d+\.?\d*)\s*(.*)/i); // Match number and unit
            let quantity = quantityMatch ? parseFloat(quantityMatch[1]) : 0;
            let unit = quantityMatch ? quantityMatch[2].trim() : ''; // Get the unit (e.g., "bottles")

            // Aggregate the quantities if the item already exists
            if (aggregatedItems[itemName]) {
                aggregatedItems[itemName].quantity += quantity;
                aggregatedItems[itemName].unit = unit; // Assume same unit for aggregation
            } else {
                aggregatedItems[itemName] = { quantity, unit };
            }
        });

        // Generate the HTML for the aggregated list
        let printListContent = '';
        for (let [itemName, data] of Object.entries(aggregatedItems)) {
            printListContent += `<li>${itemName} <strong>| QTY</strong> : ${data.quantity} ${data.unit}</li>`;
        }
        
        // Populate the print modal with the aggregated list
        $('#ShippingPrintModal .print-list ul').html(printListContent);
    });

    $(document).on('click', '#ShippingPrintModal .btn-primary', function () {
        // Get the content of the print list
        const content = $('#ShippingPrintModal .print-list').html();
        // Create a container to format the content for PDF
        const pdfContainer = `
            <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto;">
                <h3 style="text-align: center;">Shopping List</h3><hr>
                <ul style="list-style: number; padding: 0;">
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