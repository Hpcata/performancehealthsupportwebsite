<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', [AdminAuthController::class, 'index'])->name('index');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Register
Route::get('register', [AdminAuthController::class, 'register'])->name('register');
Route::post('register', [AdminAuthController::class, 'registerPost'])->name('register-post');

// Forgot password
Route::get('/forgot-password', [AdminAuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AdminAuthController::class, 'forgotPasswordPost'])->name('forgot-password-post');

// Reset password
Route::get('/reset-password/{token}', [AdminAuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password', [AdminAuthController::class, 'resetPasswordPost'])->name('reset-password-post');

// Change password
Route::get('/change-password', [AdminAuthController::class, 'changePassword'])->name('change-password');
Route::post('/change-password', [AdminAuthController::class, 'changePasswordPost'])->name('change-password-post');

Route::group(['middleware' => 'auth'], function () {
	Route::prefix('admin')->group(function () {
		Route::get('/', [AdminAuthController::class, 'dashboard'])->name('dashboard');
		Route::get('/profile/{id}', [AdminAuthController::class, 'profile'])->name('backend.admin-profile');
    	Route::get('/remove-profile-image/{id}', [AdminAuthController::class, 'removeProfileImage'])->name('remove-profile-image');
    	Route::get('/remove-front-logo/{id}', [AdminAuthController::class, 'removeFrontLogo'])->name('remove-front-logo');
    	Route::get('/remove-aboutus-image/{id}', [AdminAuthController::class, 'removeAboutUsImage'])->name('remove-aboutus-image');
    	Route::post('/profile', [AdminAuthController::class, 'profilePost'])->name('profile-post');

		Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations');
    	Route::group(['prefix' => 'organizations', 'as' => 'organizations.'], function () {
			Route::post('/media-upload', [OrganizationController::class, 'mediaUpload'])->name('media-upload');
			Route::post('/image-list', [OrganizationController::class, 'getImageList'])->name('image-list');
			Route::post('/image-delete', [OrganizationController::class, 'imageDelete'])->name('image-delete');
			Route::post('/sort-order', [OrganizationController::class, 'sorting'])->name('sort-order');
		});

    	// Integration Routes
		// Route::group(['prefix' => 'integrations', 'as' => 'integrations.'], function () {
		// 	// Route to display the integrations index page
		// 	Route::get('/', [IntegrationController::class, 'index'])->name('index');

		// 	// Route to handle the integration process (POST request)
		// 	Route::post('/integration', [IntegrationController::class, 'integrate'])->name('integrate');

		// 	// Route to handle callback
		// 	Route::get('/callback', [IntegrationController::class, 'handleZoomCallback'])->name('callback');
		// });
		
		// Testimonial routes
		Route::group(['prefix' => 'testimonials', 'as' => 'testimonials.'], function () {
			Route::get('/', [TestimonialController::class, 'index'])->name('index');
			Route::get('/list-ajax', [TestimonialController::class, 'listAjax'])->name('list-ajax');
			Route::get('/add', [TestimonialController::class, 'add'])->name('add');
			Route::get('/edit/{id}', [TestimonialController::class, 'edit'])->name('edit');
			Route::post('/save', [TestimonialController::class, 'save'])->name('save');
			Route::post('/delete', [TestimonialController::class, 'delete'])->name('delete');
		});
		
		Route::as('backend.')->group(function () {
			Route::resource('blogs', BlogController::class);
		});
	});
});

Route::get('/{slug}', [FrontController::class, 'index'])->name('front.index');
Route::get('/{slug}/blog', [FrontController::class, 'blog'])->name('front.blog');
Route::get('{slug}/blog/{id}', [FrontController::class, 'blogDetails'])->name('front.blog.detail');
Route::POST('/save-query', [FrontController::class, 'save'])->name('save-query');

// Route::get('/backend', function () {
// 	return redirect()->route('backend.home');
// });

Route::prefix('admin')->group(function () {
	//dd('backend');
	// Route::get('/home', [BackendController::class, 'index'])->name('backend.home');

	// Blog
	

	// Route::prefix('')->group(function () {
	// 	Route::get('blogs-list', [BackendController::class, 'index'])->name('backend.blogs-list');
	// 	Route::get('blogs-add', [BackendController::class, 'create'])->name('backend.blogs-add');
	// 	Route::post('blogs-store', [BackendController::class, 'store'])->name('backend.blogs-store');
	// 	Route::get('blogs-edit', [BackendController::class, 'edit'])->name('backend.blogs-edit');
	// 	Route::post('blogs-update', [BackendController::class, 'update'])->name('backend.blogs-update');
	// 	Route::post('blogs-delete', [BackendController::class, 'delete'])->name('backend.blogs-delete');
	// });
	
});
