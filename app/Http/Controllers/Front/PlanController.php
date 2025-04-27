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

class PlanController extends Controller
{
    public function show(Request $request, $id)
    {
        $plan = Plan::find($id);
        // dd($plan);
        // dd(Auth::user()->id);
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];
        // dd($subPlans);
        $user = User::find($request->user_id);

        $userPlans = UserPlan::with('plan', 
            'userMealTimes.userCategories.userMeals.userItems')
            ->where('user_id', $user->id) // Ensure user_id is always applied
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();
        // dd($userPlans);
        // $userPlan = Plan::with('mealTimes')->findOrFail($id);
        return view('front.plan-details', compact('userPlans','plan', 'user'));
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
        return view('front.break_fast', compact('userMealTime','userPlan'));
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
                'id' => $userMeal->meal->id,
                'name' => ($userMeal->meal_name) ? $userMeal->meal_name : $userMeal->meal->title,
                'description' => $userMeal->meal->description,
                'image' => $userMeal->meal->image
                    ? asset('storage/' . $userMeal->meal->image)
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
                'id' => $userItem->item->id,
                'name' => $userItem->item->title,
                'protein' => $userItem->item->protein ?? 0,
                'carbs' => $userItem->item->carbs ?? 0,
                'qty' => $qty,
                'unit' => $unit,  
                'selected_qty_unit' => $userItemMeal->selected_qty_unit ?? null,               
                // 'category' => ($userItem->item->category) ? $userItem->item->category->name : null,
                'description' => $userItem->item->description,
                'image' => $userItem->item->image
                    ? asset('storage/' . $userItem->item->image)
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
                'swap_item_id' => $swapItem->item->id,
                'swap_item_name' => $swapItem->item->title,
                'swap_item_price' => $swapItem->item->price,
                'swap_item_qty' => $swapItem->item->qty,
                'swap_item_protein' => $swapItem->item->protein,
                'swap_item_carbs' => $swapItem->item->carbs,
                'swap_item_description' => $swapItem->item->description,
                'swap_item_image' => $swapItem->item->image
                    ? asset('storage/' . $swapItem->item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });
        $user_item_id = $request->user_item_id;

       $item_image = $userItem->item->image ? asset('storage/' . $userItem->item->image) : 'https://via.placeholder.com/300x200?text=No+Image';
        // Return the response
        return response()->json(['item_id' => $userItem->item->id,'item_name' => $userItem->item->title, 'item_image' => $item_image, 'user_item_id' => $user_item_id, 'items' => $items, 'item'=> $userItem->item]);
    }

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
            // dd($request->all());
            foreach ($swaps as $swap) {
                // Update the `user_item_meals` table
                $userItemMeal = \App\Models\UserItemMeal::where('meal_id', $mealId)
                    ->where('item_id', $swap['swap_id'])
                    ->where('user_id', $userId)
                    ->first();

                $qty = $userItemMeal->qty;
                $carb = $userItemMeal->carbs;
                $protein = $userItemMeal->protein;
                $fat = $userItemMeal->fat;
                $selected_qty_unit = $userItemMeal->selected_qty_unit;
                // dd($selected_qty_unit);
                $userItemSwaps = \DB::table('user_item_swaps')
                ->where('item_id', $swap['swap_id'])
                ->where('swap_item_id', $swap['main_id'])
                ->where('user_id', $userId)
                ->where('meal_id', $mealId)
                ->first();
                if(!$userItemSwaps) {
                    $userItemSwaps = \DB::table('user_item_swaps')
                    ->where('item_id', $swap['swap_id'])
                    ->where('swap_item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->first();
                }
                // dd($userItemSwaps);
                if ($userItemMeal) {
                    $selectedQtyUnit = $userItemSwaps->selected_qty_unit;
                    if (is_string($selectedQtyUnit)) {
                        $decoded = json_decode($selectedQtyUnit, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $selectedQtyUnit = $decoded;
                        } else {
                            $selectedQtyUnit = [];
                        }
                    }
                    // dd(json_encode($selectedQtyUnit));
                    $userItemMeal->item_id = $swap['main_id'];
                    $userItemMeal->is_swiped = 1;
                    $userItemMeal->qty = $userItemSwaps->qty;
                    $userItemMeal->unit = $userItemSwaps->unit;
                    $userItemMeal->carbs = $userItemSwaps->carbs;
                    $userItemMeal->protein = $userItemSwaps->protein;
                    $userItemMeal->fat = $userItemSwaps->fat;
                    $userItemMeal->selected_qty_unit = $selectedQtyUnit;
                    $userItemMeal->save();
                }

                $userItemSwaps = \DB::table('user_item_swaps')->where('item_id', $swap['swap_id'])
                ->where('user_id', $userId)->where('meal_id', $mealId)->pluck('id')->toArray();
                if($userItemSwaps) {
                    foreach($userItemSwaps as $userSwapItem) {
                        \DB::table('user_item_swaps')->where('id', $userSwapItem)
                                ->update([
                                    'item_id' => $swap['main_id'],
                                    // 'swap_item_id' => $swap['swap_id']
                                ]);
                    }
                } else {
                    $userItemSwaps = \DB::table('user_item_swaps')->where('item_id', $swap['swap_id'])
                    ->where('user_id', $userId)->get();
                    foreach($userItemSwaps as $userItemSwap) {
                        $selectedQtyUnit = $userItemSwap->selected_qty_unit;
                        if (is_string($selectedQtyUnit)) {
                            $decoded = json_decode($selectedQtyUnit, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $selectedQtyUnit = $decoded;
                            } else {
                                $selectedQtyUnit = [];
                            }
                        }
                        \DB::table('user_item_swaps')->insert([
                            'user_id' => $userItemSwap->user_id,
                            'item_id' => $swap['main_id'],
                            'swap_item_id' => $userItemSwap->swap_item_id,
                            'qty' => $userItemSwap->qty,
                            'unit' => $userItemSwap->unit,
                            'carbs' => $userItemSwap->carbs,
                            'fat' => $userItemSwap->fat,
                            'protein' => $userItemSwap->protein,
                            'selected_qty_unit' => $selectedQtyUnit,
                            'meal_id' => $mealId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
               
                $userItemSwaps = \DB::table('user_item_swaps')->where('swap_item_id', $swap['main_id'])
                                    ->where('user_id', $userId)
                                    ->where('meal_id', $mealId)
                                    ->update([
                                        'swap_item_id' => $swap['swap_id'],
                                        'qty' => $qty,
                                        'carbs' => $carb,
                                        'protein' => $protein,
                                        'fat' => $fat,
                                        'selected_qty_unit' => $selected_qty_unit
                                    ]);

                // // Update the `user_item_swaps` table for `swap_item_id`
                // \DB::table('user_item_swaps')
                //     ->where('item_id', $swap['swap_id'])
                //     ->update(['swap_item_id' => $swap['swap_id']]);
    
                // // Update the `user_item_swaps` table for `item_id`
                // \DB::table('user_item_swaps')
                //     ->where('item_id', $swap['swap_id'])
                //     ->update(['item_id' => $swap['main_id']]);
    
                // Update the swapped item back in `user_item_meals`
                $updateUserItemMeal = \App\Models\UserItemMeal::where('meal_id', $mealId)
                    ->where('item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->first();
    
                if ($updateUserItemMeal) {
                    // $updateUserItemMeal->item_id = $swap['swap_id'];
                    $updateUserItemMeal->is_swiped = 0;
                    $updateUserItemMeal->save();
                }
    
                // Update `items` table for swipe status
                // Item::where('id', $swap['swap_id'])->update(['is_swiped' => 1]);
                // Item::where('id', $swap['main_id'])->update(['is_swiped' => 0]);
    
                // Update `user_items` and handle `user_swap_items`
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
    
            // If all swaps are applied successfully
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
    
            // Handle unexpected errors
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

        return view('front.plan-preview', compact('userPlans'));
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

}
