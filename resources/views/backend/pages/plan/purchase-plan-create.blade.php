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
                <h3 class="fw-bold mb-0">{{ 'Create Plan' }}</h3>
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
                <form action="{{ route('admin.purchase-plans.store') }}" method="POST" class="bg-light">
                    @csrf
                    <div class="panel-group" id="accordion">
                        <!-- Main Plan -->
                        @foreach ($plans as $plan)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseMainPlan">{{ $plan->name }}</a>
                                </h4>
                            </div>
                            <div id="collapseMainPlan" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <input type="hidden" name="plan_id[]" value="{{ $plan->id }}">
                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                                    <!-- Meal Times (Checkboxes) -->
                                    <ul class="list-group mb-4">
                                        @foreach ($mealTimes as $mealTime)
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
                        
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create</button>
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

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
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
                                            <p><strong>Address:</strong> ${userDetails.address || 'N/A'}</p>
                                            <p><strong>Referred By:</strong> ${userDetails.referredBy || 'N/A'}</p>
                                            <p><strong>Occupation:</strong> ${userDetails.occupation || 'N/A'}</p>
                                            <p><strong>Race/Ethnicity/Culture:</strong> ${userDetails.other || 'N/A'}</p>
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

    // Handle meal time checkbox changes
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

    // Handle meal selection/unselection
    $('.meal-items-select').on('change', function () {
        const ids = $(this).attr('id').replace('mealItems', '').split('_');
        const planId = ids[0];
        const mealTimeId = ids[1];

        const selectedMealsContainer = $(`#selectedMeals${planId}_${mealTimeId}`);
        const currentSelectedMeals = $(this).val() || [];
        const oldMeals = previouslySelectedMeals[`${planId}_${mealTimeId}`] || [];

        // Find newly selected and unselected meals
        const newMeals = currentSelectedMeals.filter(mealId => !oldMeals.includes(mealId));
        const unselectedMeals = oldMeals.filter(mealId => !currentSelectedMeals.includes(mealId));
        previouslySelectedMeals[`${planId}_${mealTimeId}`] = currentSelectedMeals;

        // Remove unselected meals
        unselectedMeals.forEach(mealId => {
            $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
        });

        // Fetch and display newly selected meals
        newMeals.forEach(mealId => {
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

                let mealContainer = $(`
                    <div id="mealContainer_${planId}_${mealTimeId}_${mealId}" class="meal-container mt-3">
                        <input type="hidden" name="meals[${planId}][${mealTimeId}][]" value="${response.meal_id}">
                        <h5 style="color:#7258db;">${mealName} (Meal)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Swap Items</th>
                                    </tr>
                                </thead>
                                <tbody class="items-table-body">
                                    <!-- Dynamic rows will be appended here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                `);

                // Populate table rows with items and their swap items
                const tableBody = mealContainer.find('.items-table-body');

                items.forEach(item => {
                    let swapItemsHTML = '';

                    if (item.swapItems && item.swapItems.length > 0) {
                        item.swapItems.forEach(swapItem => {
                            swapItemsHTML += `
                                <li>
                                    <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${mealId}][${item.id}][]" value="${swapItem.id}" class="form-check-input">
                                    <label class="form-check-label">${swapItem.name}</label>
                                </li>
                            `;
                        });
                    } else {
                        swapItemsHTML = '<span class="text-muted">No swap items available</span>';
                    }

                    // Append a new row to the table
                    tableBody.append(`
                        <tr>
                            <td>
                                <input type="checkbox" name="items[${planId}][${mealTimeId}][${mealId}][]" value="${item.id}" class="form-check-input">
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
            alert('Error while fetching meal details.');
        }
    });
});


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
