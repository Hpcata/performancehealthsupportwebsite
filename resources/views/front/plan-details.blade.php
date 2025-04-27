@extends(frontView('layouts.app'))

@section('title', $plan->name)

@section('content')
 <?php
            $userPlan = \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $plan->id)->where('status', 'active')->first();
            $isPlanCreated = $userPlan ? true : false;
        ?>
    <div class="section nutrition-plan-hero py-md-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="nutrition-plan-text">
                        @foreach($userPlans as $userPlan)
                        <h1>{{ $userPlan->plan->name }} 
                            @if(isset($userPlan->plan->subPlans) && $userPlan->plan->subPlans->count() > 0)
                                ( 
                                {{ $userPlan->plan->subPlans->pluck('name')->join(' + ') }} 
                                ({{ $userPlan->plan->subPlans->count() }} plans)
                                )
                            @endif
                        </h1>
                        @endforeach
                        <!-- <p>Make sure your daily nutrition is sufficient. Consult your Nutrition Supplements Products about nutrition with us.</p> -->
                        <!-- <a href="#" class="btn btn-primary">
                            <span class="me-1">Get Started</span>
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a> -->
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 ms-lg-auto">
                    <div class="plan-buttons-link">
                        <div class="d-flex flex-wrap align-items-center">
                            <!-- <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => $user->id]) }}">View Plan</a> -->
                            <a href="javascript:void(0);" class="ms-0 print-plan-btn @if(!$isPlanCreated) disabled @endif" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}">Print Plan</a>
                            <a href="#" class="" data-bs-toggle="modal" data-bs-target="#ShoppingModal" id="fetchAllMeals">Shopping List</a>
                            <a href="{{ route('front.profile', ['id' => $user->id]) }}" class="btn btn-primary ms-auto text-white border-0">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="container mb-2 text-end">
       <a href="{{ route('front.profile', ['id' => $user->id]) }}" class="btn btn-primary">Back</a> 
    </div> -->
    <div class="section pt-md-3">
    @foreach($userPlans as $userPlan)
        <div class="container mb-5">
            <div class="mt-4">
                <div class="row g-4">
                @if($userPlan->userMealTimes->count())
                    @foreach($userPlan->userMealTimes as $plan)
                        @if($plan->userMeals && $plan->userMeals->count())
                        <div class="col-md-3">
                            <div class="nutrition-plan-box">
                                <figure>
                                    @if($plan->mealTime->image)
                                        <img src="{{ webAssets('storage/' . $plan->mealTime->image) }}" alt="{{ $plan->mealTime->title }}">
                                    @endif
                                </figure>
                                <h5>{{ $plan->mealTime->title }} </h5>
                                <p></p>
                                <a href="{{ route('front.meal-time.details', ['id' => $plan->mealTime->id, 'plan_id' => $userPlan->id]) }}" class="btn btn-primary view-details-btn" data-category-id="{{ $plan->mealTime->id }}" 
                                    data-category-name="{{ $plan->mealTime->title }}">View Details</a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
                </div>
            </div>
        </div>
        @endforeach
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

<script>

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
            printListContent += `<h6>${category}</h6><ul>`;
            for (let [itemName, data] of Object.entries(items)) {
                printListContent += `<li>${itemName} <strong>| QTY:</strong> ${data.quantity} ${data.unit}</li>`;
            }
            printListContent += `</ul></br>`;
        }

        // Populate the print modal with the aggregated list
        $('#ShippingPrintModal .print-list').html(printListContent);
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
</script>
@endsection