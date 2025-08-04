<?php
$setting = \App\Models\SiteSettings::where('page_id', 'general')->where('meta_key', 'header_headermenu')->first();
$headerData = json_decode($setting['meta_value'], true);
$auth = auth()->guard('web')->check();
?>

@if (Route::is('front.profile') || Route::is('front.plans.details'))
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay" onclick="toggleMobileMenu()"
        style=" position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); z-index:1999;">
    </div>
    <header class="mobile-header">
        <img src="{{ frontAssets('images/logo.svg') }}" alt="2LS Logo" class="mobile-logo-img" width="140"
            height="30" />
        <button class="mobile-menu-toggle" aria-label="Toggle mobile menu" onclick="toggleMobileMenu()"
            style="background: none; border: none; color: #fff; font-size: 2rem; cursor: pointer;margin: 0 !important;">
            <span id="mobile-menu-icon">
                <!-- This will be replaced by JS -->
                <i class="fas fa-bars" aria-hidden="true" id="hamburger-icon"></i>
                <!-- <img src="{{ frontAssets('images/hamburger.svg') }}" alt="" id="hamburger-icon" style="display:inline;"> -->
                <span id="close-icon" style="display:none;">&times;</span>
            </span>
        </button>
    </header>

    <!-- Mobile Menu Markup -->
    <div class="mobile-menu" id="mobile-menu" style="z-index:2000;">
        <ul class="mobile-menu-list">
            <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                    style="color: #fff; text-decoration: none; display: block; padding: 16px 16px;">My Plans</a></li>
            <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                    style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Challenges and
                    Rewards</a></li>
            @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
                <li class="mobile-menu-link"><a
                        href="{{ route('front.profile', ['id' => Auth::guard('web')->user()->id]) }}"
                        onclick="toggleMobileMenu()"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">My Profile</a>
                </li>
            @endif
            <li>
                <div class="mobile-menu-divider" style="height:1px; background:#555; margin: 12px 16px;"></div>
            </li>
            <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                    style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Resources and
                    Tools</a></li>
            <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                    style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Store</a></li>
            <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                    style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Main Website</a></li>
            <li>
                <div class="mobile-menu-divider" style="height:1px; background:#555; margin: 12px 16px;"></div>
            </li>
            @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
                <li class="mobile-menu-link">
                    <form id="logout-form-mobile" action="{{ route('front.logout') }}" method="POST"
                        style="display: none;">@csrf</form>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit(); toggleMobileMenu();"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Sign out</a>
                </li>
            @else
                <li class="mobile-menu-link"><a href="#" onclick="toggleMobileMenu()"
                        style="color: #fff; text-decoration: none; display: block; padding: 8px 16px;">Sign in</a></li>
            @endif
        </ul>
    </div>

    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ frontAssets('images/logo.svg') }}" alt="2LS Logo" class="logo-img" width="190"
                    height="40" />
            </div>
            <nav class="nav-center">
                <span class="nav-item">My Plans</span>
                <span class="nav-item">Challenges and Rewards</span>
                <div class="nav-item dropdown">
                    <span>Resources <i class="fas fa-chevron-down"></i></span>
                    <div class="dropdown-content">
                        <a href="#" id="scanner-btn">Supplement Scanner</a>
                        <a href="#">Level-Up Library</a>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank">BioHealth
                            Passport</a>
                    </div>
                </div>
            </nav>
            @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
                <div class="nav-right">
                    <div class="nav-item dropdown">
                        <div class="nav-end">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M9 9C10.1935 9 11.3381 8.52589 12.182 7.68198C13.0259 6.83807 13.5 5.69347 13.5 4.5C13.5 3.30653 13.0259 2.16193 12.182 1.31802C11.3381 0.474106 10.1935 0 9 0C7.80653 0 6.66193 0.474106 5.81802 1.31802C4.97411 2.16193 4.5 3.30653 4.5 4.5C4.5 5.69347 4.97411 6.83807 5.81802 7.68198C6.66193 8.52589 7.80653 9 9 9ZM7.39336 10.6875C3.93047 10.6875 1.125 13.493 1.125 16.9559C1.125 17.5324 1.59258 18 2.16914 18H15.8309C16.4074 18 16.875 17.5324 16.875 16.9559C16.875 13.493 14.0695 10.6875 10.6066 10.6875H7.39336Z"
                                    fill="white" />
                            </svg>
                            <span>My Account <i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="dropdown-content">
                            <a href="{{ route('front.profile-old', ['id' => Auth::guard('web')->user()->id]) }}">My
                                Profile</a>
                            <form id="logout-form" action="{{ route('front.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <a class="p-2 dropdown-item" style="padding:0.75rem 1rem !important;" href="#"
                                onclick="handleLogout(event)">
                                Sign Out
                            </a>
                        </div>
                    </div>
                    <span class="nav-item">Main website</span>
                </div>
            @else
                <div class="nav-right">
                    <!-- <span class="nav-item" id="login">Sign in</span> -->
                    <a href="{{ route('front.index') }}" class="nav-item" style="text-decoration: none;">Main
                        website</a>
                </div>
            @endif
        </div>
    </header>
