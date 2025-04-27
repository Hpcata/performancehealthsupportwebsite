@extends(frontView('layouts.app'))

@section('content')
<style>
    /* Style error messages in red color */
    label.error {
        color: red; 
        font-size: 14px; 
        margin-top: 5px;
        display: block;
    }

    /* Highlight input fields with errors */
    input.error {
        border-color: red;
    }
</style>
<div class="section nutrition-plan-hero bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="use-login-after-img" id="new-password-popup" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
                aria-modal="true" role="dialog" style="display: block">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body new-password-modal-body pt-0">
                            <div class="h-100">
                                <div class="all-login-input">
                                    <div class="bg-white px-sm-5 px-3" style="padding-top: 1px; padding-bottom: 1px;">
                                        <div class="text-center mb-3 p-3" style="background: #3B3B3B;">
                                            <img src="{{ frontAssets('images/logo.svg') }}" alt="logo" width="300">
                                        </div>
                                        <div class="text-center">
                                            <h2 class="mb-4">Reset Password</h2>
                                        </div>
                                        <form id="new-password-form">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <div class="form-group mb-3">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" class="form-control bg-transparent border"
                                                    value="{{ $email ?? old('email') }}" id="email" autocomplete="email" autofocus>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="new-password">Password</label>
                                                <input type="password" class="form-control bg-transparent border"
                                                    id="new-password" name="password" autocomplete="new-password">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <input type="password" class="form-control bg-transparent border"
                                                    id="password_confirmation" name="password_confirmation"
                                                    autocomplete="new-password">
                                            </div>
                                            <div class="d-flex gap-2 text-center">
                                                <button type="submit"
                                                    class="btn btn-primary w-100 fw-semibold mx-auto px-sm-5 px-4"
                                                    id="submit-new-password-all-content">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0" style="visibility: hidden;">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ensure jQuery and jQuery Validate are loaded -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {

        $("#new-password-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#new-password",
                },
            },
            messages: {
                email: {
                    required: "The email field is required.",
                    email: "Please enter a valid email address.",
                },
                password: {
                    required: "The password field is required.",
                    minlength: "The password must be at least 8 characters long.",
                },
                password_confirmation: {
                    required: "Please confirm your password.",
                    equalTo: "Passwords do not match.",
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            highlight: function(element) {
                $(element).addClass('error');
            },
            unhighlight: function(element) {
                $(element).removeClass('error');
            },
            submitHandler: function(form, event) {
                console.log("Form is valid. Submitting now.");
                event.preventDefault();  // Prevent default form submission

                let formData = $(form).serialize();  // Serialize the form data

                $.ajax({
                    url: "{{ route('front.password.update', ['token' => $token]) }}",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        console.log("Form submitted successfully:", response);

                        setTimeout(function() {
                            sessionStorage.setItem('reset_success', 'true');
                            window.location.href = "{{ route('front.sub-home-page') }}";
                        }, 3000);
                    },
                    error: function(xhr) {
                        console.error("Error during form submission:", xhr.responseText);
                        alert("An error occurred. Please try again.");
                        let response = xhr.responseJSON;
                        let message = response?.message || response?.data?.message || 'An error occurred.';
                    },
                });
            },
        });
    });
</script>
@endsection
