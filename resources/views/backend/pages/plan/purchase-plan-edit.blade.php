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
        width: 50px; /* Adjust size */
        height: 50px;
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
                                                        <div class="mealTimeDetailsDiv" >
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
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <div class="nutrition-details">
                                                    <p>Plan Total Carbs: <span class="planTotalCarbs" id="allCarbsTotal">{{ $totalCarbs }}g</span> |
                                                    Plan Total Protein: <span class="planTotalProtein" id="allProteinTotal">{{ $totalProtein }}g</span> |
                                                    Plan Total Fat: <span class="PlanTotalFat" id="allFatTotal">{{ $totalFat }}g</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-5" >
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
                    <input type="hidden" name="food_id" id="foodId" value="">
                    <input type="hidden" name="food_name" id="foodName" value="">
                    <input type="hidden" name="food_carbs" id="foodCarbs" value="">
                    <input type="hidden" name="food_protein" id="foodProtein" value="">
                    <input type="hidden" name="food_fat" id="foodFat" value="">
                    <div class="form-group">
                        <label class="col-form-label" for="meals">Choose Meals:</label>
                        <select name="meals[]" id="meals" class="form-control meal-select " multiple>
                        </select>   
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="col-form-label">
                                <label class="col-form-label" for="foodQuantity">Food Quantity:</label>
                            </div>
                            <div >
                                <input type="number" name="food_qty" id="foodQuantity" class="form-control w-100" placeholder="Enter quantity">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="col-form-label">
                                <label for="itemQty" class="col-form-label">Food Quantity Unit:</label>
                            </div>
                            <div >
                                <select name="measurement" class="form-control" id="itemMeasurement" required>
                                    <option value="">Select Measurement</option>
                                    <option value="g">gm</option>
                                    <option value="cup">cup</option>
                                    <option value="tablespoon">tablespoon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="itemNutritionResult" class="mt-3" style="display: none;">
                        <label>Nutrition Information:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" class="form-control protein" placeholder="Protein (g)" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control carbs" placeholder="Carbs (g)" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control fat" placeholder="Fat (g)" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-form-label">
                        <label class="col-form-label" for="swapFoods">Choose Swap Foods:</label>
                        </div>
                        <div>
                        <select name="swap_foods[]" id="swapFoods" class="form-control w-100" >
                            
                        </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="col-form-label">
                                <label class="col-form-label" for="swapFoodQuantity">Swap Food Quantity:</label>
                            </div>
                            <div>
                                <input type="number" name="swap_food_qty" id="swapFoodQty" class="form-control w-100" placeholder="Enter quantity">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="col-form-label">
                                <label for="itemQty" class="col-form-label">Swap Food Quantity Unit:</label>
                            </div>
                            <div>
                                <select name="swapMeasurement" class="form-control" id="swapMeasurement" required>
                                    <option value="">Select Measurement</option>
                                    <option value="g">gm</option>
                                    <option value="cup">cup</option>
                                    <option value="tablespoon">tablespoon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Nutrition Result for Swap Food -->
                    <div id="swapNutritionResult" class="mt-3" style="display: none;">
                        <label>Swap Food Nutrition Information:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control protein" placeholder="Protein (g)" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control carbs" placeholder="Carbs (g)" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control fat" placeholder="Fat (g)" readonly>
                            </div>
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
                    <input type="hidden" name="food_id" id="editFoodId" value="">
                    <input type="hidden" name="food_name" id="editFoodName" value="">
                    <input type="hidden" name="food_carbs" id="editFoodCarbs" value="">
                    <input type="hidden" name="food_protein" id="editFoodProtein" value="">
                    <input type="hidden" name="food_fat" id="editFoodFat" value="">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="itemName" disabled>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="itemQty" class="form-label">Food Quantity</label>
                            <input type="number" class="form-control" id="itemQty">
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="itemQty" class="form-label">Food Quantity Unit</label>
                            <select name="measurement" class="form-control" id="itemUnit" required>
                                <option value="">Select Measurement</option>
                                <option value="g">gm</option>
                                <option value="cup">cup</option>
                                <option value="tablespoon">tablespoon</option>
                            </select>
                        </div>
                    </div>
                    <div id="editItemNutritionResult" class="mt-3" style="display: none;">
                        <label>Nutrition Information:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" class="form-control protein" placeholder="Protein (g)" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control carbs" placeholder="Carbs (g)" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control fat" placeholder="Fat (g)" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="swapItems" class="form-label">Select Swap Food</label>
                        <select class="form-select select2" id="swapItems" >
                            <!-- Options will be added dynamically -->
                        </select>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="swapItemQty" class="form-label">Swap Food Quantity</label>
                            <input type="number" class="form-control" id="swapItemQty">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="swapItemUnit" class="form-label">Swap Food Quantity Unit</label>
                            <select name="swapItemUnit" class="form-control" id="swapItemUnit" required>
                                <option value="">Select Measurement</option>
                                <option value="g">gm</option>
                                <option value="cup">cup</option>
                                <option value="tablespoon">tablespoon</option>
                            </select>
                        </div>
                    </div>
                    <!-- Nutrition Result for Swap Food -->
                    <div id="editSwapItemNutritionResult" class="mt-3" style="display: none;">
                        <label>Swap Food Nutrition Information:</label>
                        <div class="row">
                            <input type="text" class="form-control protein" id="swapProtein" placeholder="Protein (g)" readonly>
                            <input type="text" class="form-control carbs" id="swapCarbs" placeholder="Carbs (g)" readonly>
                            <input type="text" class="form-control fat" id="swapFat" placeholder="Fat (g)" readonly>

                        </div>
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
     document.addEventListener('DOMContentLoaded', function () {
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
            anchor.addEventListener('click', function (event) {
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
        window.addEventListener('beforeunload', function (event) {
            if (hasUnsavedChanges) {
                event.preventDefault();
            }
        });

        // Modal Button: "Save Changes"
        document.getElementById('saveChanges').addEventListener('click', function () {
            hasUnsavedChanges = false;
            document.getElementById('editPlanForm').submit();
            document.getElementById('savePlanModal').style.display = 'none';
        });

        // Modal Button: "No Leave"
        document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
            hasUnsavedChanges = false;

            // Correctly redirect to the stored intended URL (like Food link)
            if (intendedHref) {
                window.location.href = intendedHref;
            }
        });

        // Form Submit Logic
        document.getElementById('editPlanForm').addEventListener('submit', function () {
            hasUnsavedChanges = false; // Clear flag on form submission
        });
    });

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
        // $('.select2').select2({
        //     placeholder: "Select options",
        //     allowClear: true,
        //     width: '100%'
        // });

        $('#swapFoods').select2({
            placeholder: "Search for swap foods",
            minimumInputLength: 1,  // Trigger API call after 1 character
            width: '100%',
            allowClear: true,  // Enables clearing selection
            dropdownParent: $('#swapFoods').closest('form'), // Ensures proper dropdown positioning
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
                    // Ensure response.items exists
                    if (!response.items) {
                        return { results: [] }; // Return empty if no data
                    }
                    
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
        const loading = $('#loader-2');
        function calculateNutrition(data, resultDiv) {
            
            $.ajax({
                url: "{{ route('nutrition.calculate') }}",
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data) {
                        console.log(data);
                        setTimeout(() => {
                            resultDiv.find('.protein').val(data.protein);
                            resultDiv.find('.carbs').val(data.carbs);
                            resultDiv.find('.fat').val(data.fat);
                        }, 500);
                      
                    } else {
                        resultDiv.html(`<p class="error">Error: Could not calculate.</p>`).addClass('error').show();
                    }
                },
                error: function () {
                    resultDiv.html(`<p class="error">Error: Unable to connect to the server.</p>`).addClass('error').show();
                }
            });
        }

        function fetchItemDetails(foodId, callback) {
            $.ajax({
                url: '{{ route("admin.items.index") }}?food_id=' + foodId,
                type: 'GET',
                success: function (response) {
                    if (response.items) {
                        callback(response.items);
                    }
                },
                error: function () {
                    console.error('Error fetching item details.');
                }
            });
        }

        function handleItemChange() {
            let foodId = $('#foodId').val();
            loading.show();
            fetchItemDetails(foodId, function (item) {
                $('#foodCarbs').val(item.carbs);
                $('#foodProtein').val(item.protein);
                $('#foodFat').val(item.fat);
                $('#foodName').val(item.title);

                const data = {
                    title: $('#foodName').val(),
                    carbs: $('#foodCarbs').val(),
                    protein: $('#foodProtein').val(),
                    fat: $('#foodFat').val(),
                    qty: $('#foodQuantity').val(),
                    measurement: $('#itemMeasurement').val()
                };

                calculateNutrition(data, $('#itemNutritionResult'));

                setTimeout(() => {
                    loading.hide();
                }, 500);
            });
        }

        function handleSwapItemChange() {
            let foodId = $('#swapFoods').val();
            loading.show();
            fetchItemDetails(foodId, function (item) {
                $('#foodCarbs').val(item.carbs);
                $('#foodProtein').val(item.protein);
                $('#foodFat').val(item.fat);
                $('#foodName').val(item.title);

                const data = {
                    title: $('#foodName').val(),
                    carbs: $('#foodCarbs').val(),
                    protein: $('#foodProtein').val(),
                    fat: $('#foodFat').val(),
                    qty: $('#swapFoodQty').val(),
                    measurement: $('#swapMeasurement').val()
                };

                calculateNutrition(data, $('#swapNutritionResult'));

                setTimeout(() => {
                    loading.hide();
                }, 500);
            });
        }

        function handleEditItemChange() {
            loading.show();
            let foodId = $('#editFoodId').val();
            fetchItemDetails(foodId, function (item) {
                $('#editFoodCarbs').val(item.carbs);
                $('#editFoodProtein').val(item.protein);
                $('#editFoodFat').val(item.fat);
                $('#editFoodName').val(item.title);

                const data = {
                    title: $('#editFoodName').val(),
                    carbs: $('#editFoodCarbs').val(),
                    protein: $('#editFoodProtein').val(),
                    fat: $('#editFoodFat').val(),
                    qty: $('#itemQty').val(),
                    measurement: $('#itemUnit').val()
                };

                // calculateNutrition(data, $('#editItemNutritionResult'));
                $.ajax({
                    url: "{{ route('nutrition.calculate') }}",
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data) {
                            setTimeout(() => {
                                $('#editItemNutritionResult .protein').val(data.protein);
                                $('#editItemNutritionResult .carbs').val(data.carbs);
                                $('#editItemNutritionResult .fat').val(data.fat);
                            }, 500);
                            loading.hide();
                        } else {
                            $('#editItemNutritionResult').html(`<p class="error">Error: Could not calculate.</p>`).addClass('error').show();
                            loading.hide();
                        }
                    },
                    error: function () {
                        console.error("Error: Unable to connect to the server.");
                        loading.hide();
                    }
                });
            });
        }

        function handleEditSwapItemChange() {
            loading.show();
            let foodId = $('#swapItems').val();
            fetchItemDetails(foodId, function (item) {
                $('#editFoodCarbs').val(item.carbs);
                $('#editFoodProtein').val(item.protein);
                $('#editFoodFat').val(item.fat);
                $('#editFoodName').val(item.title);

                const data = {
                    title: $('#editFoodName').val(),
                    carbs: $('#editFoodCarbs').val(),
                    protein: $('#editFoodProtein').val(),
                    fat: $('#editFoodFat').val(),
                    qty: $('#swapItemQty').val(),
                    measurement: $('#swapItemUnit').val()
                };

                $.ajax({
                    url: "{{ route('nutrition.calculate') }}",
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data) {
                           
                            setTimeout(() => {
                                $('#swapProtein').val(data.protein);
                                $('#swapCarbs').val(data.carbs);
                                $('#swapFat').val(data.fat);
                            }, 500);
                            loading.hide();
                        } else {
                            $('#editSwapItemNutritionResult').html(`<p class="error">Error: Could not calculate.</p>`).addClass('error').show();
                            loading.hide();
                        }
                    },
                    error: function () {
                        console.error("Error: Unable to connect to the server.");
                        loading.hide();
                    }
                });
            });
        }

        // Attach event listeners (only runs when user interacts)
        $('#itemMeasurement').on('change', handleItemChange);
        $('#swapMeasurement').on('change', handleSwapItemChange);
        $('#itemUnit').on('change', handleEditItemChange);
        $('#swapItemUnit').on('change', handleEditSwapItemChange);

        $('#foodQuantity').on('change', function () {
            $('#itemMeasurement').val('');
        });
        $('#swapFoodQty').on('change', function () {
            $('#swapMeasurement').val('');
        });
        $('#itemQty').on('change', function () {
            $('#itemUnit').val('');
        });
        $('#swapItemQty').on('change', function () {
            $('#swapItemUnit').val('');
        });
        // Ensure modals reset on close
        $('#swapFoodsModal, #editItemModal').on('hidden.bs.modal', function () {
            $(this).find('input').val(''); // Reset all input fields
            $(this).find('select').val(''); // Reset all select fields
            $(this).find('.error').hide(); // Hide error messages
        });
    });

    $(document).ready(function () {
        const previouslySelectedMeals = {};
        const preSelectedMeals = @json($selectedMeals);
        const preSelectedItems = @json($selectedItems); // Pre-selected user items
        const preSelectedSwapItems = @json($selectedSwapItems); // Pre-selected swap items
        const payment = @json($payment);
        const userId = payment.user_id;
        const loader = $('#loader');
        $('.meal-time-checkbox').each(function () {
            const checkbox = $(this);
            const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
            const mealTimeId = checkbox.data('mealtime-id');
            const userId = checkbox.closest('.panel').find('input[name="user_id"]').val();
            const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
            const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
            const mealSelect = $(dropdownId).find('select');
            const mealTimeDetailsDiv = checkbox.closest('li').find('.mealTimeDetailsDiv'); // Add this

            if (preSelectedMeals[planId]?.[mealTimeId]) {
                // checkbox.prop('checked', true); // ✅ Trigger change event here
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
                    success: function (response) {
                        if (response.success) {
                            mealSelect.empty();
                            response.meals.forEach(meal => {
                                // If preSelectedMeals[mealTimeId] is an object, we check if the meal.id exists in it
                                let selectedMeal = null;
                                if (preSelectedMeals[planId]?.[mealTimeId]?.[meal.id]) {
                                    selectedMeal = preSelectedMeals[planId][mealTimeId][meal.id];
                                }                                
                                const isSelected = selectedMeal ? true : false;

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
                        }
                    },
                    error: function () {
                        alert('Error occurred while loading meals.');
                    }
                });
                // checkbox.prop('checked', false).trigger('change'); // ✅ Ensure this fires change event too

            } else {
                checkbox.prop('checked', false); // ✅ Ensure this fires change event too
                $(dropdownId).hide();
                $(selectedMealsId).hide();
                mealTimeDetailsDiv.css('display', 'block'); // Hide the element

            }

        });

        let planID = 0;
        let mealtimeID = 0;
        let mealIDs = [];

        $('.meal-time-checkbox').on('change', function () {
            
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

        // Function to calculate nutrition
        function calculateMealNutrition() {
            let grandTotalCarbs = 0;
            let grandTotalProtein = 0;
            let grandTotalFat = 0;

            $('.meal-container').each(function() {
                let totalCarbs = 0;
                let totalProtein = 0;
                let totalFat = 0;

                $(this).find('.items-table-body tr').each(function() {
                    const $tds = $(this).find('td');

                    if ($tds) {
                        $tds.each(function() {
                            const $input = $(this).find('input');

                            totalCarbs += parseFloat($input.data('carbs')) || 0;
                            totalProtein += parseFloat($input.data('protein')) || 0;
                            totalFat += parseFloat($input.data('fat')) || 0;
                        });
                    }
                });

                $(this).data({
                    totalCarbs: totalCarbs.toFixed(2),
                    totalProtein: totalProtein.toFixed(2),
                    totalFat: totalFat.toFixed(2)
                });

                grandTotalCarbs += totalCarbs;
                grandTotalProtein += totalProtein;
                grandTotalFat += totalFat;

                // Console log for testing
                console.log(`Meal Details:
                - Total Carbs: ${totalCarbs.toFixed(2)}g
                - Total Protein: ${totalProtein.toFixed(2)}g
                - Total Fat: ${totalFat.toFixed(2)}g`);
            });

            // Set the total values in respective elements
            $('#allCarbsTotal').text(`${grandTotalCarbs.toFixed(2)}g`);
            $('#allProteinTotal').text(`${grandTotalProtein.toFixed(2)}g`);
            $('#allFatTotal').text(`${grandTotalFat.toFixed(2)}g`);

            // Console log total values
            console.log(`Total Nutrition Values:
            - Total Carbs: ${grandTotalCarbs.toFixed(2)}g
            - Total Protein: ${grandTotalProtein.toFixed(2)}g
            - Total Fat: ${grandTotalFat.toFixed(2)}g`);

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
                // $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
                const removedMealContainer = $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`);
        
                // Decrement item and swap item counts
                removedMealContainer.find('input[name^="items"]').each(function () {
                    const itemId = $(this).val();
                    updateFoodCount(itemId, -1);  // Decrease item count
                });

                removedMealContainer.find('input[name^="swap_items"]').each(function () {
                    const swapItemId = $(this).val();
                    updateFoodCount(swapItemId, -1);  // Decrease swap item count
                });

                // Finally remove the meal container
                removedMealContainer.remove();
                calculateMealNutrition();

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
                                preSelectedSwapItems,
                                response.total_carbs,
                                response.total_fat,
                                response.total_protein
                            );

                            selectedMealsContainer.append(mealContainer);
                            calculateMealNutrition();  // Trigger calculation after new meal is added

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
                    error: function () {
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
                    <p>Total Carbs: <span class="totalCarbs">${totalCarbs}g </span> | Total Protein: <span class="totalProtein">${totalProtein}g </span> | Total Fat: <span class="totalFat">${totalFat}g</span></p>
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
                                <div class="d-flex align-items-start">
                                    <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]"
                                        value="${swapItem.id}" class="form-check-input me-2 d-none" ${isSelectedSwapItem ? 'checked' : 'checked'} data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}">
                                    <label class="form-check-label">${swapItem.name} (${swapItem.qty} ${swapItem.unit})</label>
                                </div>
                                <p>Carbs: ${swapItem.carbs}g | Protein: ${swapItem.protein}g | Fat: ${swapItem.fat}g</p>
                            </li>
                        `;
                    }).join('');
                } else {
                    swapItemsHTML = '<span class="text-muted">No swap items available</span>';
                }

                const swapsFoods = item.swapItems || [];
               
                // Append a row for the item and its swap items
                tableBody.append(`
                    <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${item.id}">
                        <td class="text-wrap" width="45%">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                    value="${item.id}" class="form-check-input me-2 d-none" ${isSelectedItem ? 'checked' : 'checked'} data-carbs="${item.carbs}" data-protein="${item.protein}" data-fat="${item.fat}">
                                <label class="form-check-label flex-grow-1">${item.name} (${item.qty} ${item.unit})</label>
                            </div>
                            <p>Carbs: ${item.carbs}g | Protein: ${item.protein}g | Fat: ${item.fat}g</p>
                        </td>
                        <td width="45%">
                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                        </td>
                        <td class="text-nowrap" width="10%">
                            <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${item.qty}" data-item-unit="${item.unit}" 
                                data-swap-foods="${JSON.stringify(swapsFoods)}"
                                title="Edit"><i class="icofont-edit text-success"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}"  title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
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
                $('#swapFoodsModal').find('#foodId').val(foodId);
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
            const foodQty = $('#foodQuantity').val();
            const foodUnit = $('#itemMeasurement').val();
            const foodId = $('#swapFoodsModal').data('food-id');
            const foodName = $('#swapFoodsModal').data('food-name');
            const isEditMode = $('#swapFoodsModal').data('edit-mode');
            const mealId = $('#swapFoodsModal').data('meal-id');
            const swapFoodQty = $('#swapFoodQty').val();
            const swapFoodUnit = $('#swapMeasurement').val();
            const foodProtein = $('#itemNutritionResult .protein').val();
            const foodCarbs = $('#itemNutritionResult .carbs').val();
            const foodFat = $('#itemNutritionResult .fat').val();
            const swapFoodCarbs = $('#swapNutritionResult .carbs').val();
            const swapFoodProtein = $('#swapNutritionResult .protein').val();
            const swapFoodFat = $('#swapNutritionResult .fat').val();

            const previousSwapItemId = $('#swapFoodsModal').data('previous-swapfood-id'); // Previous swap item ID
            const previousSwapItemQty = $('#swapFoodsModal').data('previous-swapfood-qty'); // Previous swap item qty

            if (!isEditMode && (!selectedMeals || selectedMeals.length === 0)) {
                alert('Please select at least one meal.');
                return;
            }

            let type = 'add';
            if (isEditMode === true) {
                type = 'edit';
            }
            // console.log(isEditMode);
            $.ajax({
                url: '{{ route("admin.save-swap-food") }}',
                method: 'POST',
                data: {
                    food_id: foodId,
                    swap_foods: selectedFoods,
                    meal_ids: isEditMode ? [mealId] : selectedMeals,
                    user_id: userId,
                    food_qty: foodQty,
                    food_unit: foodUnit,
                    swap_food_qty: swapFoodQty,
                    swap_food_unit: swapFoodUnit,
                    protein: foodProtein,
                    carbs: foodCarbs,
                    fat: foodFat,
                    swap_food_carbs: swapFoodCarbs,
                    swap_food_protein: swapFoodProtein,
                    swap_food_fat: swapFoodFat,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Swap foods saved successfully.');

                        const swapFoods = response.swapItems || [];
                        const savedItem = response.item || null;

                        if (isEditMode) {
                            const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
                            const tableBody = $(mealContainerId).find('.items-table-body');
                            const foodRow = tableBody.find(`tr[data-food-id="${foodId}"]`);

                            const swapItemsHTML = swapFoods.length > 0
                                ? swapFoods.map(item => `
                                    <li>
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${foodId}][]" value="${item.swap_item_id}" class="form-check-input me-2 d-none" data-carbs="${item.carbs !== null ? item.carbs : item.swap_item.carbs}" data-protein="${item.protein !== null ? item.protein : item.swap_item.protein}" data-fat="${item.fat !== null ? item.fat : item.swap_item.fat}" checked>
                                            <label>${item.swap_item.title} (${item.qty} ${item.unit})</label>
                                        </div>
                                        <p>Carbs: ${item.carbs !== null ? item.carbs : item.swap_item.carbs}g | Protein: ${item.protein !== null ? item.protein : item.swap_item.protein}g | Fat: ${item.fat !== null ? item.fat : item.swap_item.fat}g </p>
                                    </li>
                                `).join('')
                                : '<span class="text-muted">No swap items available</span>';

                            // Updated First <td> in Edit Mode
                            foodRow.html(`
                                <td class="text-wrap" width="45%">
                                    <div class="d-flex align-items-start">
                                        <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]" value="${savedItem.item_id}" class="form-check-input me-2 d-none" data-carbs="${savedItem.carbs !== null ? savedItem.carbs : savedItem.items.carbs}" data-protein="${savedItem.protein !== null ? savedItem.protein : savedItem.items.protein}" data-fat="${savedItem.fat !== null ? savedItem.fat : savedItem.items.fat}" checked>
                                        <label class="form-check-label flex-grow-1">${savedItem.items.title} (${savedItem.qty} ${savedItem.unit})</label>
                                    </div>
                                    <p>Carbs: ${savedItem.carbs !== null ? savedItem.carbs : savedItem.items.carbs}g | Protein: ${savedItem.protein !== null ? savedItem.protein : savedItem.items.protein}g | Fat: ${savedItem.fat !== null ? savedItem.fat : savedItem.items.fat}g </p>
                                </td>
                                <td width="45%">
                                    <ul class="list-unstyled">${swapItemsHTML}</ul>
                                </td>
                                <td width="10%">
                                    <button class="btn btn-sm btn-outline-success edit-food"
                                        data-food-id="${foodId}" 
                                        data-meal-id="${mealId}" 
                                        data-previous-swapfood-id="${swapFoods[0]?.swap_item_id || ''}"
                                        data-previous-swapfood-qty="${swapFoods[0]?.qty || ''}"
                                        data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}" 
                                        data-swapfood-qty="${swapFoods[0]?.qty || ''}"
                                        data-swapfood-unit="${swapFoods[0]?.unit || ''}"
                                        data-food-qty="${savedItem.qty}"
                                        data-food-unit="${savedItem.unit}">
                                        <i class="icofont-edit text-success"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-food" data-food-id="${foodId}" data-meal-id="${mealId}" data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}">
                                        <i class="icofont-ui-delete text-danger"></i>
                                    </button>
                                </td>
                            `);
                            
                            // ✅ Correct Count Management
                            if (swapFoods.length > 0) {
                                const currentSwapItemId = swapFoods[0].swap_item_id;

                                if (previousSwapItemId) {
                                    if (previousSwapItemId !== currentSwapItemId) {
                                        updateFoodCount(previousSwapItemId, -1); 
                                        updateFoodCount(currentSwapItemId, 1); 
                                    }
                                } else {
                                    updateFoodCount(currentSwapItemId, 1);
                                }
                            }

                            calculateTotals(planID, mealtimeID, mealId);
                            calculateMealNutrition();
                        }else {
                            // **Add Mode Logic**
                            selectedMeals.forEach(mealId => {
                                const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
                                const tableBody = $(mealContainerId).find('.items-table-body');

                                if (tableBody.find(`tr[data-food-id="${foodId}"]`).length === 0) {
                                    const swapItemsHTML = swapFoods.length > 0
                                        ? swapFoods.map(item => `
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${foodId}][]" value="${item.swap_item_id}" class="form-check-input me-2 d-none" data-carbs="${item.carbs !== null ? item.carbs : item.swap_item.carbs}" data-protein="${item.protein !== null ? item.protein : item.swap_item.protein}" data-fat="${item.fat !== null ? item.fat : item.swap_item.fat}" checked>
                                                    <label>${item.swap_item.title} (${item.qty} ${item.unit})</label>
                                                </div>
                                                <p>Carbs: ${item.carbs !== null ? item.carbs : item.swap_item.carbs}g | Protein: ${item.protein !== null ? item.protein : item.swap_item.protein}g | Fat: ${item.fat !== null ? item.fat : item.swap_item.fat}g </p>
                                            </li>
                                            
                                        `).join('')
                                        : '<span class="text-muted">No swap items available</span>';

                                    tableBody.append(`
                                        <tr data-food-id="${foodId}">
                                            <td class="text-wrap" width="45%">
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]" value="${savedItem.item_id}" class="form-check-input me-2 d-none" data-carbs="${savedItem.carbs !== null ? savedItem.carbs : savedItem.items.carbs}" data-protein="${savedItem.protein !== null ? savedItem.protein : savedItem.items.protein}" data-fat="${savedItem.fat !== null ? savedItem.fat : savedItem.items.fat}" checked>
                                                    <label class="form-check-label flex-grow-1">${savedItem.items.title} (${savedItem.qty} ${savedItem.unit})</label>
                                                </div>
                                                <p>Carbs: ${savedItem.carbs !== null ? savedItem.carbs : savedItem.items.carbs}g | Protein: ${savedItem.protein !== null ? savedItem.protein : savedItem.items.protein}g | Fat: ${savedItem.fat !== null ? savedItem.fat : savedItem.items.fat}g </p>
                                            </td>
                                            <td width="45%">
                                                <ul class="list-unstyled">${swapItemsHTML}</ul>
                                            </td>
                                            <td width="10%">
                                                <button class="btn btn-sm btn-outline-success edit-food"
                                                    data-food-id="${foodId}" 
                                                    data-meal-id="${mealId}" 
                                                    data-previous-swapfood-id="${swapFoods[0]?.swap_item_id || ''}"
                                                    data-previous-swapfood-qty="${swapFoods[0]?.qty || ''} ${swapFoods[0]?.unit || ''}"
                                                    data-previous-swapfood-unit="${swapFoods[0]?.unit || ''}"
                                                    data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}" 
                                                    data-swapfood-qty="${swapFoods[0]?.qty || ''}" 
                                                    data-swapfood-unit="${swapFoods[0]?.unit || ''}"
                                                    data-food-qty="${savedItem.qty}"
                                                    data-food-unit="${savedItem.unit}">
                                                    <i class="icofont-edit text-success"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-food" data-food-id="${foodId}" data-meal-id="${mealId}" data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}" >
                                                    <i class="icofont-ui-delete text-danger"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `);
                                }
                                calculateTotals(planID, mealtimeID, mealId);
                                calculateMealNutrition();
                            });

                            // Count logic for newly added items
                            if (savedItem) {
                                updateFoodCount(savedItem.item_id, 1);
                            }

                            if (swapFoods.length > 0) {
                                updateFoodCount(swapFoods[0].swap_item_id, 1);
                            }
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
            $('#meals').val([]).trigger('change').closest('.form-group').show();
        });

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
                    .addClass('text-primary')       // Add primary color
            } else {
                countLabel
                    .text(countText.replace(/\s*\(\d+\)$/, ''))  // Remove count if zero
                    .removeClass('text-primary')   // Remove primary color when count is zero
            }
        }

        // Edit Food Item
        // $(document).on('click', '.edit-food', function (e) {
        //     e.preventDefault();

        //     const foodId = $(this).data('food-id');
        //     const mealId = $(this).data('meal-id');
        //     const swapFoodId = $(this).data('swapfood-id');
        //     const swapFoodQty = $(this).data('swapfood-qty');
        //     console.log(mealId);
        //     console.log(foodId);
           
        //     // Store previous swap item details for comparison during save
        //     $('#swapFoodsModal').data('previous-swapfood-id', swapFoodId || null);
        //     $('#swapFoodsModal').data('previous-swapfood-qty', swapFoodQty || null);

        //     // Set edit mode flag and store mealId for later use
        //     $('#swapFoodsModal').data('edit-mode', true);
        //     $('#swapFoodsModal').data('meal-id', mealId);
        //     $('#swapFoodsModal').data('food-id', foodId);
        //     $('#swapFoodsModal').data('food-name', $(this).closest('tr').find('label').text());

        //     // Pre-select existing swap foods in the modal
        //     setTimeout(() => {
        //         $('#swapFoods').val(swapFoodId).trigger('change');
        //     }, 200);      

        //     $('#meals').val([mealId]).trigger('change');

        //     // Open modal for editing
        //     $('#swapFoodsModal').modal('show');
        // });

        $(document).on('click', '.edit-food', function (e) {
            e.preventDefault();

            // Get food and meal-related data from the button
            const foodId = $(this).data('food-id');
            const mealId = $(this).data('meal-id');
            const swapFoodId = $(this).data('swapfood-id');
            const swapFoodQty = $(this).data('swapfood-qty');
            const foodQty = $(this).data('food-qty');
            const foodUnit = $(this).data('food-unit');
            const swapFoodUnit = $(this).data('swapfood-unit');
            console.log("Meal ID:", mealId);
            console.log("Food ID:", foodId);

            // Find the row where the button was clicked
            const $row = $(this).closest('tr');

            // **Extract Food Details from the First `<td>`**
            const $foodDetailsTd = $row.find('td:first');
            const foodName = $foodDetailsTd.find('label').text().trim();
            const $foodCheckbox = $foodDetailsTd.find('input[type="checkbox"]');

            // Extract nutrition values from data attributes
            const foodCarbs = $foodCheckbox.data('carbs') || 0;
            const foodProtein = $foodCheckbox.data('protein') || 0;
            const foodFat = $foodCheckbox.data('fat') || 0;

            // console.log("Food Details:", foodName, "Carbs:", foodCarbs, "Protein:", foodProtein, "Fat:", foodFat);
            // **Extract Swap Food Details from the Second `<td>`**
            const $swapDetailsTd = $row.find('td:eq(1)'); // Second TD (Swap Food)
            const swapFoodTitle = $swapDetailsTd.find('select option:selected').text().trim();
            const swapFoodDropdown = $swapDetailsTd.find('select');
            const $swapFoodCheckbox = $foodDetailsTd.find('input[type="checkbox"]');

            const swapFoodCarbs = $swapFoodCheckbox.data('carbs') || 0;
            const swapFoodProtein = $swapFoodCheckbox.data('protein') || 0;
            const swapFoodFat = $swapFoodCheckbox.data('fat') || 0;
            
           
            // console.log("Swap Food:", swapFoodTitle);

            // Store previous swap item details for comparison during save
            $('#swapFoodsModal').data('previous-swapfood-id', swapFoodId || null);
            $('#swapFoodsModal').data('previous-swapfood-qty', swapFoodQty || null);
            $('#swapFoodsModal').data('previous-swapfood-unit', swapFoodUnit || null);

            // Set edit mode flag and store mealId for later use
            $('#swapFoodsModal').data('edit-mode', true);
            $('#swapFoodsModal').data('meal-id', mealId);
            $('#swapFoodsModal').data('food-id', foodId);
            $('#swapFoodsModal').data('food-name', foodName);

            // **Set values in the modal form**
            $('#foodId').val(foodId);
            $('#foodName').val(foodName);
            $('#foodCarbs').val(foodCarbs);
            $('#foodProtein').val(foodProtein);
            $('#foodFat').val(foodFat);

            $('#swapFoodsModal').modal('show');

            // Split quantity and unit (if available)
            
            $('#foodQuantity').val(foodQty); // Numeric quantity
            $('#itemMeasurement').val(foodUnit); // Measurement unit (g, cup, etc.)

            // Pre-select meal in the dropdown
            $('#meals').val([mealId]).trigger('change');

            // **Pre-select existing swap foods in the modal**
            setTimeout(() => {
                $('#swapFoods').val(swapFoodId).trigger('change');
            }, 200);   
            
            $('#swapFoodQty').val(swapFoodQty); // Numeric swap quantity
            $('#swapMeasurement').val(swapFoodUnit); // Swap measurement unit

            $('#itemNutritionResult').find('.protein').val(foodCarbs);
            $('#itemNutritionResult').find('.carbs').val(foodProtein);
            $('#itemNutritionResult').find('.fat').val(foodFat);

            $('#swapNutritionResult').find('.protein').val(swapFoodCarbs);
            $('#swapNutritionResult').find('.carbs').val(swapFoodProtein);
            $('#swapNutritionResult').find('.fat').val(swapFoodFat);
        
            // Open the modal
        });


        $(document).on('click', '.edit-food-1', function (e) {
            e.preventDefault();
            const foodId = $(this).data('food-id');
            const mealId = $(this).data('meal-id');
            const swapFoodId = $(this).data('swapfood-id');
            const swapFoodQty = $(this).data('swapfood-qty');

            $('#swapFoodsModal').data('edit-mode', true);
            $('#swapFoodsModal').data('meal-id', mealId);
            $('#swapFoodsModal').data('food-id', foodId);
            $('#swapFoodsModal').data('food-name', $(this).closest('tr').find('label').text());

            if (!mealId) {
                console.warn('Meal ID is missing.');
                return;
            }
            // $('#swapFoodsModal').modal('show');

            $('#swapFoodsModal').modal('show'); // Show the modal first

            // Ensure Select2 is initialized (to avoid duplicates)
            if (!$('#meals').hasClass("select2-hidden-accessible")) {
                $('#meals').select2({
                    width: '100%',
                    placeholder: "Search for meals...",
                    allowClear: true,
                    ajax: {
                        url: '{{ route("admin.meals.index") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { search: params.term };
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

            // Add the selected meal dynamically if not in AJAX response
            if (mealId) {
                $.ajax({
                    url: '{{ route("admin.meals.index") }}',
                    dataType: 'json',
                    data: { search: '' }, // Optionally search for all meals
                    success: function (data) {
                        const mealExists = data.meals.find(meal => meal.id == mealId);

                        if (mealExists) {
                            // Add selected meal if it exists in the data
                            const selectedMeal = new Option(mealExists.name, mealExists.id, true, true);
                            $('#meals').append(selectedMeal).trigger('change');
                        } else {
                            // Add meal manually if it doesn't appear in AJAX results
                            const fallbackMeal = new Option(`Meal ID ${mealId}`, mealId, true, true);
                            $('#meals').append(fallbackMeal).trigger('change');
                        }
                    },
                    error: function () {
                        console.error('Error loading meal data.');
                    }
                });
            }
                
            $('#swapFoods').val(swapFoodId).trigger('change');

        });

        // Delete Food Item
        $(document).on('click', '.delete-food', function (e) {
            e.preventDefault();
            const foodId = $(this).data('food-id');
            const mealId = $(this).data('meal-id');
            const swapFoodId = $(this).data('swapfood-id');
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
                        updateFoodCount(foodId, -1);
                        if(swapFoodId){
                            updateFoodCount(swapFoodId, -1);
                        }
                        alert('Food deleted successfully!');
                        $(mealContainerId).find(`tr[data-food-id="${foodId}"]`).remove();
                        calculateTotals(planID, mealtimeID, mealId);
                        calculateMealNutrition();
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
            const itemUnit = $(this).data('item-unit');
            const swapItems = $(this).data('swap-foods');
           
            // Open modal and populate with item details and swap items
            openEditItemModal(itemId, mealId, mealTimeId, planId, userId, itemQty, itemUnit);
        });

        // Delete Item Button Action
        $(document).on('click', '.delete-item', function () {
            const itemId = $(this).data('item-id');
            const mealId = $(this).data('meal-id');
            const mealTimeId = $(this).data('meal-time-id');
            const planId = $(this).data('plan-id');
            const userId = $(this).data('user-id');
            let swap_food_id = $(this).data('swapfood-id');
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
                            updateFoodCount(itemId, -1);
                            if(swap_food_id) {
                                updateFoodCount(swap_food_id, -1);
                            }
                            alert('Item deleted successfully!');
                            // You can perform the deletion via AJAX or remove the item row from the table
                            $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`).remove();
                            calculateTotals(planId, mealTimeId, mealId);
                            calculateMealNutrition();
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
        function openEditItemModal(itemId, mealId, mealTimeId, planId, userId, itemQty, itemUnit) {
            $.ajax({
                url: '{{ route("admin.get-swap-items") }}',
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
                        const item = response.item;
                        const selectedSwapItems = response.selectedSwapItems;
                        $('#editFoodId').val(itemId);
                        $('#editFoodName').val(item.title);
                        $('#itemName').val(item.title);
                        $('#itemQty').val(itemQty);
                        if ($("#itemUnit option[value='" + itemUnit + "']").length > 0) {
                            $('#itemUnit').val(itemUnit).change(); // Select the option
                        } else {
                            console.log("Value not found in dropdown:", itemUnit);
                        }

                        $('#swapItems').empty();

                        $('#swapItems').select2({
                            placeholder: "Search for swap foods",
                            minimumInputLength: 1,
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#editItemModal'),
                            ajax: {
                                url: '{{ route("admin.items.index") }}',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return { query: params.term };
                                },
                                processResults: function(data) {
                                    return {
                                        results: data.items.map(function(item) {
                                            return { id: item.id, text: item.title };
                                        })
                                    };
                                },
                                cache: true
                            }
                        });

                        selectedSwapItems.forEach(function(swapItem) {
                            const option = new Option(swapItem.name, swapItem.id, true, true);
                            $('#swapItems').append(option);
                            $('#swapItemQty').val(swapItem.qty);
                            $('#swapItemUnit').val(swapItem.unit).change();
                        });

                        $('#swapItems').trigger('change');

                        // Save the selected swap items in modal data for comparison
                        $('#editItemForm').data({
                            'item-id': item.id,
                            'meal-id': mealId,
                            'meal-time-id': mealTimeId,
                            'plan-id': planId,
                            'user-id': userId,
                            'item-qty': itemQty,
                            'initial-swap-items': JSON.stringify(selectedSwapItems.map(item => item.id))  // 👈 Stored for later comparison
                        });

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
            e.preventDefault();

            const itemId = $('#editItemForm').data('item-id');
            const mealId = $('#editItemForm').data('meal-id');
            const mealTimeId = $('#editItemForm').data('meal-time-id');
            const planId = $('#editItemForm').data('plan-id');
            const userId = $('#editItemForm').data('user-id');
            const newItemQty = $('#itemQty').val();
            const itemUnit = $('#itemUnit').val();
            const swapItemQty = $('#swapItemQty').val();
            const swapItemUnit = $('#swapItemUnit').val();
            const foodProtein = $('#editItemNutritionResult .protein').val();
            const foodCarbs = $('#editItemNutritionResult .carbs').val();
            const foodFat = $('#editItemNutritionResult .fat').val();
            const swapFoodCarbs = $('#editSwapItemNutritionResult .carbs').val();
            const swapFoodProtein = $('#editSwapItemNutritionResult .protein').val();
            const swapFoodFat = $('#editSwapItemNutritionResult .fat').val();

            // Handle selected swap items
            let selectedSwapItems = $('#swapItems').val() || [];
            if (!Array.isArray(selectedSwapItems)) {
                selectedSwapItems = [selectedSwapItems];
            }

            // Get initial values from modal data
            const initialSwapItems = JSON.parse($('#editItemForm').data('initial-swap-items') || '[]');
            const initialItemQty = $('#editItemForm').data('item-qty');

            // Compare initial and new values to detect changes
            const swapItemsChanged = JSON.stringify([...selectedSwapItems].sort()) !== JSON.stringify([...initialSwapItems].sort());
            const itemQtyChanged = newItemQty !== initialItemQty;

            $.ajax({
                url: '{{ route("admin.update-food-swap-foods") }}',
                method: 'POST',
                data: {
                    item_id: itemId,
                    swap_items: selectedSwapItems,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    item_qty: newItemQty,
                    item_unit: itemUnit,
                    swap_item_qty: swapItemQty,
                    swap_item_unit: swapItemUnit,
                    food_protein: foodProtein,
                    food_carbs: foodCarbs,
                    food_fat: foodFat,
                    swap_food_carbs: swapFoodCarbs,
                    swap_food_protein: swapFoodProtein,
                    swap_food_fat: swapFoodFat,
                    type: 'add',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        const selectedSwapFoods = response.foods;
                        const item = response.item;
                        const swapItem = response.swapItem;

                        $('#editItemModal').modal('hide');
                        updateItemSwapItemsInUI(itemId, planId, mealId, mealTimeId, selectedSwapFoods, item, swapItem);

                        calculateTotals(planId, mealTimeId, mealId);
                        calculateMealNutrition();
                        // ✅ If swap items changed, update their count
                        if (swapItemsChanged) {
                            initialSwapItems.forEach(prevItemId => updateFoodCount(prevItemId, -1)); // Decrease old swap item counts
                            selectedSwapItems.forEach(newItemId => updateFoodCount(newItemId, 1)); 
                            // updateFoodCount(itemId, +1);  // Increase new swap item counts
                        }

                        // ✅ Always update main item count
                        // if (itemQtyChanged || swapItemsChanged) {
                            // updateFoodCount(itemId, +1);
                        // }

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
        function updateItemSwapItemsInUI(itemId, planId, mealId, mealTimeId, selectedSwapFoods, item, swapItem) {
            // Find the row for the item
            const itemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            // console.log(item.items.title);
            // Update the first <td> text content with item title and quantity
            const itemTitleCell = itemRow.find('td:first'); // Select the first <td>
            // Replace the entire content of the first <td> with an input checkbox and label
            itemTitleCell.html(`
                    <div class="d-flex align-items-start">
                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" value="${itemId}" class="form-check-input me-2 d-none" data-carbs="${item.carbs !== null ? item.carbs : item.items.carbs}" data-protein="${item.protein !== null ? item.protein : item.items.protein}" data-fat="${item.fat !== null ? item.fat : item.items.fat}" checked>
                        <label class="form-check-label flex-grow-1">${item.items.title} (${item.qty !== null ? item.qty : '0'} ${item.unit !== null ? item.unit : ''})</label>
                    </div>
                    <p>Carbs: ${item.carbs !== null ? item.carbs : item.items.carbs}g | Protein: ${item.protein !== null ? item.protein : item.items.protein}g | Fat: ${item.fat !== null ? item.fat : item.items.fat}g </p>
                `);
            // Find the swap items container
            const swapItemsContainer = itemRow.find('td:nth-child(2) ul'); // Targeting the swap items list

            // Clear the existing swap items
            swapItemsContainer.empty();

            if (swapItem && swapItem.length > 0) {
                // Loop through selected swap items and append them
                swapItem.forEach(swapFood => {
                    swapItemsContainer.append(`
                        <li>
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]" value="${swapFood.swap_item_id}" class="form-check-input me-2 d-none" data-carbs="${swapFood.carbs !== null ? swapFood.carbs : swapFood.swap_item.carbs }" data-protein="${swapFood.protein !== null ? swapFood.protein : swapFood.swap_item.protein}" data-fat="${swapFood.fat !== null ? swapFood.fat : swapFood.swap_item.fat}" checked>
                                <label class="form-check-label">${swapFood.swap_item.title} (${swapFood.qty} ${swapFood.unit})</label>
                            </div>
                            <p>Carbs: ${swapFood.carbs !== null ? swapFood.carbs : swapFood.swap_item.carbs}g | Protein: ${swapFood.protein !== null ? swapFood.protein : swapFood.swap_item.protein}g | Fat: ${swapFood.fat !== null ? swapFood.fat : swapFood.swap_item.fat}g </p>
                        </li>
                    `);
                });
            } else {
                // If no swap items are selected
                swapItemsContainer.append('<span class="text-muted">No swap items available</span>');
            }
        }

        // Function to calculate totals
        function calculateTotals(planId, mealTimeId, mealId) {
            let totalCarbs = 0, totalProtein = 0, totalFat = 0;
            console.log(planId, mealTimeId, mealId);
            const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;

            // Loop through all checked food and swap items *within the specified meal container*
            $(`${mealContainerId} .form-check-input:checked`).each(function () {
                const $item = $(this);
                console.log('checkbox checked');
                const carbs = parseFloat($item.data('carbs')) || 0;
                const protein = parseFloat($item.data('protein')) || 0;
                const fat = parseFloat($item.data('fat')) || 0;

                totalCarbs += carbs;
                totalProtein += protein;
                totalFat += fat;
            });
          
            // Update displayed totals within the specific meal container
            $(`${mealContainerId} .totalCarbs`).text(totalCarbs.toFixed(2));
            $(`${mealContainerId} .totalProtein`).text(totalProtein.toFixed(2));
            $(`${mealContainerId} .totalFat`).text(totalFat.toFixed(2));
            // console.log('Calculate Total');
        }

        // Function to reset modal fields
        function resetModal(modalId) {
            $(modalId).find('input, select').val(''); // Reset all input and select fields
            $(modalId).find('select').trigger('change'); // Reset dropdowns with select2
        }

        // Reset `#swapFoodsModal` when closed
        $('#swapFoodsModal').on('hidden.bs.modal', function () {
            resetModal('#swapFoodsModal');
        });

        // Reset `#editItemModal` when closed
        $('#editItemModal').on('hidden.bs.modal', function () {
            resetModal('#editItemModal');
        });

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
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${food.id}][]" value="${swapItem.id}" class="form-check-input me-2 d-none" data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}" checked>
                                            <label>${swapItem.name} (${swapItem.qty})</label>
                                        </div>
                                        <p>Carbs: ${swapItem.carbs}g | Protein: ${swapItem.protein}g | Fat: ${swapItem.fat}g</p>
                                    </li>
                                `).join('') 
                                : '<span class="text-muted">No swap items available</span>';

                            tableBody.append(`
                                <tr data-food-id="${food.id}">
                                    <td width="45%">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" 
                                                value="${food.id}" 
                                                class="form-check-input me-2 d-none" data-carbs="${food.carbs}" data-protein="${food.protein}" data-fat="${food.fat}" checked>
                                            <label class="form-check-label flex-grow-1">${food.title} (${food.qty})</label>
                                        </div>
                                        <p>Carbs: ${food.carbs}g | Protein: ${food.protein}g | Fat: ${food.fat}g</p>
                                    </td>
                                    <td width="45%">
                                        <ul class="list-unstyled">${swapItemsHTML}</ul>
                                    </td>
                                    <td width="10%">
                                        <button class="btn btn-sm edit-food-1 btn-outline-success" 
                                                data-food-id="${food.id}" 
                                                data-meal-id="${mealId}" 
                                                data-swap-foods='${JSON.stringify(swapFoods)}',
                                                data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}" 
                                                data-swapfood-qty="${swapFoods[0]?.qty || ''}" 
                                                data-food-qty="${food.qty}">
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
            const category = $(this).data('category');
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
                    category: category,
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
                                    <td width="45%">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" value="${food.id}" class="form-check-input me-2 d-none" data-carbs="${food.carbs}" data-protein="${food.protein}" data-fat="${food.fat}" checked>
                                            <label class="form-check-label flex-grow-1">${food.title} (${food.qty})</label>
                                        </div>
                                        <p>Carbs: ${food.carbs}g | Protein: ${food.protein}g | Fat: ${food.fat}g</p>
                                    </td>
                                    <td width="45%">
                                        <ul class="list-unstyled">${swapItemsHTML}</ul> <!-- No swap items -->
                                    </td>
                                    <td width="10%">
                                        <button class="btn btn-sm btn-outline-success edit-food-1" data-food-id="${food.id}" data-meal-id="${mealId}" title="Edit"><i class="icofont-edit text-success"></i></button>
                                        <button class="btn  btn-sm btn-outline-danger delete-food" data-food-id="${food.id}" data-meal-id="${mealId}" title="Delete"><i class="icofont-ui-delete text-danger"></i></button>
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
    
</script>

@endsection
