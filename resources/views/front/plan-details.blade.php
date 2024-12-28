@extends(frontView('layouts.app'))

@section('title', $plan->name)

@section('content')

    <div class="section nutrition-plan-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="nutrition-plan-text">
                        <h1>We Take Care About Your <span class="text-primary">Health</span></h1>
                        <p>Make sure your daily nutrition is sufficient. Consult your Nutrition Supplements Products about nutrition with us.</p>
                        <a href="#" class="btn btn-primary">
                            <span class="me-1">Get Started</span>
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
        
                        </a>
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
                            <a href="#" class="btn btn-primary">
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
                                <img src="{!! frontAssets('images/nutrition-supplements.jpg') !!}"  alt="">
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
                                    @if(Auth::check() && Auth::user()->profile_image)
                                        <img src="{{ asset('private/public/' . Auth::user()->profile_image) }}" alt="Profile Image">
                                    @else
                                        <img src="{{ frontAssets('images/kerry-oBryan.jpg') }}" alt="Default Profile Image">
                                    @endif
                                </figure>

                                <div class="nutrition-athlete-info">
                                    @if(Auth::check())
                                        <h5>{{ Auth::user()->name }}</h5>
                                        <p>National Athlete</p>
                                        <button class="btn btn-primary edit-profile py-1 mt-1" data-profile-id="{{ Auth::user()->id }}">Edit Profile</button>
                                    @else
                                        <h5>Ellie Shiloh</h5>
                                        <p>National Athlete</p>
                                    @endif
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
        <div class="plan-buttons-link">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center">
                    <a href="#">Tracker</a>
                    <a href="#">Plan</a>
                    <a href="#">Treatment</a>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
    @foreach($userPlans as $userPlan)
        <div class="container mb-5">
            <div class="d-md-flex align-items-center justify-content-between">
                <h2>{{ $userPlan->plan->name }}
                @if(isset($userPlan->plan->subPlans) && $userPlan->plan->subPlans->count() > 0)
                    ( 
                    {{ $userPlan->plan->subPlans->pluck('name')->join(' + ') }} 
                    ({{ $userPlan->plan->subPlans->count() }} plans)
                    )
                @endif
                </h2>
                <a href="#" class="mt-3 mt-md-0 btn btn-primary">Finalise Plan</a>
            </div>
            <div class="mt-4">
                <div class="row g-4">
                @if($userPlan->userMealTimes->count())
                    @foreach($userPlan->userMealTimes as $plan)
                    <?php //dd($plan); ?>
                    <div class="col-md-4">
                        <div class="nutrition-plan-box">
                            <figure>
                                @if($plan->mealTime->image)
                                    <img src="{{ asset('private/public/storage/' . $plan->mealTime->image) }}" alt="{{ $plan->mealTime->title }}">
                                @endif
                            </figure>
                            <h5>{{ $plan->mealTime->title }} </h5>
                            <p>1024 Calories</p>
                            <a href="{{ route('front.meal-time.details', ['id' => $plan->mealTime->id, 'plan_id' => $userPlan->id]) }}" class="btn btn-primary view-details-btn" data-category-id="{{ $plan->mealTime->id }}" 
                                data-category-name="{{ $plan->mealTime->title }}">View Details</a>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal for Subcategories -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Subcategories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div id="subcategoriesLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Subcategories Content -->
                    <div id="subcategoriesContainer" class="row" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Subcategory Items -->
    <div class="modal fade" id="subcategoryItemsModal" tabindex="-1" aria-labelledby="subcategoryItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subcategoryItemsModalLabel">Subcategory Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div id="itemsLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Items Content -->
                    <div id="subcategoryItemsContainer" class="row" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" id="id" name="user_id" >

                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                    </div>
                    <div class="mb-3">
                        <label for="profileImage" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profileImage" name="profile_image">
                    </div>
                    <div class="mb-3 text-center">
                        <img id="profileImagePreview" src="" alt="Profile Image" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateProfileBtn">Update Profile</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $categoryModal = $('#categoryModal');
        const $categoryModalLabel = $('#categoryModalLabel');
        const $subcategoriesContainer = $('#subcategoriesContainer');
        const $subcategoriesLoadingSpinner = $('#subcategoriesLoadingSpinner');

        const $subcategoryItemsModal = $('#subcategoryItemsModal');
        const $subcategoryItemsModalLabel = $('#subcategoryItemsModalLabel');
        const $subcategoryItemsContainer = $('#subcategoryItemsContainer');
        const $itemsLoadingSpinner = $('#itemsLoadingSpinner');

        // Handle click event to fetch subcategories
        $('body').on('click', '.view-details-btn', function () {
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).data('category-name');

            if (!categoryId || !categoryName) {
                console.error('Invalid category data.');
                return;
            }

            // Update modal title
            $categoryModalLabel.text(categoryName);

            // Clear previous subcategories and show loading spinner
            $subcategoriesContainer.empty().hide();
            $subcategoriesLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.category.subcategories', ':categoryId') }}'.replace(':categoryId', categoryId),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.subcategories && data.subcategories.length > 0) {
                        // Populate subcategories into the modal
                        $.each(data.subcategories, function (index, subcategory) {
                            const subcategoryCard = `
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <img src="${subcategory.image}" alt="${subcategory.name}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">${subcategory.name}</h5>
                                            <p class="text-muted">${subcategory.description || ''}</p>
                                            <button 
                                                class="btn btn-secondary w-100 view-items-btn" 
                                                data-subcategory-id="${subcategory.id}" 
                                                data-subcategory-name="${subcategory.name}">
                                                View Items
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $subcategoriesContainer.append(subcategoryCard);
                        });
                    } else {
                        $subcategoriesContainer.html('<p class="text-center">No subcategories available.</p>');
                    }

                    // Hide loading spinner and show subcategories
                    $subcategoriesLoadingSpinner.hide();
                    $subcategoriesContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching subcategories:', error);
                    $subcategoriesContainer.html('<p class="text-center text-danger">Failed to load subcategories.</p>');
                    $subcategoriesLoadingSpinner.hide();
                    $subcategoriesContainer.show();
                }
            });

            // Show the modal
            $categoryModal.modal('show');
        });

        // Handle click event to fetch subcategory items
        $('body').on('click', '.view-items-btn', function () {
            const subcategoryId = $(this).data('subcategory-id');
            const subcategoryName = $(this).data('subcategory-name');
            console.log(subcategoryId, subcategoryName);
            if (!subcategoryId || !subcategoryName) {
                console.error('Invalid subcategory data.');
                return;
            }

            // Update modal title
            $subcategoryItemsModalLabel.text(subcategoryName);

            // Clear previous items and show loading spinner
            $subcategoryItemsContainer.empty().hide();
            $itemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.subcategories.items', ':subcategoryId') }}'.replace(':subcategoryId', subcategoryId),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        // Populate items into the modal
                        $.each(data.items, function (index, item) {
                            const itemCard = `
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <img src="${item.image}" alt="${item.name}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">${item.name}</h5>
                                            <p class="text-muted">${item.description || ''}</p>
                                            <p class="text-success fw-bold">${item.price ? `$${item.price}` : ''}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $subcategoryItemsContainer.append(itemCard);
                        });
                    } else {
                        $subcategoryItemsContainer.html('<p class="text-center">No items available in this subcategory.</p>');
                    }

                    // Hide loading spinner and show items
                    $itemsLoadingSpinner.hide();
                    $subcategoryItemsContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching subcategory items:', error);
                    $subcategoryItemsContainer.html('<p class="text-center text-danger">Failed to load items.</p>');
                    $itemsLoadingSpinner.hide();
                    $subcategoryItemsContainer.show();
                }
            });

            // Show the modal
            $('#categoryModal').modal('hide');
            $subcategoryItemsModal.modal('show');
        });

        $(".print-plan-btn").click(function () {
            let planId = $(this).data('plan-id');
            //alert(planId);
            window.location.href = "{{ route('plans.generatePdf', ':id') }}".replace(':id', planId);

        })
    });

    const baseUrl = "{{ asset('private/public/storage') }}";

    $(document).ready(function () {
        // Open modal and populate user data
        $('.edit-profile').on('click', function () {
            const profileId = $(this).data('profile-id');

            $.ajax({
                url: '{{ route('front.profile', ':id') }}'.replace(':id', profileId),
                method: 'GET',
                success: function (data) {
                    $('#id').val(data.id);
                    $('#firstName').val(data.first_name);
                    $('#lastName').val(data.last_name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    // $('#email').val(data.email);
                    // Set the profile image
                    if (data.profile_image) {
                        $('#profileImagePreview').attr('src', data.profile_image);
                    }
                    $('#profileModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load profile details. Please try again later.');
                }
            });
        });

        // Handle profile update form submission
        $('#updateProfileBtn').on('click', function () {
            let formData = new FormData($('#profileForm')[0]); // Get form data, including files
            formData.append('_token', '{{ csrf_token() }}'); 
            $.ajax({
                url: '{{ route("front.profile.update") }}', // Endpoint to update user profile
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.success) {
                        alert('Profile updated successfully!');
                        $('#profileModal').modal('hide');
                        location.reload(); // Optionally reload the page
                    } else {
                        alert('Failed to update profile: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error updating profile:', error);
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        alert('Validation errors: ' + Object.values(errors).join(', '));
                    } else {
                        alert('An error occurred while updating the profile. Please try again later.');
                    }

                }
            });
        });
});

</script>
@endsection