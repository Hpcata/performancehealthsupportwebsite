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
                    <a href="{{ route('admin.purchase-plans.index') }}" class="btn btn-primary btn-set-task w-sm-100 back-button">Back</a>
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
                    <div class="panel-group col-7" id="accordion">
                        
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
                                        @foreach ($plan->mealTimes as $mealTime)
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
                        <div class="col-form-label">
                            <label class="col-form-label" for="meals">Choose Meals:</label>
                        </div>
                        <div >
                        <select name="meals[]" id="meals" class="form-control w-100 " multiple>
                            @foreach ($meals as $meal)
                                <option value="{{ $meal->id }}">{{ $meal->title }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-form-label">
                        <label class="col-form-label" for="swapFoods">Choose Swap Foods:</label>
                        </div>
                        <div>
                        <select name="swap_foods[]" id="swapFoods" class="form-control w-100" multiple>
                            @foreach ($step5Foods as $category => $foods)
                                @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->title }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="saveSwapFoods">Save</button>
                </form>
            </div>
        </div>
    </div>
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
    // Track whether there are unsaved changes
    let hasUnsavedChanges = false;

    // Detect changes in input fields
    document.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', () => {
            hasUnsavedChanges = true;
        });
    });

    // Listen for beforeunload to show the custom modal
    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges) {
            // Prevent the page from unloading and suppress the browser's default dialog
            e.preventDefault();

            // Show your custom modal
            document.getElementById('savePlanModal').style.display = 'block';

            // Display a message for browsers that require it
            e.returnValue = ''; // This is required for some browsers like Chrome

            // Prevent the default dialog from appearing
            return '';  // Returning an empty string triggers the custom modal instead of the default browser dialog
        }
    });

    // Handle "Save Changes" button click
    document.getElementById('saveChanges').addEventListener('click', function () {
        // Code to save data (e.g., make an API call to save)
        hasUnsavedChanges = false;  // Reset the flag
        document.getElementById('createPlanForm').submit();
        document.getElementById('savePlanModal').style.display = 'none';
    });

    // Handle "Leave Without Saving" button click
    document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
        hasUnsavedChanges = false;  // Reset the flag
        document.getElementById('savePlanModal').style.display = 'none';  // Hide modal
    });
