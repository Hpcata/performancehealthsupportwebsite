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
        @media only screen and (max-width: 767px) {
            .col-btn{
                display: flex;
                gap:8px;
                flex-direction: column;
            }
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 8px;
            right: 1px;
            width: 20px;
        }
    </style>
    <div class="container-xxl">
        <div class="row align-items-center">
            <div class="border-0 mb-4">
                <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                    <h3 class="fw-bold mb-0">{{ isset($meal) ? 'Edit Meal' : 'Create Meal' }}</h3>
                        <a type="button" href="{{ route('admin.meals.index') }}" class="btn btn-primary btn-set-task">Back</a>
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
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $meal->title ?? '') }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description Field -->
                                <div class="col-md-12">
                                    <label for="description" class="form-label d-flex justify-content-between">
                                        <span>Description</span>
                                    </label>
                                    <textarea name="description" id="description" class="form-control" rows="4" maxlength="180">{{ old('description', $meal->description ?? '') }}</textarea>
                                    <small id="desc-count" class="text-muted" style="bottom: 10px; left: 15px; font-size: 0.75rem;">0 / 180</small>
                                </div>

                                <div class="col-md-12">
                                    <label for="note" class="form-label">Notes</label>
                                    <textarea name="note" class="form-control" rows="2">{{ old('note', $meal->note ?? '') }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <label for="tag_ids" class="form-label">Select Tags</label>
                                    <select name="tag_ids[]" class="form-control select2" id="tag_ids" multiple>
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ collect(old('tag_ids', isset($meal) ? $meal->tags->pluck('id') : []))->contains($tag->id) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Meal Times -->
                                <div class="col-md-12">
                                    <label for="meal_times" class="form-label">Category</label>
                                    <select name="meal_times[]" id="meal_times" class="form-control select2" multiple>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ collect(old('meal_times', isset($meal) ? $meal->categories->pluck('id') : []))->contains($category->id) ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sub Categories -->
                                <div class="col-md-12">
                                    <label for="sub_categories" class="form-label">Sub Categories</label>
                                    <select name="categories[]" id="categories" class="form-control select2" multiple>
                                        @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}"
                                            {{ collect(old('categories', isset($meal) ? $meal->subCategories->pluck('id') : []))->contains($subCategory->id) ? 'selected' : '' }}>
                                            {{ $subCategory->title }}
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
                                                    <th style="width: 30px"></th>
                                                    <th>Food</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sortable-food-items">
                                                @php
                                                    $flaggedFoodIds = $foods->filter(fn($f) => $f->flags->isNotEmpty())->pluck('id')->toArray();
                                                    $oldFoodIds = old('food_ids', []);
                                                @endphp

                                                @if (old('food_ids'))
                                                    @foreach (old('food_ids') as $index => $foodId)
                                                        <tr class="food-row">
                                                            <td class="drag-handle p-0" style="cursor: move; text-align: center; vertical-align: middle;">
                                                                <i class="icofont-expand-alt" style="font-size:30px;"></i>
                                                            </td>
                                                            <td>
                                                                <select name="food_ids[]" class="form-control food-select">
                                                                    <option value="">Select Food</option>
                                                                    @foreach ($foods as $food)
                                                                        <option value="{{ $food->id }}" {{ $food->id == $foodId ? 'selected' : '' }}>
                                                                            {{ $food->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <p class="food-title-qty mt-2 mb-0">
                                                                    @if (in_array($foodId, $flaggedFoodIds))
                                                                        <span style="color: purple; font-size: 24px;">&#9679;</span>
                                                                    @endif
                                                                    <strong>{{ $foods->firstWhere('id', $foodId)->title ?? '' }}</strong>
                                                                </p>

                                                                <p class="nutrition-info mt-2 mb-0 text-muted">
                                                                    Energy: {{ old('energy.' . $index, 0) }}kJ,
                                                                    Protein: {{ old('protein.' . $index, 0) }}g,
                                                                    Carb: {{ old('carbs.' . $index, 0) }}g,
                                                                    Fat: {{ old('fat.' . $index, 0) }}g
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-success edit-food col-btn"
                                                                    data-carbs="{{ old('carbs.' . $index, '') }}"
                                                                    data-protein="{{ old('protein.' . $index, '') }}"
                                                                    data-fat="{{ old('fat.' . $index, '') }}"
                                                                    data-energy="{{ old('energy.' . $index, 0) }}"
                                                                    data-serving-size="{{ old('serving_size.' . $index, '') }}"
                                                                    data-serving-size-unit="{{ old('serving_size_unit.' . $index, '') }}">
                                                                    <i class="icofont-edit text-success"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger remove-food">
                                                                    <i class="icofont-ui-delete text-danger"></i>
                                                                </button>
                                                            </td>

                                                            <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value='{{ old('selected_qty_unit.' . $index, '') }}'>
                                                            <input type="hidden" class="hidden-protein" name="protein[]" value="{{ old('protein.' . $index, 0) }}">
                                                            <input type="hidden" class="hidden-carbs" name="carbs[]" value="{{ old('carbs.' . $index, 0) }}">
                                                            <input type="hidden" class="hidden-fat" name="fat[]" value="{{ old('fat.' . $index, 0) }}">
                                                            <input type="hidden" class="hidden-energy" name="energy[]" value="{{ old('energy.' . $index, 0) }}">
                                                            <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="{{ old('serving_size.' . $index, '') }}">
                                                            <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="{{ old('serving_size_unit.' . $index, '') }}">
                                                            <input type="hidden" class="food-order-input" name="food_order[]" value="{{ $index }}">
                                                        </tr>
                                                    @endforeach
                                                @elseif(isset($meal) && $meal->items->count() > 0)
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
                                                                        $checked = $unitSet['checked'] ?? false;

                                                                        if ($checked && $qty && $unit) {
                                                                            $noSpaceUnits = ['g', 'ml', 'mL'];
                                                                            $space = in_array($unit, $noSpaceUnits) ? '' : ' ';
                                                                            $parts[] = $qty . $space . $unit;
                                                                        }
                                                                    }

                                                                    if (!empty($parts)) {
                                                                        $quantityInfo = implode(' or ', $parts);
                                                                    }
                                                                }
                                                            }

                                                            if (empty($quantityInfo)) {
                                                                $noSpaceUnits = ['g', 'ml', 'mL'];
                                                                $space = in_array($item->pivot->item_qty_unit, $noSpaceUnits) ? '' : ' ';
                                                                $quantityInfo = $item->pivot->item_qty . $space . $item->pivot->item_qty_unit;
                                                            }
                                                        @endphp

                                                        <tr class="food-row">
                                                            <td class="drag-handle p-0" style="cursor: move; text-align: center; vertical-align: middle;">
                                                                <i class="icofont-expand-alt" style="font-size:30px;"></i>
                                                            </td>
                                                            <td>
                                                                <select name="food_ids[]" class="form-control select2 food-select">
                                                                    <option value="">Select Food</option>
                                                                    @foreach ($foods as $food)
                                                                        <option value="{{ $food->id }}" {{ $food->id == $item->id ? 'selected' : '' }}>
                                                                            {{ $food->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                {{-- ✅ Show purple dot if item->flags is a non-empty collection --}}
                                                                <p class="food-title-qty mt-2 mb-0">
                                                                    @if ($item->flags && $item->flags->isNotEmpty())
                                                                        <span style="color: purple; font-size: 24px;">&#9679;</span>
                                                                    @endif
                                                                    <strong>
                                                                        {{ $item->title }} {{ $quantityInfo }}
                                                                    </strong>
                                                                </p>

                                                                <p class="nutrition-info mt-2 mb-0 text-muted">
                                                                    Energy: {{ isset($item->pivot->energy) ? floatval($item->pivot->energy) : (floatval($item->energy) ?? 0) }}kJ,
                                                                    Protein: {{ ($item->pivot->protein)}}g,
                                                                    Carb: {{($item->pivot->carbs)}}g,
                                                                    Fat: {{($item->pivot->fat)}}g
                                                                </p>
                                                            </td>
                                                            <td >
                                                                <button type="button" class="btn btn-outline-success edit-food col-btn"
                                                                    data-carbs="{{$item->pivot->carbs}}"
                                                                    data-protein="{{$item->pivot->protein}}"
                                                                    data-fat="{{$item->pivot->fat}}"
                                                                    data-energy="{{ isset($item->pivot->energy) ? floatval($item->pivot->energy) : (floatval($item->energy) ?? 0) }}"
                                                                    data-serving-size="{{$item->serving_size}}"
                                                                    data-serving-size-unit="{{$item->serving_size_unit}}" >
                                                                    <i class="icofont-edit text-success"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger remove-food">
                                                                    <i class="icofont-ui-delete text-danger"></i>
                                                                </button>
                                                            </td>

                                                            <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value='{{ isset($item->pivot->selected_qty_unit) ? ($item->pivot->selected_qty_unit) : json_encode([["qty" => $item->pivot->item_qty, "unit" => $item->pivot->item_qty_unit]]) }}'>
                                                            <input type="hidden" class="hidden-protein" name="protein[]" value="{{$item->pivot->protein}}">
                                                            <input type="hidden" class="hidden-carbs" name="carbs[]" value="{{$item->pivot->carbs}}">
                                                            <input type="hidden" class="hidden-fat" name="fat[]" value="{{ $item->pivot->fat }}">
                                                            <input type="hidden" class="hidden-energy" name="energy[]" value="{{ isset($item->pivot->energy) ? floatval($item->pivot->energy) : (floatval($item->energy) ?? 0) }}">
                                                            <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="{{$item->serving_size}}">
                                                            <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="{{$item->serving_size_unit}}">
                                                            <input type="hidden" class="food-order-input" name="food_order[]" value="{{ $loop->index }}">
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="food-row">
                                                        <td class="drag-handle p-0" style="cursor: move; text-align: center; vertical-align: middle;">
                                                            <i class="icofont-expand-alt" style="font-size:30px;"></i>
                                                        </td>
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
                                                        <td class="col-btn">
                                                            <button type="button" class="btn btn-outline-success edit-food w-fit" data-carbs="" data-protein="" data-fat="" data-serving-size="" data-serving-size-unit=""><i class="icofont-edit text-success" ></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger remove-food w-fit"><i class="icofont-ui-delete text-danger"></i>
                                                            </button>
                                                        </td>
                                                        <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value="">
                                                        <input type="hidden" class="hidden-protein" name="protein[]" value="0">
                                                        <input type="hidden" class="hidden-carbs" name="carbs[]" value="0">
                                                        <input type="hidden" class="hidden-fat" name="fat[]" value="0">
                                                        <input type="hidden" class="hidden-energy" name="energy[]" value="0">
                                                        <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="0">
                                                        <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="0">
                                                        <input type="hidden" class="food-order-input" name="food_order[]" value="0">
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" id="add-food" class="btn btn-primary mt-2">Add More</button>
                                </div>
                                <div class="total-nutritions">
                                    <p style="font-size: 16px; "><strong>Meal Total: Energy: <span class="totalEnergy">0kJ</span> | Protein: <span class="totalProtein">0g</span> | Carb: <span class="totalCarbs">0g</span> | Fat: <span class="totalFat">g</span> </strong></p>
                                </div>

                                <!-- Image Field -->
                                <div class="col-md-12">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control">
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @if (isset($meal) && $meal->image)
                                    <img src="{{ webAssets('storage/' . $meal->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;" id="existing-meal-image">
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">OR Generate Image with AI</label>
                                    <input type="text" id="ai-image-prompt" name="ai-image-prompt" class="form-control mb-2" placeholder="Enter prompt to generate image (e.g., healthy banana breakfast)">

                                    <div id="image-buttons">
                                        <button type="button" id="generate-ai-image" class="btn btn-primary" {{ isset($meal) && $meal->image ? 'style=display:none;' : '' }}>Generate Image</button>
                                        <button type="button" id="edit-ai-image" class="btn btn-warning" {{ isset($meal) && $meal->image ? '' : 'style=display:none;' }}>Edit Image</button>
                                    </div>

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
                    <p><strong>Energy:</strong> <span id="modalEnergy">0kJ </span>, <strong>Protein:</strong> <span id="modalProtein">0g </span>, <strong>Carb:</strong> <span id="modalCarbs">0g </span>, <strong>Fat:</strong> <span id="modalFat">0g </span></p>
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
@endpush

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const descInput = document.getElementById('description');
        const countDisplay = document.getElementById('desc-count');
        const maxLength = 180;

        function updateCounter() {
            const currentLength = descInput.value.length;
            countDisplay.textContent = `${currentLength} / ${maxLength}`;
        }

        descInput.addEventListener('input', updateCounter);
        updateCounter(); // Initialize on load
    });

    document.addEventListener('DOMContentLoaded', function () {
        let hasUnsavedChanges = false;
        let intendedHref = ''; // Store the intended link URL

        // Initialize Bootstrap modal
        const saveModal = new bootstrap.Modal(document.getElementById('saveMealModal'));

        // Track changes in form fields
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

        $('#categories').on('change', function () {
            hasUnsavedChanges = true;
        });

        $('#meal_times').on('change', function () {
            hasUnsavedChanges = true;
        });

        // Mark unsaved changes when food selection changes
        $(document).on('change', '.food-select', function() {
            hasUnsavedChanges = true;
        });
        $(document).on('click', '#add-food, .remove-food', function() {
            hasUnsavedChanges = true;
        });
        $(document).on('click', '#save-edit-food', function() {
            hasUnsavedChanges = true;
        });
        // Also, if you want to catch changes as soon as user types in the modal (optional):
        $(document).on('input', '#dynamicQtyMeasurementContainer input', function() {
            hasUnsavedChanges = true;
        });

        // Handle all anchor link clicks
        document.querySelectorAll('a').forEach(anchor => {
            anchor.addEventListener('click', function (event) {
                if (hasUnsavedChanges) {
                    event.preventDefault(); // Prevent navigation

                    const clickedLink = event.target.closest('a');
                    if (clickedLink) {
                        intendedHref = clickedLink.href;
                        console.log('Intended Link:', intendedHref);
                        saveModal.show(); // ✅ Show modal using Bootstrap API
                    }
                }
            });
        });

        // Warn user on page refresh/close
        window.addEventListener('beforeunload', function (event) {
            if (hasUnsavedChanges) {
                event.preventDefault();
                event.returnValue = ''; // For most browsers
            }
        });

        // "Save Changes" button in modal
        document.getElementById('saveChanges').addEventListener('click', function () {
            hasUnsavedChanges = false;
            document.getElementById('mealForm').submit();
            saveModal.hide(); // ✅ Hide modal
        });

        // "Leave Without Saving" button
        document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
            hasUnsavedChanges = false;
            saveModal.hide(); // Optional visual
            if (intendedHref) {
                window.location.href = intendedHref;
            }
        });

        // Form submit clears unsaved flag
        document.getElementById('mealForm').addEventListener('submit', function () {
            hasUnsavedChanges = false;
        });
    });

    const loader = $('#loader');

    $(document).ready(function() {
        $('#categories').select2({
            placeholder: "Select sub categories",
            allowClear: true
        });
        $('#tag_ids').select2({
            placeholder: "Select tags",
            allowClear: true
        });

        $('#meal_times').select2({
            placeholder: "Select Category",
            allowClear: true
        })

        // Preload selected foods for Edit Mode
        @if (isset($meal))
            const preselectedFoods = @json($meal->items->pluck('id'));
            $('#food_ids').val(preselectedFoods).trigger('change');
        @endif

         // Initialize Sortable
        const sortable = new Sortable(document.getElementById('sortable-food-items'), {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                updateFoodOrder();
            }
        });

        // Function to update food order
        function updateFoodOrder() {
            $('.food-row').each(function(index) {
                let $row = $(this);
                $row.find('.food-order-input').remove();
                $row.append(`<input type="hidden" class="food-order-input" name="food_order[]" value="${index}">`);
            });
        }

        // Initialize order on page load
        updateFoodOrder();

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
                                    qty: item.qty || '',
                                    measurement: item.unit || '',
                                    serving_size: item.serving_size,
                                    serving_size_unit: item.serving_size_unit,
                                    protein: item.protein,
                                    carbs: item.carbs,
                                    fat: item.fat,
                                    energy: item.energy,
                                    image: item.image,
                                    flags: item.flags || [],
                                    selected_qty_unit: item.selected_qty_unit || []
                                };
                            })
                        };
                    },
                    cache: true
                },

                // ✅ Show purple dot in dropdown
                templateResult: function(item) {
                    if (!item.id) return item.text;

                    const hasFlags = Array.isArray(item.flags) && item.flags.length > 0;
                    const purpleDot = hasFlags
                        ? '<span style="color: purple; font-size: 24px;">&#9679;</span> '
                        : '';

                    return $(`<span>${purpleDot}${item.text}</span>`);
                },

                // ✅ Also show dot in selected box (optional)
                templateSelection: function(item) {
                    if (!item.id) return item.text;

                    const hasFlags = Array.isArray(item.flags) && item.flags.length > 0;
                    const purpleDot = hasFlags
                        ? '<span style="color: purple; font-size: 24px;">&#9679;</span> '
                        : '';

                    return $(`<span>${purpleDot}${item.text}</span>`);
                },

                escapeMarkup: function(markup) {
                    return markup; // Allow HTML rendering
                }
            })
            .on('select2:select', function (e) {
                const selectedFood = e.params.data;
                const row = $(this).closest('tr');

                const carb = parseFloat(selectedFood.carbs).toFixed(2);
                const protein = parseFloat(selectedFood.protein).toFixed(2);
                const fat = parseFloat(selectedFood.fat).toFixed(2);
                const numericEnergy = parseFloat(selectedFood.energy || '0').toFixed(2);

                row.find('.edit-food').data('carbs', selectedFood.carbs);
                row.find('.edit-food').data('protein', selectedFood.protein);
                row.find('.edit-food').data('fat', selectedFood.fat);
                row.find('.edit-food').data('serving-size', selectedFood.serving_size);
                row.find('.edit-food').data('serving-size-unit', selectedFood.serving_size_unit);
                row.find('.edit-food').data('energy', numericEnergy);

                row.find('.hidden-selected-qty-unit').val(selectedFood.selected_qty_unit);
                row.find('.hidden-protein').val(selectedFood.protein);
                row.find('.hidden-carbs').val(selectedFood.carbs);
                row.find('.hidden-fat').val(selectedFood.fat);
                row.find('.hidden-energy').val(numericEnergy);
                row.find('.hidden-serving-size').val(selectedFood.serving_size);
                row.find('.hidden-serving-size-unit').val(selectedFood.serving_size_unit);
                row.find('.nutrition-info').text(`Energy: ${numericEnergy}kJ, Protein: ${protein}g, Carb: ${carb}g, Fat: ${fat}g`);

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
                const filteredUnits = selectedUnits.filter(unit => unit.checked === true);

                if (filteredUnits.length > 0) {
                    displayQty = filteredUnits.map(unit => {
                        const needsNoSpace = ['g', 'ml', 'mL'].includes(unit.unit);
                        return needsNoSpace ? `${unit.qty}${unit.unit}` : `${unit.qty} ${unit.unit}`;
                    }).join(' or ');
                } else if (selectedFood.qty && selectedFood.measurement) {
                    const needsNoSpace = ['g', 'ml', 'mL'].includes(selectedFood.measurement);
                    displayQty = needsNoSpace ? `${selectedFood.qty}${selectedFood.measurement}` : `${selectedFood.qty} ${selectedFood.measurement}`;
                }

                const displayTitle = selectedFood.text || '';
                const hasFlags = Array.isArray(selectedFood.flags) && selectedFood.flags.length > 0;
                const purpleDot = hasFlags ? '<span style="color: purple; font-size: 24px;">&#9679;</span> ' : '';

                row.find('.hidden-selected-qty-unit').val(JSON.stringify(selectedUnits));
                row.find('.food-title-qty').html(`${purpleDot}<strong>${displayTitle} ${displayQty}</strong>`);

                updateTotalNutrition();
            });
        }

        updateTotalNutrition();

        function updateTotalNutrition() {
            let totalProtein = 0;
            let totalCarbs = 0;
            let totalFat = 0;
            let totalEnergy = 0;

            $('tr').each(function () {
                const protein = parseFloat($(this).find('.hidden-protein').val()) || 0;
                const carbs = parseFloat($(this).find('.hidden-carbs').val()) || 0;
                const fat = parseFloat($(this).find('.hidden-fat').val()) || 0;
                const energy = parseFloat($(this).find('.hidden-energy').val()) || 0;

                totalProtein += protein;
                totalCarbs += carbs;
                totalFat += fat;
                totalEnergy += energy;
            });

            $('.totalProtein').text(`${totalProtein.toFixed(2)}g`);
            $('.totalCarbs').text(`${totalCarbs.toFixed(2)}g`);
            $('.totalFat').text(`${totalFat.toFixed(2)}g`);
            $('.totalEnergy').text(`${totalEnergy.toFixed(2)}kJ`);
        }

        initializeSelect2();

        $('#add-food').click(function () {
            const newFoodRow = `
                <tr class="food-row">
                    <td class="drag-handle p-0" style="cursor: move; text-align: center; vertical-align: middle;">
                        <i class="icofont-expand-alt" style="font-size:30px;"></i>
                    </td>
                    <td>
                        <select name="food_ids[]" class="form-control food-select" required>
                            <option value="">Select Food</option>
                            @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->title }}</option>
                            @endforeach
                        </select>
                        <p class="food-title-qty mt-2 mb-0"><strong class="food-title"></strong></p>
                        <p class="nutrition-info mt-2 mb-0 text-muted">Energy: 0kJ, Protein: 0g, Carb: 0g, Fat: 0g</p>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-success edit-food" data-carbs="" data-protein="" data-fat="" data-serving-size="" data-serving-size-unit="">
                            <i class="icofont-edit text-success"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger remove-food">
                            <i class="icofont-ui-delete text-danger"></i>
                        </button>
                    </td>
                    <input type="hidden" class="hidden-selected-qty-unit" name="selected_qty_unit[]" value="">
                    <input type="hidden" class="hidden-protein" name="protein[]" value="0">
                    <input type="hidden" class="hidden-carbs" name="carbs[]" value="0">
                    <input type="hidden" class="hidden-fat" name="fat[]" value="0">
                    <input type="hidden" class="hidden-energy" name="energy[]" value="0">
                    <input type="hidden" class="hidden-serving-size" name="serving_size[]" value="0">
                    <input type="hidden" class="hidden-serving-size-unit" name="serving_size_unit[]" value="0">
                    <input type="hidden" class="food-order-input" name="food_order[]" value="0">
                </tr>
            `;
            $('#food-table tbody').append(newFoodRow);
            updateFoodOrder();
            // Initialize Select2 for the newly added row
            initializeSelect2();
        });

        $(document).on('click', '.remove-food', function () {
            $(this).closest('tr').remove();
            updateFoodOrder();
            updateTotalNutrition();

        });

        let $editingRow = null;

        $(document).on('click', '.edit-food', function () {
            $editingRow = $(this).closest('tr');
            const selectedFoodName = $editingRow.find('.food-select option:selected').text();

            // Always get the latest values from the row's hidden inputs
            let qtyUnits = [];
            const $hiddenInput = $editingRow.find('.hidden-selected-qty-unit');
            let selectedQtyUnitRaw = $hiddenInput.val();

            try {
                if (selectedQtyUnitRaw && selectedQtyUnitRaw !== "null") {
                    qtyUnits = JSON.parse(selectedQtyUnitRaw);
                }
            } catch (e) {
                console.warn('Invalid JSON in selected_qty_unit:', e);
                qtyUnits = [];
            }

            if (!Array.isArray(qtyUnits) || qtyUnits.length === 0) {
                const fallbackQty = $editingRow.find('.food-qty').val() || '';
                const fallbackUnit = $editingRow.find('.food-qty-measurement').val() || '';
                qtyUnits = [{ qty: fallbackQty, unit: fallbackUnit, checked: true }];
            }

            $('#editFoodModalLabel').text(`Edit ${selectedFoodName}`);
            const $container = $('#dynamicQtyMeasurementContainer');
            $container.empty();

            qtyUnits.forEach(({ qty, unit, checked }, index) => {
                const rowHtml = `
                    <div class="row mb-2 qty-unit-row align-items-center">
                        <div class="col-1 text-center">
                            <input type="checkbox" class="form-check-input modalQtyCheckbox" ${checked ? 'checked' : ''}>
                        </div>
                        <div class="col-5">
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

            // Get nutrition data from the current row's hidden inputs (these are always up to date)
            const carbs = parseFloat($editingRow.find('.hidden-carbs').val()).toFixed(2);
            const protein = parseFloat($editingRow.find('.hidden-protein').val()).toFixed(2);
            const fat = parseFloat($editingRow.find('.hidden-fat').val()).toFixed(2);
            const energy = parseFloat($editingRow.find('.hidden-energy').val()).toFixed(2);

            $('#modalCarbs').text(carbs + 'g');
            $('#modalProtein').text(protein + 'g');
            $('#modalFat').text(fat + 'g');
            $('#modalEnergy').text(energy + 'kJ');

            // Store the original values for this food item
            $('#editFoodModal').data('originalValues', {
                carbs: carbs,
                protein: protein,
                fat: fat,
                energy: energy,
                qtyUnits: qtyUnits
            });

            // Show modal
            $('#editFoodModal').modal('show');

            // Setup sync logic with isolated data
            setupDynamicMeasurementSync($container);
            setupNutritionSync($container, carbs, protein, fat, energy);

            // (REMOVED) Do not update the hidden input on every input/change in the modal!
        });

        // Reset modal fields on close (so next open always starts fresh)
        $('#editFoodModal').on('hidden.bs.modal', function () {
            $('#dynamicQtyMeasurementContainer').empty();
            $('#modalCarbs').text('0g');
            $('#modalProtein').text('0g');
            $('#modalFat').text('0g');
            $('#modalEnergy').text('0kJ');
            $editingRow = null;
        });

        function buildUnitQtyMap($container) {
            let map = {};

            $container.find('.qty-unit-row').each(function () {
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

            value = value.trim();
            if (value.includes('/')) {
                const parts = value.split(' ');
                if (parts.length === 2) {
                    // mixed fraction (e.g. "1 1/2")
                    const whole = parseFloat(parts[0]);
                    const [num, denom] = parts[1].split('/').map(Number);
                    return whole + (num / denom);
                } else {
                    const [num, denom] = value.split('/').map(Number);
                    return num / denom;
                }
            }

            return parseFloat(value);
        }

        function setupNutritionSync($container, baseCarbs, baseProtein, baseFat, baseEnergy) {
            // Build the unit equivalents map from modal inputs for this specific food item
            const unitEquivalents = buildUnitQtyMap($container);

            const $rows = $container.find('.qty-unit-row');
            if ($rows.length === 0) return;

            // Identify the base row (first visible one)
            const $baseRow = $rows.first();
            const baseQty = parseFraction($baseRow.find('.modalQtyInput').val());
            const baseUnit = $baseRow.find('.modalMeasurementInput').val().trim().toLowerCase();

            if (!baseQty || !baseUnit) {
                console.warn('Base quantity or unit is missing.');
                return;
            }

            function updateNutrition(currentQtyRaw, currentUnit) {
                const currentQty = parseFraction(currentQtyRaw);
                const baseEquivalent = unitEquivalents[currentUnit];

                if (!baseEquivalent) {
                    console.warn('Unknown unit used in conversion:', currentUnit);
                    return;
                }

                const ratio = currentQty / baseEquivalent;
                const newCarbs = baseCarbs * ratio;
                const newProtein = baseProtein * ratio;
                const newFat = baseFat * ratio;
                const newEnergy = baseEnergy * ratio;

                $('#modalCarbs').text(newCarbs.toFixed(2) + 'g');
                $('#modalProtein').text(newProtein.toFixed(2) + 'g');
                $('#modalFat').text(newFat.toFixed(2) + 'g');
                $('#modalEnergy').text(newEnergy.toFixed(2) + 'kJ');
            }

            // Listen to input changes for this specific container
            $rows.find('.modalQtyInput, .modalMeasurementInput').on('input', function () {
                const $row = $(this).closest('.qty-unit-row');
                const currentQty = $row.find('.modalQtyInput').val();
                const currentUnit = $row.find('.modalMeasurementInput').val();
                updateNutrition(currentQty, currentUnit);
            });
        }

        function setupDynamicMeasurementSync($container) {
            const $rows = $container.find('.qty-unit-row');
            if ($rows.length < 2) return;

            let unitMap = {};

            // Build unit map for this specific container
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

            // Calculate ratios for this specific food item
            let ratios = {};
            for (const [unit, qty] of Object.entries(unitMap)) {
                ratios[unit] = qty / baseQty;
            }

            let isManuallyEditing = false;

            $rows.each(function () {
                const $qtyInput = $(this).find('.modalQtyInput');
                const $unitInput = $(this).find('.modalMeasurementInput');

                $qtyInput.off('focus blur input').on({
                    focus: function () {
                        isManuallyEditing = true;
                    },
                    blur: function () {
                        isManuallyEditing = false;
                    },
                    input: function () {
                        if (!isManuallyEditing) return;

                        const changedQty = parseFraction($(this).val());
                        const changedUnit = $unitInput.val().toLowerCase().trim();

                        if (isNaN(changedQty) || !ratios[changedUnit]) return;

                        const updatedBaseQty = changedQty / ratios[changedUnit];

                        $rows.each(function () {
                            const $otherQtyInput = $(this).find('.modalQtyInput');
                            const $otherUnitInput = $(this).find('.modalMeasurementInput');
                            const otherUnit = $otherUnitInput.val().toLowerCase().trim();

                            if (otherUnit !== changedUnit && ratios[otherUnit]) {
                                if (!$otherQtyInput.is(':focus')) {
                                    const newQty = updatedBaseQty * ratios[otherUnit];
                                    $otherQtyInput.val(newQty.toFixed(1));
                                }
                            }
                        });
                    }
                });
            });
        }

        $(document).on('click', '#save-edit-food', function () {
            if (!$editingRow) return;

            const $modal = $('#editFoodModal');
            const updatedProtein = $('#modalProtein').text().replace('g', '').trim();
            const updatedCarbs = $('#modalCarbs').text().replace('g', '').trim();
            const updatedFat = $('#modalFat').text().replace('g', '').trim();
            const updatedEnergy = $('#modalEnergy').text().replace('kJ', '').trim();

            const updatedQtyUnits = [];
            const displayQtyParts = [];

            $('#dynamicQtyMeasurementContainer .qty-unit-row').each(function () {
                const $checkbox = $(this).find('.modalQtyCheckbox');
                if ($checkbox.is(':checked')) {
                    const qty = $(this).find('.modalQtyInput').val();
                    const unit = $(this).find('.modalMeasurementInput').val().trim();

                    if (qty && unit) {
                        updatedQtyUnits.push({ qty, unit, checked: true });

                        const formattedDisplay = (unit === 'g' || unit === 'ml' || unit === 'mL')
                            ? `${qty}${unit}`
                            : `${qty} ${unit}`;
                        displayQtyParts.push(formattedDisplay);
                    }
                } else {
                    const qty = $(this).find('.modalQtyInput').val();
                    const unit = $(this).find('.modalMeasurementInput').val().trim();
                    if (qty && unit) {
                        updatedQtyUnits.push({ qty, unit, checked: false });
                    }
                }
            });

            // Update nutrition info for the specific row
            const nutritionText = `Energy: ${updatedEnergy}kJ, Protein: ${updatedProtein}g, Carb: ${updatedCarbs}g, Fat: ${updatedFat}g`;
            $editingRow.find('.nutrition-info').text(nutritionText);

            $editingRow.find('.hidden-protein').val(updatedProtein);
            $editingRow.find('.hidden-carbs').val(updatedCarbs);
            $editingRow.find('.hidden-fat').val(updatedFat);
            $editingRow.find('.hidden-energy').val(updatedEnergy);

            if (displayQtyParts.length > 0) {
                $editingRow.find('.hidden-serving-size').val(updatedQtyUnits[0].qty);
                $editingRow.find('.hidden-serving-size-unit').val(updatedQtyUnits[0].unit);
            }

            // Save JSON with checked states
            $editingRow.find('.hidden-selected-qty-unit').val(JSON.stringify(updatedQtyUnits));

            // Update display title
            const foodTitle = $editingRow.find('.food-select option:selected').text();
            const displayQty = displayQtyParts.join(' or ');
            $editingRow.find('.food-title').html(`<strong>${foodTitle} ${displayQty}</strong>`);

            // Update the edit button's data attributes with the new nutrition values
            const $editButton = $editingRow.find('.edit-food');
            $editButton.data('carbs', updatedCarbs);
            $editButton.data('protein', updatedProtein);
            $editButton.data('fat', updatedFat);
            $editButton.data('energy', updatedEnergy);
            $editButton.data('serving-size', updatedQtyUnits[0]?.qty || '');
            $editButton.data('serving-size-unit', updatedQtyUnits[0]?.unit || '');

            $modal.modal('hide');
            $editingRow = null; // Clear the reference

            // Recalculate total nutrition after save
            updateTotalNutrition();
        });

        function updateImageButtons() {
            const hasExistingImage = $('#existing-meal-image').length > 0;
            const hasGeneratedImage = $('#meal-image-preview').attr('src') !== undefined;

            if (hasExistingImage || hasGeneratedImage) {
                $('#generate-ai-image').hide();
                $('#edit-ai-image').show();
            } else {
                $('#generate-ai-image').show();
                $('#edit-ai-image').hide();
            }
        }

        // Initial button state
        updateImageButtons();

        $('#generate-ai-image').on('click', function () {
            let title = $('input[name="title"]').val();
            let description = $('textarea[name="description"]').val();
            let prompt = $('input[name="ai-image-prompt"]').val();

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
                    title: title,
                    description: description,
                    prompt: prompt
                },
                success: function (response) {
                    if (response.image_url) {
                        $('#meal-image-preview').attr('src', response.image_url).show();
                        $('#generated_image').val(response.image_url);
                        $('#image-preview-container').show();
                        updateImageButtons();
                    } else {
                        alert('Failed to generate image. Please try again.');
                    }
                    $('#loader').hide();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Something went wrong!');
                    $('#loader').hide();
                }
            });
        });

        // Handle image editing
        $('#edit-ai-image').on('click', function () {
            let title = $('input[name="title"]').val();
            let description = $('textarea[name="description"]').val();
            let prompt = $('input[name="ai-image-prompt"]').val();
            let existingImageUrl = $('#meal-image-preview').attr('src') || $('#existing-meal-image').attr('src');

            if (!title) {
                alert('Please enter a meal title first!');
                return;
            }

            if (!existingImageUrl) {
                alert('No image to edit!');
                return;
            }

            $('#loader').show();

            $.ajax({
                url: "{{ route('admin.meals.edit-image') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    description: description,
                    prompt: prompt,
                    existing_image_url: existingImageUrl
                },
                success: function (response) {
                    if (response.image_url) {
                        $('#meal-image-preview').attr('src', response.image_url).show();
                        $('#generated_image').val(response.image_url);
                        $('#image-preview-container').show();
                        $('#existing-meal-image').hide();
                    } else {
                        alert('Failed to edit image. Please try again.');
                    }
                    $('#loader').hide();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Something went wrong!');
                    $('#loader').hide();
                }
            });
        });
    });

</script>
