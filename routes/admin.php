<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminAuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FlagController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchasePlanController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard & Profile
    Route::get('/', [AdminAuthController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/{id}', [AdminAuthController::class, 'profile'])->name('index');
        Route::post('profile', [AdminAuthController::class, 'profilePost'])->name('update');
        Route::get('admin.profile.remove-image/{id}', [AdminAuthController::class, 'removeProfileImage'])->name('remove-image');
        Route::get('admin.profile.remove-logo/{id}', [AdminAuthController::class, 'removeFrontLogo'])->name('remove-logo');
        Route::get('remove-about-us-image/{id}', [AdminAuthController::class, 'removeAboutUsImage'])->name('remove-about-us');
    });

    // Product search & food add
    Route::get('admin.product.search', [ProductController::class, 'search'])->name('product.search');
    Route::post('add-food', [ProductController::class, 'addFood'])->name('product.add-food');

    // Organizations
    Route::prefix('organizations')->name('organizations.')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('index');
        Route::post('media-upload', [OrganizationController::class, 'mediaUpload'])->name('media-upload');
        Route::post('image-list', [OrganizationController::class, 'getImageList'])->name('image-list');
        Route::post('image-delete', [OrganizationController::class, 'imageDelete'])->name('image-delete');
        Route::post('sort-order', [OrganizationController::class, 'sorting'])->name('sort-order');
    });

    // Testimonials
    Route::prefix('testimonials')->as('testimonials.')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/list-ajax', [TestimonialController::class, 'listAjax'])->name('list-ajax');
        Route::get('/add', [TestimonialController::class, 'add'])->name('add');
        Route::get('/edit/{id}', [TestimonialController::class, 'edit'])->name('edit');
        Route::post('/save', [TestimonialController::class, 'save'])->name('save');
        Route::post('/delete', [TestimonialController::class, 'delete'])->name('delete');
    });

    // Blogs & Coupons
    Route::resource('blogs', BlogController::class)->names('blogs');
    Route::resource('coupons', CouponController::class)->names('coupons');

    // Tags & Flags
    Route::resource('tags', TagController::class)->names('tags');
    Route::resource('flags', FlagController::class)->names('flags');
    Route::delete('flags/{flag}/remove-food/{food}', [FlagController::class, 'removeFood'])->name('flags.removeFood');
    Route::post('flags/{flag}/add-foods', [FlagController::class, 'addFoods'])->name('flags.addFoods');
    Route::get('flags/food/list', [FlagController::class, 'foodList'])->name('flags.foodList');

    // Site Settings
    Route::get('site-settings/{slug}', [SiteSettingsController::class, 'index'])->name('settings.index');
    Route::post('site-settings-save', [SiteSettingsController::class, 'saveSiteSettings'])->name('settings.save');

    // Pages & Nested Sections
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('create', [PageController::class, 'create'])->name('create');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::get('{page}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('{page}', [PageController::class, 'update'])->name('update');
        Route::delete('{page}', [PageController::class, 'destroy'])->name('destroy');

        Route::prefix('{page}/sections')->name('sections.')->group(function () {
            Route::get('/', [SectionController::class, 'index'])->name('index');
            Route::get('create', [SectionController::class, 'create'])->name('create');
            Route::post('/', [SectionController::class, 'store'])->name('store');
        });
    });

    // Section Standalone Actions
    Route::prefix('sections')->name('sections.')->group(function () {
        Route::get('{section}', [SectionController::class, 'show'])->name('show');
        Route::get('{section}/edit', [SectionController::class, 'edit'])->name('edit');
        Route::put('{section}', [SectionController::class, 'update'])->name('update');
        Route::delete('{section}', [SectionController::class, 'destroy'])->name('destroy');
    });

    // Categories & Subcategories
    Route::resource('categories', CategoryController::class)->names('categories');
    Route::resource('sub-categories', SubCategoryController::class)->names('subcategories');

    // Items & food details
    Route::resource('items', ItemController::class)->names('items');
    Route::get('get-food-details', [ItemController::class, 'getFoodDetails'])->name('items.get-food-details');

    // Plans
    Route::resource('plans', PlanController::class)->names('plans');

    // Meals
    Route::resource('meals', MealController::class)->names('meals');
    Route::post('meals/update-name', [MealController::class, 'updateMealName'])->name('meals.update-name');
    Route::post('meals/generate-image', [MealController::class, 'generate-image'])->name('meals.generate-image');
    Route::post('meals/edit-image', [MealController::class, 'edit-image'])->name('meals.edit-image');
    Route::get('meals/import/form', [MealController::class, 'viewImport'])->name('meals.import-form');
    Route::post('meals/import', [MealController::class, 'import'])->name('meals.import');

    // Purchase Plans
    Route::prefix('purchase-plans')->name('purchase-plans.')->group(function () {
        Route::get('/', [PurchasePlanController::class, 'index'])->name('index');
        Route::get('{id}/create', [PurchasePlanController::class, 'create'])->name('create');
        Route::post('/', [PurchasePlanController::class, 'store'])->name('store');
        Route::get('{user}/edit/{plan}', [PurchasePlanController::class, 'edit'])->name('edit');
        Route::put('/', [PurchasePlanController::class, 'update'])->name('update');
        Route::get('pre-plan-details/{id}', [PurchasePlanController::class, 'getPrePlanDetails'])->name('pre-plan-details');
        Route::post('handle-plan-action', [PurchasePlanController::class, 'handlePlanAction'])->name('handle-plan-action');
        Route::post('update-nutrition-flag', [PurchasePlanController::class, 'updateNutritionFlag'])->name('update-nutrition-flag');

        Route::post('get-meal-items', [PurchasePlanController::class, 'getMealItems'])->name('get-meal-items');
        Route::post('get-meals-by-mealtime', [PurchasePlanController::class, 'getMealsByMealTime'])->name('get-meals-by-mealtime');
        Route::post('remove-user-meal', [PurchasePlanController::class, 'removeUserMeal'])->name('remove-user-meal');

        Route::get('get-items', [PurchasePlanController::class, 'getItems'])->name('get-items');
        Route::post('get-swap-items', [PurchasePlanController::class, 'getSwapItems'])->name('get-swap-items');
        Route::post('add-food', [PurchasePlanController::class, 'addFood'])->name('add-food');
        Route::post('save-swap-food', [PurchasePlanController::class, 'saveSwapFood'])->name('save-swap-food');
        Route::post('get-swap-foods', [PurchasePlanController::class, 'getSwapFoods'])->name('get-swap-foods');
        Route::post('update-swap-foods', [PurchasePlanController::class, 'updateFoodSwapFoods'])->name('update-food-swap-foods');
        Route::post('delete-purchase-plan-food', [PurchasePlanController::class, 'deletePurchasePlanFood'])->name('delete-purchase-plan-food');
        Route::post('update-swap-item', [PurchasePlanController::class, 'updateSwapItem'])->name('update-swap-item');
    });

    // User details & management
    Route::get('user/details', [UserController::class, 'getUserDetails'])->name('user.details');
    Route::resource('users', UserController::class)->only(['index', 'destroy'])->names('users');

    // Quiz
    Route::get('quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('quiz', [QuizController::class, 'store'])->name('quiz.store');
});