</script>
<script>
    $(document).ready(function () {
        $('#meals').select2({
            placeholder: "Select Meals",
            allowClear: true
        });

        $('#swapFoods').select2({
            placeholder: "Select Swap Foods",
            allowClear: true
        });
        $('.meal-items-select').select2({
            placeholder: "Select Mels",
            allowClear: true
        })


        // $('#swapFoodsModal').on('hidden.bs.modal', function () {
        //     // Clear the selected meals and swap foods when the modal is closed
        //     $('#meals').val([]).trigger('change');
        //     $('#swapFoods').val([]).trigger('change');
        // })
        // Handle checkbox click to open the modal
        // Initialize an object in local storage to store food selections
        // $('#foodSelectionsInput').val(); 
        // var storedSelections = $('#foodSelectionsInput').val(); // Get from hidden field
        // if (storedSelections) {
        //     localStorage.setItem('foodSelections', storedSelections); 
        // }

        // localStorage.removeItem('foodSelections');
        // $('#foodSelectionsInput').val('');

        // if (!localStorage.getItem('foodSelections')) {
        //     localStorage.setItem('foodSelections', JSON.stringify({}));
        // }

        // // Handle checkbox click to open the modal
        // $('.food-checkbox').on('change', function () {
        //     const foodId = $(this).data('food-id');

        //     if ($(this).is(':checked')) {
        //         // Clear modal selections
        //         $('#meals').val([]).trigger('change');
        //         $('#swapFoods').val([]).trigger('change');

        //         // Populate modal if data exists in localStorage
        //         const storedSelections = JSON.parse(localStorage.getItem('foodSelections')) || {};
        //         Object.keys(storedSelections).forEach(mealId => {
        //             if (storedSelections[mealId][foodId]) {
        //                 $('#meals').val([mealId]).trigger('change');
        //                 $('#swapFoods').val(storedSelections[mealId][foodId]).trigger('change');
        //             }
        //         });

        //         // Store the current food ID in the modal
        //         $('#swapFoodsModal').data('food-id', foodId);

        //         // Open modal
        //         $('#swapFoodsModal').modal('show');
        //     } else {
        //         // Remove food from all meals in localStorage
        //         const storedSelections = JSON.parse(localStorage.getItem('foodSelections')) || {};
        //         Object.keys(storedSelections).forEach(mealId => {
        //             if (storedSelections[mealId][foodId]) {
        //                 delete storedSelections[mealId][foodId];
        //                 if (Object.keys(storedSelections[mealId]).length === 0) {
        //                     delete storedSelections[mealId];
        //                 }
        //             }
        //         });
        //         localStorage.setItem('foodSelections', JSON.stringify(storedSelections));

        //         // Remove food from form dynamically
        //         $(`.meal-container input[value="${foodId}"]`).closest('.meal-container').remove();
        //     }
        // });

        // $('#saveSwapFoods').on('click', function () {
        //     const selectedMeals = $('#meals').val();
        //     const selectedSwapFoods = $('#swapFoods').val();
        //     const foodId = $('#swapFoodsModal').data('food-id');

        //     if (selectedMeals && selectedMeals.length && selectedSwapFoods && selectedSwapFoods.length) {
        //         const storedSelections = JSON.parse(localStorage.getItem('foodSelections')) || {};

        //         // Save the selected meals and swap foods
        //         selectedMeals.forEach(mealId => {
        //             storedSelections[mealId] = storedSelections[mealId] || {};
        //             storedSelections[mealId][foodId] = selectedSwapFoods;

        //             // Update form dynamically
        //             const mealDropdown = $(`#mealItems${mealId}`);
        //             const mealContainer = $(`#selectedMeals${mealId}`);

        //             // Add food ID to the dropdown if not already present
        //             if (!mealDropdown.find(`option[value="${foodId}"]`).length) {
        //                 mealDropdown.append(new Option(`Food ${foodId}`, foodId));
        //             }

        //             // Update meal items table dynamically
        //             const foodRow = `
        //                 <tr id="foodRow_${mealId}_${foodId}">
        //                     <td>${foodId}</td>
        //                     <td>${selectedSwapFoods.join(', ')}</td>
        //                 </tr>
        //             `;
        //             if (!mealContainer.find(`#foodRow_${mealId}_${foodId}`).length) {
        //                 mealContainer.find('tbody').append(foodRow);
        //             }
        //         });

        //         // Update localStorage
        //         localStorage.setItem('foodSelections', JSON.stringify(storedSelections));

        //         // Close modal
        //         $('#swapFoodsModal').modal('hide');
        //     } else {
        //         alert('Please select at least one meal and one swap food.');
        //     }
        // });


        // $('#createPlanForm').on('submit', function() {
        //     // Get the foodSelections data from localStorage
        //     var foodSelections = localStorage.getItem('foodSelections');

        //     // Populate the hidden input field with the data
        //     $('#foodSelectionsInput').val(foodSelections); 

        //     // localStorage.removeItem('foodSelections');

        // });
    });

    // document.addEventListener("DOMContentLoaded", function () {
    //     let hasUnsavedChanges = false;
    //     // Detect changes in input fields
    //     document.querySelectorAll('input, textarea').forEach(input => {
    //         console.log('12333');
    //         input.addEventListener('input', () => {
    //             hasUnsavedChanges = true;
    //         });
    //     });

    //     // Listen for beforeunload to show the custom modal
    //     window.addEventListener('beforeunload', function (e) {
    //         console.log('beforeunload event triggered');
    //         console.log(hasUnsavedChanges);
    //         if (hasUnsavedChanges) {
    //             // Prevent the page from unloading
    //             e.preventDefault();
    //             e.returnValue = ''; // Some browsers need this for the dialog to show

    //             // Show your custom modal instead
    //             document.getElementById('savePlanModal').style.display = 'block';
    //             return ''; // Some browsers need this to show the prompt
    //         }
    //     });

    //     // Save changes and close the modal
    //     document.getElementById('saveChanges').addEventListener('click', function () {
    //         // Save data here
    //         console.log('Saving changes...');
    //         hasUnsavedChanges = false;  // Reset the flag
    //         document.getElementById('savePlanModal').style.display = 'none';  // Hide modal
    //     });

    //     // Allow the user to leave without saving
    //     document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
    //         console.log('Leaving without saving...');
    //         hasUnsavedChanges = false;  // Reset the flag
    //         document.getElementById('savePlanModal').style.display = 'none';  // Hide modal
    //     });
    // });

    // document.addEventListener('DOMContentLoaded', function () {
    //     // Select the "Back" button
    //     const backButton = document.querySelector('.back-button');

    //     backButton.addEventListener('click', function (event) {
    //         // Prevent default navigation
    //         event.preventDefault();

    //         // Show confirmation popup
    //         const userConfirmed = confirm("You have unsaved changes. Do you want to save your current plan before leaving?");
            
    //         if (userConfirmed) {
    //             // Optionally, trigger form submission here if you want to save
    //             document.getElementById('createPlanForm').submit();
    //             // Replace with form submission or saving logic
    //         } else {
    //             // Allow navigation to proceed
    //             window.location.href = this.href;
    //         }
    //     });
    // });
