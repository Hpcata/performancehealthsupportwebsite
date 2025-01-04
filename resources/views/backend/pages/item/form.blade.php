@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($item) ? 'Edit Item' : 'Create Item' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a href="{{ route('admin.items.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($item) ? route('admin.items.update', $item) : route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($item)) 
                            @method('PUT') 
                        @endif

                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
                            </div>

                            <!-- Short Description Field -->
                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2">{{ $item->short_description ?? '' }}</textarea>
                            </div>

                            <!-- Full Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Full Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
                            </div>

                            <!-- Quantity Field -->
                            <div class="col-md-12">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="text" name="qty" class="form-control" value="{{ $item->qty ?? ''}}" placeholder="Enter quantity and unit (e.g., 200 ml, 1 cup, 100 g)">
                            </div>
                            <!-- <small class="text-muted">Please include both quantity and unit (e.g., 200 ml, 1 cup, 100 g).</small> -->

                            <!-- Protein Field -->
                            <div class="col-md-12">
                                <label for="carbs" class="form-label">Protein</label>
                                <input type="number" name="protein" class="form-control" value="{{ $item->protein ?? '' }}" step="0.01" min="0" placeholder="Enter Protein"><small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>

                            </div>

                            <!-- Carbohydrate Field -->
                            <div class="col-md-12">
                                <label for="carbs" class="form-label">Carbohydrate</label>
                                <input type="number" name="carbs" class="form-control" value="{{ $item->carbs ?? '' }}" step="0.01" min="0" placeholder="Enter Carbohydrate"><small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>

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

                            <?php //dd($item->itemSwaps); ?>
                            <!-- Swap Items Selection (Visible only if 'Is Swapped' is Yes) -->
                            <div class="col-md-12" id="swapItemsContainer" style="display: none;">
                                <label for="swap_item_ids" class="form-label">Swap Items</label>
                                <select name="swap_item_ids[]" class="form-control select2" multiple>
                                    @foreach ($allItems as $swapItem)
                                        <option value="{{ $swapItem->id }}" 
                                            {{ isset($item) && $item->items->contains($swapItem->id) ? 'selected' : '' }}>
                                            {{ $swapItem->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($item) && $item->image)
                                    <img src="{{ asset('private/public/storage/' . $item->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                                @endif
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($item) ? 'Update' : 'Create' }}</button>
                    </form>

                </div>
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
            $('.select2').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
@endsection
