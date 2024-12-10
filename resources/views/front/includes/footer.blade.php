
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

<script src="{!! frontAssets('js/bootstrap.bundle.min.js') !!}"></script>
<script src="{!! frontAssets('js/tiny-slider.js') !!}"></script>
<script src="{!! frontAssets('js/aos.js') !!}"></script>
<script src="{!! frontAssets('js/navbar.js') !!}"></script>
<script src="{!! frontAssets('js/counter.js') !!}"></script>
<script src="{!! frontAssets('js/rellax.js') !!}"></script>
<script src="{!! frontAssets('js/flatpickr.js') !!}"></script>
<script src="{!! frontAssets('js/glightbox.min.js') !!}"></script>
<script src="{!! frontAssets('js/custom.js') !!}"></script>
<script src="{!! frontAssets('js/general.js') !!}"></script>

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
