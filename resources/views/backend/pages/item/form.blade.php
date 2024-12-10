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
                        @if (isset($item)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label for="meal_ids" class="form-label">Meals</label>
                                <select name="meal_ids[]" class="form-control select2" multiple required>
                                    @foreach ($meals as $meal)
                                        <option value="{{ $meal->id }}" 
                                            {{ isset($item) && $item->meals->contains($meal->id) ? 'selected' : '' }}>
                                            {{ $meal->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Swap Items Selection -->
                            <div class="col-md-12">
                                <label for="swap_item_ids" class="form-label">Swap Items</label>
                                <select name="swap_item_ids[]" class="form-control select2" multiple>
                                    @foreach ($allItems as $swapItem)
                                        <option value="{{ $swapItem->id }}" 
                                            {{ isset($item) && $item->swapItems->contains($swapItem->id) ? 'selected' : '' }}>
                                            {{ $swapItem->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2">
                                    <a href="{{ route('admin.items.create') }}" class="btn btn-link">Create New Item</a>
                                </div>
                            </div>

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

                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($item) && $item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
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

<!-- Include Select2 CSS and JS -->
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('custom_scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
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
