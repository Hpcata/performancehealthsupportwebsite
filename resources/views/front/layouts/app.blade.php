<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Untree.co">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{!! frontAssets('favicon.svg') !!}">

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

    <style>
        #delphi-bubble-trigger {
            display: none !important;
        }
    </style>

    <!-- Includes the script file -->
    @include('front.includes.script')

    @stack('scripts')

    <script id="delphi-bubble-script">
    window.delphi = {...(window.delphi ?? {}) };
    window.delphi.bubble = {
        config: "663f5909-3622-47c9-9287-28233409948f",
        overrides: {
        landingPage: "CHAT",
        },
        trigger: {
        color: "#0090FF",
        },
    };

    $(document).ready(function() {
        let isOpen = false;

        function loadCustomDelphi() {
            // alert('1');
            $(document).find('#delphi-bubble-trigger').trigger("click");
            isOpen = true;
        }
        // Handle click event to open Delphi chat
        $(document).on('click', '.chat-widget, #chat-to-virtual-kez-btn, .start-chat', function() {
            // alert('2');
            loadCustomDelphi();
        });

        document.addEventListener('click', function(event) {
            if (event.target.closest('.chat-widget')) {
                return;
            }

            if (event.target.closest('.start-chat')) {
                return;
            }

            if (event.target.closest('#chat-to-virtual-kez-btn')) {
                return;
            }

            if (isOpen) {
                $(document).find('#delphi-bubble-trigger').trigger("click");
                isOpen = false;
            }
        });
    });

    </script>

    <script id="delphi-bubble-bootstrap" src="https://embed.delphi.ai/loader.js"></script>
    <div id="loader" style="display: none;">
        <div class="box" id="loader1"></div>
        <div class="box" id="loader2"></div>
        <div class="box" id="loader3"></div>
        <div class="box" id="loader4"></div>
        <div class="box" id="loader5"></div>
    </div>

    <!-- Error Modal HTML -->
    <div class="modal" id="errormodalmain" tabindex="-1" aria-labelledby="testLabel" aria-hidden="true">
        <div class="modal-dialog modal-confirm modal-dialog-centered">
            <div class="modal-content">
                <div class="justify-content-center modal-header">
                    <div class="icon-box">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <button class="dialog-close coming-soon-close"
                    data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z" fill="#3B3B3B"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center modal-body">
                    <h4>Ooops!</h4>
                    <p id="error">Something went wrong.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Coming Soon Modal -->
    <div class="modal" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonLabel" aria-hidden="true">
        <div class="modal-dialog modal-confirm modal-coming-soon modal-dialog-centered">
            <div class="modal-content">
                <div class="justify-content-center modal-header">
                    <div class="icon-box">
                        <i class="fas fa-clock"></i>
                    </div>
                    <button class="dialog-close coming-soon-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z" fill="#3B3B3B"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center modal-body">
                    <h4>Coming Soon!</h4>
                    <p>This feature is coming soon.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>