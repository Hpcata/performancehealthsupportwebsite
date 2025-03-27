<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Item;
use App\Models\UserItemMeal;
use App\Models\SubCategory; // Import SubCategory model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Meal::with('categories', 'items');
    
            // Apply search filter if a search term is provided
            if ($request->has('search') && !empty($request->search)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('title', 'LIKE', '%' . $request->search . '%'); // Search by category name
                });
            }
    
            // Get filtered meals
            $meals = $query->select('id', 'title as name')->get();
    
            return response()->json([
                'success' => true,
                'meals' => $meals
            ]);
        }
        $meals = Meal::with('categories','items')->get(); // Eager load subCategories
        return view('backend.pages.meal.index', compact('meals'));
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all subcategories
        $foods = Item::all();
        return view('backend.pages.meal.form', compact('categories','foods'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id', // Validate subcategory IDs
            'food_ids' => 'nullable|array', // Ensure food items are selected
            'food_ids.*' => 'integer|exists:items,id', // Ensure food items exist
            'food_qty' => 'nullable|array',
            'food_qty.*' => 'string|max:50',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal = Meal::create($data);

        if (!empty($request->food_ids)) {
            // Attach food items with their respective text-based quantities
            $foodItems = [];
            foreach ($request->food_ids as $index => $foodId) {
                $foodItems[$foodId] = ['item_qty' => $request->food_qty[$index] ?? '']; 
            }
            $meal->items()->sync($foodItems);
        }

        // Get all unique user IDs
        $userIds = UserItemMeal::getUniqueUserIds();
        
        if ($userIds->isNotEmpty()) {
            foreach ($userIds as $userId) {
                // Check if the user has an active plan
                $hasActivePlan = \DB::table('user_plans')
                    ->where('user_id', $userId)
                    ->where('status', 'active') // Assuming 'status' indicates if the plan is active
                    ->exists();
    
                // Only proceed if the user has an active plan
                if ($hasActivePlan) {
                    foreach ($request->food_ids as $index => $foodId) {
                        $exists = UserItemMeal::where('user_id', $userId)
                            ->where('meal_id', $meal->id)
                            ->where('item_id', $foodId)
                            ->exists();
        
                        $item = Item::find($foodId);
                        if (!$exists) {
                            UserItemMeal::create([
                                'user_id' => $userId,
                                'item_id' => $foodId,
                                'meal_id' => $meal->id,
                                'qty' => $request->food_qty[$index] ?? '',
                                'is_swiped' => isset($item->is_swiped) ? $item->is_swiped : 0,
                            ]);
                        }

                    }
                }
            }
        }

        if ($request->has('categories')) {
            $meal->categories()->sync($request->categories); // Sync subcategories
        }

        return redirect()->route('admin.meals.index')->with('success', 'Meal created successfully.');
    }

    public function edit(Meal $meal)
    {
        $categories = Category::all(); // Fetch all subcategories
        $foods = Item::all();
        // $foods = Item::where('is_swiped',0)->get();
        return view('backend.pages.meal.form', compact('meal', 'categories','foods'));
    }

    public function update(Request $request, Meal $meal)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id', // Validate subcategory IDs
            'food_ids' => 'nullable|array', // Ensure food items are selected
            'food_ids.*' => 'integer|exists:items,id', // Ensure food items exist
            'food_qty' => 'nullable|array',
            'food_qty.*' => 'string|max:50',
        ]);
        // dd($request->all());
        if ($request->hasFile('image')) {
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        }
        // $userIds = UserItemMeal::getUniqueUserIds();
        // foreach ($userIds as $userId) {
        //     dd($userId);
        // }
        
        $meal->update($data);

        // ✅ Clear old food items before adding new ones to prevent duplicates
        $meal->items()->detach();

        // ✅ Sync food items with quantities in the pivot table
        if ($request->has('food_ids') && !empty($request->food_ids)) {
            $foodItems = [];
            foreach ($request->food_ids as $index => $foodId) {
                $foodItems[$foodId] = ['item_qty' => $request->food_qty[$index]];
            }
            $meal->items()->sync($foodItems);
        }

        if ($request->has('food_ids')) {
            $userIds = UserItemMeal::getUniqueUserIds();
    
            if ($userIds->isNotEmpty()) {
                foreach ($userIds as $userId) {
                    // Check if the user has an active plan
                    $hasActivePlan = \DB::table('user_plans')
                        ->where('user_id', $userId)
                        ->where('status', 'active') // Assuming 'status' indicates if the plan is active
                        ->exists();
        
                    // Only proceed if the user has an active plan
                    if ($hasActivePlan) {
                        foreach ($request->food_ids as $index => $foodId) {
                            $exists = UserItemMeal::where('user_id', $userId)
                                ->where('meal_id', $meal->id)
                                ->where('item_id', $foodId)
                                ->exists();
                            $item = Item::find($foodId);
                            if (!$exists) {
                                UserItemMeal::create([
                                    'user_id' => $userId,
                                    'item_id' => $foodId,
                                    'meal_id' => $meal->id,
                                    'qty' => $request->food_qty[$index] ?? '',
                                    'is_swiped' => isset($item->is_swiped) ? $item->is_swiped : 0,
                                ]);
                            }
                        }
                    }
                }
            }
            // $meal->items()->sync($request->food_ids);
        }

        if ($request->has('categories')) {
            $meal->categories()->sync($request->categories); // Sync subcategories
        }

        return redirect()->route('admin.meals.index')->with('success', 'Meal updated successfully.');
    }

    public function destroy(Meal $meal)
    {
        if ($meal->image) {
            Storage::disk('public')->delete($meal->image);
        }

        $meal->delete();

        return redirect()->route('admin.meals.index')->with('success', 'Meal deleted successfully.');
    }

    public function updateMealName(Request $request)
    {
        $validated = $request->validate([
            'meal_id' => 'required|integer',
            'plan_id' => 'required|integer',
            'user_id' => 'required|integer',
            'meal_time_id' => 'required|integer',
            // 'category_id' => 'required|integer',
            'meal_name' => 'required|string|max:255',
        ]);
        
        $userPlan = \App\Models\UserPlan::where('user_id', $validated['user_id'])->where('plan_id', $validated['plan_id'])->first();
        // dd($userPlan);
        $userMealTime = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
                            ->where('meal_time_id', $validated['meal_time_id'])->first();
        
        $meal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTime->id)
                        ->where('meal_id', $validated['meal_id'])
                        ->where('user_plan_id', $userPlan->id)
                        ->where('user_meal_time_id', $userMealTime->id)
                        // ->where('user_category_id', $validated['category_id'])
                        ->first();
    
        if ($meal) {
            $meal->update(['meal_name' => $validated['meal_name']]);
            return response()->json(['success' => true, 'message' => 'Meal name updated successfully.']);
        }
    
        return response()->json(['success' => false, 'message' => 'Meal not found.']);
    }
}
