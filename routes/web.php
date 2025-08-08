<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
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
use App\Http\Controllers\Admin\ProductController;
use GuzzleHttp\Client;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Front\ForgotPasswordController;
use App\Http\Controllers\Front\OtpRegistrationController;
use App\Http\Controllers\Admin\NutritionAIController;
use App\Http\Controllers\Admin\ImageController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\FlagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Front\QuizController as FrontQuizController;
use App\Http\Controllers\Admin\SportCategoryController;
use App\Http\Controllers\Admin\SportGameController;

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


Route::get('/chat', function () {
    return view('image_form');
});

Route::get('/fetch-alternate-measurement', function () {
    Artisan::call('nutrition:alternates');

    return response()->json([
        'status' => 'success',
        'message' => 'Nutrition alternate measurements fetched successfully.',
    ]);
});

Route::post('/chat', [ImageController::class, 'chat'])->name('chat');
Route::get('/generate-image', [ImageController::class, 'generateImageForm']);
Route::post('/generate-image', [ImageController::class, 'generateImage'])->name('generate-image');


Route::post('/nutrition-calculation', [NutritionAIController::class, 'nutritionCalculation'])->name('nutrition.calculate');
Route::post('/calculate-nutrition', [NutritionAIController::class, 'calculateNutrition'])->name('calculate.nutrition');
Route::post('/generate-description', [NutritionAIController::class, 'generateDescription'])->name('generate.description');

Route::get('/calculate-nutrition-form', [NutritionAIController::class, 'form'])->name('view.form');

Route::post('/meal-food-nutrition-calculate', [NutritionAIController::class, 'mealFoodNutritionCalculation'])->name('meal.food.nutrition.calculate');

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

