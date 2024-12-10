@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($subcategory) ? 'Edit Subcategory' : 'Create Subcategory' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a type="button" href="{{ route('admin.subcategories.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($subcategory) ? route('admin.subcategories.update', $subcategory) : route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($subcategory)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Category Selection -->
                            <div class="col-md-12">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ isset($subcategory) && $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $subcategory->title ?? '' }}" required>
                            </div>

                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $subcategory->description ?? '' }}</textarea>
                            </div>

                            <!-- Calories Field -->
                            <!-- <div class="col-md-12">
                                <label for="calories" class="form-label">Calories</label>
                                <input type="number" name="calories" class="form-control" value="{{ $item->calories ?? '' }}" placeholder="e.g., 500">
                            </div> -->

                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($subcategory) && $subcategory->image)
                                <img src="{{ asset('storage/' . $subcategory->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($subcategory) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
