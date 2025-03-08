@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
   .hidden-checkbox {
        display: none; /* Completely hides the checkbox */
        /* margin-right: 5px; */
    }
    hr {
        margin-top : 0px !important;
        margin-bottom : 15px !important;
        border-top : 1px solid black !important;
    }

    .meal-name-edit {
        display: flex;
        margin-bottom: 1rem;
        justify-content: space-between;
    }

  </style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ 'Edit Plan' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a href="javascript:void(0);" class="btn btn-primary btn-set-task w-sm-100 mx-3 user-pre-plan-details" data-payment-id="{{ $payment->id }}" >View User Details</a>
                    <a href="{{ route('admin.purchase-plans.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="">
                <div class="card-body">
                    <form action="{{ route('admin.purchase-plans.update') }}" method="POST" class="bg-light" id="editPlanForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="panel-group col-7" id="accordion">
                                @foreach ($userPlans as $userPlan)
                                    <?php $plan = $userPlan->plan; ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapsePlan{{$plan->id}}">{{ $userPlan->plan->name }}</a>
                                            </h4>
                                        </div>
                                        <div id="collapsePlan{{$plan->id}}" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <input type="hidden" name="plan_id[]" value="{{ $plan->id }}">
                                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                                <input type="hidden" name="user_id" value="{{ $payment->user_id }}">

                                                <!-- Meal Times (Checkboxes) -->
                                                <ul class="list-group mb-4">
                                                    @foreach ($userPlan->plan->mealTimes as $mealTime)
                                                    <li class="list-group-item border rounded mb-3">
                                                        <!-- Meal Time Checkbox -->
                                                        <div class="form-check px-0">
                                                            <input type="checkbox" 
                                                                name="meal_times[{{$plan->id}}][]" 
                                                                value="{{ $mealTime->id }}" 
                                                                class="form-check-input meal-time-checkbox hidden-checkbox" 
                                                                id="mealTime{{$plan->id}}_{{$mealTime->id}}"
                                                                data-mealtime-id="{{$mealTime->id}}">

                                                            <label class="form-check-label fw-bold" for="mealTime{{$plan->id}}_{{$mealTime->id}}">
                                                                {{ $mealTime->title }} (Meal Time)
                                                            </label>
                                                        </div>

                                                        <!-- Add Meal Dropdown (Multiple Select) -->
                                                        <div class="add-meal-dropdown mt-3" id="addMealDropdown{{$plan->id}}_{{$mealTime->id}}" style="display: none;">
                                                            <label for="mealItems{{$plan->id}}_{{$mealTime->id}}" class="form-label">Add Meal</label>
                                                            <select name="selected_meals[{{$plan->id}}][{{$mealTime->id}}][]" 
                                                                    id="mealItems{{$plan->id}}_{{$mealTime->id}}" 
                                                                    class="form-select meal-items-select select2" 
                                                                    multiple>
                                                            
                                                            </select>
                                                        </div>

                                                        <!-- Selected Meals and Swap Items -->
                                                        <div class="selected-meals mt-3" id="selectedMeals{{$plan->id}}_{{$mealTime->id}}" style="display: none;">
                                                            <h6 class="fw-bold">Selected Meals and Swap Items:</h6>
                                                            <ul class="list-group"></ul>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-5">
                                <h4>Foods</h4>
                                @foreach ($step5Foods as $category => $foods)
                                    <div class="category-section mb-3">
                                        <h5 class="category-title">{{ $category ?: 'Uncategorized' }}</h5> <!-- Handle empty categories -->

                                        <div class="row">
                                            @php
                                                // Split the foods into 2 equal columns for better UI
                                                $chunkedFoods = $foods->chunk(ceil($foods->count() / 2));
                                            @endphp

                                            @foreach ($chunkedFoods as $columnFoods)
                                                <div class="col-md-6">
                                                    @foreach ($columnFoods as $food)
                                                        @php
                                                            // Check if the food title exists in the prePlanSelectedFoods array
                                                            $isMatched = in_array($food->title, $perPlanSelectedFoods);
                                                        @endphp

                                                        <div class="form-check">
                                                            <input type="checkbox" name="setp5_foods[]" value="{{ $food->id }}" 
                                                                class="form-check-input food-checkbox" 
                                                                id="setp5Food{{ $food->id }}" 
                                                                data-food-id="{{ $food->id }}" 
                                                                data-food-name="{{ $food->title }}">

                                                                <label class="form-check-label @if($isMatched) text-primary @endif" 
                                                                    for="setp5Food{{ $food->id }}">
                                                                    {{ $food->title }}
                                                                </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="pull-right">
                            <p>Last Updated: {{ isset($activity->updated_at) ? $activity->updated_at->format('d-m-Y H:i:s') : '' }} by {{ isset($activity->user) ? $activity->user->name : '' }}</p>
                        </div>

                        <div class="">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="prePlanDetail" class="modal " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pre Plan Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup for Swap Foods -->
<div class="modal" style="display:none;" id="swapFoodsModal" tabindex="-1" aria-labelledby="swapFoodsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="swapFoodsModalLabel">Select Swap Foods</h5>
                <button type="button" class="btn-close" id="closeSwapFoodsModal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="swapFoodsForm">
                    <div class="form-group">
                        <label class="col-form-label" for="meals">Choose Meals:</label>
                        <select name="meals[]" id="meals" class="form-control meal-select w-100" multiple>
                        </select>   
                    </div>
                    <div class="form-group">
                        <div class="col-form-label">
                            <label class="col-form-label" for="foodQuantity">Food Quantity:</label>
                        </div>
                        <div >
                            <input type="text" name="food_qty" id="foodQuantity" class="form-control w-100" placeholder="Enter quantity and unit (e.g., 2 slices, 1 cup, 100 g, 1 nos)">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-form-label">
                        <label class="col-form-label" for="swapFoods">Choose Swap Foods:</label>
                        </div>
                        <div>
                        <select name="swap_foods[]" id="swapFoods" class="form-control w-100" multiple>
                            
                        </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="saveSwapFoods">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="itemName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="itemQty" class="form-label">Item Quantity</label>
                        <input type="text" class="form-control" id="itemQty">
                    </div>
                    <div class="mb-3">
                        <label for="swapItems" class="form-label">Select Swap Items</label>
                        <select class="form-select select2" id="swapItems" multiple>
                            <!-- Options will be added dynamically -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup -->
<div class="modal" id="searchFoodModal" tabindex="-1" aria-labelledby="searchFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchFoodModalLabel">Search Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="form-group">
                    <input type="hidden" id="searchFoodType" value="">
                    <input type="text" id="searchFoodQuery" class="form-control" placeholder="Search Food">
                </div>

                <!-- Buttons for search types -->
                <div class="form-group">
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-primary" id="searchFoodBtn" data-plan-id="" data-mealtime-id="" data-meal-id="" data-user-id="">Search Food</button>
                    <button type="button" class="btn btn-primary" id="woolworthsSearchBtn" data-plan-id="" data-mealtime-id="" data-meal-id="" data-user-id="">Woolworths Search Food</button>
                </div>
                </div>
                
                <!-- Loader and Results Section -->
                <div class="loader" id="loader" style="display:none;">
                    <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <h5 id="searchResultsLabel">Search Results :</h5>
                <div id="foodSearchResults" style="display:none;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Carbs</th>
                                <th>Protein</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="foodResultsTableBody">
                            <!-- Search Results will appear here -->
                        </tbody>
                    </table>
                </div>
                <div id="woolworthsSearchResults" style="display:none;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th>Size</th>
                                <th>Carbs</th>
                                <th>Protein</th>
                                <th>Fat</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="woolworthsFoodResultsTableBody">
                            <!-- Search Results will appear here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Save Plan Modal -->
<div class="modal" style="display:none;" id="savePlanModal" tabindex="-1" aria-labelledby="savePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="savePlanModalLabel">Save Your Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have unsaved changes. Do you want to save your changes before you leave?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="leaveWithoutSaving" data-bs-dismiss="modal">No, Leave</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Yes, Save</button>
            </div>
        </div>
    </div>
