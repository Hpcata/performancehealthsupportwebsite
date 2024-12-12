<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Meal;
use App\Models\MealTime;
use App\Models\SubCategory;
use App\Models\Item;
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
                    ? asset('private/public/storage/' . $meal->image)
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
                'image' => $item->image ? asset('private/public/storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image'
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
                'id' => $item->id,
                'name' => $item->title,
                'price' => $item->price,
                'description' => $item->description,
                'image' => $item->image
                    ? asset('private/public/storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        // Return the response
        return response()->json(['meal' => $meal->title, 'items' => $items]);
    }
    
    public function getSwapItems($id)
    {
        // Fetch the swap with its items
        $item = Item::with('swapItems')->findOrFail($id);
        // Map the items into a response-friendly structure
        $items = $item->swapItems->map(function ($swapItem) {
            return [
                'swap_item_id' => $swapItem->id,
                'swap_item_name' => $swapItem->title,
                'swap_item_price' => $swapItem->price,
                'swap_item_description' => $swapItem->description,
                'swap_item_image' => $swapItem->image
                    ? asset('private/public/storage/' . $swapItem->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

       $item_image = $item->image ? asset('private/public/storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image';
        // Return the response
        return response()->json(['item_id' => $item->id,'item_name' => $item->title, 'item_image' => $item_image, 'items' => $items]);
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
                    ? asset('private/public/storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        // Return the response
        return response()->json(['subcategory' => $subcategory->title, 'items' => $items]);
    }
    
    public function applySwaps(Request $request)
    {
        $swaps = $request->input('swaps');
        $mealId = $request->input('meal_id');
        // dd($swaps);
        // Validate request inputs
        $meal = Meal::findOrFail($mealId);
        $mealName = $meal->title;
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'swaps' => 'required|array',
            'swaps.*.swap_id' => 'required|exists:items,id',
            'swaps.*.main_id' => 'required|exists:items,id',
        ]);

        try {
            foreach ($swaps as $swap) {
                // Check if the swapped item exists in the meal and remove the existing item
                $existingItem = \DB::table('item_meals')
                    ->where('item_id', $swap['swap_id'])
                    ->where('meal_id', $mealId)
                    ->first();

                if ($existingItem) {

                    // Remove the existing item from the meal
                    \DB::table('item_meals')
                        ->where('item_id', $swap['swap_id'])
                        ->where('meal_id', $mealId)
                        ->delete(); // This will remove the existing item

                    // Add the new swapped item to the meal
                    \DB::table('item_meals')
                        ->insert([
                            'meal_id' => $mealId,
                            'item_id' => $swap['main_id'],
                        ]);
                    \DB::table('items')
                        ->where('id', $swap['swap_id'])
                        ->update(['is_swiped' => 1]);
                    
                    \DB::table('items')
                        ->where('id', $swap['main_id'])
                        ->update(['is_swiped' => 0]);

                } else {
                    // If no matching item found to swap, return an error
                    return response()->json([
                        'success' => false,
                        'data' => [
                            'meal_id' => $mealId,
                            'mela_name' => $mealName
                        ], 
                        'message' => 'Item to swap not found in the meal for swap_id ' . $swap['swap_id'],
                    ]);
                }
            }

            // If all swaps are applied successfully
            return response()->json([
                        'success' => true, 
                        'data' => [
                            'meal_id' => $mealId,
                            'mela_name' => $mealName
                        ],
                        'message' => 'All swaps applied successfully!']);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply swaps. Error: ' . $e->getMessage(),
            ]);
        }
    }




}
