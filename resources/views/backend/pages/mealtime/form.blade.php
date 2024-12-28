@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <h3 class="fw-bold">{{ isset($mealTime) ? 'Edit Meal Time' : 'Create Meal Time' }}</h3>
        <div class="col-auto">
            <a href="{{ route('admin.meal-times.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ isset($mealTime) ? route('admin.meal-times.update', $mealTime) : route('admin.meal-times.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($mealTime)) @method('PUT') @endif

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $mealTime->title ?? '' }}" required>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $mealTime->description ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" name="time" class="form-control" value="{{ $mealTime->time ?? '' }}" required>
                    @error('time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                    @if (isset($mealTime) && $mealTime->image)
                        <img src="{{ asset('private/public/storage/' . $mealTime->image) }}" class="img-thumbnail mt-3" style="max-height: 150px;">
                    @endif
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($mealTime) ? 'Update' : 'Create' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
