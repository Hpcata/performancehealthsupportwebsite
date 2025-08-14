@if (Route::is('front.profile') || Route::is('front.plans.details') || Route::is('front.my-plans'))
    <footer class="footer">
        <div class="footer-content">
            <div class="logo">
                <img src="{!! frontAssets('images/logo.svg') !!}" alt="Logo" class="logo-img" />
            </div>
            <nav class="footer-nav">
                @if ($userId = optional(auth()->guard('web')->user())->id)
                    <a href="{{ route('front.profile-old', ['id' => $userId]) }}">My Profile</a>
                @else
                    <a href="#">My Profile</a>
                @endif
                <a href="/challenges">Challenges and Rewards</a>
                <a href="/resources">Resources and Help</a>
                <a href="/store">Store</a>
            </nav>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2025 Catalysta Pty Ltd trading as Athlete Elite. All rights reserved.</p>
        </div>
    </footer>
@elseif(Route::is('front.sub-home-page') || Route::is('front.training.nutrition.plan') || Route::is('front.competition.plan') || Route::is('front.injury.recovery.plan') || Route::is('front.about-us'))
    <footer class="footer-bg">
        <div class="footer-grid container-homepage">
            <!-- Left Section: Logo, Tagline, Social, Copyright -->
            <div class="navbar-brand">
                <a href="{{ route('front.sub-home-page') }}">
                    <img src="{!! frontAssets('images/logo.svg') !!}" alt="ATHLEAT Fuel Logo" width="142" height="30"/>
                </a>
                <p class="tagline">Be elite - Get ATHLEAT</p>
                <a href="javascript:void(0)" onclick="showLearnMoreTooltip(this, 'Coming Soon')" class="mb-auto social-icon" style="position: relative;">
                    <!-- LinkedIn Icon (using a simple text placeholder for demonstration) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                        <path
                            d="M20.4705 2.83217H3.53055C3.34013 2.82953 3.15105 2.86442 2.97411 2.93485C2.79718 3.00529 2.63585 3.10989 2.49934 3.24268C2.36284 3.37547 2.25383 3.53386 2.17854 3.70878C2.10325 3.88371 2.06316 4.07175 2.06055 4.26217V21.4022C2.06316 21.5926 2.10325 21.7806 2.17854 21.9556C2.25383 22.1305 2.36284 22.2889 2.49934 22.4217C2.63585 22.5544 2.79718 22.6591 2.97411 22.7295C3.15105 22.7999 3.34013 22.8348 3.53055 22.8322H20.4705C20.661 22.8348 20.85 22.7999 21.027 22.7295C21.2039 22.6591 21.3652 22.5544 21.5017 22.4217C21.6383 22.2889 21.7473 22.1305 21.8226 21.9556C21.8978 21.7806 21.9379 21.5926 21.9405 21.4022V4.26217C21.9379 4.07175 21.8978 3.88371 21.8226 3.70878C21.7473 3.53386 21.6383 3.37547 21.5017 3.24268C21.3652 3.10989 21.2039 3.00529 21.027 2.93485C20.85 2.86442 20.661 2.82953 20.4705 2.83217ZM8.09055 19.5722H5.09055V10.5722H8.09055V19.5722ZM6.59055 9.31217C6.17681 9.31217 5.78002 9.14781 5.48746 8.85526C5.1949 8.5627 5.03055 8.16591 5.03055 7.75217C5.03055 7.33843 5.1949 6.94164 5.48746 6.64908C5.78002 6.35653 6.17681 6.19217 6.59055 6.19217C6.81024 6.16726 7.03272 6.18903 7.24342 6.25605C7.45412 6.32308 7.64829 6.43386 7.8132 6.58113C7.97812 6.7284 8.11007 6.90885 8.20042 7.11065C8.29076 7.31246 8.33746 7.53107 8.33746 7.75217C8.33746 7.97327 8.29076 8.19189 8.20042 8.39369C8.11007 8.59549 7.97812 8.77594 7.8132 8.92321C7.64829 9.07048 7.45412 9.18126 7.24342 9.24829C7.03272 9.31532 6.81024 9.33709 6.59055 9.31217ZM18.9105 19.5722H15.9105V14.7422C15.9105 13.5322 15.4805 12.7422 14.3905 12.7422C14.0532 12.7446 13.7247 12.8505 13.4494 13.0453C13.174 13.2402 12.965 13.5148 12.8505 13.8322C12.7723 14.0672 12.7384 14.3147 12.7505 14.5622V19.5622H9.75055C9.75055 19.5622 9.75055 11.3822 9.75055 10.5622H12.7505V11.8322C13.0231 11.3593 13.4195 10.9697 13.897 10.7054C14.3745 10.4411 14.9151 10.312 15.4605 10.3322C17.4605 10.3322 18.9105 11.6222 18.9105 14.3922V19.5722Z"
                            fill="white" />
                    </svg>
                </a>
                <p class="copyright-text mob-hide">
                    Copyright © 2025 Catalysta Pty Ltd trading as Athlete Elite. All rights reserved.
                </p>
            </div>

            <!-- Navigation Links -->
            <nav class="nav-links">
                <a href="javascript:void(0)" onclick="showLearnMoreTooltip(this, 'Coming Soon')" class="footer-link" style="position: relative;">About</a>
                <div class="dropdown">
                    <a class="footer-link dropdown-toggle" href="#" role="button" id="servicesDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li>
                            <a class="dropdown-item" href="#">Training Nutrition Plan</a>
                        </li>
                        <li><a class="dropdown-item" href="#">Competition plan</a></li>
                        <li>
                            <a class="dropdown-item" href="#">Injury & Recovery Plan</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Pre & Post Surgery Plan</a>
                        </li>
                        <li><a class="dropdown-item" href="#">Private Consultations</a></li>
                    </ul>
                </div>

                <a href="javascript:void(0)" onclick="showLearnMoreTooltip(this, 'Coming Soon')" class="footer-link contact-mobile" style="position: relative;">Contact</a>
            </nav>

            <!-- Buttons (Desktop) -->
            <div class="buttons-desktop">
                @if (auth()->guard('web')->check())
                    <a href="#" class="btn btn-login mob-hide">My Profile</a>
                @else
                    <button class=" btn-login mob-hide" onclick="openSingupFreePopup(true)">Log in</button>
                    <button class=" btn-signup" onclick="openSingupFreePopup()">Sign up for free</button>
                @endif
            </div>

            <!-- Buttons (Mobile) -->
            <div class="buttons-mobile">
                @if (auth()->guard('web')->check())
                    <a href="#" class="btn btn-login rounded-md">My Profile</a>
                @else
                    <button type="button" class="rounded-md  btn-login" onclick="openSingupFreePopup(true)">Log in</button>
                    <button type="button" class="rounded-md  btn-signup" onclick="openSingupFreePopup()">Sign up for free</button>
                @endif
            </div>
            <p class="copyright-text web-hide">Copyright {{ date('Y') }} Catalysta Pty Ltd</p>
        </div>
    </footer>
