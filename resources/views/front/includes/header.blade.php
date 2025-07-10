<?php
$setting = \App\Models\SiteSettings::where('page_id', 'general')->where('meta_key', 'header_headermenu')->first();
$headerData = json_decode($setting['meta_value'], true);
$auth = auth()->guard('web')->check();
?>

@if (Route::is('front.profile') || Route::is('front.plans.details'))
<header class="mobile-header">
    <img src="images/logo (1) 1.svg" alt="2LS Logo" class="mobile-logo-img" width="120" height="40" />
    <button class="mobile-menu-open" aria-label="Open mobile menu" onclick="toggleMobileMenu()">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
</header>
<header class="header">
    <div class="header-content">
        <div class="logo">
            <img src="{{ frontAssets('images/logo (1) 1.svg') }}" alt="2LS Logo" class="logo-img" width="120" height="40" />
        </div>
        <nav class="nav-center">
            <span class="nav-item">My Plans</span>
            <span class="nav-item">Challenges and Rewards</span>
            <div class="nav-item dropdown">
                <span>Resources <i class="fas fa-chevron-down"></i></span>
                <div class="dropdown-content">
                    <a href="/articles">Articles</a>
                    <a href="/videos">Videos</a>
                    <a href="/tools">Tools</a>
                </div>
            </div>
        </nav>
        <div class="nav-right">
            <div class="nav-item dropdown">
                <span>My Account <i class="fas fa-chevron-down"></i></span>
                <div class="dropdown-content">
                    <a href="/billing">Billing</a>
                    <a href="/subscription">Subscription</a>
                    <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item text-danger p-2" href="#" onclick="handleLogout(event)">
                        Logout
                    </a>
                </div>
            </div>
            <span class="nav-item">Main website</span>
        </div>
    </div>
</header>
@else
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
                        @elseif($title == 'Contact')
                        <li class="nav-item">
                            <a class="nav-link restriction-page " id="contact-us" href="{{ route('front.index') }}#contact"> Contact</a>
                        </li>
                        @else
                        @if(Auth::check() && Auth::user()->is_superadmin == 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user"></i>
                                My Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item text-dark p-2" href="{{ route('front.profile', ['id' => Auth::user()->id]) }}">My Profile</a>
                                </li>
                                <!-- <li>
                                    <a class="dropdown-item text-dark p-2" href="">View My Plan</a>
                                </li> -->
                                <li>
                                    <!-- Logout form (hidden) -->
                                    <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item text-danger p-2" href="#" onclick="handleLogout(event)">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @elseif($title == 'Login')
                        <li class="nav-item">
                            <a class="nav-link restriction-page " id="login" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-solid fa-user"></i> Login</a>
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

@endif

<script>
    function handleLogout(event) {
        event.preventDefault();

        // Attempt logout via POST
        try {
            // Check for existing CSRF token
            const csrfToken = document.querySelector('input[name="_token"]').value;
            if (csrfToken) {
                document.getElementById('logout-form').submit();
            } else {
                // If no CSRF token found (likely session expired)
                window.location.href = "{{ route('front.logout.guest') }}";
            }
        } catch (e) {
            // Fallback in case of any error
            window.location.href = "{{ route('front.logout.guest') }}";
        }
    }

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
                        if (response.message == 'Plan not purchased.') {
                            alert('Please complete your profile.');
                        }
                        // If login is successful, redirect to the given URL
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;

                    // Show error messages for validation errors
                    if (response.message) {
                        if (response.message == 'CSRF token mismatch.') {
                            $('#login-error').text('Your session has expired. Please reload the page and login again.');
                        } else {
                            $('#login-error').text(response.message); // Display error message in #login-error div
                        }
                    } else {
                        $('#login-error').text('An error occurred. Please try again.'); // General error message
                    }

                    $('#login-submit').prop('disabled', false); // Re-enable submit button
                }
            });
        });

        $('#forgot-password').on('click', function(e) {
            e.preventDefault();
            $('#loginModal').modal('hide');
            $('#forgotPasswordModal').modal('show'); // Show the modal
        });

        $('#forgotPasswordForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('front.password.request') }}",
                method: 'POST',
                data: {
                    email: $('#email').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Password reset link has been sent to your email.');
                    $('#forgotPasswordModal').modal('hide');
                },
                error: function(xhr) {
                    alert('Failed to send reset link. Please check your email address.');
                }
            });
        });
    });
</script>