<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminAuthController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Front\PlanController as FrontPlanController;
use App\Http\Controllers\Admin\MealTimeController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Admin\PurchasePlanController;


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

Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => \Auth::check(),
        'user' => \Auth::user()
    ]);
});

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

		Route::get('/site-settings/{slug}', [SiteSettingsController::class, 'index'])->name('site-settings');
		Route::post('/site-settings-save', [SiteSettingsController::class, 'saveSiteSettings'])->name('save-site-settings');
		
		// Route::resource('pages', PageController::class);
		Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
		Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
		Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
		Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
		Route::PUT('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
		Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

		Route::get('pages/{page}/sections', [SectionController::class, 'index'])->name('sections.index');

		// Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
		Route::get('/sections/create', [SectionController::class, 'create'])->name('sections.create');
		Route::post('/sections/store', [SectionController::class, 'store'])->name('sections.store');
		Route::get('/sections/{section}', [SectionController::class, 'show'])->name('sections.show');
		Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
		Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
		Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

		// Category
		Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    	Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    	Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    	Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    	Route::put('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    	Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

		// Subcategory
		Route::get('sub-categories', [SubCategoryController::class, 'index'])->name('admin.subcategories.index');
    	Route::get('sub-categories/create', [SubCategoryController::class, 'create'])->name('admin.subcategories.create');
    	Route::post('sub-categories', [SubCategoryController::class, 'store'])->name('admin.subcategories.store');
    	Route::get('sub-categories/{id}/edit', [SubCategoryController::class, 'edit'])->name('admin.subcategories.edit');
    	Route::put('sub-categories/{id}', [SubCategoryController::class, 'update'])->name('admin.subcategories.update');
    	Route::delete('sub-categories/{id}', [SubCategoryController::class, 'destroy'])->name('admin.subcategories.destroy');

		// Items
		Route::get('items', [ItemController::class, 'index'])->name('admin.items.index');
		Route::get('items/create', [ItemController::class, 'create'])->name('admin.items.create');
		Route::post('items', [ItemController::class, 'store'])->name('admin.items.store');
		Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
		Route::put('items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
		Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
		
		// Plans
		Route::get('plans', [PlanController::class, 'index'])->name('admin.plans.index');
		Route::get('plans/create', [PlanController::class, 'create'])->name('admin.plans.create');
		Route::post('plans', [PlanController::class, 'store'])->name('admin.plans.store');
		Route::get('plans/{plan}/edit', [PlanController::class, 'edit'])->name('admin.plans.edit');
		Route::put('plans/{plan}', [PlanController::class, 'update'])->name('admin.plans.update');
		Route::delete('plans/{plan}', [PlanController::class, 'destroy'])->name('admin.plans.destroy');

		Route::get('meals', [MealController::class, 'index'])->name('admin.meals.index');
		Route::get('meals/create', [MealController::class, 'create'])->name('admin.meals.create');
		Route::post('meals', [MealController::class, 'store'])->name('admin.meals.store');
		Route::get('meals/{meal}/edit', [MealController::class, 'edit'])->name('admin.meals.edit');
		Route::put('meals/{meal}', [MealController::class, 'update'])->name('admin.meals.update');
		Route::delete('meals/{meal}', [MealController::class, 'destroy'])->name('admin.meals.destroy');
	
		Route::get('meal-times', [MealTimeController::class, 'index'])->name('admin.meal-times.index');
		Route::get('meal-times/create', [MealTimeController::class, 'create'])->name('admin.meal-times.create');
		Route::post('meal-times', [MealTimeController::class, 'store'])->name('admin.meal-times.store');
		Route::get('meal-times/{id}/edit', [MealTimeController::class, 'edit'])->name('admin.meal-times.edit');
		Route::put('meal-times/{id}', [MealTimeController::class, 'update'])->name('admin.meal-times.update');
		Route::delete('meal-times/{id}', [MealTimeController::class, 'destroy'])->name('admin.meal-times.destroy');
		
		Route::get('/purchase-plans', [PurchasePlanController::class, 'index'])->name('admin.purchase-plans.index');
		Route::get('/purchase-plans/{id}/create', [PurchasePlanController::class, 'create'])->name('admin.purchase-plans.create');
		Route::post('/purchase-plans', [PurchasePlanController::class, 'store'])->name('admin.purchase-plans.store');
		Route::get('/purchase-plans/{user}/edit/{plan}', [PurchasePlanController::class, 'edit'])->name('admin.purchase-plans.edit');
		Route::put('/purchase-plans', [PurchasePlanController::class, 'update'])->name('admin.purchase-plans.update');
		Route::get('/pre-plan-details/{id}', [PurchasePlanController::class, 'getPrePlanDetails'])->name('admin.pre-plan-details');

		Route::post('/get-meal-items', [PurchasePlanController::class, 'getMealItems'])->name('admin.get-meal-items');
		Route::post('/get-meals-by-mealtime', [PurchasePlanController::class, 'getMealsByMealTime'])->name('admin.get-meals-by-mealtime');
	});
});

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/blog', [FrontController::class, 'blog'])->name('front.blog');
Route::get('blog/{id}', [FrontController::class, 'blogDetails'])->name('front.blog.detail');
Route::POST('/save-query', [FrontController::class, 'save'])->name('save-query');
Route::get('/action-sport-nutrition-plan', [FrontController::class, 'subHomePage'])->name('front.sub-home-page');
Route::get('/pre-plan-details', [PaymentController::class, 'prePlanDetails'])->name('front.pre-plan-details');
Route::post('/pre-plan-details-store', [PaymentController::class, 'prePlanDetailsSave'])->name('front.pre-plan-details.store');