</div>
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Track whether there are unsaved changes
    let hasUnsavedChanges = false;
    let formSubmitting = false; // Flag to track form submission

    // Detect changes in input fields
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('input', () => {
            hasUnsavedChanges = true;
        });
    });

    // Disable beforeunload when clicking the "Update" button
    document.querySelector("#editPlanForm").addEventListener("submit", function () {
        formSubmitting = true; // Set the flag to true
        hasUnsavedChanges = false; // Reset unsaved changes
    });

    // Listen for beforeunload to show the custom modal
    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges && !formSubmitting) {
            // Prevent the page from unloading and suppress the browser's default dialog
            e.preventDefault();

            // Show your custom modal
            document.getElementById('savePlanModal').style.display = 'block';

            // Display a message for browsers that require it
            e.returnValue = ''; // This is required for some browsers like Chrome

            return '';  // Returning an empty string triggers the custom modal instead of the default browser dialog
        }
    });

    // Handle "Save Changes" button click inside the modal
    document.getElementById('saveChanges').addEventListener('click', function () {
        formSubmitting = true;  // Allow form submission
        hasUnsavedChanges = false;  // Reset the flag
        document.getElementById('editPlanForm').submit();
        document.getElementById('savePlanModal').style.display = 'none';
    });

    // Handle "Leave Without Saving" button click inside the modal
    document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
        hasUnsavedChanges = false;  // Reset the flag
        document.getElementById('savePlanModal').style.display = 'none';  // Hide modal
    });

