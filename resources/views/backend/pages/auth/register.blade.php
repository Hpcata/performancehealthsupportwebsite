@extends(backendView('layouts.auth'))

@section('title', 'Signup')

@section('content')
<div class="container-xxl">

	<div class="row g-0">
		<div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
			<div style="max-width: 25rem;">
				<div class="text-center mb-5">
					<i class="bi bi-bag-check-fill  text-primary" style="font-size: 90px;"></i>
				</div>
				<div class="mb-5">
					<h2 class="color-900 text-center">Performance Health</h2>
				</div>
				<!-- Image block -->
				<div class="">
					<img src="{!! backendAssets('dist/assets/images/login-img.svg') !!}" alt="login-img">
				</div>
			</div>
		</div>

		<div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
			<div class="w-100 p-3 p-md-5 card border-0 shadow-sm" style="max-width: 32rem;">
				<!-- Form -->
				@include(adminView('includes.alert'))
				<form class="row g-1" action="{!! backendRoutePut('register-post') !!}" method='POST'>
					@csrf
					<div class="col-12 text-center mb-5">
						<h1>Sign Up</h1>
					</div>
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label">First Name</label>
							<input type="text" name="first_name" class="form-control form-control-lg" placeholder="Your name">
							@include('admin.layouts.error', ['field' => 'first_name'])
						</div>
					</div>
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label">Last Name</label>
							<input type="text" name="last_name" class="form-control form-control-lg" placeholder="Your name">
							@include('admin.layouts.error', ['field' => 'last_name'])
						</div>
					</div>
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label">Email address</label>
							<input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com">
							@include('admin.layouts.error', ['field' => 'email'])
						</div>
					</div>
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label">Password</label>
							<input type="password" name="password" class="form-control form-control-lg" placeholder="***************">
							@include('admin.layouts.error', ['field' => 'password'])
						</div>
					</div>
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label">Confirm Password</label>
							<input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="***************">
							@include('admin.layouts.error', ['field' => 'password_confirmation'])
						</div>
					</div>
					<div class="col-12 text-center mt-4">
						<button type="submit" class="btn btn-lg btn-block btn-light lift text-uppercase">SIGN UP</button>
					</div>
					<div class="col-12 text-center mt-4">
						<a class="text-secondary" href="{!! backendRoutePut('login') !!}">Back to login?</a>
					</div>
				</form>
				<!-- End Form -->

			</div>
		</div>
	</div> <!-- End Row -->

</div>
@endsection

@push('styles')
@endpush

@push('custom_styles')
@endpush

@push('scripts')
@endpush

@push('custom_scripts')
@endpush

@push('modals')
@endpush