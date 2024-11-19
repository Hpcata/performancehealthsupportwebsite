const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
// 	.postCss('resources/css/app.css', 'public/css', [
// 		//
// 	]);

mix.setPublicPath('public')
	.setResourceRoot('../') // Turns assets paths in css relative to css file
	// .vue()
	.sass('public/backend/scss/main.scss', 'backend/ebazar.style.min.css')
	.combine([
		'node_modules/jquery/dist/jquery.js',
		'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
		'public/backend/dist/assets/plugin/colorpicker/colorpicker.js'
	], 'public/backend/dist/assets/bundles/libscripts.bundle.js')

	.combine([
		'node_modules/apexcharts/dist/apexcharts.min.js'
	], 'public/backend/dist/assets/bundles/apexcharts.bundle.js')

	.combine([
		'node_modules/jquery-sparkline/jquery.sparkline.min.js'
	], 'public/backend/dist/assets/bundles/sparkline.bundle.js')

	.combine([
		'public/backend/dist/assets/plugin/datatables/jquery.dataTables.min.js',
		'public/backend/dist/assets/plugin/datatables/dataTables.bootstrap5.min.js',
		'public/backend/dist/assets/plugin/datatables/dataTables.responsive.min.js'
	], 'public/backend/dist/assets/bundles/dataTables.bundle.js')

	.combine([
		'node_modules/dropify/dist/js/dropify.min.js'
	], 'public/backend/dist/assets/bundles/dropify.bundle.js')

	.sourceMaps();

if (mix.inProduction()) {
	mix.version();
} else {
	// Uses inline source-maps on development
	mix.webpackConfig({
		devtool: 'inline-source-map'
	});
}