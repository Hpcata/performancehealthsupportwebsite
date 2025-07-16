@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold">{{ isset($category) ? 'Edit Sport Category' : 'Create Sport Category' }}</h3>
                <div class="col-auto">
                    <a href="{{ route('admin.sports-categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.sports-categories.update', $category) : route('admin.sports-categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($category)) @method('PUT') @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name ?? '' }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection