<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\ForgotPasswordController;
use App\Http\Controllers\Front\PlanController as FrontPlanController;
use App\Http\Controllers\Front\QuizController as FrontQuizController;

// Front
Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index')->name('front.index');
    Route::get('/blog', 'blog')->name('front.blog');
    Route::get('/blog/{id}', 'blogDetails')->name('front.blog.detail');
    Route::post('/save-query', 'save')->name('front.save-query');
    Route::get('/action-sport-nutrition-plan', 'subHomePage')->name('front.sub-home-page');
    Route::get('/sample-plan', 'samplePlan')->name('front.sample-plan');
    Route::post('/sample-plan-details-update', 'updateSamplePlanDetails')->name('front.sample-plan-details-update');
    Route::get('/get-foods/{key}', 'getFoodItems')->name('front.flag.items');
    Route::get('/competition-plan/{id}', 'getCompetitionPlanDetails')->name('front.competition-plan-details');
    Route::get('/get-meals-items', 'getAllMeals')->name('front.get.meals.items');
    Route::post('front/register', 'register')->name('front.register');
    Route::post('front/login', 'login')->name('front.login');
    Route::post('front/logout', 'logout')->name('front.logout');
    Route::get('front/logout-guest', function () {
        return redirect()->route('front.index')->with('info', 'Your session has expired. Please log in again.');
    })->name('front.logout.guest');
    Route::post('/free-test-save', 'freeTestSave')->name('front.submit-free-test');
    Route::post('/validate-coupon-code', 'validateCouponCode')->name('front.validate.coupon.code');
    Route::get('/get-sports-games', 'getSportsGames')->name('front.get-sports-games');
    Route::post('/sport-search', 'sportSearch')->name('front.sport-search');
    Route::post('/query', 'submitQuery')->name('front.submit-query');
    Route::get('/overseas_travel_nutrition_plan', fn () => view('front.overseas_travel_nutrition_plan'))->name('front.overseas_travel_nutrition_plan');
    Route::get('/profile/{id}', 'getProfileDetails')->name('front.profile');
    Route::post('/profile/update', 'updateProfile')->name('front.profile.update');
    Route::post('/food/quantity/update', 'updateFoodQuantity')->name('front.food-quantity-update');
    Route::post('/save-weight', 'saveWeight')->name('front.save.weight');
    Route::get('/fetch-weights', 'fetchWeights')->name('front.fetch.weights');
    Route::get('/fetch/weight/data', 'fetchWeightData')->name('front.fetch.weight.data');
    Route::post('/update-goal', 'updateGoals')->name('front.update.goal');
    Route::post('/past-goals', 'getPastGoals')->name('front.past.goals');
    Route::post('/upload-report', 'uploadReport')->name('front.upload.report');
    Route::post('/delete-report', 'deleteReport')->name('front.delete.report');
    Route::get('/set-user-session/{id}', 'setUserSession')->name('front.set-user-session');
    Route::post('/google/check-login', 'checkGoogleLogin')->name('front.google.check-login');
    Route::post('/unlock-result', 'unlockFreeTestResult')->name('front.unlock-result');
    Route::post('/track-quiz-click', 'trackQuizClick')->name('front.track.quiz.click');
    Route::post('/track-quiz-progress', 'trackQuizProgress')->name('front.track.quiz.progress');
    Route::post('/track-quiz-completion', 'trackQuizCompletion')->name('front.track.quiz.completion');
});

// Payment
Route::controller(PaymentController::class)->group(function () {
    Route::get('/pre-plan-details', 'prePlanDetails')->name('front.pre-plan-details');
    Route::post('/pre-plan-details-store', 'prePlanDetailsSave')->name('front.pre-plan-details.store');
    Route::post('/questionnaire/send-mail', 'questionnaireSendMail')->name('front.questionnaire.send-mail');
    Route::post('/process-payment', 'processPayment')->name('front.process.payment');
    Route::get('/payment-success', 'paymentSuccess')->name('front.payment.success');
    Route::get('/get-race-ethnicity-culture-options', 'getRaceEthnicityCultureOptions')->name('front.get-race-ethnicity-culture-options');
});

// Forgot Password
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::post('password/email', 'sendResetLinkEmail')->name('front.password.request');
    Route::get('password/reset/{token}', 'showResetForm')->name('front.password.reset');
    Route::post('password/reset', 'reset')->name('front.password.update');
});

// Plans
Route::middleware(['auth'])->group(function () {
    Route::controller(FrontPlanController::class)->group(function () {
        Route::get('/plans/{id}/details/{user_id}', 'show')->name('front.plans.details');
        Route::get('/meal-time/{id}/{plan_id}', 'mealTimeDetails')->name('front.meal-time.details');
        Route::post('/get-meals', 'getMealByMealTimes')->name('front.get-meals');
        Route::get('/category/{id}/meals', 'getMeals')->name('front.category.meals');
        Route::get('/meal/{id}/items', 'getMealItems')->name('front.meals.items');
        Route::get('/item/{id}/swap-items', 'getSwapItems')->name('front.items.swap-items');
        Route::get('/item/swap', 'applySwaps')->name('front.items.swaps');
        Route::post('/plans/{id}/print', 'generatePdf')->name('front.plans.generatePdf');
        Route::get('/plans/preview/{id}', 'preview')->name('front.plans.preview');
        Route::post('/plans/preview', 'planPreview')->name('front.plans.preview.submit');
        Route::get('/user/{user}/plan/{plan}/meals', 'ajaxGetMeals')->name('front.user.plan.meals');
        Route::get('/get-default-plan-details/{id}', 'getDefaultPlanDetails')->name('front.get-default-plan-details');
    });
});

// Quiz
Route::prefix('quiz')->controller(FrontQuizController::class)->group(function () {
    Route::post('/start', 'startQuiz')->name('front.quiz.start');
    Route::post('/save-step', 'saveStep')->name('front.quiz.save-step');
    Route::post('/complete', 'completeQuiz')->name('front.quiz.complete');
    Route::post('/abandon', 'abandonQuiz')->name('front.quiz.abandon');
});
