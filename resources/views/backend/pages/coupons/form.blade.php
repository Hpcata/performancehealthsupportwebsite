@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($coupon) ? 'Edit Coupon' : 'Create Coupon' }}</h3>
                
                    <a type="button" href="{{ route('admin.coupons.index') }}" class="btn btn-primary btn-set-task">Back</a>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($coupon)) @method('PUT') @endif
                        <div class="row g-3 align-items-center">

                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ $coupon->code ?? '' }}" required>
                                @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" value="{{ $coupon->description ?? '' }}">{{ $coupon->description ?? '' }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="mealtime_ids" class="form-label">Select Type</label>
                                <select name="type" class="form-select select2" id="type" required>
                                    <option value="percentage" @if(isset($coupon) && $coupon->type == "percentage") selected @endif>Percentage</option>
                                    <option value="fixed" @if(isset($coupon) && $coupon->type == "fixed") selected @endif>Fixed Amount</option>
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="title" class="form-label">Value</label>
                                <input type="number" name="value" class="form-control" value="{{ $coupon->value ?? '' }}" required>
                                @error('value')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" 
                                    name="start_date" 
                                    class="form-control datepicker-start" 
                                    placeholder="dd/mm/yyyy" 
                                    value="{{ old('start_date', isset($coupon->start_date) ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') : '') }}" 
                                    required>
                                @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" 
                                    name="end_date" 
                                    class="form-control datepicker-end" 
                                    placeholder="dd/mm/yyyy" 
                                    value="{{ old('end_date', isset($coupon->end_date) ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') : '') }}" 
                                    required>
                                @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12">
                                <label for="title" class="form-label">Max Uses</label>
                                <input type="number" name="max_uses" class="form-control" value="{{ $coupon->max_uses ?? '' }}">
                                @error('max_uses')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="meal_times" class="form-label">Select Plan</label>
                                <select name="plans[]" id="plans" class="form-control select2" multiple>
                                    @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" @if(isset($coupon) && $coupon->plans->contains($plan->id)) selected @endif>
                                        {{ $plan->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="mealtime_ids" class="form-label">Status</label>
                                <select name="status" class="form-select select2" id="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($coupon) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@push('custom_scripts')
<script>
$(document).ready(function() {
    $('#plans').select2({
        placeholder: "Select plans",
        allowClear: true
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Start Date Picker
        flatpickr('.datepicker-start', {
            dateFormat: "Y-m-d", // Display format for the date picker
            defaultDate: "{{ old('start_date', isset($coupon->start_date) ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') : '') }}",
            minDate: "{{ \Carbon\Carbon::today()->format('Y-m-d') }}", // Disable past dates
            onChange: function(selectedDates, dateStr, instance) {
                // Set minimum date for the End Date picker
                const endDatePicker = document.querySelector('.datepicker-end')._flatpickr;
                endDatePicker.set('minDate', dateStr);
            }
        });

        // End Date Picker
        flatpickr('.datepicker-end', {
            dateFormat: "Y-m-d", // Display format for the date picker
            defaultDate: "{{ old('end_date', isset($coupon->end_date) ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') : '') }}",
            minDate: "{{ \Carbon\Carbon::today()->format('Y-m-d') }}", // Disable past dates
        });
    });
</script>
@endpush