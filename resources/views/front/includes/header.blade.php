<?php
$setting = \App\Models\SiteSettings::where('page_id', 'general')->where('meta_key', 'header_headermenu')->first();
$headerData = json_decode($setting['meta_value'], true);
?>
<header id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="d-flex align-items-center w-100">
                <a class="navbar-brand" href="{{ route('front.index') }}">
                <img src="{{ frontAssets('images/logo.svg') }}" alt="">
                </a>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="ms-lg-auto header-navbar navbar-nav">
                @foreach ($headerData as $menu)
                    @php
                        $slug = '';
                        $link = $menu['link'] ?? '';
                        if(str_contains($link, '|')){
                            $explodelinks = explode('|', $link);
                            $link = $explodelinks[0] ?? '';
                            $slug = $explodelinks[1] ?? '';
                        }
                        $title = $menu['title'] ?? 'Untitled';
                    @endphp
                    @if($link != '' && $link != '#')
                        <li class="nav-item">
                            <a class="nav-link restriction-page" id="{{ strtolower($title) }}" href="{{ route($link, $slug ? ['page_slug' => $slug] : []) }}">{{ $title }}</a>
                        </li>
                    @else
                        @if(Auth::check() && Auth::user()->is_superadmin == 0) 
                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="nav-link restriction-page" id="logout" href="#" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                        @elseif($title == 'Login')
                        <li class="nav-item">
                            <a class="nav-link restriction-page login-btn" id="login" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-solid fa-user"></i></a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link restriction-page" id="{{ strtolower($title) }}" href="#{{ strtolower($title) }}">{{ $title }}</a>
                        </li>
                       @endif
                    @endif
                @endforeach
                </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div id="login-error" class="text-danger"></div> <!-- This will display the error message -->
                <!-- Sign In Form -->
                <form id="login-form">
                    <div class="mb-3">
                        <label for="login-email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="login-email" required>
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="login-password" required>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" id="login-submit" class="btn btn-primary w-100 mt-3">
                        Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="mt-3 text-center">
                    <!-- <small>Don't have an account? <a href="#" id="show-signup-modal">Sign Up</a></small> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#login').on('click', function() {
            $('#loginModal').modal('show');
        })
        $('#login-form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting the normal way

            // Disable the Submit Button to avoid multiple clicks
            $('#login-submit').prop('disabled', true);

            // Get the form data
            var email = $('#login-email').val();
            var password = $('#login-password').val();

            // Send the data to the backend for validation
            $.ajax({
                url: '{{ route("front.login") }}', // This is the route for handling login (update with your actual route if different)
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}' // CSRF token for protection
                },
                success: function(response) {
                    if (response.success) {
                        // If login is successful, redirect to the given URL
                        window.location.href = response.redirect_url;
                    }
                },error: function(xhr) {
                    var response = xhr.responseJSON;

                    // Show error messages for validation errors
                    if (response.message) {
                        $('#login-error').text(response.message); // Display error message in #login-error div
                    } else {
                        $('#login-error').text('An error occurred. Please try again.'); // General error message
                    }

                    $('#login-submit').prop('disabled', false); // Re-enable submit button
                }
            });
        });
    });
</script>
<!-- <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
        <span class="icofont-close js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div> -->