@extends(frontView('layouts.app'))

@section('title', $plan->name)

@section('content')
<style>
.meal-checkbox {
    /* position: absolute; */
    top: 10px;
    right: 10px;
    z-index: 10;
    width: 20px;
    height: 20px;
}

</style>
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
                            <!-- <a href="{{ route('front.profile', ['id' => $user->id]) }}">My Profile</a> -->
                            <a href="javascript:void(0);" class="ms-0 print-plan-btn @if(!$isPlanCreated) disabled @endif" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}">Print Plan</a>
                            <a href="#" class="" data-bs-toggle="modal" data-bs-target="#ShoppingModal" id="fetchAllMeals">Shopping List</a>
                            <a href="{{ route('front.profile', ['id' => $user->id]) }}" class="btn btn-primary ms-auto text-white border-0">Back</a>
                        </div>
                    </div>
                </div>
                 <div class="col-12 mt-3">
                    <div class="plan-buttons-link">
                        <div class="d-flex flex-wrap align-items-center">
                            <!-- <a href="{{ route('front.profile', ['id' => $user->id]) }}">My Profile</a> -->
                            <a href="javascript:void(0);" class="btn btn-primary" data-user-id="{{ $user->id }}" data-plan-id="{{ $plan->id}}">Nutrition Plan</a>
                            <a href="javascript:void(0);" class="" id="">Competition Plan</a>
                            <a href="javascript:void(0);" class="" id="">Injury Plan</a>
                            <a href="javascript:void(0);"
                                class="btn"
                                id="showAllMeals"
                                data-user-id="{{ $user->id }}"
                                data-plan-id="{{ $plan->id }}"
                                data-fetch-route="{{ route('user.plan.meals', ['user' => $user->id, 'plan' => $plan->id]) }}">
                                All Meals
                            </a>                        
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
    @foreach ($userPlans as $userPlan)
        <div class="container mb-5">
            <div class="row g-4">
                @foreach ($userPlan->userCategories as $userCategory)
                    @php
                        $validSubCategories = $userCategory->userSubCategories->filter(
                            function ($subCategory) use ($userPlan, $userCategory) {
                                return $subCategory->userMeals
                                    ->where('user_plan_id', $userPlan->id)
                                    ->where('user_category_id', $userCategory->id)
                                    ->where('user_sub_category_id', $subCategory->id)
                                    ->isNotEmpty();
                            }
                        );

                        $hasValidMeal = $validSubCategories->isNotEmpty();
                    @endphp

                    @if ($hasValidMeal && $userCategory->category)
                        <div class="col-md-3">
                            <div class="nutrition-plan-box">
                                <figure>
                                    @if ($userCategory->category->image)
                                        <img src="{{ webAssets('storage/' . $userCategory->category->image) }}"
                                            alt="{{ $userCategory->category->title }}">
                                    @endif
                                </figure>

                                <h5>{{ $userCategory->category->title }}</h5>

                                @if ($validSubCategories->count() > 1)
                                    {{-- ✅ Existing functionality --}}
                                    <a href="{{ route('front.meal-time.details', [
                                            'id'      => $userCategory->id,
                                            'plan_id' => $userPlan->id
                                        ]) }}"
                                        class="btn btn-primary view-details-btn"
                                        data-category-id="{{ $userCategory->id }}"
                                        data-category-name="{{ $userCategory->category->title }}">
                                        View Details
                                    </a>
                                @else
                                    {{-- ✅ Show modal trigger button --}}
                                    @php
                                        $subCategory = $validSubCategories->first();
                                    @endphp
                                    <a href="javascript:void(0)" 
                                        class="btn btn-primary view-meal-modal"
                                        data-user-plan-id="{{ $userPlan->id }}"
                                        data-user-category-id="{{ $userCategory->id }}"
                                        data-sub-category-id="{{ $subCategory->id }}"
                                        data-category-title="{{ $userCategory->category->title }}"
                                        data-sub-category-title="{{ $subCategory->subCategory->title }}">
                                        View Meals
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
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
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="plan-preview-body">
                    <div class="text-center">Loading preview...</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="downloadPDF()">Download PDF</button>
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Modal -->
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

    <!-- Meal ItemsModal -->
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

    <!-- Swap Item List Modal -->
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

    <div class="modal" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Item Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Description without label -->
                    <div id="modalDescription" class="mb-3"></div>

                    <!-- Note with label inline -->
                    <div><strong>Note:</strong> <span id="modalNote"></span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mealsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">All Meals</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="mealListContainer"></div> <!-- Meals Render Here -->
                </div>

                <div class="modal-footer">
                    <button id="saveSelectedMeals" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ShoppingModal" tabindex="-1" aria-labelledby="ShippingModalLabel" aria-hidden="true">
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
                    <a href="javascript:void(0);" class="btn btn-primary m-0 w-100 text-center rounded-0" data-bs-target="#ShippingPrintModal" data-bs-toggle="modal">Print Shopping List Now</a>
                </div>
            </div>
        </div>
    </div>

    <!--Shoping print Modal -->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
      window.logoBase64 = 'data:image/jpg;base64,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'; // Replace with base64 logo image

    function downloadPDF() {
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
            const topMargin = 0.3;    // ~15mm
            const bottomMargin = 1.0; // ~18mm (footer + buffer)
            const leftRightMargin = 0.3;

            html2pdf()
                .set({
                    margin: [topMargin, leftRightMargin, bottomMargin, leftRightMargin],
                    filename: 'print-plan.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
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
                    const circleRadius = 0.11;
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
                        pdf.setDrawColor(0, 116, 217); // blue border (optional)
                        pdf.setFillColor(0, 116, 217); // blue fill
                        pdf.circle(circleCenterX, circleCenterY, circleRadius, 'F');

                        // Page number in white, centered in the circle
                        pdf.setTextColor(255, 255, 255);
                        pdf.setFontSize(9);
                        pdf.setFont(undefined, 'normal');
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
    }

    // Helper to convert images to base64
    function toDataURL(url) {
        return fetch(url, { mode: 'cors' })
            .then(response => response.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }

    function toDataURL(url) {
        return fetch(url, { mode: 'cors' })
            .then(response => response.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }

    $('#planPreviewModal').on('hidden.bs.modal', function () {
        location.reload(); // Reload the page when the modal is closed
    });
    // const baseUrl = "{{ asset('private/public/storage') }}";
    const user = @json($userPlan);
    const userId = user.user_id;
    const userPlanId = user.id;
    console.log('user_plan_id:' , userPlanId);
    console.log('user_id:' , userId);

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

    $(document).off('change', '#selectAllCheckbox').on('change', '#selectAllCheckbox', function () {
        let isChecked = $(this).is(':checked'); // Check if "Select All" is checked

        // Toggle all checkboxes based on the state of "Select All"
        $('.meal-item-checkbox').prop('checked', isChecked);
        $('.meal-checkbox').prop('checked', isChecked);
    });

    $(document).on('change', '.meal-item-checkbox', function () {
        let allItems = $('.meal-item-checkbox'); // All item checkboxes
        let allChecked = allItems.length === allItems.filter(':checked').length; // Check if all are selected

        // Set the global "Select All" checkbox state
        $('#selectAllCheckbox').prop('checked', allChecked);
    });

    $(document).on('click', '#fetchAllMeals', function () {
        $('#ShoppingModal .modal-body').html('<p>Loading...</p>');
        $('#ShoppingModal').modal('show');

        // let userPlanId = $(this).data('user-plan-id');

        $.ajax({
            url: '{{ route("front.get.meals.items") }}' + `?user_id=${userId}&user_plan_id=${userPlanId}`,
            method: 'GET',
            success: function (response) {
                let meals = response.meals;
                let modalContent = `
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                        <label class="form-check-label" for="selectAllCheckbox">Select All</label>
                    </div>
                `;

                meals.forEach(meal => {
                    modalContent += `<h5 class="ms-3">${meal.category_title}</h5>`;
                    modalContent += `
                        <div class="ingredient-list">
                            <input type="checkbox" class="form-check-input mt-3 mx-3 meal-checkbox" id="mealCheckbox${meal.meal_id}">
                            <h2 class="d-inline-block px-0" style="border-bottom:none;">${meal.meal_title}</h2>
                            <hr class="m-0">
                            <ul>
                    `;

                    meal.items.forEach(item => {
                        let selectedQtyUnits = [];
                        try {
                            if (item.selected_qty_unit) {
                                selectedQtyUnits = JSON.parse(item.selected_qty_unit);
                            }
                            if (!Array.isArray(selectedQtyUnits)) {
                                selectedQtyUnits = [];
                            }
                        } catch (e) {
                            console.error(`Invalid selected_qty_unit JSON for item ${item.id}:`, e);
                            selectedQtyUnits = [];
                        }

                        const checkedUnits = selectedQtyUnits.filter(u =>
                            u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                        );

                        let qtyCheckboxes = '';
                        if (checkedUnits.length > 0) {
                            const qtyText = checkedUnits.map(unitObj => {
                                let rawQty = unitObj.qty;
                                let unit = unitObj.unit;
                                let qty = 0;

                                // Handle fractional quantities
                                if (!isNaN(rawQty)) {
                                    qty = parseFloat(rawQty);
                                } else if (typeof rawQty === 'string' && rawQty.includes('/')) {
                                    const parts = rawQty.split('/');
                                    if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                                        qty = parseFloat(parts[0]) / parseFloat(parts[1]);
                                    } else {
                                        qty = rawQty; // fallback
                                    }
                                }

                                if (['g', 'ml', 'mL'].includes(unit)) {
                                    return `${Math.round(qty)}${unit}`;
                                } else {
                                    const rounded = Math.round(qty * 100) / 100;
                                    let displayQty = rawQty;

                                    if (rounded === 0.25) displayQty = '¼';
                                    else if (rounded === 0.5) displayQty = '½';
                                    else if (rounded === 0.75) displayQty = '¾';

                                    return `${displayQty} ${unit}`;
                                }
                            }).join(' or ');
                            qtyCheckboxes = `<span>${qtyText}</span>`;
                        } else {
                            let qty = parseFloat(item.qty);
                            let unit = item.unit;
                            let displayQty = qty;

                            if (['g', 'ml', 'mL'].includes(unit)) {
                                qtyCheckboxes = `<span>${Math.round(qty)}${unit}</span>`;
                            } else {
                                const rounded = Math.round(qty * 100) / 100;
                                if (rounded === 0.25) displayQty = '¼';
                                else if (rounded === 0.5) displayQty = '½';
                                else if (rounded === 0.75) displayQty = '¾';

                                qtyCheckboxes = `<span>${displayQty} ${unit}</span>`;
                            }
                        }

                        modalContent += `
                            <li class="mb-3">
                                <div class="d-flex align-items-center ingredient-info">
                                    <div class="m-0">
                                        <input class="form-check-input meal-item-checkbox" type="checkbox" value="${item.id}" id="Check${item.id}">
                                        <input type="hidden" id="category" value="${item.category || ''}">
                                    </div>
                                    <div class="me-3 ingredient-img">
                                        <figure>
                                            <img src="{{ webAssets('storage') }}/${item.image || ''}" alt="">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span><strong>${item.title}</strong></span>
                                        <div class="mt-1 d-flex flex-wrap align-items-center">
                                            <strong class="me-2">QTY:</strong> ${qtyCheckboxes}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        `;
                    });

                    modalContent += `</ul></div>`;
                });

                $('#ShoppingModal .modal-body').html(modalContent);
            },
            error: function (xhr) {
                console.error('Error fetching meals:', xhr);
                $('#ShoppingModal .modal-body').html('<p>Error loading data.</p>');
            }
        });
    });

    $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
        let aggregatedItems = {};

        $('#ShoppingModal .meal-item-checkbox:checked').each(function () {
            const listItem = $(this).closest('li');
            const itemName = listItem.find('.ingredient-info span strong').text().trim() || "Unknown Item";
            const category = listItem.find('input[type="hidden"]#category').val()?.trim() || "Uncategorized";

            const qtyContainer = listItem.find('.ingredient-info .flex-grow-1 > div.d-flex');
            if (qtyContainer.length === 0) return;

            const fullText = qtyContainer.text().trim();
            const qtyTextMatch = fullText.match(/QTY:\s*(.+)/i);
            if (!qtyTextMatch) return;

            const qtyText = qtyTextMatch[1];
            const qtyParts = qtyText.split(" or ").map(part => part.trim());

            qtyParts.forEach(part => {
                // Match values like: ½ piece OR 1/2 piece OR 1 piece
                const match = part.match(/^([\d¼½¾/.]+)\s*([a-zA-Z\s]+)$/);
                if (!match) return;

                let qtyRaw = match[1].trim();
                let unit = match[2].trim();

                // Convert unicode fraction to numeric
                const unicodeFractions = {
                    '¼': 0.25,
                    '½': 0.5,
                    '¾': 0.75
                };

                let qty = unicodeFractions[qtyRaw] ?? null;

                // If still null, try numeric or x/y string
                if (qty === null) {
                    if (qtyRaw.includes('/')) {
                        const parts = qtyRaw.split('/');
                        if (parts.length === 2) {
                            const numerator = parseFloat(parts[0]);
                            const denominator = parseFloat(parts[1]);
                            if (!isNaN(numerator) && !isNaN(denominator) && denominator !== 0) {
                                qty = numerator / denominator;
                            }
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

        // Generate HTML content
        let printListContent = '';
        for (let [category, items] of Object.entries(aggregatedItems)) {
            printListContent += `<h6>${category}</h6><ul style="list-style-type: none;">`;

            for (let [itemName, unitMap] of Object.entries(items)) {
                const qtyText = Object.entries(unitMap).map(([unit, total]) => {
                    if (['g', 'ml', 'mL'].includes(unit)) {
                        return `${Math.round(total)}${unit}`;
                    } else {
                        const roundedTotal = Math.round(total * 100) / 100;
                        let fraction = '';

                        // Convert to nearest known fraction
                        if (roundedTotal === 0.25) fraction = '¼';
                        else if (roundedTotal === 0.5) fraction = '½';
                        else if (roundedTotal === 0.75) fraction = '¾';
                        else fraction = roundedTotal;

                        return `${fraction} ${unit}`;
                    }
                }).join(' or ');

                printListContent += `
                    <li style="margin: 0; padding: 4px 0; font-size: 16px; line-height: 1.8; display: flex; align-items: center;">
                        <span style="display: inline-block; width: 26px; font-size: 40px; line-height: 1;">&#9633;</span>
                        <span style="flex: 1;">${itemName} <strong>QTY:</strong> ${qtyText}</span>
                    </li>
                `;
            }

            printListContent += `</ul><br/>`;
        }

        if (printListContent === '') {
            printListContent = '<p>No items selected.</p>';
        }

        $('#ShippingPrintModal .print-list').html(printListContent);
    });

    $(document).on('change', '.meal-checkbox', function () {
        const mealContainer = $(this).closest('.ingredient-list'); // Find the relevant meal container
        const isChecked = $(this).is(':checked'); // Check if "Meal Checkbox" is selected

        // Select/Deselect all meal items within this meal's container
        mealContainer.find('.meal-item-checkbox').prop('checked', isChecked);
    });
   
    $(document).on('click', '#ShippingPrintModal .btn-primary', function () {
        let pdfContent = '';

        $('#ShippingPrintModal .print-list h6').each(function () {
            const categoryTitle = $(this).text().trim();
            const itemList = $(this).next('ul');

            let itemsHtml = '';

            itemList.find('li').each(function () {
                const itemHtml = $(this).html(); // ✅ Keep the existing square + text
                itemsHtml += `<li style="list-style-type: none;">${itemHtml}</li>`;
            });

            pdfContent += `
                <div>
                    <h6 style="margin-bottom: 5px;">${categoryTitle}</h6>
                    <ul style="padding-left: 20px;">
                        ${itemsHtml}
                    </ul>
                </div><br/>
            `;
        });

        if (pdfContent.trim() === '') {
            pdfContent = '<p>No items found.</p>';
        }

        const pdfContainer = `
            <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto;">
                <h3 style="text-align: center;">Shopping List</h3><hr>
                ${pdfContent}
            </div>
        `;

        const options = {
            margin: 1,
            filename: 'shopping_list.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(options).from(pdfContainer).save();
    });

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

        $('body').on('click', '.view-meal-modal', function () {
            const subCategoryId = $(this).data('sub-category-id');
            const subCategoryName = $(this).data('sub-category-name');
            const userCategoryId = $(this).data('user-category-id');
            const userPlanId = $(this).data('user-plan-id');
            if (!subCategoryId || !userPlanId) {
                console.error('Invalid category data.');
                return;
            }

            // Update modal title
            $mealModalLabel.text(subCategoryName);

            // Clear previous subcategories and show loading spinner
            $mealModelContainer.empty().hide();
            $mealModelLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.category.meals', ':id') }}'.replace(':id', subCategoryId) + `?user_category_id=${userCategoryId}&user_plan_id=${userPlanId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.meals && data.meals.length > 0) {
                        // Populate subcategories into the modal
                        let mealCard = '';
                        $.each(data.meals, function (index, meal) {
                            mealCard = `
                            <div class="col-sm-6 col-lg-4">
                            <div class="nutrition-plan-box h-100 d-flex flex-column">
                                <figure>
                                    <img src="${meal.image}" alt="">
                                    <div class="nutrition-plan-box-overlay">
                                        <div class="nutrition-plan-box-text">
                                            <p>${meal.description}</p>
                                        </div>
                                    </div>
                                </figure>
                                <h5 class="mb-3">${meal.name}</h5>
                                <button type="button" class="view-items-btn btn btn-primary mt-auto" data-user-meal-id="${meal.id}" data-meal-id="${meal.id}" data-user-plan-id="${userPlanId}" data-category-id="${userCategoryId}" data-sub-category-id="${meal.user_sub_category_id}"
                                data-meal-name="${meal.name}">
                                    <svg class="me-2" width="25" height="25" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                        <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                    </svg>Smart Swaps
                                </button>
                            </div>
                        </div>`;
                            $mealModelContainer.append(mealCard);
                        });
                        // Append last card: "Purchase More Meals" only
                        const purchaseMoreCard = `
                            <div class="col-sm-6 col-lg-4 d-none">
                                <div class="nutrition-plan-box h-100 d-flex flex-column justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary mt-auto purchase-more-meals-btn">
                                        Purchase More Meals
                                    </button>
                                </div>
                            </div>`;
                        $mealModelContainer.append(purchaseMoreCard);
                        
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
            const userPlanId = $(this).data('user-plan-id');
            const userSubCategoryId = $(this).data('sub-category-id');
            const userCategoryId = $(this).data('category-id');

            if (!mealId || !mealName) {
                console.error('Invalid meal data.');
                return;
            }

            currentMealId = mealId;
            currentMealName = mealName;

            $mealItemsModalLabel.text(mealName);
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            $.ajax({
                url: '{{ route('front.meals.items', ':mealId') }}'.replace(':mealId', mealId) + `?user_meal_id=${userMealId}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function (index, item) {
                            let displayQty = '';

                            // Normalize selected_qty_unit
                            let selectedUnits = [];
                            try {
                                if (typeof item.selected_qty_unit === 'string') {
                                    selectedUnits = JSON.parse(item.selected_qty_unit);
                                } else if (Array.isArray(item.selected_qty_unit)) {
                                    selectedUnits = item.selected_qty_unit;
                                }
                            } catch (e) {
                                console.warn('Failed to parse selected_qty_unit for item:', item.name, e);
                            }

                            if (Array.isArray(selectedUnits)) {
                                const checkedUnits = selectedUnits.filter(u => {
                                    const isChecked = u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1";
                                    return isChecked;
                                });

                                if (checkedUnits.length > 0) {
                                    const formattedUnits = checkedUnits.map(u => {
                                        let qtyText = u.qty?.toString().trim() || '';
                                        const unitText = (u.unit || '').toString().trim();
                                        const needsSpace = !["g", "ml", "mL"].includes(unitText.toLowerCase());

                                        // Check if qtyText is a valid number, otherwise preserve as-is (e.g., "1/4")
                                        const numericQty = Number(qtyText);
                                        if (!isNaN(numericQty)) {
                                            qtyText = numericQty % 1 === 0 ? numericQty.toFixed(0) : numericQty.toFixed(1);
                                        }

                                        return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                }
                            }

                            // Fallback
                            if (!displayQty && item.qty && item.unit) {
                                const unit = item.unit.toString();
                                const needsSpace = !["g", "ml", "mL"].includes(unit.toLowerCase());
                                displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;
                            }

                            let infoButton = '';
                            if (item.description) {
                                infoButton = `<button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1 info-btn" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${item.description}" 
                                    data-item-id="${item.id}" 
                                    data-item-name="${item.name}"
                                    data-description="${item.description}"
                                    data-note="${item.note}">
                                    <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                        <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                        <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                    </svg>
                                    Info
                                </button>`;
                            }

                            const swapButton = item.swapItems && item.swapItems.length > 0
                                ? `<button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}" data-user-plan-id="${userPlanId}" data-sub-category-id="${userSubCategoryId}" data-user-category-id="${userCategoryId}">
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
                        $mealItemsContainer.html('<p class="text-center">No foods available in this meal.</p>');
                    }

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

            // Update modal title
            $itemsSwapModalLabel.text(itemName);

            // Clear previous subcategories and show loading spinner
            $itemsSwapContainer.empty().hide();
            $itemsSwapLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.items.swap-items', ':id') }}'.replace(':id', itemId) + `?user_meal_id=${userMealId}&user_item_id=${userItemId}&user_plan_id=${userPlanId}&sub_category_id=${userSubCategoryId}`,
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
                                            data-item-name="${data.item_name}"
                                            data-description="${data.item.description}">
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
                                        } 
                                    </div>
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

        // Store the current swap in memory
        let activeSwap = null;
        let swaps = [];

        $('body').on('click', '.swap-button', function () {
            swaps = [];

            if (activeSwap) {
                resetPreviousSwap(activeSwap);
                activeSwap = null;
            }

            const $btn = $(this);
            const $swapItem = $btn.closest('.category-item-swap');
            const $mainItem = $('.main-item-box');

            // Get item details
            const mainImage = $mainItem.find('.main-item-img');
            const mainName = $mainItem.find('figcaption');
            const mainProtein = $mainItem.find('.main-item-protein');
            const mainCarbs = $mainItem.find('.main-item-carbs');

            const swapImage = $swapItem.find('img');
            const swapName = $swapItem.find('figcaption');
            const swapProtein = $swapItem.find('.swap-item-protein');
            const swapCarbs = $swapItem.find('.swap-item-carbs');

            // Info buttons
            const $mainInfoBtn = $mainItem.find('button[data-bs-toggle="tooltip"]');
            const $swapInfoBtn = $swapItem.find('button[data-bs-toggle="tooltip"]');

            // Save old content
            const oldMain = {
                src: mainImage.attr('src'),
                name: mainName.text(),
                protein: mainProtein.text(),
                carbs: mainCarbs.text(),
                infoHtml: $mainInfoBtn.length ? $mainInfoBtn.prop('outerHTML') : null
            };

            const newSwap = {
                src: swapImage.attr('src'),
                name: swapName.text(),
                protein: swapProtein.text(),
                carbs: swapCarbs.text(),
                infoHtml: $swapInfoBtn.length ? $swapInfoBtn.prop('outerHTML') : null
            };

            // Swap core info
            mainImage.attr('src', newSwap.src);
            mainName.text(newSwap.name);
            mainProtein.text(newSwap.protein);
            mainCarbs.text(newSwap.carbs);

            swapImage.attr('src', oldMain.src);
            swapName.text(oldMain.name);
            swapProtein.text(oldMain.protein);
            swapCarbs.text(oldMain.carbs);

            // Swap Info buttons
            if (newSwap.infoHtml) {
                $mainInfoBtn.remove();
                $mainItem.find('.category-swap-img').append(newSwap.infoHtml);
            } else {
                $mainInfoBtn.remove();
            }

            if (oldMain.infoHtml) {
                $swapInfoBtn.remove();
                $swapItem.find('.category-swap-img').append(oldMain.infoHtml);
            } else {
                $swapInfoBtn.remove();
            }

            // Re-initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Store for reset
            activeSwap = {
                mainImage, mainName, mainProtein, mainCarbs,
                swapImage, swapName, swapProtein, swapCarbs,
                originalMain: oldMain,
                originalSwap: newSwap
            };

            swaps.push({
                main_id: $btn.data('swap-item-id'),
                swap_id: mainImage.data('main-id'),
                user_item_id: $btn.data('user-item-id')
            });

            console.log('Swaps:', swaps);
        });

        function resetPreviousSwap(swapData) {
            if (!swapData) return;

            console.log('Resetting previous swap:', swapData);

            // Restore original main item details
            swapData.mainImage.attr('src', swapData.originalMain.src);
            swapData.mainName.text(swapData.originalMain.name);
            swapData.mainProtein.text(swapData.originalMain.protein);
            swapData.mainCarbs.text(swapData.originalMain.carbs);

            // Remove and restore info button on main item
            swapData.mainImage.closest('.category-swap-img').find('button[data-bs-toggle="tooltip"]').remove();
            if (swapData.originalMain.infoHtml) {
                swapData.mainImage.closest('.category-swap-img').append(swapData.originalMain.infoHtml);
            }

            // Restore original swap item details
            swapData.swapImage.attr('src', swapData.originalSwap.src);
            swapData.swapName.text(swapData.originalSwap.name);
            swapData.swapProtein.text(swapData.originalSwap.protein);
            swapData.swapCarbs.text(swapData.originalSwap.carbs);

            // Remove and restore info button on swap item
            swapData.swapImage.closest('.category-swap-img').find('button[data-bs-toggle="tooltip"]').remove();
            if (swapData.originalSwap.infoHtml) {
                swapData.swapImage.closest('.category-swap-img').append(swapData.originalSwap.infoHtml);
            }

            // Re-initialize tooltips after restoring
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        // Apply Changes functionality
        $('body').on('click', '.apply-changes-btn', function () {
            // Send all swaps to the server
            const userItemId = $(this).data('user-item-id');
            const userMealId = $(this).data('user-meal-id');
            const userPlanId = $(this).data('user-plan-id');
            const userSubCategoryId = $(this).data('user-sub-category-id');
            const userCategoryId = $(this).data('user-category-id');
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
                    user_category_id: userCategoryId,
                    user_sub_category_id: userSubCategoryId,
                    user_plan_id: userPlanId,
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
                        mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId);
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

        function mealItemModelReload(meal_id, meal_name, userMealId, userSubCategoryId, userPlanId, userCategoryId){
            console.log(meal_id, meal_name);
            // Update modal title
            $mealItemsModalLabel.text(meal_name);
            console.log('122');
            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.meals.items', ':meal_id') }}'.replace(':mealId', meal_id) + `?user_meal_id=${userMealId}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function (index, item) {
                            let displayQty = '';

                            // Normalize selected_qty_unit
                            let selectedUnits = [];
                            try {
                                if (typeof item.selected_qty_unit === 'string') {
                                    selectedUnits = JSON.parse(item.selected_qty_unit);
                                } else if (Array.isArray(item.selected_qty_unit)) {
                                    selectedUnits = item.selected_qty_unit;
                                }
                            } catch (e) {
                                console.warn('Failed to parse selected_qty_unit for item:', item.name, e);
                            }

                            if (Array.isArray(selectedUnits)) {
                                const checkedUnits = selectedUnits.filter(u => {
                                    const isChecked = u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1";
                                    return isChecked;
                                });

                                if (checkedUnits.length > 0) {
                                    const formattedUnits = checkedUnits.map(u => {
                                        let qtyText = u.qty?.toString().trim() || '';
                                        const unitText = (u.unit || '').toString().trim();
                                        const needsSpace = !["g", "ml", "mL"].includes(unitText.toLowerCase());

                                        // Check if qtyText is a valid number, otherwise preserve as-is (e.g., "1/4")
                                        const numericQty = Number(qtyText);
                                        if (!isNaN(numericQty)) {
                                            qtyText = numericQty % 1 === 0 ? numericQty.toFixed(0) : numericQty.toFixed(1);
                                        }

                                        return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                }
                            }

                            // Fallback
                            if (!displayQty && item.qty && item.unit) {
                                const unit = item.unit.toString();
                                const needsSpace = !["g", "ml", "mL"].includes(unit.toLowerCase());
                                displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;
                            }

                            let infoButton = '';
                            if (item.description) {
                                infoButton = `<button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1 info-btn" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${item.description}" 
                                    data-item-id="${item.id}" 
                                    data-item-name="${item.name}"
                                    data-description="${item.description}"
                                    data-note="${item.note}">
                                    <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                        <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                        <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                    </svg>
                                    Info
                                </button>`;
                            }

                            const swapButton = item.swapItems && item.swapItems.length > 0
                                ? `<button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}" data-user-plan-id="${userPlanId}" data-sub-category-id="${userSubCategoryId}" data-user-category-id="${userCategoryId}">
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
                        $mealItemsContainer.html('<p class="text-center">No foods available in this meal.</p>');
                    }

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
            // $('#mealModel').modal('hide');
            $mealItemsModal.modal('show');
        }

        $('#swapModal').on('hidden.bs.modal', function () {
            swaps = [];
            console.log('Swaps array cleared on modal close:', swaps);
        });

        $('body').on('click', '.info-btn', function () {
            const itemName = $(this).data('item-name'); 
            const itemDescription = $(this).data('description');
            const itemNote = $(this).data('note');
            console.log(itemName, itemDescription, itemNote);
            $('#infoModal .modal-title').text(itemName);
            $('#infoModal #modalDescription').text(itemDescription);
            $('#infoModal #modalNote').text(itemNote);
            $('#mealItemModel').addClass('blur-background');
            $('#infoModal').modal('show');
        });

        $('#infoModal').on('hidden.bs.modal', function () {
            $('#mealItemModel').removeClass('blur-background');
        });
    });

    $(document).ready(function () {
        const mealsModal = new bootstrap.Modal($('#mealsModal')[0]);

        // Open modal and fetch data
        $('#showAllMeals').on('click', function () {
            const fetchUrl = $(this).data('fetch-route');

            $.ajax({
                url: fetchUrl,
                type: 'GET',
                success: renderMealsModal,
                error: function (xhr) {
                    alert('Error fetching meals: ' + xhr.responseText);
                }
            });
        });

        // Render all meals grouped by category
        function renderMealsModal(data) {
            $('#mealListContainer').empty(); // Clear old content

            // Loop through each category
            data.categories.forEach(category => {
                const catId = category.id;
                const catName = category.name;
                const meals = Array.isArray(category.meals)
                                    ? category.meals
                                    : Object.values(category.meals || {});
                console.log(meals);
                if (meals.length === 0) return; // Skip empty categories

                // Append Category Title
                $('#mealListContainer').append(`
                    <div class="mb-3">
                        <h5 class="fw-bold border-bottom pb-1 mb-3">${catName}</h5>
                        <div class="row" id="category-${catId}-meals"></div>
                    </div>
                `);

                // Append Meals under the current category
                const $container = $(`#category-${catId}-meals`);
                meals.forEach(meal => {
                    $container.append(`
                        <div class="col-sm-6 col-lg-4 mb-4">
                            <div class="nutrition-plan-box h-100 d-flex flex-column position-relative">
                                <figure class="position-relative mb-0">
                                    <img src="${meal.image_url}" alt="${meal.title}" class="img-fluid">
                                    
                                    <!-- Checkbox at top-right -->
                                    <input type="checkbox" class="select-meal-checkbox form-check-input" data-meal-id="${meal.id}" data-user-category-id="${category.user_category_id}" data-user-sub-category-id="${category.user_sub_category_id}" data-user-plan-id="${category.user_plan_id}" style="position: absolute; top: 10px; right: 10px; z-index: 2; width: 20px; height: 20px;">
                                
                                </figure>
                                <h5 class="mb-3 mt-2 text-center">${meal.title}</h5>
                                <div class="text-center mt-auto mb-2">
                                    <button type="button" class="view-items-btn btn btn-primary"
                                        data-meal-id="${meal.id}"
                                        data-meal-name="${meal.title}">
                                        Smart Swaps
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                });
            });

            mealsModal.show();
        }

        $('#saveSelectedMeals').on('click', function (e) {
            e.preventDefault(); // prevent form from submitting
            const groupedData = {};

            $('.select-meal-checkbox:checked').each(function () {
                const mealId           = $(this).data('meal-id');
                const userPlanId       = $(this).data('user-plan-id');
                const userCategoryId   = $(this).data('user-category-id');
                const userSubCatId     = $(this).data('user-sub-category-id');   // ← make sure this data‑attr exists

                groupedData[userPlanId]                     ??= {};
                groupedData[userPlanId][userCategoryId]     ??= {};
                groupedData[userPlanId][userCategoryId][userSubCatId] ??= [];

                groupedData[userPlanId][userCategoryId][userSubCatId].push(mealId);
            });

            let planId = user.plan_id;
            $("#downloadPdfForm").attr("action", "{{ route('plans.generatePdf', ':id') }}".replace(':id', planId));
            $("#downloadPdfForm input[name='user_id']").val(userId);
            $("#downloadPdfForm input[name='grouped_data']").val(JSON.stringify(groupedData));

            // Send grouped data to Laravel and receive HTML response
            fetch("{{ route('front.plans.preview') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}',
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    user_id: userId, // replace with actual user ID
                    plan_id: user.plan_id, // replace with actual plan ID
                    grouped_data: groupedData
                })
            })
            .then(res => res.text())
            .then(html => {
                console.log('12');
                // console.log(html);
                // Inject returned HTML into modal
                mealsModal.hide();
                $("#plan-preview-body").html(html);
                $("#planPreviewModal").modal("show"); // ✅ Show modal
            })
            .catch(err => {
                console.error(err);
                $("#plan-preview-body").html('<div class="text-danger">Error loading preview</div>');
            }); 
        });

    });
</script>
@endsection