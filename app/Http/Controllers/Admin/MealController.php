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

            // Apply category filter if selected
            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('categories.id', $request->category_id);
                });
            }

            $meals = $query->get();
            // Get filtered meals
            // $meals = $query->select('id', 'title as name')->get();

            return response()->json([
                'success' => true,
                'meals' => $meals
            ]);
        }

        $categories = Category::all(); // Fetch categories for dropdown
        // $meals = Meal::with('categories','items')->get(); // Eager load subCategories
        return view('backend.pages.meal.index', compact('categories'));
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
            'categories.*' => 'exists:categories,id',
            'food_ids' => 'nullable|array',
            'food_ids.*' => 'integer|exists:items,id',
        ]);
    
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
    
        $userIds = UserItemMeal::getUniqueUserIds();
    
        if ($userIds->isNotEmpty()) {
            foreach ($userIds as $userId) {
                $hasActivePlan = \DB::table('user_plans')
                    ->where('user_id', $userId)
                    ->where('status', 'active')
                    ->exists();
    
                if ($hasActivePlan) {
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
                                'qty' => $firstQty,
                                'unit' => $firstUnit,
                                'carbs' => $request->carbs[$index] ?? '0',
                                'fat' => $request->fat[$index] ?? '0',
                                'protein' => $request->protein[$index] ?? '0',
                                'is_swiped' => isset($item->is_swiped) ? $item->is_swiped : 0,
                                'selected_qty_unit' => $decodedQtyUnits
                            ]);
                        }
                    }
                }
            }
        }
    
        if ($request->has('categories')) {
            $meal->categories()->sync($request->categories);
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
            // 'food_qty' => 'nullable|array',
            // 'food_qty.*' => 'string|max:50',
            // 'food_qty_unit' => 'nullable|array',
            // 'food_qty_unit.*' => 'string|max:50',
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
                                    'is_swiped' => isset($item->is_swiped) ? $item->is_swiped : 0,
                                    'selected_qty_unit' => $decodedQtyUnits
                                ]);
                            }else {
                                // dump($firstQty);
                                $exists->qty = $firstQty;
                                $exists->unit = $firstUnit;
                                $exists->carbs = $request->carbs[$index] ?? '0';
                                $exists->protein = $request->fat[$index] ?? '0';
                                $exists->fat = $request->protein[$index] ?? '0';
                                $exists->selected_qty_unit = $decodedQtyUnits;
                                $exists->save();
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

        // Find User Meal Time
        $userMealTime = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
            ->where('meal_time_id', $validated['meal_time_id'])
            ->first();

        if ($userMealTime) {
            $userMeal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTime->id)
                            ->where('meal_id', $validated['meal_id'])
                            ->where('user_plan_id', $userPlan->id)
                            ->first();

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
                if ($meal->categories) {
                    $newMeal->categories()->attach($meal->categories->pluck('id')->toArray());
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
                    $userMeal->update(['meal_id' => $newMeal->id, 'meal_name' => $newMeal->title]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Meal name updated successfully.',
                    'meal_id' => $newMeal->id,
                    'meal_name' => $newMeal->title
                ]);
            }else {
                if ($userMeal) {
                    $userMeal->update(['meal_id' => $existingMeal->id, 'meal_name' => $existingMeal->title]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Meal name updated successfully.',
                    'meal_id' => $existingMeal->id,
                    'meal_name' => $existingMeal->title
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Meal not updated.']);
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

    public function generateImage(Request $request)
    {
        $prompt = "A beautifully plated dish of {$request->title}, professional food photography, vibrant colors, soft lighting, high resolution, delicious presentation, top-down view, 4K quality.";
        
        try {
            $client = new Client();
            $response = $client->post('https://api.openai.com/v1/images/generations', [
                'headers' => [
                    'Authorization' => 'Bearer '. config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                'prompt' => $prompt,
                'n' => 1,
                'size' => '512x512',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $imageUrl = $data['data'][0]['url'] ?? null;

            if ($imageUrl) {
                return response()->json(['image_url' => $this->compressAndSaveImage($imageUrl)]);
            }

            return response()->json(['error' => 'Image generation failed'], 500);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            \Log::error('OpenAI Image Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Image generation error'], 500);
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

            return asset($storagePath);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            \Log::error('Image Compression Error: ' . $e->getMessage());
            return null;
        }
    }
}
