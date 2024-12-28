<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Meal;
use App\Models\SubCategory;
use App\Models\MealTime;
use App\Models\Category;

class PaymentController extends Controller
{
    // List all payments with pagination
    public function index()
    {
        // Fetch payments with pagination (you can adjust per page as needed)
        $payments = Payment::with('plan')->paginate(10);

        // Return the view with the payments data
        return view('backend.pages.plan.purchase-plans', compact('payments'));
    }

    // Show a specific payment
    public function edit($id)
    {
        $plan = Plan::with([
            'mealTimes.categories.subcategories.meals.items'
        ])->findOrFail($id);

        // Get all options for each relationship (mealTimes, categories, etc.)
        $mealTimes = MealTime::all();
        $categories = Category::all();
        $subcategories = SubCategory::all();
        $meals = Meal::all();
        $items = Item::all();


        return view('backend.pages.plan.purchase-plan-edit', compact(
            'plan', 'mealTimes', 'categories', 'subcategories', 'meals', 'items',
        ));
    }


    // Handle AJAX request to get categories by meal time
    public function getCategoriesByMealTime($mealTimeId)
    {
        $mealTime = MealTime::find($mealTimeId);
        $categories = $mealTime->categories; // Get categories related to the selected meal time

        return response()->json($categories);
    }

    // Handle AJAX request to get subcategories by category
    public function getSubcategoriesByCategory($categoryId)
    {
        $category = Category::find($categoryId);
        $subcategories = $category->subcategories; // Get subcategories related to the selected category

        return response()->json($subcategories);
    }

    // Handle AJAX request to get meals by subcategory
    public function getMealsBySubcategory($subcategoryId)
    {
        $subcategory = SubCategory::find($subcategoryId);
        $meals = $subcategory->meals; // Get meals related to the selected subcategory

        return response()->json($meals);
    }

    // Handle AJAX request to get items by meal
    public function getItemsByMeal($mealId)
    {
        $meal = Meal::find($mealId);
        $items = $meal->items; // Get items related to the selected meal

        return response()->json($items);
    }
}
