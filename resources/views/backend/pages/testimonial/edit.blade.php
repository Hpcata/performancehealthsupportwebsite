@extends(backendView('layouts.app'))

@section('title', 'Testimonial')

@section('content')
@include(backendView('includes.alert'))
<div class="container-xxl">
	<div class="row align-items-center">
		<div class="border-0 mb-4">
			<div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
				<h3 class="fw-bold mb-0">{{ $testimonial->id ? 'Edit Testimonial' : 'Add Testimonial' }}</h3>
				<div class="col-auto d-flex w-sm-100">
					<a type="button" href="{{ route('testimonials.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>
				</div>
			</div>
		</div>
	</div>

    <div class="row align-item-center">
		<div class="col-md-12">
			<div class="card mb-3">
				<!-- <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
					<h6 class="mb-0 fw-bold ">Basic Inputs</h6>
				</div> -->
				<div class="card-body">
					<form id="admin-testimonial-form" method="POST" action="{{ route('testimonials.save') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name='id' value="{{ old('id', $testimonial->id ?? '') }}">
						<div class="row g-3 align-items-center">
							<div class="col-md-6">
								<label for="name" class="form-label">Name</label>
								<input type="text" class="form-control" name="name" id="name" value="{{ old('name', $testimonial->name ?? '') }}">
                                @include('backend.layouts.error', ['field' => 'name'])
							</div>
                            <div class="col-md-6">
								<label for="designation" class="form-label">Title</label>
								<input type="text" class="form-control" name="designation" id="designation" value="{{ old('designation', $testimonial->designation ?? '') }}">
                                @include('backend.layouts.error', ['field' => 'designation'])
							</div>
							<div class="col-md-12">
								<label for="review" class="form-label">Testimonial</label>
								<textarea class="form-control" name="review" id="review" rows="8">{{ old('review', $testimonial->review ?? '') }}</textarea>
								@include('backend.layouts.error', ['field' => 'review'])
							</div>
							<div class="col-md-6">
								<div class="row mt-2 align-items-center">
									<div class="col-md-6">
										<label for="testimonial_image" class="form-label">Image</label>
										<input type="file" class="form-control" name="testimonial_image" id="testimonial-image" accept="image/*,image/webp">
										@include('backend.layouts.error', ['field' => 'testimonial_image'])
									</div>
									<div class="col-md-6 @if(!$testimonialImage) d-none @endif" id="preview-div">
										<label for="status" class="form-label">Preview</label>
										<img src="{{ $testimonialImage }}" alt="testimonial" class="form-control img-fluid @if(!$testimonialImage) d-none @endif" id="preview" width="200" style="width: 200px !important;">
									</div>
								</div>
							</div>
						</div>

						<button type="submit" class="btn btn-primary mt-4">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
	<script type="text/javascript" src="{{ config('constant.ENVIRONMENT') == 'production' ? url('public/vendor/jsvalidation/js/jsvalidation.js') : url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endpush

@push('custom_scripts')
	<script>
		$(document).on('change', '#testimonial-image', function (e) {
			file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$('#preview').attr("src", event.target.result);
					$('#preview').removeClass('d-none');
					$('#preview-div').removeClass('d-none');
				};
				reader.readAsDataURL(file);
			} else {
				$('#preview').addClass('d-none');
			}
		});
	</script>
@endpush