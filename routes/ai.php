<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\NutritionAIController;

Route::get('/chat', fn() => view('image_form'));
Route::post('/chat', [ImageController::class, 'chat'])->name('chat');
Route::get('/generate-image', [ImageController::class, 'generateImageForm']);
Route::post('/generate-image', [ImageController::class, 'generateImage'])->name('generate-image');

Route::post('/nutrition-calculation', [NutritionAIController::class, 'nutritionCalculation'])->name('nutrition.calculate');
Route::post('/calculate-nutrition', [NutritionAIController::class, 'calculateNutrition'])->name('calculate.nutrition');
Route::post('/generate-description', [NutritionAIController::class, 'generateDescription'])->name('generate.description');
Route::get('/calculate-nutrition-form', [NutritionAIController::class, 'form'])->name('view.form');
Route::post('/meal-food-nutrition-calculate', [NutritionAIController::class, 'mealFoodNutritionCalculation'])->name('meal.food.nutrition.calculate');