@else
    <div class="site-footer">
        <div class="container">
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="mb-0">
                        Copyright © 2024 Kerry O’Bryan.
                    </p>
                </div>
            </div>
            <!-- /.container -->
        </div>
        <!-- /.site-footer -->
        <!-- Preloader -->
        <div id="overlayer"></div>
        <div class="loader">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
@endif

<style>
    /* home page css */
    :root {
        --size: clamp(10rem, 1rem + 40vmin, 30rem);
        --gap: calc(clamp(10rem, 1rem + 40vmin, 30rem) / 14);
        --scroll-start: 0;
        --scroll-end: calc(-100% - calc(clamp(10rem, 1rem + 40vmin, 30rem) / 14));
    }

    .marquee-main {
        overflow-x: hidden;
    }

    .marquee {
        display: flex;
        overflow: hidden;
        user-select: none;
        gap: calc(clamp(10rem, 1rem + 40vmin, 30rem) / 14);
        margin-bottom: 1.5rem;
    }

    .marquee__group {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: space-around;
        gap: calc(clamp(10rem, 1rem + 40vmin, 30rem) / 14);
        min-width: 100%;
        animation: scroll-x 30s linear infinite;
    }

    @media (prefers-reduced-motion: reduce) {
        .marquee__group {
            animation-play-state: paused;
        }
    }

    .marquee--reverse .marquee__group {
        animation-direction: reverse;
        animation-delay: -3s;
    }

    @keyframes scroll-x {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(var(--scroll-end));
        }
    }


    /* Element styles */
    .marquee__group>div {
        margin-bottom: 1px;
        border: 1px solid #e9e9e9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        width: 300px;
        min-height: 100px;

    }

    .marquee--vertical div {
        aspect-ratio: 1;
        width: calc(clamp(10rem, 1rem + 40vmin, 30rem) / 1.5);
        padding: calc(clamp(10rem, 1rem + 40vmin, 30rem) / 6);
    }

    /* Parent wrapper */
    .wrapper {
        display: flex;
        flex-direction: column;
        gap: calc(clamp(10rem, 1rem + 40vmin, 30rem) / 14);
        margin: auto;
        max-width: 100vw;
        width: 100%;
    }

    .wrapper--vertical {
        flex-direction: row;
        height: 100vh;
    }

    @keyframes fade {
        to {
            opacity: 0;
            visibility: hidden;
        }
    }

    .marquee__group span img {
        height: auto !important;
        width: auto !important;
        max-height: 67px;
    }
</style>
