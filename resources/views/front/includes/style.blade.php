@if(Route::is('front.profile') || Route::is('front.plans.details') || Route::is('front.sub-home-page') || Route::is('front.training.nutrition.plan') || Route::is('front.competition.plan') || Route::is('front.injury.recovery.plan') || Route::is('front.about-us') ||Route::is('front.my-plans'))

<link rel="stylesheet" href="{{ frontAssets('css/bootstrap/bootstrap-5.3.min.css') }}" />
<link rel="stylesheet" href="{{ frontAssets('css/styles.css') }}" />
<link rel="stylesheet" href="{{ frontAssets('css/profile_landing.css') }}" />

<!-- Preconnect for Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Non-blocking Font Awesome -->
<link
    rel="stylesheet"
    href="{{ frontAssets('css/font-awesome/all.min.css') }}"
    media="print"
    onload="this.media='all'"
/>
<noscript>
    <link
    rel="stylesheet"
    href="{{ frontAssets('css/font-awesome/all.min.css') }}"
    />
</noscript>
<!-- Non-blocking Google Fonts CSS -->
<link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    media="print"
    onload="this.media='all'"
/>
 <noscript>
    <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    />
</noscript>
{{-- <link rel="preload" as="image" href="{{ frontAssets('images/food1.webp') }}" type="image/webp"> --}}
<link rel="preload" as="image" href="{{ frontAssets('images/logo.webp') }}" type="image/webp">
<link rel="stylesheet" href="{!! frontAssets('css/tiny-slider.css') !!}">
@else
<!-- Existing code style -->
<!-- Fallback for browsers that don't support preload -->
<noscript>
    <!--<link rel="stylesheet" href="{!! frontAssets('css/tiny-slider.min.css') !!}">-->
    <link rel="stylesheet" href="{!! frontAssets('css/aos.min.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('css/flatpickr.min.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('css/glightbox.min.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('fonts/icomoon/style.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('fonts/flaticon/font/flaticon.css') !!}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</noscript>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>


<link rel="stylesheet" href="{!! frontAssets('css/style-1.css') !!}">
<link rel="stylesheet" href="{{ frontAssets('css/bootstrap/bootstrap.min.css') }}" />

<!-- <link rel="preload" href="{!! frontAssets('css/tiny-slider.min.css') !!}" as="style" onload="this.rel='stylesheet'"> -->
<link rel="preload" href="{!! frontAssets('css/aos.min.css') !!}" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="{!! frontAssets('css/flatpickr.min.css') !!}" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="{!! frontAssets('css/glightbox.min.css') !!}" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="{!! frontAssets('fonts/icomoon/style.css') !!}" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="{!! frontAssets('fonts/flaticon/font/flaticon.css') !!}" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.rel='stylesheet'">
@endif
