@extends('backend.layouts.app')

@section('content')
<style>
#swap_item_ids + .select2 .select2-selection__choice {
    display: flex !important;
    align-items: center !important;
    height: 35px !important;
    padding: 5px 10px !important;
    font-size: 14px !important;
}

#swap_item_ids + .select2 .select2-selection__choice img {
    width: 25px !important;
    height: 25px !important;
    object-fit: cover !important;
    margin-right: 8px !important;
}
.locked::after {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0.3); /* dark overlay */
    z-index: 10;
}

#lockIcon {
    width: 80px;
    height: 80px;
    object-fit: contain;
    cursor: pointer;
}

#loader-2 {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255,255,255,0.7);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-md-center align-items-start px-0 justify-content-between border-bottom flex-md-row flex-column">
                <h3 class="fw-bold mb-0">{{ isset($item) ? 'Edit Food' : 'Create Food' }}</h3>
                    @if (isset($item))
                        <a href="javascript:void(0)" class="btn btn-primary btn-set-task woolworth-json mx-3" data-json='@json($item->woolworth_json)'> Woolworths Json</a>
                    @endif
                    <div class="col-auto d-flex">
                    <a href="{{ route('woolworths-product-search') }}" class="btn btn-primary btn-set-task">Search Woolworths Shop</a>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-primary btn-set-task mx-3">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div id="form-error-message" style="color: red; display: none;" class="mb-3"></div>

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
                                <label for="title" class="form-label">Title<small class="text-danger">*</small></label>
                                <input type="text" name="title" class="form-control" id="title" value="{{ $item->title ?? '' }}" >
                                <p class="mt-3 px-2" id="subTitle" style="font-size: 16px;"></p>
                                <p class="nutrition-info mt-2 mb-0 text-muted px-2" style="font-size: 16px;"></p>
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Full Description Field -->
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="description" class="form-label mb-0">Description</label>
                                    <button type="button" id="generateDescriptionBtn" class="btn btn-sm btn-outline-primary">
                                        Generate Description
                                    </button>
                                </div>
                                <textarea name="description" id="description" class="form-control mt-2" rows="4">{{ $item->description ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="note" class="form-label">Notes</label>
                                <textarea name="note" class="form-control" rows="2">{{ $item->note ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="tag_ids" class="form-label">Select Tags</label>
                                <select name="tag_ids[]" class="form-select" id="tag_ids" multiple>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                                {{ isset($item) && $item->tags->contains($tag->id) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="flag_ids" class="form-label">Select Preferences</label>
                                <select name="flag_ids[]" class="form-select" id="flag_ids" multiple>
                                    @foreach ($flags as $flag)
                                        <option value="{{ $flag->id }}"
                                                {{ isset($item) && $item->flags->contains($flag->id) ? 'selected' : '' }}>
                                            {{ $flag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('flag_ids')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 d-flex align-items-start align-items-md-center flex-md-row flex-column gap-2 justify-content-between">
                                <div class="d-flex align-items-md-center align-items-start gap-2">
                                    <input type="checkbox"
                                        id="lockCheckbox"
                                        name="is_locked"
                                        value="{{ $item->is_locked ?? 0 }}"
                                        class="form-check-input"
                                        {{ isset($item) ? ($item->is_locked == 1 ? 'checked' : '') : '' }}
                                    />

                                    <label for="lockCheckbox" id="lockLabel" class="form-label mb-0">
                                        {{ isset($item) ? ($item->is_locked == 1 ? 'Unlock' : 'Lock') : 'Lock' }}
                                    </label>
                                    <small class="form-text text-muted mt-0">
                                        (Lock to prevent editing nutrition info. Unlock to allow changes.)
                                    </small>
                                </div>
                                <!-- Right side: Lock icon + Reset button -->
                                <div class="d-flex align-items-center gap-2 ">
                                    <button type="button" class="btn btn-secondary btn-sm" data-qty="{{ $item->serving_size ?? ''}}" data-unit="{{ $item->serving_size_unit ?? '' }}" data-title="{{ $item->title ?? '' }}" id="resetQty">Reset Qty</button>
                                </div>
                            </div>

                            <!-- Quantity Field -->
                            @php
                                $selectedUnits = [];
                                $mainQty = "";
                                $mainUnit = "";
                                if(isset($item)) {
                                    $selectedUnits = is_string($item->selected_qty_unit) ? json_decode($item->selected_qty_unit, true): $item->selected_qty_unit;
                                    $mainQty = $selectedUnits[0]['qty'] ?? ($item->qty ?? '');
                                    $mainUnit = $selectedUnits[0]['unit'] ?? ($item->unit ?? '');
                                }
                            @endphp
                            <!-- Quantity Field -->
                            <div class="lock-div position-relative" id="lockableBox">
                                <div class="row">
                                    <div class="col-md-12 add-more-container">
                                        <label class="form-label">Quantity & Measurement<small class="text-danger">*</small></label>

                                        @php
                                            $allUnits = ['g', 'mL', 'ml', 'cup', 'teaspoon', 'tablespoon', 'dessert spoon', 'handful', 'piece', 'pouch', 'tub','slice', 'roll'];
                                        @endphp

                                        @if (!empty($selectedUnits) && count($selectedUnits) > 0)
                                            <!-- Loop through selected units -->
                                            @foreach ($selectedUnits as $index => $unitData)
                                                <div class="row align-items-center mb-2">
                                                    {{-- Quantity Column --}}
                                                    <div class="col-md-4">
                                                        <div class="d-flex align-items-center">
                                                            <input
                                                                type="checkbox"
                                                                class="{{ $index === 0 ? 'qty-checkbox' : 'alt-qty-checkbox' }} me-2 {{ $index > 0 ? 'alternate-measurement-checkbox' : '' }}"
                                                                id="{{ $unitData['unit'] ?? 'main' }}"
                                                                data-qty="{{ $unitData['qty'] }}"
                                                                data-unit="{{ $unitData['unit'] }}"
                                                                {{ !empty($unitData['checked']) && $unitData['checked'] ? 'checked' : '' }}
                                                            >
                                                            <input
                                                                type="text"
                                                                name="{{ $index === 0 ? 'qty' : '' }}"
                                                                id="{{ $index === 0 ? 'qty' : '' }}"
                                                                class="form-control {{ $index === 0 ? 'qty-input' : 'alt-qty-input alternate-qty-input' }}"
                                                                value="{{ $unitData['qty'] }}"
                                                                placeholder="Enter quantity"
                                                            >
                                                        </div>
                                                    </div>

                                                    {{-- Unit Column --}}
                                                    <div class="col-md-4">
                                                        @if ($index === 0)
                                                            <select name="unit" class="form-control unit-dropdown" id="measurement">
                                                                <option value="">Select Measurement</option>
                                                                @foreach ($allUnits as $unit)
                                                                    <option value="{{ $unit }}" {{ $unitData['unit'] == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <select name="unit" class="form-control alt-unit-dropdown mt-1 alternate-measurement-dropdown alt-measurement-wrapper" id="{{ $unitData['unit'] }}">
                                                                <option value="{{ $unitData['unit'] }}">{{ $unitData['unit'] }}</option>
                                                            </select>
                                                        @endif
                                                    </div>

                                                    {{-- Add More or Spacer --}}
                                                    <div class="col-md-4">
                                                        @if ($loop->last)
                                                            <button type="button" id="add-more" class="btn btn-primary">Add More</button>
                                                            <small class="text-danger">*This will not adjust other measurements</small>
                                                        @else
                                                            <div></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Default view if no selected units -->
                                            <div class="row align-items-center mb-2 gap-3">
                                                {{-- Quantity Column --}}
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <input
                                                            type="checkbox"
                                                            class="qty-checkbox me-2"
                                                            id="main"
                                                        >
                                                        <input
                                                            type="text"
                                                            name="qty"
                                                            id="qty"
                                                            class="form-control qty-input"
                                                            value="{{ $item->qty ?? '' }}"
                                                            placeholder="Enter quantity"
                                                        >
                                                    </div>
                                                </div>

                                                {{-- Unit Column --}}
                                                <div class="col-md-4">
                                                    <select name="unit" class="form-control unit-dropdown" id="measurement">
                                                        <option value="">Select Measurement</option>
                                                        @foreach ($allUnits as $unit)
                                                            <option value="{{ $unit }}">{{ $unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        @error('qty')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('unit')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
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
                                            @error('serving_size')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <label for="serving_size" class="form-label">Serving Size Unit</label>
                                            <select name="serving_size_unit" class="form-control" id="serving_size_unit">
                                                <option value="">Select unit</option>
                                                <option value="g" {{ isset($item) && $item->serving_size_unit == 'g' ? 'selected' : ''}}>g</option>
                                                <option value="ml" {{ isset($item) && $item->serving_size_unit == 'ml' ? 'selected' : ''}}>mL</option>
                                            </select>
                                            @error('serving_size_unit')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Carbohydrate Field -->
                                        <div class="col-md-6 mt-3">
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
                                        <!-- Fat Field -->
                                        <div class="col-md-6 mt-3">
                                            <label for="energy" class="form-label">Energy</label>
                                            <input type="text" name="energy" class="form-control" id="energy" value="{{ old('energy', $item->energy ?? '') }}" placeholder="Enter Energy">
                                            <small class="text-muted">Please enter the value in kJ (e.g., 786.5kJ)</small>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="saturated" class="form-label">Saturated Fat</label>
                                            <input type="text" name="saturated" class="form-control" id="saturated" value="{{ old('saturated', $item->saturated ?? '') }}" placeholder="Enter Saturated Fat">
                                            <small class="text-muted">Please enter the value in g (e.g., 2.91g)</small>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="sugars" class="form-label">Sugars</label>
                                            <input type="text" name="sugars" class="form-control" id="sugars" value="{{ old('sugars', $item->sugars ?? '') }}" placeholder="Enter Sugars">
                                            <small class="text-muted">Please enter the value in g (e.g., 0g)</small>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="dietary_fibre" class="form-label">Dietary Fibre</label>
                                            <input type="text" name="dietary_fibre" class="form-control" id="dietary_fibre" value="{{ old('dietary_fibre', $item->dietary_fibre ?? '') }}" placeholder="Enter Dietary Fibre">
                                            <small class="text-muted">Please enter the value in g (e.g., 3.2g)</small>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="sodium" class="form-label">Sodium</label>
                                            <input type="text" name="sodium" class="form-control" id="sodium" value="{{ old('sodium', $item->sodium ?? '') }}" placeholder="Enter Sodium">
                                            <small class="text-muted">Please enter the value in mg (e.g., 72.15mg)</small>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <img class="pull-right mt-1" id="lockIcon" src="{{ webAssets('uploads/lock.png') }}" alt="Lock Icon" />
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

                            <!-- Swap Items Selection (Visible only if 'Is Swapped' is Yes) -->
                            <div class="col-md-12" id="swapItemsContainer" style="display: none;">
                                <label for="swap_item_ids" class="form-label">Swap Items</label>
                                <select name="swap_item_ids[]" class="form-control" id="swap_item_ids" multiple>

                                </select>
                            </div>

                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($item) && $item->image)
                                    <img src="{{ webAssets('storage/' . $item->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
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

<div class="modal" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editFoodForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFoodModalLabel">Edit Food Qty</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="dynamicQtyMeasurementContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-edit-food">Update & Lock</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="woolworthJsonModal" tabindex="-1" aria-labelledby="jsonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="jsonModalLabel">Woolworths JSON</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="json-content" class="table-responsive">
            <!-- Dynamic content will go here -->
            </div>
        </div>
        </div>
    </div>
</div>

<div id="loader-2" style="display: none;">
    <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" width="100px" height="100px" alt="Loading..." />
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
        let intendedHref = '';
        let isIntentionalSubmit = false;
        let saveModal = new bootstrap.Modal(document.getElementById('saveFoodModal'));

        // Track input changes
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('input', () => {
                hasUnsavedChanges = true;
            });

            input.addEventListener('change', () => {
                hasUnsavedChanges = true;
            });
        });

        // ✅ Also track changes via Select2 events
        $('#tag_ids').on('change', function () {
            hasUnsavedChanges = true;
        });

        // Intercept all anchor clicks
        document.querySelectorAll('a').forEach(anchor => {
            anchor.addEventListener('click', function (event) {
                if (hasUnsavedChanges && !isIntentionalSubmit) {
                    event.preventDefault();
                    const clickedLink = event.target.closest('a');
                    if (clickedLink) {
                        intendedHref = clickedLink.href;
                        saveModal.show(); // ✅ Use Bootstrap modal API
                    }
                }
            });
        });

        // Warn on window close
        window.addEventListener('beforeunload', function (event) {
            if (hasUnsavedChanges && !isIntentionalSubmit) {
                event.preventDefault();
                event.returnValue = '';
            }
        });

        // Handle Save Changes
        document.getElementById('saveChanges').addEventListener('click', function () {
            isIntentionalSubmit = true;
            hasUnsavedChanges = false;
            document.getElementById('foodForm').submit();
            saveModal.hide(); // ✅ Hide modal with API
        });

        // Handle Leave Without Saving
        document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
            hasUnsavedChanges = false;
            saveModal.hide(); // Optional: clean UI
            if (intendedHref) {
                window.location.href = intendedHref;
            }
        });

        // Form submit clears flag
        document.getElementById('foodForm').addEventListener('submit', function () {
            hasUnsavedChanges = false;
        });

        // Manual Save button (optional)
        $('#save-edit-food').on('click', function () {
            const updatedData = [];
            $('#dynamicQtyMeasurementContainer .row').each(function () {
                const qty = parseFloat($(this).find('.modal-qty').val());
                const unit = $(this).find('.modal-unit').val();
                const checked = $(this).find('.modal-checked').is(':checked');
                if (!isNaN(qty) && unit !== '') {
                    updatedData.push({ qty, unit, checked });
                }
            });

            $('#selected_measurements_hidden').val(JSON.stringify(updatedData));
            isIntentionalSubmit = true;
            // $('#foodForm').submit(); // optional
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.woolworth-json').forEach(button => {
            button.addEventListener('click', function () {
                const json = JSON.parse(this.getAttribute('data-json') || '{}');
                const prettyJson = JSON.stringify(json, null, 4); // Pretty print

                document.getElementById('json-content').textContent = json;
                const modal = new bootstrap.Modal(document.getElementById('woolworthJsonModal'));
                modal.show();
            });
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
            // $('#resetQty').prop('disabled',false);
        } else {
            $('#lockIcon').hide();
            $('#lockableBox').removeClass('locked');
            // $('#resetQty').prop('disabled',true);
        }

        $('#lockCheckbox').on('change', function () {
            console.log('event call');
            if ($(this).is(':checked')) {
                const rawData = $('#selected_measurements_hidden').val();
                let data = [];

                try {
                    data = JSON.parse(rawData);
                } catch (e) {
                    alert("Invalid measurement data format.");
                    $(this).prop('checked', false);
                    return;
                }

                // Check if data is null, not an array, or an empty array
                if (!Array.isArray(data) || data.length === 0) {
                    alert("Please select quantity and unit before locking.");
                    $(this).prop('checked', false);
                    return;
                }

                // Check for any missing qty or unit values
                const missingValues = data.some(item => !item.qty || !item.unit);
                if (missingValues) {
                    alert("All measurements must have both quantity and unit.");
                    $(this).prop('checked', false);
                    return;
                }

                $('#lockableBox').addClass('locked');
                $('#lockLabel').text('Unlock');
                $('#lockIcon').show();
                // $('#resetQty').prop('disabled',false);
                $(this).val(1);

                const $container = $('#dynamicQtyMeasurementContainer');
                $container.empty();

                data.forEach((item, index) => {
                    const checked = item.checked ? 'checked' : '';
                    const html = `
                        <div class="row mb-2" data-index="${index}">
                            <div class="col-2 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input modal-checked" ${checked}>
                            </div>
                            <div class="col-5">
                                <input type="text" step="any" class="form-control modal-qty" value="${item.qty}">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control modal-unit" value="${item.unit}">
                            </div>
                        </div>`;
                    $container.append(html);
                });

                $('#editFoodModal').modal('show');
            } else {
                $('#lockableBox').removeClass('locked');
                $('#lockLabel').text('Lock');
                $('#lockIcon').hide(); // hide lock icon
                $(this).val(0);
                // $('#resetQty').prop('disabled',true);
            }
        });

        // Save button inside modal
        $('#save-edit-food').on('click', function () {
            const updatedData = [];

            $('#dynamicQtyMeasurementContainer .row').each(function () {
                const qty = parseFloat($(this).find('.modal-qty').val());
                const unit = $(this).find('.modal-unit').val();
                const checked = $(this).find('.modal-checked').is(':checked');
                if (!isNaN(qty) && unit !== '') {
                    let qty = $(this).find('.modal-qty').val()
                    updatedData.push({ qty, unit, checked });
                }
            });
            // Save back to hidden field
            $('#selected_measurements_hidden').val(JSON.stringify(updatedData));
            hasUnsavedChanges = false;

            // Build FormData from form
            const formData = new FormData(document.getElementById('foodForm'));
            // console.log(formData);
            $.ajax({
                url: $('#foodForm').attr('action'), // your update route
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // $('#editFoodModal').modal('hide');
                     @if(isset($item))
                        const editUrl = "{{ route('admin.items.edit', ':id') }}".replace(':id', {{ $item->id }});
                        window.location.href = editUrl;
                    @else
                        window.location.href = "{{ route('admin.items.index')}}";
                    @endif
                },
                error: function (xhr) {
                    $('#loader-2').hide();

                    let alertMessage = '';
                    let htmlMessage = '';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;

                        alertMessage = 'Validation errors:\n';
                        htmlMessage = '<strong>Validation errors:</strong><ul>';

                        for (const field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                const fieldErrors = errors[field].join(', ');
                                alertMessage += `- ${fieldErrors}\n`;
                                htmlMessage += `<li>${fieldErrors}</li>`;
                            }
                        }

                        htmlMessage += '</ul>';

                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        alertMessage = 'Error: ' + xhr.responseJSON.message;
                        htmlMessage = `<strong>Error:</strong> ${xhr.responseJSON.message}`;
                    } else {
                        alertMessage = 'An unknown error occurred.';
                        htmlMessage = 'An unknown error occurred.';
                    }

                    // Show both
                    alert(alertMessage);
                    $('#form-error-message').html(htmlMessage).show();
                }

            });
        });

        // Cancel confirm
        $('#cancel-confirm').on('click', function () {
            $('#confirmSaveModal').modal('hide');
        });

        $('.select2').select2({
            placeholder: "Select options",
            allowClear: true,
            width: '100%'
        });

        $('#tag_ids').select2({
            placeholder: "Select tags",
            allowClear: true,
            width: '100%'
        });

        $('#flag_ids').select2({
            placeholder: "Select Preferences",
            allowClear: true,
            width: '100%'
        });

        const currentItemId = @json(isset($item) ? $item->id : null);

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
                data: function (params) {
                    const selected = $('#swap_item_ids').val() || [];
                    return {
                        query: params.term,
                        selected_ids: selected,
                        exclude_id: currentItemId
                    };
                },
                processResults: function (response) {
                    const selectedIds = $('#swap_item_ids').val() || [];
                    const selectedIdSet = new Set(selectedIds.map(id => id.toString()));

                    return {
                        results: response.items
                            .filter(item =>
                                !selectedIdSet.has(item.id.toString()) &&
                                (!currentItemId || item.id.toString() !== currentItemId.toString())
                            )
                            .map(item => ({
                                id: item.id,
                                text: item.title,
                                image: item.image
                                    ? `{{ webAssets('storage') }}/${item.image}`
                                    : '{{ asset("default.png") }}',
                                has_flags: Array.isArray(item.flags) ? item.flags.length > 0 : !!item.flags
                            }))
                    };
                },
                cache: true
            }
        });

        function formatFood(food) {
            if (!food.id) return food.text;

            const image = food.image || '{{ asset("default.png") }}';
            const dot = food.has_flags ? '<span style="color: purple; font-size: 24px;">&#9679;</span> ' : '';

            return `
                <div style="display: flex; align-items: center;">
                    ${dot}
                    <img src="${image}" style="width: 30px; height: 30px; margin-left: 6px; margin-right: 10px; object-fit: cover;" />
                    <span>${food.text}</span>
                </div>
            `;
        }

        function formatFoodSelection(food) {
            if (!food.id) return food.text;

            const image = food.image || '{{ asset("default.png") }}';
            const dot = food.has_flags ? '<span style="color: purple; font-size: 24px;">&#9679;</span> ' : '';

            return `
                <div style="display: flex; align-items: center;">
                    ${dot}
                    <img src="${image}" style="width: 25px; height: 25px; margin-left: 6px; margin-right: 5px; object-fit: cover;" />
                    <span>${food.text}</span>
                </div>
            `;
        }

        // **ðŸ”¥ Preselect Swap Items in Edit Mode**
        @if(isset($item))
            const preselected = @json($item->swapItems);

            preselected.forEach(item => {
                const image = item.image
                    ? `{{ webAssets('storage') }}/${item.image}`
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

    });

    $(document).ready(function () {
        const savedMeasurement = $('select[name="unit"]').val();
        const savedQty = $('input[name="qty"]').val();
        let baseCarb = '';
        let baseProtein = '';
        let baseFat = '';
        let baseEnergy = '';
        let baseSaturated = '';
        let baseSugars = '';
        let baseDietaryFibre = '';
        let baseSodium = '';
        let AU_UNIT_EQUIVALENTS = buildUnitQtyMap();
        let selectedQtyUnit = '';
        @if(isset($item))
            selectedQtyUnit = @json($item->selected_qty_unit);
            const title = @json($item->title);
            baseCarb = @json($item->carbs);
            baseProtein = @json($item->protein);
            baseFat = @json($item->fat);
            baseEnergy = @json($item->energy);
            baseSaturated = @json($item->saturated);
            baseSugars = @json($item->sugars);
            baseDietaryFibre = @json($item->dietary_fibre);
            baseSodium = @json($item->sodium);
        @else
            baseCarb = $('#carbs').val();
            baseProtein = $('#protein').val();
            baseFat = $('#fat').val();
            baseEnergy = $('#energy').val();
            baseSaturated = $('#saturated').val();
            baseSugars = $('#sugars').val();
            baseDietaryFibre = $('#dietary_fibre').val();
            baseSodium = $('#sodium').val();
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

        $('#measurement').on('change', function () {
            const selectedMeasurement = $(this).val();
            const qty = $('input[name="qty"]').val();
            if (!selectedMeasurement) {
                console.warn('Measurement not selected');
                return;
            }
            AU_UNIT_EQUIVALENTS = buildUnitQtyMap();
            fetchAlternateMeasurements(selectedMeasurement, qty);
            setupNutritionSync(baseCarb, baseProtein, baseFat, baseEnergy, baseSaturated, baseSugars, baseDietaryFibre, baseSodium);
            setupDynamicMeasurementSync();
            updateHiddenField();
            // AU_UNIT_EQUIVALENTS = buildUnitQtyMap();
        });

        if(selectedQtyUnit) {
            updateHiddenField();
        }

        function fetchAlternateMeasurements(selectedMeasurement, qty) {
            const data = {
                id: $('input[name="id"]').val(),
                title: $('input[name="title"]').val(),
                carbs: $('input[name="carbs"]').val(),
                protein: $('input[name="protein"]').val(),
                fat: $('input[name="fat"]').val(),
                energy: $('input[name="energy"]').val(),
                saturated: $('input[name="saturated"]').val(),
                sugars: $('input[name="sugars"]').val(),
                dietary_fibre: $('input[name="dietary_fibre"]').val(),
                sodium: $('input[name="sodium"]').val(),
                qty: qty,
                measurement: selectedMeasurement,
                serving_size: $('input[name="serving_size"]').val(),
                serving_per_pack: $('input[name="serving_per_pack"]').val(),
            };

            $('#loader-2').show();

            $.ajax({
                url: "{{ route('calculate.nutrition') }}",
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    // Update nutrition values
                    $('#carbs').val(parseFloat(data.carbs).toFixed(2));
                    $('#protein').val(parseFloat(data.protein).toFixed(2));
                    $('#fat').val(parseFloat(data.fat).toFixed(2));
                    $('#energy').val(data.energy);
                    $('#saturated').val(data.saturated);
                    $('#sugars').val(data.sugars);
                    $('#dietary_fibre').val(data.dietary_fibre);
                    $('#sodium').val(data.sodium);

                    // Remove all alt-* rows
                    $('.alt-qty-checkbox, .alt-qty-input, .alt-unit-dropdown')
                        .closest('.row.align-items-center')
                        .remove();

                    const mainRow = $('.qty-checkbox').closest('.row.align-items-center');

                    if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {
                        const entries = Object.keys(data.alternate_serving_sizes).map(key => [key, data.alternate_serving_sizes[key]]);
                        entries.forEach(([unitKey, combined], index) => {
                            const [qtyVal, unitVal] = combined.split(" ");

                            const addMoreButton = (index === 0)
                                ? `<button type="button" id="add-more" class="btn btn-primary">Add More</button><small class="text-danger">*This will not adjust other measurements</small>`
                                : `<div style="height: 38px;"></div>`;

                            const rowHtml = `
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <input
                                                type="checkbox"
                                                class="alt-qty-checkbox me-2 alternate-measurement-checkbox"
                                                data-qty="${qtyVal}"
                                                data-unit="${unitVal}"
                                                id="${unitKey}"
                                                >
                                            <input
                                                type="text"
                                                class="form-control alt-qty-input alternate-qty-input"
                                                value="${qtyVal}"
                                                placeholder="Enter quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control alt-unit-dropdown mt-1 alternate-measurement-dropdown alt-measurement-wrapper">
                                            <option value="${unitVal}" selected>${unitVal}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        ${addMoreButton}
                                    </div>
                                </div>
                            `;

                            mainRow.after(rowHtml);
                        });
                    }

                    // Update main input states
                    $('#qty').val(qty);
                    $('#measurement').val(selectedMeasurement);
                    $('.qty-checkbox').data('qty', qty).data('unit', selectedMeasurement);

                    setupNutritionSync(
                        parseFloat(parseFloat(data.carbs).toFixed(2)),
                        parseFloat(parseFloat(data.protein).toFixed(2)),
                        parseFloat(parseFloat(data.fat).toFixed(2)),
                        data.energy,
                        data.saturated,
                        data.sugars,
                        data.dietary_fibre,
                        data.sodium
                    );

                    setupDynamicMeasurementSync();

                    AU_UNIT_EQUIVALENTS = buildUnitQtyMap();
                    updateHiddenField();

                    // Hide loader after success
                    $('#loader-2').hide();
                },
                error: function () {
                    // Hide loader after success
                    $('#loader-2').hide();
                    console.error("Error fetching alternate measurements.");
                }
            });
        }

        function updateHiddenField() {
            let selectedValues = [];
            let subtitleParts = [];

            // ✅ FIRST: Loop for main quantity + unit
            $('.qty-checkbox').each(function () {
                const $checkbox = $(this);
                const rawQty = $(this).siblings('.qty-input').val();
                const qty = parseFraction(rawQty) || 0;
                const isChecked = $checkbox.is(':checked');

                const index = $(this).closest('.col-md-4').find('.qty-checkbox').index(this);
                const unitDropdown = $(this).closest('.row').find('.unit-dropdown').eq(index);
                const unit = unitDropdown.val() || $(this).data('unit');

                if (unit) {
                    selectedValues.push({
                        qty: rawQty,
                        unit: unit,
                        checked: isChecked
                    });

                    if (isChecked) {
                        const unitDisplay = (unit.toLowerCase() === 'g' || unit.toLowerCase() === 'ml')
                            ? `${rawQty}${unit}`
                            : `${rawQty} ${unit}`;
                        subtitleParts.push(unitDisplay);
                    }
                }
            });

            // ✅ SECOND: Loop for alternate measurements
            $('.alt-qty-checkbox').each(function () {
                const $checkbox = $(this);
                const rawQty = $checkbox.siblings('.alt-qty-input').val();
                const qty = parseFraction(rawQty) || 0;
                const isChecked = $checkbox.is(':checked');

                const checkboxId = $checkbox.attr('id');
                const $unitDropdown = $(`#${checkboxId}.alt-unit-dropdown`);
                const unit = $unitDropdown.val() || $checkbox.data('unit');
                if (unit && rawQty) {
                    selectedValues.push({
                        qty: rawQty,
                        unit: unit,
                        checked: isChecked
                    });

                    if (isChecked) {
                        const unitDisplay = (unit.toLowerCase() === 'g' || unit.toLowerCase() === 'ml')
                            ? `${rawQty}${unit}`
                            : `${rawQty} ${unit}`;
                        subtitleParts.push(unitDisplay);
                    }
                }
            });

            $('.alt-measurement-group').each(function () {
                const $group = $(this);
                const $checkbox = $group.find('.alt-qty-checkbox');
                const $qtyInput = $group.find('.alt-qty-input');
                const $unitInput = $group.find('.alt-unit-dropdown');

                const rawQty = $qtyInput.val();
                const isChecked = $checkbox.is(':checked');
                const unit = $unitInput.val() || $checkbox.data('unit');

                if (unit && rawQty) {
                    selectedValues.push({
                        qty: rawQty,
                        unit: unit,
                        checked: isChecked
                    });

                    if (isChecked) {
                        const unitDisplay = (unit.toLowerCase() === 'g' || unit.toLowerCase() === 'ml')
                            ? `${rawQty}${unit}`
                            : `${rawQty} ${unit}`;
                        subtitleParts.push(unitDisplay);
                    }
                }
            });

            let energy = parseFloat($('#energy').val()) || 0;
            let carbs = parseFloat($('#carbs').val()) || 0;
            let protein = parseFloat($('#protein').val()) || 0;
            let fat = parseFloat($('#fat').val()) || 0;

            $('.nutrition-info').text(`Energy: ${energy.toFixed(2)}kJ, Protein: ${protein.toFixed(2)}g, Carb: ${carbs.toFixed(2)}g, Fat: ${fat.toFixed(2)}g`);

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

        function buildUnitQtyMap() {
            let map = {};

            const mainQty = parseFraction($('#qty').val());
            const mainUnit = $('#measurement').val()?.toLowerCase();
            if (!isNaN(mainQty) && mainUnit) {
                map[mainUnit] = mainQty;
            }

            // Handle alternative quantities
            $('.alt-qty-checkbox').each(function () {
                let qty = parseFraction($(this).siblings('.alt-qty-input').val()) || 0;
                let unit = $(this).data('unit')?.toLowerCase();
                if (!isNaN(qty) && unit) {
                    map[unit] = qty;
                }
            });

            return map;
        }

        function setupNutritionSync(baseCarb, baseProtein, baseFat, baseEnergy, baseSaturated, baseSugars, baseDietaryFibre, baseSodium) {
            function updateNutrition(currentQty, currentUnit) {
                if (!currentQty || !currentUnit) return;
                currentUnit = currentUnit.toLowerCase();

                const baseEquivalent = AU_UNIT_EQUIVALENTS[currentUnit];
                if (!baseEquivalent) {
                    console.warn('Unknown unit used in conversion:', currentUnit);
                    return;
                }
                const ratio = currentQty / baseEquivalent;

                // Numeric fields (no units or symbols)
                const newCarbs = parseFloat(baseCarb) * ratio;
                const newProtein = parseFloat(baseProtein) * ratio;
                const newFat = parseFloat(baseFat) * ratio;

                // Unit/symbol fields — handled via helper
                const newEnergy = scaleNutritionValue(baseEnergy, ratio);
                const newSaturated = scaleNutritionValue(baseSaturated, ratio);
                const newSugars = scaleNutritionValue(baseSugars, ratio);
                const newDietaryFibre = scaleNutritionValue(baseDietaryFibre, ratio);
                const newSodium = scaleNutritionValue(baseSodium, ratio);

                // Update input values
                $('#carbs').val(newCarbs.toFixed(2));
                $('#protein').val(newProtein.toFixed(2));
                $('#fat').val(newFat.toFixed(2));
                $('#energy').val(newEnergy);
                $('#saturated').val(newSaturated);
                $('#sugars').val(newSugars);
                $('#dietary_fibre').val(newDietaryFibre);
                $('#sodium').val(newSodium);
            }

            // Listen for changes in alt qty input
            $('.alt-qty-input').off('input').on('input', function () {
                const inputVal = $(this).val();
                const qty = parseFraction(inputVal) || 0;

                const $rowWrapper = $(this).closest('.d-flex');
                const $checkbox = $rowWrapper.find('.alt-qty-checkbox');
                const altId = $checkbox.attr('id');
                const $dropdown = $(`.alt-unit-dropdown#${altId}`);

                let unit = null;
                if ($dropdown.length > 0) {
                    unit = $dropdown.val()?.toLowerCase();
                } else {
                    unit = $checkbox.data('unit')?.toLowerCase();
                }

                if (unit) {
                    updateNutrition(qty, unit);
                } else {
                    console.warn("Could not determine unit for nutrition sync.");
                }
            });

            // Handle main qty input
            $('.qty-input').off('input').on('input', function () {
                const inputVal = $(this).val();
                const qty = parseFraction(inputVal);
                const unitDropdown = $('#measurement').val()?.toLowerCase();

                updateNutrition(qty, unitDropdown);
            });
        }

        function scaleNutritionValue(value, ratio) {
            if (typeof value !== 'string') return value;

            value = value.trim();

            // Capture optional symbol (e.g., '<', '~'), number, and unit
            const match = value.match(/^([<~]?)[\s]*([\d.]+)\s*([a-zA-Z]*)$/);

            if (!match) return value; // fallback if doesn't match

            const symbol = match[1];        // '<' or '~' or ''
            const number = parseFloat(match[2]); // numeric part
            const unit = match[3];          // 'g', 'mg', 'kJ', etc.

            const scaled = (number * ratio).toFixed(2);

            return `${symbol} ${scaled}${unit}`.trim();
        }

        function setupDynamicMeasurementSync() {
            let isSyncing = false;

            function getQtyFromInput($input) {
                const val = $input.val();
                if (val.trim() === '') return null;
                const qty = parseFraction(val);
                return isNaN(qty) ? null : qty;
            }

            function getUnitFromWrapper($wrapper) {
                const $dropdown = $wrapper.find('.alt-unit-dropdown');
                if ($dropdown.length > 0) {
                    return $dropdown.val()?.toLowerCase();
                } else {
                    return $wrapper.find('.alt-qty-checkbox').data('unit')?.toLowerCase();
                }
            }

            function syncAllFields(baseQty, baseUnit, $sourceInput) {
                if (!baseQty || !baseUnit) return;

                const baseEquivalent = AU_UNIT_EQUIVALENTS[baseUnit];
                if (!baseEquivalent) return;

                isSyncing = true;

                // Update main quantity
                const mainUnit = $('#measurement').val()?.toLowerCase();
                const $mainQtyInput = $('#qty');
                if (mainUnit) {
                    const mainEquivalent = AU_UNIT_EQUIVALENTS[mainUnit];
                    if (mainEquivalent) {
                        const newMainQty = ((baseQty * mainEquivalent) / baseEquivalent).toFixed(1);
                        if (!$sourceInput.is($mainQtyInput)) {
                            $mainQtyInput.val(newMainQty);
                        }
                    }
                }

                // Update alt quantities
                $('.alt-qty-input').each(function () {
                    const $input = $(this);
                    if ($sourceInput.is($input)) return; // Skip field the user just edited

                    const $wrapper = $input.closest('.d-flex');
                    const $checkbox = $wrapper.find('.alt-qty-checkbox');

                    let altUnit = null;
                    const id = $checkbox.attr('id');
                    const $dropdown = $(`.alt-unit-dropdown#${id}`);
                    if ($dropdown.length > 0) {
                        altUnit = $dropdown.val()?.toLowerCase();
                    }

                    if (!altUnit) {
                        altUnit = $checkbox.data('unit')?.toLowerCase();
                    }

                    if (!altUnit) return;

                    const altEquivalent = AU_UNIT_EQUIVALENTS[altUnit];
                    if (!altEquivalent) return;

                    const newAltQtyDecimal = (baseQty * altEquivalent) / baseEquivalent;
                    $input.val(newAltQtyDecimal.toFixed(1));
                });

                isSyncing = false;
                updateHiddenField();
            }

            function handleSync($input) {
                if (isSyncing) return;

                const isMainQty = $input.is('#qty');
                let unit = null;

                if (isMainQty) {
                    unit = $('#measurement').val()?.toLowerCase();
                } else {
                    const $rowWrapper = $input.closest('.d-flex');
                    const $checkbox = $rowWrapper.find('.alt-qty-checkbox');
                    const altId = $checkbox.attr('id');
                    const $dropdown = $(`.alt-unit-dropdown#${altId}`);

                    if ($dropdown.length > 0) {
                        unit = $dropdown.val()?.toLowerCase();
                    } else {
                        unit = $checkbox.data('unit')?.toLowerCase();
                    }
                }

                const currentVal = $input.val().trim();
                const currentQty = getQtyFromInput($input);

                if (currentQty === null) {
                    return;
                }

                $input.data('last-val', currentVal);
                $input.data('use-raw', true); // Mark this input as recently edited
                $input.data('last-input', currentVal);

                if (currentQty !== null && unit) {
                    syncAllFields(currentQty, unit, $input);
                }

                updateHiddenField();
            }

            // Track initial values
            $('.qty-input, .alt-qty-input').each(function () {
                $(this).data('last-val', $(this).val().trim());
                $(this).data('last-input', $(this).val().trim());
            });

            // Events
            $(document).on('blur', '.qty-input, .alt-qty-input', function () {
                handleSync($(this));
            });

            $(document).on('keypress', '.qty-input, .alt-qty-input', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $(this).blur();
                }
            });
        }

        function parseFraction(input) {
            if (!input) return NaN;
            input = String(input).trim();

            // Mixed fraction: "1 1/2"
            if (/^\d+\s+\d+\/\d+$/.test(input)) {
                const [whole, frac] = input.split(' ');
                const [num, denom] = frac.split('/');
                return parseInt(whole) + (parseFloat(num) / parseFloat(denom));
            }

            // Simple fraction: "1/2"
            if (/^\d+\/\d+$/.test(input)) {
                const [num, denom] = input.split('/');
                return parseFloat(num) / parseFloat(denom);
            }

            // Decimal or integer
            if (!isNaN(input)) {
                return parseFloat(input);
            }

            return NaN;
        }

        // Initialize nutrition sync
        setupNutritionSync(baseCarb, baseProtein, baseFat, baseEnergy, baseSaturated, baseSugars, baseDietaryFibre, baseSodium);
        setupDynamicMeasurementSync();

        let unitIndex = $('.alternate-measurement-checkbox').length;

        $(document).on('click', '#add-more', function () {
            console.log('click event call');
            let $lastRow = $('.add-more-container > .row.align-items-center').last();
            const $clone = $lastRow.clone();

            const checkboxId = `alt-qty-checkbox-${unitIndex}`;
            const unitInputId = `alt-unit-dropdown-${unitIndex}`;

            // Reset checkbox
            $clone.find('input[type="checkbox"]')
                .prop('checked', false)
                .attr('data-qty', '')
                .attr('data-unit', '')
                .attr('id', checkboxId)
                .removeClass('qty-checkbox')
                .addClass('alt-qty-checkbox alternate-measurement-checkbox');

            // Reset quantity input
            $clone.find('input[type="text"]').each(function () {
                const $input = $(this);
                if ($input.hasClass('qty-input') || $input.hasClass('alt-qty-input')) {
                    $input
                        .val('')
                        .removeAttr('id')
                        .removeAttr('name')
                        .removeClass('qty-input')
                        .addClass('alt-qty-input alternate-qty-input');
                }
            });

            // Replace select with input
            const $select = $clone.find('select');
            if ($select.length) {
                const $inputUnit = $('<input>', {
                    type: 'text',
                    class: 'form-control alt-unit-dropdown alternate-measurement-unit',
                    id: unitInputId,
                    placeholder: 'Enter unit',
                    value: ''
                });
                $select.replaceWith($inputUnit);
            } else {
                $clone.find('.alt-unit-dropdown')
                    .val('')
                    .attr('id', unitInputId);
            }

            // Add remove button
            const $removeBtnCol = $clone.find('.col-md-4').last();
            if ($removeBtnCol.find('.remove-qty').length === 0) {
                $removeBtnCol.html('<button type="button" class="btn btn-danger btn-sm remove-qty">Remove</button>');
            }

            // ✅ Wrap with a class for alternate measurement grouping
            const $wrapper = $('<div class="alt-measurement-group"></div>');
            $wrapper.append($clone);

            // ✅ Append to only the right container
            $('.add-more-container').append($wrapper);

            unitIndex++;
            updateHiddenField();
        });

        $('#resetQty').on('click', function () {
            const selectedQty = $(this).data('qty');
            const selectedUnit = $(this).data('unit');
            const title = $(this).data('title');

            // Set hidden or visible fields if necessary

            // Update the checkbox dataset
            $('.qty-checkbox').data('qty', selectedQty).data('unit', selectedUnit);

            const data = {
                id: $('input[name="id"]').val(),
                title: $('input[name="title"]').val(),
                carbs: null,
                protein: null,
                fat: null,
                energy: null,
                qty: selectedQty,
                measurement: selectedUnit,
                serving_size: $('input[name="serving_size"]').val(),
                serving_per_pack: $('input[name="serving_per_pack"]').val(),
            };
            $('#loader-2').show();
            // Call your existing function
            $.ajax({
                url: "{{ route('calculate.nutrition') }}",
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    // Update nutrition values
                        $('#carbs').val(parseFloat(data.carbs).toFixed(2));
                        $('#protein').val(parseFloat(data.protein).toFixed(2));
                        $('#fat').val(parseFloat(data.fat).toFixed(2));
                        $('#energy').val(data.energy);
                        $('#saturated').val(data.saturated);
                        $('#sugars').val(data.sugars);
                        $('#dietary_fibre').val(data.dietary_fibre);
                        $('#sodium').val(data.sodium);

                    // Remove all alt-* rows
                    $('.alt-qty-checkbox, .alt-qty-input, .alt-unit-dropdown')
                        .closest('.row.align-items-center')
                        .remove();

                    const mainRow = $('.qty-checkbox').closest('.row.align-items-center');

                    if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {

                        const entries = Object.keys(data.alternate_serving_sizes).map(key => [key, data.alternate_serving_sizes[key]]);

                        entries.forEach(([unitKey, combined], index) => {
                            const [qtyVal, unitVal] = combined.split(" ");

                            const addMoreButton = (index === 0)
                                ? `<button type="button" id="add-more" class="btn btn-primary">Add More</button><small class="text-danger">*This will not adjust other measurements</small>`
                                : `<div style="height: 38px;"></div>`;

                            const rowHtml = `
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <input
                                                type="checkbox"
                                                class="alt-qty-checkbox me-2 alternate-measurement-checkbox"
                                                data-qty="${qtyVal}"
                                                data-unit="${unitVal}"
                                                id="${unitKey}"
                                                >
                                            <input
                                                type="text"
                                                class="form-control alt-qty-input alternate-qty-input"
                                                value="${qtyVal}"
                                                placeholder="Enter quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control alt-unit-dropdown mt-1 alternate-measurement-dropdown alt-measurement-wrapper">
                                            <option value="${unitVal}" selected>${unitVal}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        ${addMoreButton}
                                    </div>
                                </div>
                            `;

                            mainRow.after(rowHtml);
                        });

                    }
                    $('#loader-2').hide();
                    // Update main input states
                    $('#qty').val(selectedQty);
                    $('#measurement').val(selectedUnit);

                    $('.qty-checkbox').data('qty', qty).data('unit', selectedUnit);

                    setupNutritionSync(
                        parseFloat(parseFloat(data.carbs).toFixed(2)),
                        parseFloat(parseFloat(data.protein).toFixed(2)),
                        parseFloat(parseFloat(data.fat).toFixed(2)),
                        data.energy,
                        data.saturated,
                        data.sugars,
                        data.dietary_fibre,
                        data.sodium
                    );

                    setupDynamicMeasurementSync();

                    AU_UNIT_EQUIVALENTS = buildUnitQtyMap();
                    updateHiddenField();
                },
                error: function (xhr) {
                    $('#loader-2').hide();

                    let alertMessage = '';
                    let htmlMessage = '';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;

                        alertMessage = 'Validation errors:\n';
                        htmlMessage = '<strong>Validation errors:</strong><ul>';

                        for (const field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                const fieldErrors = errors[field].join(', ');
                                alertMessage += `- ${fieldErrors}\n`;
                                htmlMessage += `<li>${fieldErrors}</li>`;
                            }
                        }

                        htmlMessage += '</ul>';

                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        alertMessage = 'Error: ' + xhr.responseJSON.message;
                        htmlMessage = `<strong>Error:</strong> ${xhr.responseJSON.message}`;
                    } else {
                        alertMessage = 'An unknown error occurred.';
                        htmlMessage = 'An unknown error occurred.';
                    }

                    // Show both
                    alert(alertMessage);
                    $('#form-error-message').html(htmlMessage).show();
                }


            });
        });

        // Remove handler for dynamic remove buttons
        $(document).on('click', '.remove-qty', function () {
            $(this).closest('.row.align-items-center').remove();
            updateHiddenField();
        });

        $(document).on('blur', '.alt-unit-dropdown', function () {
            updateHiddenField();
        });

    });

    $(document).on('click', '#generateDescriptionBtn', function () {
        const mealTitle = $('input[name="title"]').val(); // Make sure the title input has this name
        if (!mealTitle) {
            alert('Please enter a meal title first.');
            return;
        }

        $.ajax({
            url: '{{ route("generate.description") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            contentType: 'application/json',
            data: JSON.stringify({ title: mealTitle }),

            // 👇 Show loader before sending request
            beforeSend: function () {
                $('#loader-2').show();
            },

            // 👇 Hide loader after request finishes (success or error)
            complete: function () {
                $('#loader-2').hide();
            },

            success: function (response) {
                $('#description').val(response.description);
            },
            error: function (xhr, status, error) {
                console.error('Error generating description:', error);
                alert('Failed to generate description. Please try again.');
            }
        });
    });

</script>
@endpush
@endsection