Route::get('/competition-plan/{id}', [FrontController::class, 'getCompetitionPlanDetails'])->name('front.competition-plan-details');
Route::get('/get-meals-items', [FrontController::class, 'getAllMeals'])->name('front.get.meals.items');
Route::get('/get-default-plan-details/{id}', [FrontPlanController::class, 'getDefaultPlanDetails'])->name('front.get-default-plan-details');
Route::get('/get-race-ethnicity-culture-options', [PaymentController::class, 'getRaceEthnicityCultureOptions'])->name('front.get-race-ethnicity-culture-options');

// Front auth
Route::post('front/register', [FrontController::class, 'register'])->name('front.register');
Route::post('front/login', [FrontController::class, 'login'])->name('front.login');

//Stripe payment
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::post('/free-test-save', [FrontController::class, 'freeTestSave'])->name('front.submit-free-test');

// Plans
Route::group(['middleware' => 'auth'], function () {
	Route::get('/plans/{id}', [FrontPlanController::class, 'show'])->name('front.plans.details');
	Route::get('/meal-time/{id}/{plan_id}', [FrontPlanController::class, 'mealTimeDetails'])->name('front.meal-time.details');

	//categories
	Route::get('/category/{id}/meals', [FrontPlanController::class, 'getMeals'])->name('front.category.meals');
	// Route::get('/category/{id}/subcategories', [FrontPlanController::class, 'getSubCategories'])->name('front.category.subcategories');
	// Route::get('/subcategory/{id}/items', [FrontPlanController::class, 'getSubcategoryItems'])->name('front.subcategories.items');
	Route::get('/meal/{id}/items', [FrontPlanController::class, 'getMealItems'])->name('front.meals.items');
	Route::get('/item/{id}/swap-items', [FrontPlanController::class, 'getSwapItems'])->name('front.items.swap-items');

	Route::get('item/swap', [FrontPlanController::class, 'applySwaps'])->name('front.items.swaps');
	Route::get('/plans/{id}/print', [FrontPlanController::class, 'generatePdf'])->name('plans.generatePdf');
	Route::get('/profile/{id}', [FrontController::class, 'getProfileDetails'])->name('front.profile');
	Route::post('/profile/update', [FrontController::class, 'updateProfile'])->name('front.profile.update');
	Route::post('/food/quantity/update', [FrontController::class, 'updateFoodQuantity'])->name('front.food-quantity-update');

});


