@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($category) ? 'Edit Category' : 'Create Category' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a type="button" href="{{ route('admin.categories.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($category)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Mealtime Selection (Multiple Select) -->
                            <div class="col-md-12">
                                <label for="mealtime_ids" class="form-label">Select Mealtimes</label>
                                <select name="mealtime_ids[]" class="form-select select2" multiple required>
                                    @foreach ($mealtimes as $mealtime)
                                        <option value="{{ $mealtime->id }}" 
                                                {{ isset($category) && $category->mealtimes->contains($mealtime->id) ? 'selected' : '' }}>
                                            {{ $mealtime->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $category->title ?? '' }}" required>
                            </div>

                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $category->description ?? '' }}</textarea>
                            </div>

                            <!-- Image Upload Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                
                                <!-- Show current image if editing -->
                                @if (isset($category) && $category->image)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">{{ isset($category) ? 'Update' : 'Create' }}</button>
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
