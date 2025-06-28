<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <title>@yield('title') </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="{!! frontAssets('favicon.png') !!}">

    <meta name="description" content="@yield('meta_description', 'Performance Health Support offers expert care from top sports nutritionists, strength coaches, and sports dietitians in Australia to boost health and performance.')">

    <meta name="keywords" content="bootstrap, bootstrap5" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{!! frontAssets('fonts/icomoon/style.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('fonts/flaticon/font/flaticon.css') !!}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{!! frontAssets('css/tiny-slider.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('css/aos.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('css/flatpickr.min.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('css/glightbox.min.css') !!}">
    <link rel="stylesheet" href="{!! frontAssets('css/style.css') !!}">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-N2BZFJGB');</script>
    <!-- End Google Tag Manager -->
    
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 5173054,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Global AJAX error handler
        $(document).ajaxError(function(event, jqXHR, settings, error) {
            if (jqXHR.status === 419 || jqXHR.status === 401) {
                // Session expired or CSRF token mismatch
                window.location.href = "{{ route('front.index') }}";
            }
        });

        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
	@stack('styles')
	@stack('custom_styles')
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N2BZFJGB"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
   @include('front.includes.header')
   @yield('content')
   @include('front.includes.footer')
</body>

</html>