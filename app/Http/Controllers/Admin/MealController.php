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
    public function index()
    {
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
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal = Meal::create($data);

        $meal->items()->sync($request->food_ids);

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
                    foreach ($request->food_ids as $foodId) {
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
                        foreach ($request->food_ids as $foodId) {
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
                                    'is_swiped' => isset($item->is_swiped) ? $item->is_swiped : 0,
                                ]);
                            }
                        }
                    }
                }
            }
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
}
