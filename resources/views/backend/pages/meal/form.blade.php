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
                                <label for="food_selection" class="form-label">Select Foods</label>
                                <div id="food-container">
                                    @if(isset($meal) && $meal->items->count() > 0)
                                        @foreach ($meal->items as $item)
                                            <div class="food-row d-flex mb-2">
                                                <select name="food_ids[]" class="form-control select2 food-select" required>
                                                    <option value="">Select Food</option>
                                                    @foreach ($foods as $food)
                                                        <option value="{{ $food->id }}" 
                                                            {{ $food->id == $item->id ? 'selected' : '' }}>
                                                            {{ $food->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="food_qty[]" class="form-control ms-2 food-qty" 
                                                    placeholder="Qty" value="{{ $item->pivot->item_qty ?? '' }}" required>
                                                <button type="button" class="btn btn-danger ms-2 remove-food">X</button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="food-row d-flex mb-2">
                                            <select name="food_ids[]" class="form-control select2 food-select" required>
                                                <option value="">Select Food</option>
                                                @foreach ($foods as $food)
                                                    <option value="{{ $food->id }}">{{ $food->title }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="food_qty[]" class="form-control ms-2 food-qty" placeholder="Qty" required>
                                            <button type="button" class="btn btn-danger ms-2 remove-food">X</button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" id="add-food" class="btn btn-primary mt-2">Add More</button>
                            </div>
                            <!-- <div class="col-md-12">
                                <label for="food_ids" class="form-label">Select Foods</label>
                                <select name="food_ids[]" id="food_ids" class="form-control select2" multiple required>
                                    @foreach ($foods as $food)
                                        <option value="{{ $food->id }}" 
                                            {{ isset($meal) && $meal->items->contains($food->id) ? 'selected' : '' }}>
                                            {{ $food->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> -->
                            
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
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

@push('custom_scripts')
<script>
    $(document).ready(function() {
        $('#categories').select2({
            placeholder: "Select categories",
            allowClear: true
        });

        // Preload selected foods for Edit Mode
        @if (isset($meal))
            const preselectedFoods = @json($meal->items->pluck('id'));
            $('#food_ids').val(preselectedFoods).trigger('change');
        @endif
    });
    $(document).ready(function () {
        function initializeSelect2() {
            $('.food-select').select2({
                placeholder: "Search and select foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.items.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { query: params.term };
                    },
                    processResults: function(response) {
                        return {
                            results: response.items.map(function(item) {
                                return { id: item.id, text: item.title };
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        
        initializeSelect2();

        $(document).on('click', '#add-food', function () {
            let foodRow = `<div class="food-row d-flex mb-2">
                <select name="food_ids[]" class="form-control select2 food-select " required>
                    <option value="">Select Food</option>
                </select>
                <input type="text" name="food_qty[]" class="form-control ms-2 food-qty" placeholder="Qty" required>
                <button type="button" class="btn btn-danger ms-2 remove-food">X</button>
            </div>`;
            let newElement = $(foodRow).appendTo('#food-container');
            newElement.find('.food-select').select2({
                placeholder: "Search and select foods",
                minimumInputLength: 1,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.items.index") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { query: params.term };
                    },
                    processResults: function(response) {
                        return {
                            results: response.items.map(function(item) {
                                return { id: item.id, text: item.title };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).on('click', '.remove-food', function () {
            $(this).closest('.food-row').remove();
        });
    });
</script>
@endpush
