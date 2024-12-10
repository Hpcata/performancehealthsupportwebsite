<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Meal;
use App\Models\MealTime;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function show($id)
    {
        $plan = Plan::with('mealTimes')->findOrFail($id);
        return view('front.plan-details', compact('plan'));
    }

    public function mealTimeDetails($id)
    {
        $mealtime = MealTime::with('categories','categories.subcategories')->findOrFail($id);
        return view('front.break_fast', compact('mealtime'));
    }

    public function getMeals($id)
    {
        $subcategory = SubCategory::with('meals')->findOrFail($id);
        $meals = $subcategory->meals->map(function ($meal) {
            return [
                'id' => $meal->id,
                'name' => $meal->title,
                'description' => $meal->description,
                'image' => $meal->image
                    ? asset('storage/' . $meal->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });
        return response()->json(['meals' => $meals]);
    }

    public function getSubCategories($id)
    {
        $category = Category::with('subCategories')->findOrFail($id);

        $items = $category->subCategories->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->title,
                'description' => $item->description,
                'image' => $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image'
            ];
        });

        return response()->json(['subcategories' => $items]);
    }

    public function getMealItems($id)
    {
        // Fetch the meal with its items
        $meal = Meal::with('items')->findOrFail($id);
        // Map the items into a response-friendly structure
        $items = $meal->items->map(function ($item) {
            return [
                'name' => $item->title,
                'price' => $item->price,
                'description' => $item->description,
                'image' => $item->image
                    ? asset('storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        // Return the response
        return response()->json(['meal' => $meal->title, 'items' => $items]);
    }
    
    public function getSubcategoryItems($id)
    {
        // Fetch the subcategory with its items
        $subcategory = SubCategory::with('items')->findOrFail($id);
        // Map the items into a response-friendly structure
        $items = $subcategory->items->map(function ($item) {
            return [
                'name' => $item->title,
                'price' => $item->price,
                'description' => $item->description,
                'image' => $item->image
                    ? asset('storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        // Return the response
        return response()->json(['subcategory' => $subcategory->title, 'items' => $items]);
    }

}
