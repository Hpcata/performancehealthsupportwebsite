@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold">{{ isset($game) ? 'Edit Sport Game' : 'Create Sport Game' }}</h3>
                <div class="col-auto">
                    <a href="{{ route('admin.sport-games.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ isset($game) ? route('admin.sport-games.update', $game->id) : route('admin.sport-games.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($game)) @method('PUT') @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Sport Game</label>
                    <input type="text" name="name" class="form-control" value="{{ $game->name ?? '' }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="sport_category_id" class="form-label">Sport Category</label>
                    <select class="form-control select2" name="sport_category_id" id="sport_category_id" required>
                        <option value="">Select Sport Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('sport_category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <small class="form-text text-muted">
                        Only image files are allowed (.jpg, .jpeg, .png, .gif, .webp). Max size: 2MB.
                    </small>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if (isset($game) && $game->image_path)
                        <img src="{{ asset('storage/' . $game->image_path) }}" class="img-thumbnail mt-3" style="max-height: 150px;">
                    @endif
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($game) ? 'Update' : 'Create' }}</button>

            </form>
        </div>
    </div>
</div>
@endsection