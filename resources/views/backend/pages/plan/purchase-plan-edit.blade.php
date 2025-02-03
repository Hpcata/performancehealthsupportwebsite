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
                    <form action="{{ route('admin.purchase-plans.update') }}" method="POST" class="bg-light">
                        @csrf
                        @method('PUT')
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
                                                            class="form-select meal-items-select" 
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

<div class="modal" id="foodModal" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="foodModalLabel">Add Food</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form id="addFoodForm">
                        <div class="row g-3 align-items-center">
                            <input type="hidden" name="plan_id" id="planId" value="">
                            <input type="hidden" name="meal_time_id" id="mealTimeId" value="">
                            <input type="hidden" name="user_id" id="userId" value="">
                            <input type="hidden" name="meal_id" id="mealId" value="">

                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="" required>
                            </div>

                            <!-- Short Description Field -->
                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2"></textarea>
                            </div>

                            <!-- Full Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Full Description</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>

                            <!-- Quantity Field -->
                            <div class="col-md-12">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="text" name="qty" class="form-control" value="{{ $item->qty ?? ''}}" 
                                    placeholder="Enter quantity and unit (e.g., 200 ml, 1 cup, 100 g)">
                            </div>

                            <!-- Protein Field -->
                            <div class="col-md-12">
                                <label for="carbs" class="form-label">Protein</label>
                                <input type="number" name="protein" class="form-control" value="{{ $item->protein ?? '0' }}" 
                                    step="0.01" min="0" placeholder="Enter Protein">
                                <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                            </div>

                            <!-- Carbohydrate Field -->
                            <div class="col-md-12">
                                <label for="carbs" class="form-label">Carbohydrate</label>
                                <input type="number" name="carbs" class="form-control" value="{{ $item->carbs ?? '0' }}" 
                                    step="0.01" min="0" placeholder="Enter Carbohydrate">
                                <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                            </div>

                            <!-- Is Swapped Field -->
                            <div class="col-md-12">
                                <label for="is_swiped" class="form-label">Is Swapped? &nbsp;</label>
                                <small class="form-text text-muted">(Is this item used in the swapped list?)</small>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_swiped" id="is_swiped_yes" value="1" 
                                        {{ (isset($item) && $item->is_swiped == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_swiped_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_swiped" id="is_swiped_no" value="0" 
                                        {{ (!isset($item) || $item->is_swiped == 0) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_swiped_no">No</label>
                                </div>
                            </div>

                            <!-- Swap Items Selection -->
                            <div class="col-md-12" id="swapItemsContainer" style="display: none;">
                                <label for="swap_item_ids" class="form-label">Swap Items</label>
                                <select name="swap_item_ids[]" class="form-control select2" id="swapItemsSelect" multiple>
                                    
                                </select>
                            </div>

                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
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

</script>
<!-- JavaScript for Dynamic Checkbox Enabling/Disabling -->
<script>
    $(document).ready(function () {
    const previouslySelectedMeals = {};
    const preSelectedMeals = @json($selectedMeals);
    const preSelectedItems = @json($selectedItems); // Pre-selected user items
    const preSelectedSwapItems = @json($selectedSwapItems); // Pre-selected swap items
    const payment = @json($payment);
    const userId = payment.user_id;

    $('.meal-time-checkbox').each(function () {
        const checkbox = $(this);
        const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
        const mealTimeId = checkbox.data('mealtime-id');
        const userId = checkbox.closest('.panel').find('input[name="user_id"]').val();
        const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
        const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
        const mealSelect = $(dropdownId).find('select');

        if (preSelectedMeals[planId][mealTimeId]) {
            checkbox.prop('checked', true);
            $(dropdownId).show();
            $(selectedMealsId).show();

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
                        const selectedMeal = preSelectedMeals[planId][mealTimeId][meal.id];
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
        } else {
            checkbox.prop('checked', false);
            $(dropdownId).hide();
            $(selectedMealsId).hide();
        }
    });

    $('.meal-time-checkbox').on('change', function () {
        const checkbox = $(this);
        const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
        const mealTimeId = checkbox.data('mealtime-id');

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
                        console.log(response.meals)
                        // Populate new options
                        response.meals.forEach(meal => {
                            mealSelect.append(`
                                <option value="${meal.id}">${meal.name}</option>
                            `);
                        });
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

    $('.meal-items-select').on('change', function () {
        const ids = $(this).attr('id').replace('mealItems', '').split('_');
        const planId = ids[0];
        const mealTimeId = ids[1];

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

                        let mealContainer = $(`
                            <div id="mealContainer_${planId}_${mealTimeId}_${mealId}" class="meal-container mt-3">
                                <input type="hidden" name="meals[${planId}][${mealTimeId}][]" value="${response.meal_id}">
                               <div class="meal-name-edit">
                                    <input type="text" value="${mealName}" class="editable-meal-name" data-meal-time-id="${mealTimeId}"
                                    data-meal-id="${mealId}" data-category-id="" data-plan-id="${planId}" data-user-id="${userId}" style="border: none; font-weight: bold; font-size: 14px; color: #6610f2; width: 50%;" title="Click to edit"/>
                            
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#foodModal" data-meal-id="${mealId}" data-meal-time-id="${mealTimeId}" data-plan-id="${planId}" data-user-id="${userId}">Add Food</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Swap Items</th>
                                            </tr>
                                        </thead>
                                        <tbody class="items-table-body"></tbody>
                                    </table>
                                </div>
                            </div>
                        `);

                        const tableBody = mealContainer.find('.items-table-body');

                        items.forEach(item => {
                            // Check if the item is pre-selected
                            const isSelectedItem = preSelectedItems[mealTimeId] &&
                                                preSelectedItems[mealTimeId][mealId] &&
                                                preSelectedItems[mealTimeId][mealId].includes(item.id);

                            // Generate the HTML for swap items
                            let swapItemsHTML = '';

                            if (item.swapItems && item.swapItems.length > 0) {
                                swapItemsHTML = item.swapItems.map(swapItem => {
                                    const isSelectedSwapItem = preSelectedSwapItems[mealTimeId] &&
                                                            preSelectedSwapItems[mealTimeId][mealId] &&
                                                            preSelectedSwapItems[mealTimeId][mealId][item.id] &&
                                                            preSelectedSwapItems[mealTimeId][mealId][item.id].includes(swapItem.id);

                                    return `
                                        <li>
                                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]" value="${swapItem.id}" class="form-check-input" ${isSelectedSwapItem ? 'checked' : ''}>
                                            <label class="form-check-label">${swapItem.name}</label>
                                        </li>
                                    `;
                                }).join('');
                            } else {
                                swapItemsHTML = '<span class="text-muted">No swap items available</span>';
                            }

                            // Append a new row with the correct separation of item and swap items
                            tableBody.append(`
                                <tr>
                                    <td>
                                        <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" value="${item.id}" class="form-check-input" ${isSelectedItem ? 'checked' : ''}>
                                        <label class="form-check-label">${item.name}</label>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">${swapItemsHTML}</ul>
                                    </td>
                                </tr>
                            `);
                        });


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

    $(document).ready(function () {
        // Event triggered when the modal is about to be shown
        $('#foodModal').on('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = $(event.relatedTarget);

            // Extract info from data-* attributes
            var mealId = button.data('meal-id');
            var mealTimeId = button.data('meal-time-id');
            var planId = button.data('plan-id');
            var userId = button.data('user-id');

            // Update the modal's content
            var modal = $(this);
            modal.find('#mealId').val(mealId);
            modal.find('#mealTimeId').val(mealTimeId);
            modal.find('#planId').val(planId);
            modal.find('#userId').val(userId);
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

    $(document).ready(function () {
        // Handle form submission
        $('#addFoodForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            let formData = new FormData(this); // Gather form data

            $.ajax({
                url: "{{ route('admin.add-food') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                processData: false, // Prevent jQuery from processing data
                contentType: false, // Prevent jQuery from setting content type
                success: function (response) {
                    // Handle success response
                    if (response.success) {
                        alert('Food added successfully!');
                        window.location.reload();
                    } else {
                        alert('Failed to add food. Please try again.'); 
                    }
                },
                error: function () {
                    // Handle error response
                    alert('An error occurred. Please try again.');
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

@endsection
