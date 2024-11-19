@extends(adminView('layouts.auth'))

@section('title', 'Password Reset')

@section('content')
<div class="container-xxl">

	<div class="row g-0">
		<div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
			<div style="max-width: 25rem;">
				<div class="text-center mb-5">
					<img src="{!! adminAssets('dist/assets/images/logo1.svg') !!}" alt="login-img" width="500">
				</div>
				<!-- Image block -->
				<div class="">
					<img src="{!! adminAssets('dist/assets/images/login-img.svg') !!}" alt="login-img">
				</div>
			</div>
		</div>

		<div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
			<div class="w-100 p-3 p-md-5 card border-0 shadow-sm" style="max-width: 32rem;">
				<!-- Form -->
				<form action="{!! adminRoutePut('forgot-password-post') !!}" method="POST" class="row g-1 p-3 p-md-4">
					@csrf
					<div class="col-12 text-center mb-5">
						<img src="{!! adminAssets('dist/assets/images/forgot-password.svg') !!}" class="w240 mb-4" alt="" />
						<h1>Forgot password?</h1>
						<span>We will send a link to your email, use that link to reset password.</span>
					</div>
					@include(adminView('includes.alert'))
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label">Email address</label>
							<input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com">
						</div>
					</div>
					<div class="col-12 text-center mt-4">
						<button type="submit" class="btn btn-lg btn-block btn-light lift text-uppercase">SUBMIT</button>
					</div>
					<div class="col-12 text-center mt-4">
						<span class="text-muted"><a href="{!! adminRoutePut('login') !!}" class="text-secondary">Back to Sign in</a></span>
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