@extends(backendView('layouts.app'))

@section('title', 'Change Password')

@push('custom_styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .password-wrapper {
            position: relative;
        }
        .password-wrapper input {
            padding-right: 2.5rem;
        }
        .password-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    @include(backendView('includes.alert'))
    <div class="container-xxl">
        <div class="row align-items-center">
            <div class="border-0 mb-4">
                <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                    <h3 class="fw-bold mb-0">Change Password</h3>
                    <div class="col-auto d-flex w-sm-100">
                        <a type="button" href="{{ route('dashboard') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-item-center">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                        <h6 class="mb-0 fw-bold ">Basic Inputs</h6>
                    </div>
                    <div class="card-body">
                        <form id="admin-change-password-form" method="POST" action="{{ route('change-password') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" name="current_password" id="current_password">
                                        <i class="fas fa-eye toggle-password" data-toggle="#current_password"></i>
                                    </div>
                                    @include('backend.layouts.error', ['field' => 'current_password'])
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" name="new_password" id="new_password">
                                        <i class="fas fa-eye toggle-password" data-toggle="#new_password"></i>
                                    </div>
                                    @include('backend.layouts.error', ['field' => 'new_password'])
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation">
                                        <i class="fas fa-eye toggle-password" data-toggle="#new_password_confirmation"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush

@push('custom_scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                const input = document.querySelector(this.getAttribute('data-toggle'));
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    </script>
@endpush