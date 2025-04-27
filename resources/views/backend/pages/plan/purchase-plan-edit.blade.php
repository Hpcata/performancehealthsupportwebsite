@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style>
    .hidden-checkbox {
        display: none;
        /* Completely hides the checkbox */
        /* margin-right: 5px; */
    }
    hr {
        margin-top: 0px !important;
        margin-bottom: 15px !important;
        border-top: 1px solid black !important;
    }

    .meal-name-edit {
        display: flex;
        margin-bottom: 1rem;
        justify-content: space-between;
    }
    #loader-2 {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        display: none;
    }
    #loader-2 img {
        width: 50px;
        /* Adjust size */
        height: 50px;
    }

    .btn-group-sm>.btn,
    .btn-sm {
        padding: 3px 5px !important;
    }
</style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <!-- Left Section: Title -->
                <div class="d-flex align-items-center flex-wrap">
                    <h3 class="fw-bold mb-0 me-3">Edit Plan</h3>
                    <h3 class="fw-bold mb-0" style="">({{ $payment->user->name }})</h3>
                </div>

                <!-- Right Section: Buttons -->
                <div class="col-auto d-flex w-sm-100 mt-2 mt-sm-0">
                    <a href="javascript:void(0);" class="btn btn-primary btn-set-task w-sm-100 mx-3 user-pre-plan-details" data-payment-id="15">View User Details</a>
                    <a href="https://test.performancehealthsupport.com/private/public/admin/purchase-plans" class="btn btn-primary btn-set-task w-sm-100">Back</a>
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
                                                <input type="hidden"
                                                    name="meal_times[{{$plan->id}}][]"
                                                    value="{{ $mealTime->id }}"
                                                    id="hidden-mealtime">
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
                                                    <div class="mealTimeDetailsDiv">
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
                                                            <h6 class="fw-bold">Selected Meals and Foods:</h6>
                                                            <ul class="list-group"></ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="nutrition-details">
                                                <p><strong>Plan Total: </strong>Protein: <span class="planTotalProtein" id="allProteinTotal">{{ $totalProtein }}g</span> | Carb: <span class="planTotalCarbs" id="allCarbsTotal">{{ $totalCarbs }}g</span> | Fat: <span class="PlanTotalFat" id="allFatTotal">{{ $totalFat }}g</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-5">
                                <div style="max-height: 90vh; overflow-y: auto; overflow-x: hidden; border: 1px solid #ddd; padding: 10px; border-radius: 8px; position: sticky; top:15px;">
                                    <h4>Foods</h4>
                                    @foreach ($step5Foods as $category => $foods)
                                    @php
                                    $category = \App\Models\FoodCategory::find($category);
                                    $category = isset($category) ? $category->name : 'Uncategorized';
                                    @endphp
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

                                                    <label class="form-check-label "
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

<div class="modal" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editItemId">
                    <input type="hidden" id="editMealId">
                    <input type="hidden" id="editPlanId">
                    <input type="hidden" id="editMealTimeId">
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="editItemName" readonly>
                    </div>
                    <div class="mx-3" id="dynamicQtyMeasurementContainer"></div>

                    <div class="nutrition-info mt-3 mx-3">
                        <p><strong>Protein:</strong> <span id="modalProtein">0g </span>,
                            <strong>Carb:</strong> <span id="modalCarbs">0g </span>,
                            <strong>Fat:</strong> <span id="modalFat">0g </span>
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="saveItemChanges">Save</button>
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
                        <!-- <button type="button" class="btn btn-primary" id="searchFoodBtn" data-plan-id="" data-mealtime-id="" data-meal-id="" data-user-id="">Search Food</button> -->
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

<div class="modal" id="editSwapItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Edit Swap Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSwapItemForm">
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="editItemName" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="swapFoodDropdown" class="form-label">Select Swap Food</label>
                        <select id="swapFoodDropdown" class="form-select mb-3">
                        </select>
                    </div>

                    <div class="mb-3 mx-3" id="dynamicQtyMeasurementContainer">
                    </div>

                    <div class="mb-3 mt-3 mx-3">
                        <label class="form-label">Nutrition Info</label>
                        <p>Protein: <span id="modalProtein">0g</span> | Carb: <span id="modalCarbs">0g</span> | Fat: <span id="modalFat">0g</span></p>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" id="editMainItemId">
                    <input type="hidden" id="editSwapItemId">
                    <input type="hidden" id="editSwapPlanId">
                    <input type="hidden" id="editSwapMealTimeId">
                    <input type="hidden" id="editSwapMealId">
                    <input type="hidden" id="previousSwapItemId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveSwapItemChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="addSwapItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Add Swap Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSwapItemForm">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="itemName" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="swapFoodDropdown" class="form-label">Select Swap Food</label>
                        <select id="swapItemDropdown" class="form-select mb-3">
                        </select>
                    </div>

                    <div class="mb-3 mx-3" id="dynamicQtyMeasurementContainer">
                    </div>

                    <div class="mb-3 mt-3 mx-3">
                        <label class="form-label">Nutrition Info</label>
                        <p>Protein: <span id="modalProtein">0g</span> | Carb: <span id="modalCarbs">0g</span> | Fat: <span id="modalFat">0g</span></p>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" id="refItemId">
                    <input type="hidden" id="swapItemId">
                    <input type="hidden" id="swapPlanId">
                    <input type="hidden" id="swapMealTimeId">
                    <input type="hidden" id="swapMealId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveSwapItem">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add More Food Modal -->