@elseif(Route::is('front.sub-home-page'))
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom homepage-navbar">
        <div class="container-homepage">
            <a class="navbar-brand" href="#">
                <img src="{{ frontAssets('images/logo.svg') }}" alt="ATHLEAT Fuel Logo" />
            </a>
            <div class="mob-btn-wrap">
                <button class="me-0 btn btn-login web-hide">Log in</button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    style="border: none">
                    <span  class="menu-icon" style="color: white">
                        <img src="{{ frontAssets('images/bars.svg') }}" alt="ATHLEAT Fuel Logo" class="bars-icon"/>
                          <img src="{{ frontAssets('images/cross.svg') }}" alt="Menu" class="cross-icon" />
                    </span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="mx-auto navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Services
                            <svg width="10" height="7" viewBox="0 0 10 7" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1.5L5 5.5L9 1.5" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#">Training Nutrition Plan</a>
                            </li>
                            <li><a class="dropdown-item" href="#">Competition plan</a></li>
                            <li>
                                <a class="dropdown-item" href="#">Injury & Recovery Plan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Pre & Post Surgery Plan </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Private Consultations </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Resources</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Store</a>
                    </li>
                </ul>

                <div class="d-flex">
                    @if(Auth::check())
                        <a href="{{ route('front.profile', ['id' => Auth::guard('web')?->user()?->id]) }}" class="btn btn-signup mob-hide">
                            My Account
                        </a>
                    @else
                        <button class=" btn-login mob-hide">Log in</button>
                        <button class="btn btn-signup" id="show-new-signup-modal" data-bs-toggle="modal" data-bs-target="#signupModal">
                            Sign up for free
                        </button>
                    @endif
                    <button class="ms-2 btn btn-login web-hide">Virtual Kez</button>
                </div>
            </div>
        </div>
    </nav>
