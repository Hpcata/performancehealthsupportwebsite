@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold">{{ isset($category) ? 'Edit Category' : 'Create Category' }}</h3>
                <div class="col-auto">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($category)) @method('PUT') @endif

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $category->title ?? '' }}" required>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $category->description ?? '' }}</textarea>
                </div>

                <!-- <div class="mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" name="time" class="form-control" value="{{ $category->time ?? '' }}" required>
                    @error('time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> -->

                <div class="mb-3">
                    <label for="time" class="form-label">Order</label>
                    <input type="number" name="order" class="form-control" value="{{ $category->order ?? '' }}" min=0 required>
                    @error('time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <small class="form-text text-muted">
                        Only image files are allowed (.jpg, .jpeg, .png, .gif, .webp). Max size: 2MB.
                    </small>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if (isset($category) && $category->image)
                        <img src="{{ webAssets('storage/' . $category->image) }}" class="img-thumbnail mt-3" style="max-height: 150px;">
                    @endif
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
