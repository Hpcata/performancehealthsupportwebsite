@extends('backend.layouts.app')

@section('content')
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />


<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($meal) ? 'Edit Meal' : 'Create Meal' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a type="button" href="{{ route('admin.meals.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($meal) ? route('admin.meals.update', $meal) : route('admin.meals.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($meal)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $meal->title ?? '' }}" required>
                            </div>
                            
                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $meal->description ?? '' }}</textarea>
                            </div>

                            <!-- Meal Times -->
                         {{--  <div class="col-md-12">
                                <label for="meal_times" class="form-label">Meal Times</label>
                                <select name="meal_times[]" id="meal_times" class="form-control select2" multiple>
                                    @foreach ($mealTimes as $mealTime)
                                    <option value="{{ $mealTime->id }}" 
                                        {{ isset($meal) && $meal->mealTimes->contains($mealTime->id) ? 'selected' : '' }}>
                                        {{ $mealTime->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            --}}
                            <!-- Sub Categories -->
                            <div class="col-md-12">
                                <label for="sub_categories" class="form-label">Categories</label>
                                <select name="categories[]" id="categories" class="form-control select2" multiple>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ isset($meal) && $meal->categories->contains($category->id) ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Food Selection Dropdown -->
                            <div class="col-md-12">
                                <label for="food_ids" class="form-label">Select Foods</label>
                                <select name="food_ids[]" id="food_ids" class="form-control select2" multiple required>
                                    @foreach ($foods as $food)
                                        <option value="{{ $food->id }}" 
                                            {{ isset($meal) && $meal->items->contains($food->id) ? 'selected' : '' }}>
                                            {{ $food->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($meal) && $meal->image)
                                <img src="{{ asset('private/public/storage/' . $meal->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($meal) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('custom_scripts')
<script>
$(document).ready(function() {
    $('#categories').select2({
        placeholder: "Select categories",
        allowClear: true
    });
    $('#food_ids').select2({
        placeholder: "Select foods",
        allowClear: true
    });
});
</script>
@endpush
