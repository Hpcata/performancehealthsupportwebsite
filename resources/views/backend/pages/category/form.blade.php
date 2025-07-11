@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($subCategory) ? 'Edit Sub Category' : 'Create Sub Category' }}</h3>
               
                    <a type="button" href="{{ route('admin.subcategories.index') }}" class="btn btn-primary btn-set-task">Back</a>
              
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form  action="{{ isset($subCategory) && $subCategory->id ? route('admin.subcategories.update', $subCategory->id) : route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            @if(isset($subCategory) && $subCategory->id)
                                 @method('PUT') 
                            @endif

                        <div class="row g-3 align-items-center">
                            <!-- Mealtime Selection (Multiple Select) -->
                            <div class="col-md-12">
                                <label for="mealtime_ids" class="form-label">Select Categories</label>
                                <select name="mealtime_ids[]" class="form-select select2" id="mealtime_ids" multiple required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ isset($subCategory) && $subCategory->categories->contains($category->id) ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mealtime_ids')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $subCategory->title ?? '' }}" required>
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $subCategory->description ?? '' }}</textarea>
                            </div>

                            <!-- Image Upload Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <small class="form-text text-muted">
                                    Only image files are allowed (.jpg, .jpeg, .png, .gif, .webp). Max size: 2MB.
                                </small>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                
                                <!-- Show current image if editing -->
                                @if (isset($subCategory) && $subCategory->image)
                                <div class="mt-3">
                                    <img src="{{ webAssets('storage/' . $subCategory->image) }}" alt="Category Image" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">{{ isset($subCategory) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Select2 CSS and JS -->
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush
@push('custom_scripts')
    <script>
        $(document).ready(function () {
            $('#mealtime_ids').select2({
                placeholder: "Select categories",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
@endsection