@else
    <header id="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="d-flex align-items-center w-100">
                    <a class="navbar-brand" href="{{ route('front.index') }}">
                        <img src="{{ frontAssets('images/logo.svg') }}" alt="">
                    </a>
                    <button class="collapsed navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="ms-lg-auto header-navbar navbar-nav">
                            @foreach ($headerData as $menu)
                                @php
                                    $slug = '';
                                    $link = $menu['link'] ?? '';
                                    if (str_contains($link, '|')) {
                                        $explodelinks = explode('|', $link);
                                        $link = $explodelinks[0] ?? '';
                                        $slug = $explodelinks[1] ?? '';
                                    }
                                    $title = $menu['title'] ?? 'Untitled';
                                @endphp
                                @if ($link != '' && $link != '#')
                                    <li class="nav-item">
                                        <a class="nav-link restriction-page" id="{{ strtolower($title) }}"
                                            href="{{ route($link, $slug ? ['page_slug' => $slug] : []) }}">{{ $title }}</a>
                                    </li>
                                @elseif($title == 'Contact')
                                    <li class="nav-item">
                                        <a class="nav-link restriction-page" id="contact-us"
                                            href="{{ route('front.index') }}#contact"> Contact</a>
                                    </li>
                                @else
                                    @if (Auth::check() && Auth::guard('web')->user()->is_superadmin == 0)
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                                                role="button" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="fa-solid fa-user"></i>
                                                My Account
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="userDropdown">
                                                <li>
                                                    <a class="p-2 text-dark dropdown-item"
                                                        href="{{ route('front.profile-old', ['id' => Auth::guard('web')->user()->id]) }}">
                                                        My Profile
                                                    </a>
                                                </li>
                                                <!-- <li>
                                        <a class="p-2 text-dark dropdown-item" href="">View My Plan</a>
                                    </li> -->
                                                <li>
                                                    <!-- Logout form (hidden) -->
                                                    <form id="logout-form" action="{{ route('front.logout') }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                    </form>
                                                    <a class="p-2 text-danger dropdown-item" href="#"
                                                        onclick="handleLogout(event)">
                                                        Logout
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @elseif($title == 'Login')
                                        <li class="nav-item">
                                            <a class="nav-link restriction-page" id="login" href="#"
                                                data-bs-toggle="modal" data-bs-target="#loginModal"><i
                                                    class="fa-solid fa-user"></i> Login</a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link restriction-page" id="{{ strtolower($title) }}"
                                                href="#{{ strtolower($title) }}">{{ $title }}</a>
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
                            <input type="password" name="password" class="form-control" id="login-password"
                                required>
                        </div>

                        <!-- Sign In Button -->
                           <button type="submit" id="login-submit" class="btn-primary w-100 mt-3">
                            Sign In
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-3 text-center">
                        <small>Don't have an account? <a href="#" id="show-signup-modal">Sign Up</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                url: '{{ route('front.login') }}', // This is the route for handling login (update with your actual route if different)
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
                            $('#login-error').text(
                                'Your session has expired. Please reload the page and login again.'
                                );
                        } else {
                            $('#login-error').text(response
                            .message); // Display error message in #login-error div
                        }
                    } else {
                        $('#login-error').text(
                        'An error occurred. Please try again.'); // General error message
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

<script>
    function toggleMobileMenu() {
        console.log('toggleMobileMenu called');
        var menu = document.getElementById('mobile-menu');
        var overlay = document.getElementById('mobile-menu-overlay');
        var isOpen = menu.classList.contains('open');
        if (isOpen) {
            menu.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        } else {
            menu.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }
      // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            const navbar = document.querySelector('.homepage-navbar');
            const barsIcon = document.querySelector('.bars-icon');
            const crossIcon = document.querySelector('.cross-icon');

            if (navbarToggler && navbarCollapse) {
                // Custom click handler to control timing
                navbarToggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Check if menu is currently open
                    const isMenuOpen = navbarCollapse.classList.contains('show');
                    
                    if (!isMenuOpen) {
                        // Menu is closed, opening it
                        // 1. Immediately change background color
                        if (navbar) navbar.classList.add('menu-open');
                        
                        // 2. Change icon immediately
                        if (barsIcon) barsIcon.style.display = 'none';
                        if (crossIcon) crossIcon.style.display = 'block';
                        
                        // 3. Open menu after 0.1s delay
                        setTimeout(() => {
                            navbarCollapse.classList.add('show');
                        }, 100);
                    } else {
                        // Menu is open, closing it
                        // 1. Immediately remove background color
                        if (navbar) navbar.classList.remove('menu-open');
                        
                        // 2. Change icon immediately
                        if (barsIcon) barsIcon.style.display = 'block';
                        if (crossIcon) crossIcon.style.display = 'none';
                        
                        // 3. Close menu after 0.1s delay
                        setTimeout(() => {
                            navbarCollapse.classList.remove('show');
                        }, 100);
                    }
                });

                // Remove Bootstrap's default toggle behavior
                navbarToggler.removeAttribute('data-bs-toggle');
                navbarToggler.removeAttribute('data-bs-target');
            }
        });
</script>