</script>
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

        // Step 1: Handle meal time checkbox changes
        $('.meal-time-checkbox').on('change', function () {
            const checkbox = $(this);
            const planId = checkbox.closest('.panel').find('input[name="plan_id[]"]').val();
            const mealTimeId = checkbox.data('mealtime-id');

            const dropdownId = `#addMealDropdown${planId}_${mealTimeId}`;
            const selectedMealsId = `#selectedMeals${planId}_${mealTimeId}`;
            const mealSelect = $(dropdownId).find('select');

            if (checkbox.is(':checked')) {
                $(dropdownId).show(); // Show Add Meal dropdown
                $(selectedMealsId).show(); // Show Selected Meals container

                // Load meals dynamically
                $.ajax({
                    url: '{{ route("admin.get-meals-by-mealtime") }}',
                    method: 'POST',
                    data: {
                        meal_time_id: mealTimeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            mealSelect.empty(); // Clear previous options
                            response.meals.forEach(meal => {
                                mealSelect.append(`<option value="${meal.id}">${meal.name}</option>`);
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
                $(dropdownId).hide();
                $(selectedMealsId).hide();
                mealSelect.val([]).trigger('change');
                $(selectedMealsId).empty(); // Clear selected meals
            }
        });
        let userId = $('#user_id').val();
        console.log(userId);
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
            // console.log(newMeals);
            previouslySelectedMeals[`${planId}_${mealTimeId}`] = currentSelectedMeals;

            // Remove unselected meals
            unselectedMeals.forEach(mealId => {
               
                $(`#mealContainer_${planId}_${mealTimeId}_${mealId}`).remove();
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
                            const mealContainer = createMealContainer(planId, mealTimeId, mealId, response);
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

        // Step 3: Modal popup for selecting swap foods
        $('#saveSwapFoods').on('click', function () {
            const selectedMeals = $('#meals').val(); // Get selected meal IDs
            const selectedFoods = $('#swapFoods').val(); // Get selected food IDs
            const foodId = $('#swapFoodsModal').data('food-id');
            const foodName = $('#swapFoodsModal').data('food-name');

            if (!selectedMeals || selectedMeals.length === 0) {
                alert('Please select at least one meal.');
                return;
            }
            // console.log(mealtimeID)
            // console.log(planID)
            
            // Fetch stored swap foods from the database
            $.ajax({
                url: '{{ route("admin.get-swap-foods") }}', // API to fetch stored swap foods
                method: 'POST',
                data: { food_id: foodId, swap_food_ids: selectedFoods, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    let swapFoods = response.swapFoods || []; // Database swap foods
                    
                    // Merge selected foods and stored swap foods (remove duplicates)
                    // let mergedSwapFoods = [...new Set([...selectedFoods, ...storedSwapFoods])];

                    // Loop through selected meals and add foods dynamically
                    selectedMeals.forEach(mealId => {
                        const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
                        const mealContainer = $(mealContainerId);

                        if (mealContainer.length === 0) {
                            alert(`Meal container for Meal ID ${mealId} not found.`);
                            return;
                        }

                        const tableBody = mealContainer.find('.items-table-body');

                        // Check if the food item is already in the meal container
                        if (tableBody.find(`tr[data-food-id="${foodId}"]`).length === 0) {
                            let swapItemsHTML = swapFoods.map(item => `
                                <li>
                                    <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${foodId}][]" value="${item.id}" class="form-check-input">
                                    <label>${item.name}</label>
                                </li>
                            `).join('');

                            tableBody.append(`
                                <tr data-food-id="${foodId}">
                                    <td>
                                        <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]" value="${foodId}" class="form-check-input">
                                        <label class="form-check-label">${foodName}</label>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">${swapItemsHTML}</ul>
                                    </td>
                                </tr>
                            `);
                        }
                    });

                    // Save data to the database
                    $.ajax({
                        url: '{{ route("admin.save-swap-food") }}',
                        method: 'POST',
                        data: {
                            food_id: foodId,
                            swap_foods: swapFoods, // Save combined swap foods
                            meal_ids: selectedMeals,
                            user_id: userId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                console.log('Swap foods saved successfully.');
                                updateFoodCount(foodId);
                            } else {
                                alert('Failed to save swap foods.');
                            }
                        },
                        error: function () {
                            alert('Error occurred while saving swap foods.');
                        }
                    });

                    // Uncheck all food checkboxes
                    $('.food-checkbox').prop('checked', false);

                    // Close modal & reset fields
                    $('#swapFoodsModal').modal('hide');
                    $('#swapFoods').val([]).trigger('change');
                    $('#meals').val([]).trigger('change');
                },
                error: function () {
                    alert('Error fetching stored swap foods.');
                }
            });
        });
        // $('#saveSwapFoods').on('click', function () {
        //     const selectedMeals = $('#meals').val(); // Get selected meal IDs
        //     const selectedFoods = $('#swapFoods').val(); // Get selected food IDs
        //     const foodId = $('#swapFoodsModal').data('food-id');
        //     const foodName = $('#swapFoodsModal').data('food-name');

        //     if (!selectedMeals || selectedMeals.length === 0) {
        //         alert('Please select at least one meal.');
        //         return;
        //     }
        //     console.log(mealtimeID)
        //     console.log(planID)
            
        //     // Loop through selected meals and add foods dynamically
        //     selectedMeals.forEach(mealId => {
        //         const mealContainerId = `#mealContainer_${planID}_${mealtimeID}_${mealId}`;
        //         const mealContainer = $(mealContainerId);

        //         if (mealContainer.length === 0) {
        //             alert(`Meal container for Meal ID ${mealId} not found.`);
        //             return;
        //         }

        //         const tableBody = mealContainer.find('.items-table-body');

        //         // Loop through selected foods and add them dynamically
        //         // Check if no swap foods are selected
        //         if (!selectedFoods || selectedFoods.length === 0) {
        //             if (tableBody.find(`tr[data-food-id="${foodId}"]`).length === 0) {
        //                 tableBody.append(`
        //                     <tr data-food-id="${foodId}">
        //                         <td>
        //                             <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]" value="${foodId}" class="form-check-input">
        //                             <label class="form-check-label">${foodName}</label>
        //                         </td>
        //                         <td>
        //                             <span class="text-muted">No swap items available</span>
        //                         </td>
        //                     </tr>
        //                 `);
        //             }
        //         } else {
        //             // Loop through selected foods and add them dynamically
        //             selectedFoods.forEach(swapfoodId => {
        //                 if (tableBody.find(`tr[data-food-id="${foodId}"]`).length === 0) {
        //                     const swapFoodName = $(`#swapFoods option[value="${swapfoodId}"]`).text();

        //                     const swapItemsHTML = selectedFoods.map(swapItemId => `
        //                         <li>
        //                             <input type="checkbox" name="swap_items[${planID}][${mealtimeID}][${mealId}][${foodId}][]" value="${swapItemId}" class="form-check-input">
        //                             <label>${$(`#swapFoods option[value="${swapItemId}"]`).text()}</label>
        //                         </li>
        //                     `).join('');

        //                     tableBody.append(`
        //                         <tr data-food-id="${foodId}">
        //                             <td>
        //                                 <input type="checkbox" name="items[${planID}][${mealtimeID}][${mealId}][]" value="${foodId}" class="form-check-input">
        //                                 <label class="form-check-label">${foodName}</label>
        //                             </td>
        //                             <td>
        //                                 <ul class="list-unstyled">${swapItemsHTML}</ul>
        //                             </td>
        //                         </tr>
        //                     `);
        //                 }
        //             });
        //         }

        //         $.ajax({
        //             url: '{{ route("admin.save-swap-food") }}',
        //             method: 'POST',
        //             data: {
        //                 food_id: foodId,
        //                 swap_food_ids : selectedFoods,
        //                 meal_ids: selectedMeals,
        //                 user_id: userId,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function (response) {
        //                 if (response.success) {
        //                     console.log('Swap foods saved successfully.');
        //                 } else {
        //                     alert('Failed to save swap foods.');
        //                 }
        //             },
        //             error: function () {
        //                 alert('Error occurred while saving swap foods.');
        //             }
        //         });
                
        //     });

        //     // **Uncheck all food-checkbox elements**
        //     $('.food-checkbox').prop('checked', false);

        //     // Close the modal after saving
        //     $('#swapFoodsModal').modal('hide');
        //     $('#swapFoods').val([]).trigger('change');
        //     $('#meals').val([]).trigger('change');
        // });

        // Show/Hide modal
        $('.food-checkbox').on('change', function () {
            const foodId = $(this).data('food-id');
            const foodName = $(this).data('food-name');

            if ($(this).is(':checked')) {
                $('#swapFoodsModal').data('food-id', foodId);
                $('#swapFoodsModal').data('food-name', foodName);

                // Open the modal
                $('#swapFoodsModal').modal('show');
            }
        });

        $('#closeSwapFoodsModal').on('click', function () { 
            $('#swapFoodsModal').modal('hide');
        });
    
        $(window).on('click', function (event) {
            if ($(event.target).is('#swapFoodsModal')) {
                $('#swapFoodsModal').hide();
            }
        });

        function createMealContainer(planId, mealTimeId, uniqueMealId, response) {
            const mealName = response.meal_name;
            const items = response.data;

            let mealContainer = $(`
                <div id="mealContainer_${planId}_${mealTimeId}_${uniqueMealId}" class="meal-container mt-3">
                    <input type="hidden" name="meals[${planId}][${mealTimeId}][]" value="${uniqueMealId}">
                    <h5 style="color:#7258db;">${mealName} (Meal)</h5>
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
                const swapItemsHTML = item.swapItems.length
                    ? item.swapItems.map(swapItem => `
                        <li>
                            <input type="checkbox" name="swap_items[${planId}][${mealTimeId}][${uniqueMealId}][${item.id}][]" value="${swapItem.id}" class="form-check-input">
                            <label>${swapItem.name}</label>
                        </li>
                    `).join('')
                    : '<span class="text-muted">No swap items available</span>';

                tableBody.append(`
                    <tr>
                        <td>
                            <input type="checkbox" name="items[${planId}][${mealTimeId}][${uniqueMealId}][]" value="${item.id}" class="form-check-input">
                            <label class="form-check-label">${item.name}</label>
                        </td>
                        <td>
                            <ul class="list-unstyled">${swapItemsHTML}</ul>
                        </td>
                    </tr>
                `);
            });

            return mealContainer;
        }

        function updateFoodCount(foodId) {
            let countLabel = $(`#setp5Food${foodId}`).siblings('.form-check-label');
            let countText = countLabel.text();

            // Extract current count from label text (if any)
            let match = countText.match(/\((\d+)\)$/);
            let currentCount = match ? parseInt(match[1]) : 0;

            // Increment the count
            let newCount = currentCount + 1;

            // Update the label with new count
            countLabel.text(countText.replace(/\(\d+\)$/, '') + ` (${newCount})`);
        }
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