</script>
<script>
    $(document).ready(function () {
        $.ajax({
                url: '{{ route("admin.get-items") }}',
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        $('#swapItemsSelect').empty();

                        response.items.forEach(function (item) {
                            $('#swapItemsSelect').append('<option value="' + item.id + '">' + item.title + '</option>');
                        });
                    } else {
                        alert('Failed to load items.');
                    }
                }
            });
    });
    $(document).ready(function () {
        // Use event delegation to handle dynamically added elements
        $(document).on('click', '.user-pre-plan-details', function () {
            const paymentId = $(this).data('payment-id');
            
            // Debugging log
            console.log('Clicked on user-pre-plan-details button with paymentId:', paymentId);

            $.ajax({
                url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                method: 'GET',

                success: function (response) {
                    if (response.success) {
                        console.log(response.data);

                        let modalContent = '';

                        // Add User Details at the top
                        if (response.userDetails) {
                            const userDetails = response.userDetails;
                            modalContent += `
                                <div>
                                    <h4 style="color:#7258db;">User Details</h4><hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Name:</strong> ${userDetails.name || 'N/A'}</p>
                                            <p><strong>Email:</strong> ${userDetails.email || 'N/A'}</p>
                                            <p><strong>Phone:</strong> ${userDetails.phone || 'N/A'}</p>
                                            <p><strong>DOB:</strong> ${userDetails.dob || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Postcode:</strong> ${userDetails.address || 'N/A'}</p>
                                            <p><strong>Referred By:</strong> ${userDetails.referredBy || 'N/A'}</p>
                                            <p><strong>Occupation:</strong> ${userDetails.occupation || 'N/A'}</p>
                                            <p><strong>Race/Ethnicity/Culture:</strong> ${userDetails.culture || 'N/A'}</p>
                                        </div>
                                    </div>
                                </div><hr>`;
                        }

                        // Loop through the response "data" object to display forms, questions, and answers
                        const formData = response.data;

                        Object.keys(formData).forEach(function (formName) {
                            modalContent += `<div><h4 style="color:#7258db;">${formName}</h4><hr>`;

                            const formQuestions = formData[formName];

                            Object.keys(formQuestions).forEach(function (question) {
                                let answer = formQuestions[question];
                                let answerContent = '';

                                // Safely handle different answer types (null, array, object, string)
                                if (!answer) {
                                    answerContent = 'N/A'; // Handle null values
                                } else if (Array.isArray(answer)) {
                                    answerContent = '<ul>';
                                    answer.forEach(function (item) {
                                        answerContent += `<li>${item}</li>`;
                                    });
                                    answerContent += '</ul>';
                                } else if (typeof answer === 'object') {
                                    answerContent = '<ul>';
                                    for (const [key, value] of Object.entries(answer)) {
                                        const formattedKey = key
                                            .replace(/_/g, ' ') // Replace underscores with spaces
                                            .replace(/\b\w/g, char => char.toUpperCase()); // Capitalize each word

                                        answerContent += `<li>${formattedKey}: `;
                                        if (Array.isArray(value)) {
                                            answerContent += '<ul>';
                                            value.forEach(function (subItem) {
                                                answerContent += `<li>${subItem}</li>`;
                                            });
                                            answerContent += '</ul>';
                                        } else {
                                            answerContent += `${value || 'N/A'}`;
                                        }
                                        answerContent += '</li>';
                                    }
                                    answerContent += '</ul>';
                                } else {
                                    answerContent = answer || 'N/A'; // Fallback for null values
                                }

                                modalContent += `
                                    <div>
                                        <p><strong>Q : ${question}</strong></p>
                                        <p>${answerContent}</p>
                                    </div>`;
                            });

                            modalContent += `</div><hr>`;
                        });

                        // Set the content inside the modal
                        $('#prePlanDetail .modal-body').html(modalContent);

                        // Show the modal
                        $('#prePlanDetail').modal('show');
                    } else {
                        alert('Failed to load the data');
                    }
                },
                error: function () {
                    alert('An error occurred while fetching the data.');
                }
            });
        });
    });

    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Select options",
            allowClear: true,
            width: '100%'
        });

        $('#swapFoods').select2({
            placeholder: "Search for swap foods",
            minimumInputLength: 1,  // Trigger API call after 1 character
            width: '100%',
            ajax: {
                url: '{{ route("admin.items.index") }}',  // Use your index API route
                dataType: 'json',
                delay: 250,  // Delay for better performance
                data: function(params) {
                    return {
                        query: params.term  // Send the search term as 'query' to the API
                    };
                },
                processResults: function(response) {
                    // Map API response to Select2 format
                    return {
                        results: response.items.map(function(item) {
                            return {
                                id: item.id,
                                text: item.title
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });

    $(document).ready(function () {
        const previouslySelectedMeals = {};
        const preSelectedMeals = @json($selectedMeals);
        const preSelectedItems = @json($selectedItems); // Pre-selected user items
        const preSelectedSwapItems = @json($selectedSwapItems); // Pre-selected swap items
        const payment = @json($payment);
        const userId = payment.user_id;

        // $('.meal-time-checkbox').each(function () {
        //     const checkbox = $(this);
        //     const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
        //     console.log(planId);
        //     const mealTimeId = checkbox.data('mealtime-id');
        //     const userId = checkbox.closest('.panel').find('input[name="user_id"]').val();
        //     const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
        //     const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
        //     const mealSelect = $(dropdownId).find('select');

        //     if (preSelectedMeals[planId]?.[mealTimeId]) {
        //         checkbox.prop('checked', true);
        //         $(dropdownId).show();
        //         $(selectedMealsId).show();
        //         initializeSelect2(mealSelect, mealTimeId); // Initialize Select2 with AJAX

        //         $.ajax({
        //             url: '{{ route("admin.get-meals-by-mealtime") }}',
        //             method: 'POST',
        //             data: {
        //                 plan_id: planId,
        //                 meal_time_id: mealTimeId,
        //                 user_id: userId,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function (response) {
        //                 if (response.success) {
        //                     mealSelect.empty();
        //                     response.meals.forEach(meal => {
        //                         // If preSelectedMeals[mealTimeId] is an object, we check if the meal.id exists in it
        //                         let selectedMeal = null;
        //                         if (preSelectedMeals[planId]?.[mealTimeId]?.[meal.id]) {
        //                             selectedMeal = preSelectedMeals[planId][mealTimeId][meal.id];
        //                         }                                
        //                         const isSelected = selectedMeal ? true : false;

        //                         // Get the user_meal_id if the meal is selected
        //                         const userMealId = isSelected ? selectedMeal : null;

        //                         mealSelect.append(`
        //                             <option value="${meal.id}" ${isSelected ? 'selected' : ''} 
        //                                     id="${userMealId ? userMealId : ''}">
        //                                 ${meal.name}
        //                             </option>
        //                         `);
        //                     });
        //                     mealSelect.trigger('change');
        //                 }
        //             },
        //             error: function () {
        //                 alert('Error occurred while loading meals.');
        //             }
        //         });
        //     } else {
        //         checkbox.prop('checked', false);
        //         $(dropdownId).hide();
        //         $(selectedMealsId).hide();
        //     }
        // });

        let planID = 0;
        let mealtimeID = 0;
        let mealIDs = [];
        $('.meal-time-checkbox').on('change', function () {
            const checkbox = $(this);
            const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
            const mealTimeId = checkbox.data('mealtime-id');
            mealIDs = [];
            planID = planId;
            mealtimeID = mealTimeId;

            // Construct unique IDs for the dropdown and selected meals container
            const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
            const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
            const mealSelect = $(dropdownId).find('select');

            console.log(`Toggling dropdown for: Plan ID: ${planId}, Meal Time ID: ${mealTimeId}`);
            console.log(`Dropdown ID: ${dropdownId}, Selected Meals ID: ${selectedMealsId}`);

            // Check if checkbox is checked
            if (checkbox.is(':checked')) {
                $(dropdownId).show();          // Show Add Meal dropdown
                $(selectedMealsId).show();     // Show Selected Meals container
                initializeSelect2(mealSelect, mealTimeId); // Initialize Select2 with AJAX

                // Load dynamic dropdown options via AJAX
                $.ajax({
                    url: '{{ route("admin.get-meals-by-mealtime") }}', // Replace with your route to fetch meals dynamically
                    method: 'POST',
                    data: {
                        plan_id: planId,
                        user_id: userId,
                        meal_time_id: mealTimeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            // Clear previous options
                            mealSelect.empty();
                            response.meals.forEach(meal => {
                                // If preSelectedMeals[mealTimeId] is an object, we check if the meal.id exists in it
                                let selectedMeal = null;
                                if (preSelectedMeals[planId]?.[mealTimeId]?.[meal.id]) {

                                    selectedMeal = preSelectedMeals[planId][mealTimeId][meal.id];
                                }
                                const isSelected = selectedMeal ? true : false;
                                if (isSelected) {
                                    // Push selected meal ID into mealIDs array
                                    mealIDs.push(meal.id);
                                }
                                // Get the user_meal_id if the meal is selected
                                const userMealId = isSelected ? selectedMeal : null;

                                mealSelect.append(`
                                    <option value="${meal.id}" ${isSelected ? 'selected' : ''} 
                                            id="${userMealId ? userMealId : ''}">
                                        ${meal.name}
                                    </option>
                                `);
                            });
                            mealSelect.trigger('change');

                        } else {
                            alert('Failed to load meals for the selected meal time.');
                        }
                    },
                    error: function () {
                        alert('Error occurred while loading meals.');
                    }
                });
            } else {
                $(dropdownId).hide();          // Hide dropdown
                $(selectedMealsId).hide();     // Hide selected meals
                $(dropdownId).find('select').val([]).trigger('change'); // Clear selected values
                $(selectedMealsId).empty();    // Clear selected meals content
            }
        });

        function initializeSelect2(mealSelect, mealTimeId) {
            mealSelect.select2({
                placeholder: 'Search meals...',
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.get-meals-by-mealtime") }}',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            meal_time_id: mealTimeId,
                            search: params.term || '', // Search keyword
                            _token: '{{ csrf_token() }}'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.meals.map(meal => ({
                                id: meal.id,
                                text: meal.name
                            }))
                        };
                    },
                    cache: true
                }
            });
        }

        $('.meal-items-select').on('change', function () {
            const ids = $(this).attr('id').replace('mealItems', '').split('_');
            const planId = ids[0];
            const mealTimeId = ids[1];

            planID = planId;
            mealtimeID = mealTimeId;

            const selectedMealsContainer = $(`#selectedMeals${planId}_${mealTimeId}`);
            const currentSelectedMeals = $(this).val() || [];
            const oldMeals = previouslySelectedMeals[`${planId}_${mealTimeId}`] || [];

            const newMeals = currentSelectedMeals.filter(mealId => !oldMeals.includes(mealId));
            const unselectedMeals = oldMeals.filter(mealId => !currentSelectedMeals.includes(mealId));
            previouslySelectedMeals[`${planId}_${mealTimeId}`] = currentSelectedMeals;

            unselectedMeals.forEach(mealId => {
                $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
            });

            let selectedOptionId = [];
            $(this).find('option:selected').each(function() {
                const selectedOptionId = $(this).attr('id');  // This retrieves the 'id' of the selected option
                console.log('Selected Option ID:', selectedOptionId); // Log or process the ID as needed
            });

            newMeals.forEach(mealId => {
                $.ajax({
                    url: '{{ route("admin.get-meal-items") }}',
                    method: 'POST',
                    data: {
                        meal_id: mealId,
                        user_id: userId,
                        plan_id: planId,
                        meal_time_id: mealTimeId,
                        type:'edit',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            const mealName = response.meal_name;
                            const mealId = response.meal_id;
                            const items = response.data;
                            const mealContainer = createMealContainer(
                                planId,
                                mealTimeId,
                                response.meal_id,
                                response.meal_name,
                                response.data,
                                userId,
                                preSelectedItems,
                                preSelectedSwapItems
                            );

                            selectedMealsContainer.append(mealContainer);
                        } else {
                            alert('Failed to fetch meal details.');
                        }
                    },
                    error: function () {
                        // alert('Error while fetching meal details.');
                    }
                });
            });


        });

        function createMealContainer(planId, mealTimeId, mealId, mealName, items, userId, preSelectedItems, preSelectedSwapItems) {
            let mealContainer = $(`
                <div id="mealContainer_${planId}_${mealTimeId}_${mealId}" class="meal-container mt-3">
                    <input type="hidden" name="meals[${planId}][${mealTimeId}][]" value="${mealId}">
                    <div class="meal-name-edit">
                        <input type="text" value="${mealName}" class="editable-meal-name"
                            data-meal-time-id="${mealTimeId}" data-meal-id="${mealId}"
                            data-plan-id="${planId}" data-user-id="${userId}"
                            style="border: none; font-weight: bold; font-size: 14px; color: #6610f2; width: 50%;" title="Click to edit"/>
                        <button type="button" class="btn btn-primary add-food-button"
                            data-meal-id="${mealId}" data-meal-time-id="${mealTimeId}" data-plan-id="${planId}" data-user-id="${userId}">
                            Add Food
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Swap Items</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="items-table-body"></tbody>
                        </table>
                    </div>
                </div>
            `);

            const tableBody = mealContainer.find('.items-table-body');

            // Populate items and swap items
            items.forEach(item => {
                const isSelectedItem = preSelectedItems[mealTimeId] &&
                                    preSelectedItems[mealTimeId][mealId] &&
                                    preSelectedItems[mealTimeId][mealId].includes(item.id);

                let swapItemsHTML = '';

                if (item.swapItems && item.swapItems.length > 0) {
                    swapItemsHTML = item.swapItems.map(swapItem => {
                        const isSelectedSwapItem = preSelectedSwapItems[mealTimeId] &&
                                                preSelectedSwapItems[mealTimeId][mealId] &&
                                                preSelectedSwapItems[mealTimeId][mealId][item.id] &&
                                                preSelectedSwapItems[mealTimeId][mealId][item.id].includes(swapItem.id);

                        return `
                            <li>
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]"
                                    value="${swapItem.id}" class="form-check-input" ${isSelectedSwapItem ? 'checked' : ''}>
                                <label class="form-check-label">${swapItem.name}</label>
                            </li>
                        `;
                    }).join('');
                } else {
                    swapItemsHTML = '<span class="text-muted">No swap items available</span>';
                }

                // Append a row for the item and its swap items
                tableBody.append(`
                    <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${item.id}">
                        <td>
                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                value="${item.id}" class="form-check-input" ${isSelectedItem ? 'checked' : ''}>
                            <label class="form-check-label">${item.name}</label>
                        </td>
                        <td>
                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm edit-item"
                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${item.qty}" title="Edit"><i class="icofont-edit text-success"></i></button>
                            <button type="button" class="btn btn-sm delete-item"
                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                        </td>
                    </tr>
                `);
            });

            // Return the constructed meal container
            return mealContainer;
        }

        $('.food-checkbox').on('change', function () {
            const foodId = $(this).data('food-id');
            const foodName = $(this).data('food-name');
            $('#swapFoodsModalLabel').text(`Add ${foodName} to Meals`);
            if ($(this).is(':checked')) {
                $('#swapFoodsModal').data('food-id', foodId);
                $('#swapFoodsModal').data('food-name', foodName);

                // Open the modal
                $('#swapFoodsModal').modal('show');
                fetchMeals(); 
            }
        });

        $('.meal-select').select2({
            width: '100%',
            placeholder: "Search for meals...",
            allowClear: true,
            ajax: {
                url: '{{ route("admin.meals.index") }}', // API route to get meals dynamically
                dataType: 'json',
                delay: 250, // Delay for better search performance
                data: function (params) {
                    return {
                        search: params.term, // Send search keyword
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.meals.map(meal => ({
                            id: meal.id,
                            text: meal.name
                        }))
                    };
                },
                cache: true
            }
        });
        
        // Function to fetch meals dynamically
        function fetchMeals() {
            $.ajax({
                url: '{{ route("admin.meals.index") }}',
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        let mealsSelect = $('#meals');
                        mealsSelect.empty();
                        response.meals.forEach(meal => {
                            mealsSelect.append(new Option(meal.name, meal.id, false, false));
                        });
                    }
                },
                error: function () {
                    alert('Error loading meals.');
                }
            });
        }

        $('#swapFoodsModal').on('show.bs.modal', function () {
            $.ajax({
                url: '{{ route("admin.items.index") }}',
                type: 'GET',
                success: function(response) {
                    const swapFoodsSelect = $('#swapFoods');
                    swapFoodsSelect.empty(); // Clear existing options
                    
                    if (response.items.length > 0) {
                        response.items.forEach(item => {
                            const option = new Option(item.title, item.id, false, false);
                            swapFoodsSelect.append(option);
                        });
                    } else {
                        swapFoodsSelect.append('<option disabled>No swap foods available</option>');
                    }

                    // Reinitialize Select2 to update the options
                    swapFoodsSelect.trigger('change');
                },
                error: function() {
                    alert('Error fetching swap foods. Please try again.');
                }
            });
        });
        
        $('#closeSwapFoodsModal').on('click', function () { 
            $('#swapFoodsModal').modal('hide');
            $('#swapFoods').val([]).trigger('change');
            $('#meals').val([]).trigger('change');
            $('.food-checkbox').prop('checked', false);
        });
    
        $(window).on('click', function (event) {
            if ($(event.target).is('#swapFoodsModal')) {
                $('#swapFoodsModal').hide();
            }
        });

        $('#saveSwapFoods').on('click', function () {
            const selectedMeals = $('#meals').val();
            const selectedFoods = $('#swapFoods').val();
            const foodId = $('#swapFoodsModal').data('food-id');
            const foodName = $('#swapFoodsModal').data('food-name');
            const isEditMode = $('#swapFoodsModal').data('edit-mode');
            const mealId = $('#swapFoodsModal').data('meal-id');
            const foodQty = $('#foodQuantity').val();
            if (!isEditMode && (!selectedMeals || selectedMeals.length === 0)) {
                alert('Please select at least one meal.');
                return;
            }

            // Fetch existing swap foods
            $.ajax({
                url: '{{ route("admin.get-swap-foods") }}',
                method: 'POST',
                data: { food_id: foodId, swap_food_ids: selectedFoods, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    let swapFoods = response.swapFoods || [];

                    if (isEditMode) {
                        // **Edit Mode Logic: Update the existing row**
                        const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
                        const tableBody = $(mealContainerId).find('.items-table-body');
                        const foodRow = tableBody.find(`tr[data-food-id="${foodId}"]`);

                        // Update swap items list in the row
                        const swapItemsHTML = swapFoods.length > 0 
                            ? swapFoods.map(item => `
                                <li>
                                    <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${foodId}][]" value="${item.id}" class="form-check-input">
                                    <label>${item.name} (${item.qty})</label>
                                </li>
                            `).join('') 
                            : '<span class="text-muted">No swap items available</span>';

                        foodRow.find('td:nth-child(2)').html(`<ul class="list-unstyled">${swapItemsHTML}</ul>`);

                    } else {
                        // **Add Mode Logic: Add new food to selected meals**
                        selectedMeals.forEach(mealId => {
                            const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
                            const tableBody = $(mealContainerId).find('.items-table-body');

                            if (tableBody.find(`tr[data-food-id="${foodId}"]`).length === 0) {
                                const swapItemsHTML = swapFoods.length > 0 
                                    ? swapFoods.map(item => `
                                        <li>
                                            <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${foodId}][]" value="${item.id}" class="form-check-input">
                                            <label>${item.name} (${item.qty})</label>
                                        </li>
                                    `).join('') 
                                    : '<span class="text-muted">No swap items available</span>';

                                tableBody.append(`
                                    <tr data-food-id="${foodId}">
                                        <td>
                                            <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]" value="${foodId}" class="form-check-input">
                                            <label class="form-check-label">${foodName} (${foodQty})</label>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm edit-food" data-food-id="${foodId}" data-meal-id="${mealId}" data-swap-foods='${JSON.stringify(swapFoods)}' title="Edit"><i class="icofont-edit text-success"></i></button>
                                            <button class="btn  btn-sm delete-food" data-food-id="${foodId}" data-meal-id="${mealId}" title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                        });
                    }

                    // Save data to the database
                    $.ajax({
                        url: '{{ route("admin.save-swap-food") }}',
                        method: 'POST',
                        data: {
                            food_id: foodId,
                            swap_foods: swapFoods,
                            meal_ids: isEditMode ? [mealId] : selectedMeals,
                            user_id: userId,
                            food_qty: foodQty,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                console.log('Swap foods saved successfully.');
                                if(!isEditMode){
                                    updateFoodCount(foodId, 'add');
                                }
                            } else {
                                alert('Failed to save swap foods.');
                            }
                        },
                        error: function () {
                            alert('Error occurred while saving swap foods.');
                        }
                    });

                    // Reset fields and close modal
                    $('.food-checkbox').prop('checked', false);
                    $('#swapFoodsModal').modal('hide').removeData();
                    $('#swapFoodsModal').removeData('edit-mode').removeData('meal-id');
                    $('#swapFoods').val([]).trigger('change');
                    $('#meals').val([]).trigger('change').closest('.form-group').show(); // Show meals dropdown after edit
                },
                error: function () {
                    alert('Error fetching stored swap foods.');
                }
            });
        });

        function updateFoodCount(foodId, type) {
            let countLabel = $(`#setp5Food${foodId}`).siblings('.form-check-label');
            
            // Check if the correct label is selected
            if (countLabel.length === 0) {
                console.error(`Label for foodId ${foodId} not found.`);
                return;
            }

            let countText = countLabel.text();
            console.log('Original count text:', countText);

            // Extract current count from label text (if any)
            let match = countText.match(/\((\d+)\)$/);
            let currentCount = match ? parseInt(match[1]) : 0;
            console.log('Current count:', currentCount);

            // Initialize newCount
            let newCount = currentCount;

            // Adjust count based on type
            if (type === 'delete') {
                console.log('Action: delete');
                newCount = currentCount - 1;
            } else {
                console.log('Action: add');
                newCount = currentCount + 1;
            }

            // Ensure count doesn't go below zero
            if (newCount < 0) newCount = 0;

            console.log('New count:', newCount);

            // Update the label
            if (match) {
                // If a count already exists, replace it
                countLabel.text(countText.replace(/\(\d+\)$/, `(${newCount})`));
            } else {
                // If no count exists, append it
                countLabel.text(`${countText.trim()} (${newCount})`);
            }
        }

        // Edit Food Item
        $(document).on('click', '.edit-food', function (e) {
            e.preventDefault();
            const foodId = $(this).data('food-id');
            const mealId = $(this).data('meal-id');
            const swapFoods = $(this).data('swap-foods'); // Get swap items from data attribute
            const foodQty = $(this).data('food-qty');
            console.log('swapFoods:', swapFoods);
            if (typeof swapFoods === 'string') {
                try {
                    swapFoods = JSON.parse(swapFoods); // Convert JSON string to object
                } catch (error) {
                    console.error('Error parsing swapFoods:', error);
                    swapFoods = [];
                }
            }

            console.log('Parsed swapFoods:', swapFoods);

            // Set edit mode flag and store mealId for later use
            $('#swapFoodsModal').data('edit-mode', true);
            $('#swapFoodsModal').data('meal-id', mealId);
            $('#swapFoodsModal').data('food-id', foodId);
            $('#swapFoodsModal').data('food-name', $(this).closest('tr').find('label').text());
            $('#swapFoodsModal').data('food-qty', foodQty);
            
            $('#swapFoods').select2({
                width: '100%',
                dropdownParent: $('#swapFoodsModal') 
            });

            // Pre-select swap foods if available
            if (Array.isArray(swapFoods) && swapFoods.length > 0) {
                let swapFoodIds = swapFoods.map(item => item.id);

                console.log('swapFoodIds for Select2:', swapFoodIds);

                // Use a small delay to ensure Select2 values are set correctly
                setTimeout(function () {
                    $('#swapFoods').val(swapFoodIds).trigger('change');
                }, 200);
            } else {
                console.warn('swapFoods is empty:', swapFoods);
            }

            $('#meals').val([mealId]).trigger('change');
            $('#foodQuantity').val(foodQty);
            $('#swapFoodsModalLabel').text('Edit Food');
            $('#swapFoodsModal').modal('show');
        });

        // Delete Food Item
        $(document).on('click', '.delete-food', function (e) {
            e.preventDefault();
            const foodId = $(this).data('food-id');
            const mealId = $(this).data('meal-id');
            const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
            $.ajax({
                url: '{{ route("admin.delete-purchase-plan-food") }}',  // You need to create this route
                method: 'POST',
                data: {
                    item_id: foodId,
                    meal_id: mealId,
                    meal_time_id: mealtimeID,
                    plan_id: planID,
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        alert('Food deleted successfully!');
                        $(mealContainerId).find(`tr[data-food-id="${foodId}"]`).remove();
                        updateFoodCount(foodId, 'delete');
                    } else {
                        alert('Failed to delete food.');
                    }
                },
                error: function () {
                    alert('Error while deleting food.');
                }
            })
            
        });

        // Edit Item Button Action
        $(document).on('click', '.edit-item', function () {
            const itemId = $(this).data('item-id');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('meal-time-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');
            const itemQty = $(this).data('item-qty');
            // Open modal and populate with item details and swap items
            openEditItemModal(itemId, mealId, mealTimeId, planId, userId);
        });

        // Delete Item Button Action
        $(document).on('click', '.delete-item', function () {
            const itemId = $(this).data('item-id');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('meal-time-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');
            // Your delete logic for the item (e.g., show confirmation and delete item)
            if (confirm('Are you sure you want to delete this item?')) {
                console.log('Delete Item:', itemId, mealId, mealTimeId, planId, userId);
                $.ajax({
                    url: '{{ route("admin.delete-purchase-plan-food") }}',  // You need to create this route
                    method: 'POST',
                    data: {
                        item_id: itemId,
                        meal_id: mealId,
                        meal_time_id: mealTimeId,
                        plan_id: planId,
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Item deleted successfully!');
                            // You can perform the deletion via AJAX or remove the item row from the table
                            $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`).remove();
                        } else {
                            alert('Failed to delete item.');
                        }
                    },
                    error: function () {
                        alert('Error while deleting item.');
                    }
                })
            }
        });

        // Open the Edit Item Modal and populate it with item details and swap items
        function openEditItemModal(itemId, mealId, mealTimeId, planId, userId) {
            $.ajax({
                url: '{{ route("admin.get-swap-items") }}',  // API route to get swap items for this item
                method: 'POST',
                data: {
                    item_id: itemId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        const item = response.item;  // Item details
                        const selectedSwapItems = response.selectedSwapItems;  // Array of selected swap items (id and name)
                        let Qty = response?.item?.qty !== null ? response.item.qty : item.qty;

                        // Set the item name in the modal
                        $('#itemName').val(item.title);
                        $('#itemQty').val(itemQty);
                        // Clear the existing options in the select dropdown
                        $('#swapItems').empty();

                        // Initialize the Select2 dropdown
                        $('#swapItems').select2({
                            placeholder: "Search for swap foods",
                            minimumInputLength: 1,
                            ajax: {
                                url: '{{ route("admin.items.index") }}', // Dynamic API for searching swap foods
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        query: params.term  // Send the search term to the server
                                    };
                                },
                                processResults: function(data) {
                                    return {
                                        results: data.items.map(function(item) {
                                            return { id: item.id, text: item.title };  // Return items in required format
                                        })
                                    };
                                },
                                cache: true
                            }
                        });

                        // Append selected items from the response to the dropdown
                        selectedSwapItems.forEach(function(swapItem) {
                            const option = new Option(swapItem.name, swapItem.id, true, true); // Use name for the label, id for the value
                            $('#swapItems').append(option);
                        });

                        // Trigger a change event to update the Select2 dropdown UI
                        $('#swapItems').trigger('change');

                        // Store item details in the modal's form
                        $('#editItemForm').data({
                            'item-id': item.id,
                            'meal-id': mealId,
                            'meal-time-id': mealTimeId,
                            'plan-id': planId,
                            'user-id': userId,
                            'item-qty': Qty
                        });

                        // Show the modal
                        $('#editItemModal').modal('show');
                    } else {
                        alert('Failed to load item details.');
                    }
                },
                error: function () {
                    alert('Error fetching item details.');
                }
            });
        }
        
        // Save changes when the modal form is submitted
        $('#editItemForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            const itemId = $('#editItemForm').data('item-id');
            const mealId = $('#editItemForm').data('meal-id');
            const mealTimeId = $('#editItemForm').data('meal-time-id');
            const planId = $('#editItemForm').data('plan-id');
            const userId = $('#editItemForm').data('user-id');
            const selectedSwapItems = $('#swapItems').val(); // Get selected swap items
            const itemQty = $('#itemQty').val();
            // Send the updated swap items to the server via AJAX
            $.ajax({
                url: '{{ route("admin.update-food-swap-foods") }}', // Server route to handle update
                method: 'POST',
                data: {
                    item_id: itemId,
                    swap_items: selectedSwapItems, // Array of selected swap item IDs
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    item_qty: itemQty,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        selectedSwapFoods = response.foods;
                        $('#editItemModal').modal('hide'); // Close the modal
                        updateItemSwapItemsInUI(itemId, planId, mealId, mealTimeId, selectedSwapFoods); // Update UI with new swap items
                        alert('Swap items updated successfully!');
                    } else {
                        alert('Failed to update swap items.');
                    }
                },
                error: function () {
                    alert('An error occurred while updating swap items.');
                }
            });
        });

        // Function to update swap items in the UI after saving
        function updateItemSwapItemsInUI(itemId, planId, mealId, mealTimeId, selectedSwapFoods) {
            // Find the row for the item
            const itemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            const swapItemsContainer = itemRow.find('td:nth-child(2) ul'); // Targeting the swap items list

            // Clear the existing swap items
            swapItemsContainer.empty();

            if (selectedSwapFoods) {
                // For each selected swap item, create a list item
                selectedSwapFoods.forEach(swapFood => {
                    // Assuming you have a way to get swap item names from their IDs (e.g., a lookup object or another AJAX call)

                    swapItemsContainer.append(`
                        <li>
                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]" value="${swapFood.id}" class="form-check-input">
                            <label class="form-check-label">${swapFood.title} (${swapFood.qty})</label>
                        </li>
                    `);
                });
            } else {
                // If no swap items are selected
                swapItemsContainer.append('<span class="text-muted">No swap items available</span>');
            }
        }
    });

    $(document).on('change', '.editable-meal-name', function () {
        const mealId = $(this).data('meal-id');
        const planId = $(this).data('plan-id');
        const userId = $(this).data('user-id');
        const categoryId = $(this).data('category-id');
        const mealTimeId = $(this).data('meal-time-id');
        const newMealName = $(this).val().trim();

        $.ajax({
            url: '{{ route("admin.update-meal-name") }}',
            method: 'POST',
            data: {
                meal_id: mealId,
                user_id: userId,
                plan_id: planId,
                meal_name: newMealName,
                meal_time_id: mealTimeId,
                category_id: categoryId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    console.log(response.message);
                    alert('Meal name updated successfully!');
                    window.location.reload();
                } else {
                    alert('Failed to update meal name.');
                }
            },
            error: function () {
                alert('Error while updating meal name.');
            }
        });
    });

    // $(document).ready(function () {
    //     // Event triggered when the modal is about to be shown
    //     $('#foodModal').on('show.bs.modal', function (event) {
    //         // Button that triggered the modal
    //         var button = $(event.relatedTarget);

    //         // Extract info from data-* attributes
    //         var mealId = button.data('meal-id');
    //         var mealTimeId = button.data('meal-time-id');
    //         var planId = button.data('plan-id');
    //         var userId = button.data('user-id');
    //         console.log(mealId, mealTimeId, planId, userId);
    //         // Update the modal's content
    //         var modal = $(this);
    //          // Set the data attributes on the Search Food button
    //         modal.find('#searchFoodBtn')
    //             .attr('data-plan-id', planId)
    //             .attr('data-mealtime-id', mealTimeId)
    //             .attr('data-meal-id', mealId)
    //             .attr('data-user-id', userId);

    //         // Set the data attributes on the Woolworths Search Food button
    //         modal.find('#woolworthsSearchBtn')
    //             .attr('data-plan-id', planId)
    //             .attr('data-mealtime-id', mealTimeId)
    //             .attr('data-meal-id', mealId)
    //             .attr('data-user-id', userId);
    //     });
    // });

    $(document).ready(function() {
        // Initially hide swap item dropdown if is_swiped is no
        if ($('input[name="is_swiped"]:checked').val() == '1') {
            $('#swapItemsContainer').show();
        } else {
            $('#swapItemsContainer').hide();
        }

        // Show/hide the swap item dropdown based on is_swiped selection
        $('input[name="is_swiped"]').on('change', function() {
            if ($(this).val() == '1') {
                $('#swapItemsContainer').show();
            } else {
                $('#swapItemsContainer').hide();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const mealTimeCheckboxes = document.querySelectorAll('.meal-time-checkbox');

        mealTimeCheckboxes.forEach(mealTimeCheckbox => {
            mealTimeCheckbox.addEventListener('change', (event) => {
                const mealTimeId = event.target.getAttribute('data-mealtime-id');
                const relatedCheckboxes = document.querySelectorAll(`.meal-time-related[data-mealtime-id="${mealTimeId}"] input`);

                relatedCheckboxes.forEach(checkbox => {
                    checkbox.disabled = !event.target.checked; // Disable if mealTime is unchecked
                });
            });

            // Trigger change event on page load to ensure proper state
            mealTimeCheckbox.dispatchEvent(new Event('change'));
        });
    });

    $(document).ready(function () {
        const loader = $('#loader');
        const foodResultsTableBody = $('#foodResultsTableBody');
        const woolWorthsFoodResultsTableBody = $('#woolworthsFoodResultsTableBody');
        const foodSearchResults = $('#foodSearchResults'); // Wraps the results
        const woolworthsSearchResults = $('#woolworthsSearchResults'); // Wraps the results

        // Handle click on "Add Food" button
        $(document).on('click', '.add-food-button', function () {
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('meal-time-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');
            
            // Pass data to modal
            $('#searchFoodModal').find('#searchFoodBtn')
                .attr('data-plan-id', planId)
                .attr('data-mealtime-id', mealTimeId)
                .attr('data-meal-id', mealId)
                .attr('data-user-id', userId);
            
            $('#searchFoodModal').find('#woolworthsSearchBtn')
                .attr('data-plan-id', planId)
                .attr('data-mealtime-id', mealTimeId)
                .attr('data-meal-id', mealId)
                .attr('data-user-id', userId);

            // Open modal
            $('#searchFoodModal').modal('show');
        });

        // Handle search type selection (System Food Search or Woolworths Search)
        $(document).on('click', '#searchFoodBtn', function () {
            // Set the search type to system search and trigger the search
            $('#searchFoodType').val('system');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('mealtime-id');
            const userId = $(this).data('user-id');
            const planId = $(this).data('plan-id');
            performSearch(mealId, mealTimeId, userId, planId);
        });

        $(document).on('click', '#woolworthsSearchBtn', function () {
            // Set the search type to woolworths search and trigger the search
            $('#searchFoodType').val('woolworths');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('mealtime-id');
            const userId = $(this).data('user-id');
            const planId = $(this).data('plan-id');
            performSearch(mealId, mealTimeId, userId, planId);
        });

        // Perform the search based on the search type
        function performSearch(mealId, mealTimeId, userId, planId) {
            const query = $('#searchFoodQuery').val().trim();
            const searchType = $('#searchFoodType').val();

            console.log(query, searchType, mealId, mealTimeId, userId, planId);
            // If query is empty, show alert
            if (query === '') {
                alert('Please enter a search term.');
                return;
            }

            // Clear previous results and show loader
            foodResultsTableBody.empty();
            loader.show();
            foodSearchResults.hide();
            woolworthsSearchResults.hide();

            // Execute different searches based on the search type
            if (searchType === 'system') {
                // Perform system food search
                $.ajax({
                    url: '{{ route("admin.items.index") }}', // Use Laravel route for system food search
                    type: 'GET',
                    data: { query: query },
                    success: function (response) {
                        loader.hide();
                        $('#searchResultsLabel').text('System Search Results:');

                        foodSearchResults.show();
                        if (response.items.length > 0) {
                            response.items.forEach(item => {
                                const imagePath = item.image ? `{{ asset('private/public/storage/') }}/${item.image}` : 'https://via.placeholder.com/50';
                                const row = `
                                    <tr>
                                        <td>${item.title}</td>
                                        <td>${item.carbs ?? 'N/A'}</td>
                                        <td>${item.protein ?? 'N/A'}</td>
                                        <td><img src="${imagePath}" alt="Food Image" width="50" height="50"></td>
                                        <td>
                                            <button class="btn btn-success add-food-btn" 
                                                    data-food-id="${item.id}" 
                                                    data-meal-id="${mealId}" 
                                                    data-mealtime-id="${mealTimeId}" 
                                                    data-user-id="${userId}" 
                                                    data-plan-id="${planId}">
                                                Add Food
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                foodResultsTableBody.append(row);
                            });
                        } else {
                            foodResultsTableBody.append('<tr><td colspan="5" class="text-center">No results found.</td></tr>');
                        }
                    },
                    error: function () {
                        loader.hide();
                        alert('Error occurred while searching. Please try again.');
                    }
                });
            } else if (searchType === 'woolworths') {
                // Perform Woolworths product search
                $.ajax({
                    url: '{{ route("woolworths-product-search") }}', // Use route for Woolworths search
                    type: 'GET',
                    data: { query: query },
                    success: function (response) {
                        loader.hide();
                        $('#searchResultsLabel').text('Woolworths Search Results:');
                        woolworthsSearchResults.show();
                        if (response.results.length > 0) {
                            response.results.forEach(product => {
                                const row = `
                                    <tr>
                                        <td>${product.name}</td>
                                        <td>${product.barcode}</td>
                                        <td>$${product.price}</td>
                                        <td>${product.size}</td>
                                        <td>${product.nutrition.carbohydrate || 'N/A'}</td>
                                        <td>${product.nutrition.protein || 'N/A'}</td>
                                        <td>${product.nutrition.fat || 'N/A'}</td>
                                        <td><img src="${product.image}" width="50" height="50"></td>
                                        <td>
                                            <button class="btn btn-success add-woolworths-food" 
                                                    data-name="${product.name}" 
                                                    data-image="${product.image}" 
                                                    data-protein="${product.nutrition.protein || 0}" 
                                                    data-carbs="${product.nutrition.carbohydrate || 0}"
                                                    data-fat="${product.nutrition.fat || 0}"
                                                    data-meal-id="${mealId}" data-mealtime-id="${mealTimeId}" data-plan-id="${planId}" data-user-id="${userId}">
                                                Add Woolworths Food
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                $('#woolworthsFoodResultsTableBody').append(row);
                            });
                        } else {
                            $('#woolworthsFoodResultsTableBody').append('<tr><td colspan="8" class="text-center">No results found.</td></tr>');
                        }
                    },
                    error: function () {
                        loader.hide();
                        alert('Error occurred while searching Woolworths. Please try again.');
                    }
                });
            }
        }

        $(document).on('click', '.add-food-btn', function () {
            const foodId = $(this).data('food-id');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('mealtime-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');

            loader.show();

            $.ajax({
                url: '{{ route("admin.add-food") }}',
                type: 'POST',
                data: {
                    item_id: foodId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    type: 'system',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        alert('Food added successfully!');

                        const food = response.data;
                        const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;
                        const tableBody = $(mealContainerId).find('.items-table-body');
                        
                        const swapFoods = food.swapItems ?? [];  // Use the simplified swapItems array

                        if (tableBody.find(`tr[data-food-id="${food.id}"]`).length === 0) {

                            const swapItemsHTML = swapFoods.length > 0 
                                ? swapFoods.map(swapItem => `
                                    <li>
                                        <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${food.id}][]" 
                                            value="${swapItem.id}" 
                                            class="form-check-input">
                                        <label>${swapItem.name} (${swapItem.qty})</label>
                                    </li>
                                `).join('') 
                                : '<span class="text-muted">No swap items available</span>';

                            tableBody.append(`
                                <tr data-food-id="${food.id}">
                                    <td>
                                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" 
                                            value="${food.id}" 
                                            class="form-check-input">
                                        <label class="form-check-label">${food.title} (${food.qty})</label>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">${swapItemsHTML}</ul>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm edit-food" 
                                                data-food-id="${food.id}" 
                                                data-meal-id="${mealId}" 
                                                data-swap-foods='${JSON.stringify(swapFoods)}'
                                                data-food-qty="${food.qty}">
                                            <i class="icofont-edit text-success"></i>
                                        </button>
                                        <button class="btn  btn-sm delete-food" 
                                                data-food-id="${food.id}" 
                                                data-meal-id="${mealId}">
                                            <i class="icofont-ui-delete text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        }
                        $('#foodSearchResults').hide();
                        $('#searchFoodQuery').val('');
                        $('#searchResultsLabel').text('');
                        $('#searchFoodModal').modal('hide');
                    } else {
                        alert('Failed to add food: ' + (response.message || 'Unknown error.'));
                    }

                },
                error: function () {
                    alert('Error while adding food.');
                },
                complete: function () {
                    loader.hide();
                }
            });
        });

        // Add Food Button inside the search results
        $(document).on('click', '.add-woolworths-food', function () {
            const name = $(this).data('name');
            const image = $(this).data('image');
            const protein = $(this).data('protein');
            const carbs = $(this).data('carbs');
            const fat = $(this).data('fat');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('mealtime-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');

            loader.show();

            $.ajax({
                url: '{{ route("admin.add-food") }}',
                type: 'POST',
                data: {
                    name: name,
                    image: image,
                    protein: protein,
                    carbs: carbs,
                    fat: fat,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    type: 'woolworths',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        alert('Food added successfully!');
                        const food = response.data;
                        const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;
                        const tableBody = $(mealContainerId).find('.items-table-body');

                        // Check if the food item already exists in the table
                        if (tableBody.find(`tr[data-food-id="${food.id}"]`).length === 0) {

                            // Since swap items are not available, show the message
                            const swapItemsHTML = '<span class="text-muted">No swap items available</span>';

                            // Append the single food item row to the table
                            tableBody.append(`
                                <tr data-food-id="${food.id}">
                                    <td>
                                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" value="${food.id}" class="form-check-input">
                                        <label class="form-check-label">${food.title} (${food.qty})</label>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">${swapItemsHTML}</ul> <!-- No swap items -->
                                    </td>
                                    <td>
                                        <button class="btn  btn-sm edit-food" data-food-id="${food.id}" data-meal-id="${mealId}" title="Edit"><i class="icofont-edit text-success"></i></button>
                                        <button class="btn  btn-sm delete-food" data-food-id="${food.id}" data-meal-id="${mealId}" title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                                    </td>
                                </tr
                            `);
                        }
                        $('#woolworthsSearchResults').hide();
                        $('#searchFoodQuery').val('');
                        $('#searchResultsLabel').text('');
                        $('#searchFoodModal').modal('hide');
                    } else {
                        alert('Failed to add food: ' + (response.message || 'Unknown error.'));
                    }
                },
                error: function () {
                    alert('Error while adding food.');
                },
                complete: function () {
                    loader.hide();
                }
            });
        });
    });
    // $(document).ready(function () {
    //     const loader = $('#loader');
    //     const searchResults = $('#searchResults');
    //     const resultsTableBody = $('#resultsTableBody');

    //     // Open modal when Woolworths button is clicked
    //     $('#woolworthsSearchBtn').on('click', function (e) {
    //         $('#woolworthsModal').modal('show');
    //         $('#foodModal').modal('hide');

    //         var mealId = $(this).data('meal-id');
    //         var mealTimeId = $(this).data('mealtime-id');
    //         var planId = $(this).data('plan-id');
    //         var userId = $(this).data('user-id');
    //         console.log(mealId, mealTimeId, planId, userId);
    //         $('#woolworthsModal').find('#search-product')
    //             .attr('data-plan-id', planId)
    //             .attr('data-mealtime-id', mealTimeId)
    //             .attr('data-meal-id', mealId)
    //             .attr('data-user-id', userId);
    //     });

    //     // Handle the search form submission
    //     $('#woolworthsSearchForm').on('submit', function (e) {
    //         e.preventDefault();
    //         loader.show();
    //         searchResults.hide();
    //         resultsTableBody.empty();  // Clear previous results

    //         var mealId = $('#search-product').data('meal-id');
    //         var mealTimeId = $('#search-product').data('mealtime-id');
    //         var planId = $('#search-product').data('plan-id');
    //         var userId = $('#search-product').data('user-id');
    //         console.log(mealId, mealTimeId, planId, userId);
    //         const query = $('#searchQuery').val();

    //         $.ajax({
    //             url: '{{ route("woolworths-product-search") }}',
    //             type: 'GET',
    //             data: { query: query },
    //             success: function (response) {
    //                 if (response.results && response.results.length > 0) {
    //                     response.results.forEach(product => {
    //                         const row = `
    //                             <tr>
    //                                 <td>${product.name}</td>
    //                                 <td>${product.barcode}</td>
    //                                 <td>$${product.price}</td>
    //                                 <td>${product.size}</td>
    //                                 <td>${product.nutrition.carbohydrate || 'N/A'}</td>
    //                                 <td>${product.nutrition.protein || 'N/A'}</td>
    //                                 <td><img src="${product.image}" width="50" height="50"></td>
    //                                 <td>
    //                                     <button 
    //                                         class="btn btn-success add-woolworths-food" 
    //                                         data-name="${product.name}" 
    //                                         data-image="${product.image}" 
    //                                         data-protein="${product.nutrition.protein || 0}" 
    //                                         data-carbs="${product.nutrition.carbohydrate || 0}"
    //                                         data-meal-id="${mealId}" data-mealtime-id="${mealTimeId}" data-plan-id="${planId}" data-user-id="${userId}">
    //                                         Add Food
    //                                     </button>
    //                                 </td>
    //                             </tr>
    //                         `;
    //                         resultsTableBody.append(row);
    //                     });
    //                     searchResults.show();
    //                 } else {
    //                     resultsTableBody.append('<tr><td colspan="8" class="text-center">No products found.</td></tr>');
    //                     searchResults.show();
    //                 }
    //             },
    //             error: function () {
    //                 alert('Error fetching products. Please try again.');
    //             },
    //             complete: function () {
    //                 loader.hide();
    //             }
    //         });
    //     });

    //     // Add Food Button inside the search results
    //     $(document).on('click', '.add-woolworths-food', function () {
    //         const name = $(this).data('name');
    //         const image = $(this).data('image');
    //         const protein = $(this).data('protein');
    //         const carbs = $(this).data('carbs');
    //         const mealId = $(this).data('meal-id');
    //         const mealTimeId = $(this).data('mealtime-id');
    //         const planId = $(this).data('plan-id');
    //         const userId = $(this).data('user-id');

    //         loader.show();

    //         $.ajax({
    //             url: '{{ route("admin.add-food") }}',
    //             type: 'POST',
    //             data: {
    //                 name: name,
    //                 image: image,
    //                 protein: protein,
    //                 carbs: carbs,
    //                 meal_id: mealId,
    //                 meal_time_id: mealTimeId,
    //                 plan_id: planId,
    //                 user_id: userId,
    //                 type: 'woolworths',
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             success: function (response) {
    //                 if (response.success) {
    //                     alert('Food added successfully!');
    //                     const food = response.data;
    //                     const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;
    //                     const tableBody = $(mealContainerId).find('.items-table-body');

    //                     // Check if the food item already exists in the table
    //                     if (tableBody.find(`tr[data-food-id="${food.id}"]`).length === 0) {

    //                         // Since swap items are not available, show the message
    //                         const swapItemsHTML = '<span class="text-muted">No swap items available</span>';

    //                         // Append the single food item row to the table
    //                         tableBody.append(`
    //                             <tr data-food-id="${food.id}">
    //                                 <td>
    //                                     <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" value="${food.id}" class="form-check-input">
    //                                     <label class="form-check-label">${food.title}</label>
    //                                 </td>
    //                                 <td>
    //                                     <ul class="list-unstyled">${swapItemsHTML}</ul> <!-- No swap items -->
    //                                 </td>
    //                                 <td>
    //                                     <button class="btn btn-warning btn-sm edit-food" data-food-id="${food.id}" data-meal-id="${mealId}">Edit</button>
    //                                     <button class="btn btn-danger btn-sm delete-food" data-food-id="${food.id}" data-meal-id="${mealId}">Delete</button>
    //                                 </td>
    //                             </tr
    //                         `);
    //                     }
    //                     $('#woolworthsModal').modal('hide');
    //                 } else {
    //                     alert('Failed to add food: ' + (response.message || 'Unknown error.'));
    //                 }
    //             },
    //             error: function () {
    //                 alert('Error while adding food.');
    //             },
    //             complete: function () {
    //                 loader.hide();
    //             }
    //         });
    //     });

    //     $('#searchFoodBtn').on('click', function (e) {
    //         $('#searchFoodModal').modal('show');
    //         $('#foodModal').modal('hide');

    //         var mealId = $(this).data('meal-id');
    //         var mealTimeId = $(this).data('mealtime-id');
    //         var planId = $(this).data('plan-id');
    //         var userId = $(this).data('user-id');
    //         console.log(mealId, mealTimeId, planId, userId);
    //         $('#searchFoodModal').find('#search-food')
    //             .attr('data-plan-id', planId)
    //             .attr('data-mealtime-id', mealTimeId)
    //             .attr('data-meal-id', mealId)
    //             .attr('data-user-id', userId);
    //     });
        
    //     $('#searchFoodForm').on('submit', function (e) {
    //         e.preventDefault();

    //         const query = $('#searchFoodQuery').val().trim();
    //         const mealId = $('#search-food').data('meal-id');
    //         const mealTimeId = $('#search-food').data('mealtime-id');
    //         const userId = $('#search-food').data('user-id');
    //         const planId = $('#search-food').data('plan-id');

    //         if (query === '') {
    //             alert('Please enter a search term.');
    //             return;
    //         }

    //         // Show loader and hide results
    //         $('#loader').show();
    //         $('#foodSearchResults').hide();

    //         $.ajax({
    //             url: '{{ route("admin.items.index") }}', // Use Laravel route helper
    //             type: 'GET',
    //             data: {
    //                 query: query
    //             },
    //             success: function (response) {
    //                 $('#loader').hide();
    //                 $('#foodSearchResults').show();
    //                 $('#foodResultsTableBody').empty();  // Clear previous results

    //                 if (response.items.length > 0) {
    //                     response.items.forEach(item => {
    //                         const imagePath = item.image 
    //                                 ? `{{ asset('private/public/storage/') }}/${item.image}` 
    //                                 : 'https://via.placeholder.com/50';  // Fallback image if no image is set

    //                         const row = `
    //                             <tr>
    //                                 <td>${item.title}</td>
    //                                 <td>${item.carbs ?? 'N/A'}</td>
    //                                 <td>${item.protein ?? 'N/A'}</td>
    //                                 <td><img src="${imagePath}" alt="Food Image" width="50" height="50"></td>
    //                                 <td>
    //                                     <button class="btn btn-success add-food-btn" 
    //                                             data-food-id="${item.id}" 
    //                                             data-meal-id="${mealId}" 
    //                                             data-mealtime-id="${mealTimeId}" 
    //                                             data-user-id="${userId}" 
    //                                             data-plan-id="${planId}">
    //                                         Add
    //                                     </button>
    //                                 </td>
    //                             </tr>
    //                         `;
    //                         $('#foodResultsTableBody').append(row);
    //                     });
    //                 } else {
    //                     $('#foodResultsTableBody').append('<tr><td colspan="5" class="text-center">No results found.</td></tr>');
    //                 }
    //             },
    //             error: function () {
    //                 $('#loader').hide();
    //                 alert('Error occurred while searching. Please try again.');
    //             }
    //         });
    //     });

    //     $(document).on('click', '.add-food-btn', function () {
    //         const foodId = $(this).data('food-id');
    //         const mealId = $(this).data('meal-id');
    //         const mealTimeId = $(this).data('mealtime-id');
    //         const planId = $(this).data('plan-id');
    //         const userId = $(this).data('user-id');

    //         loader.show();

    //         $.ajax({
    //             url: '{{ route("admin.add-food") }}',
    //             type: 'POST',
    //             data: {
    //                 item_id: foodId,
    //                 meal_id: mealId,
    //                 meal_time_id: mealTimeId,
    //                 plan_id: planId,
    //                 user_id: userId,
    //                 type: 'system',
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             success: function (response) {
    //                 if (response.success) {
    //                     alert('Food added successfully!');

    //                     const food = response.data;
    //                     const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;
    //                     const tableBody = $(mealContainerId).find('.items-table-body');
                        
    //                     const swapFoods = food.swapItems ?? [];  // Use the simplified swapItems array

    //                     if (tableBody.find(`tr[data-food-id="${food.id}"]`).length === 0) {

    //                         const swapItemsHTML = swapFoods.length > 0 
    //                             ? swapFoods.map(swapItem => `
    //                                 <li>
    //                                     <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${food.id}][]" 
    //                                         value="${swapItem.id}" 
    //                                         class="form-check-input">
    //                                     <label>${swapItem.name}</label>
    //                                 </li>
    //                             `).join('') 
    //                             : '<span class="text-muted">No swap items available</span>';

    //                         tableBody.append(`
    //                             <tr data-food-id="${food.id}">
    //                                 <td>
    //                                     <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" 
    //                                         value="${food.id}" 
    //                                         class="form-check-input">
    //                                     <label class="form-check-label">${food.title}</label>
    //                                 </td>
    //                                 <td>
    //                                     <ul class="list-unstyled">${swapItemsHTML}</ul>
    //                                 </td>
    //                                 <td>
    //                                     <button class="btn btn-warning btn-sm edit-food" 
    //                                             data-food-id="${food.id}" 
    //                                             data-meal-id="${mealId}" 
    //                                             data-swap-foods='${JSON.stringify(swapFoods)}'>
    //                                         Edit
    //                                     </button>
    //                                     <button class="btn btn-danger btn-sm delete-food" 
    //                                             data-food-id="${food.id}" 
    //                                             data-meal-id="${mealId}">
    //                                         Delete
    //                                     </button>
    //                                 </td>
    //                             </tr>
    //                         `);
    //                     }

    //                     $('#searchFoodModal').modal('hide');
    //                 } else {
    //                     alert('Failed to add food: ' + (response.message || 'Unknown error.'));
    //                 }

    //             },
    //             error: function () {
    //                 alert('Error while adding food.');
    //             },
    //             complete: function () {
    //                 loader.hide();
    //             }
    //         });
    //     });
    // });
</script>

@endsection