<div class="modal" id="addMoreFoodModal" tabindex="-1" aria-labelledby="addMoreFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="editSwapItemForm">
                    <!-- Food Dropdown -->
                    <div class="mb-3">
                        <label for="foodDropdown" class="form-label">Select Food</label>
                        <select id="foodDropdown" class="form-select">
                            <!-- Dynamically populated -->
                        </select>
                    </div>

                    <!-- Dynamic Quantity & Measurement -->
                    <div class="mb-3 mx-3" id="dynamicQtyMeasurementContainer"></div>
                    <div class="mb-3">
                        <strong>Protein:</strong> <span id="modalProtein">0g</span> |
                        <strong>Carb:</strong> <span id="modalCarbs">0g</span> |
                        <strong>Fat:</strong> <span id="modalFat">0g</span>
                    </div>
                    <input type="hidden" id="itemId">
                    <input type="hidden" id="foodPlanId">
                    <input type="hidden" id="foodMealTimeId">
                    <input type="hidden" id="foodMealId">
                    <input type="hidden" id="foodUserId">
                </form>
                <button type="button" class="btn btn-primary mb-2 add-food-button" id="searchFoodModal" data-plan-id="" data-meal-id="" data-meal-time-id="" data-user-id="">
                    Add Woolworths Food
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveMoreFoodBtn" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" style="display:none;" id="mealFoodAddModal" tabindex="-1" aria-labelledby="mealFoodAddModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mealFoodAddModalLabel">Select Swap Foods</h5>
                <button type="button" class="btn-close" id="closeMealFoodAddModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mealFoodAddForm">
                    <input type="hidden" name="food_id" id="foodId" value="">
                    <input type="hidden" name="food_name" id="foodName" value="">
                    <input type="hidden" name="food_carbs" id="foodCarbs" value="">
                    <input type="hidden" name="food_protein" id="foodProtein" value="">
                    <input type="hidden" name="food_fat" id="foodFat" value="">
                   
                    <div class="form-group">
                        <label class="col-form-label" for="meals">Choose Meals:</label>
                        <select name="meals[]" id="meals" class="form-control meal-select" multiple required>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="editItemName" readonly>
                    </div>

                    <!-- Dynamic Quantity & Measurement -->
                    <div class="mb-3 mx-3" id="dynamicQtyMeasurementContainer"></div>
                    <div class="mb-3">
                        <strong>Protein:</strong> <span id="modalProtein">0g</span> |
                        <strong>Carb:</strong> <span id="modalCarbs">0g</span> |
                        <strong>Fat:</strong> <span id="modalFat">0g</span>
                    </div>

                    <input type="hidden" id="itemId">
                    <input type="hidden" id="foodPlanId">
                    <input type="hidden" id="foodMealTimeId">
                    <input type="hidden" id="foodMealId">
                    <input type="hidden" id="foodUserId">
                    <button type="button" class="btn btn-primary" id="saveMealFood">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="loader-2" style="display: none;">
    <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="Loading..." />
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
    document.addEventListener('DOMContentLoaded', function() {
        let hasUnsavedChanges = false;
        let intendedHref = ''; // Store the intended link URL

        // Track changes in form fields
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('input', () => {
                hasUnsavedChanges = true;
            });
        });

        // Custom navigation detection
        document.querySelectorAll('a').forEach(anchor => {
            anchor.addEventListener('click', function(event) {
                if (hasUnsavedChanges) {
                    event.preventDefault(); // Prevent immediate navigation

                    // Correctly capture the intended URL
                    const clickedLink = event.target.closest('a');

                    if (clickedLink) {
                        intendedHref = clickedLink.href;
                        console.log('Intended Link:', intendedHref); // ✅ Correctly logs the clicked link URL
                        document.getElementById('savePlanModal').style.display = 'block'; // Show modal
                    }
                }
            });
        });

        // Suppress browser's default popup for page reload/close
        window.addEventListener('beforeunload', function(event) {
            if (hasUnsavedChanges) {
                event.preventDefault();
            }
        });

        // Modal Button: "Save Changes"
        document.getElementById('saveChanges').addEventListener('click', function() {
            hasUnsavedChanges = false;
            document.getElementById('editPlanForm').submit();
            document.getElementById('savePlanModal').style.display = 'none';
        });

        // Modal Button: "No Leave"
        document.getElementById('leaveWithoutSaving').addEventListener('click', function() {
            hasUnsavedChanges = false;

            // Correctly redirect to the stored intended URL (like Food link)
            if (intendedHref) {
                window.location.href = intendedHref;
            }
        });

        // Form Submit Logic
        document.getElementById('editPlanForm').addEventListener('submit', function() {
            hasUnsavedChanges = false; // Clear flag on form submission
        });
    });

    $(document).ready(function() {
        // Use event delegation to handle dynamically added elements
        $(document).on('click', '.user-pre-plan-details', function() {
            const paymentId = $(this).data('payment-id');

            // Debugging log
            console.log('Clicked on user-pre-plan-details button with paymentId:', paymentId);

            $.ajax({
                url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                method: 'GET',

                success: function(response) {
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

                        Object.keys(formData).forEach(function(formName) {
                            modalContent += `<div><h4 style="color:#7258db;">${formName}</h4><hr>`;

                            const formQuestions = formData[formName];

                            Object.keys(formQuestions).forEach(function(question) {
                                let answer = formQuestions[question];
                                let answerContent = '';

                                // Safely handle different answer types (null, array, object, string)
                                if (!answer) {
                                    answerContent = 'N/A'; // Handle null values
                                } else if (Array.isArray(answer)) {
                                    answerContent = '<ul>';
                                    answer.forEach(function(item) {
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
                                            value.forEach(function(subItem) {
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
                error: function() {
                    alert('An error occurred while fetching the data.');
                }
            });
        });
    });
    // $('#swapFoods').val(null).trigger('change');

    $(document).ready(function() {
        const previouslySelectedMeals = {};
        const preSelectedMeals = @json($selectedMeals);
        const preSelectedItems = @json($selectedItems); // Pre-selected user items
        const preSelectedSwapItems = @json($selectedSwapItems); // Pre-selected swap items
        const payment = @json($payment);
        const userId = payment.user_id;
        const loader = $('#loader');
        console.log(preSelectedMeals);
        $('.meal-time-checkbox').each(function() {
            const checkbox = $(this);
            const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
            const mealTimeId = checkbox.data('mealtime-id');
            // console.log(`Plan ID: ${planId}, Meal Time ID: ${mealTimeId}`);
            const userId = checkbox.closest('.panel').find('input[name="user_id"]').val();
            const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
            const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
            const mealSelect = $(dropdownId).find('select');
            const mealTimeDetailsDiv = checkbox.closest('li').find('.mealTimeDetailsDiv');

            if (preSelectedMeals[planId]?.[mealTimeId]) {
                $(dropdownId).show();
                $(selectedMealsId).show();
                mealTimeDetailsDiv.css('display', 'none');

                initializeSelect2(mealSelect, mealTimeId); // Initialize Select2 with AJAX

                $.ajax({
                    url: '{{ route("admin.get-meals-by-mealtime") }}',
                    method: 'POST',
                    data: {
                        plan_id: planId,
                        meal_time_id: mealTimeId,
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            mealSelect.empty(); // Clear previous options before adding new ones

                            response.meals.forEach(meal => {
                                // Determine if the meal is pre-selected
                                let isSelected = !!preSelectedMeals[planId]?.[mealTimeId]?.[meal.id];

                                mealSelect.append(new Option(meal.name, meal.id, isSelected, isSelected));
                            });

                            mealSelect.trigger('change'); // Ensure Select2 reflects the changes
                        }
                    },
                    error: function() {
                        alert('Error occurred while loading meals.');
                    }
                });

            } else {
                checkbox.prop('checked', false);
                $(dropdownId).hide();
                $(selectedMealsId).hide();
                mealTimeDetailsDiv.css('display', 'block');
            }

            // ✅ Fix: Handle meal removal when "X" is clicked in select2
            mealSelect.on('select2:unselect', function(e) {
                let mealId = e.params.data.id;

                if (preSelectedMeals[planId]?.[mealTimeId]?.[mealId]) {
                    delete preSelectedMeals[planId][mealTimeId][mealId]; // Remove from preSelectedMeals
                }

                $(this).find(`option[value="${mealId}"]`).remove(); // Remove the option
                $(this).trigger('change'); // Refresh Select2
            });
        });

        let planID = 0;
        let mealtimeID = 0;
        let mealIDs = [];
        let mealTimeIds = [];
        $('.meal-time-checkbox').on('change', function() {

            const checkbox = $(this);
            const mealTimeDetailsDiv = checkbox.closest('li').find('.mealTimeDetailsDiv');

            if (checkbox.is(':checked')) {
                mealTimeDetailsDiv.css('display', 'block'); // Hide the element
            } else {
                mealTimeDetailsDiv.css('display', 'none'); // Hide the element
            }

            const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
            const mealTimeId = checkbox.data('mealtime-id');
            mealIDs = [];
            planID = planId;
            mealtimeID = mealTimeId;
            mealTimeIds.push(mealTimeId);
            // Construct unique IDs for the dropdown and selected meals container
            const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
            const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
            const mealSelect = $(dropdownId).find('select');

            console.log(`Toggling dropdown for: Plan ID: ${planId}, Meal Time ID: ${mealTimeId}`);
            console.log(`Dropdown ID: ${dropdownId}, Selected Meals ID: ${selectedMealsId}`);

            // Check if checkbox is checked
            if (checkbox.is(':checked')) {
                $(dropdownId).show(); // Show Add Meal dropdown
                $(selectedMealsId).show(); // Show Selected Meals container
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
                    success: function(response) {
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
                    error: function() {
                        alert('Error occurred while loading meals.');
                    }
                });
            } else {
                // $(dropdownId).hide();          // Hide dropdown
                // $(selectedMealsId).hide();     // Hide selected meals
                // $(dropdownId).find('select').val([]).trigger('change'); // Clear selected values
                // $(selectedMealsId).empty();    // Clear selected meals content

            }
            calculateMealNutrition();
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
                    data: function(params) {
                        return {
                            meal_time_id: mealTimeId,
                            user_id: userId,
                            search: params.term || '', // Search keyword
                            _token: '{{ csrf_token() }}'
                        };
                    },
                    processResults: function(data) {
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
       
        // Function to calculate nutrition
        function calculateMealNutrition() {
            let grandTotalCarbs = 0;
            let grandTotalProtein = 0;
            let grandTotalFat = 0;

            $('.meal-container').each(function () {
                let totalCarbs = 0;
                let totalProtein = 0;
                let totalFat = 0;

                $(this).find('.items-table-body tr').each(function () {
                    const $firstTd = $(this).find('td').first();
                    const $input = $firstTd.find('input');

                    totalCarbs += parseFloat($input.data('carbs')) || 0;
                    totalProtein += parseFloat($input.data('protein')) || 0;
                    totalFat += parseFloat($input.data('fat')) || 0;
                });

                $(this).data({
                    totalCarbs: totalCarbs.toFixed(1),
                    totalProtein: totalProtein.toFixed(1),
                    totalFat: totalFat.toFixed(1)
                });

                grandTotalCarbs += totalCarbs;
                grandTotalProtein += totalProtein;
                grandTotalFat += totalFat;

                // console.log(`Meal Details:
                // - Total Carb: ${totalCarbs.toFixed(2)}g
                // - Total Protein: ${totalProtein.toFixed(2)}g
                // - Total Fat: ${totalFat.toFixed(2)}g`);
            });

            $('#allCarbsTotal').text(`${Math.round(grandTotalCarbs)}g`);
            $('#allProteinTotal').text(`${Math.round(grandTotalProtein)}g`);
            $('#allFatTotal').text(`${Math.round(grandTotalFat)}g`);

            // console.log(`Total Nutrition Values:
            // - Total Carb: ${grandTotalCarbs.toFixed(2)}g
            // - Total Protein: ${grandTotalProtein.toFixed(2)}g
            // - Total Fat: ${grandTotalFat.toFixed(2)}g`);
        }

        $('.meal-items-select').on('change', function() {
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
                // $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
                const removedMealContainer = $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`);

                // Decrement item and swap item counts
                removedMealContainer.find('input[name^="items"]').each(function() {
                    const itemId = $(this).val();
                    updateFoodCount(itemId, -1); // Decrease item count
                });

                removedMealContainer.find('input[name^="swap_items"]').each(function() {
                    const swapItemId = $(this).val();
                    updateFoodCount(swapItemId, -1); // Decrease swap item count
                });

                // Finally remove the meal container
                removedMealContainer.remove();
                calculateMealNutrition();

            });

            let selectedOptionId = [];
            $(this).find('option:selected').each(function() {
                const selectedOptionId = $(this).attr('id'); // This retrieves the 'id' of the selected option
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
                        type: 'edit',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
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
                                preSelectedSwapItems,
                                response.total_carbs,
                                response.total_fat,
                                response.total_protein
                            );

                            selectedMealsContainer.append(mealContainer);
                            calculateMealNutrition(); // Trigger calculation after new meal is added

                            response.data.forEach(item => {
                                // ✅ Increment count for each item in the meal
                                updateFoodCount(item.id, 1);

                                // ✅ Increment count for each swap item if available
                                item.swapItems.forEach(swapItem => {
                                    updateFoodCount(swapItem.id, 1);
                                });
                            });
                        } else {
                            alert('Failed to fetch meal details.');
                        }
                    },
                    error: function() {
                        // alert('Error while fetching meal details.');
                    }
                });
            });

        });

        function createMealContainer(planId, mealTimeId, mealId, mealName, items, userId, preSelectedItems, preSelectedSwapItems, totalCarbs, totalFat, totalProtein) {
            let mealContainer = $(`
                <div id="mealContainer_${planId}_${mealTimeId}_${mealId}" class="meal-container mt-3">
                    <input type="hidden" name="meals[${planId}][${mealTimeId}][]" value="${mealId}">
                    <div class="meal-name-edit">
                        <input type="text" value="${mealName}" class="editable-meal-name"
                            data-meal-time-id="${mealTimeId}" data-meal-id="${mealId}"
                            data-plan-id="${planId}" data-user-id="${userId}"
                            style="border: none; font-weight: bold; font-size: 14px; color: #6610f2; width: 60%;" title="Click to edit"/>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Food</th>
                                    <th>Swap Foods</th>
                                </tr>
                            </thead>
                            <tbody class="items-table-body"></tbody>
                        </table>
                        <button type="button" class="btn btn-primary add-more-food mb-2"
                            data-meal-id="${mealId}" data-meal-time-id="${mealTimeId}" data-plan-id="${planId}" data-user-id="${userId}">
                            Add More Food
                        </button>
                    </div>
                    <p><strong>Meal Total: </strong>Protein: <span class="totalProtein">${Math.round(totalProtein)}g</span> | Carb: <span class="totalCarbs">${Math.round(totalCarbs)}g</span> | Fat: <span class="totalFat">${Math.round(totalFat)}g</span></p>
                </div>
            `);

            const tableBody = mealContainer.find('.items-table-body');

            items.forEach(item => {
                const isSelectedItem = preSelectedItems[mealTimeId] &&
                    preSelectedItems[mealTimeId][mealId] &&
                    preSelectedItems[mealTimeId][mealId].includes(item.id);

                const swapsFoods = item.swapItems || [];

                // Parse selected_qty_unit for the main item
                let selectedQtyUnits = [];
                try {
                    selectedQtyUnits = typeof item.selected_qty_unit === 'string'
                        ? JSON.parse(item.selected_qty_unit)
                        : (Array.isArray(item.selected_qty_unit) ? item.selected_qty_unit : []);
                } catch (e) {
                    console.warn('Invalid JSON in item.selected_qty_unit:', item.selected_qty_unit, e);
                }

                const checkedQtyUnits = selectedQtyUnits.filter(q => q.checked === "true");

                const qtyUnitDisplay = checkedQtyUnits.length
                    ? `(${checkedQtyUnits.map(q => `${formatQty(q.qty)}${["g", "mL"].includes(q.unit) ? q.unit : ' ' + q.unit}`).join(' or ')})`
                    : `(${formatQty(item.qty)}${["g", "mL"].includes(item.unit) ? item.unit : ' ' + item.unit})`;

                // Generate swap items HTML
                let swapItemsHTML = '';
                if (item.swapItems && item.swapItems.length > 0) {
                    swapItemsHTML = item.swapItems.map(swapItem => {
                        const isSelectedSwapItem = preSelectedSwapItems[mealTimeId] &&
                            preSelectedSwapItems[mealTimeId][mealId] &&
                            preSelectedSwapItems[mealTimeId][mealId][item.id] &&
                            preSelectedSwapItems[mealTimeId][mealId][item.id].includes(swapItem.id);

                        // Parse selected_qty_unit for each swap item
                        let selectedQtyUnits = [];
                        try {
                            selectedQtyUnits = typeof swapItem.selected_qty_unit === 'string'
                                ? JSON.parse(swapItem.selected_qty_unit)
                                : (Array.isArray(swapItem.selected_qty_unit) ? swapItem.selected_qty_unit : []);
                        } catch (e) {
                            console.warn('Invalid JSON in swapItem.selected_qty_unit:', swapItem.selected_qty_unit, e);
                        }

                        const checkedQtyUnitSwapItems = selectedQtyUnits.filter(q => q.checked === "true");
                        const swapItemQtyUnitDisplay = checkedQtyUnitSwapItems.length
                            ? `(${checkedQtyUnitSwapItems.map(q => `${formatQty(q.qty)}${["g", "mL"].includes(q.unit) ? q.unit : ' ' + q.unit}`).join(' or ')})`
                            : `(${formatQty(swapItem.qty)}${["g", "mL"].includes(swapItem.unit) ? swapItem.unit : ' ' + swapItem.unit})`;

                        return `
                            <li class="list-unstyled" data-swap-item-id="${swapItem.id}">
                                <div class="d-flex justify-content-between align-items-start mb-0">
                                    <div class="col-9">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]"
                                                value="${swapItem.id}" class="form-check-input me-2 d-none" ${isSelectedSwapItem ? 'checked' : 'checked'} 
                                                data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}">
                                            <label class="form-check-label">${swapItem.name}</label>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-2"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swap-qty="${swapItem.qty}" data-swap-unit="${swapItem.unit}"
                                            data-selected-qty-unit='${JSON.stringify(swapItem.selected_qty_unit)}'
                                            title="Edit"><i class="icofont-edit text-success"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Delete">
                                            <i class="icofont-ui-delete text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="px-2 mb-2 fw-bold">${swapItemQtyUnitDisplay}</p>
                                        <p class="mb-0 px-2">Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                    </div>
                                </div>
                            </li>
                        `;
                    }).join('');
                } else {
                    swapItemsHTML = `<li class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="col-9">
                                            <span class="text-muted">No swap items available</span>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                                title="Add"><i class="icofont-plus text-primary"></i>
                                            </button>
                                        </div>
                                    </li>`;
                }

                // Append table row
                tableBody.append(`
                    <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${item.id}">
                        <td class="text-wrap" width="50%">
                            <div class="d-flex justify-content-between align-items-start mb-0">
                                <div class="col-9">
                                    <div class="d-flex align-items-start">
                                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                            value="${item.id}" class="form-check-input me-2 d-none" ${isSelectedItem ? 'checked' : 'checked'} 
                                            data-carbs="${item.carbs}" data-protein="${item.protein}" data-fat="${item.fat}">
                                        <label class="form-check-label flex-grow-1">${item.name}</label>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${item.qty}" data-item-unit="${item.unit}" 
                                        data-selected-qty-unit='${JSON.stringify(item.selected_qty_unit)}'
                                        title="Edit"><i class="icofont-edit text-success"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swapfood-id="${swapsFoods[0]?.swap_item_id || ''}"  
                                        title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="px-2 mb-2 fw-bold">${qtyUnitDisplay}</p>
                                    <p class="px-2 mb-0">Protein: ${Math.round(item.protein)}g | Carb: ${Math.round(item.carbs)}g | Fat: ${Math.round(item.fat)}g</p>
                                </div>
                            </div>
                        </td>
                        <td width="50%">
                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                        </td>
                    </tr>
                `);
            });

            return mealContainer;
        }

        function formatQty(value) {
            // Check if it's a string fraction like "1/2", "3/4"
            if (typeof value === 'string' && /^\d+\/\d+$/.test(value)) {
                return value; // keep it as-is
            }

            const floatVal = parseFloat(value);
            if (isNaN(floatVal)) return value;

            return floatVal % 1 === 0 ? floatVal.toFixed(0) : floatVal.toFixed(1);
        }

        $('.food-checkbox').on('change', function () {
            const foodId = $(this).data('food-id');
            const foodName = $(this).data('food-name');
            // const uniqueMealTimeIds = mealTimeIds.filter((value, index, self) => self.indexOf(value) === index);
            // console.log(uniqueMealTimeIds);
            if (!$(this).is(':checked')) return;

            // Update modal title & hidden inputs
            $('#mealFoodAddModalLabel').text(`Add ${foodName} to Meals`);
            $('#mealFoodAddModal').data('food-id', foodId);
            $('#mealFoodAddModal').data('food-name', foodName);
            $('#mealFoodAddModal #foodId').val(foodId);
            $('#mealFoodAddModal #foodName').val(foodName);
            $('#mealFoodAddModal #editItemName').val(foodName);

            // Open modal first (optionally show loader inside)
            $('#mealFoodAddModal').modal('show');

            // Call AJAX to get food info including selected_qty_unit
            $.ajax({
                url: '{{ route("admin.items.index") }}?food_id=' + foodId,
                type: 'GET',
                success: function (response) {
                    if (response.items) {
                        const foodItem = response.items;
                        const carb = Math.round(foodItem.carbs * 10) / 10;
                        const protein = Math.round(foodItem.protein * 10) / 10;
                        const fat = Math.round(foodItem.fat * 10) / 10;

                        let selectedQtyUnits = [];

                        try {
                            const rawJson = foodItem.selected_qty_unit;
                            if (rawJson && rawJson !== "null") {
                                if (typeof rawJson === 'string') {
                                    selectedQtyUnits = JSON.parse(rawJson);
                                } else {
                                    selectedQtyUnits = rawJson; // Already parsed
                                }
                            }
                        } catch (e) {
                            console.warn('Invalid selected_qty_unit JSON:', e);
                        }

                        if (!Array.isArray(selectedQtyUnits) || selectedQtyUnits.length === 0) {
                            selectedQtyUnits = [{
                                qty: foodItem.qty,
                                unit: foodItem.unit,
                                checked: false
                            }];
                        }

                        // Macros
                        $('#mealFoodAddModal #foodCarbs').val(foodItem.carbs || 0);
                        $('#mealFoodAddModal #foodProtein').val(foodItem.protein || 0);
                        $('#mealFoodAddModal #foodFat').val(foodItem.fat || 0);
                        $('#mealFoodAddModal #modalCarbs').text((carb || 0) + 'g');
                        $('#mealFoodAddModal #modalProtein').text((protein || 0) + 'g');
                        $('#mealFoodAddModal #modalFat').text((fat || 0) + 'g');

                        // Qty + unit builder
                        const $container = $('#mealFoodAddModal #dynamicQtyMeasurementContainer').empty();

                        selectedQtyUnits.forEach(({ qty, unit, checked }, index) => {
                            const row = `
                                <div class="row mb-2 qty-unit-row align-items-center">
                                    <div class="col-auto mt-4">
                                        <input type="checkbox" name="selectedQty[]" class="form-check-input qtyCheckboxSelector" ${checked ? 'checked' : ''}>
                                    </div>
                                    <div class="col-5">
                                        ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                                        <input type="text" class="form-control modalQtyInput" value="${qty}">
                                    </div>
                                    <div class="col-5">
                                        ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                                        <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                                    </div>
                                </div>
                            `;
                            $container.append(row);
                        });

                        setupDynamicMeasurementSync('#mealFoodAddModal');

                        setTimeout(() => {
                            setupNutritionSync(foodItem.carbs, foodItem.protein, foodItem.fat, '#mealFoodAddModal');
                        }, 200);
                    }
                },
                error: function () {
                    console.error('Error fetching item details.');
                }
            });

            fetchMeals(); // Call this if it's defined in your code
        });

        $('#saveMealFood').on('click', function () {
            const modal = $('#mealFoodAddModal');
            
            const anyChecked = $('#mealFoodAddModal .qty-unit-row').find('.qtyCheckboxSelector:checked').length > 0;
            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }
            // Collect base info
            const foodId = modal.find('#foodId').val();
            const foodName = modal.find('#foodName').val();
            const meals = modal.find('#meals').val();
            if (!meals || meals.length === 0) {
                alert('Please select meal.');
                return;
            }
            const carbs = modal.find('#foodCarbs').val();
            const protein = modal.find('#foodProtein').val();
            const fat = modal.find('#foodFat').val();

            const selectedQtyUnits = [];
            const checkedQtyUnits = [];
            let qty = 0;
            let unit = '';

            $('#mealFoodAddModal #dynamicQtyMeasurementContainer .qty-unit-row').each(function () {
                const isChecked = $(this).find('.qtyCheckboxSelector').is(':checked');
                const $row = $(this);
                const rawQtyInput = $(this).find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                unit = $(this).find('.modalMeasurementInput').val().trim();

                if (!isNaN(parsedQty) && unit) {
                    let qtyToUse = rawQtyInput;
                    if (parsedQty % 1 === 0) {
                        qtyToUse = parsedQty.toString();
                    } 

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });
                    if (isChecked) {
                        checkedQtyUnits.push(`${qtyToUse}${["g", "ml"].includes(unit.toLowerCase()) ? unit : ' ' + unit}`);
                        qty = qtyToUse;
                    }
                }
            });
          
            $.ajax({
                url: '{{ route("admin.add-food") }}',
                type: 'POST',
                data: {
                    item_id: foodId,
                    meal_ids: meals,
                    user_id: userId,
                    carbs: carbs,
                    fat: fat,
                    protein: protein,
                    selected_qty_unit:selectedQtyUnits,
                    qty: qty,
                    unit: unit,
                    checked_qty_unit: checkedQtyUnits,
                    type: 'meal-food-add',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (!response.success) return alert(response.message);

                    const item = response.item;
                    const swapFoods = item.swap_items || [];
                    let swapItemsHTML = '';
                    const uniqueMealTimeIds = [...new Set(mealTimeIds)];

                    uniqueMealTimeIds.forEach(mealTimeId => {
                        meals.forEach(mealId => {
                                const mealContainerId = `#mealContainer_${planID}_${mealTimeId}_${mealId}`;
                                if (!$(mealContainerId).length) return;

                                const tableBody = $(mealContainerId).find('.items-table-body');

                                if (swapFoods.length > 0) {
                                    swapItemsHTML = swapFoods.map(swapItem => `
                                        <li class="list-unstyled" data-swap-item-id="${swapItem.id}">
                                            <div class="d-flex justify-content-between align-items-start mb-0"> 
                                                <div class="col-9">
                                                    <div class="d-flex align-items-start">
                                                        <input type="checkbox" name="swap_items[${planID}][${mealTimeId}][${mealId}][${item.id}][]"
                                                            value="${swapItem.id}" class="form-check-input me-2 d-none" checked
                                                            data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}">
                                                        <label class="form-check-label">${swapItem.title}</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-2"
                                                        data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swap-qty="${swapItem.qty}" data-swap-unit="${swapItem.unit}"
                                                        data-selected-qty-unit='${JSON.stringify(swapItem.selected_qty_unit)}'
                                                        title="Edit"><i class="icofont-edit text-success"></i></button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                                        data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Delete">
                                                        <i class="icofont-ui-delete text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <p class="px-2 mb-2 fw-bold">(${swapItem.qty}${swapItem.unit})</p>
                                                    <p class="mb-0 px-2">Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                                </div>
                                            </div>
                                        </li>
                                    `).join('');

                                    swapFoods.map(swapItem =>
                                        updateFoodCount(swapItem.id, 1)
                                    );
                                    // Add the final "+ Add" button row
                                    // swapItemsHTML += `
                                    //     <li class="d-flex justify-content-between align-items-start mb-2">
                                    //         <div class="col-9"><span class="text-muted">Add another swap item</span></div>
                                    //         <div>
                                    //             <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                    //                 data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                    //                 data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Add">
                                    //                 <i class="icofont-plus text-primary"></i>
                                    //             </button>
                                    //         </div>
                                    //     </li>
                                    // `;
                                } else {
                                    swapItemsHTML = `
                                        <li class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="col-9"><span class="text-muted">No swap items available</span></div>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                                    data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                    data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Add">
                                                    <i class="icofont-plus text-primary"></i>
                                                </button>
                                            </div>
                                        </li>`;
                                }

                                const rowHTML = `
                                    <tr id="itemRow_${planID}_${mealTimeId}_${mealId}_${item.id}">
                                        <td class="text-wrap" width="50%">
                                            <div class="d-flex justify-content-between align-items-start mb-0">
                                                <div class="col-9">
                                                    <div class="d-flex align-items-start">
                                                        <input type="checkbox" name="items[${planID}][${mealTimeId}][${mealId}][]"
                                                            value="${item.id}" class="form-check-input me-2 d-none"
                                                            data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" checked>
                                                        <label class="form-check-label flex-grow-1">${item.title}</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${qty}" data-item-unit="${unit}"
                                                        data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                                        title="Edit"><i class="icofont-edit text-success"></i></button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}"
                                                        title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <p class="px-2 mb-2 fw-bold">(${checkedQtyUnits.join(' or ')})</p>
                                                    <p class="mb-0 px-2">Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="50%">
                                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                                        </td>
                                    </tr>
                                `;

                                tableBody.append(rowHTML);
                            });
                        });
                    updateFoodCount(foodId, 1)
                    $('#mealFoodAddModal').modal('hide');
                }
            });

        });

        $('#closeMealFoodAddModal').on('click', function() {
            $('#mealFoodAddModal').modal('hide');
            $('.food-checkbox').prop('checked', false);
            $('#mealFoodAddModal').find('input').val('');
            $('#mealFoodAddModal').find('select').val('').trigger('change');
        });

        $('#mealFoodAddModal').on('hide.bs.modal', function() {
            $('.food-checkbox').prop('checked', false);
            $('#mealFoodAddModal').find('input').val('');
            $('#mealFoodAddModal').find('select').val('').trigger('change');
        });
        
        $('#addMoreFoodModal').on('hide.bs.modal', function() {
            $('#addMoreFoodModal').find('input').val('');
            $('#addMoreFoodModal').find('select').val('').trigger('change');
            $(this).find('#dynamicQtyMeasurementContainer').empty(); // ✅ Clears dynamic content
        });

        $('.meal-select').select2({
            width: '100%',
            placeholder: "Search for meals...",
            allowClear: true,
            ajax: {
                url: '{{ route("admin.meals.index") }}', // API route to get meals dynamically
                dataType: 'json',
                delay: 250, // Delay for better search performance
                data: function(params) {
                    return {
                        search: params.term, // Send search keyword
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.meals.map(meal => ({
                            id: meal.id,
                            text: meal.title
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
                success: function(response) {
                    if (response.success) {
                        let mealsSelect = $('#meals');
                        mealsSelect.empty();
                        response.meals.forEach(meal => {
                            mealsSelect.append(new Option(meal.title, meal.id, false, false));
                        });
                    }
                },
                error: function() {
                    alert('Error loading meals.');
                }
            });
        }

        function updateFoodCount(foodId, change) {
            let countLabel = $(`#setp5Food${foodId}`).siblings('.form-check-label');
            let countText = countLabel.text();

            // Extract current count from label text (if any)
            let match = countText.match(/\((\d+)\)$/);
            let currentCount = match ? parseInt(match[1]) : 0;

            // Calculate new count (ensure it never goes below zero)
            let newCount = Math.max(0, currentCount + change);

            // Update the label with new count
            if (newCount > 0) {
                countLabel
                    .text(countText.replace(/\(\d+\)$/, '') + ` (${newCount})`)
                    .addClass('text-primary') // Add primary color
            } else {
                countLabel
                    .text(countText.replace(/\s*\(\d+\)$/, '')) // Remove count if zero
                    .removeClass('text-primary') // Remove primary color when count is zero
            }
        }

        function setupNutritionSync(baseCarbs, baseProtein, baseFat, modal) {
            const AU_UNIT_EQUIVALENTS = {
                'cup': 250,
                'tablespoon': 20,
                'teaspoon': 5,
                'dessert spoon': 10,
                'piece': 150,
                'slice': 30,
                'roll': 70,
                'tub': 180,
                'pouch': 100,
                'handful': 40,
                'ml': 1,
                'g': 1
            };

            const $container = $(`${modal} #dynamicQtyMeasurementContainer`);
            const $rows = $container.find('.qty-unit-row');
            if ($rows.length === 0) return;

            const $baseRow = $rows.first();
            const baseQty = parseFraction($baseRow.find('.modalQtyInput').val());
            const baseUnit = $baseRow.find('.modalMeasurementInput').val().trim().toLowerCase();

            if (!baseQty || !baseUnit) {
                console.warn('Base quantity or unit is missing.');
                return;
            }

            function updateNutrition(currentQtyRaw, currentUnit) {
                const currentQty = parseFraction(currentQtyRaw);
                if (!currentQty || !currentUnit) return;

                currentUnit = currentUnit.toLowerCase();
                let baseEquivalent = AU_UNIT_EQUIVALENTS[baseUnit];
                let currentEquivalent = AU_UNIT_EQUIVALENTS[currentUnit];

                if (!baseEquivalent || !currentEquivalent) {
                    console.warn('Unknown unit used in conversion.');
                    return;
                }

                const baseGrams = baseQty * baseEquivalent;
                const currentGrams = currentQty * currentEquivalent;

                const multiplier = currentGrams / baseGrams;

                $(`${modal} #modalCarbs`).text((Math.round(baseCarbs * multiplier * 10) / 10) + 'g');
                $(`${modal} #modalProtein`).text((Math.round(baseProtein * multiplier * 10) / 10) + 'g');
                $(`${modal} #modalFat`).text((Math.round(baseFat * multiplier * 10) / 10) + 'g');
            }

            $rows.find('.modalQtyInput').on('input', function () {
                const $row = $(this).closest('.qty-unit-row');
                const newQtyRaw = $(this).val();
                const newUnit = $row.find('.modalMeasurementInput').val().trim().toLowerCase();

                updateNutrition(newQtyRaw, newUnit);
            });

            // Utility: Convert fractions like "1/2" or "3/4" to decimal numbers
            function parseFraction(input) {
                if (!input) return null;
                input = input.trim();
                // Direct number
                if (!isNaN(input)) return parseFloat(input);

                // Handle fractions like "1/2", "3/4", or even "1 1/2"
                const parts = input.split(' ');
                let result = 0;

                parts.forEach(part => {
                    if (part.includes('/')) {
                        const [num, denom] = part.split('/');
                        if (!isNaN(num) && !isNaN(denom)) {
                            result += parseFloat(num) / parseFloat(denom);
                        }
                    } else if (!isNaN(part)) {
                        result += parseFloat(part);
                    }
                });

                return result || null;
            }
        }

        function setupDynamicMeasurementSync(modal) {
            const $container = $(`${modal} #dynamicQtyMeasurementContainer`);
            const $rows = $container.find('.qty-unit-row');
            if ($rows.length < 2) return;

            let unitMap = {};

            $rows.each(function () {
                const qty = parseFraction($(this).find('.modalQtyInput').val());
                const unit = $(this).find('.modalMeasurementInput').val().toLowerCase().trim();
                if (!isNaN(qty) && unit) {
                    unitMap[unit] = qty;
                }
            });

            const baseUnit = Object.keys(unitMap)[0];
            const baseQty = unitMap[baseUnit];

            if (!baseQty || !baseUnit) return;

            let ratios = {};
            for (const [unit, qty] of Object.entries(unitMap)) {
                ratios[unit] = qty / baseQty;
            }

            $rows.each(function () {
                const $qtyInput = $(this).find('.modalQtyInput');
                const $unitInput = $(this).find('.modalMeasurementInput');

                $qtyInput.on('input', function () {
                    const changedQty = parseFraction($(this).val());
                    const changedUnit = $unitInput.val().toLowerCase().trim();

                    if (isNaN(changedQty) || !ratios[changedUnit]) return;

                    const updatedBaseQty = changedQty / ratios[changedUnit];

                    $rows.each(function () {
                        const $otherQtyInput = $(this).find('.modalQtyInput');
                        const $otherUnitInput = $(this).find('.modalMeasurementInput');

                        const unit = $otherUnitInput.val().toLowerCase().trim();
                        if (unit !== changedUnit && ratios[unit]) {
                            const newQty = updatedBaseQty * ratios[unit];
                            $otherQtyInput.val(newQty.toFixed(1));
                        }
                    });
                });
            });
        }

        // ✅ Helper to parse fractions like "1/2", "1 1/4", etc.
        function parseFraction(input) {
            if (!input) return NaN;
            input = input.trim();
            if (!isNaN(input)) return parseFloat(input); // e.g., "1.5", "2"
            if (/^\d+\s+\d+\/\d+$/.test(input)) {
                const [whole, frac] = input.split(' ');
                const [num, denom] = frac.split('/');
                return parseInt(whole) + (parseFloat(num) / parseFloat(denom));
            } else if (/^\d+\/\d+$/.test(input)) {
                const [num, denom] = input.split('/');
                return parseFloat(num) / parseFloat(denom);
            }
            return NaN;
        }

        let currentItemRow = null;

        $(document).on('click', '.edit-item', function () {
            const btn = $(this);
            const itemId = btn.data('item-id');
            const mealId = btn.data('meal-id');
            const planId = btn.data('plan-id');
            const mealTimeId = btn.data('meal-time-id');
            const name = btn.closest('td').find('label').text().split('(')[0].trim();

            const fallbackQty = btn.data('item-qty');
            const fallbackUnit = btn.data('item-unit');

            const checkbox = btn.closest('td').find('input[type="checkbox"]');
            const baseCarbs = parseFloat(checkbox.data('carbs')) || 0;
            const baseProtein = parseFloat(checkbox.data('protein')) || 0;
            const baseFat = parseFloat(checkbox.data('fat')) || 0;

            currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);

            $('#editItemId').val(itemId);
            $('#editMealId').val(mealId);
            $('#editPlanId').val(planId);
            $('#editMealTimeId').val(mealTimeId);
            $('#editItemName').val(name);

            let selectedQtyUnits = [];
            let rawJson = btn.attr('data-selected-qty-unit');
            try {
                if (rawJson && rawJson !== "null") {
                    selectedQtyUnits = JSON.parse(rawJson);
                }
            } catch (e) {
                console.warn('Invalid JSON:', e);
            }

            if (!Array.isArray(selectedQtyUnits) || selectedQtyUnits.length === 0) {
                selectedQtyUnits = [{ qty: fallbackQty, unit: fallbackUnit, checked: false }];
            }

            const $container = $('#dynamicQtyMeasurementContainer').empty();

            selectedQtyUnits.forEach(({ qty, unit, checked }, index) => {
                const row = `
                    <div class="row mb-2 qty-unit-row align-items-end">
                        <div class="col-1 text-center mb-3">
                            <input type="checkbox" class="form-check-input qtyUnitSelector" ${checked ? 'checked' : '' }>
                        </div>
                        <div class="col-5">
                            ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                            <input type="text" class="form-control modalQtyInput" value="${qty}">
                        </div>
                        <div class="col-5">
                            ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                            <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                        </div>
                    </div>
                `;
                $container.append(row);
            });

            $('#modalCarbs').text((Math.round(baseCarbs * 10) / 10) + 'g');
            $('#modalProtein').text((Math.round(baseProtein * 10) / 10) + 'g');
            $('#modalFat').text((Math.round(baseFat * 10) / 10) + 'g');

            const modal = new bootstrap.Modal(document.getElementById('editItemModal'));
            modal.show();

            setupDynamicMeasurementSync('#editItemModal');
            setTimeout(() => {
                setupNutritionSync(baseCarbs, baseProtein, baseFat, '#editItemModal');
            }, 200);
        });

        $('#saveItemChanges').on('click', function () {
            const itemId = $('#editItemId').val();
            const mealId = $('#editMealId').val();
            const planId = $('#editPlanId').val();
            const mealTimeId = $('#editMealTimeId').val();
            const name = $('#editItemName').val();

            const selectedQtyUnits = [];
            const checkedQtyUnits = [];
            const qtyUnitDisplay = [];
            let qty = 0;
            let unit = '';

            const anyChecked = $('#dynamicQtyMeasurementContainer .qty-unit-row').find('.qtyUnitSelector:checked').length > 0;
            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }

            $('#dynamicQtyMeasurementContainer .qty-unit-row').each(function () {
                const $row = $(this);
                const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                unit = $row.find('.modalMeasurementInput').val().trim();
                const isChecked = $row.find('.qtyUnitSelector').is(':checked');

                if (!isNaN(parsedQty) && unit) {
                    // Check if parsedQty has a decimal part of .0, then show it as an integer
                    let qtyToUse = rawQtyInput;

                    if (parsedQty % 1 === 0) { // Check if it's a whole number (i.e., no decimal part)
                        qtyToUse = parsedQty.toString(); // Convert to string without decimal part
                    } 

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });
                    if (isChecked) {
                        qtyUnitDisplay.push(`${qtyToUse}${["g", "ml"].includes(unit.toLowerCase()) ? unit : ' ' + unit}`);

                        qty = qtyToUse;
                    }
                }
            });

            const carbs = parseFloat($('#modalCarbs').text()) || 0;
            const protein = parseFloat($('#modalProtein').text()) || 0;
            const fat = parseFloat($('#modalFat').text()) || 0;

            const updatedHTML = `
                <div class="d-flex justify-content-between align-items-start mb-0">
                    <div class="col-9">
                        <div class="d-flex align-items-start">
                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                value="${itemId}" class="form-check-input me-2 d-none" checked
                                data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}">
                            <label class="form-check-label flex-grow-1">${name}</label>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-success edit-item"
                            data-item-id="${itemId}" data-meal-id="${mealId}" data-plan-id="${planId}"
                            data-meal-time-id="${mealTimeId}" data-user-id=""
                            data-item-qty="" data-item-unit=""
                            data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                            title="Edit"><i class="icofont-edit text-success"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                            data-item-id="${itemId}" data-meal-id="${mealId}" data-plan-id="${planId}"
                            data-meal-time-id="${mealTimeId}" data-user-id="" title="Delete">
                            <i class="icofont-ui-delete text-danger"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="px-2 mb-2 fw-bold">(${qtyUnitDisplay.join(' or ')})</p>
                        <p class="mb-0 px-2">Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                    </div>
                </div>
            `;

            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            currentItemRow.find('td:first').html(updatedHTML);

            const modalEl = document.getElementById('editItemModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            calculateTotals(planId, mealTimeId, mealId);
            calculateMealNutrition();

            $.ajax({
                url: '{{ route("admin.update-food-swap-foods") }}',
                method: 'POST',
                data: {
                    item_id: itemId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    item_qty: qty,
                    item_unit: unit,
                    food_carbs: carbs,
                    food_protein: protein,
                    food_fat: fat,
                    selected_qty_unit: selectedQtyUnits,
                    checked_qty_unit: checkedQtyUnits,
                    type: 'item-update',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Food updated successfully');
                    } else {
                        alert('Failed to update food.');
                    }
                },
                error: function () {
                    alert('An error occurred while updating food.');
                }
            });
        });

        // Delete Item Button Action
        $(document).on('click', '.delete-item', function() {
            const itemId = $(this).data('item-id');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('meal-time-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');
            let swap_food_id = $(this).data('swapfood-id');

            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            const allSwapLis = currentItemRow.find('li');
            
            // Your delete logic for the item (e.g., show confirmation and delete item)
            if (confirm('Are you sure you want to delete this item and swap items?')) {
                console.log('Delete Item:', itemId, mealId, mealTimeId, planId, userId);
                const $row = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
                const $tableBody = $row.closest('tbody'); // Get the parent tbody

                // Count remaining <tr> elements in the table
                const remainingRows = $tableBody.find('tr').length;

                // Uncheck checkboxes in the first and second <td>
                $row.find('input[type="checkbox"]').prop('checked', false);

                // Remove the row
                $row.remove();

                // If there are no more rows left, remove the meal from the meal section dropdown
                if (remainingRows === 1) { // Since we're removing a row, we check before it's removed
                    $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
                    const dropdown = `#addMealDropdown${planId}_${mealTimeId}`;
                    const $select = $(dropdown).find('select'); // Find the select inside the div

                    // Correcting the dropdown selection and removing the meal option
                    $select.find('option[value="' + mealId + '"]').remove();
                    $select.trigger('change');
                    // initializeSelect2($select, mealTimeId);
                }

                updateFoodCount(itemId, -1);

                allSwapLis.each(function () {
                    const swapItemId = $(this).data('swap-item-id'); // OR use attr('data-swap-item-id')
                    console.log('Swap Item ID:', swapItemId);
                    updateFoodCount(swapItemId, -1);
                });
                
                calculateTotals(planId, mealTimeId, mealId);
                calculateMealNutrition();
            }
        });

        $(document).on('click', '.edit-swap-item', function () {
            const btn = $(this);
            const modalId = '#editSwapItemModal';

            const swapItemId = btn.data('swap-item-id');
            const itemId = btn.data('item-id');
            const mealId = btn.data('meal-id');
            const planId = btn.data('plan-id');
            const mealTimeId = btn.data('meal-time-id');
            const swapQty = btn.data('swap-qty');
            const swapUnit = btn.data('swap-unit');

            const checkbox = btn.closest('li').find('input[type="checkbox"]');
            const baseCarbs = parseFloat(checkbox.data('carbs')) || 0;
            const baseProtein = parseFloat(checkbox.data('protein')) || 0;
            const baseFat = parseFloat(checkbox.data('fat')) || 0;

            const name = btn.closest('tr').find('td').first().find('label').text().split('(')[0].trim();
            $(`${modalId} #editItemName`).val(name);

            $('#editMainItemId').val(itemId);
            $('#editSwapItemId').val(swapItemId);
            $('#editSwapPlanId').val(planId);
            $('#editSwapMealTimeId').val(mealTimeId);
            $('#editSwapMealId').val(mealId);
            $('#previousSwapItemId').val(swapItemId);

            $(`${modalId} #modalCarbs`).text((Math.round(baseCarbs * 10) / 10) + 'g');
            $(`${modalId} #modalProtein`).text((Math.round(baseProtein * 10) / 10) + 'g');
            $(`${modalId} #modalFat`).text((Math.round(baseFat * 10) / 10) + 'g');

            const $dropdown = $('#swapFoodDropdown');
            const optionExists = $dropdown.find(`option[value="${swapItemId}"]`).length > 0;

            let selectedQtyUnits = [];
            try {
                const rawJson = btn.attr('data-selected-qty-unit');
                if (rawJson && rawJson !== "null") {
                    selectedQtyUnits = JSON.parse(rawJson);
                }
            } catch (e) {
                console.warn('Invalid selected_qty_unit JSON:', e);
            }

            if (!Array.isArray(selectedQtyUnits) || selectedQtyUnits.length === 0) {
                selectedQtyUnits = [{ qty: swapQty, unit: swapUnit, checked: false }];
            }

            const buildQtyUnitRows = (units) => {
                const $container = $(`${modalId} #dynamicQtyMeasurementContainer`).empty();
                units.forEach(({ qty, unit, checked }, index) => {
                    const row = `
                        <div class="row mb-2 qty-unit-row align-items-center">
                            <div class="col-auto mt-4">
                                <input type="checkbox" class="form-check-input qtyUnitSelector" ${checked ? 'checked' : '' }>
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                                <input type="text" class="form-control modalQtyInput" value="${qty}">
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                                <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                            </div>
                        </div>
                    `;
                    $container.append(row);
                });
            };

            if (!optionExists) {
                $.ajax({
                    url: '{{ route("admin.items.index") }}',
                    data: { query: '' },
                    success: function (response) {
                        const selected = response.items.find(item => item.id == swapItemId);
                        if (!selected) return;

                        const imageUrl = selected.image ? `{{ asset('private/public/storage/') }}/${selected.image}` : '';

                        const option = new Option(selected.title, selected.id, true, true);
                        $(option)
                            .attr('data-image', imageUrl)
                            .data('data', {
                                id: selected.id,
                                text: selected.title,
                                image: imageUrl,
                                carbs: selected.carbs,
                                protein: selected.protein,
                                fat: selected.fat,
                                qty: selected.qty,
                                unit: selected.unit,
                                selected_qty_unit: selected.selected_qty_unit
                            });

                        $dropdown.append(option).trigger('change');

                        buildQtyUnitRows(selectedQtyUnits);

                        setupNutritionSync(
                            parseFloat(selected.carbs) || 0,
                            parseFloat(selected.protein) || 0,
                            parseFloat(selected.fat) || 0,
                            modalId
                        );
                        setupDynamicMeasurementSync(modalId);
                        // $(`${modalId} .qty-unit-row`).first().find('.modalQtyInput').trigger('input');
                    }
                });
            } else {
                $dropdown.val(swapItemId).trigger('change');
                buildQtyUnitRows(selectedQtyUnits);
                setupNutritionSync(baseCarbs, baseProtein, baseFat, modalId);
                setupDynamicMeasurementSync(modalId);
                // $(`${modalId} .qty-unit-row`).first().find('.modalQtyInput').trigger('input');
            }

            const modal = new bootstrap.Modal(document.getElementById('editSwapItemModal'));
            modal.show();
        });

        $('#saveSwapItemChanges').on('click', function () {
            const modalSelector = '#editSwapItemModal';
            const anyChecked = $(`${modalSelector} .qty-unit-row`).find('.qtyUnitSelector:checked').length > 0;

            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }

            const itemId = $('#editMainItemId').val();
            const previousSwapItemId = $('#previousSwapItemId').val();
            const swapItemId = $('#editSwapItemId').val();
            const mealId = $('#editSwapMealId').val();
            const planId = $('#editSwapPlanId').val();
            const mealTimeId = $('#editSwapMealTimeId').val();

            const $dropdown = $('#swapFoodDropdown');
            const name = $dropdown.find('option:selected').text();

            const selectedQtyUnits = [];
            const checkedQtyUnits = [];

            let qty = 0;
            let unit = "";

            $('#editSwapItemModal .qty-unit-row').each(function () {
                const $row = $(this);
                const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                unit = $row.find('.modalMeasurementInput').val().trim();
                const isChecked = $row.find('.qtyUnitSelector').is(':checked');

                if (!isNaN(parsedQty) && unit) {
                    // Check if parsedQty has a decimal part of .0, then show it as an integer
                    let qtyToUse = rawQtyInput;

                    if (parsedQty % 1 === 0) { // Check if it's a whole number (i.e., no decimal part)
                        qtyToUse = parsedQty.toString(); // Convert to string without decimal part
                    } 

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        checkedQtyUnits.push(`${qtyToUse}${["g", "ml"].includes(unit.toLowerCase()) ? unit : ' ' + unit}`);
                        // Set actual qty to be submitted
                        qty = qtyToUse;
                    }
                }
            });
            // console.log(qty);
            const carbs = parseFloat($('#editSwapItemModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#editSwapItemModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#editSwapItemModal #modalFat').text()) || 0;

            const updatedLI = `
                <li class="list-unstyled" data-swap-item-id="${swapItemId}">
                    <div class="d-flex justify-content-between align-items-start mb-0">
                        <div class="col-9">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]"
                                    value="${swapItemId}" class="form-check-input me-2 d-none" checked
                                    data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}">
                                <label class="form-check-label">${name}</label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-2"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}"
                                data-swap-qty="${qty}" data-swap-unit="${unit}"
                                data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                title="Edit"><i class="icofont-edit text-success"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}" title="Delete">
                                <i class="icofont-ui-delete text-danger"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="px-2 mb-2 fw-bold">(${checkedQtyUnits.join(' or ')})</p>
                            <p class="mb-0 px-2">Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                        </div>
                    </div>
                </li>
            `;

            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            const liToReplace = currentItemRow.find(`td:nth-child(2) li[data-swap-item-id="${previousSwapItemId}"]`);
            liToReplace.replaceWith(updatedLI);

            if (previousSwapItemId !== swapItemId) {
                updateFoodCount(previousSwapItemId, -1);
                updateFoodCount(swapItemId, 1);
            }

            const modalEl = document.getElementById('editSwapItemModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            $.ajax({
                url: '{{ route("admin.update-food-swap-foods") }}',
                method: 'POST',
                data: {
                    item_id: itemId,
                    swap_item_id: swapItemId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    swap_item_qty: qty,
                    swap_item_unit: unit,
                    swap_food_carbs: carbs,
                    swap_food_protein: protein,
                    swap_food_fat: fat,
                    swap_selected_qty_unit: selectedQtyUnits,
                    checked_qty_unit: checkedQtyUnits,
                    type: 'swap-food-update',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Swap food updated successfully');
                        modal.hide();
                    } else {
                        alert('Failed to update swap items.');
                        modal.hide();
                    }
                },
                error: function () {
                    alert('An error occurred while updating swap items.');
                    modal.hide();
                }
            });
        });

        $(document).on('click', '.delete-swap-item', function () {
            const button = $(this);

            const swapItemId = button.data('swap-item-id');
            const itemId = button.data('item-id');
            const mealId = button.data('meal-id');
            const planId = button.data('plan-id');
            const mealTimeId = button.data('meal-time-id');
            const userId = button.data('user-id');

            if (!confirm('Are you sure you want to delete this swap item?')) return;

            const $li = button.closest('li');
            const $ul = $li.closest('ul');

            // Remove the swap item
            $li.remove();
            updateFoodCount(swapItemId, -1);

            // Check if there are any <li> left in the list
            if ($ul.children('li').length === 0) {
                // Add "No swap items available" message
                $ul.append(`
                    <li class="d-flex justify-content-between align-items-start mb-2">
                        <div class="col-9">
                            <span class="text-muted">No swap items available</span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                data-item-id="${itemId}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                title="Add"><i class="icofont-plus text-primary"></i>
                            </button>
                        </div>
                    </li>
                `);
            }
        });

        $(document).ready(function () {
            $('#swapFoodDropdown').select2({
                placeholder: "Search for swap foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                dropdownParent: $('#editSwapItemModal'),
                ajax: {
                    url: '{{ route("admin.items.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { query: params.term };
                    },
                    processResults: function (response) {
                        return {
                            results: response.items.map(item => ({
                                id: item.id,
                                text: item.title,
                                image: item.image ? `{{ asset('private/public/storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                qty: item.qty,
                                unit: item.unit,
                                selected_qty_unit: item.selected_qty_unit
                            }))
                        };
                    },
                    cache: true
                },
                templateResult: formatFood,
                templateSelection: formatFoodSelection
            });

            function formatFood(food) {
                if (!food.id) return food.text;
                return $(`
                    <div style="display: flex; align-items: center;">
                        <img src="${food.image}" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            function formatFoodSelection(food) {
                if (!food.id) return food.text;
                return $(`
                    <div style="display: flex; align-items: center;">
                        <img src="${food.image}" style="width: 25px; height: 25px; margin-right: 5px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            $('#swapFoodDropdown').on('select2:select', function (e) {
                const data = e.params.data;
                let selectedQtyUnits = [];

                try {
                    if (data.selected_qty_unit && data.selected_qty_unit !== "null") {
                        if (typeof data.selected_qty_unit === 'string') {
                            selectedQtyUnits = JSON.parse(data.selected_qty_unit);
                        } else {
                            selectedQtyUnits = data.selected_qty_unit; // Already an object/array
                        }
                    }
                } catch (e) {
                    console.warn('Invalid JSON in selected_qty_unit:', e);
                }

                if (!Array.isArray(selectedQtyUnits) || selectedQtyUnits.length === 0) {
                    selectedQtyUnits = [{ qty: data.qty, unit: data.unit, checked: false }];
                }

                const $container = $('#editSwapItemModal #dynamicQtyMeasurementContainer').empty();

                selectedQtyUnits.forEach(({ qty, unit, checked }, index) => {
                    const row = `
                       <div class="row mb-2 qty-unit-row align-items-center">
                            <div class="col-auto mt-4">
                                <input type="radio" name="primaryQty" class="form-check-input primaryQtySelector" ${checked ? 'checked' : ''}>
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                                <input type="text" class="form-control modalQtyInput" value="${qty}" data-base-qty="${qty}">
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                                <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                            </div>
                        </div>

                    `;
                    $container.append(row);
                });

                $('#editSwapItemModal #modalCarbs').text((parseFloat(data.carbs) || 0).toFixed(1) + 'g');
                $('#editSwapItemModal #modalProtein').text((parseFloat(data.protein) || 0).toFixed(1) + 'g');
                $('#editSwapItemModal #modalFat').text((parseFloat(data.fat) || 0).toFixed(1) + 'g');
                
                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, '#editSwapItemModal');
                setupDynamicMeasurementSync('#editSwapItemModal');
            });

        });

        $(document).on('click', '.add-swap-item', function () {
            const btn = $(this);
            const itemId = $(this).data('item-id');
            const mealId = $(this).data('meal-id');
            const planId = $(this).data('plan-id');
            const mealTimeId = $(this).data('meal-time-id');
            const modalId = '#addSwapItemModal';

            // Set item name
            const name = btn.closest('tr').find('td').first().find('label').text().split('(')[0].trim();
            $(`${modalId} #itemName`).val(name);

            $(`${modalId} .modal-title`).text('Add Swap Food');

            // Set required hidden fields
            $('#addSwapItemModal #refItemId').val(itemId);
            $('#addSwapItemModal #swapPlanId').val(planId);
            $('#addSwapItemModal #swapMealTimeId').val(mealTimeId);
            $('#addSwapItemModal #swapMealId').val(mealId);

            // Reset swap item ID since this is a new add
            $('#addSwapItemModal #swapItemId').val('');
            // $('#addSwapItemModal #previousSwapItemId').val('');

            // Clear old values
            $('#addSwapItemModal #itemName').val(name);
            $('#addSwapItemModal #modalCarbs').text('0g');
            $('#addSwapItemModal #modalProtein').text('0g');
            $('#addSwapItemModal #modalFat').text('0g');
            $('#addSwapItemModal #dynamicQtyMeasurementContainer').empty();

            // Optionally reset the dropdown to default (you control how it fills)
            $('#swapItemDropdown').val('').trigger('change');

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('addSwapItemModal'));
            modal.show();
            setupDynamicMeasurementSync('#addSwapItemModal');

        });

        $(document).ready(function () {
            $('#swapItemDropdown').select2({
                placeholder: "Search for swap foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                dropdownParent: $('#addSwapItemModal'),
                ajax: {
                    url: '{{ route("admin.items.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { query: params.term };
                    },
                    processResults: function (response) {
                        return {
                            results: response.items.map(item => ({
                                id: item.id,
                                text: item.title,
                                image: item.image ? `{{ asset('private/public/storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                qty: item.qty,
                                unit: item.unit,
                                selected_qty_unit: item.selected_qty_unit
                            }))
                        };
                    },
                    cache: true
                },
                templateResult: formatFood,
                templateSelection: formatFoodSelection
            });

            function formatFood(food) {
                if (!food.id) return food.text;
                return $(`
                    <div style="display: flex; align-items: center;">
                        <img src="${food.image}" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            function formatFoodSelection(food) {
                if (!food.id) return food.text;
                return $(`
                    <div style="display: flex; align-items: center;">
                        <img src="${food.image}" style="width: 25px; height: 25px; margin-right: 5px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            $('#swapItemDropdown').on('select2:select', function (e) {
                const data = e.params.data;
                let selectedQtyUnits = [];
                try {
                    // Only parse if it's a string
                    if (typeof data.selected_qty_unit === 'string') {
                        selectedQtyUnits = JSON.parse(data.selected_qty_unit);
                    } else {
                        selectedQtyUnits = data.selected_qty_unit; // Already an object/array
                    }
                } catch (e) {
                    console.warn('Invalid JSON in selected_qty_unit:', e);
                }

                if (!Array.isArray(selectedQtyUnits) || selectedQtyUnits.length === 0) {
                    selectedQtyUnits = [{ qty: data.qty, unit: data.unit, checked: false }];
                }

                const $container = $('#addSwapItemModal #dynamicQtyMeasurementContainer').empty();

                selectedQtyUnits.forEach(({ qty, unit, checked }, index) => {
                    const row = `
                       <div class="row mb-2 qty-unit-row align-items-center">
                            <div class="col-auto mt-4">
                                <input type="checkbox" class="form-check-input multiQtyCheckbox" name="multiQty[]" data-qty="${qty}" data-unit="${unit}" ${checked ? 'checked' : ''}>
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                                <input type="text" class="form-control modalQtyInput" value="${qty}" data-base-qty="${qty}">
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                                <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                            </div>
                        </div>

                    `;
                    $container.append(row);
                });

                $('#addSwapItemModal #modalCarbs').text((parseFloat(data.carbs) || 0).toFixed(1) + 'g');
                $('#addSwapItemModal #modalProtein').text((parseFloat(data.protein) || 0).toFixed(1) + 'g');
                $('#addSwapItemModal #modalFat').text((parseFloat(data.fat) || 0).toFixed(1) + 'g');
                
                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, '#addSwapItemModal');
                setupDynamicMeasurementSync('#addSwapItemModal');
            });

        });

        $('#saveSwapItem').on('click', function () {
            const itemId = $('#refItemId').val();
            // const swapItemId = $('#swapItemId').val();
            const mealId = $('#swapMealId').val();
            const planId = $('#swapPlanId').val();
            const mealTimeId = $('#swapMealTimeId').val();
            const $dropdown = $('#swapItemDropdown');
            const name = $dropdown.find('option:selected').text();
            const swapItemId = $dropdown.find('option:selected').val();

            const anyChecked = $('#addSwapItemModal .qty-unit-row').find('.multiQtyCheckbox:checked').length > 0;
            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }

            const selectedQtyUnits = [];
            let selectedTitleParts = [];
            let checkedQtyUnits = [];
            let qty = 0;
            let unit = "";
            $('#addSwapItemModal .qty-unit-row').each(function () {
                const $row = $(this);
                const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                unit = $(this).find('.modalMeasurementInput').val().trim();
                const $checkbox = $(this).find('.multiQtyCheckbox');
                const isChecked = $checkbox.is(':checked');
                if (!isNaN(parsedQty) && unit) {
                    // Check if parsedQty has a decimal part of .0, then show it as an integer
                    let qtyToUse = rawQtyInput;

                    if (parsedQty % 1 === 0) { // Check if it's a whole number (i.e., no decimal part)
                        qtyToUse = parsedQty.toString(); // Convert to string without decimal part
                    } 

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });
                    if (isChecked) {
                        checkedQtyUnits.push(`${qtyToUse}${["g", "ml"].includes(unit.toLowerCase()) ? unit : ' ' + unit}`);
                        selectedTitleParts.push(`${qtyToUse}${["g", "ml"].includes(unit.toLowerCase()) ? unit : ' ' + unit}`);

                        qty = qtyToUse;
                    }
                }
            });

            const carbs = parseFloat($('#addSwapItemModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#addSwapItemModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#addSwapItemModal #modalFat').text()) || 0;

            const updatedLI = `
                <li class="list-unstyled" data-swap-item-id="${swapItemId}">
                    <div class="d-flex justify-content-between align-items-start mb-0">
                        <div class="col-9">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]"
                                    value="${swapItemId}" class="form-check-input me-2 d-none " checked
                                    data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}">
                                <label class="form-check-label">${name}</label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-2"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}"
                                data-swap-qty="${qty}" data-swap-unit="${unit}"
                                data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                title="Edit"><i class="icofont-edit text-success"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}" title="Delete">
                                <i class="icofont-ui-delete text-danger"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="px-2 mb-2 fw-bold">(${selectedTitleParts.join(' or ')})</p>
                            <p class="mb-0 px-2">Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                        </div>
                    </div>
                </li>
            `;

            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            // Find and replace only the current <li> using swapItemId
            const liToReplace = currentItemRow.find(`td:nth-child(2) ul`);
            liToReplace.replaceWith(updatedLI);

            updateFoodCount(swapItemId, 1);

            const modalEl = document.getElementById('addSwapItemModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            $.ajax({
                url: '{{ route("admin.update-food-swap-foods") }}',
                method: 'POST',
                data: {
                    item_id: itemId,
                    swap_item_id: swapItemId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    swap_item_qty: qty,
                    swap_item_unit: unit,
                    swap_food_carbs: carbs,
                    swap_food_protein: protein,
                    swap_food_fat: fat,
                    swap_selected_qty_unit: selectedQtyUnits,
                    type: 'swap-food-update',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log('swap food added successfully');
                        modal.hide();
                    } else {
                        alert('Failed to update swap items.');
                        modal.hide();
                    }
                },
                error: function () {
                    alert('An error occurred while updating swap items.');
                    modal.hide();
                }
            });
            
            // calculateTotals(planId, mealTimeId, mealId);
            // calculateMealNutrition();
        });

        $('#editSwapItemModal').on('hidden.bs.modal', function () {
            // Reset inputs
            $(this).find('input').val('');
            $(this).find('#swapFoodDropdown').trigger('change');
            $(this).find('#dynamicQtyMeasurementContainer').empty();

            // Clear macros
            $(this).find('#modalCarbs').text('0.0g');
            $(this).find('#modalProtein').text('0.0g');
            $(this).find('#modalFat').text('0.0g');
        });

        $(document).ready(function () {
            $('#foodDropdown').select2({
                placeholder: "Search for foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                dropdownParent: $('#addMoreFoodModal'),
                ajax: {
                    url: '{{ route("admin.items.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { query: params.term };
                    },
                    processResults: function (response) {
                        return {
                            results: response.items.map(item => ({
                                id: item.id,
                                text: item.title,
                                image: item.image ? `{{ asset('private/public/storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                qty: item.qty,
                                unit: item.unit,
                                selected_qty_unit: item.selected_qty_unit
                            }))
                        };
                    },
                    cache: true
                },
                templateResult: formatFood,
                templateSelection: formatFoodSelection
            });

            function formatFood(food) {
                if (!food.id) return food.text;
                return $(`
                    <div style="display: flex; align-items: center;">
                        <img src="${food.image}" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            function formatFoodSelection(food) {
                if (!food.id) return food.text;
                return $(`
                    <div style="display: flex; align-items: center;">
                        <img src="${food.image}" style="width: 25px; height: 25px; margin-right: 5px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            $('#foodDropdown').on('select2:select', function (e) {
                const data = e.params.data;
                let selectedQtyUnits = [];

                try {
                    // Only parse if it's a string
                    if (typeof data.selected_qty_unit === 'string') {
                        selectedQtyUnits = JSON.parse(data.selected_qty_unit);
                    } else {
                        selectedQtyUnits = data.selected_qty_unit; // Already an object/array
                    }
                } catch (e) {
                    console.warn('Invalid JSON in selected_qty_unit:', e);
                }

                if (!Array.isArray(selectedQtyUnits) || selectedQtyUnits.length === 0) {
                    selectedQtyUnits = [{ qty: data.qty, unit: data.unit, checked: false }];
                }

                const $container = $('#addMoreFoodModal #dynamicQtyMeasurementContainer').empty();

                selectedQtyUnits.forEach(({ qty, unit, checked }, index) => {
                    const row = `
                        <div class="row mb-2 qty-unit-row align-items-center">
                            <div class="col-auto mt-4">
                                <input type="checkbox" class="form-check-input multiQtyCheckbox" ${checked ? 'checked' : ''}>
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                                <input type="text" class="form-control modalQtyInput" value="${qty}" data-base-qty="${qty}">
                            </div>
                            <div class="col-5">
                                ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                                <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                            </div>
                        </div>
                    `;
                    $container.append(row);
                });

                // Nutrition display (optional — can add spans inside modal if needed)
                $('#addMoreFoodModal #modalCarbs').text((parseFloat(data.carbs) || 0).toFixed(1) + 'g');
                $('#addMoreFoodModal #modalProtein').text((parseFloat(data.protein) || 0).toFixed(1) + 'g');
                $('#addMoreFoodModal #modalFat').text((parseFloat(data.fat) || 0).toFixed(1) + 'g');

                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, '#addMoreFoodModal');
                setupDynamicMeasurementSync('#addMoreFoodModal');
            });
        });

        $(document).on('click', '.add-more-food', function () {
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('meal-time-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');

            $('#addMoreFoodModal #itemId').val(itemId);
            $('#addMoreFoodModal #foodPlanId').val(planId);
            $('#addMoreFoodModal #foodMealTimeId').val(mealTimeId);
            $('#addMoreFoodModal #foodMealId').val(mealId);
            $('#addMoreFoodModal #foodUserId').val(userId);

            const $woolworthsBtn = $('#addMoreFoodModal .add-food-button');
            $woolworthsBtn.attr('data-plan-id', planId);
            $woolworthsBtn.attr('data-meal-id', mealId);
            $woolworthsBtn.attr('data-meal-time-id', mealTimeId);
            $woolworthsBtn.attr('data-user-id', userId);

            $('#foodDropdown').val('');
            $('#dynamicQtyMeasurementContainer').empty();

            // You can optionally store these in hidden inputs if needed
            $('#addMoreFoodModal').modal('show');
        });

        $(document).on('click', '#saveMoreFoodBtn', function () {
            const selectedData = $('#foodDropdown').select2('data')[0];
            if (!selectedData) return alert('Please select a food first.');

            const anyChecked = $('#addMoreFoodModal .qty-unit-row').find('.multiQtyCheckbox:checked').length > 0;
            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }
            const foodId = selectedData.id;
            const mealId = $('#addMoreFoodModal #foodMealId').val();
            const mealTimeId = $('#addMoreFoodModal #foodMealTimeId').val();
            const planId = $('#addMoreFoodModal #foodPlanId').val();
            const userId = $('#addMoreFoodModal #foodUserId').val();
            const carbs = parseFloat($('#addMoreFoodModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#addMoreFoodModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#addMoreFoodModal #modalFat').text()) || 0;

            const selectedQtyUnits = [];
            const checkedQtyUnits = [];
            let qty = 0;
            let unit = "";

            $('#addMoreFoodModal .qty-unit-row').each(function () {
                const $checkbox = $(this).find('.multiQtyCheckbox');
                const $row = $(this);
                const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                unit = $(this).find('.modalMeasurementInput').val().trim();
                const isChecked = $checkbox.is(':checked');

                if (!isNaN(parsedQty) && unit) {
                    // Check if parsedQty has a decimal part of .0, then show it as an integer
                    let qtyToUse = rawQtyInput;

                    if (parsedQty % 1 === 0) { // Check if it's a whole number (i.e., no decimal part)
                        qtyToUse = parsedQty.toString(); // Convert to string without decimal part
                    } 

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });
                    if (isChecked) {
                        checkedQtyUnits.push(`${qtyToUse}${["g", "ml"].includes(unit.toLowerCase()) ? unit : ' ' + unit}`);
                        qty = qtyToUse;
                    }
                }
            });

            const $mealContainer = $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`);
           
            const $tableBody = $mealContainer.find('.items-table-body');
            console.log($tableBody);
            $.ajax({
                url: '{{ route("admin.add-food") }}',
                type: 'POST',
                data: {
                    item_id: foodId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    carbs: carbs,
                    fat: fat,
                    protein: protein,
                    selected_qty_unit:selectedQtyUnits,
                    qty: qty,
                    unit: unit,
                    type: 'add-more-food',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (!response.success) return alert(response.message);

                    const item = response.item;
                    const swapFoods = item.swap_items || [];
                    let swapItemsHTML = '';

                    if (swapFoods.length > 0) {
                        swapItemsHTML = swapFoods.map(swapItem => `
                            <li class="list-unstyled" data-swap-item-id="${swapItem.id}">
                                <div class="d-flex justify-content-between align-items-start mb-0">
                                    <div class="col-9">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]"
                                                value="${swapItem.id}" class="form-check-input me-2 d-none" checked
                                                data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}">
                                            <label class="form-check-label">${swapItem.title}</label>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-2"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swap-qty="${swapItem.qty}" data-swap-unit="${swapItem.unit}"
                                            data-selected-qty-unit='${JSON.stringify(swapItem.selected_qty_unit)}'
                                            title="Edit"><i class="icofont-edit text-success"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Delete">
                                            <i class="icofont-ui-delete text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="px-2 mb-2 fw-bold">(${swapItem.qty}${["g", "mL"].includes(swapItem.unit) ? swapItem.unit : ' ' + swapItem.unit})</p>
                                        <p class="mb-0 px-2">Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                    </div>
                                </div>
                            </li>
                        `).join('');

                        swapFoods.map(swapItem => 
                            updateFoodCount(swapItem.id, 1)
                        );

                    } else {
                        swapItemsHTML = `
                            <li class="d-flex justify-content-between align-items-start mb-2">
                                <div class="col-9"><span class="text-muted">No swap items available</span></div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Add">
                                        <i class="icofont-plus text-primary"></i>
                                    </button>
                                </div>
                            </li>`;
                    }

                    const rowHTML = `
                        <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${item.id}">
                            <td class="text-wrap" width="50%">
                                <div class="d-flex justify-content-between align-items-start mb-0">
                                    <div class="col-9">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                                value="${item.id}" class="form-check-input me-2 d-none" checked
                                                data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}">
                                            <label class="form-check-label flex-grow-1">${item.title}</label>
                                        </div>
                                        
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                            data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${qty}" data-item-unit="${unit}"
                                            data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                            title="Edit"><i class="icofont-edit text-success"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                            data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}"
                                            title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                                    </div>
                                </div>
                                    <div class="row">
                                    <div class="col">
                                        <p class="px-2 mb-2 fw-bold">(${checkedQtyUnits.join(' or ')})</p>
                                        <p class="px-2 mb-0">Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                                    </div>
                                </div>
                            </td>
                            <td width="50%">
                                <ul class="list-unstyled">${swapItemsHTML}</ul>
                            </td>
                        </tr>
                    `;

                    updateFoodCount(item.id, 1)

                    calculateTotals(planId, mealTimeId, mealId);
                    calculateMealNutrition();

                    $tableBody.append(rowHTML);
                    $('#addMoreFoodModal').modal('hide');
                }
            });
        });

        // Function to calculate totals
        function calculateTotals(planId, mealTimeId, mealId) {
            let totalCarbs = 0,
                totalProtein = 0,
                totalFat = 0;

            const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;

            // Loop through each <tr> in the tbody
            $(`${mealContainerId} tbody tr`).each(function () {
                const $row = $(this);

                // Find checked input inside first <td> (main item)
                const $mainItemInput = $row.find('td:first input[type="checkbox"]:checked');
                if ($mainItemInput.length) {
                    totalCarbs += parseFloat($mainItemInput.data('carbs')) || 0;
                    totalProtein += parseFloat($mainItemInput.data('protein')) || 0;
                    totalFat += parseFloat($mainItemInput.data('fat')) || 0;
                }

                // Find checked inputs inside second <td> (swap items)
                // $row.find('td:eq(1) input[type="checkbox"]:checked').each(function () {
                //     const $swapInput = $(this);
                //     totalCarbs += parseFloat($swapInput.data('carbs')) || 0;
                //     totalProtein += parseFloat($swapInput.data('protein')) || 0;
                //     totalFat += parseFloat($swapInput.data('fat')) || 0;
                // });
            });

            // Update totals in the specific meal container
            $(`${mealContainerId} .totalCarbs`).text(Math.round(totalCarbs));
            $(`${mealContainerId} .totalProtein`).text(Math.round(totalProtein));
            $(`${mealContainerId} .totalFat`).text(Math.round(totalFat));
        }
    });

    $(document).on('change', '.editable-meal-name', function() {
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
            success: function(response) {
                if (response.success) {
                    console.log(response.message);
                    alert('Meal name updated successfully!');

                    // ✅ Find the correct dropdown using plan_id & meal_time_id
                    const mealDropdown = $(`#mealItems${planId}_${mealTimeId}`);

                    // ✅ Store currently selected values before modifying the dropdown
                    let selectedValues = mealDropdown.val() || [];

                    // ✅ Remove the old meal entry and add the updated one
                    mealDropdown.find(`option[value="${mealId}"]`).remove(); // Remove old meal name
                    mealDropdown.append(new Option(response.meal_name, response.meal_id, true, true)); // Add updated meal name

                    // ✅ Restore previous selections, ensuring they remain
                    selectedValues = selectedValues.filter(id => id !== mealId); // Remove old meal from selection
                    selectedValues.push(response.meal_id); // Add updated meal ID to selection
                    mealDropdown.val(selectedValues).trigger('change'); // Restore selection with updated name
                    // window.location.reload();
                } else {
                    alert('Failed to update meal name.');
                }
            },
            error: function() {
                alert('Error while updating meal name.');
            }
        });
    });

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

    $(document).ready(function() {
        const loader = $('#loader');
        const foodResultsTableBody = $('#foodResultsTableBody');
        const woolWorthsFoodResultsTableBody = $('#woolworthsFoodResultsTableBody');
        const foodSearchResults = $('#foodSearchResults'); // Wraps the results
        const woolworthsSearchResults = $('#woolworthsSearchResults'); // Wraps the results

        // Handle click on "Add Food" button
        $(document).on('click', '.add-food-button', function() {
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

            $('#addMoreFoodModal').modal('hide');

            // Open modal
            $('#searchFoodModal').modal('show');
        });

        // Handle search type selection (System Food Search or Woolworths Search)
        $(document).on('click', '#searchFoodBtn', function() {
            // Set the search type to system search and trigger the search
            $('#searchFoodType').val('system');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('mealtime-id');
            const userId = $(this).data('user-id');
            const planId = $(this).data('plan-id');
            performSearch(mealId, mealTimeId, userId, planId);
        });

        $(document).on('click', '#woolworthsSearchBtn', function() {
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
                    data: {
                        query: query
                    },
                    success: function(response) {
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
                                        <td>${item.fat ?? 'N/A'}</td>
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
                    error: function() {
                        loader.hide();
                        alert('Error occurred while searching. Please try again.');
                    }
                });
            } else if (searchType === 'woolworths') {
                // Perform Woolworths product search
                $.ajax({
                    url: '{{ route("woolworths-product-search") }}', // Use route for Woolworths search
                    type: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
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
                                                    data-serving-size="${product.nutrition.serving_size || 0}" 
                                                    data-serving-per-pack="${product.nutrition.serving_per_pack || 0}" 
                                                    data-category="${product.category || '' }"
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
                    error: function() {
                        loader.hide();
                        alert('Error occurred while searching Woolworths. Please try again.');
                    }
                });
            }
        }

        $(document).on('click', '.add-food-btn', function() {
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
                success: function(response) {
                    if (response.success) {
                        // alert('Food added successfully!');

                        const food = response.data;
                        const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;
                        const tableBody = $(mealContainerId).find('.items-table-body');

                        const swapFoods = food.swapItems ?? []; // Use the simplified swapItems array

                        if (tableBody.find(`tr[data-food-id="${food.id}"]`).length === 0) {

                            const swapItemsHTML = swapFoods.length > 0 ?
                                swapFoods.map(swapItem => `
                                    <li>
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${food.id}][]" value="${swapItem.id}" class="form-check-input me-2 d-none" data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}" checked>
                                            <label>${swapItem.name} (${swapItem.qty} ${swapItem.unit})</label>
                                        </div>
                                        <p>Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                    </li>
                                `).join('') :
                                '<span class="text-muted">No swap items available</span>';

                            tableBody.append(`
                                <tr data-food-id="${food.id}">
                                    <td width="45%">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" 
                                                value="${food.id}" 
                                                class="form-check-input me-2 d-none" data-carbs="${food.carbs}" data-protein="${food.protein}" data-fat="${food.fat}" checked>
                                            <label class="form-check-label flex-grow-1">${food.title} (${food.qty}${food.unit})</label>
                                        </div>
                                        <p>Protein: ${Math.round(food.protein)}g | Carb: ${Math.round(food.carbs)}g | Fat: ${Math.round(food.fat)}g</p>
                                    </td>
                                    <td width="45%">
                                        <ul class="list-unstyled">${swapItemsHTML}</ul>
                                    </td>
                                    <td width="10%">
                                        <button class="btn btn-sm edit-food btn-outline-success" 
                                                data-food-id="${food.id}" 
                                                data-meal-id="${mealId}" 
                                                data-swap-foods='${JSON.stringify(swapFoods)}',
                                                data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}" 
                                                data-swapfood-qty="${swapFoods[0]?.qty || ''}" 
                                                data-swapfood-unit="${swapFoods[0]?.unit || ''}" 
                                                data-food-qty="${food.qty}"
                                                data-food-unit="${food.unit}">
                                            <i class="icofont-edit text-success"></i>
                                        </button>
                                        <button class="btn  btn-sm delete-food btn-outline-danger" 
                                                data-food-id="${food.id}" 
                                                data-meal-id="${mealId}"
                                                data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}">
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
                error: function() {
                    alert('Error while adding food.');
                },
                complete: function() {
                    loader.hide();
                }
            });
        });

        // Add Food Button inside the search results
        $(document).on('click', '.add-woolworths-food', function() {
            const name = $(this).data('name');
            const image = $(this).data('image');
            const protein = $(this).data('protein');
            const carbs = $(this).data('carbs');
            const fat = $(this).data('fat');
            const category = $(this).data('category');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('mealtime-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');
            const servingSize = $(this).data('serving-size');
            const servingPerPack = $(this).data('serving-per-pack');

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
                    serving_size: servingSize,
                    serving_per_pack: servingPerPack,
                    category: category,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    type: 'woolworths',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // alert('Food added successfully!');
                        const food = response.data;
                        const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;
                        const tableBody = $(mealContainerId).find('.items-table-body');
                        
                        const selectedQtyUnits = {
                            qty: food.qty,
                            unit: food.unit
                        };
                        // Check if the food item already exists in the table
                        // if (tableBody.find(`tr[data-food-id="${food.id}"]`).length === 0) {

                            // Since swap items are not available, show the message
                            const swapItemsHTML = `<li class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="col-9">
                                                        <span class="text-muted">No swap items available</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                                            data-item-id="${food.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                                            title="Add"><i class="icofont-plus text-primary"></i>
                                                        </button>
                                                    </div>
                                                </li>`;

                            // Append the single food item row to the table
                            tableBody.append(`
                                <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${food.id}">
                                    <td class="text-wrap" width="50%">
                                        <div class="d-flex justify-content-between align-items-start mb-0">
                                            <div class="col-9">
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                                        value="${food.id}" class="form-check-input me-2 d-none" checked
                                                        data-carbs="${food.carbs}" data-protein="${food.protein}" data-fat="${food.fat}">
                                                    <label class="form-check-label flex-grow-1">${food.title}</label>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                                    data-item-id="${food.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                    data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${food.qty}" data-item-unit="${food.unit}"
                                                    data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                                    title="Edit"><i class="icofont-edit text-success"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                                    data-item-id="${food.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                    data-meal-time-id="${mealTimeId}" data-user-id="${userId}"
                                                    title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="px-2 mb-2 fw-bold">(${food.qty}${["g", "mL"].includes(food.unit) ? food.unit : ' ' + food.unit})</p>
                                                <p class="mb-0 px-2">Protein: ${Math.round(food.protein)}g | Carb: ${Math.round(food.carbs)}g | Fat: ${Math.round(food.fat)}g</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="50%">
                                        <ul class="list-unstyled">${swapItemsHTML}</ul>
                                    </td>
                                </tr>
                            `);
                        // }
                        $('#woolworthsSearchResults').hide();
                        $('#searchFoodQuery').val('');
                        $('#searchResultsLabel').text('');
                        $('#searchFoodModal').modal('hide');
                    } else {
                        alert('Failed to add food: ' + (response.message || 'Unknown error.'));
                    }
                },
                error: function() {
                    alert('Error while adding food.');
                },
                complete: function() {
                    loader.hide();
                }
            });
        });
    });
</script>

@endsection