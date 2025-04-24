@extends('backend.layouts.app')

@section('content')
<style>
    #loader {
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
    #loader img {
        width: 50px; /* Adjust size */
        height: 50px;
    }
</style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($meal) ? 'Edit Meal' : 'Create Meal' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a type="button" href="{{ route('admin.meals.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form id="mealForm" action="{{ isset($meal) ? route('admin.meals.update', $meal) : route('admin.meals.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($meal)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $meal->title ?? '' }}" required>
                            </div>
                            
                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $meal->description ?? '' }}</textarea>
                            </div>
                            <select name="food_ids[]" class="form-control" id="food">
                                               
                            </select>
                            <!-- Meal Times -->
                         {{--  <div class="col-md-12">
                                <label for="meal_times" class="form-label">Meal Times</label>
                                <select name="meal_times[]" id="meal_times" class="form-control select2" multiple>
                                    @foreach ($mealTimes as $mealTime)
                                    <option value="{{ $mealTime->id }}" 
                                        {{ isset($meal) && $meal->mealTimes->contains($mealTime->id) ? 'selected' : '' }}>
                                        {{ $mealTime->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            --}}
                            <!-- Sub Categories -->
                            <div class="col-md-12">
                                <label for="sub_categories" class="form-label">Categories</label>
                                <select name="categories[]" id="categories" class="form-control select2" multiple>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ isset($meal) && $meal->categories->contains($category->id) ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Food Selection Dropdown -->
                            <div class="col-md-12">
                                <label for="food_selection" class="form-label">Select Foods</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="food-table">
                                        <thead>
                                            <tr>
                                                <th>Food</th>
                                                <!-- <th>Quantity</th>
                                                <th>Measurement</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($meal) && $meal->items->count() > 0)
                                                
                                                @foreach ($meal->items as $item)
                                                    @php
                                                    $quantityInfo = '';

                                                    if (!empty($item->pivot->selected_qty_unit)) {
                                                        $decoded = json_decode($item->pivot->selected_qty_unit, true);

                                                        if (is_array($decoded)) {
                                                            $parts = [];

                                                            foreach ($decoded as $unitSet) {
                                                                $qty = $unitSet['qty'] ?? '';
                                                                $unit = $unitSet['unit'] ?? '';
                                                                if ($qty && $unit) {
                                                                    $parts[] = $qty . '' . $unit;
                                                                }
                                                            }

                                                            $quantityInfo = implode(' or ', $parts);
                                                        }
                                                    } else {
                                                        $quantityInfo = $item->pivot->item_qty . ' ' . $item->pivot->item_qty_unit;
                                                    }

                                                    @endphp
                                                    <tr class="food-row">
                                                        <td>
                                                            <select name="food_ids[]" class="form-control select2 food-select">
                                                                <option value="">Select Food</option>
                                                                @foreach ($foods as $food)
                                                                    <option value="{{ $food->id }}" {{ $food->id == $item->id ? 'selected' : '' }}>
                                                                        {{ $food->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="food-title-qty mt-2 mb-0"><strong>{{ $item->title }} {{ $quantityInfo }}</strong></p>
                                                            <p class="nutrition-info mt-2 mb-0 text-muted">Protein: {{ round($item->pivot->protein)}}g, Carb: {{round($item->pivot->carbs)}}g, Fat: {{round($item->pivot->fat)}}g</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-success edit-food" data-carbs="{{$item->pivot->carbs}}" data-protein="{{$item->pivot->protein}}" data-fat="{{$item->pivot->fat}}" data-serving-size="{{$item->serving_size}}" data-serving-size-unit="{{$item->serving_size_unit}}"><i class="icofont-edit text-success" ></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger remove-food"><i class="icofont-ui-delete text-danger"></i>
                                                            </button>
                                                        </td>
                                                        <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value='{{ isset($item->pivot->selected_qty_unit) ? ($item->pivot->selected_qty_unit) : json_encode([["qty" => $item->pivot->item_qty, "unit" => $item->pivot->item_qty_unit]]) }}'>
                                                        <input type="hidden" class="hidden-protein" name="protein[]" value="{{$item->pivot->protein}}">
                                                        <input type="hidden" class="hidden-carbs" name="carbs[]" value="{{$item->pivot->carbs}}">
                                                        <input type="hidden" class="hidden-fat" name="fat[]" value="{{$item->pivot->fat}}">
                                                        <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="{{$item->serving_size}}">
                                                        <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="{{$item->serving_size_unit}}">
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="food-row">
                                                    <td>
                                                        <select name="food_ids[]" class="form-control food-select" required>
                                                            <option value="">Select Food</option>
                                                            @foreach ($foods as $food)
                                                                <option value="{{ $food->id }}">{{ $food->title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="food-title-qty mt-2 mb-0"><strong></strong></p>
                                                        <p class="nutrition-info mt-2 mb-0 text-muted">Protein: 0g, Carb: 0g, Fat: 0g</p>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-success edit-food" data-carbs="" data-protein="" data-fat="" data-serving-size="" data-serving-size-unit=""><i class="icofont-edit text-success" ></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger remove-food"><i class="icofont-ui-delete text-danger"></i>
                                                        </button>
                                                    </td>
                                                    <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value="">
                                                    <input type="hidden" class="hidden-protein" name="protein[]" value="0">
                                                    <input type="hidden" class="hidden-carbs" name="carbs[]" value="0">
                                                    <input type="hidden" class="hidden-fat" name="fat[]" value="0">
                                                    <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="0">
                                                    <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="0">
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" id="add-food" class="btn btn-primary mt-2">Add More</button>
                            </div>

                            <!-- <div class="col-md-12">
                                <label for="food_ids" class="form-label">Select Foods</label>
                                <select name="food_ids[]" id="food_ids" class="form-control select2" multiple required>
                                    @foreach ($foods as $food)
                                        <option value="{{ $food->id }}" 
                                            {{ isset($meal) && $meal->items->contains($food->id) ? 'selected' : '' }}>
                                            {{ $food->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> -->
                            
                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($meal) && $meal->image)
                                <img src="{{ asset('storage/' . $meal->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">OR Generate Image with AI</label>
                                <button type="button" id="generate-ai-image" class="btn btn-primary">Generate Image</button>
                                
                                <div id="image-preview-container" style="display: none;">
                                    <img id="meal-image-preview" class="img-thumbnail mt-2" style="max-height: 150px;">
                                </div>
                                
                                <input type="hidden" name="generated_image" id="generated_image">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($meal) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFoodModalLabel">Edit Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="dynamicQtyMeasurementContainer"></div>
          
                <div class="nutrition-info mt-3">
                <p><strong>Protein:</strong> <span id="modalProtein">0g </span>, <strong>Carb:</strong> <span id="modalCarbs">0g </span>, <strong>Fat:</strong> <span id="modalFat">0g </span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary save-edit-food" id="save-edit-food">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Save Food Modal -->
<div class="modal" style="display:none;" id="saveMealModal" tabindex="-1" aria-labelledby="saveMealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveMealModalLabel">Save Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have unsaved changes. Do you want to save your changes before you leave?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="leaveWithoutSaving" data-bs-dismiss="modal">No, Continue</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Yes, Save</button>
            </div>
        </div>
    </div>
</div>

<div id="loader" style="display: none;">
    <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="Loading..." />
</div>
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

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
                        document.getElementById('saveMealModal').style.display = 'block'; // Show modal
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
            document.getElementById('mealForm').submit();
            document.getElementById('saveMealModal').style.display = 'none';
        });

        // Modal Button: "No Leave"
        document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
            hasUnsavedChanges = false;

            // Correctly redirect to the stored intended URL (like Food link)
            if (intendedHref) {
                window.location.href = intendedHref;
            }
        });

        // Form submit bypasses the unsaved warning
        document.getElementById('mealForm').addEventListener('submit', function () {
            hasUnsavedChanges = false;
        });
    });

    const loader = $('#loader');

    $(document).ready(function() {
        $('#categories').select2({
            placeholder: "Select categories",
            allowClear: true
        });

        // Preload selected foods for Edit Mode
        @if (isset($meal))
            const preselectedFoods = @json($meal->items->pluck('id'));
            $('#food_ids').val(preselectedFoods).trigger('change');
        @endif
    });
    $(document).ready(function () {
        function initializeSelect2() {
            $('.food-select').not('.select2-hidden-accessible').select2({
                placeholder: "Search and select foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.items.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { query: params.term };
                    },
                    processResults: function(response) {
                        return {
                            results: response.items.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.title,
                                    qty: item.qty || '',  // Fetch default quantity
                                    measurement: item.unit || '', // Fetch default measurement
                                    serving_size: item.serving_size,
                                    serving_size_unit: item.serving_size_unit,
                                    protein: item.protein,
                                    carbs: item.carbs,
                                    fat: item.fat,
                                    image: item.image,
                                    selected_qty_unit: item.selected_qty_unit || []  // Ensure it's an array
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function (e) {
                const selectedFood = e.params.data;
                const row = $(this).closest('tr');
                const carb = Math.round(selectedFood.carbs);

                const protein = Math.round(selectedFood.protein);

                const fat = Math.round(selectedFood.fat);

                row.find('.edit-food').data('carbs', selectedFood.carbs)
                row.find('.edit-food').data('protein', selectedFood.protein)
                row.find('.edit-food').data('fat', selectedFood.fat)
                row.find('.edit-food').data('serving-size', selectedFood.serving_size)
                row.find('.edit-food').data('serving-size-unit', selectedFood.serving_size_unit)

                row.find('.hidden-selected-qty-unit').val(selectedFood.selected_qty_unit);
                row.find('.hidden-protein').val(selectedFood.protein);
                row.find('.hidden-carbs').val(selectedFood.carbs);
                row.find('.hidden-fat').val(selectedFood.fat);
                row.find('.hidden-serving-size').val(selectedFood.serving_size);
                row.find('.hidden-serving-size-unit').val(selectedFood.serving_size_unit);
                row.find('.nutrition-info').text(`Protein: ${protein}g, Carb: ${carb}g, Fat: ${fat}g`);

                let selectedUnits = [];

                try {
                    if (typeof selectedFood.selected_qty_unit === 'string') {
                        selectedUnits = JSON.parse(selectedFood.selected_qty_unit.replace(/&quot;/g, '"'));
                    } else if (Array.isArray(selectedFood.selected_qty_unit)) {
                        selectedUnits = selectedFood.selected_qty_unit;
                    }
                } catch (err) {
                    console.error('Error parsing selected_qty_unit:', err);
                }
                let displayQty = '';
                if (selectedUnits.length > 0) {
                    displayQty = selectedUnits
                        .map(unit => `${unit.qty}${unit.unit}`)
                        .join(' or ');
                } else if (selectedFood.qty && selectedFood.measurement) {
                    displayQty = `${selectedFood.qty}${selectedFood.measurement}`;
                }

                const displayTitle = selectedFood.text || '';

                // Set hidden field value as escaped JSON
                row.find('.hidden-selected-qty-unit').val(JSON.stringify(selectedUnits));

                // Display formatted title + qty
                row.find('.food-title-qty').html(`<strong>${displayTitle} ${displayQty}</strong>`);
            });

        }
        
        // function updateQtyAndMeasurement(row, selectedQtyUnit, defaultQty, defaultMeasurement) {
        //     let qtyDropdown = row.find('.food-qty');
        //     let measurementDropdown = row.find('.food-qty-measurement');

        //     // Clear existing options
        //     qtyDropdown.empty();
        //     measurementDropdown.empty();

        //     // Parse JSON string into JS array if it's not already an array
        //     let parsedQtyUnit = [];
        //     if (typeof selectedQtyUnit === "string") {
        //         try {
        //             parsedQtyUnit = JSON.parse(selectedQtyUnit);
        //         } catch (e) {
        //             console.error("Invalid JSON format for selectedQtyUnit:", e);
        //         }
        //     } else {
        //         parsedQtyUnit = selectedQtyUnit;
        //     }

        //     if (parsedQtyUnit.length > 0) {
        //         parsedQtyUnit.forEach(function (option, index) {
        //             let qtyOption = new Option(option.qty, option.qty, index === 0, index === 0);
        //             let measurementOption = new Option(option.unit, option.unit, index === 0, index === 0);

        //             qtyDropdown.append(qtyOption);
        //             measurementDropdown.append(measurementOption);
        //         });

        //         // Initialize Select2 for qty with tagging (editable dropdown)
        //         // qtyDropdown.select2({
        //         //     // tags: true,
        //         //     placeholder: "Select or enter quantity",
        //         //     width: '100%'
        //         // });
        //     } else {
        //         // Fallback if selectedQtyUnit is null or empty
        //         qtyDropdown.append(new Option(defaultQty, defaultQty, true, true)).trigger('change');
        //         measurementDropdown.append(new Option(defaultMeasurement, defaultMeasurement, true, true));
        //     }
        // }

        // $('.food-qty').select2({
        //     // tags: true,
        //     placeholder: "Select or enter quantity",
        //     allowClear: true,
        //     width: '100%'
        // })
            
        initializeSelect2();

        $('#add-food').click(function () {
            const newFoodRow = `
                <tr class="food-row">
                    <td>
                        <select name="food_ids[]" class="form-control food-select" required>
                            <option value="">Select Food</option>
                            @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->title }}</option>
                            @endforeach
                        </select>
                        <p class="food-title-qty mt-2 mb-0"><strong></strong></p>
                        <p class="nutrition-info mt-2 mb-0 text-muted">Protein: 0g, Carb: 0g, Fat: 0g</p>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-success edit-food" data-carbs="" data-protein="" data-fat="" data-serving-size="" data-serving-size-unit=""><i class="icofont-edit text-success"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger remove-food"><i class="icofont-ui-delete text-danger"></i>
                        </button>
                    </td>
                    <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value="">
                    <input type="hidden" class="hidden-protein" name="protein[]" value="0">
                    <input type="hidden" class="hidden-carbs" name="carbs[]" value="0">
                    <input type="hidden" class="hidden-fat" name="fat[]" value="0">
                    <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="0">
                    <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="0">
                </tr>
            `;
            $('#food-table tbody').append(newFoodRow);

            // Initialize Select2 for the newly added row
            initializeSelect2();
        });

        $(document).on('click', '.remove-food', function () {
            $(this).closest('tr').remove();
        });

        // function updateNutrition(row) {
        //     const foodId = row.find('.food-select').val();
        //     const foodTitle = row.find('.food-select option:selected').text();
        //     const qty = row.find('.food-qty').val();
        //     const measurement = row.find('.food-qty-measurement').val();

        //     if (!foodId || !qty || !measurement) return;

        //     const data = {
        //         id: foodId,
        //         title: foodTitle,
        //         qty: qty,
        //         measurement: measurement,
        //         _token: '{{ csrf_token() }}'
        //     };

        //     $.ajax({
        //         url: "{{ route('meal.food.nutrition.calculate') }}",
        //         type: 'POST',
        //         data: data,
        //         success: function(response) {
        //             if (response) {
        //                 row.find('.hidden-protein').val(response.protein);
        //                 row.find('.hidden-carbs').val(response.carbs);
        //                 row.find('.hidden-fat').val(response.fat);
        //                 row.find('.nutrition-info').text(`P: ${response.protein}g, C: ${response.carbs}g, F: ${response.fat}g`);
        //             }
        //         },
        //         error: function(xhr) {
        //             console.error('Error:', xhr.responseText);
        //         }
        //     });
        // }
        // Call updateNutrition when qty or measurement is changed
        // $(document).on('input', '.food-qty', function () {
        //     const row = $(this).closest('tr');
        //     const qtyInput = $(this);
        //     const value = qtyInput.val();
        //     const regex = /^(\d+(\.\d*)?|\d+\/\d+)?$/;
        //     const errorMessage = qtyInput.closest('td').find('.error-message');
            
        //     if (!regex.test(value)) {
        //         errorMessage.show();
        //         qtyInput[0].setCustomValidity("Invalid quantity format");
        //     } else {
        //         errorMessage.hide();
        //         qtyInput[0].setCustomValidity("");
        //     }
        //     // updateNutrition(row);
        // });

        // ✅ Trigger update when measurement (unit) is changed
        // $(document).on('change', '.food-qty-measurement', function () {
        //     const row = $(this).closest('tr');
        //     // updateNutrition(row);
        // });

        let $editingRow = null;

        $(document).on('click', '.edit-food', function () {
            window.currentEditFoodButton = $(this);

            $editingRow = $(this).closest('tr');
            console.log($(this).data('carbs'));
            const selectedFoodName = $editingRow.find('.food-select option:selected').text();

            let qtyUnits = [];

            // Try reading hidden-selected-qty-unit
            let selectedQtyUnitRaw = $editingRow.find('.hidden-selected-qty-unit').val();

            try {
                if (selectedQtyUnitRaw && selectedQtyUnitRaw !== "null") {
                    qtyUnits = JSON.parse(selectedQtyUnitRaw);
                }
            } catch (e) {
                console.warn('Invalid JSON in selected_qty_unit:', e);
                qtyUnits = [];
            }

            // If empty, fallback to food-qty and food-qty-measurement values
            if (!Array.isArray(qtyUnits) || qtyUnits.length === 0) {
                const fallbackQty = $editingRow.find('.food-qty').val() || '';
                const fallbackUnit = $editingRow.find('.food-qty-measurement').val() || '';
                qtyUnits = [{ qty: fallbackQty, unit: fallbackUnit }];
            }

            // Set modal title
            $('#editFoodModalLabel').text(`Edit ${selectedFoodName}`);

            const $container = $('#dynamicQtyMeasurementContainer');
            $container.empty();

            // Create input rows
            qtyUnits.forEach(({ qty, unit }, index) => {
                const rowHtml = `
                    <div class="row mb-2 qty-unit-row">
                        <div class="col-6">
                            ${index === 0 ? '<label class="form-label">Quantity</label>' : ''}
                            <input type="text" class="form-control modalQtyInput" value="${qty}">
                        </div>
                        <div class="col-6">
                            ${index === 0 ? '<label class="form-label">Measurement</label>' : ''}
                            <input type="text" class="form-control modalMeasurementInput" value="${unit}">
                        </div>
                    </div>
                `;
                $container.append(rowHtml);
            });

            // const carbs = parseFloat($(this).data('carbs'));
            // const protein = parseFloat($(this).data('protein'));
            // const fat = parseFloat($(this).data('fat'));

            const carbs = Math.round(parseFloat($(this).data('carbs')) * 10) / 10;   
            const protein = Math.round(parseFloat($(this).data('protein')) * 10) / 10;
            const fat = Math.round(parseFloat($(this).data('fat')) * 10) / 10;

            const baseQty = parseFloat($('.modalQtyInput').first().val());
            const baseUnit = $('.modalMeasurementInput').first().val();

            $('#modalCarbs').text(carbs + 'g');
            $('#modalProtein').text(protein + 'g');
            $('#modalFat').text(fat + 'g');

            $('#editFoodModal').modal('show');
            setupDynamicMeasurementSync();
            setupNutritionSync(carbs, protein, fat);
        });

        // function setupNutritionSync(baseCarbs, baseProtein, baseFat) {
        //     const $rows = $('#dynamicQtyMeasurementContainer .qty-unit-row');

        //     if ($rows.length === 0) return;

        //     // Identify base row (e.g., grams)
        //     let $baseRow = null;
        //     $rows.each(function () {
        //         const unit = $(this).find('.modalMeasurementInput').val().trim().toLowerCase();
        //         if (unit === 'g') {
        //             $baseRow = $(this);
        //         }
        //     });

        //     if (!$baseRow) {
        //         console.warn('No base row with grams found.');
        //         return;
        //     }

        //     // Get original base values
        //     let originalBaseQty = parseFloat($baseRow.find('.modalQtyInput').val());

        //     if (!originalBaseQty) return;

        //     function updateNutritionFromGrams(grams) {
        //         const multiplier = grams / originalBaseQty;

        //         $('#modalCarbs').text((baseCarbs * multiplier).toFixed(2) + 'g');
        //         $('#modalProtein').text((baseProtein * multiplier).toFixed(2) + 'g');
        //         $('#modalFat').text((baseFat * multiplier).toFixed(2) + 'g');
        //     }

        //     $rows.find('.modalQtyInput').on('input', function () {
        //         const $changedRow = $(this).closest('.qty-unit-row');
        //         const changedQty = parseFloat($(this).val());
        //         const changedUnit = $changedRow.find('.modalMeasurementInput').val().trim().toLowerCase();

        //         if (!changedQty || !changedUnit) return;

        //         // If user changes the grams input
        //         if (changedUnit === 'g') {
        //             updateNutritionFromGrams(changedQty);
        //             return;
        //         }

        //         // Find the matching row with grams to get its qty
        //         const $gramRow = $rows.filter(function () {
        //             return $(this).find('.modalMeasurementInput').val().trim().toLowerCase() === 'g';
        //         }).first();

        //         if ($gramRow.length) {
        //             const gramQty = parseFloat($gramRow.find('.modalQtyInput').val());

        //             // Now find the "piece" qty for the same item
        //             const $pieceRow = $rows.filter(function () {
        //                 return $(this).find('.modalMeasurementInput').val().trim().toLowerCase() === changedUnit;
        //             }).first();

        //             if ($pieceRow.length) {
        //                 const pieceQty = parseFloat($pieceRow.find('.modalQtyInput').val());

        //                 if (pieceQty && gramQty) {
        //                     const perUnitGrams = gramQty / pieceQty; // 1 piece = 150g
        //                     const newGrams = changedQty * perUnitGrams;

        //                     // Update gram input value in the base row
        //                     $gramRow.find('.modalQtyInput').val(newGrams.toFixed(2));

        //                     // Call the nutrition update with new grams
        //                     updateNutritionFromGrams(newGrams);
        //                 }
        //             }
        //         }
        //     });
        // }

        function setupNutritionSync(baseCarbs, baseProtein, baseFat) {
            const AU_UNIT_EQUIVALENTS = {
                'cup': 250,           // mL
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

            const $rows = $('#dynamicQtyMeasurementContainer .qty-unit-row');
            if ($rows.length === 0) return;

            // Step 1: Identify the base row (first one shown)
            const $baseRow = $rows.first();
            const baseQty = parseFloat($baseRow.find('.modalQtyInput').val());
            const baseUnit = $baseRow.find('.modalMeasurementInput').val().trim().toLowerCase();

            if (!baseQty || !baseUnit) {
                console.warn('Base quantity or unit is missing.');
                return;
            }

            function updateNutrition(currentQty, currentUnit) {
                if (!currentQty || !currentUnit) return;

                currentUnit = currentUnit.toLowerCase();
                let baseEquivalent = AU_UNIT_EQUIVALENTS[baseUnit];
                let currentEquivalent = AU_UNIT_EQUIVALENTS[currentUnit];

                if (!baseEquivalent || !currentEquivalent) {
                    console.warn('Unknown unit used in conversion.');
                    return;
                }

                // Convert both quantities to grams or equivalent unit
                const baseGrams = baseQty * baseEquivalent;
                const currentGrams = currentQty * currentEquivalent;

                const multiplier = currentGrams / baseGrams;

                $('#modalCarbs').text((baseCarbs * multiplier).toFixed(1) + 'g');
                $('#modalProtein').text((baseProtein * multiplier).toFixed(1) + 'g');
                $('#modalFat').text((baseFat * multiplier).toFixed(1) + 'g');
            }

            // Listen to input on any qty input field
            $rows.find('.modalQtyInput').on('input', function () {
                const $row = $(this).closest('.qty-unit-row');
                const newQty = parseFloat($(this).val());
                const newUnit = $row.find('.modalMeasurementInput').val().trim().toLowerCase();

                updateNutrition(newQty, newUnit);
            });
        }

        function setupDynamicMeasurementSync() {
            const $rows = $('#dynamicQtyMeasurementContainer .qty-unit-row');
            if ($rows.length < 2) return;

            let unitMap = {}; // e.g., { g: 150, piece: 1 }

            // 1. Build unit map
            $rows.each(function () {
                const qty = parseFloat($(this).find('.modalQtyInput').val());
                const unit = $(this).find('.modalMeasurementInput').val().toLowerCase().trim();
                if (!isNaN(qty) && unit) {
                    unitMap[unit] = qty;
                }
            });

            // Use the first row as the base
            const baseUnit = Object.keys(unitMap)[0];
            const baseQty = unitMap[baseUnit];

            if (!baseQty || !baseUnit) return;

            // Convert all units to ratio relative to base
            let ratios = {};
            for (const [unit, qty] of Object.entries(unitMap)) {
                ratios[unit] = qty / baseQty; // e.g., 1 piece = 1/150 g = 0.0067
            }

            // 2. Setup listener on each qty input
            $rows.each(function () {
                const $qtyInput = $(this).find('.modalQtyInput');
                const $unitInput = $(this).find('.modalMeasurementInput');

                $qtyInput.on('input', function () {
                    const changedQty = parseFloat($(this).val());
                    const changedUnit = $unitInput.val().toLowerCase().trim();

                    if (isNaN(changedQty) || !ratios[changedUnit]) return;

                    // Convert input qty to base qty
                    const updatedBaseQty = changedQty / ratios[changedUnit];

                    // Update all others
                    $rows.each(function () {
                        const $otherQtyInput = $(this).find('.modalQtyInput');
                        const $otherUnitInput = $(this).find('.modalMeasurementInput');

                        const unit = $otherUnitInput.val().toLowerCase().trim();
                        if (unit !== changedUnit && ratios[unit]) {
                            const newQty = updatedBaseQty * ratios[unit];
                            $otherQtyInput.val(newQty.toFixed(2));
                        }
                    });
                });
            });
        }

        $(document).on('click', '#save-edit-food', function () {
            const $modal = $('#editFoodModal');
            const updatedProtein = $('#modalProtein').text().replace('g', '').trim();
            const updatedCarbs = $('#modalCarbs').text().replace('g', '').trim();
            const updatedFat = $('#modalFat').text().replace('g', '').trim();

            // Get all qty/unit pairs from modal
            const updatedQtyUnits = [];
            $('#dynamicQtyMeasurementContainer .qty-unit-row').each(function () {
                const qty = $(this).find('.modalQtyInput').val();
                const unit = $(this).find('.modalMeasurementInput').val();
                if (qty && unit) {
                    updatedQtyUnits.push({ qty: parseFloat(qty), unit: unit.trim() });
                }
            });

            const $triggerButton = window.currentEditFoodButton;
            if (!$triggerButton) return;

            const $tr = $triggerButton.closest('tr');

            // ✅ Update visible nutrition info
            const nutritionText = `Protein: ${Math.round(updatedProtein)}g, Carb: ${Math.round(updatedCarbs)}g, Fat: ${Math.round(updatedFat)}g`;
            $tr.find('.nutrition-info').text(nutritionText);

            // ✅ Update hidden fields
            $tr.find('.hidden-protein').val(updatedProtein);
            $tr.find('.hidden-carbs').val(updatedCarbs);
            $tr.find('.hidden-fat').val(updatedFat);

            // Set first qty/unit pair as serving size info
            if (updatedQtyUnits.length > 0) {
                $tr.find('.hidden-serving-size').val(updatedQtyUnits[0].qty);
                $tr.find('.hidden-serving-size-unit').val(updatedQtyUnits[0].unit);
            }

            // ✅ Save selected_qty_unit back to hidden input
            $tr.find('.hidden-selected-qty-unit').val(JSON.stringify(updatedQtyUnits));

            // ✅ Update the display title with qty/units like: "150g or 1 piece"
            const foodTitle = $tr.find('.food-select option:selected').text();
            const displayQty = updatedQtyUnits.map(item => `${item.qty}${item.unit}`).join(' or ');
            $tr.find('.food-title-qty').html(`<strong>${foodTitle} ${displayQty}</strong>`);

            $modal.modal('hide');
        });

        // $('.modalQtyInput').on('change', function () {
        //     setupDynamicMeasurementSync();

        // });
    });
          

    $(document).ready(function () {
        $('#generate-ai-image').on('click', function () {
            let title = $('input[name="title"]').val();

            if (!title) {
                alert('Please enter a meal title first!');
                return;
            }
            $('#loader').show();
            $.ajax({
                url: "{{ route('admin.meals.generate-image') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title
                },
                success: function (response) {
                    if (response.image_url) {
                        $('#meal-image-preview').attr('src', response.image_url).show();
                        $('#generated_image').val(response.image_url); // Store for form submission
                        $('#image-preview-container').show();
                        $('#loader').hide();
                    }else{
                        alert('Failed to generate image. Please try again.');
                        $('#loader').hide();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Something went wrong!');
                    $('#loader').hide();
                }
            });
        });
        // $('#title').on('blur', function () {
        //     let title = $(this).val();
        //     if (title.trim() !== "") {
        //         $.ajax({
        //             url: "{{ route('admin.meals.generate-image') }}",
        //             method: "POST",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 title: title
        //             },
        //             success: function (response) {
        //                 if (response.image_url) {
        //                     $('#meal-image-preview').attr('src', response.image_url).show();
        //                     $('#generated_image').val(response.image_url); // Store for form submission
        //                     $('#image-preview-container').show();
        //                 }
        //             }
        //         });
        //     }
        // });
    });

</script>
