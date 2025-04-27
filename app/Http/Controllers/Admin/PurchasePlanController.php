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
use App\Models\UserPlan;
use App\Models\User;
use Log;
use App\Mail\ActivePlanMail;
use Mail;
use PHPUnit\TextUI\Help;
use Storage;
use Illuminate\Support\Str;
use App\Models\UserItemMeal;
use App\Models\UserItemSwap;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\isEmpty;

class PurchasePlanController extends Controller
{
    // List all payments with pagination
    public function index()
    {
        // Fetch payments with pagination (you can adjust per page as needed)
        $payments = Payment::with('plan')->get();

        // Return the view with the payments data
        return view('backend.pages.plan.purchase-plans', compact('payments'));
    }

    public function create($id)
    {   
        // Fetch the plan with its related data
        $payment = Payment::findOrFail($id);
        $plan = Plan::find($payment->plan_id);
        $subPlans = $plan->subPlans()->pluck('sub_plan_id')->toArray();

        $plans = Plan::with([
            'subPlans.mealTimes.categories.meals.items.swapItems',
            'mealTimes.categories.meals.items.swapItems'
        ])->where('id',$payment->plan_id)
        ->when($subPlans, function ($query) use ($subPlans) {
            return $query->orWhereIn('id', $subPlans);
        })->get();

        // Get all options for each relationship (mealTimes, categories, etc.)
        $mealTimes = MealTime::all();
        $categories = Category::all();
        $meals = Meal::all();
        $items = Item::where('is_swiped',0)->get();
        $step5Foods = Item::where('is_extra', 0)->get()->groupBy('category_id');
        $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
            $query->where('form_slug', 'food_preference')
                  ->whereIn('question', ['Grains', 'Legumes, beans and pulses','Eggs','Meat','Meat Alternatives','Seafood','Dairy','Non-Dairy','Fruit','Vegetable','Oils / Butter']); // Add your condition here
        }])->where('payment_id', $id)->first();       

        if(isset($userPrePlan->prePlanDetails)){
            $prePlanDetails = $userPrePlan->prePlanDetails;
            $perPlanSelectedFoods = $prePlanDetails->map(function ($detail) {
                return json_decode($detail->answer, true); // Decode JSON into an array
            })->flatten()->toArray();
        }else {
            $perPlanSelectedFoods = [];
        }
        return view('backend.pages.plan.purchase-plan-create', compact(
            'plans', 'mealTimes', 'categories', 'meals', 'items', 'payment', 'subPlans', 'step5Foods', 'perPlanSelectedFoods'
        ));
    }

    public function store(Request $request)
    {
        // // Validate the incoming request data
        // $validated = $request->validate([
        //     'meal_times' => 'required|array',
        //     'meal_times.*' => 'exists:meal_times,id',
        //     'meals.*.*' => 'required|array',
        //     'meals.*.*' => 'exists:meals,id',
        //     'items.*.*' => 'required|array',
        //     'items.*.*' => 'exists:items,id',
        // ]);
        // dd('23');
        try {
            $payment = Payment::findOrFail($request->payment_id);
            \DB::beginTransaction();
            // Initialize arrays
            
            $mealItems = \App\Models\ItemMeal::with('item')->get();
            $swapItemsGrouped = \DB::table('item_swaps')->get()->groupBy('item_id');
            
            foreach ($mealItems as $mealItem) {
                // Create meal entry if not exists
                \App\Models\UserItemMeal::firstOrCreate([
                    'item_id' => $mealItem->item_id,
                    'meal_id' => $mealItem->meal_id,
                    'user_id' => $payment->user_id,
                ], [
                    'qty'     => $mealItem->item_qty ?? $mealItem->item->qty,
                    'unit'    => $mealItem->item_qty_unit ?? $mealItem->item->unit,
                    'carbs'   => $mealItem->carbs ?? $mealItem->item->carbs,
                    'protein' => $mealItem->protein ?? $mealItem->item->protein,
                    'fat'     => $mealItem->fat ?? $mealItem->item->fat,
                    'selected_qty_unit'=> $mealItem->selected_qty_unit,
                    'is_swiped' => $mealItem->item->is_swiped ?? 0,
                ]);

                // Process all swap items for this meal item
                $relatedSwaps = $swapItemsGrouped[$mealItem->item_id] ?? collect();

                foreach ($relatedSwaps as $swapItem) {
                    $item = \App\Models\Item::find($swapItem->swap_item_id);

                    $selectedQtyUnit = $item->selected_qty_unit ?: json_encode([
                        ["qty" => $item->qty, "unit" => $item->unit]
                    ]);

                    \App\Models\UserItemSwap::firstOrCreate([
                        'item_id' => $swapItem->item_id,
                        'swap_item_id' => $swapItem->swap_item_id,
                        'user_id' => $payment->user_id,
                        'meal_id' => $mealItem->meal_id,
                    ], [
                        'qty'     => $item->qty,
                        'unit'    => $item->unit,
                        'carbs'   => $item->carbs,
                        'protein' => $item->protein,
                        'fat'     => $item->fat,
                        'selected_qty_unit'=> $selectedQtyUnit,
                    ]);
                }
            }
            // foreach ($mealItems as $mealItem) {
            //     $exists = UserItemMeal::where('item_id', $mealItem->item_id)
            //         ->where('meal_id', $mealItem->meal_id)
            //         ->where('user_id', $payment->user_id)
            //         ->exists();
            
            //     if (!$exists) {
            //         UserItemMeal::create([
            //             'item_id' => $mealItem->item_id,
            //             'meal_id' => $mealItem->meal_id,
            //             'user_id' => $payment->user_id,
            //             'qty'     => $mealItem->item_qty ?? $mealItem->item->qty,
            //             'unit'    => $mealItem->item_qty_unit ?? $mealItem->item->unit,
            //             'carbs'   => $mealItem->carbs ?? $mealItem->item->carbs,
            //             'protein' => $mealItem->protein ?? $mealItem->item->protein,
            //             'fat'     => $mealItem->fat ?? $mealItem->item->fat,
            //             'selected_qty_unit'=> $mealItem->selected_qty_unit,
            //             'is_swiped' => $mealItem->item->is_swiped ?? 0,
            //         ]);
            //     }
            // }
            
            // $swapItems = \DB::table('item_swaps')->get();
            
            // foreach ($swapItems as $swapItem) {
            //     $item = \App\Models\Item::find($swapItem->swap_item_id);
            //     $mealId = \App\Models\ItemMeal::where('item_id', $swapItem->item_id)->value('meal_id'); // Fetch associated meal_id

            //     $selectedQtyUnit = $item->selected_qty_unit;

            //     if (empty($selectedQtyUnit)) {
            //         $selectedQtyUnit = json_encode([
            //             [
            //                 "qty" => $item->qty,
            //                 "unit" => $item->unit,
            //             ]
            //         ]);
            //     }

            //     $exists = UserItemSwap::where('item_id', $swapItem->item_id)
            //         ->where('swap_item_id', $swapItem->swap_item_id)
            //         ->where('user_id', $payment->user_id)
            //         ->where('meal_id', $mealId)
            //         ->exists();
            
            //     if (!$exists) {
            //         UserItemSwap::create([
            //             'item_id' => $swapItem->item_id,
            //             'meal_id' => $mealId,
            //             'swap_item_id' => $swapItem->swap_item_id,
            //             'user_id' => $payment->user_id,
            //             'qty'     => $item->qty,
            //             'unit'    => $item->unit,
            //             'carbs'   => $item->carbs,
            //             'protein' => $item->protein,
            //             'fat'     => $item->fat,
            //             'selected_qty_unit'=> $selectedQtyUnit,
            //         ]);
            //     }
            // }
            
            $meals = [];
            $categories = [];
            $items = [];
            $swapItems = [];

            // Step 1: Populate categories by planId and mealTimeId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = $request->meal_times[$planId];

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($request->meals[$planId][$mealTimeId])) {
                            $mealIds = $request->meals[$planId][$mealTimeId];
                            
                            $categoriesByMeal = \DB::table('meal_category')
                                    ->whereIn('meal_id', $mealIds)
                                    ->pluck('category_id')
                                    ->unique()
                                    ->toArray();
                            $mealTimeCategories = \DB::table('category_mealtime')
                                ->where('meal_time_id', $mealTimeId)
                                ->pluck('category_id')
                                ->unique()
                                ->toArray();
                            $commonCategories = array_intersect($categoriesByMeal, $mealTimeCategories);

                            foreach ($commonCategories as $categoryId) {
                                $categories[$planId][$mealTimeId][] = $categoryId;
                            }
                        }
                    }
                }
            }

            // Step 2: Organize meals by planId, mealTimeId, and categoryId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = $request->meal_times[$planId];

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($categories[$planId][$mealTimeId])) {
                            $categoryIds = $categories[$planId][$mealTimeId];

                            foreach ($categoryIds as $categoryId) {
                                if (isset($request->meals[$planId][$mealTimeId])) {
                                    $mealIds = $request->meals[$planId][$mealTimeId];
                                    foreach ($mealIds as $mealId) {
                                        $categoriesByMeal = \DB::table('meal_category')
                                            ->where('meal_id', $mealId)
                                            ->where('category_id', $categoryId)
                                            ->exists();

                                        if ($categoriesByMeal) {
                                            $meals[$planId][$mealTimeId][$categoryId][] = $mealId;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //Step 3: Organize items by planId, mealTimeId, categoryId, and mealId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = $request->meal_times[$planId];

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($categories[$planId][$mealTimeId])) {
                            $categoryIds = $categories[$planId][$mealTimeId];

                            foreach ($categoryIds as $categoryId) {
                                if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                    $mealIds = $meals[$planId][$mealTimeId][$categoryId];

                                    foreach ($mealIds as $mealId) {
                                        if (isset($request->items[$planId][$mealTimeId][$mealId])) {
                                            $itemIds = $request->items[$planId][$mealTimeId][$mealId];

                                            foreach ($itemIds as $itemId) {
                                                $items[$planId][$mealTimeId][$categoryId][$mealId][] = $itemId;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // // Step 4: Organize swap items by planId, mealTimeId, categoryId, mealId, and itemId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = $request->meal_times[$planId];
            
                    foreach ($mealTimeIds as $mealTimeId) {
                        // Ensure $categories is an array
                        $categoryData = $categories[$planId][$mealTimeId] ?? [];
                        $categoryData = is_object($categoryData) ? (array) $categoryData : $categoryData;
            
                        foreach ($categoryData as $categoryId) {
                            // Ensure $meals is an array
                            $mealData = $meals[$planId][$mealTimeId][$categoryId] ?? [];
                            $mealData = is_object($mealData) ? (array) $mealData : $mealData;
            
                            foreach ($mealData as $mealId) {
                                // Ensure $items is an array
                                $itemData = $items[$planId][$mealTimeId][$categoryId][$mealId] ?? [];
                                $itemData = is_object($itemData) ? (array) $itemData : $itemData;
            
                                foreach ($itemData as $itemId) {
                                    // Ensure $swap_items is an array
                                    $swapItemData = $request->swap_items[$planId][$mealTimeId][$mealId][$itemId] ?? [];
                                    $swapItemData = is_object($swapItemData) ? (array) $swapItemData : $swapItemData;
            
                                    foreach ($swapItemData as $swapItemId) {
                                        // dump($swapItemId); // Debugging output
                                        $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId][] = $swapItemId;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (isset($request->plan_id) && is_array($request->plan_id)) {
                foreach ($request->plan_id as $planId) {
                    $userPlan = \DB::table('user_plans')->updateOrInsert(
                        ['user_id' => $request->user_id, 'plan_id' => $planId],
                        ['status' => 'active', 'modified_by' => auth()->id(), 'updated_at' => now()]
                    );
            
                    $userPlan = \DB::table('user_plans')
                        ->where('user_id', $request->user_id)
                        ->where('plan_id', $planId)
                        ->value('id');
            
                    $existingMeals = \DB::table('user_meals')
                        ->where('user_plan_id', $userPlan)
                        ->pluck('meal_id')
                        ->toArray();
            
                    $newMeals = Arr::flatten($meals[$planId]);
                    $mealsToRemove = array_diff($existingMeals, $newMeals);
            
                    if (!empty($mealsToRemove)) {
                        \DB::table('user_meals')
                            ->where('user_plan_id', $userPlan)
                            ->whereIn('meal_id', $mealsToRemove)
                            ->delete();
                    }
                    // dd($request->all());
                    if (isset($request->meal_times[$planId])) {
                        foreach (array_unique($request->meal_times[$planId]) as $mealTimeId) {
                            $userMealTimeId = \DB::table('user_meal_times')->updateOrInsert(
                                ['user_plan_id' => $userPlan, 'meal_time_id' => $mealTimeId],
                                ['created_at' => now(), 'updated_at' => now()]
                            );
            
                            $userMealTimeId = \DB::table('user_meal_times')
                                ->where('user_plan_id', $userPlan)
                                ->where('meal_time_id', $mealTimeId)
                                ->value('id');
            
                            if (isset($categories[$planId][$mealTimeId])) {
                                foreach ($categories[$planId][$mealTimeId] as $categoryId) {
                                    \DB::table('user_categories')->updateOrInsert(
                                        ['user_plan_id' => $userPlan, 'meal_time_id' => $userMealTimeId, 'category_id' => $categoryId],
                                        ['created_at' => now(), 'updated_at' => now()]
                                    );
            
                                    $userCategoryId = \DB::table('user_categories')
                                        ->where('user_plan_id', $userPlan)
                                        ->where('meal_time_id', $userMealTimeId)
                                        ->where('category_id', $categoryId)
                                        ->value('id');
            
                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        foreach ($meals[$planId][$mealTimeId][$categoryId] as $mealId) {
                                            // ✅ FIX: Check for meal with full context (meal_time + category)
                                            $userMealId = \DB::table('user_meals')->where([
                                                'user_plan_id' => $userPlan,
                                                'user_meal_time_id' => $userMealTimeId,
                                                'user_category_id' => $userCategoryId,
                                                'meal_id' => $mealId,
                                            ])->value('id');
            
                                            if (!$userMealId) {
                                                $userMealId = \DB::table('user_meals')->insertGetId([
                                                    'user_plan_id' => $userPlan,
                                                    'user_meal_time_id' => $userMealTimeId,
                                                    'user_category_id' => $userCategoryId,
                                                    'user_subcategory_id' => null,
                                                    'meal_id' => $mealId,
                                                    'created_at' => now(),
                                                    'updated_at' => now(),
                                                ]);
                                            }
            
                                            $existingItems = \DB::table('user_items')
                                                ->where('user_meal_id', $userMealId)
                                                ->where('user_plan_id', $userPlan)
                                                ->where('user_meal_time_id', $userMealTimeId)
                                                ->where('user_category_id', $userCategoryId)
                                                ->pluck('item_id')
                                                ->toArray();
                                            // dd($existingItems);
                                            $currentItems = isset($items[$planId][$mealTimeId][$categoryId][$mealId])
                                                ? $items[$planId][$mealTimeId][$categoryId][$mealId]
                                                : [];
            
                                            $itemsToRemove = array_diff($existingItems, $currentItems);
                                            // dd($currentItems);
                                            if (!empty($itemsToRemove)) {
                                                \DB::table('user_items')
                                                    ->where('user_plan_id', $userPlan)
                                                    ->where('user_meal_time_id', $userMealTimeId)
                                                    ->where('user_category_id', $userCategoryId)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();
            
                                                \DB::table('user_item_meals')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();
            
                                                \DB::table('user_item_swaps')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();
                                            }
                                            
                                            $b = \DB::table('user_item_meals')
                                                ->where('user_id', $request->user_id)
                                                ->where('meal_id', $mealId)
                                                ->whereNotIn('item_id', $currentItems)
                                                ->delete();
                                            // dd($currentItems);
                                            foreach ($currentItems as $itemId) {
                                                \DB::table('user_items')->updateOrInsert(
                                                    [
                                                        'user_plan_id' => $userPlan,
                                                        'user_meal_time_id' => $userMealTimeId,
                                                        'user_category_id' => $userCategoryId,
                                                        'user_subcategory_id' => null,
                                                        'user_meal_id' => $userMealId,
                                                        'item_id' => $itemId
                                                    ],
                                                    ['created_at' => now(), 'updated_at' => now()]
                                                );
            
                                                $userItemId = \DB::table('user_items')
                                                    ->where('user_plan_id', $userPlan)
                                                    ->where('user_meal_time_id', $userMealTimeId)
                                                    ->where('user_category_id', $userCategoryId)
                                                    ->where('user_subcategory_id', null)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('item_id', $itemId)
                                                    ->value('id');
                                                // dd($userItemId);
                                                $existingSwapItems = \DB::table('user_swap_items')
                                                    ->where('user_plan_id', $userPlan)
                                                    ->where('user_meal_time_id', $userMealTimeId)
                                                    ->where('user_category_id', $userCategoryId)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('user_item_id', $userItemId)
                                                    ->pluck('swap_item_id')
                                                    ->toArray();
            
                                                $currentSwapItems = isset($swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId])
                                                    ? $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId]
                                                    : [];
                                                    // dd($currentSwapItems);
                                                $p = \DB::table('user_item_swaps')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('item_id', $itemId)
                                                    ->where('meal_id', $mealId)
                                                    ->whereNotIn('swap_item_id', $currentSwapItems)
                                                    ->delete();
                                                // dd($p);
                                                $swapItemsToRemove = array_diff($existingSwapItems, $currentSwapItems);
                                                // dd($swapItemsToRemove);
                                                if (!empty($swapItemsToRemove)) {
                                                    \DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan)
                                                        ->where('user_meal_time_id', $userMealTimeId)
                                                        ->where('user_category_id', $userCategoryId)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItemId)
                                                        ->whereIn('swap_item_id', $swapItemsToRemove)
                                                        ->delete();
                                                }
                                                
                                                foreach ($currentSwapItems as $swapItemId) {
                                                    \DB::table('user_swap_items')->updateOrInsert(
                                                        [
                                                            'user_plan_id' => $userPlan,
                                                            'user_meal_time_id' => $userMealTimeId,
                                                            'user_category_id' => $userCategoryId,
                                                            'user_subcategory_id' => null,
                                                            'user_meal_id' => $userMealId,
                                                            'user_item_id' => $userItemId,
                                                            'swap_item_id' => $swapItemId
                                                        ],
                                                        ['created_at' => now(), 'updated_at' => now()]
                                                    );



                                                }
                                                $a = \DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan)
                                                        ->where('user_meal_time_id', $userMealTimeId)
                                                        ->where('user_category_id', $userCategoryId)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItemId)
                                                        ->get();
                                                // dd($a);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // if (isset($request->plan_id) && is_array($request->plan_id)) {
            //     foreach ($request->plan_id as $planId) {
            //         $userPlan = \DB::table('user_plans')->insertGetId([
            //             'user_id' => $payment->user_id,
            //             'plan_id' => $planId,
            //             'status' => 'active',
            //             'modified_by' => auth()->id(),
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ]);
    
            //         if (isset($request->meal_times[$planId])) {
            //             $mealTimeIds = $request->meal_times[$planId];
    
            //             foreach ($mealTimeIds as $mealTimeId) {
            //                 // Insert into user_meal_times
            //                 $userMealTimeId = \DB::table('user_meal_times')->insertGetId([
            //                     'user_plan_id' => $userPlan,
            //                     'meal_time_id' => $mealTimeId,
            //                     'created_at' => now(),
            //                     'updated_at' => now(),
            //                 ]);

            //                 if (isset($categories[$planId][$mealTimeId])) {
            //                     $categoryIds = $categories[$planId][$mealTimeId];
            //                     foreach ($categoryIds as $categoryId) {
            //                         $userCategoryId = \DB::table('user_categories')->insertGetId([
            //                             'user_plan_id' => $userPlan,
            //                             'meal_time_id' => $userMealTimeId,
            //                             'category_id' => $categoryId,
            //                             'created_at' => now(),
            //                             'updated_at' => now(),
            //                         ]);

            //                         if (isset($meals[$planId][$mealTimeId][$categoryId])) {
            //                             $mealIds = $meals[$planId][$mealTimeId][$categoryId];
    
            //                             foreach ($mealIds as $mealId) {
            //                                 // Insert meal into user_meals
            //                                 $userMealId = \DB::table('user_meals')->insertGetId([
            //                                     'user_plan_id' => $userPlan,
            //                                     'user_meal_time_id' => $userMealTimeId,
            //                                     'user_category_id' => $userCategoryId,
            //                                     'user_subcategory_id' => null,
            //                                     'meal_id' => $mealId,
            //                                     'created_at' => now(),
            //                                     'updated_at' => now(),
            //                                 ]);

            //                                 if (isset($items[$planId][$mealTimeId][$categoryId][$mealId])) {
            //                                     $itemIds = $items[$planId][$mealTimeId][$categoryId][$mealId];
    
            //                                     foreach ($itemIds as $itemId) {
            //                                         $userItemId = \DB::table('user_items')->insertGetId([
            //                                             'user_plan_id' => $userPlan,
            //                                             'user_meal_time_id' => $userMealTimeId,
            //                                             'user_category_id' => $userCategoryId,
            //                                             'user_subcategory_id' => null,
            //                                             'user_meal_id' => $userMealId,
            //                                             'item_id' => $itemId,
            //                                             'created_at' => now(),
            //                                             'updated_at' => now(),
            //                                         ]);

            //                                         if (isset($swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId])) {
            //                                             $swapItemIds = $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId];
    
            //                                             foreach ($swapItemIds as $swapItemId) {
            //                                                 \DB::table('user_swap_items')->insert([
            //                                                     'user_plan_id' => $userPlan,
            //                                                     'user_meal_time_id' => $userMealTimeId,
            //                                                     'user_category_id' => $userCategoryId,
            //                                                     'user_subcategory_id' => null,
            //                                                     'user_meal_id' => $userMealId,
            //                                                     'user_item_id' => $userItemId,
            //                                                     'swap_item_id' => $swapItemId,
            //                                                     'created_at' => now(),
            //                                                     'updated_at' => now(),
            //                                                 ]);
            //                                             }
            //                                         }
            //                                     }
            //                                 }
            //                             }
            //                         }
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }
                           
            \DB::commit();

            $payment = \App\Models\Payment::with('user')->where('id',$payment->id)->first();
            $email = $payment->user->email;
            $planName = \App\Models\Plan::where('id', $payment->plan_id)->first()->name;
            $user = $payment->user;

            Mail::to($email)->send(new ActivePlanMail($user, $planName));

            return redirect()->route('admin.purchase-plans.index')
                ->with('success', 'User Plan created successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            \DB::rollBack();
            \Log::error('Error creating User Plan: ' . $e->getMessage());
            \Log::error('Request Data: ', $request->all());
            return redirect()->route('admin.purchase-plans.index')
                ->with('error', 'Failed to create User Plan. Error: ' . $e->getMessage());
        }
    }

    public function edit(User $user, $planId)
    {
        $payment = Payment::find($planId);
        $plan = Plan::find($payment->plan_id);
        $subPlanIds = $plan->subPlans->pluck('id')->toArray();

        $userPlans = UserPlan::with([
            'plan', 
            'userMealTimes.userCategories.userMeals.userItems.userSwapItems',
        ])
        ->where('user_id', $user->id)
        ->where('plan_id', $plan->id)
        ->when($subPlanIds, function ($query) use ($subPlanIds) {
            return $query->orWhereIn('plan_id', $subPlanIds);
        })
        ->get();

        if (!$userPlans) {
            return redirect()->route('admin.purchase-plans.index')
                            ->with('error', 'User Plan not found.');
        }

        $selectedMeals = [];
        $selectedItems = [];
        $selectedSwapItems = [];

        // Initialize Nutrition Totals
        $totalCarbs = 0;
        $totalFat = 0;
        $totalProtein = 0;
        // dd($userPlans);
        foreach ($userPlans as $userPlan) {
            foreach ($userPlan->userMealTimes as $mealTime) {
                // dd($mealTime->userCategories);
                $selectedMeals[$userPlan->plan_id][$mealTime->meal_time_id] = 
                    $mealTime->userMeals->pluck('id','meal_id')->toArray();
                    // dd($mealTime->userMeals);
                foreach($mealTime->userCategories as $categories) {
                    foreach ($categories->userMeals as $userMeal) {
                        $mealId = $userMeal->meal_id;
                        // Store user items
                        // dd($userMeal->userItems);
                        $selectedItems[$mealTime->meal_time_id][$mealId] = 
                            $userMeal->userItems->pluck('item_id')->toArray();
    
                        // Calculate Nutritional Values for Items
                        foreach ($userMeal->userItems as $userItem) {
                            $item = Item::find($userItem->item_id); // Assuming Item contains nutritional data
                            if ($item) {
                                $totalCarbs += $item->carbs ?? 0;
                                $totalFat += $item->fat ?? 0;
                                $totalProtein += $item->protein ?? 0;
                            }
    
                            // Store user swap items
                            $selectedSwapItems[$mealTime->meal_time_id][$mealId][$userItem->item_id] = 
                                $userItem->userSwapItems->pluck('swap_item_id')->toArray();
    
                            // Calculate Nutritional Values for Swap Items
                            // foreach ($userItem->userSwapItems as $swapItem) {
                            //     $swap = Item::find($swapItem->swap_item_id); // Assuming Item model stores nutrition data
                            //     if ($swap) {
                            //         $totalCarbs += $swap->carbs ?? 0;
                            //         $totalFat += $swap->fat ?? 0;
                            //         $totalProtein += $swap->protein ?? 0;
                            //     }
                            // }
                        }
                    }
                }
            }
        }
        // dd($selectedItems);
        $mealTimes = MealTime::all();
        $categories = Category::all();
        $meals = Meal::all();
        $items = Item::where('is_swiped', 0)->get();

        $activity = UserPlan::with([
            'modifiedBy',
        ])
        ->where('user_id', $payment->user_id)
        ->where('plan_id', $payment->plan_id)
        ->orderBy('updated_at', 'desc')
        ->first();

        $step5Foods = Item::with('category')
                        ->where('is_extra', 0)
                        ->get()
                        ->groupBy('category_id');

        $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
            $query->where('form_slug', 'food_preference')
                ->whereIn('question', [
                    'Grains', 'Legumes, beans and pulses',
                    'Eggs', 'Meat', 'Meat Alternatives',
                    'Seafood', 'Dairy', 'Non-Dairy',
                    'Fruit', 'Vegetable', 'Oils / Butter'
                ]);
        }])->where('payment_id', $payment->id)->first();

        $perPlanSelectedFoods = !empty($userPrePlan) && $userPrePlan->prePlanDetails
            ? $userPrePlan->prePlanDetails->map(function ($detail) {
                return json_decode($detail->answer, true);
            })->flatten()->toArray()
            : [];

        // dd($totalCarbs, $totalFat, $totalProtein);
        return view('backend.pages.plan.purchase-plan-edit', compact(
            'userPlans', 'mealTimes', 'categories', 'meals', 'items',
            'selectedMeals', 'selectedItems', 'selectedSwapItems',
            'activity', 'payment', 'step5Foods', 'perPlanSelectedFoods',
            'totalCarbs', 'totalFat', 'totalProtein' // Include total values in the view
        ));
    }
    
    public function update(Request $request)
    {
        // Validate the incoming request data
        // $validated = $request->validate([
        //     'meal_times' => 'required|array',
        //     'meal_times.*' => 'exists:meal_times,id',
        //     'categories.*.*' => 'exists:categories,id',
        //     'meals.*.*' => 'exists:meals,id',
        //     'items.*.*' => 'exists:items,id',
        // ]);
        
        try {
            // Find the UserPlan by ID
            $payment = Payment::findOrFail($request->payment_id);
            // \DB::beginTransaction();
            // Initialize arrays
            $meals = [];
            $categories = [];
            $items = [];
            $swapItems = [];

            // Step 1: Populate categories by planId and mealTimeId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = array_unique($request->meal_times[$planId]);
                    // dd(array_unique($mealTimeIds));
                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($request->meals[$planId][$mealTimeId])) {
                            $mealIds = $request->meals[$planId][$mealTimeId];
                            // dd($mealIds);
                            // Fetch categories for meals
                            $categoriesByMeal = \DB::table('meal_category')
                                    ->whereIn('meal_id', $mealIds)
                                    ->pluck('category_id') // Get a collection of category IDs
                                    ->unique() // Remove duplicate values
                                    ->toArray();
                            $mealTimeCategories = \DB::table('category_mealtime')
                                ->where('meal_time_id', $mealTimeId)
                                ->pluck('category_id') // Get a collection of category IDs
                                ->unique() // Remove duplicate values
                                ->toArray();
                            $commonCategories = array_intersect($categoriesByMeal, $mealTimeCategories);

                            foreach ($commonCategories as $categoryId) {
                                $categories[$planId][$mealTimeId][] = $categoryId;
                            }
                        }
                    }
                }
            }

            // Step 2: Organize meals by planId, mealTimeId, and categoryId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = array_unique($request->meal_times[$planId]);

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($categories[$planId][$mealTimeId])) {
                            $categoryIds = $categories[$planId][$mealTimeId];
                            // dd($categoryIds);
                            foreach ($categoryIds as $categoryId) {
                                if (isset($request->meals[$planId][$mealTimeId])) {
                                    $mealIds = $request->meals[$planId][$mealTimeId];
                                    foreach ($mealIds as $mealId) {
                                        $categoriesByMeal = \DB::table('meal_category')
                                            ->where('meal_id', $mealId)
                                            ->where('category_id', $categoryId)
                                            ->exists(); // Use `exists` for a simple existence check

                                        if ($categoriesByMeal) {
                                            // Organize meals under the respective category
                                            $meals[$planId][$mealTimeId][$categoryId][] = $mealId;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            //Step 3: Organize items by planId, mealTimeId, categoryId, and mealId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = array_unique($request->meal_times[$planId]);

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($categories[$planId][$mealTimeId])) {
                            $categoryIds = $categories[$planId][$mealTimeId];

                            foreach ($categoryIds as $categoryId) {
                                if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                    $mealIds = $meals[$planId][$mealTimeId][$categoryId];

                                    foreach ($mealIds as $mealId) {
                                        if (isset($request->items[$planId][$mealTimeId][$mealId])) {
                                            $itemIds = $request->items[$planId][$mealTimeId][$mealId];

                                            foreach ($itemIds as $itemId) {
                                                $items[$planId][$mealTimeId][$categoryId][$mealId][] = $itemId;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // // Step 4: Organize swap items by planId, mealTimeId, categoryId, mealId, and itemId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = array_unique($request->meal_times[$planId]);

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($categories[$planId][$mealTimeId])) {
                            $categoryIds = $categories[$planId][$mealTimeId];

                            foreach ($categoryIds as $categoryId) {
                                if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                    $mealIds = $meals[$planId][$mealTimeId][$categoryId];

                                    foreach ($mealIds as $mealId) {
                                        if (isset($items[$planId][$mealTimeId][$categoryId][$mealId])) {
                                            $itemIds = $items[$planId][$mealTimeId][$categoryId][$mealId];

                                            foreach ($itemIds as $itemId) {
                                                if (isset($request->swap_items[$planId][$mealTimeId][$mealId][$itemId])) {
                                                    $swapItemIds = $request->swap_items[$planId][$mealTimeId][$mealId][$itemId];

                                                    foreach ($swapItemIds as $swapItemId) {
                                                        $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId][] = $swapItemId;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // dd($request->all());
            $userPlans = UserPlan::with([
                'plan', 
                'userMealTimes.userCategories.userSubcategories.userMeals.userItems',
            ])
            ->where('user_id', $request->user_id)
            ->whereIn('plan_id', $request->plan_id)
            ->get();

            // Check if the UserPlan exists
            if (!$userPlans) {
                return redirect()->route('admin.purchase-plans.index')
                                ->with('error', 'User Plan not found.');
            }
            // dd($categories);
            \DB::beginTransaction();
            // Update or Create Plans
            if (isset($request->plan_id) && is_array($request->plan_id)) {
                foreach ($request->plan_id as $planId) {
                    $userPlan = \DB::table('user_plans')->updateOrInsert(
                        ['user_id' => $request->user_id, 'plan_id' => $planId],
                        ['status' => 'active', 'modified_by' => auth()->id(), 'updated_at' => now()]
                    );
            
                    $userPlan = \DB::table('user_plans')
                        ->where('user_id', $request->user_id)
                        ->where('plan_id', $planId)
                        ->value('id');
            
                    // ✅ Get existing meals for the user plan
                    $existingMeals = \DB::table('user_meals')
                        ->where('user_plan_id', $userPlan)
                        ->pluck('meal_id')
                        ->toArray();
            
                    $newMeals = Arr::flatten(($meals[$planId]));
                    // ✅ Remove meals not in the request
                    $mealsToRemove = array_diff($existingMeals, $newMeals);
                    if (!empty($mealsToRemove)) {
                        \DB::table('user_meals')
                            ->where('user_plan_id', $userPlan)
                            ->whereIn('meal_id', $mealsToRemove)
                            ->delete();
                    }

                    if (isset($request->plan_id) && is_array($request->plan_id)) {
                        foreach ($request->plan_id as $planId) {
                            $userPlan = \DB::table('user_plans')->updateOrInsert(
                                ['user_id' => $request->user_id, 'plan_id' => $planId],
                                ['status' => 'active', 'modified_by' => auth()->id(), 'updated_at' => now()]
                            );
                    
                            $userPlan = \DB::table('user_plans')
                                ->where('user_id', $request->user_id)
                                ->where('plan_id', $planId)
                                ->value('id');
                    
                            $existingMeals = \DB::table('user_meals')
                                ->where('user_plan_id', $userPlan)
                                ->pluck('meal_id')
                                ->toArray();
                    
                            $newMeals = Arr::flatten($meals[$planId]);
                            $mealsToRemove = array_diff($existingMeals, $newMeals);
                    
                            if (!empty($mealsToRemove)) {
                                \DB::table('user_meals')
                                    ->where('user_plan_id', $userPlan)
                                    ->whereIn('meal_id', $mealsToRemove)
                                    ->delete();
                            }
                    
                            if (isset($request->meal_times[$planId])) {
                                foreach (array_unique($request->meal_times[$planId]) as $mealTimeId) {
                                    $userMealTimeId = \DB::table('user_meal_times')->updateOrInsert(
                                        ['user_plan_id' => $userPlan, 'meal_time_id' => $mealTimeId],
                                        ['created_at' => now(), 'updated_at' => now()]
                                    );
                    
                                    $userMealTimeId = \DB::table('user_meal_times')
                                        ->where('user_plan_id', $userPlan)
                                        ->where('meal_time_id', $mealTimeId)
                                        ->value('id');
                    
                                    if (isset($categories[$planId][$mealTimeId])) {
                                        foreach ($categories[$planId][$mealTimeId] as $categoryId) {
                                            \DB::table('user_categories')->updateOrInsert(
                                                ['user_plan_id' => $userPlan, 'meal_time_id' => $userMealTimeId, 'category_id' => $categoryId],
                                                ['created_at' => now(), 'updated_at' => now()]
                                            );
                    
                                            $userCategoryId = \DB::table('user_categories')
                                                ->where('user_plan_id', $userPlan)
                                                ->where('meal_time_id', $userMealTimeId)
                                                ->where('category_id', $categoryId)
                                                ->value('id');
                    
                                            if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                                foreach ($meals[$planId][$mealTimeId][$categoryId] as $mealId) {
                                                    // ✅ FIX: Check for meal with full context (meal_time + category)
                                                    $userMealId = \DB::table('user_meals')->where([
                                                        'user_plan_id' => $userPlan,
                                                        'user_meal_time_id' => $userMealTimeId,
                                                        'user_category_id' => $userCategoryId,
                                                        'meal_id' => $mealId,
                                                    ])->value('id');
                    
                                                    if (!$userMealId) {
                                                        $userMealId = \DB::table('user_meals')->insertGetId([
                                                            'user_plan_id' => $userPlan,
                                                            'user_meal_time_id' => $userMealTimeId,
                                                            'user_category_id' => $userCategoryId,
                                                            'user_subcategory_id' => null,
                                                            'meal_id' => $mealId,
                                                            'created_at' => now(),
                                                            'updated_at' => now(),
                                                        ]);
                                                    }
                    
                                                    $existingItems = \DB::table('user_items')
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_plan_id', $userPlan)
                                                        ->where('user_meal_time_id', $userMealTimeId)
                                                        ->where('user_category_id', $userCategoryId)
                                                        ->pluck('item_id','id')
                                                        ->toArray();
                                                    // dd($existingItems);
                                                    $currentItems = isset($items[$planId][$mealTimeId][$categoryId][$mealId])
                                                        ? $items[$planId][$mealTimeId][$categoryId][$mealId]
                                                        : [];
                    
                                                    $itemsToRemove = array_diff($existingItems, $currentItems);
                                                    
                                                    if (!empty($itemsToRemove)) {
                                                        \DB::table('user_items')
                                                            ->where('user_plan_id', $userPlan)
                                                            ->where('user_meal_time_id', $userMealTimeId)
                                                            ->where('user_category_id', $userCategoryId)
                                                            ->where('user_meal_id', $userMealId)
                                                            ->whereIn('item_id', $itemsToRemove)
                                                            ->delete();
                    
                                                        \DB::table('user_item_meals')
                                                            ->where('user_id', $request->user_id)
                                                            ->where('meal_id', $mealId)
                                                            ->whereIn('item_id', $itemsToRemove)
                                                            ->delete();
                    
                                                        \DB::table('user_item_swaps')
                                                            ->where('user_id', $request->user_id)
                                                            ->where('meal_id', $mealId)
                                                            ->whereIn('item_id', $itemsToRemove)
                                                            ->delete();
                                                    }
                                                    
                                                    \DB::table('user_item_meals')
                                                        ->where('user_id', $request->user_id)
                                                        ->where('meal_id', $mealId)
                                                        ->whereNotIn('item_id', $currentItems)
                                                        ->delete();
                    
                                                    foreach ($currentItems as $itemId) {
                                                        \DB::table('user_items')->updateOrInsert(
                                                            [
                                                                'user_plan_id' => $userPlan,
                                                                'user_meal_time_id' => $userMealTimeId,
                                                                'user_category_id' => $userCategoryId,
                                                                'user_subcategory_id' => null,
                                                                'user_meal_id' => $userMealId,
                                                                'item_id' => $itemId
                                                            ],
                                                            ['created_at' => now(), 'updated_at' => now()]
                                                        );
                    
                                                        $userItemId = \DB::table('user_items')
                                                            ->where('user_plan_id', $userPlan)
                                                            ->where('user_meal_time_id', $userMealTimeId)
                                                            ->where('user_category_id', $userCategoryId)
                                                            ->where('user_subcategory_id', null)
                                                            ->where('user_meal_id', $userMealId)
                                                            ->where('item_id', $itemId)
                                                            ->value('id');
                                                       
                                                        $existingSwapItems = \DB::table('user_swap_items')
                                                            ->where('user_plan_id', $userPlan)
                                                            ->where('user_meal_time_id', $userMealTimeId)
                                                            ->where('user_category_id', $userCategoryId)
                                                            ->where('user_meal_id', $userMealId)
                                                            ->where('user_item_id', $userItemId)
                                                            ->pluck('swap_item_id')
                                                            ->toArray();
                                                        $currentSwapItems = isset($swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId])
                                                            ? $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId]
                                                            : [];
                                                        
                                                        $p = \DB::table('user_item_swaps')
                                                            ->where('user_id', $request->user_id)
                                                            ->where('item_id', $itemId)
                                                            ->where('meal_id', $mealId)
                                                            ->whereNotIn('swap_item_id', $currentSwapItems)
                                                            ->delete();
                                                        $swapItemsToRemove = array_diff($existingSwapItems, $currentSwapItems);
                                                       
                                                        if (!empty($swapItemsToRemove)) {
                                                            \DB::table('user_swap_items')
                                                                ->where('user_plan_id', $userPlan)
                                                                ->where('user_meal_time_id', $userMealTimeId)
                                                                ->where('user_category_id', $userCategoryId)
                                                                ->where('user_meal_id', $userMealId)
                                                                ->where('user_item_id', $userItemId)
                                                                ->whereIn('swap_item_id', $swapItemsToRemove)
                                                                ->delete();
                                                        }
                                                       
                                                        foreach ($currentSwapItems as $swapItemId) {
                                                            \DB::table('user_swap_items')->updateOrInsert(
                                                                [
                                                                    'user_plan_id' => $userPlan,
                                                                    'user_meal_time_id' => $userMealTimeId,
                                                                    'user_category_id' => $userCategoryId,
                                                                    'user_subcategory_id' => null,
                                                                    'user_meal_id' => $userMealId,
                                                                    'user_item_id' => $userItemId,
                                                                    'swap_item_id' => $swapItemId
                                                                ],
                                                                ['created_at' => now(), 'updated_at' => now()]
                                                            );
                                                        }
                                                        $a = \DB::table('user_swap_items')
                                                                ->where('user_plan_id', $userPlan)
                                                                ->where('user_meal_time_id', $userMealTimeId)
                                                                ->where('user_category_id', $userCategoryId)
                                                                ->where('user_meal_id', $userMealId)
                                                                ->where('user_item_id', $userItemId)
                                                                ->get();
                                                        
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            \DB::commit();

            return redirect()->route('admin.purchase-plans.index')
                            ->with('success', 'User Plan updated successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            \DB::rollBack();
            \Log::error('Error updating User Plan: ' . $e->getMessage());
            \Log::error('Request Data: ', $request->all());

            return redirect()->route('admin.purchase-plans.index')
                            ->with('error', 'Failed to update User Plan. Error: ' . $e->getMessage());
        }
    }

    public function getMealItems(Request $request)
    {
        $userId = $request->user_id;

        if ($request->type == 'edit') {
            $userMeal = \App\Models\UserItemMeal::where('meal_id', $request->meal_id)
                ->where('user_id', $userId)
                ->get();
            // dd($userMeal);
            if (!$userMeal) {
                $meal = Meal::with('items.swapItems')->find($request->meal_id);

                if (!$meal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Meal not found.'
                    ], 404);
                }

                $mealName = $meal->title;
                $mealId = $meal->id;

                $totalCarbs = 0;
                $totalProtein = 0;
                $totalFat = 0;

                $data = $meal->userMealItems->map(function ($item) use ($userId, &$totalCarbs, &$totalProtein, &$totalFat, $request) {
                    $userSwapItem = \DB::table('user_item_swaps')
                        ->where('user_id', $userId)
                        ->where('meal_id', $request->meal_id)
                        ->where('item_id', $item->id)
                        ->first();
                    if ($userSwapItem->isEmpty()) {
                        // $swapItems = Item::with('swapItems')->find($item->item_id);
                        $userSwapItem = \App\Models\UserItemSwap::with('item')
                        ->where('item_id', $item->item_id)
                        ->where('user_id', $userId)
                        // ->where('meal_id', $request->meal_id)
                        ->get();
                    }
                    // dd($item);
                    $swapItems = $item->userItemSwaps->map(function ($swapItem) use ($userSwapItem, &$totalCarbs, &$totalProtein, &$totalFat) {
                        // $totalCarbs += $swapItem->carbs;
                        // $totalProtein += $swapItem->protein;
                        // $totalFat += $swapItem->fat;

                        return [
                            'id' => $swapItem->id,
                            'name' => $swapItem->title,
                            'qty' => $swapItem->qty ?? 0,
                            'unit' => $swapItem->unit,
                            'carbs' => $swapItem->carbs,
                            'protein' => $swapItem->protein,
                            'fat' => $swapItem->fat,
                            'selected_qty_unit' => $swapItem->selected_qty_unit
                        ];
                    });

                    $totalCarbs += $item->carbs;
                    $totalProtein += $item->protein;
                    $totalFat += $item->fat;

                    return [
                        'id' => $item->id,
                        'name' => $item->title,
                        'qty'  => $item->pivot->qty 
                            ?? optional(\App\Models\ItemMeal::where('item_id', $item->id)->first())->item_qty 
                            ?? optional($userSwapItem)->qty 
                            ?? 0,
                        'unit' => $item->pivot->unit,
                        'carbs' => $item->carbs,
                        'protein' => $item->protein,
                        'fat' => $item->fat,
                        'selected_qty_unit' => $item->pivot->selected_qty_unit,
                        'swapItems' => $swapItems
                    ];
                });
            } else {
                // dd('3');
                $meal = Meal::where('id', $request->meal_id)
                    ->with(['userMealItems' => function ($query) use ($userId) {
                        $query->where('user_id', $userId)
                            ->with(['userItemSwaps' => function ($subQuery) use ($userId) {
                                $subQuery->where('user_id', $userId);
                            }]);
                    }])
                    ->first();

                $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)
                    ->where('plan_id', $request->plan_id)
                    ->first();

                $userMealTimes = null;
                $userUpdateMeal = null;
                if ($userPlan) {
                    $userMealTimes = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
                        ->where('meal_time_id', $request->meal_time_id)
                        ->first();

                    if ($userMealTimes) {
                        $userUpdateMeal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTimes->id)
                            ->where('meal_id', $request->meal_id)
                            ->first();
                    }
                }

                $mealName = $userUpdateMeal->meal_name ?? $meal->title;
                $mealId = $meal->id;

                $totalCarbs = 0;
                $totalProtein = 0;
                $totalFat = 0;
                // dd($userMeal);
                $data = $userMeal->map(function ($item) use($userId, &$totalCarbs, &$totalProtein, &$totalFat, $request) {
                    // dd($userId);
                    $swapItems = \App\Models\UserItemSwap::with('item')
                        ->where('item_id', $item->item_id)
                        ->where('user_id', $userId)
                        ->where('meal_id', $request->meal_id)
                        ->get();

                    if ($swapItems->isEmpty()) {
                        $swapItems = \App\Models\UserItemSwap::with('item')
                            ->where('item_id', $item->item_id)
                            ->where('user_id', $userId)
                            ->whereNull('meal_id')
                            ->get(); // No meal_id filter here (includes NULL)
                    }

                    // dd($swapItems);
                    $totalCarbs += isset($item->carbs) ? $item->carbs : $item->items->carbs;
                    $totalProtein += isset($item->protein) ? $item->protein : $item->items->protein;
                    $totalFat += isset($item->fat) ? $item->fat : $item->items->fat;
                    // dd($item);
                    return [
                        'id' => isset($item->items->id) ? $item->items->id : $item->item_id,
                        'name' => isset($item->items->title) ? $item->items->title : '',
                        'qty' => isset($item->qty) ? $item->qty : (isset($item->items->item_qty) ? $item->items->item_qty : 0),
                        'unit' => isset($item->unit) ? $item->unit :  (isset($item->items->unit) ? $item->items->unit : ''),
                        'carbs' => isset($item->carbs) ? $item->carbs : $item->items->carbs,
                        'protein' =>  isset($item->protein) ? $item->protein : $item->items->protein,
                        'fat' => isset($item->fat) ? $item->fat : $item->items->fat,
                        'selected_qty_unit' => isset($item->selected_qty_unit) ? $item->selected_qty_unit : (isset($item->items->selected_qty_unit) ? $item->items->selected_qty_unit : ''),
                        'swapItems' => $swapItems->map(function ($swapFood) use (&$totalCarbs, &$totalProtein, &$totalFat) {
                            // $totalCarbs += isset($swapFood->carbs) ? $swapFood->carbs : $swapFood->swapItem->carbs;
                            // $totalProtein += isset($swapFood->protein) ? $swapFood->protein : $swapFood->swapItem->protein;
                            // $totalFat += isset($swapFood->fat) ? $swapFood->fat : $swapFood->swapItem->fat;
                            // dd($swapFood->selected_qty_unit);
                            return [    
                                'id' => $swapFood->swapItem->id,
                                'name' => $swapFood->swapItem->title,
                                'qty' => $swapFood->qty,
                                'unit' => $swapFood->unit ?? '',
                                'carbs' => isset($swapFood->carbs) ? $swapFood->carbs : $swapFood->swapItem->carbs,
                                'protein' => isset($swapFood->protein) ? $swapFood->protein : $swapFood->swapItem->protein,
                                'fat' => isset($swapFood->fat) ? $swapFood->fat : $swapFood->swapItem->fat,
                                'selected_qty_unit' => isset($swapFood->selected_qty_unit) ? $swapFood->selected_qty_unit : (isset($swapFood->swapItem->selected_qty_unit) ? $swapFood->swapItem->selected_qty_unit : ''),
                            ];
                        })
                    ];
                });
            }
        } else {
            // dd('here');
            $meal = Meal::with('items.swapItems')->find($request->meal_id);

            if (!$meal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meal not found.'
                ], 404);
            }

            $mealName = $meal->title;
            $mealId = $meal->id;

            $totalCarbs = 0;
            $totalProtein = 0;
            $totalFat = 0;

            $data = $meal->items->map(function ($item) use (&$totalCarbs, &$totalProtein, &$totalFat) {
                $totalCarbs += $item->pivot->carbs ?? $item->carbs;
                $totalProtein += $item->pivot->protein ?? $item->protein;
                $totalFat += $item->pivot->fat ?? $item->fat;

                $swapItems = $item->swapItems->map(function ($swapItem){
                    // $totalCarbs += $swapItem->carbs;
                    // $totalProtein += $swapItem->protein;
                    // $totalFat += $swapItem->fat;
                
                    return [
                        'id' => $swapItem->id,
                        'name' => $swapItem->title,
                        'qty' => $swapItem->qty ?? 0,
                        'unit' => $swapItem->unit ?? '',
                        'carbs' => $swapItem->carbs,
                        'protein' => $swapItem->protein,
                        'fat' => $swapItem->fat,
                        'selected_qty_unit' => $swapItem->selected_qty_unit,
                    ];
                });

                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'qty'  => isset($item->pivot->item_qty) ? $item->pivot->item_qty : $item->qty,
                    'unit' => isset($item->pivot->item_qty_unit) ? $item->pivot->item_qty_unit : $item->unit,
                    'carbs' => isset($item->pivot->carbs) ? $item->pivot->carbs : $item->carbs,
                    'protein' => isset($item->pivot->protein) ? $item->pivot->protein : $item->protein,
                    'fat' => isset($item->pivot->fat) ? $item->pivot->fat : $item->fat,
                    'selected_qty_unit' => isset($item->pivot->selected_qty_unit) ? $item->pivot->selected_qty_unit : $item->selected_qty_unit,
                    'swapItems' => $swapItems
                ];
            });
        }

        return response()->json([
            'success' => true,
            'meal_id' => $mealId,
            'meal_name' => $mealName,
            'data' => $data,
            'total_carbs' => number_format($totalCarbs, 2),
            'total_protein' => number_format($totalProtein, 2),
            'total_fat' => number_format($totalFat, 2)
        ]);
    }
    
    public function getMealsByMealTime(Request $request)
    {
        // Retrieve the MealTime along with its related categories and meals
        $mealTime = MealTime::with('categories.meals') // Load categories and meals
                    ->where('id', $request->meal_time_id)
                    ->first();

        // Check if MealTime exists
        if (!$mealTime) {
            return response()->json([
                'success' => false,
                'message' => 'MealTime not found.'
            ], 404);
        }

        // Apply search filter if provided
        $search = $request->search;
        $filteredCategories = $mealTime->categories->filter(function ($category) use ($search) {
            return empty($search) || stripos($category->title, $search) !== false;
        });
        // dd($filteredCategories);
        // Prepare the response: Flatten and collect only meals from filtered categories
        $meals = $filteredCategories->flatMap(function ($category) use ($request){
            return $category->meals->filter(function ($meal) use ($request) {
                // Check if the meal's user_id is NULL or matches the requested user_id
                return is_null($meal->user_id) || $meal->user_id == $request->user_id;
            })->map(function ($meal) {
                return [
                    'id' => $meal->id,
                    'name' => $meal->title
                ];
            });
        });

        $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)
                    ->where('plan_id', $request->plan_id)->first();

        if ($userPlan) {
            $userMealTimes = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
                                ->where('meal_time_id', $request->meal_time_id)->first();

            $userMeals = [];
            if (isset($userMealTimes->userCategories)) {
                $userMeals = $userMealTimes->userCategories->flatMap(function ($category) {
                    return $category->userMeals->map(function ($meal) {
                        return [
                            'id' => $meal->meal_id,
                            'name' => $meal->meal_name
                        ];
                    });
                });
            }

            $userMeals = collect($userMeals);
            $updatedMeals = $meals->map(function ($meal) use ($userMeals) {
                // Check if the meal exists in $userMeals
                $matchingUserMeal = $userMeals->firstWhere('id', $meal['id']);
                if ($matchingUserMeal && !empty($matchingUserMeal['name'])) {
                    // Replace the name if a valid name is found in $userMeals
                    $meal['name'] = $matchingUserMeal['name'];
                }
                return $meal;
            });
        } else {
            $updatedMeals = $filteredCategories->flatMap(function ($category) use ($request){
                return $category->meals->filter(function ($meal) use ($request) {
                    // Check if the meal's user_id is NULL or matches the requested user_id
                    return is_null($meal->user_id) || $meal->user_id == $request->user_id;
                })->map(function ($meal) {
                    return [
                        'id' => $meal->id,
                        'name' => $meal->title
                    ];
                });
            });
        }

        // Return only the meals in the desired structure
        return response()->json([
            'success' => true,
            'meals' => $updatedMeals
        ]);
    }

    public function getPrePlanDetails($id)
    {
        $userPrePlan = \App\Models\UserPrePlan::with('prePlanDetails','payment')->where('payment_id', $id)->first();
        $prePlanDetails = $userPrePlan->prePlanDetails ?? [];

        $userDetails = [
            'name' => isset($userPrePlan->user) ? $userPrePlan->user->name : '',
            'email' => isset($userPrePlan->user) ? $userPrePlan->user->email : '',
            'phone' => $userPrePlan->payment->phone,
            'dob' => formatDate($userPrePlan->dob) ?? '',
            'address' => $userPrePlan->address ?? '',
            'occupation' => $userPrePlan->occupation ?? '',
            'culture' => $userPrePlan->culture ?? '',
            'referredBy' => $userPrePlan->referredBy ?? '',
            'other' => $userPrePlan->other ?? '',
        ];
         // Group questions and answers by form_name
        $groupedData = [];
        foreach ($prePlanDetails as $detail) {
            $formName = $detail['form_name'];

            // Decode JSON answers where applicable
            $answer = $detail['answer'];
            $decodedAnswer = json_decode($answer, true);
            $finalAnswer = $decodedAnswer !== null ? $decodedAnswer : $answer;

            // Add to the grouped data
            if (!isset($groupedData[$formName])) {
                $groupedData[$formName] = [];
            }
            $groupedData[$formName][$detail['question']] = $finalAnswer;
        }

        return response()->json([
            'success' => true,
            'userDetails' => $userDetails,
            'data' => $groupedData
        ]);

    }

    public function getItems(Request $request) 
    {
        $items = Item::where('is_swiped',0)->get();

        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    }

    public function getSwapitems(Request $request) 
    {
        // dd($request->all());
        if($request->has('type') && $request->type == "edit") {
            $item = \App\Models\UserItemMeal::with('items')
            ->where('user_id', $request->user_id)
            ->where('meal_id', $request->meal_id)
            ->where('item_id', $request->item_id)
            ->first();
            if(!$item) {
                $userPlan = \App\Models\UserPlan::where('plan_id',$request->plan_id)->where('user_id', $request->user_id)->first();
                $userMeal = \App\Models\UserMeal::where('user_plan_id',$userPlan->id)->where('meal_id', $request->meal_id)->first();
                $item = \App\Models\UserItem::where('user_meal_id', $userMeal->id)->where('user_plan_id', $userPlan->id)->first();
            }

            $items = Item::where('is_swiped',1)->get();

            $selectedSwapItems = \DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->pluck('swap_item_id')->toArray();
            
            $selectedSwapItems = Item::whereIn('id', $selectedSwapItems)->get();
            $selectedItemArr = [];
            foreach ($selectedSwapItems as $swapItem) {
                $Item = \DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
                $selectedItemArr[] = [
                    'id' => $swapItem->id,
                    'name' => $swapItem->title,
                    'qty' => $Item->qty,
                    'unit' => $Item->unit,
                    'image' => $swapItem->image,
                    'carbs' => $swapItem->carbs,
                    'protein' => $swapItem->protein,
                    'fat' => $swapItem->fat,

                ];
            }
            return response()->json([
                'success' => true,
                'item'      => $item,
                'swapItems' => $items,
                'selectedSwapItems' => $selectedItemArr
            ]);
        }else {
            // dd('22');
            $item = Item::find($request->item_id);
            // dd($item);
            $items = isset($item->swapItems) ? $item->swapItems : [];

            $selectedSwapItems = \DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->pluck('swap_item_id')->toArray();
            
            $selectedSwapItems = Item::whereIn('id', $selectedSwapItems)->get();
            $selectedItemArr = [];
            foreach ($selectedSwapItems as $swapItem) {
                $Item = \DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
                $selectedItemArr[] = [
                    'id' => $swapItem->id,
                    'name' => $swapItem->title,
                    'qty' => $Item->qty,
                    'unit' => $Item->unit,
                    'image' => $swapItem->image
                ];
            }

            return response()->json([
                'success' => true,
                'item'      => $item,
                'swapItems' => $items,
                'selectedSwapItems' => !empty($selectedItemArr) ? $selectedItemArr : collect($items)->map(fn($item) => ['id' => $item->id, 'name' => $item->title , 'qty' => $item->qty, 'unit' => $item->unit, 'image' => $item->image])->toArray()

            ]);
        }
    }

    public function updateFoodSwapFoods(Request $request) 
    {
        // dd($request->all());
        if($request->type == "item-update") {
            $userItemMeal = UserItemMeal::with('items')
                ->where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->where('item_id', $request->item_id)
                ->first();

            $savedItem = null;

            // ✅ Update or create UserItemMeal
            if ($userItemMeal) {
                $userItemMeal->update([
                    'qty' => $request->item_qty,
                    'unit' => $request->item_unit,
                    'carbs' => $request->food_carbs,
                    'protein' => $request->food_protein,
                    'fat' => $request->food_fat,
                    'selected_qty_unit' => $request->selected_qty_unit,
                    'updated_at' => now(),
                ]);
                $savedItem = $userItemMeal->load('items');
            } else {
                $savedItem = UserItemMeal::create([
                    'user_id' => $request->user_id,
                    'item_id' => $request->item_id,
                    'qty' => $request->item_qty,
                    'unit' => $request->item_unit,
                    'carbs' => $request->food_carbs,
                    'protein' => $request->food_protein,
                    'fat' => $request->food_fat,
                    'meal_id' => $request->meal_id,
                    'selected_qty_unit' => $request->selected_qty_unit,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $savedItem->load('items');
            }
            return response()->json([
                'success' => true,
                'item' => $savedItem,
                'message' => 'item updated successfully!'
            ]);
        }
        if($request->type == "swap-food-update") {
            $existingSwap = UserItemSwap::with('swapItem')
                ->where('item_id', $request->item_id)
                ->where('meal_id', $request->meal_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->where('user_id', $request->user_id)
                ->first();
                // dd($existingSwap);
            if ($existingSwap) {
                $existingSwap->update([
                    'qty' => $request->swap_item_qty,
                    'carbs' => $request->swap_food_carbs,
                    'protein' => $request->swap_food_protein,
                    'fat' => $request->swap_food_fat,
                    'unit' => $request->swap_item_unit,
                    'selected_qty_unit' => $request->swap_selected_qty_unit,
                    'updated_at' => now(),
                ]);
                $savedSwapItems[] = $existingSwap->load('swapItem');
            } else {
                $swapItem = UserItemSwap::create([
                    'item_id' => $request->item_id,
                    'swap_item_id' => $request->swap_item_id,
                    'user_id' => $request->user_id,
                    'meal_id' => $request->meal_id,
                    'qty' => $request->swap_item_qty,
                    'carbs' => $request->swap_food_carbs,
                    'protein' => $request->swap_food_protein,
                    'fat' => $request->swap_food_fat,
                    'unit' => $request->swap_item_unit,
                    'selected_qty_unit' => $request->swap_selected_qty_unit,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $savedSwapItems[] = $swapItem->load('swapItem');
            }
            return response()->json([
                'success' => true,
                'swapItem' => $savedSwapItems,
                'message' => 'Swap foods updated successfully!'
            ]);
        }

    }

    public function deletePurchasePlanFood(Request $request)
    {
        try {
            
            $userItemMealDeleted = \DB::table('user_item_meals')->where('user_id', $request->user_id)->where('meal_id', $request->meal_id)->where('item_id', $request->item_id)->delete();

            $userItemSwapDeleted = \DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->delete();
                // Check if at least one deletion was successful
            if ($userItemMealDeleted > 0 || $userItemSwapDeleted > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Food deleted successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching food items found to delete.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addFood(Request $request) 
    {
        // Validate the request
        if($request->type == 'add-more-food'){

            $item = Item::with('swapItems')->find($request->item_id);
            // dd($item->swapItems);
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }

            $userItemMeal = UserItemMeal::where('user_id', $request->user_id)
                            ->where('meal_id', $request->meal_id)
                            ->where('item_id', $request->item_id)
                            ->first();

            if ($userItemMeal) {
                
                // $userItemMeal->qty = $request->qty;
                // $userItemMeal->unit = $request->unit;
                // $userItemMeal->carbs = $request->carbs;
                // $userItemMeal->fat = $request->fat;
                // $userItemMeal->protein = $request->protein;
                // $userItemMeal->selected_qty_unit = $request->selected_qty_unit;
                // $userItemMeal->save();

                return response()->json(['success' => false, 'message' => 'Food already added.']);
            } else {
                $userItemMeal = new UserItemMeal();
                $userItemMeal->user_id = $request->user_id;
                $userItemMeal->meal_id = $request->meal_id;
                $userItemMeal->is_swiped = $item->is_swiped;
                $userItemMeal->item_id = $request->item_id;
                $userItemMeal->qty = $request->qty;
                $userItemMeal->unit = $request->unit;
                $userItemMeal->carbs = $request->carbs;
                $userItemMeal->fat = $request->fat;
                $userItemMeal->protein = $request->protein;
                $userItemMeal->selected_qty_unit = $request->selected_qty_unit;
                $userItemMeal->save();
                
                $swapItems = $item->swapItems;
                
                foreach ($swapItems as $swapItem) {
                    
                    $exists = \DB::table('user_item_swaps')
                        ->where('user_id', $request->user_id)
                        ->where('item_id', $item->id)
                        ->where('swap_item_id', $swapItem->id)
                        ->exists();

                    if (!$exists) {
                        // $deleteSwapFood = UserItemSwap::with('swapItem')
                        // ->where('item_id', $item->id)
                        // ->where('user_id', $request->user_id)
                        // ->delete();

                        \DB::table('user_item_swaps')->insert([
                            'user_id' => $request->user_id,
                            'item_id' => $item->id,
                            'swap_item_id' => $swapItem->id,
                            'qty' => $swapItem->qty,
                            'unit' => $swapItem->unit,
                            'carbs' => $swapItem->carbs,
                            'fat' => $swapItem->fat,
                            'protein' => $swapItem->protein,
                            'selected_qty_unit' => $swapItem->selected_qty_unit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                return response()->json([
                    'success' => true,
                    'item' => $item,
                    'message' => 'Food added successfully.'
                ]);
            }
        }else if($request->type == 'meal-food-add'){

            $item = Item::with('swapItems')->find($request->item_id);
            // dd($item->swapItems);
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }

            $mealIds = $request->meal_ids;
            foreach($mealIds as $mealId) {
                $userItemMeal = UserItemMeal::where('user_id', $request->user_id)
                            ->where('meal_id', $mealId)
                            ->where('item_id', $request->item_id)
                            ->first();

                if ($userItemMeal) {
                    return response()->json(['success' => false, 'message' => 'Food already added.']);
                } else {
                    $userItemMeal = new UserItemMeal();
                    $userItemMeal->user_id = $request->user_id;
                    $userItemMeal->meal_id = $mealId;
                    $userItemMeal->is_swiped = $item->is_swiped;
                    $userItemMeal->item_id = $request->item_id;
                    $userItemMeal->qty = $request->qty;
                    $userItemMeal->unit = $request->unit;
                    $userItemMeal->carbs = $request->carbs;
                    $userItemMeal->fat = $request->fat;
                    $userItemMeal->protein = $request->protein;
                    $userItemMeal->selected_qty_unit = $request->selected_qty_unit;
                    $userItemMeal->save();
                    
                    $swapItems = $item->swapItems;
                    
                    foreach ($swapItems as $swapItem) {
                        
                        $exists = \DB::table('user_item_swaps')
                            ->where('user_id', $request->user_id)
                            ->where('item_id', $item->id)
                            ->where('meal_id', $mealId)
                            ->where('swap_item_id', $swapItem->id)
                            ->exists();

                        if (!$exists) {
                            // $deleteSwapFood = UserItemSwap::with('swapItem')
                            // ->where('item_id', $item->id)
                            // ->where('user_id', $request->user_id)
                            // ->delete();

                            \DB::table('user_item_swaps')->insert([
                                'user_id' => $request->user_id,
                                'item_id' => $item->id,
                                'meal_id' => $mealId,
                                'swap_item_id' => $swapItem->id,
                                'qty' => $swapItem->qty,
                                'unit' => $swapItem->unit,
                                'carbs' => $swapItem->carbs,
                                'fat' => $swapItem->fat,
                                'protein' => $swapItem->protein,
                                'selected_qty_unit' => $swapItem->selected_qty_unit,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'item' => $item,
                'message' => 'Food added successfully.'
            ]);

        } else if($request->type == 'woolworths') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|url',
                'protein' => 'nullable',
                'fat' => 'nullable',
                'carbs' => 'nullable',
                'category' => 'nullable',
                'meal_id' => 'required|exists:meals,id',
                'user_id' => 'required|exists:users,id',
                'serving_per_pack' => 'nullable',
                'serving_size' => 'nullable',
            ]);

            try {
                // Step 1: Download the image from the URL
                $imageContent = file_get_contents($validated['image']);
                if ($imageContent === false) {
                    return response()->json(['success' => false, 'message' => 'Unable to download the image.']);
                }
    
                // Step 2: Generate a unique filename and save to storage
                $imageName = Str::random(32) . '.jpg';  // Generate a random 32-character string and append '.jpg'
                // Full path to store the image in public storage
                $imagePath = 'items/' . $imageName; // Define the folder and filename
            
                // Save the image to storage
                Storage::disk('public')->put($imagePath, $imageContent);
                
                $protein = $validated['protein'] ? rtrim($validated['protein'], 'g') : 0;
                $carbs = $validated['carbs'] ? rtrim($validated['carbs'], 'g') : 0;
                $fat = $validated['fat'] ? rtrim($validated['fat'], 'g') : 0;
                $serving_size_parse = isset($validated['serving_size']) ? $this->parseServingSize($validated['serving_size']) : 0;
                $serving_size = $serving_size_parse != 0 ? $serving_size_parse['serving_size'] : '0';
                $serving_size_unit = $serving_size_parse != 0 ? $serving_size_parse['serving_size_unit'] : 'g';

                if(isDecimal($protein)){
                    $protein = floatval($protein);
                }else {
                    $protein = formatDecimal($protein);
                }
    
                if(isDecimal($carbs)){
                    $carbs = floatval($carbs);
                }else {
                    $carbs = formatDecimal($carbs);
                }
    
                if(isDecimal($fat)){
                    $fat = floatval($fat);
                }else {
                    $fat = formatDecimal($fat);
                }

                $serving_size = isDecimal($serving_size) ? floatval($serving_size) : formatDecimal($serving_size);

                $keywords = explode(" ", strtolower($validated['category']));

                // Search for any matching keyword in the database
                $foodCategory = \App\Models\FoodCategory::where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhereRaw("LOWER(name) LIKE ?", ["%$keyword%"]);
                    }
                })->first();

                if(!$foodCategory){

                    $foodCategory = new \App\Models\FoodCategory();
                    $foodCategory->name = ucwords($validated['category']);
                    $foodCategory->save();
                }
            
                // Step 3: Save food details in the database
                $food = new Item(); // Assuming you have a Food model
                $food->title = $validated['name'];
                $food->protein = cleanDecimal($protein);
                $food->carbs = cleanDecimal($carbs);
                $food->fat = cleanDecimal($fat);
                $food->qty = cleanDecimal($serving_size);
                $food->unit = $serving_size_unit;
                $food->serving_per_pack = $validated['serving_per_pack'];
                $food->serving_size = $serving_size;
                $food->serving_size_unit = $serving_size_unit;
                $food->image = 'items/' . $imageName; // Path to the stored image
                $food->is_swiped = 0;
                $food->category_id = isset($foodCategory) ? $foodCategory->id : null;

                if($food->save()) {
                    $userItemMeal = new UserItemMeal();
                    $userItemMeal->user_id = $request->user_id;
                    $userItemMeal->meal_id = $request->meal_id;
                    $userItemMeal->protein = cleanDecimal($protein);
                    $userItemMeal->carbs = cleanDecimal($carbs);
                    $userItemMeal->fat = cleanDecimal($fat);
                    $userItemMeal->qty = cleanDecimal($serving_size);
                    $userItemMeal->unit = $serving_size_unit;
                    $userItemMeal->is_swiped = $food->is_swiped;
                    $userItemMeal->item_id = $food->id;
                    $userItemMeal->save();
                }

                // Step 4: Redirect with success message
                return response()->json(['success' => true, 'data' => $food, 'message' => 'Food added successfully.']);
            } catch (\Exception $e) {
                dd($e->getMessage());
                Log::error('Error adding food: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to add food ']);
            }
        }else {

            $data = $request->validate([
                'item_id' => 'required|exists:items,id',
                'user_id' => 'required|exists:users,id',
                'meal_id' => 'required|exists:meals,id',
            ]);

            $item = Item::with('swapItems')->find($data['item_id']);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }
            
            $userItemMeal = UserItemMeal::where('user_id', $data['user_id'])
            ->where('meal_id', $data['meal_id'])
            ->where('item_id', $data['item_id'])
            ->first();

            if ($userItemMeal) {
                return response()->json(['success' => false, 'message' => 'Food already added.']);
            } else {
                $userItemMeal = new UserItemMeal();
                $userItemMeal->user_id = $data['user_id'];
                $userItemMeal->meal_id = $data['meal_id'];
                $userItemMeal->is_swiped = $item->is_swiped;
                $userItemMeal->item_id = $data['item_id'];
                $userItemMeal->qty = $item->qty;
                $userItemMeal->unit = $item->unit;
                $userItemMeal->save();
                
                $swapItems = $item->swapItems;
                // dd($swapItems);
                foreach ($swapItems as $swapItem) {
                    
                    $exists = \DB::table('user_item_swaps')
                        ->where('user_id', $request->user_id)
                        ->where('item_id', $item->id)
                        ->where('swap_item_id', $swapItem->id)
                        ->exists();

                    if (!$exists) {
                        $deleteSwapFood = UserItemSwap::with('swapItem')
                        ->where('item_id', $item->id)
                        ->where('user_id', $request->user_id)
                        ->delete();

                        \DB::table('user_item_swaps')->insert([
                            'user_id' => $request->user_id,
                            'item_id' => $item->id,
                            'swap_item_id' => $swapItem->id,
                            'qty' => $swapItem->qty,
                            'unit' => $swapItem->unit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Prepare simplified swapItems array
                $simplifiedSwapItems = $swapItems->map(function ($swapItem) {
                    return [
                        'id' => $swapItem->id,      // Swap item ID
                        'name' => $swapItem->title,  // Swap item name (assuming 'title' holds the name)
                        'qty' => $swapItem->qty,
                        'unit' => $swapItem->unit,
                        'carbs' => $swapItem->carbs,
                        'protein' => $swapItem->protein,
                        'fat' => $swapItem->fat
                    ];
                });

                // Include simplified swapItems in response
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'qty'  => $item->qty,
                        'unit' => $item->unit,
                        'carbs' => $item->carbs,
                        'protein' => $item->protein,
                        'fat'     => $item->fat,
                        'swapItems' => $simplifiedSwapItems  // Pass simplified swap items here
                    ],
                    'message' => 'Food added successfully.'
                ]);
            }
        }
    }

    private function parseServingSize($input) {
        // Remove extra spaces and convert to lowercase
        $input = strtolower(trim($input));
    
        // Remove words like "approximate", "about", "around"
        $input = preg_replace('/\b(approximate|about|around)\b/', '', $input);
    
        // Extract numeric value (including decimals)
        preg_match('/\d+(\.\d+)?/', $input, $matches);
        $value = isset($matches[0]) ? floatval($matches[0]) : 0;
    
        // Determine the unit (g or ml), default to "g" if missing
        $unit = 'g'; // Default unit is grams
        if (strpos($input, 'ml') !== false) {
            $unit = 'ml';
        } elseif (strpos($input, 'g') !== false) {
            $unit = 'g';
        }
    
        return [
            'serving_size' => $value,
            'serving_size_unit' => $unit
        ];
    }

    public function getSwapFoods(Request $request)
    {
        $foodId = $request->food_id;
        $food = \App\Models\Item::with('swapItems')->where('id', $foodId)->first();
        
        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found.'
            ]);
        }
    
        $swapFoodIds = $request->swap_food_ids;
        
        // Get swap foods from the database
        $swapFoods = $food->swapItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->title, // Assuming 'name' is the column for food name
                'qty' => $item->qty
            ];
        })->toArray();
    
        // If request contains additional swap food IDs, merge them
        if ($swapFoodIds) {
            if ($request->has('swap_food_ids') && is_array($request->swap_food_ids)) {

                foreach ($swapFoodIds as $swapId) {
                    // Ensure the food is not already in the swap list
                    if (!in_array($swapId, array_column($swapFoods, 'id'))) {
                        $item = \App\Models\Item::find($swapId);
                        if ($item) {
                            $swapFoods[] = [
                                'id' => $item->id,
                                'name' => $item->title,
                                'qty' => $item->qty
                            ];
                        }
                    }
                }
            }else {
                $swapId = $request->swap_food_ids;
                $item = \App\Models\Item::find($swapId);
                if ($item) {
                    $swapFoods[] = [
                        'id' => $item->id,
                        'name' => $item->title,
                        'qty' => $item->qty
                    ];
                }
            }
        }
        // dd($swapFoods);
        return response()->json([
            'success' => true,
            'swapFoods' => $swapFoods
        ]);
    }
    
    public function saveSwapFood(Request $request) 
    {
        $foodId = $request->food_id;
        $swapFoodIds = $request->swap_foods;
        $mealIds = $request->meal_ids;
        $foodQty = $request->food_qty ?? null;
        $swapFoodQty = $request->swap_food_qty ?? null;
    
        // Initialize response data
        $savedItem = null;
        $savedSwapItems = [];
    
        // Handle UserItemMeal entries
        foreach ($mealIds as $mealId) {
            $existingMeal = UserItemMeal::with('items')
                ->where('item_id', $foodId)
                ->where('meal_id', $mealId)
                ->first();
    
            if ($existingMeal) {
                // ✅ Update item quantity if record exists
                $existingMeal->update([
                    'qty' => $foodQty,
                    'unit' => $request->food_unit,
                    'carbs' => $request->carbs,
                    'protein' => $request->protein,
                    'fat' => $request->fat,
                    'updated_at' => now(),
                ]);
                $savedItem = $existingMeal->load('items');
            } else {
                $savedItem = UserItemMeal::create([
                    'item_id' => $foodId,
                    'meal_id' => $mealId,
                    'user_id' => $request->user_id,
                    'qty' => $foodQty,
                    'unit' => $request->food_unit,
                    'carbs' => $request->carbs,
                    'protein' => $request->protein,
                    'fat' => $request->fat,
                    'created_at' => now(),
                    'is_swiped' => 0,
                    'updated_at' => now(),
                ]);
    
                // Load related `items` after saving
                $savedItem->load('items');
            }
        }
    
        // Handle swap food entries (array or single entry)
        if ($swapFoodIds) {
            if (is_array($swapFoodIds)) {
                foreach ($swapFoodIds as $swapFood) {
                    if (!isset($swapFood['id'])) continue;
    
                    $existingSwap = UserItemSwap::with('swapItem')
                        ->where('item_id', $foodId)
                        ->where('swap_item_id', $swapFood['id'])
                        ->first();
    
                    if ($existingSwap) {
                        // ✅ Update swap item quantity if record exists
                        $existingSwap->update([
                            'qty' => $swapFoodQty,
                            'carbs'=> $request->swap_food_carbs,
                            'protein' => $request->swap_food_protein,
                            'fat' => $request->swap_food_fat,
                            'unit' => $request->swap_food_unit,
                            'updated_at' => now(),

                        ]);
                        $savedSwapItems[] = $existingSwap->load('swapItem');
                    } else {
                        $deleteSwapFood = UserItemSwap::with('swapItem')
                        ->where('item_id', $foodId)
                        ->where('user_id', $request->user_id)
                        ->delete();

                        $swapItem = UserItemSwap::create([
                            'item_id' => $foodId,
                            'swap_item_id' => $swapFood['id'],
                            'user_id' => $request->user_id,
                            'qty' => $swapFoodQty,
                            'carbs'=> $request->swap_food_carbs,
                            'protein' => $request->swap_food_protein,
                            'fat' => $request->swap_food_fat,
                            'unit' => $request->swap_food_unit,
                        ]);
    
                        // Load related `swapItem` after saving
                        $swapItem->load('swapItem');
                        $savedSwapItems[] = $swapItem;
                    }
                }
            } else {
                $existingSwap = UserItemSwap::with('swapItem')
                    ->where('item_id', $foodId)
                    ->where('swap_item_id', $swapFoodIds)
                    ->first();
    
                if ($existingSwap) {
                    // ✅ Update swap item quantity if record exists
                    $existingSwap->update([
                        'qty' => $swapFoodQty,
                        'carbs'=> $request->swap_food_carbs,
                        'protein' => $request->swap_food_protein,
                        'fat' => $request->swap_food_fat,
                        'unit' => $request->swap_food_unit,
                        'updated_at' => now(),
                    ]);
                    $savedSwapItems[] = $existingSwap->load('swapItem');
                } else {
                    $deleteSwapFood = UserItemSwap::with('swapItem')
                        ->where('item_id', $foodId)
                        ->where('user_id', $request->user_id)
                        ->delete();

                    $swapItem = UserItemSwap::create([
                        'item_id' => $foodId,
                        'swap_item_id' => $swapFoodIds,
                        'user_id' => $request->user_id,
                        'qty' => $swapFoodQty,
                        'carbs'=> $request->swap_food_carbs,
                        'protein' => $request->swap_food_protein,
                        'fat' => $request->swap_food_fat,
                        'unit' => $request->swap_food_unit,
                        'created_at' => now(),
                    ]);
    
                    // Load related `swapItem` after saving
                    $swapItem->load('swapItem');
                    $savedSwapItems[] = $swapItem;
                }
            }
        } else {
            $item = Item::with('swapItems')->find($foodId);
            $savedSwapItems = $item->swapItems;
        }
    
        return response()->json([
            'success' => true,
            'item' => $savedItem,
            'swapItems' => $savedSwapItems
        ]);
    }
    

    // public function saveSwapFood(Request $request) 
    // {
    //     $foodId = $request->food_id;
    //     $swapFoodIds = $request->swap_foods;
    //     $mealIds = $request->meal_ids;
    //     $foodQty = $request->food_qty ?? null;
    //     $swapFoodQty = $request->swap_food_qty ?? null;

    //     // Initialize response data
    //     $savedItem = null;
    //     $savedSwapItems = [];

    //     foreach ($mealIds as $mealId) {
    //         $existingMeal = \DB::table('user_item_meals')
    //             ->where('item_id', $foodId)
    //             ->where('meal_id', $mealId)
    //             ->first();

    //         if (!$existingMeal) {
    //             \DB::table('user_item_meals')->insert([
    //                 'item_id' => $foodId,
    //                 'meal_id' => $mealId,
    //                 'user_id' => $request->user_id,
    //                 'qty' => $foodQty,
    //                 'is_swiped' => 0,
    //             ]);

    //             // Capture saved item
    //             $savedItem = \DB::table('user_item_meals')
    //                 ->where('item_id', $foodId)
    //                 ->where('meal_id', $mealId)
    //                 ->first();
    //         }
    //     }

    //     // Handle swap food entries (array or single entry)
    //     if ($swapFoodIds) {
    //         if (is_array($swapFoodIds)) {
    //             foreach ($swapFoodIds as $swapFood) {
    //                 if (!isset($swapFood['id'])) continue;

    //                 $existingSwap = \DB::table('user_item_swaps')
    //                     ->where('item_id', $foodId)
    //                     ->where('swap_item_id', $swapFood['id'])
    //                     ->first();

    //                 if (!$existingSwap) {
    //                     \DB::table('user_item_swaps')->insert([
    //                         'item_id' => $foodId,
    //                         'swap_item_id' => $swapFood['id'],
    //                         'user_id' => $request->user_id,
    //                         'qty' => $swapFoodQty,
    //                     ]);

    //                     // Capture saved swap item
    //                     $savedSwapItems[] = \DB::table('user_item_swaps')
    //                         ->where('item_id', $foodId)
    //                         ->where('swap_item_id', $swapFood['id'])
    //                         ->first();
    //                 }
    //             }
    //         } else {
    //             $existingSwap = \DB::table('user_item_swaps')
    //                 ->where('item_id', $foodId)
    //                 ->where('swap_item_id', $swapFoodIds)
    //                 ->first();

    //             if (!$existingSwap) {
    //                 \DB::table('user_item_swaps')->insert([
    //                     'item_id' => $foodId,
    //                     'swap_item_id' => $swapFoodIds,
    //                     'user_id' => $request->user_id,
    //                     'qty' => $swapFoodQty,
    //                 ]);

    //                 // Capture saved swap item
    //                 $savedSwapItems[] = \DB::table('user_item_swaps')
    //                     ->where('item_id', $foodId)
    //                     ->where('swap_item_id', $swapFoodIds)
    //                     ->first();
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'item' => $savedItem,
    //         'swapItems' => $savedSwapItems
    //     ]);
    // }

}