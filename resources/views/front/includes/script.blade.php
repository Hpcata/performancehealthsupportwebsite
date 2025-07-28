@if(Route::is('front.profile') || Route::is('front.plans.details'))
<script src="{!! frontAssets('js/script.js') !!}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{!! frontAssets('js/bootstrap/bootstrap.bundle.min.js') !!}"></script>
@else
<script src="{!! frontAssets('js/tiny-slider.js') !!}"></script>
<script src="{!! frontAssets('js/aos.js') !!}"></script>
<script src="{!! frontAssets('js/navbar.js') !!}"></script>
<script src="{!! frontAssets('js/counter.js') !!}"></script>
<script src="{!! frontAssets('js/rellax.js') !!}"></script>
<script src="{!! frontAssets('js/flatpickr.js') !!}"></script>
<script src="{!! frontAssets('js/glightbox.min.js') !!}"></script>
<script src="{!! frontAssets('js/custom.js') !!}"></script>
<script src="{!! frontAssets('js/general.js') !!}"></script>
<script src="{!! frontAssets('js/bootstrap/bootstrap.bundle.min.js') !!}"></script>

@endif
