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
use GuzzleHttp\Client;
use App\Models\Tag;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MealsImport;
use Psy\Readline\Hoa\Console;

class MealController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Meal::with('subCategories', 'items');

            // Apply search filter if a search term is provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    // Search in Meal title
                    $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                    // Or in Subcategory title
                    ->orWhereHas('subCategories', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('sub_categories.title', 'LIKE', '%' . $searchTerm . '%');
                    });
                });
            }

            // Apply category filter if selected
            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->whereHas('subCategories', function ($q) use ($request) {
                    $q->where('sub_categories.id', $request->category_id);
                });
            }

            $meals = $query->get();

            return response()->json([
                'success' => true,
                'meals' => $meals
            ]);
        }


        $subCategories = SubCategory::all(); // Fetch categories for dropdown
        // $meals = Meal::with('categories','items')->get(); // Eager load subCategories
        return view('backend.pages.meal.index', compact('subCategories'));
    }

    public function create()
    {
        $subCategories = SubCategory::all(); // Fetch all subcategories
        $foods = Item::all();
        $tags = Tag::all();
        $categories = \App\Models\Category::orderBy('order', 'asc')->get();

        return view('backend.pages.meal.form', compact('subCategories','foods', 'tags', 'categories'));
    }
    
    public function store(Request $request)
    {
        try {

       
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'categories' => 'nullable|array',
            // 'categories.*' => 'exists:categories,id',
            'food_ids' => 'nullable|array',
            'food_ids.*' => 'integer|exists:items,id',
            'note' => 'nullable'
        ],[
            'title.required' => 'The meal title is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be larger than 2MB.',
        ]);
        // dd($request->all());
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        } elseif ($request->filled('generated_image')) {
            $imageUrl = $request->generated_image;
            $imageContents = file_get_contents($imageUrl);
            $imageName = 'meals/' . uniqid() . '.jpg';
    
            Storage::disk('public')->put($imageName, $imageContents);
            $data['image'] = $imageName;
        }
    
        $meal = Meal::create($data);
        $meal->tags()->sync($request->input('tag_ids')); // attaches tags via pivot

        $selectedQtyUnitsArray = $request->selected_qty_unit;
    
        if ($request->has('food_ids') && !empty($request->food_ids)) {
            $foodItems = [];
    
            foreach ($request->food_ids as $index => $foodId) {
                $selectedQtyUnitRaw = $selectedQtyUnitsArray[$index];
                $decodedQtyUnits = json_decode($selectedQtyUnitRaw, true);
    
                if (empty($decodedQtyUnits)) {
                    $item = \App\Models\Item::find($foodId);
                    if ($item) {
                        $decodedQtyUnits = [[
                            'qty' => $item->qty ?? '',
                            'unit' => $item->unit ?? '',
                            'checked' => 'true'
                        ]];
                        $selectedQtyUnitsArray[$index] = json_encode($decodedQtyUnits);
                    }
                }
    
                $firstQty = '';
                $firstUnit = '';
    
                if (is_array($decodedQtyUnits) && count($decodedQtyUnits) > 0) {
                    $firstQty = $decodedQtyUnits[0]['qty'] ?? '';
                    $firstUnit = $decodedQtyUnits[0]['unit'] ?? '';
                }
    
                $foodItems[$foodId] = [
                    'item_qty' => $firstQty,
                    'item_qty_unit' => $firstUnit,
                    'protein' => $request->protein[$index] ?? '0',
                    'carbs' => $request->carbs[$index] ?? '0',
                    'fat' => $request->fat[$index] ?? '0',
                    'selected_qty_unit' => json_encode($decodedQtyUnits ?? [])
                ];
            }
    
            $meal->items()->sync($foodItems);
        }
    
        if ($request->has('categories')) {
            $meal->subCategories()->sync($request->categories);
        }
        
        if ($request->has('meal_times')) {
            $meal->categories()->sync($request->meal_times); // Sync subcategories
        }
        
        return redirect()->route('admin.meals.index')->with('success', 'Meal created successfully.');
         } catch(\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
        }
    }
    

    public function edit(Meal $meal)
    {
        $subCategories = SubCategory::all(); // Fetch all subcategories
        $foods = Item::all();
        $tags = Tag::all();
        $categories = \App\Models\Category::orderBy('order', 'asc')->get();

        // $foods = Item::where('is_swiped',0)->get();
        return view('backend.pages.meal.form', compact('meal', 'subCategories','foods', 'tags', 'categories'));
    }

    public function update(Request $request, Meal $meal)
    {
        // dd($request->all());
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:sub_categories,id', // Validate subcategory IDs
            'food_ids' => 'nullable|array', // Ensure food items are selected
            'food_ids.*' => 'integer|exists:items,id', // Ensure food items exist
            'note' => 'nullable'
            // 'food_qty' => 'nullable|array',
            // 'food_qty.*' => 'string|max:50',
            // 'food_qty_unit' => 'nullable|array',
            // 'food_qty_unit.*' => 'string|max:50',
        ],[
            'title.required' => 'The meal title is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be larger than 2MB.',
        ]);
        // dd($request->all());
        if ($request->hasFile('image')) {
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        } elseif ($request->filled('generated_image')) {
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image);
            }
            $imageUrl = $request->generated_image;
            $imageContents = file_get_contents($imageUrl);
            $imageName = 'meals/' . uniqid() . '.jpg';

            Storage::disk('public')->put($imageName, $imageContents);
            $data['image'] = $imageName;
        }
        // $userIds = UserItemMeal::getUniqueUserIds();
        // foreach ($userIds as $userId) {
        //     dd($userId);
        // }
        
        $meal->update($data);
        $meal->tags()->sync($request->input('tag_ids')); // attaches tags via pivot

        // ✅ Clear old food items before adding new ones to prevent duplicates
        $meal->items()->detach();
        $selectedQtyUnitsArray = $request->selected_qty_unit;
        // ✅ Sync food items with quantities in the pivot table
        if ($request->has('food_ids') && !empty($request->food_ids)) {
            $foodItems = [];

            foreach ($request->food_ids as $index => $foodId) {
                $selectedQtyUnitRaw = $selectedQtyUnitsArray[$index];
                $decodedQtyUnits = json_decode($selectedQtyUnitRaw, true);
                if (empty($decodedQtyUnits)) {
                    $item = \App\Models\Item::find($foodId); // Adjust namespace if needed
            
                    if ($item) {
                        $decodedQtyUnits = [[
                            'qty' => $item->qty ?? '',
                            'unit' => $item->unit ?? '',
                            'checked' => 'true' // Optional: Mark it as selected
                        ]];
            
                        // Update the request array with the default value
                        $selectedQtyUnitsArray[$index] = json_encode($decodedQtyUnits);
                    }
                }
                // dd($decodedQtyUnits );
                $firstQty = '';
                $firstUnit = '';
        
                if (is_array($decodedQtyUnits) && count($decodedQtyUnits) > 0) {
                    $firstQty = $decodedQtyUnits[0]['qty'] ?? '';
                    $firstUnit = $decodedQtyUnits[0]['unit'] ?? '';
                }
        
                $foodItems[$foodId] = [
                    'item_qty' => $firstQty,
                    'item_qty_unit' => $firstUnit,
                    'protein' => $request->protein[$index] ?? '0',
                    'carbs' => $request->carbs[$index] ?? '0',
                    'fat' => $request->fat[$index] ?? '0',
                    // 'energy' => $request->energy[$index] ?? '0',
                    'selected_qty_unit' => json_encode($decodedQtyUnits ?? []) // ✅ Fixed here
                ];
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
                            $selectedQtyUnitRaw = $selectedQtyUnitsArray[$index];
                            $decodedQtyUnits = json_decode($selectedQtyUnitRaw, true);
                            $item = \App\Models\Item::find($foodId); // Adjust namespace if needed
                            if (empty($decodedQtyUnits)) {
                        
                                if ($item) {
                                    $decodedQtyUnits = [[
                                        'qty' => $item->qty ?? '',
                                        'unit' => $item->unit ?? '',
                                        'checked' => 'true' // Optional: Mark it as selected
                                    ]];
                        
                                    // Update the request array with the default value
                                    $selectedQtyUnitsArray[$index] = json_encode($decodedQtyUnits);
                                }
                            }
                            // dd($decodedQtyUnits);
                            // Initialize defaults
                            $firstQty = '';
                            $firstUnit = '';

                            if (is_array($decodedQtyUnits) && count($decodedQtyUnits) > 0) {
                                $firstQty = $decodedQtyUnits[0]['qty'] ?? '';
                                $firstUnit = $decodedQtyUnits[0]['unit'] ?? '';
                            }
                            // dd($firstQty, $firstUnit);
                            $exists = UserItemMeal::where('user_id', $userId)
                                ->where('meal_id', $meal->id)
                                ->where('item_id', $foodId)
                                ->first();
                            $item = Item::find($foodId);
                            if (!$exists) {
                                UserItemMeal::create([
                                    'user_id' => $userId,
                                    'item_id' => $foodId,
                                    'meal_id' => $meal->id,
                                    'qty' => $firstQty,
                                    'unit' => $firstUnit,
                                    'carbs' => $request->carbs[$index] ?? '0',
                                    'fat' => $request->fat[$index] ?? '0',
                                    'protein' => $request->protein[$index] ?? '0',
                                    'energy' => $request->energy[$index] ?? '0',
                                    'is_swiped' => isset($item->is_swiped) ? $item->is_swiped : 0,
                                    'selected_qty_unit' => $decodedQtyUnits
                                ]);
                            }else {
                                // dump($firstQty);
                                $exists->qty = $firstQty;
                                $exists->unit = $firstUnit;
                                $exists->carbs = $request->carbs[$index] ?? '0';
                                $exists->protein = $request->protein[$index] ?? '0';
                                $exists->fat = $request->fat[$index] ?? '0';
                                $exists->energy = $request->energy[$index] ?? '0';
                                $exists->selected_qty_unit = $decodedQtyUnits;
                                $exists->save();
                            }

                            // ✅ Create UserItemSwap entries if item has swapItems
                            if ($item && $item->swapItems()->exists()) {
                                foreach ($item->swapItems as $swapItem) {
                                    $alreadyExists = \App\Models\UserItemSwap::where('user_id', $userId)
                                        ->where('meal_id', $meal->id)
                                        ->where('item_id', $item->id)
                                        ->where('swap_item_id', $swapItem->id)
                                        ->first();

                                    if (!$alreadyExists) {
                                        \App\Models\UserItemSwap::create([
                                            'user_id' => $userId,
                                            'meal_id' => $meal->id,
                                            'item_id' => $item->id,
                                            'swap_item_id' => $swapItem->id,
                                            'qty' => $firstQty,
                                            'unit' => $firstUnit,
                                            'carbs' => $swapItem->carbs ?? '0',
                                            'fat' => $swapItem->fat ?? '0',
                                            'protein' => $swapItem->protein ?? '0',
                                            'energy' => $swapItem->energy ?? '0',
                                            'selected_qty_unit' => $swapItem->selected_qty_unit ?? null
                                        ]);
                                    } else {
                                        // Update existing swap item
                                        $alreadyExists->qty = $firstQty;
                                        $alreadyExists->unit = $firstUnit;
                                        $alreadyExists->carbs = $swapItem->carbs ?? '0';
                                        $alreadyExists->fat = $swapItem->fat ?? '0';
                                        $alreadyExists->protein = $swapItem->protein ?? '0';
                                        $alreadyExists->energy = $swapItem->energy ?? '0';
                                        $alreadyExists->selected_qty_unit = $swapItem->selected_qty_unit ?? null;
                                        $alreadyExists->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // $meal->items()->sync($request->food_ids);
        }

        if ($request->has('categories')) {
            $meal->subCategories()->sync($request->categories); // Sync subcategories
        }

        if ($request->has('meal_times')) {
            $meal->categories()->sync($request->meal_times); // Sync subcategories
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
        try{

            $validated = $request->validate([
                'meal_id' => 'required|integer',
                'plan_id' => 'required|integer',
                'user_id' => 'required|integer',
                'meal_time_id' => 'required|integer',
                'meal_name' => 'required|string|max:255',
            ]);

            $meal = Meal::find($validated['meal_id']);

            // Find User Plan
            $userPlan = \App\Models\UserPlan::where('user_id', $validated['user_id'])
                ->where('plan_id', $validated['plan_id'])
                ->first();

            if (!$userPlan) {
                return response()->json(['success' => false, 'message' => 'User plan not found.'], 404);
            }
// dd($userPlan);
            // Find User Meal Time
            $userMealTime = \App\Models\UserCategory::where('user_plan_id', $userPlan->id)
                ->where('id', $validated['meal_time_id'])
                ->first();
            // dd($userMealTime);
            if ($userMealTime) {
                $userMeal = \App\Models\UserMeal::where('user_category_id', $userMealTime->id)
                                ->where('id', $validated['meal_id'])
                                ->where('user_plan_id', $userPlan->id)
                                ->first();
                // dd($userMeal);
                $existingMeal = \App\Models\Meal::where('title', trim($validated['meal_name']))
                                ->where('user_id', $validated['user_id'])
                                ->first();

                if (!$existingMeal) {
                    // Create a new meal
                    $newMeal = new \App\Models\Meal();
                    $newMeal->title = $validated['meal_name'];
                    $newMeal->user_id = $validated['user_id'];
                    $newMeal->save();
        
                    // Attach existing categories if available
                    if ($meal->subCategories) {
                        $newMeal->subCategories()->attach($meal->subCategories->pluck('id')->toArray());
                    }

                    if($meal->items) {
                        $syncData = [];

                        foreach ($meal->items as $item) {
                            $syncData[$item->id] = [
                                'item_qty' => $item->pivot->item_qty,
                                'item_qty_unit' => $item->pivot->item_qty_unit,
                                'carbs' => $item->pivot->carbs,
                                'protein' => $item->pivot->protein,
                                'fat' => $item->pivot->fat,
                                'selected_qty_unit' => $item->pivot->selected_qty_unit,
                            ];
                        }

                        $newMeal->items()->sync($syncData);
                        // $newMeal->items()->attach($meal->items->pluck('id')->toArray());
                    }
        
                    // Clone meal items
                    $existingMealItems = \App\Models\UserItemMeal::where('user_id', $validated['user_id'])
                        ->where('meal_id', $validated['meal_id'])
                        ->get();

                    $existingMealSwapItems = \App\Models\UserItemSwap::where('user_id', $validated['user_id'])
                        ->where('meal_id', $validated['meal_id'])
                        ->get();
                    // dd($existingMealSwapItems);
                    foreach ($existingMealItems as $existingMealItem) {
                        \App\Models\UserItemMeal::create([
                            'user_id' => $validated['user_id'],
                            'meal_id' => $newMeal->id,
                            'item_id' => $existingMealItem->item_id,
                            'qty' => $existingMealItem->qty,
                            'unit' => $existingMealItem->unit,
                            'carbs' => $existingMealItem->carbs,
                            'fat' => $existingMealItem->fat,
                            'protein' => $existingMealItem->protein,
                            'selected_qty_unit' => $existingMealItem->selected_qty_unit,
                        ]);
                    }

                    foreach ($existingMealSwapItems as $existingMealSwapItem) {
                        \App\Models\UserItemSwap::create([
                            'user_id' => $validated['user_id'],
                            'meal_id' => $newMeal->id,
                            'item_id' => $existingMealSwapItem->item_id,
                            'swap_item_id' => $existingMealSwapItem->swap_item_id,
                            'qty' => $existingMealSwapItem->qty,
                            'unit' => $existingMealSwapItem->unit,
                            'carbs' => $existingMealSwapItem->carbs,
                            'fat' => $existingMealSwapItem->fat,
                            'protein' => $existingMealSwapItem->protein,
                            'selected_qty_unit' => $existingMealSwapItem->selected_qty_unit,
                        ]);
                    }

                    if ($userMeal) {
                        $userMeal->update(['id' => $newMeal->id, 'meal_name' => $newMeal->title]);
                    }
                    return response()->json([
                        'success' => true,
                        'message' => 'Meal name updated successfully.',
                        'meal_id' => $newMeal->id,
                        'meal_name' => $newMeal->title
                    ]);
                }else {
                    if ($userMeal) {
                        $userMeal->update(['id' => $existingMeal->id, 'meal_name' => $existingMeal->title]);
                    }
                    return response()->json([
                        'success' => true,
                        'message' => 'Meal name updated successfully.',
                        'meal_id' => $existingMeal->id,
                        'meal_name' => $existingMeal->title
                    ]);
                }
            }

        } catch (\Exception $e) {
            // dd($e->getMessage());
            // Log the error message for debugging
            \Log::error('Error updating meal name: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
        // return response()->json(['success' => false, 'message' => 'Meal not updated.']);
    }

    // public function updateMealName(Request $request)
    // {
    //     $validated = $request->validate([
    //         'meal_id' => 'required|integer',
    //         'plan_id' => 'required|integer',
    //         'user_id' => 'required|integer',
    //         'meal_time_id' => 'required|integer',
    //         // 'category_id' => 'required|integer',
    //         'meal_name' => 'required|string|max:255',
    //     ]);
        
    //     $userPlan = \App\Models\UserPlan::where('user_id', $validated['user_id'])->where('plan_id', $validated['plan_id'])->first();
    //     // dd($userPlan);
    //     $userMealTime = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
    //                         ->where('meal_time_id', $validated['meal_time_id'])->first();
    //     if($userMealTime){
    //         $meal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTime->id)
    //                         ->where('meal_id', $validated['meal_id'])
    //                         ->where('user_plan_id', $userPlan->id)
    //                         ->where('user_meal_time_id', $userMealTime->id)
    //                         // ->where('user_category_id', $validated['category_id'])
    //                         ->first();
    //     }else{
    //         $meal = \App\Models\Meal::where('id', $validated['meal_id'])->first();
    //     }
    //     // dd($meal->meal->title, $validated['meal_name']);
    //     $existingMeal = Meal::where('title', $validated['meal_name'])->where('user_id', $validated['user_id'])->first();
    //     if(!$existingMeal){
            
    //         $newMeal = new Meal();
    //         $newMeal->title = $validated['meal_name'];
    //         $newMeal->user_id = $validated['user_id'];
    //         $newMeal->save();
        
    //         if ($meal) {
    //             $existingCategories = $meal->categories->pluck('id')->toArray(); 
    //             $newMeal->categories()->attach($existingCategories); // Attaching categories to new meal
    //         }
        
    //         $existingMealItems = \App\Models\UserItemMeal::where('user_id', $validated['user_id'])->where('meal_id', $validated['meal_id'])->get();

    //         foreach ($existingMealItems as $existingMealItem) {
    //             $newMealItem = new UserItemMeal();
    //             $newMealItem->user_id = $validated['user_id'];
    //             $newMealItem->meal_id = $newMeal->id;
    //             $newMealItem->item_id = $existingMealItem->item_id;
    //             $newMealItem->qty = $existingMealItem->qty;
    //             $newMealItem->unit = $existingMealItem->unit;
    //             $newMealItem->carbs = $existingMealItem->carbs;
    //             $newMealItem->fat = $existingMealItem->fat;
    //             $newMealItem->protein = $existingMealItem->protein;
    //             $newMealItem->save();

    //             // $existingItemSwaps = \App\Models\UserItemSwap::where('user_id', $validated['user_id'])->where('item_id', $existingMealItem->item_id)->get();
    //             // foreach ($existingItemSwaps as $existingItemSwap) {
    //             //     $newItemSwap = new \App\Models\UserItemSwap();
    //             //     $newItemSwap->user_id = $validated['user_id'];
    //             //     $newItemSwap->item_id = $existingItemSwap->item_id;
    //             //     $newItemSwap->swap_item_id = $existingItemSwap->swap_item_id;
    //             //     $newItemSwap->qty = $existingItemSwap->qty;
    //             //     $newItemSwap->save();
    //             // }

    //         }
        
    //         if ($meal) {
    //             $meal->update([
    //                 'meal_id' => $newMeal->id,
    //                 'meal_name' => $validated['meal_name']
    //             ]);
    //             return response()->json(['success' => true, 'message' => 'Meal name updated successfully.']);
    //         }
    //     } else {

    //         if ($meal) {
    //             $meal->update([
    //                 'meal_id' => $existingMeal->id,
    //                 'meal_name' => $validated['meal_name']
    //             ]);
    //             return response()->json(['success' => true, 'message' => 'Meal name updated successfully.']);
    //         }
    //     }

    //     return response()->json(['success' => false, 'message' => 'Meal not found.']);
    // }

    // public function updateMealName(Request $request)
    // {
    //     $validated = $request->validate([
    //         'meal_id' => 'required|integer',
    //         'plan_id' => 'required|integer',
    //         'user_id' => 'required|integer',
    //         'meal_time_id' => 'required|integer',
    //         // 'category_id' => 'required|integer',
    //         'meal_name' => 'required|string|max:255',
    //     ]);
        
    //     $userPlan = \App\Models\UserPlan::where('user_id', $validated['user_id'])->where('plan_id', $validated['plan_id'])->first();
    //     // dd($userPlan);
    //     $userMealTime = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
    //                         ->where('meal_time_id', $validated['meal_time_id'])->first();
        
    //     $meal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTime->id)
    //                     ->where('meal_id', $validated['meal_id'])
    //                     ->where('user_plan_id', $userPlan->id)
    //                     ->where('user_meal_time_id', $userMealTime->id)
    //                     // ->where('user_category_id', $validated['category_id'])
    //                     ->first();
    
    //     if ($meal) {
    //         $meal->update(['meal_name' => $validated['meal_name']]);
    //         return response()->json(['success' => true, 'message' => 'Meal name updated successfully.']);
    //     }
    
    //     return response()->json(['success' => false, 'message' => 'Meal not found.']);
    // }

    // public function generateImage(Request $request)
    // {
    //     $prompt = "A beautifully plated dish of {$request->title} {$request->description}, professional food photography, vibrant colors, soft lighting, high resolution, delicious presentation, top-down view, 4K quality and close lookout image.";
        
    //     try {
    //         $client = new Client();
    //         $response = $client->post('https://api.openai.com/v1/images/generations', [
    //             'headers' => [
    //                 'Authorization' => 'Bearer '. config('services.openai.key'),
    //                 'Content-Type'  => 'application/json',
    //             ],
    //             'json' => [
    //             'prompt' => $prompt,
    //             'n' => 1,
    //             'size' => '512x512',
    //             ],
    //         ]);

    //         $data = json_decode($response->getBody(), true);

    //         $imageUrl = $data['data'][0]['url'] ?? null;
    //         // dd($imageUrl);
    //         if ($imageUrl) {
    //             return response()->json(['image_url' => $this->compressAndSaveImage($imageUrl)]);
    //         }

    //         return response()->json(['error' => 'Image generation failed'], 500);
    //     } catch (\Exception $e) {
    //         // dd($e->getMessage());
    //         \Log::error('OpenAI Image Generation Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Image generation error'], 500);
    //     }
    // }

     public function generateImage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prompt' => 'nullable|string',
            'existing_image_url' => 'nullable|url',
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $prompt = $request->input('prompt', '');
        $existingImageUrl = $request->input('existing_image_url');
        if(!$prompt && !$existingImageUrl) {
            $fullPrompt = "A beautifully plated dish of {$title} {$description}, professional food photography, vibrant colors, soft lighting, high resolution, delicious presentation, top-down view, 4K quality and close lookout image.";
        }else {
            $fullPrompt = "Edit this dish of {$title} {$description} to look more gourmet. " . $prompt;
        }

        try {
            $client = new \GuzzleHttp\Client();

            if ($existingImageUrl) {
                // Try to fetch image
                $imageContents = @file_get_contents($existingImageUrl);
                if ($imageContents === false) {
                    return response()->json(['error' => 'Invalid or inaccessible image URL'], 400);
                }

                // Ensure image is PNG (DALL·E edit requires PNG with transparency)
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($finfo, $imageContents);
                finfo_close($finfo);

                if ($mimeType !== 'image/png') {
                    return response()->json(['error' => 'Image must be a PNG with transparency for editing'], 400);
                }

                // Save to temporary file
                $tempPath = tempnam(sys_get_temp_dir(), 'edit_image_') . '.png';
                file_put_contents($tempPath, $imageContents);

                // Prepare multipart data
                $multipart = [
                    [
                        'name'     => 'image',
                        'contents' => fopen($tempPath, 'r'),
                        'filename' => 'image.png',
                        'headers'  => ['Content-Type' => 'image/png']
                    ],
                    [
                        'name'     => 'prompt',
                        'contents' => $fullPrompt,
                    ],
                    [
                        'name'     => 'n',
                        'contents' => 1,
                    ],
                    [
                        'name'     => 'size',
                        'contents' => '512x512',
                    ],
                ];

                // Send edit request
                $response = $client->post('https://api.openai.com/v1/images/edits', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . config('services.openai.key'),
                    ],
                    'multipart' => $multipart,
                ]);

                unlink($tempPath); // Clean up temp file

            } else {
                // Generate new image if no existing image provided
                $response = $client->post('https://api.openai.com/v1/images/generations', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . config('services.openai.key'),
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'prompt' => $fullPrompt,
                        'n' => 1,
                        'size' => '512x512',
                    ],
                ]);
            }

            // Parse and return the response
            $data = json_decode($response->getBody(), true);

            if (!isset($data['data'][0]['url'])) {
                return response()->json(['error' => 'Image generation failed or no image returned'], 500);
            }

            return response()->json([
                'image_url' => $data['data'][0]['url']
            ]);

        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to connect to OpenAI',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function editImage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prompt' => 'nullable|string',
            'existing_image_url' => 'required|url',
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $prompt = $request->input('prompt', '');
        
        // Construct a more specific prompt for editing
        if ($prompt) {
            // If a specific prompt is provided, use it directly
            $basePrompt = $prompt;
        } else {
            // Otherwise, create a default prompt
            $basePrompt = "Create a professional food photography of " . $title;
            if ($description) {
                $basePrompt .= " that is " . $description;
            }
            $basePrompt .= ". Make it look appetizing and professional.";
        }

        \Log::debug('OpenAI API Prompt: ' . $basePrompt);

        try {
            $client = new \GuzzleHttp\Client();

            // Use the image generation endpoint instead of editing
            $response = $client->post('https://api.openai.com/v1/images/generations', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'prompt' => $basePrompt,
                    'n' => 1,
                    'size' => '1024x1024',
                    'response_format' => 'url',
                ],
            ]);

            // Parse and return the response
            $data = json_decode($response->getBody(), true);

            if (!isset($data['data'][0]['url'])) {
                return response()->json(['error' => 'Image generation failed or no image returned'], 500);
            }

            return response()->json([
                'image_url' => $data['data'][0]['url']
            ]);

        } catch (\Exception $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to connect to OpenAI',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    // ✅ Compress and Save Image Locally
    private function compressAndSaveImage($imageUrl)
    {
        try {
            $imageData = file_get_contents($imageUrl);
            $image = imagecreatefromstring($imageData);

            ob_start();
            imagejpeg($image, null, 75); // Compress to 75% quality
            $compressedImage = ob_get_clean();

            $fileName = uniqid('meal_') . '.jpg';
            $storagePath = 'storage/meals/' . $fileName;
            file_put_contents(public_path($storagePath), $compressedImage);
            // dd($storagePath);

            return asset('private/public/'.$storagePath);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            \Log::error('Image Compression Error: ' . $e->getMessage());
            return null;
        }
    }

    public function viewImport()
    {
        return view('backend.pages.meal.import-form');

    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:2048', // Adjust size limit as needed
        ]);

        // Get the file from the request
        $file = $request->file('file');
        
        // Load the file using Maatwebsite Excel
        Excel::load($file, function($reader) {
            // Iterate through each row in the file
            $reader->each(function($row) {
                // dd($row);
                // Import data into the 'meals' table (you can modify this to fit your data structure)
                Meal::create([
                    'title' => $row['breakfast'],           // Assuming 'breakfast' column in your sheet
                    'description' => $row['description'],    // Assuming 'description' column in your sheet
                    'note' => $row['notes___variations'],   // Assuming 'notes___variations' column in your sheet
                    'user_id' => auth()->id(),              // You can link to the logged-in user
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        });

        return back()->with('success', 'Meals imported successfully!');
    }
}
