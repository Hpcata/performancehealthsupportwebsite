@extends('backend.layouts.app')

@section('content')
<style>
.select2-selection__choice {
    display: flex !important;
    align-items: center !important;
    height: 35px !important; /* Adjust height as needed */
    padding: 5px 10px !important;
    font-size: 14px !important;
}

.select2-selection__choice img {
    width: 25px !important;
    height: 25px !important;
    object-fit: cover !important;
    /* border-radius: 50% !important; */
    margin-right: 8px !important;
}
.locked::after {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0.3); /* dark overlay */
    z-index: 10;
}

.locked {
    position: relative;
    pointer-events: none;
}

.locked #lockIcon {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 150px;  /* Adjust as needed */
    height: auto;
    /* opacity: 0.7; */
    display: block;
}
</style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($item) ? 'Edit Food' : 'Create Food' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a href="{{ route('woolworths-product-search') }}" class="btn btn-primary btn-set-task w-sm-100">Search Woolworths Shop</a>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-primary btn-set-task w-sm-100 mx-3">Back</a>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form id="foodForm" action="{{ isset($item) ? route('admin.items.update', $item) : route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($item)) 
                            @method('PUT') 
                        @endif
                        <input type="hidden" name="id" class="form-control" id="id" value="{{ $item->id ?? '' }}" >

                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="title" value="{{ $item->title ?? '' }}" required>
                                <p class="mt-3 px-2" id="subTitle"></p>
                            </div>

                            <!-- Short Description Field -->
                        {{--<div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2">{{ $item->short_description ?? '' }}</textarea>
                            </div>
                        --}}
                            <!-- Full Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
                            </div>

                            <!-- category Field -->
                            <div class="col-md-12">
                                <label for="category" class="form-label">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ isset($item) && $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 d-flex align-items-center gap-2">
                                <input type="checkbox" 
                                    id="lockCheckbox" 
                                    name="is_locked" 
                                    value="{{ $item->is_locked ?? 0 }}" 
                                    class="form-check-input" 
                                    {{ isset($item) ? ($item->is_locked == 1 ? 'checked' : '') : '' }} />

                                <label for="lockCheckbox" id="lockLabel" class="form-label mb-0">
                                    {{ isset($item) ? ($item->is_locked == 1 ? 'Unlock' : 'Lock') : 'Lock' }}
                                </label>
                                <small class="form-text text-muted">(Lock to prevent editing nutrition info. Unlock to allow changes.)</small>
                            </div>
                            <!-- Quantity Field -->
                            @php 
                            $selectedUnits = [];
                            $mainQty = "";
                            $mainUnit = "";
                            if(isset($item)){
                                $selectedUnits = is_string($item->selected_qty_unit) ? json_decode($item->selected_qty_unit, true): $item->selected_qty_unit;                                
                                    $mainQty = $selectedUnits[0]['qty'] ?? ($item->qty ?? '');
                                    $mainUnit = $selectedUnits[0]['unit'] ?? ($item->unit ?? '');
                            }
                            @endphp
                            <!-- Quantity Field -->
                            <div class="lock-div position-relative" id="lockableBox"> 
                                <img id="lockIcon" src="{{ asset('uploads/lock.png') }}" style="display: none;"/>
                                <div class="row">
                                    <!-- 🔹 Main Quantity Input -->
                                    <div class="col-md-4">
                                        <label for="qty" class="form-label">Quantity</label> <span class="qty-error"></span>
                                        <div class="d-flex align-items-center mb-1">
                                            <input type="checkbox" class="qty-checkbox ms-2 me-2" 
                                                {{ isset($selectedUnits[0]) ? 'checked' : '' }}
                                                data-qty="{{ $mainQty }}" data-unit="{{ $mainUnit }}">
                                            <input type="number" name="qty" id="qty" class="form-control qty-input" 
                                                value="{{ $mainQty }}" placeholder="Enter quantity" step="0.01">
                                        </div>
                                        @if($selectedUnits)
                                            @foreach ($selectedUnits as $index => $unitData)
                                                @if ($index > 0)
                                                    <div class="d-flex align-items-center mb-1 alt-qty-wrapper">
                                                        <input type="checkbox" class="alt-qty-checkbox ms-2 me-2 alternate-measurement-checkbox" id="{{$unitData['unit']}}"
                                                            checked data-qty="{{ $unitData['qty'] }}" data-unit="{{ $unitData['unit'] }}">
                                                        <input type="number"  class="form-control alt-qty-input alternate-qty-input" 
                                                            value="{{ $unitData['qty'] }}" >
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- 🔹 Main Measurement Dropdown -->
                                    <div class="col-md-4">
                                        <label for="measurement" class="form-label">Measurement</label>
                                        <select name="unit" class="form-control unit-dropdown" id="measurement">
                                            <option value="">Select Measurement</option>
                                            @foreach (['g', 'mL', 'cup', 'teaspoon', 'tablespoon', 'dessert spoon', 'handful', 'piece', 'pouch', 'tub','slice', 'roll'] as $unit)
                                                <option value="{{ $unit }}" {{ $mainUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                            @endforeach
                                        </select>
                                        @if($selectedUnits)
                                            @foreach ($selectedUnits as $index => $unitData)
                                                @if ($index > 0)
                                                <select name="unit" class="form-control alt-unit-dropdown mt-1 alternate-measurement-dropdown alt-measurement-wrapper" id="{{$unitData['unit'] }}">
                                                    <option value="">Select Measurement</option>
                                                    @foreach (['g', 'mL', 'cup', 'teaspoon', 'tablespoon', 'dessert spoon', 'handful', 'piece', 'pouch', 'tub','slice', 'roll'] as $unit)
                                                        <option value="{{ $unit }}" {{ $unitData['unit'] == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                            @endforeach 
                                        @endif
                                    </div>
                                </div>

                                <!-- Nutrition Information Section -->
                                <div class="col-md-12 border rounded p-3">
                                    <h5 class="mb-3">Nutrition Information :</h5>

                                    <div class="row">
                                        <!-- Protein Field -->
                                        <div class="col-md-6">
                                            <label for="protein" class="form-label">Protein</label>
                                            <input type="number" name="protein" class="form-control" id="protein" value="{{ number_format($item->protein ?? '0' ,1)}}" 
                                                step="0.01" min="0" placeholder="Enter Protein">
                                            <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                                        </div>

                                        <!-- Serving Size Field -->
                                        <div class="col-md-2 mt-3">
                                            <label for="serving_size" class="form-label">Serving Size</label>
                                            <input type="number" name="serving_size" class="form-control d-inline-block d-flex" id="serving_size" value="{{ number_format($item->serving_size ?? '0', 1) }}"  step="0.01" min="0" placeholder="Enter Serving Size">
                                            <!-- <p>Gm</p> -->
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <label for="serving_size" class="form-label">Serving Size Unit</label>
                                            <select name="serving_size_unit" class="form-control" id="serving_size_unit">
                                                <option value="">Select unit</option>
                                                <option value="g" {{ isset($item) && $item->serving_size_unit == 'g' ? 'selected' : ''}}>g</option>
                                                <option value="ml" {{ isset($item) && $item->serving_size_unit == 'ml' ? 'selected' : ''}}>mL</option>
                                            </select>
                                            <!-- <input type="text" name="serving_size_unit" class="form-control d-inline-block d-flex" id="serving_size_unit" value="{{ $item->serving_size_unit ?? 'gm' }}" placeholder="Enter Serving Size"> -->
                                        </div>
                                        <!-- Carbohydrate Field -->
                                        <div class="col-md-6">
                                            <label for="carbs" class="form-label">Carbohydrate</label>
                                            <input type="number" name="carbs" class="form-control" id="carbs" value="{{ number_format($item->carbs ?? '0', 1) }}" 
                                                step="0.01" min="0" placeholder="Enter Carbohydrate">
                                            <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                                        </div>

                                        <!-- Serving Per Pack Field -->
                                        <div class="col-md-6 mt-3">
                                            <label for="serving_per_pack" class="form-label">Serving Per Pack</label>
                                            <input type="text" name="serving_per_pack" class="form-control" id="serving_per_pack" value="{{ $item->serving_per_pack ?? '' }}"  placeholder="Enter Serving Per Pack">
                                            <small class="text-muted">Please enter the total number of servings per pack.</small>
                                        </div>

                                        <!-- Fat Field -->
                                        <div class="col-md-6 mt-3">
                                            <label for="fat" class="form-label">Fat</label>
                                            <input type="number" name="fat" class="form-control" id="fat" value="{{ number_format($item->fat ?? '0', 1) }}" step="0.01" min="0" placeholder="Enter Fat">
                                            <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Is Swapped Field -->
                            <div class="col-md-12">
                                <label for="is_swiped" class="form-label">Is Swapped? &nbsp;</label>
                                <small class="form-text text-muted">(Is this item used in the swapped list?)</small>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_swiped" id="is_swiped_yes" value="1"
                                        {{ (isset($item) && $item->is_swiped == 1) || !isset($item) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_swiped_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_swiped" id="is_swiped_no" value="0"
                                        {{ (isset($item) && $item->is_swiped == 0) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_swiped_no">No</label>
                                </div>
                            </div>

                            <?php //dd($item->itemSwaps); ?>
                            <!-- Swap Items Selection (Visible only if 'Is Swapped' is Yes) -->
                            <div class="col-md-12" id="swapItemsContainer" style="display: none;">
                                <label for="swap_item_ids" class="form-label">Swap Items</label>
                                <select name="swap_item_ids[]" class="form-control" id="swap_item_ids" multiple>
                                   
                                </select>
                            </div>

                            <div class="col-md-12" id="swapFoods">

                            </div>
                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($item) && $item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                                @endif
                            </div>

                            <input type="hidden"
                            id="selected_measurements_hidden"
                            name="selected_qty_unit"
                            value="{{ json_encode($selectedUnits) }}">

                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($item) ? 'Update' : 'Create' }}</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Save Food Modal -->
<div class="modal" style="display:none;" id="saveFoodModal" tabindex="-1" aria-labelledby="saveFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveFoodModalLabel">Save Food</h5>
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

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

@push('custom_scripts')
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
                        document.getElementById('saveFoodModal').style.display = 'block'; // Show modal
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
            document.getElementById('foodForm').submit();
            document.getElementById('saveFoodModal').style.display = 'none';
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
        document.getElementById('foodForm').addEventListener('submit', function () {
            hasUnsavedChanges = false;
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
   
        if ($('input[name="is_locked"]:checked').val() == '1') {
            $('#lockIcon').show();
            $('#lockableBox').addClass('locked');
        } else {
            $('#lockIcon').hide();
            $('#lockableBox').removeClass('locked');
        }

        $('#lockCheckbox').on('change', function () {
            if ($(this).is(':checked')) {
                $('#lockableBox').addClass('locked');
                $('#lockLabel').text('Unlock');
                $('#lockIcon').show(); // Show lock icon
                $(this).val(1);
            } else {
                $('#lockableBox').removeClass('locked');
                $('#lockLabel').text('Lock');
                $('#lockIcon').hide(); // hide lock icon
                $(this).val(0);
            }
        });


        $('.select2').select2({
            placeholder: "Select options",
            allowClear: true,
            width: '100%'
        });

        $('#swap_item_ids').select2({
            placeholder: "Search and select swap items",
            minimumInputLength: 1,
            width: '100%',
            escapeMarkup: markup => markup,
            templateResult: formatFood,
            templateSelection: formatFoodSelection,
            ajax: {
                url: '{{ route("admin.items.index") }}',
                dataType: 'json',
                delay: 250,
                data: params => ({ query: params.term }),
                processResults: response => ({
                    results: response.items.map(item => ({
                        id: item.id,
                        text: item.title,
                        image: item.image
                            ? `{{ asset('storage') }}/${item.image}`
                            : '{{ asset("default.png") }}'
                    }))
                }),
                cache: true
            }
        });

        function formatFood(food) {
            if (!food.id) return food.text;

            const image = food.image || '{{ asset("default.png") }}';

            return `
                <div style="display: flex; align-items: center;">
                    <img src="${image}" style="width: 30px; height: 30px; margin-right: 10px; object-fit: cover;" />
                    <span>${food.text}</span>
                </div>
            `;
        }

        function formatFoodSelection(food) {
            if (!food.id) return food.text;

            const image = food.image || '{{ asset("default.png") }}';

            return `
                <div style="display: flex; align-items: center;">
                    <img src="${image}" style="width: 25px; height: 25px; margin-right: 5px; object-fit: cover;" />
                    <span>${food.text}</span>
                </div>
            `;
        }

        // **🔥 Preselect Swap Items in Edit Mode**
        @if(isset($item))
            const preselected = @json($item->swapItems);

            preselected.forEach(item => {
                const image = item.image
                    ? `{{ asset('storage') }}/${item.image}`
                    : '{{ asset("default.png") }}';

                const option = new Option(item.title, item.id, true, true);
                $('#swap_item_ids').append(option).trigger('change');

                // Add image data to Select2's internal data store
                const selectedData = $('#swap_item_ids').select2('data');
                selectedData.forEach(obj => {
                    if (obj.id == item.id) {
                        obj.image = image;
                    }
                });
            });
        @endif

        // const resultDiv = $('#nutritionResult');
    
        // function fetchAlternateMeasurements(selectedMeasurement, qty) {
        //     const data = {
        //         id: $('input[name="id"]').val(),
        //         title: $('input[name="title"]').val(),
        //         carbs: $('input[name="carbs"]').val(),
        //         protein: $('input[name="protein"]').val(),
        //         fat: $('input[name="fat"]').val(),
        //         qty: qty,
        //         measurement: selectedMeasurement,
        //         serving_size: $('input[name="serving_size"]').val(),
        //         serving_per_pack: $('input[name="serving_per_pack"]').val(),
        //     };

        //     $.ajax({
        //         url: "{{ route('calculate.nutrition') }}",
        //         type: 'POST',
        //         data: data,
        //         headers: {
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         success: function (data) {
        //             if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {
        //                 let alternateSizesHtml = `<p><strong>Alternate Serving Sizes:</strong></p><form id="altServingForm">`;

        //                 Object.entries(data.alternate_serving_sizes).forEach(([key, size]) => {
        //                     alternateSizesHtml += `
        //                         <div class="alt-serving-item">
        //                             <input type="radio" name="selected_size" value="${size}" class="alt-serving-radio">
        //                             <input type="text" class="alt-serving-input" value="${size}" data-key="${key}">
        //                         </div>`;
        //                 });

        //                 alternateSizesHtml += `</form>`;
        //                 resultDiv.html(alternateSizesHtml).show();
        //             } else {
        //                 resultDiv.html(`<p>No alternate serving sizes available.</p>`).show();
        //             }
        //         },
        //         error: function () {
        //             resultDiv.html(`<p class="error">Error: Unable to fetch alternate sizes.</p>`).addClass('error').show();
        //         }
        //     });
        // }

        // // **🔥 On Page Load: Fetch Alternatives for Saved Measurement**
        // const savedMeasurement = $('select[name="unit"]').val();
        // const savedQty = $('input[name="qty"]').val();
        // fetchAlternateMeasurements(savedMeasurement, savedQty);

        // // **🔥 When User Changes Measurement**
        // $('#measurement').on('change', function () {
        //     const selectedMeasurement = $(this).val();
        //     const qty = $('input[name="qty"]').val();
        //     fetchAlternateMeasurements(selectedMeasurement, qty);
        // });

        // // **🔥 When User Edits the Quantity**
        // $('input[name="qty"]').on('input', function () {
        //     const selectedMeasurement = $('select[name="unit"]').val();
        //     const qty = $(this).val();
        //     fetchAlternateMeasurements(selectedMeasurement, qty);
        // });

    });
    $(document).ready(function () {
        const savedMeasurement = $('select[name="unit"]').val();
        const savedQty = $('input[name="qty"]').val();
        @if(isset($item))
        const selectedQtyUnit = @json($item->selected_qty_unit);
        const title = @json($item->title);
        @endif
        let selectedUnits = [];

        try {
            selectedUnits = typeof selectedQtyUnit === 'string' ? JSON.parse(selectedQtyUnit) : selectedQtyUnit;
        } catch (e) {
            selectedUnits = [];
        }

        const isMainChecked = $('.qty-checkbox').is(':checked');

        // Call fetchAlternateMeasurements only if no preselected OR checkbox unchecked
        if ((!selectedUnits || selectedUnits.length === 0) || !isMainChecked) {
           
            // Trigger with current values if available
            let selectedMeasurement = $('#measurement').val();
            let qty = $('#qty').val();
            if (selectedMeasurement && qty) {
                // fetchAlternateMeasurements(selectedMeasurement, qty);
            }
        }

        function fetchAlternateMeasurements(selectedMeasurement, qty) {
            const data = {
                id: $('input[name="id"]').val(),
                title: $('input[name="title"]').val(),
                carbs: $('input[name="carbs"]').val(),
                protein: $('input[name="protein"]').val(),
                fat: $('input[name="fat"]').val(),
                qty: qty,
                measurement: selectedMeasurement,
                serving_size: $('input[name="serving_size"]').val(),
                serving_per_pack: $('input[name="serving_per_pack"]').val(),
            };

            $.ajax({
                url: "{{ route('calculate.nutrition') }}",
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#carbs').val(Math.round(data.carbs * 10) / 10);
                    $('#protein').val(Math.round(data.protein * 10) / 10);
                    $('#fat').val(Math.round(data.fat * 10) / 10);
                    
                    let qtyDiv = $('#qty').closest('.col-md-4'); // Quantity div
                    let measurementDiv = $('#measurement').closest('.col-md-4'); // Measurement div

                    // Remove existing alternate values
                    qtyDiv.find('.alt-qty-wrapper').remove();
                    measurementDiv.find('.alt-measurement-wrapper').remove();

                    if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {
                        let altQtyHtml = `<div class="alt-qty-wrapper mt-2">`;
                        let altMeasurementHtml = `<div class="alt-measurement-wrapper mt-2">`;

                        Object.entries(data.alternate_serving_sizes).forEach(([key, size]) => {
                            if (size !== "Not applicable") {
                                let [altQty, altUnit] = size.split(" "); // Example: "0.5 cup" → ["0.5", "cup"]

                                // ✅ Append alternative quantity inside qtyDiv
                                altQtyHtml += `
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="checkbox" class="alt-qty-checkbox ms-2 me-2" id="${key}" data-qty="${altQty}" data-unit="${altUnit}">
                                        <input type="text" class="form-control alt-qty-input" value="${altQty}" >
                                    </div>
                                `;

                                // ✅ Generate the dropdown options dynamically
                                let measurementOptions = `
                                    <option value="">Select Measurement</option>
                                    <option value="g">g</option>
                                    <option value="mL">mL</option>
                                    <option value="cup">cup</option>
                                    <option value="teaspoon">teaspoon</option>
                                    <option value="tablespoon">tablespoon</option>
                                    <option value="dessert spoon">dessert spoon</option>
                                    <option value="handful">handful</option>
                                    <option value="piece">piece</option>
                                    <option value="pouch">pouch</option>
                                    <option value="tub">tub</option>
                                    <option value="slice">slice</option>
                                    <option value="roll">roll</option>
                                `;

                                // ✅ Check if altUnit exists in the predefined options
                                let existingOptions = ["g", "mL", "cup", "teaspoon", "tablespoon", "dessert spoon", "handful", "piece", "pouch", "tub", 'slice', 'roll'];
                                if (!existingOptions.includes(altUnit)) {
                                    // If altUnit is not found, add it dynamically and set it as selected
                                    measurementOptions += `<option value="${altUnit}" selected>${altUnit}</option>`;
                                } else {
                                    // If altUnit is found, mark it as selected
                                    measurementOptions = measurementOptions.replace(`value="${altUnit}"`, `value="${altUnit}" selected`);
                                }

                                // ✅ Alternative Measurement Dropdown **+ Input Field**
                                altMeasurementHtml += `
                                    <div class="d-flex align-items-center mb-1">
                                        <select class="form-control alt-unit-dropdown" id="${key}">
                                            ${measurementOptions}
                                        </select>
                                    </div>
                                `;
                            }
                        });

                        altQtyHtml += `</div>`;
                        altMeasurementHtml += `</div>`;

                        qtyDiv.append(altQtyHtml);
                        measurementDiv.append(altMeasurementHtml);

                        $('#qty').val(parseFloat(qty).toFixed(1));
                        $('#measurement').val(selectedMeasurement);
                        $('.qty-checkbox').data('qty', qty);
                        $('.qty-checkbox').data('unit', selectedMeasurement);
                    }
                },
                error: function () {
                    console.error("Error fetching alternate sizes.");
                }
            });
        }

        // function fetchAlternateMeasurements(selectedMeasurement, qty) {
        //     const data = {
        //         id: $('input[name="id"]').val(),
        //         title: $('input[name="title"]').val(),
        //         carbs: $('input[name="carbs"]').val(),
        //         protein: $('input[name="protein"]').val(),
        //         fat: $('input[name="fat"]').val(),
        //         qty: qty,
        //         measurement: selectedMeasurement,
        //         serving_size: $('input[name="serving_size"]').val(),
        //         serving_per_pack: $('input[name="serving_per_pack"]').val(),
        //     };

        //     $.ajax({
        //         url: "{{ route('calculate.nutrition') }}",
        //         type: 'POST',
        //         data: data,
        //         headers: {
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         success: function (data) {
        //             $('#carbs').val(parseFloat(data.carbs).toFixed(1));
        //             $('#protein').val(parseFloat(data.protein).toFixed(1));
        //             $('#fat').val(parseFloat(data.fat).toFixed(1));

        //             let qtyDiv = $('#qty').closest('.col-md-4');
        //             let measurementDiv = $('#measurement').closest('.col-md-4');

        //             // Remove existing alternate fields
        //             qtyDiv.find('.alt-qty-wrapper').remove();
        //             measurementDiv.find('.alt-measurement-wrapper').remove();

        //             if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {
        //                 let altQtyHtml = `<div class="alt-qty-wrapper mt-2">`;
        //                 let altMeasurementHtml = `<div class="alt-measurement-wrapper mt-2">`;

        //                 Object.entries(data.alternate_serving_sizes).forEach(([key, size]) => {
        //                     if (size !== "Not applicable") {
        //                         let [altQty, altUnit] = size.split(" "); // e.g., "2.13 slices"
        //                         if(altUnit !== $('#measurement').val()) {

        //                             // Alt Qty block
        //                             altQtyHtml += `
        //                                 <div class="d-flex align-items-center mb-1">
        //                                     <input type="checkbox" class="alt-qty-checkbox ms-2 me-2" id="${key}" data-qty="${altQty}" data-unit="${altUnit}">
        //                                     <input type="text" class="form-control alt-qty-input" value="${altQty}">
        //                                 </div>
        //                             `;
    
        //                             // Build options list
        //                             const units = ["g", "mL", "cup", "teaspoon", "tablespoon", "dessert spoon", "handful", "piece", "pouch", "tub", "slice", "rolls"];
        //                             let measurementOptions = `<option value="">Select Measurement</option>`;
        //                             units.forEach(u => {
        //                                 measurementOptions += `<option value="${u}" ${u === altUnit ? 'selected' : ''}>${u}</option>`;
        //                             });
    
        //                             // Alt Measurement block
        //                             altMeasurementHtml += `
        //                                 <div class="d-flex align-items-center mb-1">
        //                                     <select class="form-control alt-unit-dropdown" id="${key}">
        //                                         ${measurementOptions}
        //                                     </select>
        //                                 </div>
        //                             `;
        //                         } else {
        //                             $('#qty').val(parseFloat(altQty).toFixed(2));
        //                             $('#measurement').val(altUnit);
        //                         }
        //                     }
        //                 });

        //                 altQtyHtml += `</div>`;
        //                 altMeasurementHtml += `</div>`;

        //                 qtyDiv.append(altQtyHtml);
        //                 measurementDiv.append(altMeasurementHtml);
        //             }

        //         },
        //         error: function () {
        //             console.error("Error fetching alternate sizes.");
        //         }
        //     });
        // }

        // Trigger when main measurement dropdown changes
        $('#measurement').on('change', function () {
            const selectedMeasurement = $(this).val();
            const qty = $('input[name="qty"]').val();

            $('.alternate-measurement-checkbox').hide();
            $('.alternate-qty-input').hide().val('');
            $('.alternate-measurement-dropdown').hide().val('');
            $('.alternate-measurement-checkbox input[type="checkbox"]').prop('checked', false);
            $('#selected_measurements_hidden').val('');
            $('.qty-checkbox').prop('checked', false);

            if (selectedMeasurement && qty) {
                fetchAlternateMeasurements(selectedMeasurement, qty);
            } else {
                $('#qty-error').text('Please add quantity').show();
            }
        });

        // ✅ When alternate qty input is changed
        $(document).on('input', '.alt-qty-input', function () {
            const $input = $(this);
            const newQty = parseFloat($input.val());

            // Get unit ID from corresponding checkbox
            const unitId = $input.closest('.d-flex').find('.alt-qty-checkbox').attr('id');
            const $unitSelect = $('#' + unitId);
            const selectedUnit = $unitSelect.val();

            $('.alt-unit-dropdown').val('');
            updateHiddenField();

            // if (selectedUnit && newQty > 0) {
            //     fetchAlternateMeasurements(selectedUnit, newQty);
            // }
        });

        // $(document).on('input', '.alt-qty-input', function () {
        //     const $input = $(this);
        //     const newQty = parseFloat($input.val());
        //     const unitId = $input.prev('.alt-qty-checkbox').attr('id'); // Get the ID (e.g., 'slice', 'roll')
        //     $(this).siblings('.qty-checkbox').data('qty', newQty);
        //     updateHiddenField();

        //     // Find the corresponding select dropdown using the same ID
        //     const $dropdown = $('#' + unitId);

        //     if ($dropdown.length > 0) {
        //         const selectedUnit = $dropdown.val();

        //         if (selectedUnit) {
        //             // Call your function to fetch updated values
        //             fetchAlternateMeasurements(selectedUnit, newQty);
        //         }
        //     }
        // });


        // ✅ When alternate unit dropdown is changed
        $(document).on('change', '.alt-unit-dropdown', function () {
            const $select = $(this);
            const selectedUnit = $select.val();
            // console.log(selectedUnit);
            // Get matching input for this dropdown
            const unitId = $select.attr('id');
            const $qtyInput = $(`.alt-qty-checkbox#${unitId}`).closest('.d-flex').find('.alt-qty-input');
            const qty = parseFloat($qtyInput.val());

            $(this).siblings('.alt-qty-checkbox').data('unit', selectedUnit);
            updateHiddenField();

            if (selectedUnit && qty > 0) {
                fetchAlternateMeasurements(selectedUnit, qty);
            }
        });


        // 🔹 Fetch alternative measurements when quantity or measurement changes
        // $('#measurement').on('change', function () {
        //     let selectedMeasurement = $('#measurement').val();
        //     let qty = $('#qty').val();
        //     if (selectedMeasurement && qty) {
        //         fetchAlternateMeasurements(selectedMeasurement, qty);
        //     }else {
        //         $('#qty-error').text('Please add quantity').show();
        //     }
        // });

        $(document).on('input', '.qty-input', function () {
            $('#measurement').val('');
            $('#selected_measurements_hidden').val('');
            $('.qty-checkbox').prop('checked', false);
            $('.alt-qty-checkbox').prop('checked', false);
        });

        // 🔹 Update the hidden field with selected values before form submission
        function updateHiddenField() {
            let selectedValues = [];
            let subtitleParts = [];

            // ✅ FIRST: Loop for main quantity + unit
            $('.qty-checkbox:checked').each(function () {
                let qty = parseFloat($(this).siblings('.qty-input').val()) || 0;

                let index = $(this).closest('.col-md-4').find('.qty-checkbox').index(this);
                let unitDropdown = $(this).closest('.row').find('.unit-dropdown').eq(index);
                let unit = unitDropdown.val() || $(this).data('unit');

                if (unit) {
                    selectedValues.push({
                        qty: qty,
                        unit: unit
                    });
                    subtitleParts.push(`${qty}${unit}`);
                }
            });

            // ✅ SECOND: Loop for alternate measurements
            $('.alt-qty-checkbox:checked').each(function () {
                let qty = parseFloat($(this).siblings('.alt-qty-input').val()) || 0;

                let index = $(this).closest('.alt-qty-wrapper').find('.alt-qty-checkbox').index(this);
                let unitDropdown = $(this).closest('.row').find('.alt-measurement-wrapper .alt-unit-dropdown').eq(index);
                let unit = unitDropdown.val() || $(this).data('unit');

                if (unit) {
                    selectedValues.push({
                        qty: qty,
                        unit: unit
                    });
                    subtitleParts.push(`${qty}${unit}`);
                }
                

            });

            // 🔄 Store as JSON string in hidden input
            $('#selected_measurements_hidden').val(JSON.stringify(selectedValues));
            const subtitle = subtitleParts.join(' or ');
            $('#subTitle').html(`<strong>${title} ${subtitle}</strong>`);
        }

        $(document).on('change', '.alt-qty-checkbox', function () {
            updateHiddenField();
        });
        
        $(document).on('change', '.qty-checkbox', function () {
            let isChecked = $(this).is(':checked');
            let container = $(this).closest('.col-md-4');

            // Quantity input and unit dropdown within the same section
            let qtyInput = container.find('.qty-input');
            let unitDropdown = $('.col-md-4').find('.unit-dropdown');

            // Toggle readonly/disabled based on checkbox state
            if (isChecked) {
                qtyInput.prop('disabled', false);
                unitDropdown.prop('disabled', false);
                
            }else {
                let selectedMeasurement = $(this).val();
                let qty = $('input[name="qty"]').val(); 

                qtyInput.prop('disabled', false);
                unitDropdown.prop('disabled', false);
                //fetchAlternateMeasurements(selectedMeasurement, qty);
            }
            updateHiddenField();
        });

        // 🔹 When an alternative input field is manually updated, update the checkbox value
        // $(document).on('input', '.alt-qty-input', function () {
        //     let newQty = $(this).val();
        //     $(this).siblings('.alt-qty-checkbox').data('qty', newQty);
        //     updateHiddenField();

        // });
        // $(document).on('input', '.qty-input', function () {
        //     let newQty = $(this).val();
        //     $(this).siblings('.qty-checkbox').data('qty', newQty);
        //     updateHiddenField();

        // });

        
        // 🔹 When an alternative dropdown is changed, update the checkbox data
        // $(document).on('change', '.alt-unit-dropdown', function () {
        //     let newUnit = $(this).val();
        //     $(this).siblings('.alt-qty-checkbox').data('unit', newUnit);
        //     updateHiddenField();

        // });
        // $(document).on('change', '.unit-dropdown', function () {
        //     let newUnit = $(this).val();
        //     $(this).siblings('.qty-checkbox').data('unit', newUnit);
        //     updateHiddenField();

        // });

        // **🔥 When User Edits the Measurement**
        // $('#measurement').on('change', function () {
        //     console.log('measurement chane');
        //     const selectedMeasurement = $(this).val();
        //     const qty = $('input[name="qty"]').val();

        //     $('.alternate-measurement-checkbox').hide();
        //     $('.alternate-qty-input').hide().val('');
        //     $('.alternate-measurement-dropdown').hide().val('');

        //     // Optionally uncheck checkboxes
        //     $('.alternate-measurement-checkbox input[type="checkbox"]').prop('checked', false);
        //     $('#selected_measurements_hidden').val('');
        //     $('.qty-checkbox').prop('checked', false);
        //     // $('.alt-qty-checkbox').prop('checked', false);
        //     // 🔄 Clear alternate fields
        //     if (selectedMeasurement && qty) {
        //         fetchAlternateMeasurements(selectedMeasurement, qty);
        //     }else {
        //         $('#qty-error').text('Please add quantity').show();
        //     }
        // });

        // **🔥 When User Edits the Quantity**
        // $('input[name="qty"]').on('change', function () {
        //     console.log('input chnage');
        //     const selectedMeasurement = $('select[name="unit"]').val();
        //     const qty = $(this).val();
        //     $('#measurement').val('');
        //     $('.alternate-measurement-checkbox').hide();
        //     $('.alternate-qty-input').hide().val('');
        //     $('.alternate-measurement-dropdown').hide().val('');

        //     // Optionally uncheck checkboxes
        //     $('.alternate-measurement-checkbox input[type="checkbox"]').prop('checked', false);
        //     $('#selected_measurements_hidden').val('');
        //     //fetchAlternateMeasurements(selectedMeasurement, qty);
        // });
    });
    // $(document).ready(function () {
    //     const savedMeasurement = $('select[name="unit"]').val();
    //     const savedQty = $('input[name="qty"]').val();

    //     fetchAlternateMeasurements(savedMeasurement, savedQty);

    //     function fetchAlternateMeasurements(selectedMeasurement, qty) {
    //         const data = {
    //             id: $('input[name="id"]').val(),
    //             title: $('input[name="title"]').val(),
    //             carbs: $('input[name="carbs"]').val(),
    //             protein: $('input[name="protein"]').val(),
    //             fat: $('input[name="fat"]').val(),
    //             qty: qty,
    //             measurement: selectedMeasurement,
    //             serving_size: $('input[name="serving_size"]').val(),
    //             serving_per_pack: $('input[name="serving_per_pack"]').val(),
    //         };

    //         $.ajax({
    //             url: "{{ route('calculate.nutrition') }}",
    //             type: 'POST',
    //             data: data,
    //             headers: {
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //             },
    //             success: function (data) {
    //                 $('#carbs').val(data.carbs);
    //                 $('#protein').val(data.protein);
    //                 $('#fat').val(data.fat);
                    
    //                 let qtyDiv = $('#qty').closest('.col-md-4'); // Quantity div
    //                 let measurementDiv = $('#measurement').closest('.col-md-4'); // Measurement div

    //                 // Remove existing alternate values
    //                 qtyDiv.find('.alt-qty-wrapper').remove();
    //                 measurementDiv.find('.alt-measurement-wrapper').remove();

    //                 if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {
    //                     let altQtyHtml = `<div class="alt-qty-wrapper mt-2">`;
    //                     let altMeasurementHtml = `<div class="alt-measurement-wrapper mt-2">`;

    //                     Object.entries(data.alternate_serving_sizes).forEach(([key, size]) => {
    //                         if (size !== "Not applicable") {
    //                             let [altQty, altUnit] = size.split(" "); // Example: "0.5 cup" → ["0.5", "cup"]

    //                             // ✅ Append alternative quantity inside qtyDiv
    //                             altQtyHtml += `
    //                                 <div class="d-flex align-items-center mb-1">
    //                                     <input type="checkbox" class="alt-qty-checkbox ms-2 me-2" data-qty="${altQty}" data-unit="${altUnit}">
    //                                     <input type="text" class="form-control alt-qty-input" value="${altQty}" >
    //                                 </div>
    //                             `;

    //                             // ✅ Generate the dropdown options dynamically
    //                             let measurementOptions = `
    //                                 <option value="">Select Measurement</option>
    //                                 <option value="g">g</option>
    //                                 <option value="mL">mL</option>
    //                                 <option value="cup">cup</option>
    //                                 <option value="teaspoon">teaspoon</option>
    //                                 <option value="tablespoon">tablespoon</option>
    //                                 <option value="dessert spoon">dessert spoon</option>
    //                                 <option value="handful">handful</option>
    //                                 <option value="piece">piece</option>
    //                                 <option value="pouch">pouch</option>
    //                                 <option value="tub">tub</option>
    //                             `;

    //                             // ✅ Check if altUnit exists in the predefined options
    //                             let existingOptions = ["g", "mL", "cup", "teaspoon", "tablespoon", "dessert spoon", "handful", "piece", "pouch", "tub"];
    //                             if (!existingOptions.includes(altUnit)) {
    //                                 // If altUnit is not found, add it dynamically and set it as selected
    //                                 measurementOptions += `<option value="${altUnit}" selected>${altUnit}</option>`;
    //                             } else {
    //                                 // If altUnit is found, mark it as selected
    //                                 measurementOptions = measurementOptions.replace(`value="${altUnit}"`, `value="${altUnit}" selected`);
    //                             }

    //                             // ✅ Alternative Measurement Dropdown **+ Input Field**
    //                             altMeasurementHtml += `
    //                                 <div class="d-flex align-items-center mb-1">
    //                                     <select class="form-control alt-unit-dropdown">
    //                                         ${measurementOptions}
    //                                     </select>
    //                                 </div>
    //                             `;
    //                         }
    //                     });

    //                     altQtyHtml += `</div>`;
    //                     altMeasurementHtml += `</div>`;

    //                     qtyDiv.append(altQtyHtml);
    //                     measurementDiv.append(altMeasurementHtml);
    //                 }
    //             },
    //             error: function () {
    //                 console.error("Error fetching alternate sizes.");
    //             }
    //         });

    //     }

    //     // 🔹 Fetch alternative measurements when quantity or measurement changes
    //     $('#qty, #measurement').on('change', function () {
    //         let selectedMeasurement = $('#measurement').val();
    //         let qty = $('#qty').val();
    //         if (selectedMeasurement && qty) {
    //             fetchAlternateMeasurements(selectedMeasurement, qty);
    //         }
    //     });

    //     // 🔹 Update the hidden field with selected values before form submission
    //     function updateHiddenField() {
    //         let selectedValues = [];

    //         $('.alt-qty-checkbox:checked').each(function () {
    //             let qty = parseFloat($(this).siblings('.alt-qty-input').val()) || 0;
                
    //             // Find the index of the selected checkbox
    //             let index = $(this).closest('.alt-qty-wrapper').find('.alt-qty-checkbox').index(this);
                
    //             // Find the corresponding unit dropdown in .alt-measurement-wrapper
    //             let unitDropdown = $(this).closest('.row').find('.alt-measurement-wrapper .alt-unit-dropdown').eq(index);
                
    //             let unit = unitDropdown.val() || $(this).data('unit');  // Use dropdown if exists, otherwise use data-unit
                
    //             // console.log("Qty:", qty, "Unit:", unit); // Debugging output

    //             if (unit !== undefined && unit !== "") {
    //                 selectedValues.push(`${qty} ${unit}`);
    //             }
    //         });
    //         $('.qty-checkbox:checked').each(function () {
    //             let qty = parseFloat($(this).siblings('.qty-input').val()) || 0;

    //             // Find the index of the selected checkbox
    //             let index = $(this).closest('.col-md-4').find('.qty-checkbox').index(this);
                
    //             // Find the corresponding unit dropdown in .alt-measurement-wrapper
    //             let unitDropdown = $(this).closest('.row').find('.unit-dropdown').eq(index);
                
    //             let unit = unitDropdown.val() || $(this).data('unit');  // Use dropdown if exists, otherwise use data-unit
                
    //             // console.log("Qty:", qty, "Unit:", unit); // Debugging output

    //             if (unit !== undefined && unit !== "") {
    //                 selectedValues.push(`${qty} ${unit}`);
    //             }

    //         });

    //         $('#selected_measurements_hidden').val(selectedValues.join(', '));
    //     }

    //     $(document).on('change', '.alt-qty-checkbox', function () {
    //         updateHiddenField();
    //     });
    //     $(document).on('change', '.qty-checkbox', function () {
    //         updateHiddenField();
    //     });

    //     // 🔹 When an alternative quantity checkbox is checked, update qty & measurement fields
    //     // $(document).on('change', '.alt-qty-checkbox', function () {
    //     //     if ($(this).is(':checked')) {
    //     //         let newQty = $(this).siblings('.alt-qty-input').val();
    //     //         let newUnit = $(this).siblings('.alt-unit-dropdown').val();

    //     //         $('#qty').val(newQty);
    //     //         $('#measurement').val(newUnit);

    //     //         // Uncheck all other checkboxes
    //     //         $('.alt-qty-checkbox').not(this).prop('checked', false);
    //     //     }
    //     // });

    //     // 🔹 When an alternative input field is manually updated, update the checkbox value
    //     $(document).on('input', '.alt-qty-input', function () {
    //         let newQty = $(this).val();
    //         $(this).siblings('.alt-qty-checkbox').data('qty', newQty);
    //         updateHiddenField();

    //     });
    //     $(document).on('input', '.qty-input', function () {
    //         let newQty = $(this).val();
    //         $(this).siblings('.qty-checkbox').data('qty', newQty);
    //         updateHiddenField();

    //     });

    //     // 🔹 When an alternative dropdown is changed, update the checkbox data
    //     $(document).on('change', '.alt-unit-dropdown', function () {
    //         let newUnit = $(this).val();
    //         $(this).siblings('.alt-qty-checkbox').data('unit', newUnit);
    //         updateHiddenField();

    //     });
    //     $(document).on('change', '.unit-dropdown', function () {
    //         let newUnit = $(this).val();
    //         $(this).siblings('.qty-checkbox').data('unit', newUnit);
    //         updateHiddenField();

    //     });

    //     // **🔥 Create Hidden Field and Store Selected Data Before Form Submission**
    //     $('<input>').attr({
    //         type: 'hidden',
    //         id: 'selected_measurements_hidden',
    //         name: 'selected_qty_unit'
    //     }).appendTo('form');

    //     // **🔥 When User Edits the Measurement**
    //     $('#measurement').on('change', function () {
    //         const selectedMeasurement = $(this).val();
    //         const qty = $('input[name="qty"]').val();
    //         fetchAlternateMeasurements(selectedMeasurement, qty);
    //     });

    //     // **🔥 When User Edits the Quantity**
    //     $('input[name="qty"]').on('change', function () {
    //         const selectedMeasurement = $('select[name="unit"]').val();
    //         const qty = $(this).val();
    //         fetchAlternateMeasurements(selectedMeasurement, qty);
    //     });
    // });

</script>
@endpush
@endsection
