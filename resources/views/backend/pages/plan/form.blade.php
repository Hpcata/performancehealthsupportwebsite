@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($plan) ? 'Edit Plan' : 'Create Plan' }}</h3>
              
                    <a href="{{ route('admin.plans.index') }}" class="btn btn-primary btn-set-task">Back</a>
             
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($plan) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($plan)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Name -->
                            <div class="col-md-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $plan->name ?? '' }}" required>
                            </div>

                            <!-- Subtitle -->
                            <div class="col-md-12">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" id="subtitle" name="subtitle" class="form-control" value="{{ $plan->subtitle ?? '' }}">
                            </div>

                            <!-- Price -->
                            <div class="col-md-12">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" id="price" name="price" class="form-control" value="{{ $plan->price ?? '' }}" required>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <div id="editor" class="form-control" style="min-height: 200px;">{{ $plan->description ?? '' }}</div> <!-- CKEditor will use this div -->
                                <input type="hidden" name="description" id="description" value="{{ $plan->description ?? '' }}"/>

                                <!-- <textarea id="description" name="description" class="form-control">{{ $plan->description ?? '' }}</textarea> -->
                            </div>

                            <!-- Meal Times -->
                            <div class="col-md-12">
                                <label for="meal_times" class="form-label">Meal Timesss</label>
                                <select id="meal_times" name="meal_times[]" class="form-select select2 form-control-multiple" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ isset($plan) && $plan->categories->contains($category->id) ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub-Plans -->
                            <div class="col-md-12">
                                <label for="sub_plan_ids" class="form-label">Sub Plans(Child Plans)</label>
                                <select id="sub_plan_ids" name="sub_plan_ids[]" class="form-select select2 form-control-multiple" multiple>
                                    @foreach ($subPlans as $subPlan)
                                        <option value="{{ $subPlan->id }}" 
                                            {{ isset($plan) && $plan->subPlans->contains($subPlan->id) ? 'selected' : '' }}>
                                            {{ $subPlan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Image -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" id="image" name="image" class="form-control">
                                @include('backend.layouts.error', ['field' => 'image'])

                                @if (isset($plan) && $plan->image)
                                    <div class="mt-2">
                                        <img src="{{ webAssets('storage/' . $plan->image) }}" alt="Plan Image" width="100">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">{{ isset($plan) ? 'Update' : 'Create' }}</button>
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
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>

@endpush
@push('custom_scripts')
<script>
$(document).ready(function() {
    ClassicEditor
        .create(document.querySelector('#editor'), {
            htmlEncodeOutput: false,    // Prevents encoding of HTML entities
            entities: false,            // Disables entity encoding
            basicEntities: false,        // Ensures basic entities like `<`, `>` are not encoded

        })
        .then(editor => {
        // When the form is submitted, transfer the content of CKEditor to the hidden field
            $('form').on('submit', function() {
                $('#description').val(editor.getData().trim());  // Save clean HTML
            });
        })
        .catch(error => {
            console.error(error);
        });

    $('#meal_times').select2({
        placeholder: "Select meal times",
        allowClear: true
    });
    $('#sub_plan_ids').select2({
        placeholder: "Select meal times",
        allowClear: true
    });
});
</script>
@endpush