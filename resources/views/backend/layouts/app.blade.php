<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<?php header('Cache-Control: no-cache, max-age=0, must-revalidate, no-store'); ?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Athleat Fuel:: @yield('title')</title>
	<link rel="icon" href="{{ url('/') }}/favicon.ico" type="image/x-icon"> <!-- Favicon-->

	<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/datatables/responsive.dataTables.min.css') !!}">
	<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/datatables/dataTables.bootstrap5.min.css') !!}">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

	@stack('styles')

	<!-- project css file  -->
	<link rel="stylesheet" href="{!! backendAssets('ebazar.style.min.css') !!}">
	<link rel="stylesheet" href="{{ backendAssets('css/custom.css') }}">

	@stack('custom_styles')

</head>

<body>
	<div id="ebazar-layout" class="theme-blue">

		@include(backendView('includes.sidebar'))

		<!-- main body area -->
		<div class="main px-lg-4 px-md-4">
			@include(backendView('includes.header'))

			<!-- Body: Body -->
			<div class="body d-flex py-3">
				@yield('content')
			</div>

			@stack('modals')
		</div>

	</div>
	<!-- jQuery -->
	<!-- JQuery Core Js -->
	<script src="https://cdn.tiny.cloud/1/szrx9k170icaql0d40hu6euk46v017qd55txbsvwac74hcdq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
	<script src="{!! backendAssets('dist/assets/bundles/libscripts.bundle.js') !!}"></script>
	<script src="{!! backendAssets('dist/assets/bundles/dataTables.bundle.js') !!}"></script>
	<script src="{!! backendAssets('dist/assets/js/general.js') !!}"></script>

	<script>
		$('#myDataTable')
			.DataTable({
				responsive: true,
				stateSave: true,
				pageLength: 100,
				columnDefs: [
					{
						"targets": -1, // targets the last column (Action)
						"orderable": false
					}
				]
			});

	</script>
	@stack('scripts')
	<!-- JQuery Page Js -->
	<script src="{!! backendAssets('dist/assets/js/template.js') !!}"></script>

	@stack('custom_scripts')
</body>

</html>