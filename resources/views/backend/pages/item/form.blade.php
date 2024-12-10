@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($item) ? 'Edit Item' : 'Create Item' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a type="button" href="{{ route('admin.items.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
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
                            <!-- Subcategory Selection (Multiple Select) -->
                            <div class="col-md-12">
                                <label for="subcategory_ids" class="form-label">Subcategories</label>
                                <select name="subcategory_ids[]" class="form-control" multiple required>
                                    @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" 
                                        {{ isset($item) && $item->subcategories->contains($subcategory->id) ? 'selected' : '' }}>
                                        {{ $subcategory->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
                            </div>

                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
                            </div>

                            <!-- Price Field -->
                            <div class="col-md-12">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" value="{{ $item->price ?? '' }}" placeholder="e.g., 100.00" required>
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
@endsection
