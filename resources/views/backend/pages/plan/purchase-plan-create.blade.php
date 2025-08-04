@extends('backend.layouts.app')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
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
        .btn-group-sm>.btn,
        .btn-sm {
            padding: 2px 4px !important;
        }
        .btn-group-sm>.btn, .btn-sm
        {
            font-size: 10px !important;
        }
        .select2-selection__clear{
            display: none !important;
        }

        .toggle-arrow {
            transition: transform 0.3s ease;
        }
        .toggle-arrow.rotate {
            transform: rotate(-180deg);
        }

        /* Image styling */
        .select2-selection__rendered img {
            flex-shrink: 0;
            width: 25px;
            height: 25px;
            margin-right: 5px;
        }

        /* Text part of the selection */
        .select2-selection__rendered span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            /* display: inline-block; */
            max-width: 100%;
        }
        .food-label{
            font-weight:normal !important;
        }
        .panel-body {
            max-height: 600px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <div class="d-flex align-items-center flex-wrap">
                    <h3 class="fw-bold mb-0 me-3">{{ 'Create Plan' }}</h3>
                    <h3 class="fw-bold mb-0" style="">({{ $payment->user->name }})</h3>
                </div>
                <div class="">
                    <a href="javascript:void(0);" class="btn btn-primary btn-set-task mx-3 user-pre-plan-details" data-payment-id="{{ $payment->id }}" >View User Details</a>
                    <a href="{{ route('admin.purchase-plans.index') }}" class="btn btn-primary btn-set-task back-button">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="">
                <div class="card-body">
                <form action="{{ route('admin.purchase-plans.store') }}" method="POST" class="bg-light" id="createPlanForm">
                    @csrf
                    <input type="hidden" name="foodSelections" id="foodSelectionsInput">
                    <div class="row">
                        <div class="panel-group col-8" id="accordion">
                            
                            <!-- Main Plan -->
                            @foreach ($plans as $plan)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapsePlan{{$plan->id}}">{{ $plan->name }}</a>
                                    </h4>
                                </div>
                                <div id="collapsePlan{{$plan->id}}" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <input type="hidden" name="plan_id[]" value="{{ $plan->id }}">
                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                        <input type="hidden" name="user_id" id="user_id" value="{{ $payment->user_id }}">

                                        <!-- Meal Times (Checkboxes) -->
                                        <ul class="list-group mb-4">
                                            @foreach ($plan->categories as $mealTime)
                                            <li class="list-group-item border rounded mb-3">
                                                <!-- Meal Time Checkbox -->
                                                <div class="form-check d-flex justify-content-between align-items-center px-0">
                                                    <!-- Meal Time Checkbox and Label -->
                                                    <div class="meal-time-label">
                                                        <!-- Arrow toggle aligned to the right -->
                                                        <span class="toggle-arrow me-2" data-toggle-id="{{$plan->id}}_{{$mealTime->id}}" style="cursor: pointer;">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </span>
                                                        <input type="checkbox" 
                                                            name="meal_times[{{$plan->id}}][]" 
                                                            value="{{ $mealTime->id }}" 
                                                            class="form-check-input meal-time-checkbox hidden-checkbox" 
                                                            id="mealTime{{$plan->id}}_{{$mealTime->id}}"
                                                            data-mealtime-id="{{$mealTime->id}}">

                                                        <label class="form-check-label fw-bold" for="mealTime{{$plan->id}}_{{$mealTime->id}}">
                                                            {{ $mealTime->title }}
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="mealTimeDetailsDiv mt-2" style="display: none;">
                                                    <!-- Add Meal Dropdown (Multiple Select) -->
                                                    <div class="add-meal-dropdown mt-3" id="addMealDropdown{{$plan->id}}_{{$mealTime->id}}" style="display: none;">
                                                        <label for="mealItems{{$plan->id}}_{{$mealTime->id}}" class="form-label">Add Meal</label>
                                                        <select name="selected_meals[{{$plan->id}}][{{$mealTime->id}}][]" 
                                                                id="mealItems{{$plan->id}}_{{$mealTime->id}}" 
                                                                class="form-select meal-items-select select2 form-control " 
                                                                multiple>
                                                        </select>
                                                    </div>

                                                    <!-- Selected Meals and Swap Items -->
                                                    <div class="selected-meals mt-3" id="selectedMeals{{$plan->id}}_{{$mealTime->id}}" style="display: none;">
                                                        <!-- <h6 class="fw-bold">Selected Meals and Swap Items:</h6> -->
                                                        <ul class="list-group"></ul>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>

                                        <!-- <div class="nutrition-details">
                                            <p style="font-size: 16px; color:grey;"><strong>Plan Total: Energy: <span class="planTotalEnergy" id="allEnergyTotal">0.0kJ</span> | Protein: <span class="planTotalProtein" id="allProteinTotal">0.0g</span> | Carb: <span class="planTotalCarbs" id="allCarbsTotal">0.0g</span> | Fat: <span class="PlanTotalFat" id="allFatTotal">0.0g</span></strong></p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="col-4">
                            <div style="max-height: 90vh; overflow-y: auto; overflow-x: hidden; border: 1px solid #ddd; padding: 10px; border-radius: 8px; position: sticky; top:15px;">
                                <h4>Food Prefrences</h4>
                                <span class="">
                                    <strong>Key: </strong>
                                    <p class="mb-0" style="color: black; font-size:15px;">Athlete Preferences</p>
                                    <p class="mb-0" style="color: #7258db; font-size:15px;">Included Preferences </p> 
                                    <p class="mb-0" style="color: #198754; font-size:15px;">Recommendations</p>
                                </span>
                                <div class="category-section mb-3" id="category-section">
                                    @php
                                        $titleToIdMap = $step5Foods->pluck('id', 'title')->toArray();
                                        $preplanSlectedFoods = [];
                                    @endphp

                                    @foreach($foodPreferences as $mainQuestion => $subGroups)
                                        <h5 class="mt-4">{{ $mainQuestion }}</h5> {{-- Main category/question --}}

                                        @php
                                            $hasRenderedFlat = false; // Flag to skip repeated rendering of flat foods
                                        @endphp

                                        @foreach($subGroups as $subQuestion => $answers)
                                            @if(!empty($answers) && collect($answers)->filter()->count())

                                                @if (!is_numeric($subQuestion))
                                                    {{-- Normal sub-question --}}
                                                    @if($mainQuestion == 'Cuisines')
                                                    <h6 class="mt-3 text-muted">{{ ucFirst($subQuestion) }}</h6>
                                                    @else
                                                    <h6 class="mt-3 text-muted">{{ $subQuestion }}</h6>
                                                    @endif
                                                    <div class="row" id="category-row-{{ Str::slug($subQuestion) }}">
                                                        @php
                                                            $columns = collect($answers)->filter()->chunk(ceil(collect($answers)->filter()->count() / 2));
                                                        @endphp

                                                        @foreach ($columns as $columnFoods)
                                                            <div class="col-md-6">
                                                                @foreach ($columnFoods as $foodTitle)
                                                                    @php
                                                                        $foodId = $titleToIdMap[$foodTitle] ?? null;
                                                                        $preplanSlectedFoods[] = $foodId;
                                                                    @endphp

                                                                    <div class="form-check" id="food-wrapper-{{ $foodId }}" data-category-id="{{ $subQuestion }}">
                                                                        <input type="checkbox" name="setp5_foods[]"
                                                                            value="{{ $foodId ?? '' }}"
                                                                            class="form-check-input food-checkbox"
                                                                            id="setp5Food{{ $foodId ?? md5($foodTitle) }}"
                                                                            data-food-id="{{ $foodId ?? '' }}"
                                                                            data-food-name="{{ $foodTitle }}"
                                                                            {{ $foodId ? '' : 'disabled title="Food not found"' }}>
                                                                        <label class="form-check-label food-label" for="setp5Food{{ $foodId ?? md5($foodTitle) }}">
                                                                            {{ $foodTitle }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif (!$hasRenderedFlat)
                                                    {{-- Only render flat (numeric/null) foods once --}}
                                                    @php
                                                        $flatFoods = collect($subGroups)
                                                            ->filter(function ($_, $key) {
                                                                return is_numeric($key) || is_null($key);
                                                            })
                                                            ->flatten()
                                                            ->filter()
                                                            ->unique()
                                                            ->values();

                                                        $columns = $flatFoods->chunk(ceil($flatFoods->count() / 2));
                                                        $counter = 1;
                                                        $hasRenderedFlat = true;
                                                    @endphp

                                                    <div class="row" id="category-row-{{ Str::slug($mainQuestion) }}">
                                                        @foreach ($columns as $columnFoods)
                                                            <div class="col-md-6">
                                                                @foreach ($columnFoods as $foodTitle)
                                                                    @php
                                                                        $foodId = $titleToIdMap[$foodTitle] ?? null;
                                                                        $preplanSlectedFoods[] = $foodId;
                                                                    @endphp

                                                                    @if ($foodId)
                                                                        <div class="form-check" id="food-wrapper-{{ $foodId }}" data-category-id="{{ $counter }}">
                                                                            <input type="checkbox" name="setp5_foods[]"
                                                                                value="{{ $foodId }}"
                                                                                class="form-check-input food-checkbox"
                                                                                id="setp5Food{{ $foodId }}"
                                                                                data-food-id="{{ $foodId }}"
                                                                                data-food-name="{{ $foodTitle }}">
                                                                            <label class="form-check-label food-label" for="setp5Food{{ $foodId }}">
                                                                                {{ $foodTitle }}
                                                                            </label>
                                                                        </div>
                                                                        @php $counter++; @endphp
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                                <div class="recommendations-food-section mb-3" id="recommendations-food-section">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="mt-4">
                        <!-- <button type="submit" class="btn btn-primary">Create</button> -->
                        <button type="submit" class="btn btn-primary" name="action" value="save">Save</button>
                        <button type="submit" class="btn btn-success" name="action" value="save_exit">Save & Exit</button>
                         <!-- Button to view user profile (with user_id as data attribute) -->
                        <!-- <button type="button" class="btn btn-success view-user-profile" data-user-id="{{ $payment->user_id }}">View User Profile</button> -->
                        <a href="{{ route('front.profile', ['id' => $payment->user_id, 'admin_view' => 1]) }}"
                            target="_blank" class="btn btn-success" >View User Profile
                        </a>
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
                <h4 class="modal-title">Questionnaire</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- Save Plan Modal -->
<div class="modal" id="savePlanModal" tabindex="-1" aria-labelledby="savePlanModalLabel" aria-hidden="true">
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
                    <input type="hidden" id="description">
                    <input type="hidden" id="ratio">

                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="editItemName" readonly>
                    </div>
                    <div class="mx-3" id="dynamicQtyMeasurementContainer"></div>

                    <div class="nutrition-info mt-3 mx-3">
                        <p>
                            <strong>Energy:</strong> <span id="modalEnergy">0kJ </span>,
                            <strong>Protein:</strong> <span id="modalProtein">0g </span>,
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
                        <p>Energy: <span id="modalEnergy">0kJ</span> | Protein: <span id="modalProtein">0g</span> | Carb: <span id="modalCarbs">0g</span> | Fat: <span id="modalFat">0g</span></p>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" id="editMainItemId">
                    <input type="hidden" id="editSwapItemId">
                    <input type="hidden" id="editSwapPlanId">
                    <input type="hidden" id="editSwapMealTimeId">
                    <input type="hidden" id="editSwapMealId">
                    <input type="hidden" id="previousSwapItemId">
                    <input type="hidden" id="description">
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
                        <p>Energy: <span id="modalEnergy">0kJ</span> | Protein: <span id="modalProtein">0g</span> | Carb: <span id="modalCarbs">0g</span> | Fat: <span id="modalFat">0g</span></p>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" id="refItemId">
                    <input type="hidden" id="swapItemId">
                    <input type="hidden" id="swapPlanId">
                    <input type="hidden" id="swapMealTimeId">
                    <input type="hidden" id="swapMealId">
                    <input type="hidden" id="description">
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
                        <strong>Energy:</strong> <span id="modalEnergy">0kJ</span> |
                        <strong>Protein:</strong> <span id="modalProtein">0g</span> |
                        <strong>Carb:</strong> <span id="modalCarbs">0g</span> |
                        <strong>Fat:</strong> <span id="modalFat">0g</span>
                    </div>
                    <input type="hidden" id="itemId">
                    <input type="hidden" id="foodPlanId">
                    <input type="hidden" id="foodMealTimeId">
                    <input type="hidden" id="foodMealId">
                    <input type="hidden" id="foodUserId">
                    <input type="hidden" id="description">
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
                    <input type="hidden" name="food_energy" id="foodEnergy" value="">
                   <input type="hidden" name="description" id="description" value="">
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
                        <strong>Energy:</strong> <span id="modalEnergy">0kJ</span> |
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
<!-- Add more swap items modal -->
<div class="modal" id="addMoreSwapItemModal" tabindex="-1">
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
                        <label for="moreSwapFoodDropdown" class="form-label">Select Swap Food</label>
                        <select id="moreSwapFoodDropdown" class="form-select mb-3">
                        </select>
                    </div>

                    <div class="mb-3 mx-3" id="dynamicQtyMeasurementContainer">
                    </div>

                    <div class="mb-3 mt-3 mx-3">
                        <label class="form-label">Nutrition Info</label>
                        <p>Energy: <span id="modalEnergy">0kJ</span> | Protein: <span id="modalProtein">0g</span> | Carb: <span id="modalCarbs">0g</span> | Fat: <span id="modalFat">0g</span></p>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" id="refItemId">
                    <input type="hidden" id="swapItemId">
                    <input type="hidden" id="swapPlanId">
                    <input type="hidden" id="swapMealTimeId">
                    <input type="hidden" id="swapMealId">
                    <input type="hidden" id="description">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveMoreSwapItem">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup -->
<div class="modal" id="searchFoodModal" tabindex="-1" aria-labelledby="searchFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen modal-lg">
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

<!-- Info Modal -->
<div class="modal" id="itemInfoModal" tabindex="-1" role="dialog" aria-labelledby="itemInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemInfoModalLabel">Item Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Info: </strong> <span id="modalDescription"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Main Item Delete Modal -->
<div class="modal" id="deleteMainItemModal" tabindex="-1" aria-labelledby="deleteMainItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete this item and its swap items?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmDeleteMainItem" type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Swap Item Delete Modal -->
<div class="modal " id="deleteSwapItemModal" tabindex="-1" aria-labelledby="deleteSwapItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Swap Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete this swap item?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmDeleteSwapItem" type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<div id="loader-2" style="display: none;">
    <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="Loading..." />
</div>

<!-- jQuery CDN -->
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

@push('custom_scripts')
<script>

    const preSelectedFoods = @json($preplanSlectedFoods);
    
    document.addEventListener('DOMContentLoaded', function () {
        let hasUnsavedChanges = false;
        let intendedHref = ''; // Store the intended link URL
        let intendedModalAction = null; // Store the intended modal action
        let intendedProfileAction = null; // Store the intended profile action

        // Track changes in form fields
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('input', () => {
                hasUnsavedChanges = true;
            });
        });

        // Handle View User Profile button
        $(document).on('click', '.view-user-profile', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (hasUnsavedChanges) {
                intendedProfileAction = this;
                const modalInstance = new bootstrap.Modal(document.getElementById('savePlanModal'));
                modalInstance.show();
                return false;
            }else {
                // If no unsaved changes, proceed with normal profile view
                const userId = $(this).data('user-id');
                const sessionUrl = "{{ route('front.set-user-session', ':id') }}".replace(':id', userId);
                
                $.ajax({
                    url: sessionUrl,
                    method: 'GET',
                    success: function(response) {
                        if (response.redirect_url) {
                            window.open(response.redirect_url, '_blank');
                        } else {
                            alert('Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        alert('Error setting user session.');
                    }
                });
            }
        });

        // Handle View User Details button
        document.querySelectorAll('.user-pre-plan-details').forEach(button => {
            button.addEventListener('click', function(event) {
                if (hasUnsavedChanges) {
                    event.preventDefault();
                    event.stopPropagation();
                    intendedModalAction = this;
                    const modalInstance = new bootstrap.Modal(document.getElementById('savePlanModal'));
                    modalInstance.show();
                }
            });
        });

        // Handle other navigation links
        document.querySelectorAll('a:not(.user-pre-plan-details)').forEach(anchor => {
            anchor.addEventListener('click', function(event) {
                if (hasUnsavedChanges) {
                    event.preventDefault();
                    intendedHref = this.href;
                    const modalInstance = new bootstrap.Modal(document.getElementById('savePlanModal'));
                    modalInstance.show();
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
            document.getElementById('createPlanForm').submit();
            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('savePlanModal'));
            modalInstance.hide();

            // Handle navigation after saving
            if (intendedHref) {
                window.location.href = intendedHref;
                intendedHref = '';
            }

            // Handle profile action after saving
            if (intendedProfileAction) {
                const userId = intendedProfileAction.getAttribute('data-user-id');
                const sessionUrl = "{{ route('front.set-user-session', ':id') }}".replace(':id', userId);
                
                $.ajax({
                    url: sessionUrl,
                    method: 'GET',
                    success: function(response) {
                        if (response.redirect_url) {
                            window.open(response.redirect_url, '_blank');
                        } else {
                            alert('Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        alert('Error setting user session.');
                    }
                });
                intendedProfileAction = null;
            }

            // Handle modal action after saving
            if (intendedModalAction) {
                const paymentId = intendedModalAction.getAttribute('data-payment-id');
                $.ajax({
                    url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Your existing modal content code here
                            // ... (keep the existing modal content code)
                        }
                    }
                });
                intendedModalAction = null;
            }
        });

        // Modal Button: "No Leave"
        document.getElementById('leaveWithoutSaving').addEventListener('click', function() {
            hasUnsavedChanges = false;
            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('savePlanModal'));
            modalInstance.hide();

            // Handle navigation without saving
            if (intendedHref) {
                window.location.href = intendedHref;
                intendedHref = '';
            }

            // Handle modal action without saving
            if (intendedModalAction) {
                const paymentId = intendedModalAction.getAttribute('data-payment-id');
                $.ajax({
                    url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Your existing modal content code here
                            // ... (keep the existing modal content code)
                        }
                    }
                });
                intendedModalAction = null;
            }
        });

        // Form submit bypasses the unsaved warning
        document.getElementById('createPlanForm').addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });
    });

    $(document).ready(function () {
        $('#meals').select2({
            placeholder: "Select Meals",
            allowClear: true,
            width: '100%'
        });

        $('.meal-items-select').select2({
            placeholder: "Select Mels",
            allowClear: true,
            width: 100
        })

    });

    $(document).on('blur', '.modalQtyInput', function () {
        let value = parseFloat($(this).val());

        if (isNaN(value) || value <= 0) {
            // Show warning or set to default (optional)
            alert('Please enter a value greater than 0.');
            $(this).val(''); // or set to '1' or some default
        }
    });

    $(document).ready(function () {
        // Use event delegation to handle dynamically added elements
        $(document).on('click', '.user-pre-plan-details', function () {
            const paymentId = $(this).data('payment-id');
            
            $.ajax({
                url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                method: 'GET',

                success: function(response) {
                    if (response.success) {
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
                                            <p><strong>Sport:</strong> ${userDetails.occupation || 'N/A'}</p>
                                        </div>
                                    </div>
                                </div><hr>`;
                        }

                        const formData = response.data;

                        const foodGroups = response.foodGroups || {}; // assuming this comes from AJAX response

                        Object.keys(formData).forEach(function (formName) {
                            if (formName === 'Personal Details') return;

                            modalContent += `<div><h4 style="color:#7258db;">${formName}</h4><hr>`;
                            const formQuestions = formData[formName];

                            Object.keys(formQuestions).forEach(function (question) {
                                let answer = formQuestions[question];
                                let answerContent = '';

                                // === FOOD PREFERENCE HANDLING ===
                                if (formName === 'Food Preference') {
                                    const expectedGroups = foodGroups;
                                    const groupNameRaw = question;

                                    const clean = s => (s || '').toString().trim();
                                    const normal = s => clean(s).replace(/\s{2,}/g, ' ');

                                    const groupKey = normal(groupNameRaw);
                                    const expectedSubs = expectedGroups[groupKey] || [];
                                    const userValue = answer;

                                    answerContent = '<ul>';

                                    if (userValue === null || userValue === undefined) {
                                        answerContent += `<li class="text-danger">Not selected</li>`;
                                    } else {
                                        if (Array.isArray(userValue)) {
                                            const nonEmpty = userValue.filter(x => clean(x));
                                            if (nonEmpty.length) {
                                                nonEmpty.forEach(item => {
                                                    answerContent += `<li>${item}</li>`;
                                                });
                                            } else {
                                                answerContent += `<li class="text-danger">Not selected</li>`;
                                            }
                                        } else if (typeof userValue === 'object') {
                                            expectedSubs.forEach(sub => {
                                                const subKey = normal(sub);
                                                const val = userValue[subKey];
                                                if (Array.isArray(val)) {
                                                    const cleanItems = val.filter(x => x && x !== 'null' && x !== null);
                                                    if (cleanItems.length) {
                                                        answerContent += `<li><strong>${subKey}</strong><ul>${cleanItems.map(v => `<li>${v}</li>`).join('')}</ul></li>`;
                                                    }
                                                } else if (typeof val === 'string' && clean(val) !== '' && val !== 'null') {
                                                    answerContent += `<li><strong>${subKey}:</strong> ${val}</li>`;
                                                } else {
                                                    answerContent += `<li class="text-danger">${subKey} — Not selected</li>`;
                                                }
                                            });
                                        } else {
                                            answerContent += `<li>${clean(userValue)}</li>`;
                                        }
                                    }

                                    answerContent += '</ul>';

                                    modalContent += `
                                        <div>
                                            <p><strong>Q : ${groupNameRaw}</strong></p>
                                            <div>${answerContent}</div>
                                        </div>`;
                                    return; // skip to next question
                                }

                                // === DEFAULT LOGIC FOR OTHER FORMS ===
                                if (!answer) {
                                    answerContent = '<span class="text-danger">Not selected</span>';
                                } else if (Array.isArray(answer)) {
                                    const filtered = answer.filter(item => item);
                                    if (filtered.length) {
                                        answerContent = '<ul>' + filtered.map(i => `<li>${i}</li>`).join('') + '</ul>';
                                    }
                                } else if (typeof answer === 'object') {
                                    // special case: { answer: 'Yes', date: '3 months ago' }
                                    if ('answer' in answer && 'date' in answer) {
                                        answerContent = `
                                            <ul>
                                                <li><strong>Answer:</strong> ${answer.answer}</li>
                                                <li><strong>Date:</strong> ${answer.date}</li>
                                            </ul>`;
                                    } else {
                                        let valid = Object.entries(answer).filter(([_, v]) => v);
                                        if (question.includes('hunger/appetite over the day')) {
                                            const order = ['breakfast', 'morning_tea', 'lunch', 'afternoon_tea', 'dinner', 'dessert'];
                                            valid.sort((a, b) => order.indexOf(a[0]) - order.indexOf(b[0]));
                                        }
                                        if (valid.length) {
                                            answerContent = '<ul>';
                                            valid.forEach(([k, v]) => {
                                                const keyLabel = k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                                if (Array.isArray(v)) {
                                                    const sub = v.filter(x => x);
                                                    if (sub.length) {
                                                        answerContent += `<li><strong>${keyLabel}</strong><ul>${sub.map(s => `<li>${s}</li>`).join('')}</ul></li>`;
                                                    }
                                                } else {
                                                    answerContent += `<li><strong>${keyLabel}:</strong> ${v}</li>`;
                                                }
                                            });
                                            answerContent += '</ul>';
                                        }
                                    }
                                } else {
                                    answerContent = answer;
                                }

                                // Append question + answer block
                                if (answerContent && question) {
                                    modalContent += `
                                        <div>
                                            <p><strong>Q : ${question}</strong></p>
                                            <p>${answerContent}</p>
                                        </div>`;
                                }
                            });

                            modalContent += '</div><hr>';
                        });

                        // Set the content inside the modal
                        $('#prePlanDetail .modal-body').html(modalContent);

                        // Show the modal
                        $('#prePlanDetail').modal('show');
                    } else {
                        if (!response.data) {
                            alert('Pre plan details not available.');
                        } else {
                            alert('Failed to load the data');
                        }
                    }
                },
                error: function () {
                    alert('An error occurred while fetching the data.');
                }
            });
        });

        // $('button[name="action"][value="view"]').on('click', function(e) {
        //     e.preventDefault();

        //     var user_id = $(this).data('user-id');  // Assume you set a data attribute with the user's ID on the button
        //     var payment_id = $(this).data('payment-id');  // Assume you set a data attribute with the user's ID on the button

        //     $.ajax({
        //         url: '{{ route("admin.handle-plan-action") }}',  // URL to your controller method for storing the form
        //         method: 'POST',
        //         data: {
        //             action: 'view',
        //             user_id: user_id,
        //             payment_id : payment_id,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             if (response.status === 'success') {
        //                 window.open(response.redirect_url, '_blank');

        //                 // window.location.href = response.redirect_url;  // Redirect to user profile page
        //             } else {
        //                 alert('Error: ' + response.message);
        //             }
        //         },
        //         error: function(xhr) {
        //             alert('Something went wrong!');
        //         }
        //     });
        // });

        // Handle the "Send" button click (Send meal plan)
        $('button[name="action"][value="send"]').on('click', function(e) {
            e.preventDefault();

            var user_id = $(this).data('user-id');  // Assume you set a data attribute with the user's ID on the button
            var payment_id = $(this).data('payment-id');  // Assume you set a data attribute with the user's ID on the button

            $.ajax({
                url: '{{ route("admin.handle-plan-action") }}',  // URL to your controller method for storing the form
                method: 'POST',
                data: {
                    action: 'send',
                    user_id: user_id,
                    payment_id : payment_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);  // Show success message
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong!');
                }
            });
        });
    });

    $(document).ready(function () {
        const loading = $('#loader-2');
        function calculateNutrition(data, resultDiv) {
            $.ajax({
                url: "{{ route('calculate.nutrition') }}",
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data) {
                        setTimeout(() => {
                            resultDiv.find('.protein').val(data.protein);
                            resultDiv.find('.carbs').val(data.carbs);
                            resultDiv.find('.fat').val(data.fat);
                            resultDiv.find('.serving_size').val(data.serving_size);
                            resultDiv.find('.serving_size_unit').val(data.serving_size_unit);
                            resultDiv.find('.serving_per_pack').val(data.serving_per_pack);
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
       
        // Ensure modals reset on close
        $('#swapFoodsModal, #editItemModal').on('hidden.bs.modal', function () {
            $(this).find('input').val(''); // Reset all input fields
            $(this).find('select').val(''); // Reset all select fields
            $(this).find('.error').hide(); // Hide error messages
        });

        $('#swapFoodsModal').on('hidden.bs.modal', function () {
            resetModal();
        });

        function resetModal() {
            $('#swapFoodsModal').find('input').val('');
            $('#swapFoodsModal').find('select').val('').trigger('change');
            $('#swapFoodsModal').find('.error').hide();
        }
    });

    $(document).on('click', '.toggle-arrow', function (e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent bubbling to checkbox label or container

        const toggleId = $(this).data('toggle-id');
        const container = $(`#addMealDropdown${toggleId}`).closest('.mealTimeDetailsDiv');

        container.slideToggle(200); // Toggle visibility with slide effect

        // Optional: Rotate the arrow
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });

    $(document).ready(function () {
        function getPercentageChange(id) {
            var data = window.mesureofnewaddedfood && window.mesureofnewaddedfood[id] ? window.mesureofnewaddedfood[id] : null;
            if (data && typeof data[0] !== 'undefined' && typeof data[0].old !== 'undefined' && 
                typeof data[1] !== 'undefined' && typeof data[1].new !== 'undefined') {
                var oldData = data[0].old;
                var newData = data[1].new;

                for (var i = 0; i < oldData.length; i++) {
                    if (oldData[i].checked) {
                        var newItem = newData.find(item => item.unit === oldData[i].unit && item.checked);
                        if (newItem && oldData[i].qty !== newItem.qty) {
                            var oldQty = oldData[i].qty;
                            var newQty = newItem.qty;
                            var ratio = newQty / oldQty;
                            return ratio.toFixed(2); // Return ratio with 2 decimal places
                        }
                    }
                }
                return 1; // Return 1 if no change is found (ratio of 1 means no change)
            }
            return null; // Return null if data is unavailable
        }

        const previouslySelectedMeals = {};

        // Step 1: Handle meal time checkbox changes
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

            const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
            const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;

            const mealSelect = $(`#mealItems${planId}_${mealTimeId}`);

            if (checkbox.is(':checked')) {
                $(dropdownId).show(); // Show the dropdown
                $(selectedMealsId).show(); 
                initializeSelect2(mealSelect, mealTimeId); // Initialize Select2 with AJAX
            } else {
                $(dropdownId).hide();
                mealSelect.val(null).trigger('change'); // Clear selection
                $(selectedMealsId).hide();     // Hide selected meals

            }
            calculateMealNutrition();
        });

        function initializeSelect2(mealSelect, mealTimeId) {
            mealSelect.select2({
                placeholder: 'Search meals…',
                allowClear: true,

                /* 1️⃣  AJAX config – we pass ALL the fields we'll need later */
                ajax: {
                    url: '{{ route("admin.get-meals-by-mealtime") }}',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        meal_time_id: mealTimeId,
                        user_id: userId,
                        search: params.term ?? '',
                        _token: '{{ csrf_token() }}'
                    }),
                    processResults: data => ({
                        results: data.meals.map(m => ({
                            id     : m.id,
                            text   : m.name,      // keeps keyboard navigation working
                            image  : m.image,
                            energy : m.energy,    // kJ
                            protein: m.protein,   // g
                            carbs  : m.carbs,     // g
                            fat    : m.fat        // g
                        }))
                    }),
                    cache: true
                },

                /* 2️⃣  Tell Select2 how to render each piece of data */
                templateResult   : formatMeal,        // dropdown rows
                templateSelection: sel => sel.text,   // text after user picks
                escapeMarkup     : m => m             // DON'T auto‑escape → allow our HTML
            });
        }

        /* Renders one row inside the dropdown */
        function formatMeal(meal) {
            if (meal.loading) return meal.text;      // built‑in "loading…" row

            const img   = meal.image
                ? `<img src="${meal.image}" class="s2-meal-img me-2" style="width: 30px; height: 30px;">`
                : '';
            const macro = (val, label) => val != null
                ? `<span class="me-1">${label}:${Number(val).toFixed(1)}${label === 'Energy' ? 'kJ' : 'g'}</span>`
                : '';

            return `
                <div class="d-flex align-items-start">
                    ${img}
                    <div>
                        <div>${meal.text}</div>
                        <small class="text-muted">
                            ${macro(meal.energy, 'Energy')}
                            ${macro(meal.protein,'Protein')}
                            ${macro(meal.carbs , 'Carbs')}
                            ${macro(meal.fat   , 'Fat')}
                        </small>
                    </div>
                </div>
            `;
        }
        
        // Function to calculate nutrition
        function calculateMealNutrition() {
            let grandTotalCarbs = 0;
            let grandTotalProtein = 0;
            let grandTotalFat = 0;
            let grandTotalEnergy = 0;

            $('.meal-container').each(function () {
                let totalCarbs = 0;
                let totalProtein = 0;
                let totalFat = 0;
                let totalEnergy = 0;

                $(this).find('.items-table-body tr').each(function () {
                    const $firstTd = $(this).find('td').eq(1);
                    const $input = $firstTd.find('input');

                    totalCarbs += parseFloat($input.data('carbs')) || 0;
                    totalProtein += parseFloat($input.data('protein')) || 0;
                    totalFat += parseFloat($input.data('fat')) || 0;
                    totalEnergy += parseFloat($input.data('energy')) || 0;
                });

                $(this).data({
                    totalCarbs: totalCarbs.toFixed(1),
                    totalProtein: totalProtein.toFixed(1),
                    totalFat: totalFat.toFixed(1),
                    totalEnergy: totalEnergy.toFixed(1)
                });

                grandTotalCarbs += totalCarbs;
                grandTotalProtein += totalProtein;
                grandTotalFat += totalFat;
                grandTotalEnergy += totalEnergy;

            });

            $('#allCarbsTotal').text(`${Math.round(grandTotalCarbs)}g`);
            $('#allProteinTotal').text(`${Math.round(grandTotalProtein)}g`);
            $('#allFatTotal').text(`${Math.round(grandTotalFat)}g`);
            $('#allEnergyTotal').text(`${Math.round(grandTotalEnergy)}kJ`);
        }

        let userId = $('#user_id').val();
        
        let planID = 0;
        let mealtimeID = 0;
        // Step 2: Handle meal selection changes
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

            // Remove unselected meals
            unselectedMeals.forEach(mealId => {
                // $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
                const removedMealContainer = $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`);
                if (removedMealContainer.length) {
                    // Decrement item and swap item counts
                    removedMealContainer.find('input[name^="items"]').each(function() {
                        const itemId = $(this).val();
                        updateFoodCount(itemId, -1, null); // Decrease item count
                    });

                    $.ajax({
                        url: '{{ route("admin.remove-user-meal") }}',
                        method: 'POST',
                        data: {
                            user_id: userId,
                            meal_id: mealId,
                            plan_id: planId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response.message || 'Meal removed successfully');
                        },
                        error: function(xhr) {
                            alert('Failed to remove meal. Please try again.');
                        }
                    });
                    
                    // Remove the meal container
                    removedMealContainer.remove();
                    // Trigger meal count update
                    $(document).trigger('mealRemoved', [planId, mealTimeId]);
                    calculateMealNutrition();
                }
            });

            // Fetch and add new meals
            newMeals.forEach(mealId => {
                
                if ($(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).length) return;
                $.ajax({
                    url: '{{ route("admin.get-meal-items") }}',
                    method: 'POST',
                    data: {
                        meal_id: mealId,
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
                                response.meal_note,
                                items,
                                userId,
                                response.total_carbs,
                                response.total_fat,
                                response.total_protein,
                                response.total_energy
                            );

                            selectedMealsContainer.append(mealContainer);
                            
                            calculateMealNutrition();
                            response.data.forEach(item => {
                                // ✅ Increment count for each item in the meal
                                updateFoodCount(item.id, 1, 'purple', item.name, null);  
                            });
                        } else {
                            alert('Failed to fetch meal details.');
                        }
                    },
                    error: function () {
                        alert('Error while fetching meal details.');
                    }
                });
            });
        });

        const formattedQty = (qty, unit) => {
            const noSpaceUnits = ['g', 'ml', 'mL'];
            const space = noSpaceUnits.includes(unit) ? '' : ' ';

            if (typeof qty === 'string' && qty.includes('/')) {
                return `${qty}${space}${unit}`;
            }

            let parsedQty = parseFloat(qty);

            // Round for noSpace units
            if (noSpaceUnits.includes(unit)) {
                parsedQty = Math.round(parsedQty);
            }

            // Remove trailing ".0" for all units
            const displayQty = Number.isInteger(parsedQty) ? parsedQty : parsedQty;

            return `${displayQty}${space}${unit}`;
        };


        function createMealContainer(planId, mealTimeId, mealId, mealName,mealNote, items, userId, totalCarbs, totalFat, totalProtein, totalEnergy) {
            let mealContainer = $(`
                <div id="mealContainer_${planId}_${mealTimeId}_${mealId}" class="meal-container mt-3">
                    <input type="hidden" name="meals[${planId}][${mealTimeId}][]" value="${mealId}">
                    <div class="meal-name-edit d-flex justify-content-between align-items-center toggle-meal-content" style="cursor: pointer;">
                        <i class="toggle-arrow icofont-simple-down ms-2 me-2"></i>
                        <input type="text"
                            value="${mealName}"
                            class="editable-meal-name form-control border-0 shadow-none p-0"
                            data-meal-time-id="${mealTimeId}"
                            data-meal-id="${mealId}"
                            data-plan-id="${planId}"
                            data-user-id="${userId}"
                            style="font-weight: bold; font-size: 14px; color: #6610f2; width: 100%;" />
                    </div>
                    <p class="mb-2 meal-total-bar" style="font-size: 14px; color:grey;"><strong>
                        Meal Total: Energy: <span class="totalEnergy">${(totalEnergy)}kJ</span> | 
                        Protein: <span class="totalProtein">${Math.round(totalProtein)}g</span> | 
                        Carb: <span class="totalCarbs">${Math.round(totalCarbs)}g</span> | 
                        Fat: <span class="totalFat">${Math.round(totalFat)}g</span>
                        </strong>
                    </p>
                    <div class="meal-content">
                        <small class="text-muted"> NOTE: ${mealNote ?? 'Nil'}</small>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
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
                        
                    </div>
                </div>
            `);

            const tableBody = mealContainer.find('.items-table-body');

            // Populate items and swap items
            items.forEach(item => {
                
                let swapItemsHTML = '';
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

                const checkedQtyUnits = selectedQtyUnits.filter(q => q.checked === true || q.checked === "true");

               const qtyUnitDisplay = checkedQtyUnits.length
                ? `(${checkedQtyUnits.map(q => formattedQty(q.qty, q.unit)).join(' or ')})`
                : `(${formattedQty(item.qty, item.unit)})`;

                if (item.swapItems && item.swapItems.length > 0) {
                    swapItemsHTML = item.swapItems.map(swapItem => {
                        let selectedQtyUnits = [];
                        try {
                            selectedQtyUnits = typeof swapItem.selected_qty_unit === 'string'
                                ? JSON.parse(swapItem.selected_qty_unit)
                                : (Array.isArray(swapItem.selected_qty_unit) ? swapItem.selected_qty_unit : []);
                        } catch (e) {
                            console.warn('Invalid JSON in swapItem.selected_qty_unit:', swapItem.selected_qty_unit, e);
                        }

                        const checkedQtyUnitSwapItems = selectedQtyUnits.filter(q => q.checked === true || q.checked === "true");

                        const swapItemQtyUnitDisplay = checkedQtyUnitSwapItems.length
                        ? `(${checkedQtyUnitSwapItems.map(q => formattedQty(q.qty, q.unit)).join(' or ')})`
                        : `(${formattedQty(swapItem.qty, swapItem.unit)})`;

                        return `
                            <li class="list-unstyled mb-3" data-swap-item-id="${swapItem.id}">
                                <div class="d-flex justify-content-between align-items-start mb-0">
                                    <div class="col-9">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]"
                                                value="${swapItem.id}" class="form-check-input me-2 d-none" data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}" data-energy="${swapItem.energy}" checked>
                                            <label class="form-check-label">${swapItem.name}</label>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                            data-swap-item-id="${swapItem.id}" data-swapItem-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-description="${swapItem.description}"  data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="${swapItem.description}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-0"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swap-qty="${swapItem.qty}" data-swap-unit="${swapItem.unit}"
                                            data-selected-qty-unit='${JSON.stringify(swapItem.selected_qty_unit)}'
                                            title="Edit"><i class="icofont-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Delete">
                                            <i class="icofont-ui-delete"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="px-2 mb-2 fw-bold">${swapItemQtyUnitDisplay}</p>
                                        <p class="mb-0 px-2">Energy: ${parseFloat(swapItem.energy ?? 0)}kJ | Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                    </div>
                                </div>
                            </li>
                        `;
                    }).join('');

                    swapItemsHTML += `
                        <li class="d-flex justify-content-between align-items-start mt-1">
                            <div class="col-9"></div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-more-swap-item ms-2"
                                    data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                    data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                    title="Add More"><i class="icofont-plus"></i></button>
                            </div>
                        </li>`;
                } else {
                    swapItemsHTML = `<li class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="col-9">
                                            <span class="text-muted">No swap items available</span>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                                title="Add"><i class="icofont-plus"></i>
                                            </button>
                                        </div>
                                    </li>`;
                }

                // Append a row for the item and its swap items
                tableBody.append(`
                    <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${item.id}" data-item-id="${item.id}">
                        <td width="30" class="align-middle text-center">
                            <span class="drag-handle" style="cursor:move; margin-right:8px;"><i class="fa fa-bars"></i></span>
                        </td>
                        <td class="text-wrap" width="50%">
                            <div class="d-flex justify-content-between align-items-start mb-0">
                                <div class="col-9">
                                    <div class="d-flex align-items-start">
                                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                            value="${item.id}" class="form-check-input me-2 d-none" checked
                                            data-carbs="${item.carbs}" data-protein="${item.protein}" data-fat="${item.fat}" data-energy="${parseFloat(item.energy ?? 0)}">
                                        <label class="form-check-label flex-grow-1">${item.name}</label>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                        data-swap-item-id="${item.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-description="${item.description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${item.description}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${item.qty}" data-item-unit="${item.unit}" 
                                        data-selected-qty-unit='${item.selected_qty_unit}'
                                        title="Edit"><i class="icofont-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swapfood-id="${swapsFoods[0]?.swap_item_id || ''}"  
                                        title="Delete"><i class="icofont-ui-delete"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="px-2 mb-2 fw-bold">${qtyUnitDisplay}</p>
                                    <p class="px-2 mb-0">Energy: ${parseFloat((item.energy ?? 0))}kJ | Protein: ${Math.round(item.protein)}g | Carb: ${Math.round(item.carbs)}g | Fat: ${Math.round(item.fat)}g</p>
                                </div>
                            </div>
                        </td>
                        <td width="50%">
                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                        </td>
                    </tr>
                `);
            });

            // Get the DOM element for the table body
            const tableBodyEl = tableBody[0];

            // Destroy previous Sortable instance if any (to avoid duplicates)
            if (tableBodyEl._sortable) {
                tableBodyEl._sortable.destroy();
            }

            // Initialize SortableJS
            tableBodyEl._sortable = Sortable.create(tableBodyEl, {
                animation: 150,
                handle: '.drag-handle', // Only allow dragging by the handle
                onEnd: function (evt) {
                    // Get the new order of item IDs
                    const newOrder = [];
                    $(tableBodyEl).find('tr').each(function () {
                        newOrder.push($(this).data('item-id'));
                    });
                    // Store newOrder as needed (e.g., in a hidden input or JS variable)
                    // Example: window.currentItemOrder = newOrder;
                  
                }
            });

            $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();

            // Return the constructed meal container
            return mealContainer;

        }

        $(document).on('click', '.toggle-arrow', function (e) {
            // Ensure we're only toggling when the arrow is clicked
            e.preventDefault();

            // Get the closest meal container
            const container = $(this).closest('.meal-container');

            // Find the meal content div
            const content = container.find('.meal-content');

            // Toggle the content visibility with a slide effect
            content.slideToggle(200);

            // Rotate the arrow
            $(this).toggleClass('rotate');
        });
      
        $(document).on('change','.food-checkbox', function () {
            const foodId = $(this).data('food-id');
            const foodName = $(this).data('food-name');

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
                        const energy = parseFloat(foodItem.energy) ? (parseFloat(foodItem.energy) * 10) / 10 : 0;

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
                        $('#mealFoodAddModal #foodEnergy').val(foodItem.energy || 0);
                        $('#mealFoodAddModal #modalCarbs').text((carb || 0) + 'g');
                        $('#mealFoodAddModal #modalProtein').text((protein || 0) + 'g');
                        $('#mealFoodAddModal #modalFat').text((fat || 0) + 'g');
                        $('#mealFoodAddModal #modalEnergy').text((energy || 0) + 'kJ');
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
                            setupNutritionSync(foodItem.carbs, foodItem.protein, foodItem.fat, parseFloat(foodItem.energy || 0), '#mealFoodAddModal');
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
            const energy = parseFloat(modal.find('#foodEnergy').val()) || 0;

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

                    // Round qty if unit is g, ml, or mL
                    if (["g", "ml", "mL"].includes(unit.toLowerCase())) {
                        qtyToUse = Math.round(parsedQty).toString();
                    } else if (parsedQty % 1 === 0) {
                        // If unit is not weight/volume but qty is whole number, use parsed number string
                        qtyToUse = parsedQty.toString();
                    }

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        // For checked units, add formatted string with or without space
                        checkedQtyUnits.push(qtyToUse + (["g", "ml", "mL"].includes(unit.toLowerCase()) ? unit : ' ' + unit));
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
                    energy: energy,
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
                    meals.forEach(mealId => {
                        const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
                        const tableBody = $(mealContainerId).find('.items-table-body');

                        if (swapFoods.length > 0) {
                            swapItemsHTML = swapFoods.map(swapItem => {
                                const checkedQtyText = getQtyDisplay(
                                    swapItem.selected_qty_unit || [],
                                    swapItem.qty,
                                    swapItem.unit
                                );

                                return `
                                    <li class="list-unstyled mb-3" data-swap-item-id="${swapItem.id}">
                                        <div class="d-flex justify-content-between align-items-start mb-0"> 
                                            <div class="col-9">
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${item.id}][]"
                                                        value="${swapItem.id}" class="form-check-input me-2 d-none" checked
                                                        data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}" data-energy="${parseFloat(swapItem.energy)}">
                                                    <label class="form-check-label">${swapItem.title}</label>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                                    data-swap-item-id="${swapItem.id}" data-item-id="${swapItem.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                    data-meal-time-id="${mealtimeID}" data-user-id="${userId}" data-description="${swapItem.description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${swapItem.description}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-0"
                                                    data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                    data-meal-time-id="${mealtimeID}" data-user-id="${userId}" data-swap-qty="${swapItem.qty}" data-swap-unit="${swapItem.unit}"
                                                    data-selected-qty-unit='${JSON.stringify(swapItem.selected_qty_unit)}'
                                                    title="Edit"><i class="icofont-edit"></i></button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                                    data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                    data-meal-time-id="${mealtimeID}" data-user-id="${userId}" title="Delete">
                                                    <i class="icofont-ui-delete"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="px-2 mb-2 fw-bold">${checkedQtyText}</p>
                                                <p class="mb-0 px-2">Energy: ${parseFloat(swapItem.energy)}kJ | Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                            </div>
                                        </div>
                                    </li>
                                `;
                            }).join('');

                            swapItemsHTML += `
                                <li class="d-flex justify-content-between align-items-start mt-1">
                                    <div class="col-9"></div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-more-swap-item ms-2"
                                            data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                            data-meal-time-id="${mealtimeID}" data-user-id="${userId}" 
                                            title="Add More"><i class="icofont-plus"></i></button>
                                    </div>
                                </li>`;

                            // swapFoods.map(swapItem =>
                            //     updateFoodCount(swapItem.id, 1, 'green')
                            // );
                        } else {
                            swapItemsHTML = `
                                <li class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="col-9"><span class="text-muted">No swap items available</span></div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                            data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                            data-meal-time-id="${mealtimeID}" data-user-id="${userId}" title="Add">
                                            <i class="icofont-plus"></i>
                                        </button>
                                    </div>
                                </li>`;
                        }
                        const rowHTML = `
                            <tr id="itemRow_${planID}_${mealtimeID}_${mealId}_${item.id}" data-item-id="${item.id}">
                                <td width="30" class="align-middle text-center">
                                    <span class="drag-handle" style="cursor:move; margin-right:8px;"><i class="fa fa-bars"></i></span>
                                </td>
                                <td class="text-wrap" width="50%">
                                    <div class="d-flex justify-content-between align-items-start mb-0">
                                        <div class="col-9">
                                            <div class="d-flex align-items-start">
                                                <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]"
                                                    value="${item.id}" class="form-check-input me-2 d-none"
                                                    data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" data-energy="${energy}" checked>
                                                <label class="form-check-label flex-grow-1">${item.title}</label>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                                data-swap-item-id="${item.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                data-meal-time-id="${mealtimeID}" data-user-id="${userId}" data-description="${item.description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${item.description}">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                data-meal-time-id="${mealtimeID}" data-user-id="${userId}" data-item-qty="${qty}" data-item-unit="${unit}"
                                                data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                                title="Edit"><i class="icofont-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                                data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planID}"
                                                data-meal-time-id="${mealtimeID}" data-user-id="${userId}"
                                                title="Delete"><i class="icofont-ui-delete"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="px-2 mb-2 fw-bold">(${checkedQtyUnits.join(' or ')})</p>
                                            <p class="mb-0 px-2">Energy: ${parseFloat(energy)}kJ | Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                                        </div>
                                    </div>
                                </td>
                                <td width="50%">
                                    <ul class="list-unstyled">${swapItemsHTML}</ul>
                                </td>
                            </tr>
                        `;

                        tableBody.append(rowHTML);
                        calculateTotals(planID, mealtimeID, mealId);
                        calculateMealNutrition();
                    });
                    updateFoodCount(foodId, 1, 'green');
                    
                    $('#mealFoodAddModal').modal('hide');
                }
            });

        });
        
        function getQtyDisplay(selectedQtyUnits, fallbackQty, fallbackUnit) {
            const checked = selectedQtyUnits.filter(q => q.checked === true || q.checked === "true");

            if (checked.length) {
                return `(${checked.map(q => {
                    const unit = q.unit || '';
                    const qty = q.qty || '';
                    const space = ['g', 'ml', 'mL'].includes(unit) ? '' : ' ';
                    const formattedQty = formatQty(qty, unit);
                    return `${formattedQty}${space}${unit}`;
                }).join(' or ')})`;
            } else {
                const formattedQty = formatQty(fallbackQty, fallbackUnit);
                const space = ['g', 'ml', 'mL'].includes(fallbackUnit) ? '' : ' ';
                return `(${formattedQty}${space}${fallbackUnit})`;
            }
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

        function getQtyDisplay(selectedQtyUnits, fallbackQty, fallbackUnit) {
            const checked = selectedQtyUnits.filter(q => q.checked === true || q.checked === "true");

            if (checked.length) {
                return `(${checked.map(q => {
                    const unit = q.unit || '';
                    const qty = q.qty || '';
                    const space = ['g', 'ml', 'mL'].includes(unit) ? '' : ' ';
                    const formattedQty = formatQty(qty, unit);
                    return `${formattedQty}${space}${unit}`;
                }).join(' or ')})`;
            } else {
                const formattedQty = formatQty(fallbackQty, fallbackUnit);
                const space = ['g', 'ml', 'mL'].includes(fallbackUnit) ? '' : ' ';
                return `(${formattedQty}${space}${fallbackUnit})`;
            }
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
            $('#addMoreFoodModal').find('#dynamicQtyMeasurementContainer').empty(); // ✅ Clears dynamic content
            $('#addMoreFoodModal').find('#modalEnergy').text('0.0kJ');
            $('#addMoreFoodModal').find('#modalProtein').text('0.0g');
            $('#addMoreFoodModal').find('#modalCarbs').text('0.0g');
            $('#addMoreFoodModal').find('#modalFat').text('0.0g');
            
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

        function fetchFoodDetails(foodId, callback) {
            $.ajax({
                url: '{{ route("admin.items.index") }}?food_id=' + foodId,
                type: 'GET',
                success: function (response) {
                    let item = null;

                    if (Array.isArray(response.items) && response.items.length > 0) {
                        item = response.items[0]; // from get()
                    } else if (typeof response.items === 'object' && response.items !== null) {
                        item = response.items; // from first()
                    }

                    if (item) {
                        callback(item);
                    } else {
                        console.warn('Item not found.');
                    }
                },
                error: function () {
                    console.error('Error fetching item details.');
                }
            });
        }

        function updateFoodCount(foodId, change, color = null, title = null, category_id = null) {
            $.ajax({
                url: '{{ route("admin.get-food-details") }}?food_id=' + foodId,
                method: 'GET',
                success: function(response) {
                    if (response.item) {
                        const foodData = response.item;

                        const categoryName = (foodData.flags && foodData.flags.length > 0)
                            ? foodData.flags[0].name
                            : (foodData.category && foodData.category.name)
                                ? foodData.category.name
                                : 'Uncategorized';

                        processFoodUpdate(foodId, change, foodData.title, categoryName);
                    } else {
                        console.error('Error fetching food details: Invalid response format');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching food details:', xhr.responseText);
                }
            });
        }

        function processFoodUpdate(foodId, change, foodTitle, categoryName) {
            let textColor = 'text-dark';
            if (preSelectedFoods.includes(Number(foodId))) {
                textColor = 'text-primary';
            } else {
                textColor = 'text-success';
            }

            let foodWrapper = $(`#food-wrapper-${foodId}`);
            let justAdded = false;

            // ✅ Safe slug from category name
            const categoryId = categoryName
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')   // Replace non-alphanumeric chars with hyphen
                .replace(/^-+|-+$/g, '');      // Trim hyphens

            let categoryRow = $(`#category-row-${categoryId}`);

            if (!foodWrapper.length) {
                // Convert category name to slug format for ID
                const categoryId = categoryName
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')  // Remove everything except letters, numbers, spaces, and dashes
                .replace(/\s+/g, '-');

                let categoryRow = $(`#category-row-${categoryId}`);
                
                // If category row doesn't exist, create it
                if (!categoryRow.length) {
                    // Create category section if it doesn't exist
                    let categorySection = $(`#category-section-${categoryId}`);
                    if (!categorySection.length) {
                        categorySection = $(`
                            <div class="category-section mb-3" id="category-section-${categoryId}">
                                <h6 class="mt-3 text-muted">${categoryName}</h6>
                                <div class="row" id="category-row-${categoryId}"></div>
                            </div>
                        `);
                        $('#category-section').append(categorySection);
                    }
                    
                    // Get or create the category row
                    categoryRow = $(`#category-row-${categoryId}`);
                }

                categoryRow = $(`#category-row-${categoryId}`);

                // Find or create a column
                let column = categoryRow.find('.col-md-6').filter(function () {
                    return $(this).children().length < 10;
                }).first();

                if (!column.length) {
                    column = $('<div class="col-md-6"></div>');
                    categoryRow.append(column);
                }

                const foodHTML = `
                    <div class="form-check dynamically-added-food" id="food-wrapper-${foodId}" data-category-id="${categoryId}">
                        <input type="checkbox" name="setp5_foods[]" value="${foodId}"
                            class="form-check-input food-checkbox"
                            id="setp5Food${foodId}"
                            data-food-id="${foodId}"
                            data-food-name="${foodTitle}">
                        <label class="form-check-label food-label" for="setp5Food${foodId}">
                            ${foodTitle}
                        </label>
                    </div>
                `;
                column.append(foodHTML);
                justAdded = true;
                foodWrapper = $(`#food-wrapper-${foodId}`);
            }

            const checkbox = $(`#setp5Food${foodId}`);
            const countLabel = checkbox.siblings('.form-check-label');
            const categoryWrapper = $(`#category-section-${categoryId}`);

            foodWrapper.removeClass('d-none');
            categoryWrapper.removeClass('d-none');

            let countText = countLabel.text();
            let match = countText.match(/\((\d+)\)$/);
            let currentCount = match ? parseInt(match[1]) : 0;
            let newCount = Math.max(0, currentCount + change);

            if (change === -1) {
                if (currentCount > 1) {
                    countLabel.text(countText.replace(/\(\d+\)$/, '').trim() + ` (${newCount})`).addClass(textColor);
                } else {
                    if (!preSelectedFoods.includes(Number(foodId))) {
                        foodWrapper.remove();
                        const remainingFoods = categoryWrapper.find('.form-check:visible');
                        if (remainingFoods.length === 0) {
                            categoryWrapper.addClass('d-none');
                        }
                    } else {
                        countLabel.text(countText.replace(/\s*\(\d+\)$/, '')).removeClass('text-primary text-success').addClass('text-dark');
                    }
                }
                return;
            }

            if (newCount > 0) {
                countLabel.text(countText.replace(/\(\d+\)$/, '').trim() + ` (${newCount})`).addClass(textColor);
            } else {
                countLabel.text(countText.replace(/\s*\(\d+\)$/, '')).addClass('text-dark');
            }
        }

        let AU_UNIT_EQUIVALENTS = buildUnitQtyMap(modal = null);

        function buildUnitQtyMap(modal) {
            let map = {};

            $(`${modal} #dynamicQtyMeasurementContainer .qty-unit-row`).each(function () {
                const qtyInput = $(this).find('.modalQtyInput').val();
                const unitInput = $(this).find('.modalMeasurementInput').val();

                const qty = parseFraction(qtyInput);
                const unit = unitInput?.toLowerCase();

                if (!isNaN(qty) && unit) {
                    map[unit] = qty;
                }
            });

            return map;
        }

        function parseFraction(value) {
            if (!value) return NaN;
            value = String(value).trim(); // ✅ Convert to string safely
            if (value.includes('/')) {
                const parts = value.split(' ');
                if (parts.length === 2) {
                    // mixed fraction (e.g. "1 1/2")
                    const whole = parseFloat(parts[0]);
                    const [num, denom] = parts[1].split('/').map(Number);
                    return whole + (num / denom);
                } else {
                    if (!preSelectedFoods.includes(Number(foodId))) {
                        foodWrapper.remove();
                        
                        // Check if category has no more visible food items
                        const remainingFoods = categoryWrapper.find('.form-check:visible');
                        if (remainingFoods.length === 0) {
                            categoryWrapper.addClass('d-none');
                        }
                    } else {
                        countLabel.text(countText.replace(/\s*\(\d+\)$/, ''))
                                .removeClass('text-primary text-success')
                                .addClass('text-dark');
                    }
                }
            }

            return parseFloat(value);
        }

        function setupNutritionSync(baseCarbs, baseProtein, baseFat, baseEnergy, modal) {
          
            AU_UNIT_EQUIVALENTS = buildUnitQtyMap(modal);
            
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

                const normalizedUnitEquivalents = {};
                Object.keys(AU_UNIT_EQUIVALENTS).forEach(key => {
                    normalizedUnitEquivalents[key.trim().toLowerCase()] = AU_UNIT_EQUIVALENTS[key];
                });

                // Later in your function
                const baseEquivalent = normalizedUnitEquivalents[currentUnit.trim().toLowerCase()];
               
                if (!baseEquivalent) {
                    console.warn('Unknown unit used in conversion:', currentUnit);
                    return;
                }
                const ratio = currentQty / baseEquivalent;
               
                $(`${modal} #modalCarbs`).text((Math.round(baseCarbs * ratio * 10) / 10) + 'g');
                $(`${modal} #modalProtein`).text((Math.round(baseProtein * ratio * 10) / 10) + 'g');
                $(`${modal} #modalFat`).text((Math.round(baseFat * ratio * 10) / 10) + 'g');
                $(`${modal} #modalEnergy`).text((Math.round(baseEnergy * ratio * 10) / 10) + 'kJ');

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

        function parseFraction(input) {
            if (input === undefined || input === null) return null;

            input = String(input).trim(); // Ensure it's a string
            if (!input) return null;

            if (!isNaN(input)) return parseFloat(input);

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
        
        let currentItemRow = null;

        $(document).on('click', '.edit-item', function () {
            const btn = $(this);
            const itemId = btn.data('item-id');
            const mealId = btn.data('meal-id');
            const planId = btn.data('plan-id');
            const mealTimeId = btn.data('meal-time-id');
            const name = btn.closest('td').find('label').text().split('(')[0].trim();
            const description = btn.data('description');

            const fallbackQty = btn.data('item-qty');
            const fallbackUnit = btn.data('item-unit');

            const checkbox = btn.closest('td').find('input[type="checkbox"]');
            const baseCarbs = parseFloat(checkbox.data('carbs')) || 0;
            const baseProtein = parseFloat(checkbox.data('protein')) || 0;
            const baseFat = parseFloat(checkbox.data('fat')) || 0;
            const baseEnergy = parseFloat(checkbox.data('energy')) || 0;

            currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);

            $('#editItemId').val(itemId);
            $('#editMealId').val(mealId);
            $('#editPlanId').val(planId);
            $('#editMealTimeId').val(mealTimeId);
            $('#editItemName').val(name);
            $('#editItemModal #description').val(description);

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

            const $container = $('#editItemModal #dynamicQtyMeasurementContainer').empty();

            selectedQtyUnits.forEach(({ qty, unit, checked }, index) => {
                const row = `
                    <div class="row mb-2 qty-unit-row align-items-end">
                        <div class="col-1 text-center mb-3">
                            <input type="checkbox" class="form-check-input qtyUnitSelector" ${checked ? 'checked' : '' }>
                        </div>
                        <div class="col-5">
                            ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                            <input type="text" class="form-control modalQtyInput" value="${qty}" data-original-qty="${qty}">
                        </div>
                        <div class="col-5">
                            ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                            <input type="text" class="form-control modalMeasurementInput" value="${unit}" data-original-unit="${unit}">
                        </div>
                    </div>
                `;
                $container.append(row);
            });

            $('#modalCarbs').text((Math.round(baseCarbs * 10) / 10) + 'g');
            $('#modalProtein').text((Math.round(baseProtein * 10) / 10) + 'g');
            $('#modalFat').text((Math.round(baseFat * 10) / 10) + 'g');
            $('#modalEnergy').text((Math.round(baseEnergy * 10) / 10) + 'kJ');

            const modal = new bootstrap.Modal(document.getElementById('editItemModal'));
            modal.show();

            let ratio = null;
            $container.find('.modalQtyInput').on('input', function () {
                const $row = $(this).closest('.qty-unit-row');
                const newQty = parseFraction($(this).val());
                const newUnit = $row.find('.modalMeasurementInput').val().trim().toLowerCase();
                const originalQty = parseFraction($(this).data('original-qty'));
                const originalUnit = $row.find('.modalMeasurementInput').data('original-unit')?.trim().toLowerCase();

                if (!isNaN(newQty) && !isNaN(originalQty) && originalQty && newQty) {
                    ratio = newQty / originalQty;
                } else {
                    ratio = null;
                }

                // if (originalUnit !== newUnit && unitRatios?.[originalUnit] && unitRatios?.[newUnit]) {
                //     const unitRatio = unitRatios[newUnit] / unitRatios[originalUnit];
                //     ratio *= unitRatio;
                // }

                $('#editItemModal #ratio').val(ratio);
                // Optionally: update nutrition or UI here
            });

            setupDynamicMeasurementSync('#editItemModal');
            setTimeout(() => {
                setupNutritionSync(baseCarbs, baseProtein, baseFat, baseEnergy, '#editItemModal');
            }, 200);
        });

        $('#saveItemChanges').on('click', function () {
            // Validate: Prevent save if any checked qty is 0, blank, or invalid
            let invalidQty = false;
            $('#editItemModal #dynamicQtyMeasurementContainer .qty-unit-row').each(function () {
                const $row = $(this);
                const isChecked = $row.find('.qtyUnitSelector').is(':checked');
                if (isChecked) {
                    const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                    const parsedQty = parseFraction(rawQtyInput);
                    if (!rawQtyInput || isNaN(parsedQty) || parsedQty <= 0) {
                        invalidQty = true;
                    }
                }
            });
            if (invalidQty) {
                alert('Please enter a quantity greater than 0 for all selected options.');
                return;
            }
            const itemId = $('#editItemId').val();
            const mealId = $('#editMealId').val();
            const planId = $('#editPlanId').val();
            const mealTimeId = $('#editMealTimeId').val();
            const name = $('#editItemName').val();
            const description = $('#editItemModal #description').val();
            const ratio = parseFloat($('#editItemModal #ratio').val());
           
            const selectedQtyUnits = [];
            const checkedQtyUnits = [];
            const qtyUnitDisplay = [];
            let qty = 0;
            let unit = '';

            const anyChecked = $('#editItemModal #dynamicQtyMeasurementContainer .qty-unit-row').find('.qtyUnitSelector:checked').length > 0;
            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }
            let foundChecked = false;

            $('#editItemModal #dynamicQtyMeasurementContainer .qty-unit-row').each(function () {
                const $row = $(this);
                const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                unit = $row.find('.modalMeasurementInput').val().trim();
                const isChecked = $row.find('.qtyUnitSelector').is(':checked');

                if (!isNaN(parsedQty) && unit) {
                    let qtyToUse;

                    // Normalize unit for comparison (to lowercase)
                    const normalizedUnit = unit.toLowerCase();

                    if (['g', 'ml', 'ml'].includes(normalizedUnit)) {
                        // Round qty for these units
                        qtyToUse = Math.round(parsedQty).toString();
                    } else {
                        // For other units, keep original input or parsed number depending on decimals
                        if (parsedQty % 1 === 0) {
                            qtyToUse = parsedQty.toString(); // whole number as string
                        } else {
                            qtyToUse = rawQtyInput; // keep input as is (like "1/2")
                        }
                    }

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        qtyUnitDisplay.push(`${qtyToUse}${['g', 'ml', 'mL'].includes(unit) ? unit : ' ' + unit}`);
                        // Set qty and unit only for the FIRST checked row
                        qty = qtyToUse;
                        unit = unit;
                    }
                }
            });

            const carbs = parseFloat($('#editItemModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#editItemModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#editItemModal #modalFat').text()) || 0;
            const energy = parseFloat($('#editItemModal #modalEnergy').text()) || 0;

            const updatedHTML = `
                <div class="d-flex justify-content-between align-items-start mb-0">
                    <div class="col-9">
                        <div class="d-flex align-items-start">
                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                value="${itemId}" class="form-check-input me-2 d-none" checked
                                data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" data-energy="${energy}">
                            <label class="form-check-label flex-grow-1">${name}</label>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary view-info"
                            data-description="${description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${description}">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success edit-item"
                            data-item-id="${itemId}" data-meal-id="${mealId}" data-plan-id="${planId}"
                            data-meal-time-id="${mealTimeId}" data-user-id=""
                            data-item-qty="" data-item-unit=""
                            data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                            title="Edit"><i class="icofont-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                            data-item-id="${itemId}" data-meal-id="${mealId}" data-plan-id="${planId}"
                            data-meal-time-id="${mealTimeId}" data-user-id="" title="Delete">
                            <i class="icofont-ui-delete"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="px-2 mb-2 fw-bold">(${qtyUnitDisplay.join(' or ')})</p>
                        <p class="mb-0 px-2">Energy: ${parseFloat(energy)}kJ |  Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                    </div>
                </div>
            `;

            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            // currentItemRow.find('td:nth-child(3)').html(updatedHTML);
            currentItemRow.find('td:nth-child(2)').html(updatedHTML);

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
                    food_energy: energy,
                    selected_qty_unit: selectedQtyUnits,
                    checked_qty_unit: checkedQtyUnits,
                    type: 'item-update',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        const $updatedRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
                        const $swapListItems = $updatedRow.find('td').eq(2).find('li[data-swap-item-id]');
                        // const ratio = parseFloat($('#editItemModal #ratio').val());
                      
                        if (ratio !== 0 && !isNaN(ratio)) {
                            $swapListItems.each(function () {
                                const $swapLi = $(this);
                                const $checkbox = $swapLi.find('input[type="checkbox"]');
                                const $editBtn = $swapLi.find('.edit-swap-item');
                                const $quantityP = $swapLi.find('p').first();
                                const $nutritionP = $swapLi.find('p').last();

                                // Step 1: Nutrition update
                                const origEnergy = parseFloat($checkbox.attr('data-energy')) || 0;
                                const origProtein = parseFloat($checkbox.attr('data-protein')) || 0;
                                const origCarbs = parseFloat($checkbox.attr('data-carbs')) || 0;
                                const origFat = parseFloat($checkbox.attr('data-fat')) || 0;

                                const newEnergy = (origEnergy * ratio).toFixed(2);
                                const newProtein = (origProtein * ratio).toFixed(2);
                                const newCarbs = (origCarbs * ratio).toFixed(2);
                                const newFat = (origFat * ratio).toFixed(2);

                                $nutritionP.text(
                                    `Energy: ${Math.round(newEnergy)}kJ | Protein: ${Math.round(newProtein)}g | Carb: ${Math.round(newCarbs)}g | Fat: ${Math.round(newFat)}g`
                                );

                                $checkbox.attr('data-energy', newEnergy);
                                $checkbox.attr('data-protein', newProtein);
                                $checkbox.attr('data-carbs', newCarbs);
                                $checkbox.attr('data-fat', newFat);

                                // Step 2: Update Units (use only updatedUnits now)
                                let selectedUnits;
                                try {
                                    selectedUnits = JSON.parse($editBtn.attr('data-selected-qty-unit') || '[]');
                                } catch (e) {
                                    console.warn('Invalid JSON:', e);
                                    selectedUnits = [];
                                }

                                const updatedUnits = selectedUnits.map(unit => {
                                    const baseQty = parseFloat(unit.qty);
                                    if (isNaN(baseQty)) return unit;
                                    return {
                                        ...unit,
                                        qty: (baseQty * ratio).toFixed(2).replace(/\.00$/, '')
                                    };
                                });

                                // Step 3: Build qty string for display
                                const noSpaceUnits = ['g', 'ml', 'mL'];

                                const checkedUnits = updatedUnits.filter(u => u.checked);
                                const qtyDisplayParts = checkedUnits.map(u => {
                                    return noSpaceUnits.includes(u.unit)
                                        ? `${u.qty}${u.unit}`
                                        : `${u.qty} ${u.unit}`;
                                });

                                $quantityP.text(`(${qtyDisplayParts.join(' or ')})`);

                                // Step 4: Update button attributes
                                const updatedJSON = JSON.stringify(updatedUnits);
                                $editBtn.attr('data-selected-qty-unit', updatedJSON);

                                // Optional: Ajax DB update
                                const swapItemId = $swapLi.data('swap-item-id');
                                $.ajax({
                                    url: '{{ route("admin.update-swap-item") }}',
                                    method: 'POST',
                                    data: {
                                        swap_item_id: swapItemId,
                                        item_id: itemId,
                                        plan_id: planId,
                                        meal_id: mealId,
                                        meal_time_id: mealTimeId,
                                        user_id: userId,
                                        food_energy: newEnergy,
                                        food_protein: newProtein,
                                        food_carbs: newCarbs,
                                        food_fat: newFat,
                                        ratio: ratio,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function (resp) {
                                        if (!resp.success) {
                                            console.warn(`Swap item ${swapItemId} update failed.`);
                                        }
                                    },
                                    error: function () {
                                        console.warn(`Error updating swap item ${swapItemId}`);
                                    }
                                });
                            });

                            $('#editItemModal #ratio').val(0); // reset ratio
                        }
                    } else {
                        alert('Failed to update food.');
                    }
                },
                error: function () {
                    alert('An error occurred while updating food.');
                }
            });
        });

        let mainDeleteParams = null;

        $(document).on('click', '.delete-item', function () {
            const $btn = $(this);
            const itemId = $btn.data('item-id');
            const mealId = $btn.data('meal-id');
            const mealTimeId = $btn.data('meal-time-id');
            const planId = $btn.data('plan-id');
            const userId = $btn.data('user-id');

            const $row = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            const $tableBody = $row.closest('tbody');
            const remainingRows = $tableBody.find('tr').length;
            const allSwapLis = $row.find('li');

            mainDeleteParams = {
                $row, $tableBody, remainingRows,
                itemId, mealId, mealTimeId, planId, allSwapLis, userId
            };

            new bootstrap.Modal(document.getElementById('deleteMainItemModal')).show();
        });

        $('#confirmDeleteMainItem').on('click', function () {
            const {
                $row, $tableBody, remainingRows,
                itemId, mealId, mealTimeId,
                planId, allSwapLis, userId
            } = mainDeleteParams;

            $.ajax({
                url: '{{ route("admin.delete-user-meal-food") }}',
                type: 'POST',
                data: {
                    item_id: itemId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // ✅ Safe to remove from DOM now
                        $row.find('input[type="checkbox"]').prop('checked', false);
                        $row.remove();

                        if (remainingRows === 1) {
                            $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
                            const $select = $(`#addMealDropdown${planId}_${mealTimeId} select`);
                            $select.find(`option[value="${mealId}"]`).remove();
                            $select.trigger('change');
                        }

                        updateFoodCount(itemId, -1, null);

                        calculateTotals(planId, mealTimeId, mealId);
                        calculateMealNutrition();

                        mainDeleteParams = null;
                        $('#deleteMainItemModal').modal('hide');
                    } else {
                        $row.find('input[type="checkbox"]').prop('checked', false);
                        $row.remove();

                        if (remainingRows === 1) {
                            $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
                            const $select = $(`#addMealDropdown${planId}_${mealTimeId} select`);
                            $select.find(`option[value="${mealId}"]`).remove();
                            $select.trigger('change');
                        }

                        updateFoodCount(itemId, -1, null);

                        calculateTotals(planId, mealTimeId, mealId);
                        calculateMealNutrition();

                        mainDeleteParams = null;
                        $('#deleteMainItemModal').modal('hide');
                    }
                },
                error: function() {
                    alert('Failed to delete food item.');
                }
            });
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
            const description = btn.data('description');
            const checkbox = btn.closest('li').find('input[type="checkbox"]');
            const baseCarbs = parseFloat(checkbox.data('carbs')) || 0;
            const baseProtein = parseFloat(checkbox.data('protein')) || 0;
            const baseFat = parseFloat(checkbox.data('fat')) || 0;
            const baseEnergy = parseFloat(checkbox.data('energy')) || 0;
            const name = btn.closest('tr').find('td').eq(1).find('label').text().split('(')[0].trim();
            $(`${modalId} #editItemName`).val(name);

            $('#editMainItemId').val(itemId);
            $('#editSwapItemId').val(swapItemId);
            $('#editSwapPlanId').val(planId);
            $('#editSwapMealTimeId').val(mealTimeId);
            $('#editSwapMealId').val(mealId);
            $('#previousSwapItemId').val(swapItemId);
            $('#editSwapItemModal #description').val(description);
            $(`${modalId} #modalCarbs`).text((Math.round(baseCarbs * 10) / 10) + 'g');
            $(`${modalId} #modalProtein`).text((Math.round(baseProtein * 10) / 10) + 'g');
            $(`${modalId} #modalFat`).text((Math.round(baseFat * 10) / 10) + 'g');
            $(`${modalId} #modalEnergy`).text((Math.round(baseEnergy * 10) / 10) + 'kJ');

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

                        const imageUrl = selected.image ? `{{ webAssets('storage/') }}/${selected.image}` : '';

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
                                energy: selected.energy,
                                qty: selected.qty,
                                unit: selected.unit,
                                description: selected.description,
                                selected_qty_unit: selected.selected_qty_unit
                            });

                        $dropdown.append(option).trigger('change');

                        buildQtyUnitRows(selectedQtyUnits);

                        setupNutritionSync(
                            parseFloat(selected.carbs) || 0,
                            parseFloat(selected.protein) || 0,
                            parseFloat(selected.fat) || 0,
                            parseFloat(selected.energy) || 0,
                            modalId
                        );
                        setupDynamicMeasurementSync(modalId);
                        // $(`${modalId} .qty-unit-row`).first().find('.modalQtyInput').trigger('input');
                    }
                });
            } else {
                $dropdown.val(swapItemId).trigger('change');
                buildQtyUnitRows(selectedQtyUnits);
                setupNutritionSync(baseCarbs, baseProtein, baseFat, baseEnergy, modalId);
                setupDynamicMeasurementSync(modalId);
                // $(`${modalId} .qty-unit-row`).first().find('.modalQtyInput').trigger('input');
            }

            const modal = new bootstrap.Modal(document.getElementById('editSwapItemModal'));
            modal.show();
        });

        $('#saveSwapItemChanges').on('click', function () {
            // Validate: Prevent save if any checked qty is 0, blank, or invalid
            let invalidQty = false;
            $('#editSwapItemModal .qty-unit-row').each(function () {
                const $row = $(this);
                const isChecked = $row.find('.qtyUnitSelector').is(':checked');
                if (isChecked) {
                    const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                    const parsedQty = parseFraction(rawQtyInput);
                    if (!rawQtyInput || isNaN(parsedQty) || parsedQty <= 0) {
                        invalidQty = true;
                    }
                }
            });
            if (invalidQty) {
                alert('Please enter a quantity greater than 0 for all selected options.');
                return;
            }
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
                    let qtyToUse;
                    const normalizedUnit = unit.toLowerCase();

                    if (['g', 'ml', 'mL'].includes(normalizedUnit)) {
                        // Round for g/ml/mL
                        qtyToUse = Math.round(parsedQty).toString();
                    } else {
                        // Keep as fraction if decimal or keep whole number
                        qtyToUse = parsedQty % 1 === 0 ? parsedQty.toString() : rawQtyInput;
                    }

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        checkedQtyUnits.push(`${qtyToUse}${["g", "ml", "mL"].includes(unit) ? unit : ' ' + unit}`);
                        // Set actual qty to be submitted
                        qty = qtyToUse;
                    }
                }
            });
            const carbs = parseFloat($('#editSwapItemModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#editSwapItemModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#editSwapItemModal #modalFat').text()) || 0;
            const energy = parseFloat($('#editSwapItemModal #modalEnergy').text()) || 0;
            const description = $('#editSwapItemModal #description').val();

            const updatedLI = `
                <li class="list-unstyled mb-3" data-swap-item-id="${swapItemId}">
                    <div class="d-flex justify-content-between align-items-start mb-0">
                        <div class="col-9">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]"
                                    value="${swapItemId}" class="form-check-input me-2 d-none" checked
                                    data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" data-energy="${energy}">
                                <label class="form-check-label">${name}</label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                data-description="${description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${description}">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-0"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}"
                                data-swap-qty="${qty}" data-swap-unit="${unit}"
                                data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                title="Edit"><i class="icofont-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}" title="Delete">
                                <i class="icofont-ui-delete"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="px-2 mb-2 fw-bold">(${checkedQtyUnits.join(' or ')})</p>
                            <p class="mb-0 px-2">Energy: ${parseFloat(energy)}kJ | Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                        </div>
                    </div>
                </li>
            `;

            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            const liToReplace = currentItemRow.find(`td:nth-child(3) li[data-swap-item-id="${previousSwapItemId}"]`);
            liToReplace.replaceWith(updatedLI);

            if (previousSwapItemId !== swapItemId) {
                updateFoodCount(previousSwapItemId, -1, null);
                updateFoodCount(swapItemId, 1, 'green');
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
                    swap_food_energy: energy,
                    swap_selected_qty_unit: selectedQtyUnits,
                    checked_qty_unit: checkedQtyUnits,
                    type: 'swap-food-update',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
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

        // Show swap item delete modal
        let swapDeleteParams = null;
        $(document).on('click', '.delete-swap-item', function () {
            const $btn = $(this);
            const $li = $btn.closest('li');
            const $ul = $li.closest('ul');

            swapDeleteParams = {
                $li, $ul,
                itemId: $btn.data('item-id'),
                mealId: $btn.data('meal-id'),
                mealTimeId: $btn.data('meal-time-id'),
                planId: $btn.data('plan-id'),
                userId: $btn.data('user-id'),
                swapItemId: $btn.data('swap-item-id')
            };

            new bootstrap.Modal(document.getElementById('deleteSwapItemModal')).show();
        });

        $('#confirmDeleteSwapItem').on('click', function () {
            const {
                $li, $ul,
                itemId, mealId, mealTimeId,
                planId, userId, swapItemId
            } = swapDeleteParams;

            $.ajax({
                url: '{{ route("admin.delete-user-meal-swap-food") }}',
                type: 'POST',
                data: {
                    item_id: itemId,
                    meal_id: mealId,
                    meal_time_id: mealTimeId,
                    plan_id: planId,
                    user_id: userId,
                    swap_item_id: swapItemId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // ✅ Remove the deleted swap item from DOM
                        $li.remove();

                        // Check if only one <li> remains and it's the 'Add More' button
                        const $remainingItems = $ul.find('li');
                        const onlyAddMoreBtnLeft = $remainingItems.length === 1 &&
                            $remainingItems.find('.add-more-swap-item').length;

                        if (onlyAddMoreBtnLeft) {
                            const $messageLi = $remainingItems.first();
                            const $col9 = $messageLi.find('.col-9');
                            $col9.html('<span class="text-muted">No swap items available</span>');
                        }

                        // Hide the modal
                        bootstrap.Modal.getInstance(document.getElementById('deleteSwapItemModal')).hide();
                        swapDeleteParams = null;

                    } else {
                        // ✅ Remove the deleted swap item from DOM
                        $li.remove();

                        // Check if only one <li> remains and it's the 'Add More' button
                        const $remainingItems = $ul.find('li');
                        const onlyAddMoreBtnLeft = $remainingItems.length === 1 &&
                            $remainingItems.find('.add-more-swap-item').length;

                        if (onlyAddMoreBtnLeft) {
                            const $messageLi = $remainingItems.first();
                            const $col9 = $messageLi.find('.col-9');
                            $col9.html('<span class="text-muted">No swap items available</span>');
                        }

                        // Hide the modal
                        bootstrap.Modal.getInstance(document.getElementById('deleteSwapItemModal')).hide();
                        swapDeleteParams = null;
                    }
                },
                error: function () {
                    alert('Failed to delete swap food item.');
                }
            });
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
                                image: item.image ? `{{ webAssets('storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                energy: item.energy,
                                qty: item.qty,
                                unit: item.unit,
                                description: item.description,
                                selected_qty_unit: item.selected_qty_unit,
                                has_flags: Array.isArray(item.flags) ? item.flags.length > 0 : !!item.flags
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
                const dot = food.has_flags ? '<span style="color: purple; font-size: 24px; margin-right: 6px;">&#9679;</span>' : '';

                return $(`
                    <div style="display: flex; align-items: center;">
                        ${dot}
                        <img src="${food.image}" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            function formatFoodSelection(food) {
                if (!food.id) return food.text;
                const dot = food.has_flags ? '<span style="color: purple; font-size: 24px; margin-right: 6px;">&#9679;</span>' : '';

                return $(`
                    <div style="display: flex; align-items: center;">
                        ${dot}
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

                $('#editSwapItemModal #modalCarbs').text((parseFloat(data.carbs) || 0).toFixed(1) + 'g');
                $('#editSwapItemModal #modalProtein').text((parseFloat(data.protein) || 0).toFixed(1) + 'g');
                $('#editSwapItemModal #modalFat').text((parseFloat(data.fat) || 0).toFixed(1) + 'g');
                $('#editSwapItemModal #modalEnergy').text((parseFloat(data.energy) || 0).toFixed(1) + 'kJ');
                $('#editSwapItemModal #description').val(data.description);
                
                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, parseFloat(data.energy) || 0, '#editSwapItemModal');
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
            const description = $(this).data('description');
            // Set item name
            const name = btn.closest('tr').find('td').eq(1).find('label').text().split('(')[0].trim();
            $(`${modalId} #itemName`).val(name);

            $(`${modalId} .modal-title`).text('Add Swap Food');

            // Set required hidden fields
            $('#addSwapItemModal #refItemId').val(itemId);
            $('#addSwapItemModal #swapPlanId').val(planId);
            $('#addSwapItemModal #swapMealTimeId').val(mealTimeId);
            $('#addSwapItemModal #swapMealId').val(mealId);
            $('#addSwapItemModal #description').val(description);

            // Reset swap item ID since this is a new add
            $('#addSwapItemModal #swapItemId').val('');
            // $('#addSwapItemModal #previousSwapItemId').val('');

            // Clear old values
            $('#addSwapItemModal #itemName').val(name);
            $('#addSwapItemModal #modalCarbs').text('0g');
            $('#addSwapItemModal #modalProtein').text('0g');
            $('#addSwapItemModal #modalFat').text('0g');
            $('#addSwapItemModal #modalEnergy').text('0kJ');
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
                                image: item.image ? `{{ webAssets('storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                energy: item.energy,
                                qty: item.qty,
                                unit: item.unit,
                                description: item.description,
                                selected_qty_unit: item.selected_qty_unit,
                                has_flags: Array.isArray(item.flags) ? item.flags.length > 0 : !!item.flags
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
                const dot = food.has_flags ? '<span style="color: purple; font-size: 24px; margin-right: 6px;">&#9679;</span>' : '';

                return $(`
                    <div style="display: flex; align-items: center;">
                        ${dot}
                        <img src="${food.image}" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            function formatFoodSelection(food) {
                if (!food.id) return food.text;
                const dot = food.has_flags ? '<span style="color: purple; font-size: 24px; margin-right: 6px;">&#9679;</span>' : '';

                return $(`
                    <div style="display: flex; align-items: center;">
                        ${dot}
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
                $('#addSwapItemModal #modalEnergy').text((parseFloat(data.energy) || 0).toFixed(1) + 'kJ');
                $('#addSwapItemModal #description').val(data.description);
                
                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, parseFloat(data.energy) || 0,  '#addSwapItemModal');
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
            const description = $('#addSwapItemModal #description').val();

            // ✅ Check if food is selected
            if (!swapItemId || swapItemId === '0') {
                alert('Please select a food item.');
                return;
            }
            
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
                    let qtyToUse;
                    const normalizedUnit = unit.toLowerCase();

                    if (['g', 'ml', 'mL'].includes(normalizedUnit)) {
                        // Round for g/ml/mL units
                        qtyToUse = Math.round(parsedQty).toString();
                    } else {
                        // Keep as-is if not whole number, else use integer version
                        qtyToUse = parsedQty % 1 === 0 ? parsedQty.toString() : rawQtyInput;
                    }

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        const formattedUnit = ['g', 'ml', 'mL'].includes(unit) ? unit : ' ' + unit;
                        checkedQtyUnits.push(`${qtyToUse}${formattedUnit}`);
                        selectedTitleParts.push(`${qtyToUse}${formattedUnit}`);

                        qty = qtyToUse;
                    }
                }
            });

            const carbs = parseFloat($('#addSwapItemModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#addSwapItemModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#addSwapItemModal #modalFat').text()) || 0;
            const energy = parseFloat($('#addSwapItemModal #modalEnergy').text()) || 0;
            const updatedLI = `
                <li class="list-unstyled mb-3" data-swap-item-id="${swapItemId}">
                    <div class="d-flex justify-content-between align-items-start mb-0">
                        <div class="col-9">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]"
                                    value="${swapItemId}" class="form-check-input me-2 d-none " checked
                                    data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" data-energy="${energy}">
                                <label class="form-check-label">${name}</label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                data-description="${description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${description}">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-0"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}"
                                data-swap-qty="${qty}" data-swap-unit="${unit}"
                                data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                title="Edit"><i class="icofont-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}" title="Delete">
                                <i class="icofont-ui-delete"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="px-2 mb-2 fw-bold">(${selectedTitleParts.join(' or ')})</p>
                            <p class="mb-0 px-2">Energy: ${parseFloat(energy)}kJ | Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                        </div>
                    </div>
                </li>
                <li class="d-flex justify-content-between align-items-start mt-1">
                    <div class="col-9"></div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-more-swap-item ms-2"
                            data-item-id="${itemId}" data-meal-id="${mealId}" data-plan-id="${planId}"
                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                            title="Add More"><i class="icofont-plus"></i></button>
                    </div>
                </li>
            `;

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
                    swap_food_energy: energy,
                    swap_selected_qty_unit: selectedQtyUnits,
                    type: 'add-swap-food',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
                        // Find and replace only the current <li> using swapItemId
                        const liToReplace = currentItemRow.find(`td:nth-child(3) ul`);
                        liToReplace.replaceWith(updatedLI);

                        updateFoodCount(swapItemId, 1, 'green');

                        modal.hide();
                    } else {
                        alert(response.message || 'Failed to add swap item.');
                        modal.hide();
                    }
                },
                error: function () {
                    alert('An error occurred while updating swap items.');
                    modal.hide();
                }
            });
            
        });

        $(document).on('click', '.add-more-swap-item', function () {
            const btn = $(this);
            const itemId = $(this).data('item-id');
            const mealId = $(this).data('meal-id');
            const planId = $(this).data('plan-id');
            const mealTimeId = $(this).data('meal-time-id');
            const modalId = '#addMoreSwapItemModal';
            const description = $(this).data('description');

            // Set item name
            const name = btn.closest('tr').find('td').eq(1).find('label').text().split('(')[0].trim();
            $(`${modalId} #itemName`).val(name);

            $(`${modalId} .modal-title`).text('Add Swap Food');

            // Set required hidden fields
            $('#addMoreSwapItemModal #refItemId').val(itemId);
            $('#addMoreSwapItemModal #swapPlanId').val(planId);
            $('#addMoreSwapItemModal #swapMealTimeId').val(mealTimeId);
            $('#addMoreSwapItemModal #swapMealId').val(mealId);
            $('#addMoreSwapItemModal #description').val(description);

            // Reset swap item ID since this is a new add
            $('#addMoreSwapItemModal #swapItemId').val('');
            // $('#addMoreSwapItemModal #previousSwapItemId').val('');

            // Clear old values
            $('#addMoreSwapItemModal #itemName').val(name);
            $('#addMoreSwapItemModal #modalCarbs').text('0g');
            $('#addMoreSwapItemModal #modalProtein').text('0g');
            $('#addMoreSwapItemModal #modalFat').text('0g');
            $('#addMoreSwapItemModal #modalEnergy').text('0kJ');
            $('#addMoreSwapItemModal #dynamicQtyMeasurementContainer').empty();

            // Optionally reset the dropdown to default (you control how it fills)
            $('#swapItemDropdown').val('').trigger('change');

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('addMoreSwapItemModal'));
            modal.show();
            setupDynamicMeasurementSync('#addMoreSwapItemModal');

        });

        $(document).ready(function () {
            $('#moreSwapFoodDropdown').select2({
                placeholder: "Search for swap foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                dropdownParent: $('#addMoreSwapItemModal'),
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
                                image: item.image ? `{{ webAssets('storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                energy: item.energy,
                                qty: item.qty,
                                unit: item.unit,
                                description: item.description,
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

            $('#moreSwapFoodDropdown').on('select2:select', function (e) {
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

                const $container = $('#addMoreSwapItemModal #dynamicQtyMeasurementContainer').empty();

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

                $('#addMoreSwapItemModal #modalCarbs').text((parseFloat(data.carbs) || 0).toFixed(1) + 'g');
                $('#addMoreSwapItemModal #modalProtein').text((parseFloat(data.protein) || 0).toFixed(1) + 'g');
                $('#addMoreSwapItemModal #modalFat').text((parseFloat(data.fat) || 0).toFixed(1) + 'g');
                $('#addMoreSwapItemModal #modalEnergy').text((parseFloat(data.energy) || 0).toFixed(1) + 'kJ');
                $('#addMoreSwapItemModal #description').val(data.description);
                
                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, parseFloat(data.energy) || 0, '#addMoreSwapItemModal');
                setupDynamicMeasurementSync('#addMoreSwapItemModal');
            });

        });

        $('#saveMoreSwapItem').on('click', function () {
            const itemId = $('#addMoreSwapItemModal #refItemId').val();
            const mealId = $('#addMoreSwapItemModal #swapMealId').val();
            const planId = $('#addMoreSwapItemModal #swapPlanId').val();
            const mealTimeId = $('#addMoreSwapItemModal #swapMealTimeId').val();
            const $dropdown = $('#addMoreSwapItemModal #moreSwapFoodDropdown');
            const name = $dropdown.find('option:selected').text();
            const swapItemId = $dropdown.find('option:selected').val();
            const description = $('#addMoreSwapItemModal #description').val();

            const anyChecked = $('#addMoreSwapItemModal .qty-unit-row').find('.multiQtyCheckbox:checked').length > 0;
            if (!anyChecked) {
                alert('Please select at least one quantity/measurement option.');
                return;
            }

            const selectedQtyUnits = [];
            let selectedTitleParts = [];
            let checkedQtyUnits = [];
            let qty = 0;
            let unit = "";
            $('#addMoreSwapItemModal .qty-unit-row').each(function () {
                const $row = $(this);
                const rawQtyInput = $row.find('.modalQtyInput').val().trim();
                const parsedQty = parseFraction(rawQtyInput);
                let unit = $row.find('.modalMeasurementInput').val().trim();
                const $checkbox = $row.find('.multiQtyCheckbox');
                const isChecked = $checkbox.is(':checked');

                if (!isNaN(parsedQty) && unit) {
                    let qtyToUse;
                    const normalizedUnit = unit.toLowerCase();

                    if (['g', 'ml'].includes(normalizedUnit)) {
                        // Round for g/ml/mL units
                        qtyToUse = Math.round(parsedQty).toString();
                    } else {
                        // Keep as-is if not whole number, else use integer version
                        qtyToUse = parsedQty % 1 === 0 ? parsedQty.toString() : rawQtyInput;
                    }

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        const formattedUnit = ['g', 'ml', 'mL'].includes(unit) ? unit : ' ' + unit;
                        checkedQtyUnits.push(`${qtyToUse}${formattedUnit}`);
                        selectedTitleParts.push(`${qtyToUse}${formattedUnit}`);

                        qty = qtyToUse;
                    }
                }
            });

            const carbs = parseFloat($('#addMoreSwapItemModal #modalCarbs').text()) || 0;
            const protein = parseFloat($('#addMoreSwapItemModal #modalProtein').text()) || 0;
            const fat = parseFloat($('#addMoreSwapItemModal #modalFat').text()) || 0;
            const energy = parseFloat($('#addMoreSwapItemModal #modalEnergy').text()) || 0;
            const updatedLI = `
                <li class="list-unstyled mb-3" data-swap-item-id="${swapItemId}">
                    <div class="d-flex justify-content-between align-items-start mb-0">
                        <div class="col-9">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${itemId}][]"
                                    value="${swapItemId}" class="form-check-input me-2 d-none " checked
                                    data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" data-energy="${energy}">
                                <label class="form-check-label">${name}</label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                data-description="${description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${description}">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-0"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}"
                                data-swap-qty="${qty}" data-swap-unit="${unit}"
                                data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                title="Edit"><i class="icofont-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                data-swap-item-id="${swapItemId}" data-item-id="${itemId}" data-meal-id="${mealId}"
                                data-plan-id="${planId}" data-meal-time-id="${mealTimeId}" title="Delete">
                                <i class="icofont-ui-delete"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="px-2 mb-2 fw-bold">(${selectedTitleParts.join(' or ')})</p>
                            <p class="mb-0 px-2">Energy: ${parseFloat(energy)}kJ | Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                        </div>
                    </div>
                </li>
            `;

            // const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            // const swapItemsContainer = currentItemRow.find(`td:nth-child(2) ul`);
            
            // // Check if there are any existing swap items
            // const existingItems = swapItemsContainer.find('li[data-swap-item-id]');
            const currentItemRow = $(`#itemRow_${planId}_${mealTimeId}_${mealId}_${itemId}`);
            const swapItemsContainer = currentItemRow.find(`td:nth-child(3)`);

            
            const modalEl = document.getElementById('addMoreSwapItemModal');
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
                    swap_food_energy: energy,
                    swap_selected_qty_unit: selectedQtyUnits,
                    type: 'add-swap-food',
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // Check if there are any existing swap items
                        const existingItems = swapItemsContainer.find('li[data-swap-item-id]');
                        if (existingItems.length > 0) {
                            // If there are existing items, append the new item after them
                            existingItems.last().after(updatedLI);
                        } else {
                            // If no existing items, replace the entire content
                            swapItemsContainer.html(updatedLI);
                        }
                        
                        $('[data-bs-toggle="tooltip"]').tooltip();

                        modal.hide();
                    } else {
                        alert(response.message || 'Failed to update swap items.');
                        modal.hide();
                    }
                },
                error: function () {
                    alert('Failed to update swap items.');
                    modal.hide();
                }
            });
        });

        $('#addMoreSwapItemModal').on('hidden.bs.modal', function () {

            // Reset all inputs inside the modal
            $(this).find('input').val('');

            // ✅ Clear the select value FIRST, then trigger change
            const $dropdown = $('#addMoreSwapItemModal #moreSwapFoodDropdown');
            $dropdown.val(null).trigger('change'); // This clears Select2 selected value

            // ✅ Clear dynamic fields
            $('#addMoreSwapItemModal #dynamicQtyMeasurementContainer').empty();

            // ✅ Reset macro values
            $('#addMoreSwapItemModal #modalCarbs').text('0.0g');
            $('#addMoreSwapItemModal #modalProtein').text('0.0g');
            $('#addMoreSwapItemModal #modalFat').text('0.0g');
            $('#addMoreSwapItemModal #modalEnergy').text('0.0kJ'); // Removed space and fixed selector
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
            $(this).find('#modalEnergy').text('0.0kJ');
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
                                image: item.image ? `{{ webAssets('storage/') }}/${item.image}` : '',
                                carbs: item.carbs,
                                protein: item.protein,
                                fat: item.fat,
                                energy: item.energy,
                                qty: item.qty,
                                unit: item.unit,
                                description: item.description,
                                selected_qty_unit: item.selected_qty_unit,
                                has_flags: Array.isArray(item.flags) ? item.flags.length > 0 : !!item.flags
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
                const dot = food.has_flags ? '<span style="color: purple; font-size: 24px; margin-right: 6px;">&#9679;</span>' : '';
                return $(`
                    <div style="display: flex; align-items: center;">
                        ${dot}
                        <img src="${food.image}" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            function formatFoodSelection(food) {
                if (!food.id) return food.text;
                const dot = food.has_flags ? '<span style="color: purple; font-size: 24px; margin-right: 6px;">&#9679;</span>' : '';
                return $(`
                    <div style="display: flex; align-items: center;">
                        ${dot}
                        <img src="${food.image}" style="width: 25px; height: 25px; margin-right: 5px;">
                        <span>${food.text}</span>
                    </div>
                `);
            }

            $('#foodDropdown').on('select2:select', function (e) {
                const data = e.params.data;

                // Initialize window.mesureofnewaddedfood if it doesn’t exist
                if (typeof window.mesureofnewaddedfood === 'undefined') {
                    window.mesureofnewaddedfood = {};
                }

                // Check if the property for data.id is an array; if not, make it one
                if (!Array.isArray(window.mesureofnewaddedfood[data.id])) {
                    window.mesureofnewaddedfood[data.id] = [];
                }

                // Safely push the selected quantity unit
                window.mesureofnewaddedfood[data.id].push({"old":data.selected_qty_unit});

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
                $('#addMoreFoodModal #modalEnergy').text((parseFloat(data.energy) || 0).toFixed(1) + 'kJ');
                $('#addMoreFoodModal #description').val(data.description);

                setupNutritionSync(parseFloat(data.carbs) || 0, parseFloat(data.protein) || 0, parseFloat(data.fat) || 0, parseFloat(data.energy) || 0, '#addMoreFoodModal');
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
            const energy = parseFloat($('#addMoreFoodModal #modalEnergy').text()) || 0;

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
                    let qtyToUse = rawQtyInput;

                    // Only round if it's a decimal and the unit is g/ml/mL and NOT a fraction
                    const isFraction = rawQtyInput.includes('/');

                    if (!isFraction && ["g", "ml", "mL"].includes(unit)) {
                        qtyToUse = Math.round(parsedQty).toString();
                    } else if (!isFraction && parsedQty % 1 === 0) {
                        // For non-decimal whole numbers (e.g., 2.0), keep as integer string
                        qtyToUse = parsedQty.toString();
                    }

                    selectedQtyUnits.push({ qty: qtyToUse, unit: unit, checked: isChecked });

                    if (isChecked) {
                        checkedQtyUnits.push(`${qtyToUse}${["g", "ml", "mL"].includes(unit) ? unit : ' ' + unit}`);
                        qty = qtyToUse;
                    }
                }
            });

            // Initialize window.mesureofnewaddedfood if it doesn’t exist
            if (typeof window.mesureofnewaddedfood === 'undefined') {
                window.mesureofnewaddedfood = {};
            }

            // Check if the property for foodId is an array; if not, make it one
            if (!Array.isArray(window.mesureofnewaddedfood[foodId])) {
                window.mesureofnewaddedfood[foodId] = [];
            }

            // Safely push the selected quantity unit
            window.mesureofnewaddedfood[foodId].push({"new":selectedQtyUnits});

            var percentage = getPercentageChange(foodId);

            const $mealContainer = $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`);
           
            const $tableBody = $mealContainer.find('.items-table-body');
           
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
                    energy: energy,
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
                        swapItemsHTML = swapFoods.map(swapItem => {
                            const checkedQtyText = getQtyDisplay(
                                swapItem.selected_qty_unit || [],
                                swapItem.qty,
                                swapItem.unit
                            );

                        return `
                            <li class="list-unstyled mb-3" data-swap-item-id="${swapItem.id}">
                                <div class="d-flex justify-content-between align-items-start mb-0">
                                    <div class="col-9">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]"
                                                value="${swapItem.id}" class="form-check-input me-2 d-none" checked
                                                data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}" data-energy="${swapItem.energy}">
                                            <label class="form-check-label">${swapItem.title}</label>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-info"
                                            data-description="${swapItem.description}" data-bs-toggle="tooltip" data-bs-placement="top" title="${swapItem.description}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success edit-swap-item ms-0"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-swap-qty="${swapItem.qty}" data-swap-unit="${swapItem.unit}"
                                            data-selected-qty-unit='${JSON.stringify(swapItem.selected_qty_unit)}'
                                            title="Edit"><i class="icofont-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-swap-item"
                                            data-swap-item-id="${swapItem.id}" data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Delete">
                                            <i class="icofont-ui-delete"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="px-2 mb-2 fw-bold">${checkedQtyText}</p>
                                        <p class="mb-0 px-2">Energy: ${parseFloat(swapItem.energy)}kJ | Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                    </div>
                                </div>
                            </li>
                            `;
                        }).join('');

                        swapItemsHTML += `
                            <li class="d-flex justify-content-between align-items-start mt-1">
                                <div class="col-9"></div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-more-swap-item ms-2"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                        title="Add More"><i class="icofont-plus"></i></button>
                                </div>
                            </li>`;

                        // swapFoods.map(swapItem => 
                        //     updateFoodCount(swapItem.id, 1, 'green')
                        // );

                    } else {
                        swapItemsHTML = `
                            <li class="d-flex justify-content-between align-items-start mb-2">
                                <div class="col-9"><span class="text-muted">No swap items available</span></div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-swap-item ms-2"
                                        data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" title="Add">
                                        <i class="icofont-plus"></i>
                                    </button>
                                </div>
                            </li>`;
                    }

                    const rowHTML = `
                        <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${item.id}" data-item-id="${item.id}">
                            <td width="30" class="align-middle text-center">
                                <span class="drag-handle" style="cursor:move; margin-right:8px;"><i class="fa fa-bars"></i></span>
                            </td>
                            <td class="text-wrap" width="50%">
                                <div class="d-flex justify-content-between align-items-start mb-0">
                                    <div class="col-9">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                                value="${item.id}" class="form-check-input me-2 d-none" checked
                                                data-carbs="${carbs}" data-protein="${protein}" data-fat="${fat}" data-energy="${energy}">
                                            <label class="form-check-label flex-grow-1">${item.title}</label>
                                        </div>
                                        
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="${item.description}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success edit-item main-food-edit-link"
                                            data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${qty}" data-item-unit="${unit}"
                                            data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                            title="Edit"><i class="icofont-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                            data-item-id="${item.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}"
                                            title="Delete"><i class="icofont-ui-delete"></i></button>
                                    </div>
                                </div>
                                    <div class="row">
                                    <div class="col">
                                        <p class="px-2 mb-2 fw-bold">(${checkedQtyUnits.join(' or ')})</p>
                                        <p class="px-2 mb-0">Energy: ${parseFloat(energy)}kJ | Protein: ${Math.round(protein)}g | Carb: ${Math.round(carbs)}g | Fat: ${Math.round(fat)}g</p>
                                    </div>
                                </div>
                            </td>
                            <td width="50%">
                                <ul class="list-unstyled">${swapItemsHTML}</ul>
                            </td>
                        </tr>
                    `;

                    $tableBody.append(rowHTML);
                    $tableBody.find('button.main-food-edit-link').click();
                    $('#editItemModal #ratio').val(parseFloat(percentage));
                    $('#editItemModal').modal('hide');
                    $('#editItemModal #saveItemChanges').click();

                    updateFoodCount(item.id, 1, 'green')

                    calculateTotals(planId, mealTimeId, mealId);
                    calculateMealNutrition();
                    $('#addMoreFoodModal').modal('hide');
                }
            });
        });

        function calculateTotals(planId, mealTimeId, mealId) {
            let totalCarbs = 0,
                totalProtein = 0,
                totalFat = 0;
                totalEnergy = 0;

            const mealContainerId = `#mealContainer_${planId}_${mealTimeId}_${mealId}`;

            // Loop through each <tr> in the tbody
            $(`${mealContainerId} tbody tr`).each(function () {
                const $row = $(this);

                // Find checked input inside first <td> (main item)
                const $mainItemInput = $row.find('td').eq(1).find('input[type="checkbox"]:checked');
                if ($mainItemInput.length) {
                    totalCarbs += parseFloat($mainItemInput.data('carbs')) || 0;
                    totalProtein += parseFloat($mainItemInput.data('protein')) || 0;
                    totalFat += parseFloat($mainItemInput.data('fat')) || 0;
                    totalEnergy += parseFloat($mainItemInput.data('energy')) || 0;
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
            $(`${mealContainerId} .totalCarbs`).text(Math.round(totalCarbs)+'g');
            $(`${mealContainerId} .totalProtein`).text(Math.round(totalProtein)+'g');
            $(`${mealContainerId} .totalFat`).text(Math.round(totalFat)+'g');
            $(`${mealContainerId} .totalEnergy`).text(Math.round(totalEnergy)+'kJ');
        }

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
                                    const imagePath = item.image ? `{{ webAssets('storage/') }}/${item.image}` : 'https://via.placeholder.com/50';
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
                                                        data-energy="${product.nutrition.energy || 0}"
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
                                                <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${food.id}][]" value="${swapItem.id}" class="form-check-input me-2 d-none" data-carbs="${swapItem.carbs}" data-protein="${swapItem.protein}" data-fat="${swapItem.fat}" data-energy="${swapItem.energy}"  checked>
                                                <label>${swapItem.name} (${swapItem.qty} ${swapItem.unit})</label>
                                            </div>
                                            <p class="mb-0 px-2">Energy: ${parseFloat(swapItem.energy)}kJ | Protein: ${Math.round(swapItem.protein)}g | Carb: ${Math.round(swapItem.carbs)}g | Fat: ${Math.round(swapItem.fat)}g</p>
                                        </li>
                                    `).join('') :
                                    '<span class="text-muted">No swap items available</span>';

                                tableBody.append(`
                                    <tr data-food-id="${food.id}" data-item-id="${food.id}">
                                        <td width="30" class="align-middle text-center">
                                            <span class="drag-handle" style="cursor:move; margin-right:8px;"><i class="fa fa-bars"></i></span>
                                        </td>
                                        <td width="45%">
                                            <div class="d-flex align-items-start">
                                                <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" 
                                                    value="${food.id}" 
                                                    class="form-check-input me-2 d-none" data-carbs="${food.carbs}" data-protein="${food.protein}" data-fat="${food.fat}" data-energy="${food.energy}"  checked>
                                                <label class="form-check-label flex-grow-1">${food.title} (${food.qty}${food.unit})</label>
                                            </div>
                                             <p class="mb-0 px-2">Energy: ${parseFloat(food.energy)}kJ | Protein: ${Math.round(food.protein)}g | Carb: ${Math.round(food.carbs)}g | Fat: ${Math.round(food.fat)}g</p>
                                        </td>
                                        <td width="45%">
                                            <ul class="list-unstyled">${swapItemsHTML}
                                                <li class="d-flex justify-content-between align-items-start mt-1">
                                                    <div class="col-9"></div>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary add-more-swap-item ms-2"
                                                            data-item-id="${food.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                            data-meal-time-id="${mealTimeId}" data-user-id="${userId}" 
                                                            title="Add More"><i class="icofont-plus"></i></button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td width="10%">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="${food.description}">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm edit-food btn-outline-success" 
                                                    data-food-id="${food.id}" 
                                                    data-meal-id="${mealId}" 
                                                    data-swap-foods='${JSON.stringify(swapFoods)}',
                                                    data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}" 
                                                    data-swapfood-qty="${swapFoods[0]?.qty || ''}" 
                                                    data-swapfood-unit="${swapFoods[0]?.unit || ''}" 
                                                    data-food-qty="${food.qty}"
                                                    data-food-unit="${food.unit}">
                                                <i class="icofont-edit"></i>
                                            </button>
                                            <button class="btn  btn-sm delete-food btn-outline-danger" 
                                                    data-food-id="${food.id}" 
                                                    data-meal-id="${mealId}"
                                                    data-swapfood-id="${swapFoods[0]?.swap_item_id || ''}">
                                                <i class="icofont-ui-delete"></i>
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
                        energy: energy,
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
                                                                title="Add"><i class="icofont-plus"></i>
                                                            </button>
                                                        </div>
                                                    </li>`;

                                // Append the single food item row to the table
                                tableBody.append(`
                                    <tr id="itemRow_${planId}_${mealTimeId}_${mealId}_${food.id}" data-item-id="${food.id}">
                                        <td width="30" class="align-middle text-center">
                                            <span class="drag-handle" style="cursor:move; margin-right:8px;"><i class="fa fa-bars"></i></span>
                                        </td>
                                        <td class="text-wrap" width="50%">
                                            <div class="d-flex justify-content-between align-items-start mb-0">
                                                <div class="col-9">
                                                    <div class="d-flex align-items-start">
                                                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]"
                                                            value="${food.id}" class="form-check-input me-2 d-none" checked
                                                            data-carbs="${food.carbs}" data-protein="${food.protein}" data-fat="${food.fat}" data-energy="${food.energy}">
                                                        <label class="form-check-label flex-grow-1">${food.title}</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="${food.description}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success edit-item"
                                                        data-item-id="${food.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}" data-item-qty="${food.qty}" data-item-unit="${food.unit}"
                                                        data-selected-qty-unit='${JSON.stringify(selectedQtyUnits)}'
                                                        title="Edit"><i class="icofont-edit"></i></button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                                                        data-item-id="${food.id}" data-meal-id="${mealId}" data-plan-id="${planId}"
                                                        data-meal-time-id="${mealTimeId}" data-user-id="${userId}"
                                                        title="Delete"><i class="icofont-ui-delete"></i></button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <p class="px-2 mb-2 fw-bold">(${
                                                        typeof food.qty === 'string' && food.qty.includes('/')
                                                            ? food.qty
                                                            : (
                                                                ["g", "ml", "mL"].includes(food.unit)
                                                                    ? Math.round(parseFloat(food.qty))
                                                                    : food.qty
                                                            )
                                                        }${["g", "ml", "mL"].includes(food.unit) ? food.unit : ' ' + food.unit})
                                                    </p>
                                                    <p>Energy: ${parseFloat(food.energy)}kJ | Protein: ${Math.round(food.protein)}g | Carb: ${Math.round(food.carbs)}g | Fat: ${Math.round(food.fat)}g</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="50%">
                                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                                        </td>
                                    </tr>
                                `);
                            // }
                            updateFoodCount(food.id, 1, 'green')
                            calculateTotals(planId, mealTimeId, mealId);
                            calculateMealNutrition();

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
    });

    $(document).ready(function () {
        $(document).on('click', '.view-info', function (e) {
            e.preventDefault();

            const $this = $(this);
            const description = $this.data('description');

            // ✅ Correctly locate the corresponding label text
            const foodName = $this.closest('.d-flex').find('label').text().trim();

            const modal = $('#itemInfoModal');

            if (modal.length) {
                modal.find('.modal-title').text(foodName || 'Item Information');
                modal.find('#modalDescription').text(description || 'No description available.');

                const bsModal = new bootstrap.Modal(modal[0]);
                bsModal.show();
            }
        });

         function initializeTooltips() {
            // First dispose all existing tooltips
            $('[data-bs-toggle="tooltip"]').tooltip('dispose');
            
            // Initialize new tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                const $this = $(this);
                const description = $this.data('description');
                
                // Set both title attributes for consistency
                $this.attr({
                    'title': description,
                    'data-bs-original-title': description
                });
                
                // Initialize Bootstrap tooltip
                new bootstrap.Tooltip(this, {
                    trigger: 'hover',
                    placement: 'top',
                    html: true
                });
            });
        }

        // Initialize tooltips on page load
        initializeTooltips();

        // Handle dynamic content updates
        $(document).on('shown.bs.modal', function() {
            initializeTooltips();
        });

        // Handle AJAX content updates
        $(document).ajaxComplete(function() {
            initializeTooltips();
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

</script>
@endpush
@endsection
