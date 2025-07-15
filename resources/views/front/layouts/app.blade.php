<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Untree.co">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{!! frontAssets('favicon.png') !!}">

    <meta name="description" content="@yield('meta_description', 'Performance Health Support offers expert care from top sports nutritionists, strength coaches, and sports dietitians in Australia to boost health and performance.')">
    <meta name="keywords" content="bootstrap, bootstrap5" />

    {{-- Styles and Preloads --}}
    @include('front.includes.style')

    {{-- jQuery --}}
    <script src="{!! frontAssets('js/jquery-3.6.min.js') !!}"></script>

    {{-- GTM & Hotjar --}}
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-N2BZFJGB');

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

        document.addEventListener('DOMContentLoaded', function() {
            $(document).ajaxError(function(event, jqXHR, settings, error) {
                if (jqXHR.status === 419 || jqXHR.status === 401) {
                    window.location.href = "https://performancehealthsupport.com";
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    <script>
        window.AppConfig = {
            testimonialsApiUrl: "{{ url('/api/testimonials') }}",
            organizationsApiUrl: "{{ url('/api/organizations') }}"
        };
    </script>
	@stack('styles')
	@stack('custom_styles')
</head>

<body>
    <!-- GTM noscript -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N2BZFJGB" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    @include('front.includes.header')

    @yield('content')

    @include('front.includes.footer')

    @stack('scripts')
</body>

</html>