Route::group(['middleware' => ['auth:admin', 'admin']], function () {
	Route::get('/woolworths-product-search', [ProductController::class, 'search'])->name('woolworths-product-search');
	Route::post('/add-food', [ProductController::class, 'addFood'])->name('add-food');

	Route::prefix('admin')->group(function () {
		Route::get('/', [AdminAuthController::class, 'index'])->name('dashboard');
		Route::get('/profile/{id}', [AdminAuthController::class, 'profile'])->name('backend.admin-profile');
    	Route::get('/remove-profile-image/{id}', [AdminAuthController::class, 'removeProfileImage'])->name('remove-profile-image');
    	Route::get('/remove-front-logo/{id}', [AdminAuthController::class, 'removeFrontLogo'])->name('remove-front-logo');
    	Route::get('/remove-aboutus-image/{id}', [AdminAuthController::class, 'removeAboutUsImage'])->name('remove-aboutus-image');
    	Route::post('/profile', [AdminAuthController::class, 'profilePost'])->name('profile-post');

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

		Route::as('admin.')->group(function () {
			Route::resource('coupons', CouponController::class);
			Route::resource('sports-categories', SportCategoryController::class);
    		Route::resource('sport-games', SportGameController::class);
		});

		Route::get('/site-settings/{slug}', [SiteSettingsController::class, 'index'])->name('site-settings');
		Route::post('/site-settings-save', [SiteSettingsController::class, 'saveSiteSettings'])->name('save-site-settings');

		Route::get('/tags', [TagController::class, 'index'])->name('admin.tags.index');
		Route::get('/tags/create', [TagController::class, 'create'])->name('admin.tags.create');
		Route::post('/tags', [TagController::class, 'store'])->name('admin.tags.store');
		Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('admin.tags.edit');
		Route::PUT('/tags/{tag}', [TagController::class, 'update'])->name('admin.tags.update');
		Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('admin.tags.destroy');

		Route::get('/flags', [FlagController::class, 'index'])->name('admin.flags.index');
		Route::get('/flags/create', [FlagController::class, 'create'])->name('admin.flags.create');
		Route::post('/flags', [FlagController::class, 'store'])->name('admin.flags.store');
		Route::get('/flags/{flag}/edit', [FlagController::class, 'edit'])->name('admin.flags.edit');
		Route::PUT('/flags/{flag}', [FlagController::class, 'update'])->name('admin.flags.update');
		Route::delete('/flags/{flag}', [FlagController::class, 'destroy'])->name('admin.flags.destroy');
		Route::delete('/admin/flags/{flag}/remove-food/{food}', [FlagController::class, 'removeFood'])->name('admin.flags.removeFood');
		Route::post('/admin/flags/{flag}/add-foods', [FlagController::class, 'addFoods'])->name('admin.flags.addFoods');
		Route::get('/flags/food/list', [FlagController::class, 'foodLists'])->name('admin.flags.foodList');

		// Route::resource('pages', PageController::class);
		Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
		Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
		Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
		Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
		Route::PUT('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
		Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

		Route::get('pages/{page}/sections', [SectionController::class, 'index'])->name('sections.index');

		// Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
		Route::get('pages/{page}/sections/create', [SectionController::class, 'create'])->name('sections.create');
		Route::post('/sections/store', [SectionController::class, 'store'])->name('sections.store');
		// Route::get('/sections/{section}', [SectionController::class, 'show'])->name('sections.show');
		Route::get('pages/{page}/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
		Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
		Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
		Route::post('/sections/reorder', [SectionController::class, 'reorder'])->name('sections.reorder');
		Route::get('/sections/used-types', [SectionController::class, 'getUsedSectionTypes'])->name('sections.used-types');

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
    	Route::get('sub-categories/{subcategory}/edit', [SubCategoryController::class, 'edit'])->name('admin.subcategories.edit');
    	Route::put('sub-categories/{subcategory}', [SubCategoryController::class, 'update'])->name('admin.subcategories.update');
    	Route::delete('sub-categories/{subcategory}', [SubCategoryController::class, 'destroy'])->name('admin.subcategories.destroy');

		// Items
		Route::get('items', [ItemController::class, 'index'])->name('admin.items.index');
		Route::get('items/create', [ItemController::class, 'create'])->name('admin.items.create');
		Route::post('items', [ItemController::class, 'store'])->name('admin.items.store');
		Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
		Route::put('items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
		Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
		Route::get('get-food-details', [ItemController::class, 'getFoodDetails'])->name('admin.get-food-details');
		Route::get('get-food-details-batch', [ItemController::class, 'getFoodDetailsBatch'])->name('admin.get-food-details-batch');

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
		Route::post('update-meal-name', [MealController::class, 'updateMealName'])->name('admin.update-meal-name');
		Route::post('meals/generate-image', [MealController::class, 'generateImage'])->name('admin.meals.generate-image');
		Route::post('meals/edit-image', [MealController::class, 'editImage'])->name('admin.meals.edit-image');
		Route::get('meals/import/form', [MealController::class, 'viewImport'])->name('admin.meals.import-view');
		Route::post('/meals/import', [MealController::class, 'import'])->name('admin.meals.import');

		Route::get('/purchase-plans', [PurchasePlanController::class, 'index'])->name('admin.purchase-plans.index');
		Route::get('/purchase-plans/{id}/create', [PurchasePlanController::class, 'create'])->name('admin.purchase-plans.create');
		Route::post('/purchase-plans', [PurchasePlanController::class, 'store'])->name('admin.purchase-plans.store');
		Route::get('/purchase-plans/{user}/edit/{plan}', [PurchasePlanController::class, 'edit'])->name('admin.purchase-plans.edit');
		Route::put('/purchase-plans', [PurchasePlanController::class, 'update'])->name('admin.purchase-plans.update');
		Route::get('/pre-plan-details/{id}', [PurchasePlanController::class, 'getPrePlanDetails'])->name('admin.pre-plan-details');
		Route::post('/handle-plan-action', [PurchasePlanController::class, 'handlePlanAction'])->name('admin.handle-plan-action');
		Route::post('/update-nutrition-flag', [PurchasePlanController::class, 'updateNutritionFalg'])->name('admin.update-nutrition-flag');

		Route::post('/get-meal-items', [PurchasePlanController::class, 'getMealItems'])->name('admin.get-meal-items');
		Route::post('/get-meal-items-batch', [PurchasePlanController::class, 'getMealItemsBatch'])->name('admin.get-meal-items-batch');
		Route::post('/get-meals-by-mealtime', [PurchasePlanController::class, 'getMealsByMealTime'])->name('admin.get-meals-by-mealtime');
		Route::post('/get-meals-by-mealtime-batch', [PurchasePlanController::class, 'getMealsByMealTimeBatch'])->name('admin.get-meals-by-mealtime-batch');
		Route::post('/admin/remove-user-meal', [PurchasePlanController::class, 'removeUserMeal'])->name('admin.remove-user-meal');

		Route::get('/get-items', [PurchasePlanController::class, 'getItems'])->name('admin.get-items');
		Route::post('/get-swap-items', [PurchasePlanController::class, 'getSwapItems'])->name('admin.get-swap-items');
		Route::post('/add-food', [PurchasePlanController::class, 'addFood'])->name('admin.add-food');
		Route::post('/save-swap-food', [PurchasePlanController::class, 'saveSwapFood'])->name('admin.save-swap-food');
		Route::post('/get-swap-foods', [PurchasePlanController::class, 'getSwapFoods'])->name('admin.get-swap-foods');
		Route::post('/update-swap-foods', [PurchasePlanController::class, 'updateFoodSwapFoods'])->name('admin.update-food-swap-foods');
		Route::post('/delete-purchase-plan-food', [PurchasePlanController::class, 'deletePurchasePlanFood'])->name('admin.delete-purchase-plan-food');
		Route::post('/update-swap-item', [PurchasePlanController::class, 'updateSwapItem'])->name('admin.update-swap-item');
		Route::get('/admin/user/details', [UserController::class, 'getUserDetails'])->name('admin.user.details');

		// Athlete plan meal food delete
		Route::post('delete-food-item', [PurchasePlanController::class, 'deleteUserMealFood'])->name('admin.delete-user-meal-food');
		Route::post('delete-swap-food-item', [PurchasePlanController::class, 'deleteUserMealSwapFood'])->name('admin.delete-user-meal-swap-food');

		// User routes
		Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
		Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

		// Quiz
		Route::get('/quiz', [QuizController::class, 'index'])->name('admin.quiz.index');
		Route::post('/quiz', [QuizController::class, 'store'])->name('admin.quiz.store');

	});
});

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/blog', [FrontController::class, 'blog'])->name('front.blog');
Route::get('blog/{id}', [FrontController::class, 'blogDetails'])->name('front.blog.detail');
Route::POST('/save-query', [FrontController::class, 'save'])->name('save-query');
Route::get('/action-sport-nutrition-plan', [FrontController::class, 'subHomePage'])->name('front.sub-home-page');
Route::get('/pre-plan-details', [PaymentController::class, 'prePlanDetails'])->name('front.pre-plan-details');
Route::post('/pre-plan-details-store', [PaymentController::class, 'prePlanDetailsSave'])->name('front.pre-plan-details.store');
Route::post('/questionnaire/send-mail', [PaymentController::class, 'questionnaireSendMail'])->name('front.questionnaire.send-mail');

Route::get('/sample-plan', [FrontController::class, 'samplePlan'])->name('front.sample-plan');
Route::post('/sample-plan-details-update', [FrontController::class, 'updateSamplePlanDetails'])->name('front.sample-plan-details-update');

Route::get('/get-foods/{key}', [FrontController::class, 'getFoodItems'])->name('front.flag.items');

Route::get('/competition-plan/{id}', [FrontController::class, 'getCompetitionPlanDetails'])->name('front.competition-plan-details');
Route::get('/get-meals-items', [FrontController::class, 'getAllMeals'])->name('front.get.meals.items');
Route::get('/get-default-plan-details/{id}', [FrontPlanController::class, 'getDefaultPlanDetails'])->name('front.get-default-plan-details');
Route::get('/training-nutrition-plan', [FrontController::class, 'trainingNutritionPlan'])->name('front.training.nutrition.plan');
Route::get('/competition-plan', [FrontController::class, 'competitionPlan'])->name('front.competition.plan');
Route::get('/injury-recovery-plan', [FrontController::class, 'injuryRecoveryPlan'])->name('front.injury.recovery.plan');

// Front auth
Route::post('front/register', [FrontController::class, 'register'])->name('front.register');
Route::post('front/login', [FrontController::class, 'login'])->name('front.login');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('front.password.request');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('front.password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('front.password.update');
Route::post('front/logout', [FrontController::class, 'logout'])->name('front.logout');

// OTP Registration Flow
Route::post('front/otp/send', [OtpRegistrationController::class, 'sendOtp'])->name('front.otp.send');
Route::post('front/otp/verify', [OtpRegistrationController::class, 'verifyOtp'])->name('front.otp.verify');
Route::post('front/otp/register', [OtpRegistrationController::class, 'completeRegistration'])->name('front.otp.register');
Route::post('front/otp/resend', [OtpRegistrationController::class, 'resendOtp'])->name('front.otp.resend');
Route::get('front/otp/sport-games-age-groups', [OtpRegistrationController::class, 'getSportGamesAndAgeGroups'])->name('front.otp.sport-games-age-groups');
// Debug route for OTP testing (remove in production)
Route::get('front/otp/debug/{mobile}', [OtpRegistrationController::class, 'debugCache'])->name('front.otp.debug');

// GET route fallback for expired session
Route::get('front/logout-guest', function () {
    return redirect()->route('front.index')->with('info', 'Your session has expired. Please log in again.');
})->name('front.logout.guest');

//Stripe payment
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::post('/free-test-save', [FrontController::class, 'freeTestSave'])->name('front.submit-free-test');
Route::post('/validate-coupon-code', [FrontController::class, 'validateCouponCode'])->name('validate.coupon.code');

Route::get('/get-sports-games', [FrontController::class, 'getSportsGames'])->name('front.get-sports-games');
Route::post('/sport-search', [FrontController::class, 'sportSearch'])->name('front.sport-search');

Route::post('/query', [FrontController::class, 'submitQuery'])->name('front.submit-query');

Route::get('/overseas_travel_nutrition_plan', function () {
	return view('front.overseas_travel_nutrition_plan');
})->name('front.overseas_travel_nutrition_plan');

// Plans
Route::group(['middleware' => 'auth'], function () {
	// Route::get('/plans/{id}', [FrontPlanController::class, 'show'])->name('front.plans.details');
	Route::get('/plans/{id}/details/{user_id}', [FrontPlanController::class, 'show'])->name('front.plans.details');
	Route::get('/meal-time/{id}/{plan_id}', [FrontPlanController::class, 'mealTimeDetails'])->name('front.meal-time.details');
	Route::post('/get-meals', [FrontPlanController::class, 'getMealByMealTimes'])->name('front.get-meals');

	//categories
	Route::get('/category/{id}/meals', [FrontPlanController::class, 'getMeals'])->name('front.category.meals');
	// Route::get('/category/{id}/subcategories', [FrontPlanController::class, 'getSubCategories'])->name('front.category.subcategories');
	// Route::get('/subcategory/{id}/items', [FrontPlanController::class, 'getSubcategoryItems'])->name('front.subcategories.items');
	Route::get('/meal/{id}/items', [FrontPlanController::class, 'getMealItems'])->name('front.meals.items');
	Route::get('/item/{id}/swap-items', [FrontPlanController::class, 'getSwapItems'])->name('front.items.swap-items');

	Route::get('item/swap', [FrontPlanController::class, 'applySwaps'])->name('front.items.swaps');
	// Route::get('/plans/{id}/print', [FrontPlanController::class, 'generatePdf'])->name('plans.generatePdf');
	Route::post('/plans/{id}/print', [FrontPlanController::class, 'generatePdf'])->name('plans.generatePdf');
	Route::get('/plans/preview/{id}', [FrontPlanController::class, 'preview'])->name('plans.preview');
	Route::post('/plans/preview/', [FrontPlanController::class, 'planPreview'])->name('front.plans.preview');

	// TODO : New Design profile-landing page Route//
	Route::get('/profile-landing/{id}', [FrontController::class, 'getProfile'])->name('front.profile');
	Route::get('/profile/{id}', [FrontController::class, 'getProfileDetails'])->name('front.profile-old');
	Route::post('/profile/update', [FrontController::class, 'updateProfile'])->name('front.profile.update');
	Route::post('/food/quantity/update', [FrontController::class, 'updateFoodQuantity'])->name('front.food-quantity-update');

	Route::post('/save-weight', [FrontController::class, 'saveWeight'])->name('front.save.weight');
    Route::get('/fetch-weights', [FrontController::class, 'fetchWeights'])->name('front.fetch.weights');
	Route::get('/fetch/weight/data', [FrontController::class, 'fetchWeightData'])->name('front.fetch.weight.data');

	Route::post('/update-goal', [FrontController::class, 'updateGoals'])->name('front.update.goal');
    Route::post('/past-goals', [FrontController::class, 'getPastGoals'])->name('front.past.goals');

	Route::post('/upload-report', [FrontController::class, 'uploadReport'])->name('front.upload.report');
	Route::post('/delete-report', [FrontController::class, 'deleteReport'])->name('front.delete.report');

	Route::get('/user/{user}/plan/{plan}/meals', [FrontPlanController::class, 'ajaxGetMeals'])->name('user.plan.meals');
	Route::post('/track/click', [FrontPlanController::class, 'trackClick'])->name('front.track.click');
	Route::get('/get-meals/{plan}/{category}', [FrontController::class, 'getMeals'])->name('front.get-profile-meals');
	Route::post('/meal-details', [FrontPlanController::class, 'getMealDetails'])->name('front.meal.details');
	Route::post('/meal-smart-swaps', [FrontPlanController::class, 'getMealSmartSwaps'])->name('front.meal.smart.swaps');

	// My Plans page - accessible only to authenticated users
	Route::get('/my-plans', [FrontController::class, 'myPlans'])->name('front.my-plans');

});
Route::get('/set-user-session/{id}', [FrontController::class, 'setUserSession'])->name('front.set-user-session');

Route::post('/google/check-login', [FrontController::class, 'checkGoogleLogin'])->name('front.google.check-login');
Route::post('/unlock-result', [FrontController::class, 'unlockFreeTestResult'])->name('front.unlock-result');
// Quiz tracking routes
Route::post('/track-quiz-click', [FrontController::class, 'trackQuizClick'])->name('front.track.quiz.click');
Route::post('/track-quiz-progress', [FrontController::class, 'trackQuizProgress'])->name('front.track.quiz.progress');
Route::post('/track-quiz-completion', [FrontController::class, 'trackQuizCompletion'])->name('front.track.quiz.completion');

// Quiz Routes
Route::prefix('quiz')->group(function () {
    Route::post('/start', [FrontQuizController::class, 'startQuiz'])->name('front.quiz.start');
    Route::post('/save-step', [FrontQuizController::class, 'saveStep'])->name('front.quiz.save-step');
    Route::post('/complete', [FrontQuizController::class, 'completeQuiz'])->name('front.quiz.complete');
    Route::post('/abandon', [FrontQuizController::class, 'abandonQuiz'])->name('front.quiz.abandon');
    Route::post('/nutrition-score', [FrontQuizController::class, 'getNutritionScore'])->name('front.quiz.nutrition-score');
});

Route::get('/about-us', [FrontController::class, 'aboutUs'])->name('front.about-us');