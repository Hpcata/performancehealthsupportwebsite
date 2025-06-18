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
// use PDF;
use App\Models\UserPlan;
use App\Models\UserMealTime;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function show(Request $request, $id)
    {
        // Find the plan and handle if not found
        $plan = Plan::find($id);
        if (!$plan) {
            return redirect()->back()->with('error', 'Plan not found.');
        }

        // Get sub plans if they exist
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        // Find the user and handle if not found
        $user = User::find($request->user_id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        try {
            // Load user plans and relationships
            $userPlans = UserPlan::with([
                'plan',
                'userMealTimes.mealTime', // ensure mealTime is loaded
                'userMealTimes.userCategories.userMeals.userItems'
            ])
            ->where('user_id', $user->id)
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

            // Sort userMealTimes by mealTime.order
            $userPlans->each(function ($userPlan) {
                $userPlan->userMealTimes = $userPlan->userMealTimes
                    ->sortBy(fn($mt) => $mt->mealTime->order ?? 0)
                    ->values(); // reindex
            });

            return view('front.plan-details', compact('userPlans', 'plan', 'user'));
        } catch (\Exception $e) {
            \Log::error('Error in PlanController@show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the plan details.');
        }
    }


    public function mealTimeDetails(Request $request, $id, $plan_id)
    {
        $userPlan = UserPlan::with('plan', 
        'userMealTimes.userCategories.userMeals.userItems')->where('id', $plan_id)->first();

        $userMealTime = UserMealTime::with('userCategories.userMeals.userItems')->where('meal_time_id', $id)
        ->where('user_plan_id', $plan_id)
        ->first();
        // dd($userMealTime);
        // $mealtime = MealTime::with('categories','categories.subcategories')->findOrFail($id);
        return view('front.sub-category-details', compact('userMealTime','userPlan'));
    }

    public function getMeals(Request $request, $id)
    {
        // dd($request->all());
        $category = \App\Models\UserCategory::with('userMeals.userItems')->where('id', $request->user_category_id)->first();
        
        // $subcategory = SubCategory::with('meals')->findOrFail($id);
        $meals = $category->userMeals->map(function ($userMeal) {
            // dd($usermeal->meal);
            return [
                'user_meal_id' => $userMeal->id,
                'id' => isset($userMeal->meal->id) ? $userMeal->meal->id : null,
                'name' => isset($userMeal->meal_name) ? $userMeal->meal_name : (isset($userMeal->meal->title) ? $userMeal->meal->title : null),
                'description' => isset($userMeal->meal->description) ? $userMeal->meal->description : null,
                'image' => isset($userMeal->meal->image)
                    ? webAssets('storage/' . $userMeal->meal->image)
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
                'image' => $item->image ? webAssets('storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image'
            ];
        });

        return response()->json(['subcategories' => $items]);
    }

    public function getMealItems(Request $request, $id)
    {
        // Fetch the meal with its items
        $userMeal = \App\Models\UserMeal::with('userItems')->where('id',$request->user_meal_id)->first();
        // dd($userMeal);
        // $meal = Meal::with('items')->findOrFail($id);
        // Map the items into a response-friendly structure
        $userPlan = \App\Models\UserPlan::where('id', $userMeal->user_plan_id)->where('status', 'active')->first();

        $items = $userMeal->userItems->map(function ($userItem) use($userPlan, $userMeal) {
            // dd($userItem);
            $userItemMeal = \App\Models\UserItemMeal::where('user_id', $userPlan->user_id)->where('meal_id', $userMeal->meal_id)->where('item_id', $userItem->item_id)->first();

            $qty = isset($userItemMeal->qty) ? $userItemMeal->qty : null;
            $unit = isset($userItemMeal->unit) ? $userItemMeal->unit : null;

            if (empty($qty) || empty($unit)) {
                $qty = $userItem->pivot->item_qty ?? 0; // fallback qty
                $unit = $userItem->pivot->item_qty_unit ?? ""; // fallback qty
                // You can also set a default unit here if needed
            }
            
            return [
                'user_meal_id' => $userItem->userMeal->id,
                'user_item_id' => $userItem->id,
                'id' => isset($userItem->item) ? $userItem->item->id : null,
                'name' => isset($userItem->item->title) ? $userItem->item->title : null,
                'protein' => isset($userItem->item->protein) ? $userItem->item->protein : 0,
                'carbs' => isset($userItem->item->carbs) ? $userItem->item->carbs : 0,
                'qty' => $qty,
                'unit' => $unit,  
                'selected_qty_unit' => $userItemMeal->selected_qty_unit ?? null,               
                // 'category' => ($userItem->item->category) ? $userItem->item->category->name : null,
                'description' => isset($userItem->item->description) ? $userItem->item->description : null,
                'note' => isset($userItem->item->note) ? $userItem->item->note : 'Nil',
                'image' => isset($userItem->item->image)
                    ? webAssets('storage/' . $userItem->item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
                'swapItems' => $userItem->userSwapItems
            ];
        });

        // Return the response
        return response()->json(['meal' => $userMeal->meal->title, 'items' => $items]);
    }
    
    public function getSwapItems(Request $request, $id)
    {
        //dd($request['user_item_id']);
        // Fetch the swap with its items
        $userItem = \App\Models\UserItem::with('userSwapItems')->where('id',$request['user_item_id'])->first();
        // dd($userItem);
        $item = Item::with('swapItems')->findOrFail($id);
        // Map the items into a response-friendly structure
        $items = $userItem->userSwapItems->map(function ($swapItem) use($request) {
            return [
                'swap_item_id' => isset($swapItem->item->id) ? $swapItem->item->id : null,
                'swap_item_name' => isset($swapItem->item->title) ? $swapItem->item->title : null,
                'swap_item_qty' => isset($swapItem->item->qty) ? $swapItem->item->qty : null,
                'swap_item_protein' => isset($swapItem->item->protein) ? $swapItem->item->protein : null,
                'swap_item_carbs' => isset($swapItem->item->carbs) ? $swapItem->item->carbs : null,
                'swap_item_description' => isset($swapItem->item->description) ? $swapItem->item->description : null,
                'swap_item_image' => isset($swapItem->item->image)
                    ? webAssets('storage/' . $swapItem->item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });
        $user_item_id = $request->user_item_id;

       $item_image = $userItem->item->image ? webAssets('storage/' . $userItem->item->image) : 'https://via.placeholder.com/300x200?text=No+Image';
        // Return the response
        return response()->json(['item_id' => $userItem->item->id,'item_name' => $userItem->item->title, 'item_image' => $item_image, 'user_item_id' => $user_item_id, 'items' => $items, 'item'=> $userItem->item]);
    }

    // public function applySwaps(Request $request)
    // {
    //     // Validate request inputs
    //     $request->validate([
    //         'meal_id' => 'required|exists:meals,id',
    //         'swaps' => 'required|array',
    //         'swaps.*.swap_id' => 'required|exists:items,id',
    //         'swaps.*.main_id' => 'required|exists:items,id',
    //         'swaps.*.user_item_id' => 'required|exists:user_items,id',
    //     ]);

    //     $userId = $request->user_id;
    //     $mealId = $request['meal_id'];
    //     $swaps = $request['swaps'];
    //     $meal = Meal::findOrFail($mealId);
    //     $mealName = $meal->title;

    //     try {
    //         \DB::beginTransaction();

    //         $userMealId = null;

    //         foreach ($swaps as $swap) {
    //             $userItemMeal = \App\Models\UserItemMeal::where('meal_id', $mealId)
    //                 ->where('item_id', $swap['swap_id'])
    //                 ->where('user_id', $userId)
    //                 ->first();

    //             $qty = $userItemMeal->qty ?? null;
    //             $carb = $userItemMeal->carbs ?? null;
    //             $protein = $userItemMeal->protein ?? null;
    //             $fat = $userItemMeal->fat ?? null;
    //             $selected_qty_unit = $userItemMeal->selected_qty_unit ?? null;

    //             // Properly handle selected_qty_unit
    //             if (is_string($selected_qty_unit)) {
    //                 $decoded = json_decode($selected_qty_unit, true);
    //                 if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
    //                     $selected_qty_unit = $decoded;
    //                 } else {
    //                     $selected_qty_unit = [];
    //                 }
    //             }

    //             $userItemSwaps = \DB::table('user_item_swaps')
    //                 ->where('item_id', $swap['swap_id'])
    //                 ->where('swap_item_id', $swap['main_id'])
    //                 ->where('user_id', $userId)
    //                 ->where('meal_id', $mealId)
    //                 ->first();

    //             if (!$userItemSwaps) {
    //                 $userItemSwaps = \DB::table('user_item_swaps')
    //                     ->where('item_id', $swap['swap_id'])
    //                     ->where('swap_item_id', $swap['main_id'])
    //                     ->where('user_id', $userId)
    //                     ->first();
    //             }

    //             if ($userItemMeal && $userItemSwaps) {
    //                 $userItemMeal->item_id = $swap['main_id'];
    //                 $userItemMeal->is_swiped = 1;
    //                 $userItemMeal->qty = $userItemSwaps->qty;
    //                 $userItemMeal->unit = $userItemSwaps->unit;
    //                 $userItemMeal->carbs = $userItemSwaps->carbs;
    //                 $userItemMeal->protein = $userItemSwaps->protein;
    //                 $userItemMeal->fat = $userItemSwaps->fat;
    //                 $userItemMeal->selected_qty_unit = is_array($userItemSwaps->selected_qty_unit) 
    //                     ? json_encode($userItemSwaps->selected_qty_unit) 
    //                     : $userItemSwaps->selected_qty_unit;
    //                 $userItemMeal->save();
    //             }

    //             $userItemSwaps = \DB::table('user_item_swaps')->where('item_id', $swap['swap_id'])
    //                 ->where('user_id', $userId)->where('meal_id', $mealId)->pluck('id')->toArray();

    //             if ($userItemSwaps) {
    //                 foreach ($userItemSwaps as $userSwapItem) {
    //                     \DB::table('user_item_swaps')->where('id', $userSwapItem)->update([
    //                         'item_id' => $swap['main_id'],
    //                     ]);
    //                 }
    //             } else {
    //                 $userItemSwaps = \DB::table('user_item_swaps')
    //                     ->where('item_id', $swap['swap_id'])
    //                     ->where('user_id', $userId)
    //                     ->get();

    //                 foreach ($userItemSwaps as $userItemSwap) {
    //                     $swapSelectedQtyUnit = $userItemSwap->selected_qty_unit;
                        
    //                     // Properly handle selected_qty_unit for swap items
    //                     if (is_string($swapSelectedQtyUnit)) {
    //                         $decoded = json_decode($swapSelectedQtyUnit, true);
    //                         if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
    //                             $swapSelectedQtyUnit = $decoded;
    //                         } else {
    //                             $swapSelectedQtyUnit = [];
    //                         }
    //                     }

    //                     \DB::table('user_item_swaps')->insert([
    //                         'user_id' => $userItemSwap->user_id,
    //                         'item_id' => $swap['main_id'],
    //                         'swap_item_id' => $userItemSwap->swap_item_id,
    //                         'qty' => $userItemSwap->qty,
    //                         'unit' => $userItemSwap->unit,
    //                         'carbs' => $userItemSwap->carbs,
    //                         'fat' => $userItemSwap->fat,
    //                         'protein' => $userItemSwap->protein,
    //                         'selected_qty_unit' => is_array($swapSelectedQtyUnit) 
    //                             ? json_encode($swapSelectedQtyUnit) 
    //                             : $swapSelectedQtyUnit,
    //                         'meal_id' => $mealId,
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ]);
    //                 }
    //             }

    //             \DB::table('user_item_swaps')
    //                 ->where('swap_item_id', $swap['main_id'])
    //                 ->where('user_id', $userId)
    //                 ->where('meal_id', $mealId)
    //                 ->update([
    //                     'swap_item_id' => $swap['swap_id'],
    //                     'qty' => $qty,
    //                     'carbs' => $carb,
    //                     'protein' => $protein,
    //                     'fat' => $fat,
    //                     'selected_qty_unit' => is_array($selected_qty_unit) 
    //                         ? json_encode($selected_qty_unit) 
    //                         : $selected_qty_unit,
    //                 ]);

    //             $updateUserItemMeal = \App\Models\UserItemMeal::where('meal_id', $mealId)
    //                 ->where('item_id', $swap['main_id'])
    //                 ->where('user_id', $userId)
    //                 ->first();

    //             if ($updateUserItemMeal) {
    //                 $updateUserItemMeal->is_swiped = 0;
    //                 $updateUserItemMeal->save();
    //             }

    //             $userItem = \App\Models\UserItem::where('id', $swap['user_item_id'])
    //                 ->where('item_id', $swap['swap_id'])
    //                 ->first();

    //             if ($userItem) {
    //                 $userMealId = $userItem->user_meal_id;

    //                 $swapItems = \DB::table('user_swap_items')
    //                     ->where('user_item_id', $userItem->id)
    //                     ->get();

    //                 foreach ($swapItems as $swapItem) {
    //                     \DB::table('user_swap_items')
    //                         ->where('id', $swapItem->id)
    //                         ->where('swap_item_id', $swap['main_id'])
    //                         ->update(['swap_item_id' => $swap['swap_id']]);
    //                 }

    //                 $userItem->item_id = $swap['main_id'];
    //                 $userItem->save();
    //             } else {
    //                 throw new \Exception("Item to swap not found in the meal for swap_id {$swap['swap_id']}");
    //             }
    //         }

    //         \DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'meal_id' => $mealId,
    //                 'meal_name' => $mealName,
    //                 'user_meal_id' => $userMealId,
    //             ],
    //             'message' => 'All swaps applied successfully!',
    //         ]);
    //     } catch (\Exception $e) {
    //         \DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to apply swaps. Error: ' . $e->getMessage(),
    //         ]);
    //     }
    // }
    
    public function applySwaps(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'swaps' => 'required|array',
            'swaps.*.swap_id' => 'required|exists:items,id',
            'swaps.*.main_id' => 'required|exists:items,id',
            'swaps.*.user_item_id' => 'required|exists:user_items,id',
        ]);

        $userId = $request->user_id;
        $mealId = $request['meal_id'];
        $swaps = $request['swaps'];
        $meal = Meal::findOrFail($mealId);
        $mealName = $meal->title;

        try {
            \DB::beginTransaction();

            $userMealId = null;

            foreach ($swaps as $swap) {
                $userItemMeal = \App\Models\UserItemMeal::where('meal_id', $mealId)
                    ->where('item_id', $swap['swap_id'])
                    ->where('user_id', $userId)
                    ->first();

                $qty = $userItemMeal->qty ?? null;
                $carb = $userItemMeal->carbs ?? null;
                $protein = $userItemMeal->protein ?? null;
                $fat = $userItemMeal->fat ?? null;
                $selected_qty_unit = $userItemMeal->selected_qty_unit ?? [];

                if (is_string($selected_qty_unit)) {
                    $decoded = json_decode($selected_qty_unit, true);
                    $selected_qty_unit = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [];
                }

                $userItemSwap = \App\Models\UserItemSwap::where('item_id', $swap['swap_id'])
                    ->where('swap_item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->where('meal_id', $mealId)
                    ->first();

                if (!$userItemSwap) {
                    $userItemSwap = \App\Models\UserItemSwap::where('item_id', $swap['swap_id'])
                        ->where('swap_item_id', $swap['main_id'])
                        ->where('user_id', $userId)
                        ->first();
                }

                if ($userItemMeal && $userItemSwap) {
                    $userItemMeal->item_id = $swap['main_id'];
                    $userItemMeal->is_swiped = 1;
                    $userItemMeal->qty = $userItemSwap->qty;
                    $userItemMeal->unit = $userItemSwap->unit;
                    $userItemMeal->carbs = $userItemSwap->carbs;
                    $userItemMeal->protein = $userItemSwap->protein;
                    $userItemMeal->fat = $userItemSwap->fat;
                    $userItemMeal->selected_qty_unit = is_array($userItemSwap->selected_qty_unit)
                        ? $userItemSwap->selected_qty_unit
                        : json_decode($userItemSwap->selected_qty_unit, true);
                    $userItemMeal->save();
                }

                $existingSwaps = \App\Models\UserItemSwap::where('item_id', $swap['swap_id'])
                    ->where('user_id', $userId)
                    ->where('meal_id', $mealId)
                    ->get();

                if ($existingSwaps->isNotEmpty()) {
                    foreach ($existingSwaps as $existingSwap) {
                        $existingSwap->item_id = $swap['main_id'];
                        $existingSwap->save();
                    }
                } else {
                    $fallbackSwaps = \App\Models\UserItemSwap::where('item_id', $swap['swap_id'])
                        ->where('user_id', $userId)
                        ->get();

                    foreach ($fallbackSwaps as $fallback) {
                        $selectedUnit = is_array($fallback->selected_qty_unit)
                            ? $fallback->selected_qty_unit
                            : json_decode($fallback->selected_qty_unit, true);

                        \App\Models\UserItemSwap::create([
                            'user_id' => $fallback->user_id,
                            'item_id' => $swap['main_id'],
                            'swap_item_id' => $fallback->swap_item_id,
                            'qty' => $fallback->qty,
                            'unit' => $fallback->unit,
                            'carbs' => $fallback->carbs,
                            'fat' => $fallback->fat,
                            'protein' => $fallback->protein,
                            'selected_qty_unit' => $selectedUnit,
                            'meal_id' => $mealId,
                        ]);
                    }
                }

                \App\Models\UserItemSwap::where('swap_item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->where('meal_id', $mealId)
                    ->update([
                        'swap_item_id' => $swap['swap_id'],
                        'qty' => $qty,
                        'carbs' => $carb,
                        'protein' => $protein,
                        'fat' => $fat,
                        'selected_qty_unit' => $selected_qty_unit,
                    ]);

                $updateUserItemMeal = \App\Models\UserItemMeal::where('meal_id', $mealId)
                    ->where('item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->first();

                if ($updateUserItemMeal) {
                    $updateUserItemMeal->is_swiped = 0;
                    $updateUserItemMeal->save();
                }

                $userItem = \App\Models\UserItem::where('id', $swap['user_item_id'])
                    ->where('item_id', $swap['swap_id'])
                    ->first();

                if ($userItem) {
                    $userMealId = $userItem->user_meal_id;

                    $swapItems = \DB::table('user_swap_items')
                        ->where('user_item_id', $userItem->id)
                        ->get();

                    foreach ($swapItems as $swapItem) {
                        \DB::table('user_swap_items')
                            ->where('id', $swapItem->id)
                            ->where('swap_item_id', $swap['main_id'])
                            ->update(['swap_item_id' => $swap['swap_id']]);
                    }

                    $userItem->item_id = $swap['main_id'];
                    $userItem->save();
                } else {
                    throw new \Exception("Item to swap not found in the meal for swap_id {$swap['swap_id']}");
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'meal_id' => $mealId,
                    'meal_name' => $mealName,
                    'user_meal_id' => $userMealId,
                ],
                'message' => 'All swaps applied successfully!',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to apply swaps. Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function generatePdf(Request $request, $id)
    {
        // Fetch the plan with its related data using eager loading
        // $plan = Plan::with([
        //     'mealTimes.categories.subcategories.meals.items.swapItems',  // Load related data
        // ])->findOrFail($id);
        $plan = Plan::find($id);
        
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::with('plan', 
            'userMealTimes.userCategories.userMeals.userItems')
            ->where('user_id', $request->user_id) // Ensure user_id is always applied
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

        // Sort userMealTimes by mealTime.order ASC
        $userPlans->each(function ($userPlan) {
            $userPlan->userMealTimes = $userPlan->userMealTimes
                ->sortBy(fn($mt) => $mt->mealTime->order ?? 0)
                ->values(); // reindex
        });
        // dd($userPlans);
        // Pass the plan data to the Blade view for rendering the PDF
        // $pdf = PDF::loadView('front.plan-pdf', compact('userPlans'));
        // $pdf->setOption('enable-local-file-access', true);
        $pdf = Pdf::loadView('front.plan-pdf', compact('userPlans'))
        ->setPaper('A4', 'portrait'); // Set page size and layout

        // Download the generated PDF
        return $pdf->download('plan_' . $id . '.pdf');
        // return $pdf->stream('plan.pdf');

    }

    public function preview(Request $request, $id)
    {
        $plan = Plan::find($id);
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::with('plan', 'userMealTimes.userCategories.userMeals.userItems')
            ->where('user_id', $request->user_id)
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

            // Sort userMealTimes by mealTime.order ASC
        $userPlans->each(function ($userPlan) {
            $userPlan->userMealTimes = $userPlan->userMealTimes
                ->sortBy(fn($mt) => $mt->mealTime->order ?? 0)
                ->values(); // reindex
        });
        $printAllmeal = true;
        return view('front.plan-preview', compact('userPlans', 'printAllmeal'));
    }

    public function planPreview(Request $request)
    {

        $groupedData = $request->input('grouped_data'); // ✅ Access grouped data from AJAX

        $plan = Plan::find($request->plan_id);
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::with('plan', 'userMealTimes.userCategories.userMeals.userItems')
            ->where('user_id', $request->user_id)
            ->where(function ($query) use ($request, $subPlans) {
                $query->where('plan_id', $request->plan_id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

        // Sort userMealTimes
        $userPlans->each(function ($userPlan) {
            $userPlan->userMealTimes = $userPlan->userMealTimes
                ->sortBy(fn($mt) => $mt->mealTime->order ?? 0)
                ->values();
        });

        // ✅ Now you can use $groupedData to highlight or modify view data
        // Example: you could pass it to the view for use in blade:
        $printAllmeal = false;
        // dd(view('front.plan-preview', compact('userPlans', 'groupedData', 'printAllmeal')));
        return view('front.plan-preview', compact('userPlans', 'groupedData', 'printAllmeal'));
    }

    public function getDefaultPlanDetails($id)
    {
        $plan = Plan::with([
            'subPlans.mealTimes.categories.meals.items.swapItems',
            'mealTimes.categories.meals.items.swapItems'
        ])->find($id);

        if (!$plan) {
            return response()->json(['error' => 'Plan not found'], 404);
        }

        // Structure the main plan and subPlans data
        $structurePlan = function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => $plan->price,
                'mealTimes' => $plan->mealTimes->map(function ($mealTime) {
                    return [
                        'id' => $mealTime->id,
                        'title' => $mealTime->title,
                        'categories' => $mealTime->categories->map(function ($category) {
                            return [
                                'id' => $category->id,
                                'name' => $category->title,
                                'meals' => $category->meals->map(function ($meal) {
                                    return [
                                        'id' => $meal->id,
                                        'name' => $meal->title,
                                        'items' => $meal->items->map(function ($item) {
                                            return [
                                                'id' => $item->id,
                                                'name' => $item->title,
                                                'swapItems' => $item->swapItems->map(function ($swapItem) {
                                                    return [
                                                        'id' => $swapItem->id,
                                                        'name' => $swapItem->title,
                                                    ];
                                                }),
                                            ];
                                        }),
                                    ];
                                }),
                            ];
                        }),
                    ];
                }),
            ];
        };

        // Include subPlans in the response
        $response = [
            'mainPlan' => $structurePlan($plan),
            'subPlans' => $plan->subPlans->map($structurePlan),
        ];

        return response()->json($response);
    }

    public function getMealByMealTimes(Request $request)
    {
        $request->validate([
            'user_meal_time_id' => 'required|integer|exists:user_meal_times,id',
        ]);

        $userMealTime = \App\Models\UserMealTime::with('userMeals.meal')  // Assuming you want meal info
            ->findOrFail($request->user_meal_time_id);

        $meals = $userMealTime->userMeals->map(function ($userMeal) {
            $meal = $userMeal->meal;

            return [
                'user_meal_id' => $userMeal->id,
                'id' => $meal->id,
                'name' => $userMeal->meal_name ?: $meal->title,
                'description' => $meal->description,
                'image' => $meal->image
                    ? webAssets('storage/' . $meal->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        return response()->json(['meals' => $meals]);
    }

     public function ajaxGetMeals(User $user, Plan $plan)
    {
        $userPlan = UserPlan::with([
            'userMealTimes.userCategories.userMeals.meal',
        ])
        ->where('user_id', $user->id)
        ->where('plan_id', $plan->id)
        ->firstOrFail();

        $categories = $userPlan->userMealTimes;

        $result = [];

        foreach ($userPlan->userMealTimes as $mealTime) {
            foreach ($mealTime->userCategories as $userCategory) {
                $categoryName = optional($userCategory->category)->title;

                $meals = $userCategory->userMeals->map(function ($userMeal) {
                    $meal = optional($userMeal->meal);
                    return [
                        'id' => $meal->id,
                        'title' => $meal->title,
                        'description' => $meal->description,
                        'image_url' => $meal && $meal->image
                            ? asset('private/public/storage/'.$meal->image)
                            : 'https://via.placeholder.com/300x200?text=No+Image',
                    ];
                });

                $result[] = [
                    'user_plan_id' => $userPlan->id,
                    'user_meal_time_id' => $mealTime->id,
                    'user_category_id' => $userCategory->id,
                    'meal_time_id' => $mealTime->meal_time_id,
                    'id' => $userCategory->category_id,
                    'name' => $categoryName,
                    'meals' => $meals,
                ];
            }
        }
        // dd($result);
        return response()->json([
            'categories' => $result
        ]);
    }

}
