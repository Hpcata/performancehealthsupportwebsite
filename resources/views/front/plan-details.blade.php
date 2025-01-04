@extends(frontView('layouts.app'))

@section('title', $plan->name)

@section('content')

    <div class="section nutrition-plan-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="nutrition-plan-text">
                        <h1>We Take Care About Your <span class="text-primary">Health</span></h1>
                        <p>Make sure your daily nutrition is sufficient. Consult your Nutrition Supplements Products about nutrition with us.</p>
                        <a href="#" class="btn btn-primary">
                            <span class="me-1">Get Started</span>
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
        
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 ms-lg-auto">
                    <div class="nutrition-plan-img-box">
                        <div class="go-bottom-link">
                            <figure class="top-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M50,45V0H3v0.1h2.1C29.9,0.1,50,20.2,50,45z" fill="#fafafa"/>
                                </svg>
                            </figure>
                            <a href="#" class="btn btn-primary">
                                <svg width="71" height="72" viewBox="0 0 71 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M50.8233 17.2911C49.0555 17.2911 47.7297 18.6169 47.7297 20.3847L47.7297 43.6603L22.2444 18.175C20.9186 17.1438 18.8562 17.1438 17.6777 18.3223C16.4992 19.5008 16.4992 21.5632 17.6777 22.7417L43.3103 48.3744L20.0347 48.3744C18.2669 48.3744 16.9411 49.7002 16.9411 51.4679C16.9411 53.2357 18.2669 54.5615 20.0347 54.5615H50.9706C51.2653 54.5615 51.7072 54.4142 52.1491 54.2669C52.4438 54.2669 52.7384 53.9723 53.033 53.6777C53.3276 53.383 53.6223 53.0884 53.7696 52.6465C53.9169 52.2045 54.0642 51.7626 54.0642 51.4679L54.0642 20.532C53.9169 18.9116 52.4438 17.4384 50.8233 17.2911Z" fill="white"/>
                                </svg>                                    
                            </a>
                            <figure class="bottom-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M50,45V0H3v0.1h2.1C29.9,0.1,50,20.2,50,45z" fill="#fafafa"/>
                                </svg>
                            </figure>
                        </div>
                        <div class="nutrition-plan-img">
                            <figure>
                                <img src="{!! frontAssets('images/nutrition-supplements.jpg') !!}"  alt="">
                            </figure>
                        </div>
                        <div class="nutrition-bottom-box">
                            <figure class="top-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M0,5v45h47v-0.1h-2.1C20.1,49.9,0,29.8,0,5z" fill="#fafafa"/>
                                </svg>
                            </figure>
                            <div class="nutrition-athlete-box">
                                <figure>
                                    @if(Auth::check() && Auth::user()->profile_image)
                                        <img src="{{ asset('private/public/' . Auth::user()->profile_image) }}" alt="Profile Image">
                                    @else
                                        <img src="{{ frontAssets('images/kerry-oBryan.jpg') }}" alt="Default Profile Image">
                                    @endif
                                </figure>

                                <div class="nutrition-athlete-info">
                                    @if(Auth::check())
                                        <h5>{{ Auth::user()->name }}</h5>
                                        <p>National Athlete</p>
                                        <a  class="btn btn-primary edit-profile py-1 mt-1" href="{{ route('front.competition-plan-details', ['id' => Auth::user()->id]) }}" data-profile-id="{{ Auth::user()->id }}">View Profile</a>
                                    @else
                                        <h5>Ellie Shiloh</h5>
                                        <p>National Athlete</p>
                                    @endif
                                </div>
                            </div>

                            <figure class="bottom-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M0,5v45h47v-0.1h-2.1C20.1,49.9,0,29.8,0,5z" fill="#fafafa"/>
                                </svg>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="plan-buttons-link">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center">
                    <a href="#">Tracker</a>
                    <a href="#">Plan</a>
                    <a href="#">Treatment</a>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
    @foreach($userPlans as $userPlan)
        <div class="container mb-5">
            <div class="d-md-flex align-items-center justify-content-between">
                <h2>{{ $userPlan->plan->name }}
                @if(isset($userPlan->plan->subPlans) && $userPlan->plan->subPlans->count() > 0)
                    ( 
                    {{ $userPlan->plan->subPlans->pluck('name')->join(' + ') }} 
                    ({{ $userPlan->plan->subPlans->count() }} plans)
                    )
                @endif
                </h2>
                <a href="#" class="mt-3 mt-md-0 btn btn-primary">Finalise Plan</a>
            </div>
            <div class="mt-4">
                <div class="row g-4">
                @if($userPlan->userMealTimes->count())
                    @foreach($userPlan->userMealTimes as $plan)
                    <?php //dd($plan); ?>
                    <div class="col-md-4">
                        <div class="nutrition-plan-box">
                            <figure>
                                @if($plan->mealTime->image)
                                    <img src="{{ asset('private/public/storage/' . $plan->mealTime->image) }}" alt="{{ $plan->mealTime->title }}">
                                @endif
                            </figure>
                            <h5>{{ $plan->mealTime->title }} </h5>
                            <p></p>
                            <a href="{{ route('front.meal-time.details', ['id' => $plan->mealTime->id, 'plan_id' => $userPlan->id]) }}" class="btn btn-primary view-details-btn" data-category-id="{{ $plan->mealTime->id }}" 
                                data-category-name="{{ $plan->mealTime->title }}">View Details</a>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" id="id" name="user_id" >

                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                    </div>
                    <div class="mb-3">
                        <label for="profileImage" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profileImage" name="profile_image">
                    </div>
                    <div class="mb-3 text-center">
                        <img id="profileImagePreview" src="" alt="Profile Image" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateProfileBtn">Update Profile</button>
            </div>
        </div>
    </div>
</div>

<script>

    const baseUrl = "{{ asset('private/public/storage') }}";

    $(document).ready(function () {
        // Open modal and populate user data
        $('.edit-profile').on('click', function () {
            const profileId = $(this).data('profile-id');

            $.ajax({
                url: '{{ route('front.profile', ':id') }}'.replace(':id', profileId),
                method: 'GET',
                success: function (data) {
                    $('#id').val(data.id);
                    $('#firstName').val(data.first_name);
                    $('#lastName').val(data.last_name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    // $('#email').val(data.email);
                    // Set the profile image
                    if (data.profile_image) {
                        $('#profileImagePreview').attr('src', data.profile_image);
                    }
                    $('#profileModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load profile details. Please try again later.');
                }
            });
        });

        // Handle profile update form submission
        $('#updateProfileBtn').on('click', function () {
            let formData = new FormData($('#profileForm')[0]); // Get form data, including files
            formData.append('_token', '{{ csrf_token() }}'); 
            $.ajax({
                url: '{{ route("front.profile.update") }}', // Endpoint to update user profile
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.success) {
                        alert('Profile updated successfully!');
                        $('#profileModal').modal('hide');
                        location.reload(); // Optionally reload the page
                    } else {
                        alert('Failed to update profile: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error updating profile:', error);
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        alert('Validation errors: ' + Object.values(errors).join(', '));
                    } else {
                        alert('An error occurred while updating the profile. Please try again later.');
                    }

                }
            });
        });
    });

</script>
@endsection