<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Meal;
use App\Models\Item;
use App\Models\UserPlan;
use App\Models\ItemMeal;
use App\Models\UserItemMeal;
use App\Models\UserItemSwap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Models\UserCategory;
use App\Models\UserSubCategory;
use App\Models\UserMeal;
use App\Models\UserItem;
use App\Models\UserSwapItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivePlanMail;
use App\Models\User;

use function PHPUnit\Framework\isEmpty;

class PurchasePlanController extends Controller
{
    // List all payments with pagination
    public function index()
    {
        // Fetch payments with pagination (you can adjust per page as needed)
        $payments = Payment::with('plan:id,name')->get();
        $planIds = array_unique(array_column($payments->toArray(), 'plan_id'));
        $userIds = array_unique(array_column($payments->toArray(), 'user_id'));

        $useWisePlanData = [];
        if($userIds && $planIds) {
            $userPlanQuery = UserPlan::select([
                'id',
                'user_id',
                'plan_id'
            ])->whereIn('user_id', $userIds)->whereIn('plan_id', $planIds)->get()->toArray();

            if($userPlanQuery) {
                foreach($userPlanQuery as $plan) {
                    $useWisePlanData[$plan['user_id']][] = $plan['plan_id'];
                }
            }
        }

        // Return the view with the payments data
        return view('backend.pages.plan.purchase-plans', compact('payments', 'useWisePlanData'));
    }

    public function create($id)
    {   
        // Fetch the plan with its related data
        $payment = Payment::findOrFail($id);
        $plan = Plan::find($payment->plan_id);
        $subPlans = $plan->subPlans()->pluck('sub_plan_id')->toArray();

        $plans = Plan::with([
            'subPlans.categories.subCategories.meals.items.swapItems',
        ])->where('id',$payment->plan_id)
        ->when($subPlans, function ($query) use ($subPlans) {
            return $query->orWhereIn('id', $subPlans);
        })->get();

        // Get all options for each relationship
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $meals = Meal::all();
        $items = Item::where('is_swiped',0)->get();

        $step5Foods = Item::get();
        // ->filter(function ($item) use ($perPlanSelectedFoods) {
        //     return in_array($item->title, $perPlanSelectedFoods);
        // })
        // ->groupBy('category_id');

        $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
            $query->where('form_slug', 'food_preference')
                    ->orderBy('id', 'asc');
                // ->whereIn('question', [
                //     'Grains', 'Legumes, beans and pulses',
                //     'Eggs', 'Meat', 'Meat Alternatives',
                //     'Seafood', 'Dairy', 'Non-Dairy',
                //     'Fruit', 'Vegetable', 'Oils / Butter'
                // ]);
            }])
        ->where('payment_id', $payment->id)->first();

        $foodPreferences = collect();

        if (!empty($userPrePlan) && $userPrePlan->prePlanDetails) {
            foreach ($userPrePlan->prePlanDetails as $detail) {
                $question = $detail->question ?? 'Unknown'; // fallback if question missing
                $answers = json_decode($detail->answer, true);

                // Make sure $answers is array and skip nulls
                if (is_array($answers)) {
                    $filteredAnswers = array_filter($answers); // remove nulls
                    if (!empty($filteredAnswers)) {
                        $foodPreferences->put($question, collect($filteredAnswers));
                    }
                }
            }
        }
        // dd($perPlanSelectedFoods);
        $otherFoods = $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
            $query->where('form_slug', 'food_preference')
                ->whereIn('question', ['Cuisines', 'Snacks']); // Add your question filters here
        }])->where('payment_id', $id)->first();

        $groupedAnswers = [];

        if (isset($otherFoods->prePlanDetails)) {
            foreach ($otherFoods->prePlanDetails as $detail) {
                $question = $detail->question ?? null;
                if (!$question) continue;

                $answers = json_decode($detail->answer, true);

                // Only proceed if decoded answer is an array
                if (is_array($answers)) {
                    // Assign the full answer array preserving keys under the question name
                    // If multiple entries for the same question exist, merge them
                    if (!isset($groupedAnswers[$question])) {
                        $groupedAnswers[$question] = $answers;
                    } else {
                        // Merge arrays preserving keys; keys in later arrays override earlier
                        $groupedAnswers[$question] = array_merge($groupedAnswers[$question], $answers);
                    }
                }
            }
        }
        $perPlanSelectedFoods =[];
        // dd($groupedAnswers);
        $otherFoods = $groupedAnswers;
        return view('backend.pages.plan.purchase-plan-create', compact(
            'payment',
            'plans',
            'categories',
            'subCategories',
            'meals',
            'items',
            'step5Foods', 'perPlanSelectedFoods', 'otherFoods' ,'foodPreferences'
        ));
    }

    // public function store(Request $request)
    // {
    //     try {
    //         // $request->validate([
    //         //     'user_id' => 'required|integer',
    //         //     'plan_id' => 'required|array',
    //         //     'plan_id.*' => 'required|integer',
    //         //     'meal_time' => 'required|array',
    //         //     'meal_time.*' => 'required|string',
    //         //     'category_id' => 'required|array',
    //         //     'category_id.*' => 'required|integer',
    //         //     'meal_id' => 'required|array',
    //         //     'meal_id.*' => 'required|integer',
    //         //     'item_id' => 'required|array',
    //         //     'item_id.*' => 'required|integer',
    //         //     'swap_item_id' => 'required|array',
    //         //     'swap_item_id.*' => 'required|integer',
    //         // ]);

    //         // dd($request->all());
    //         DB::beginTransaction();
    //         $userId = $request->user_id;
    //         $planIds = $request->plan_id;
    //         $categories = $request->meal_times;
    //         $mealIds = $request->meals;
    //         $itemIds = $request->items;
    //         $swapItemIds = $request->swap_items;

    //         // Process meal items and swap items
    //         $mealItems = [];
    //         $swapItems = [];

    //         foreach ($mealIds as $mealId) {
    //             $mealItems[$mealId] = $itemIds[$mealId] ?? [];
    //             $swapItems[$mealId] = $swapItemIds[$mealId] ?? [];
    //         }

    //         // Get all items and swap items
    //         $allItems = Item::whereIn('id', array_merge(...array_values($itemIds)))->get();
    //         $allSwapItems = Item::whereIn('id', array_merge(...array_values($swapItemIds)))->get();

    //         // Process categories and meal times
    //         $categoryData = [];
    //         foreach ($categories as $planId => $planCategories) {
    //             foreach ($planCategories as $mealTime => $categoryId) {
    //                 $categoryData[$planId][$mealTime] = $categoryId;
    //             }
    //         }

    //         // Process items and swap items
    //         $items = [];
    //         $swapItems = [];
    //         foreach ($mealIds as $planId => $planMeals) {
    //             foreach ($planMeals as $mealTime => $mealId) {
    //                 $items[$planId][$mealTime][$mealId] = $itemIds[$planId][$mealTime][$mealId] ?? [];
    //                 $swapItems[$planId][$mealTime][$mealId] = $swapItemIds[$planId][$mealTime][$mealId] ?? [];
    //             }
    //         }

    //         // Process user plans and meals
    //         foreach ($planIds as $planId) {
    //             // Create or update user plan
    //             $userPlan = UserPlan::updateOrInsert(
    //                 ['user_id' => $userId, 'plan_id' => $planId],
    //                 ['created_by' => auth()->id(), 'updated_by' => auth()->id()]
    //             );

    //             if (!$userPlan) {
    //                 $userPlan = UserPlan::where('user_id', $userId)
    //                     ->where('plan_id', $planId)
    //                     ->first();
    //             }

    //             // Get existing meals
    //             $existingMeals = UserMeal::where('user_plan_id', $userPlan->id)->get();
    //             $existingMealIds = $existingMeals->pluck('id')->toArray();

    //             // Remove meals that are no longer associated
    //             $mealsToRemove = array_diff($existingMealIds, array_merge(...array_values($mealIds[$planId] ?? [])));
    //             if (!empty($mealsToRemove)) {
    //                 UserMeal::whereIn('id', $mealsToRemove)->delete();
    //             }

    //             // Process each meal time
    //             foreach ($categories[$planId] ?? [] as $mealTime => $categoryId) {
    //                 // Create or update user category
    //                 $userCategory = UserCategory::updateOrInsert(
    //                     [
    //                         'user_plan_id' => $userPlan->id,
    //                         'id' => $categoryId
    //                     ],
    //                     [
    //                         'created_by' => auth()->id(),
    //                         'updated_by' => auth()->id()
    //                     ]
    //                 );

    //                 if (!$userCategory) {
    //                     $userCategory = UserCategory::where('user_plan_id', $userPlan->id)
    //                         ->where('id', $categoryId)
    //                         ->first();
    //                 }

    //                 // Get subcategories for this category
    //                 $subCategories = SubCategory::where('category_id', $categoryId)->get();

    //                 // Process meals for this category
    //                 if (isset($mealIds[$planId][$mealTime])) {
    //                     foreach ($mealIds[$planId][$mealTime] as $mealId) {
    //                         // Get the meal to find its subcategory
    //                         $meal = Meal::with('subCategory')->find($mealId);
                            
    //                         if ($meal && $meal->subCategory) {
    //                             // Create or update user subcategory
    //                             $userSubCategory = UserSubCategory::updateOrInsert(
    //                                 [
    //                                     'user_plan_id' => $userPlan->id,
    //                                     'user_category_id' => $userCategory->id,
    //                                     'id' => $meal->subCategory->id
    //                                 ],
    //                                 [
    //                                     'created_by' => auth()->id(),
    //                                     'updated_by' => auth()->id()
    //                                 ]
    //                             );

    //                             if (!$userSubCategory) {
    //                                 $userSubCategory = UserSubCategory::where('user_plan_id', $userPlan->id)
    //                                     ->where('user_category_id', $userCategory->id)
    //                                     ->where('id', $meal->subCategory->id)
    //                                     ->first();
    //                             }

    //                             // Create or update user meal
    //                             $userMeal = UserMeal::updateOrInsert(
    //                                 [
    //                                     'user_plan_id' => $userPlan->id,
    //                                     'user_category_id' => $userCategory->id,
    //                                     'user_sub_category_id' => $userSubCategory->id,
    //                                     'id' => $mealId
    //                                 ],
    //                                 [
    //                                     'created_by' => auth()->id(),
    //                                     'updated_by' => auth()->id()
    //                                 ]
    //                             );

    //                             if (!$userMeal) {
    //                                 $userMeal = UserMeal::where('user_plan_id', $userPlan->id)
    //                                     ->where('user_category_id', $userCategory->id)
    //                                     ->where('user_sub_category_id', $userSubCategory->id)
    //                                     ->where('id', $mealId)
    //                                     ->first();
    //                             }

    //                             // Process items for this meal
    //                             if (isset($items[$planId][$mealTime][$mealId])) {
    //                                 foreach ($items[$planId][$mealTime][$mealId] as $itemId) {
    //                                     // Create or update user item
    //                                     $userItem = UserItem::updateOrInsert(
    //                                         [
    //                                             'user_plan_id' => $userPlan->id,
    //                                             'user_category_id' => $userCategory->id,
    //                                             'user_sub_category_id' => $userSubCategory->id,
    //                                             'user_meal_id' => $userMeal->id,
    //                                             'id' => $itemId
    //                                         ],
    //                                         [
    //                                             'created_by' => auth()->id(),
    //                                             'updated_by' => auth()->id()
    //                                         ]
    //                                     );

    //                                     if (!$userItem) {
    //                                         $userItem = UserItem::where('user_plan_id', $userPlan->id)
    //                                             ->where('user_category_id', $userCategory->id)
    //                                             ->where('user_sub_category_id', $userSubCategory->id)
    //                                             ->where('user_meal_id', $userMeal->id)
    //                                             ->where('id', $itemId)
    //                                             ->first();
    //                                     }

    //                                     // Process swap items for this item
    //                                     if (isset($swapItems[$planId][$mealTime][$mealId][$itemId])) {
    //                                         foreach ($swapItems[$planId][$mealTime][$mealId][$itemId] as $swapItemId) {
    //                                             UserSwapItem::updateOrInsert(
    //                                                 [
    //                                                     'user_plan_id' => $userPlan->id,
    //                                                     'user_category_id' => $userCategory->id,
    //                                                     'user_sub_category_id' => $userSubCategory->id,
    //                                                     'user_meal_id' => $userMeal->id,
    //                                                     'user_item_id' => $userItem->id,
    //                                                     'id' => $swapItemId
    //                                                 ],
    //                                                 [
    //                                                     'created_by' => auth()->id(),
    //                                                     'updated_by' => auth()->id()
    //                                                 ]
    //                                             );
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         if ($request->action === 'save_and_exit') {
    //             return redirect()->route('admin.purchase-plans.index')
    //                 ->with('success', 'User Plan created successfully.');
    //         }

    //         return redirect()->back()
    //             ->with('success', 'User Plan created successfully.');

    //     } catch (\Exception $e) {
    //         dd($e);
    //         DB::rollBack();
    //         \Illuminate\Support\Facades\Log::error('Error in store method: ' . $e->getMessage());
    //         return redirect()->back()
    //             ->with('error', 'An error occurred while creating the user plan.')
    //             ->withInput();
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $payment = Payment::findOrFail($request->payment_id);
            DB::beginTransaction();

            // Get payment and related data
            $payment = Payment::with('user')->findOrFail($request->payment_id);
         
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
                            
                            $categoriesByMeal = \DB::table('meal_sub_category')
                                    ->whereIn('meal_id', $mealIds)
                                    ->pluck('sub_category_id')
                                    ->unique()
                                    ->toArray();
                            $mealTimeCategories = \DB::table('subcategory_category')
                                ->where('category_id', $mealTimeId)
                                ->pluck('sub_category_id')
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
                                        $categoriesByMeal = \DB::table('meal_sub_category')
                                            ->where('meal_id', $mealId)
                                            ->where('sub_category_id', $categoryId)
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
                        ->first();
            
                    $existingMeals = \DB::table('user_meals')
                        ->where('user_plan_id', $userPlan->id)
                        ->pluck('id')
                        ->toArray();
            
                    $newMeals = isset($meals[$planId]) ? Arr::flatten($meals[$planId]) : [];
                    $mealsToRemove = array_diff($existingMeals, $newMeals);
            
                    if (!empty($mealsToRemove)) {
                        \DB::table('user_meals')
                            ->where('user_plan_id', $userPlan->id)
                            ->whereIn('id', $mealsToRemove)
                            ->delete();
                    }
                   
                    if (isset($request->meal_times[$planId])) {
                        foreach (array_unique($request->meal_times[$planId]) as $mealTimeId) {
                            $userMealTimeId = \DB::table('user_categories')->updateOrInsert(
                                ['user_plan_id' => $userPlan->id, 'id' => $mealTimeId],
                                ['created_at' => now(), 'updated_at' => now()]
                            );
            
                            $userMealTime = \DB::table('user_categories')
                                ->where('user_plan_id', $userPlan->id)
                                ->where('id', $mealTimeId)
                                ->first();
            
                            if (isset($categories[$planId][$mealTimeId])) {
                                foreach ($categories[$planId][$mealTimeId] as $categoryId) {
                                    \DB::table('user_sub_categories')->updateOrInsert(
                                        ['user_plan_id' => $userPlan->id, 'user_category_id' => $userMealTime->id, 'id' => $categoryId],
                                        ['created_at' => now(), 'updated_at' => now()]
                                    );
            
                                    $userCategory = \DB::table('user_sub_categories')
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_category_id', $userMealTime->id)
                                        ->where('id', $categoryId)
                                        ->first();
            
                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        foreach ($meals[$planId][$mealTimeId][$categoryId] as $mealId) {
                                           
                                            $userMeal = \DB::table('user_meals')->where([
                                                'user_plan_id' => $userPlan->id,
                                                'user_category_id' => $userMealTime->id,
                                                'user_sub_category_id' => $userCategory->id,
                                                'id' => $mealId,
                                            ])->first();

                                            if (!$userMeal) {
                                                $userMeal = \DB::table('user_meals')->insertGetId([
                                                    'user_plan_id' => $userPlan->id,
                                                    'user_category_id' => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id' => $mealId,
                                                    'created_at' => now(),
                                                    'updated_at' => now(),
                                                ]);

                                                $userMeal = \DB::table('user_meals')->where([
                                                    'user_plan_id' => $userPlan->id,
                                                    'user_category_id' => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id' => $mealId,
                                                ])->first();
                                            }
                                            $userMealId = $userMeal->id ?? null;

                                            $mealItems = \App\Models\ItemMeal::where('meal_id', $mealId)->get();
                                            foreach ($mealItems as $mealItem) {
                                                $mealExist = \DB::table('user_item_meals')
                                                ->where('user_id', $request->user_id)
                                                ->where('meal_id', $mealId)
                                                ->where('item_id', $mealItem->item_id)
                                                ->first();
                                                
                                                if (!$mealExist) {
                                                   
                                                    UserItemMeal::create([
                                                        'user_id' => $request->user_id,
                                                        'meal_id' => $mealId,
                                                        'item_id' => $mealItem->item_id,
                                                        'qty' => $mealItem->item_qty,
                                                        'unit' => $mealItem->item_qty_unit,
                                                        'carbs' => $mealItem->carbs,
                                                        'protein' => $mealItem->protein,
                                                        'fat' => $mealItem->fat,
                                                        'energy' => $mealItem->energy,
                                                        'selected_qty_unit' => $mealItem->selected_qty_unit,
                                                        'created_at' => now(),
                                                        'updated_at' => now(),
                                                    ]);

                                                    $itemSwapIds = \DB::table('item_swaps')->where('item_id', $mealItem->item_id)->pluck('swap_item_id')->toArray();
                                                    $itemSwaps = Item::whereIn('id', $itemSwapIds)->get();
                                                    // dd($itemSwaps);
                                                    foreach ($itemSwaps as $itemSwap) {
                                                        $swapItemExist = \DB::table('user_item_swaps')
                                                        ->where('user_id', $request->user_id)
                                                        ->where('meal_id', $mealId)
                                                        ->where('item_id', $mealItem->item_id)
                                                        ->where('swap_item_id', $itemSwap->id)
                                                        ->first();
                                                        // dd($swapItemExist);
                                                        // Insert item swaps for the user
                                                        if(!$swapItemExist) {
                                                            UserItemSwap::create([
                                                                'user_id' => $request->user_id,
                                                                'meal_id' => $mealId,
                                                                'item_id' => $mealItem->item_id,
                                                                'swap_item_id' => $itemSwap->id,
                                                                'qty' => $itemSwap->qty,
                                                                'carbs' => $itemSwap->carbs,
                                                                'protein' => $itemSwap->protein,
                                                                'fat' => $itemSwap->fat,
                                                                'energy' => $itemSwap->energy,
                                                                'unit' => $itemSwap->unit,
                                                                'selected_qty_unit' => $itemSwap->selected_qty_unit,
                                                                'created_at' => now(),
                                                                'updated_at' => now(),
                                                            ]);
                                                        }
                                                    }
                                                }
                                            }

                                            $existingItems = \DB::table('user_items')
                                                ->where('user_meal_id', $userMealId)
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                                ->pluck('id')
                                                ->toArray();
                                            // dd($existingItems);
                                            $currentItems = isset($items[$planId][$mealTimeId][$categoryId][$mealId])
                                                ? $items[$planId][$mealTimeId][$categoryId][$mealId]
                                                : [];
            
                                            $itemsToRemove = array_diff($existingItems, $currentItems);
                                            // dd($itemsToRemove);
                                            if (!empty($itemsToRemove)) {
                                                
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

                                            \DB::table('user_items')
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                                ->where('user_meal_id', $userMealId)
                                                ->delete();
            
                                            // dd($currentItems);
                                            foreach ($currentItems as $itemId) {
                                                \DB::table('user_items')->updateOrInsert(
                                                    [
                                                        'user_plan_id' => $userPlan->id,
                                                        'user_category_id' => $userMealTime->id,
                                                        'user_sub_category_id' => $userCategory->id,
                                                        'user_meal_id' => $userMealId,
                                                        'id' => $itemId
                                                    ]
                                                );
            
                                                $userItem = \DB::table('user_items')
                                                    ->where('user_plan_id', $userPlan->id)
                                                    ->where('user_category_id', $userMealTime->id)
                                                    ->where('user_sub_category_id', $userCategory->id)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('id', $itemId)
                                                    ->first();
                                              
                                                $existingSwapItems = \DB::table('user_swap_items')
                                                    ->where('user_plan_id', $userPlan->id)
                                                    ->where('user_category_id', $userMealTime->id)
                                                    ->where('user_sub_category_id', $userCategory->id)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('user_item_id', $userItem->id)
                                                    ->pluck('id')
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
                                                // dd($swapItemsToRemove);
                                                if (!empty($swapItemsToRemove)) {
                                                    \DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan->id)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItem->id)
                                                        ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_sub_category_id', $userCategory->id)
                                                        ->whereIn('id', $swapItemsToRemove)
                                                        ->delete();
                                                }
                                                
                                                foreach ($currentSwapItems as $swapItemId) {
                                                    \DB::table('user_swap_items')->updateOrInsert(
                                                        [
                                                            'user_plan_id' => $userPlan->id,
                                                            'user_meal_id' => $userMealId,
                                                            'user_item_id' => $userItem->id,
                                                            'user_category_id' => $userMealTime->id,
                                                            'user_sub_category_id' => $userCategory->id,
                                                            'id' => $swapItemId
                                                        ],
                                                        ['created_at' => now(), 'updated_at' => now()]
                                                    );
                                                }
                                                $a = \DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan->id)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItem->id)
                                                        ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_sub_category_id', $userCategory->id)
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

            DB::commit();

            $action = $request->input('action');
            if ($action === 'save_exit') {
                return redirect()->route('admin.purchase-plans.index')
                    ->with('success', 'User Plan created successfully.');
            }

            // For 'save' action, redirect back to edit page with correct parameters
            return redirect()->route('admin.purchase-plans.edit', [
                'user' => $payment->user_id,
                'plan' => $payment->id
            ])->with('success', 'User Plan saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            Log::error('Error creating User Plan: ' . $e->getMessage());
            Log::error('Request Data: ', $request->all());
            return redirect()->route('admin.purchase-plans.index')
                ->with('error', 'Failed to create User Plan. Error: ' . $e->getMessage());
        }
    }

    public function edit(User $user, $planId)
    {
        // \DB::enableQueryLog();
        // dd("Edit User Plan");
        try {
       
            $payment = Payment::find($planId);
            $plan = $payment->plan;
            $subPlanIds = $plan->subPlans->pluck('id')->toArray();

            $userPlans = UserPlan::with([
                'plan', 
                // 'userCategories.userSubCategories.userMeals.userItems.userSwapItems',
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
            // dd($userPlans->userCategories);
            $selectedMeals = [];
            $selectedItems = [];
            $selectedSwapItems = [];

            // Initialize Nutrition Totals
            $totalCarbs = 0;
            $totalFat = 0;
            $totalProtein = 0;
            $totalEnergy = 0;

            foreach ($userPlans as $userPlan) {
                foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userCategory) {
                    $selectedMeals[$userPlan->plan_id][$userCategory->id] = 
                        $userCategory->userMeals->where('user_plan_id', $userPlan->id)->pluck('id')->toArray();

                    foreach($userCategory->userSubCategories->where('user_plan_id', $userPlan->id)->where('user_category_id', $userCategory->id) as $userSubCategory) {
                        foreach ($userSubCategory->userMeals->where('user_plan_id', $userPlan->id)->where('user_category_id', $userCategory->id) as $userMeal) {
                            $mealId = $userMeal->id;
                            $selectedItems[$userCategory->id][$mealId] = 
                                $userMeal->userItems->where('user_plan_id', $userPlan->id)
                                            ->where('user_category_id', $userCategory->id)
                                            ->where('user_sub_category_id', $userSubCategory->id)
                                            ->pluck('id')->toArray();

                            foreach ($userMeal->userItems->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userCategory->id)
                                                ->where('user_sub_category_id', $userSubCategory->id) as $userItem) {
                                $item = Item::find($userItem->id);
                                if ($item) {
                                    $totalCarbs += $item->carbs ?? 0;
                                    $totalFat += $item->fat ?? 0;
                                    $totalProtein += $item->protein ?? 0;
                                    $totalEnergy += floatval($item->energy ?? 0);
                                }

                                $selectedSwapItems[$userCategory->id][$mealId][$userItem->id] = 
                                    $userItem->userSwapItems->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userCategory->id)
                                                ->where('user_sub_category_id', $userSubCategory->id)->pluck('id')->toArray();
                            }
                        }
                    }
                }
            }
            // dd($selectedMeals);
            $categories = Category::all();
            $subCategories = SubCategory::all();
            $meals = Meal::all();
            $items = Item::where('is_swiped', 0)->get();

            $activity = UserPlan::with([
                'modifiedBy',
            ])
            ->where('user_id', $payment->user_id)
            ->where('plan_id', $payment->plan_id)
            ->orderBy('updated_at', 'desc')
            ->first();

            $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
                $query->where('form_slug', 'food_preference')
                        ->orderBy('id', 'asc');
                }])
                ->where('payment_id', $payment->id)->first();

            $foodPreferences = collect();

            if (!empty($userPrePlan) && $userPrePlan->prePlanDetails) {
                foreach ($userPrePlan->prePlanDetails as $detail) {
                    $question = $detail->question ?? 'Unknown';
                    $answers = json_decode($detail->answer, true);

                    if (is_array($answers)) {
                        $filteredAnswers = array_filter($answers);
                        if (!empty($filteredAnswers)) {
                            $foodPreferences->put($question, collect($filteredAnswers));
                        }
                    }
                }
            }

            $perPlanSelectedFoods =[];
            $step5Foods = Item::get();
            $otherFoods = $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
                $query->where('form_slug', 'food_preference')
                    ->whereIn('question', ['Cuisines', 'Snacks']);
            }])->where('payment_id', $payment->id)->first();

            $groupedAnswers = [];

            if (isset($otherFoods->prePlanDetails)) {
                foreach ($otherFoods->prePlanDetails as $detail) {
                    $question = $detail->question ?? null;
                    if (!$question) continue;

                    $answers = json_decode($detail->answer, true);

                    if (is_array($answers)) {
                        if (!isset($groupedAnswers[$question])) {
                            $groupedAnswers[$question] = $answers;
                        } else {
                            $groupedAnswers[$question] = array_merge($groupedAnswers[$question], $answers);
                        }
                    }
                }
            }
            $otherFoods = $groupedAnswers;

            // $queries = \DB::getQueryLog();
            // \Log::info('Queries executed:', $queries);

            // dd($userPlans);
            // $userPlan = $userPlans->first();
            return view('backend.pages.plan.purchase-plan-edit', compact(
                'userPlans','categories', 'subCategories', 'meals', 'items',
                'selectedMeals', 'selectedItems', 'selectedSwapItems',
                'activity', 'payment', 'step5Foods', 'perPlanSelectedFoods', 'subCategories',
                'totalCarbs', 'totalFat', 'totalProtein', 'totalEnergy', 'otherFoods', 'foodPreferences'
            ));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            // Find the UserPlan by ID
            $payment = Payment::findOrFail($request->payment_id);
            $action = $request->input('action');
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
                            $categoriesByMeal = \DB::table('meal_sub_category')
                                ->whereIn('meal_id', $mealIds)
                                ->pluck('sub_category_id')
                                ->unique()
                                ->toArray();
                            $mealTimeCategories = \DB::table('subcategory_category')
                                ->where('category_id', $mealTimeId)
                                ->pluck('sub_category_id')
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
                    $mealTimeIds = array_unique($request->meal_times[$planId]);

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($categories[$planId][$mealTimeId])) {
                            $categoryIds = $categories[$planId][$mealTimeId];
                            // dd($categoryIds);
                            foreach ($categoryIds as $categoryId) {
                                if (isset($request->meals[$planId][$mealTimeId])) {
                                    $mealIds = $request->meals[$planId][$mealTimeId];
                                    foreach ($mealIds as $mealId) {
                                        $categoriesByMeal = \DB::table('meal_sub_category')
                                            ->where('meal_id', $mealId)
                                            ->where('sub_category_id', $categoryId)
                                            ->exists();

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
                'userCategories.userSubcategories.userMeals.userItems',
            ])
            ->where('user_id', $request->user_id)
            ->whereIn('plan_id', $request->plan_id)
            ->get();

            // Check if the UserPlan exists
            if (!$userPlans) {
                return redirect()->route('admin.purchase-plans.index')
                                ->with('error', 'User Plan not found.');
            }
            
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
                        ->first();
            
                    // ✅ Get existing meals for the user plan
                    $existingMeals = \DB::table('user_meals')
                        ->where('user_plan_id', $userPlan->id)
                        ->pluck('id')
                        ->toArray();
            
                    // Add check for meals array key
                    $newMeals = isset($meals[$planId]) ? Arr::flatten($meals[$planId]) : [];
                    
                    // ✅ Remove meals not in the request
                    $mealsToRemove = array_diff($existingMeals, $newMeals);
                    if (!empty($mealsToRemove)) {
                        $meals = \DB::table('user_meals')
                            ->where('user_plan_id', $userPlan->id)
                            ->whereIn('id', $mealsToRemove)
                            ->get();

                        foreach ($meals as $meal) {
                            $items = \DB::table('user_items')
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_meal_id', $meal->id)
                                ->get();
                                
                            foreach ($items as $item) {
                                // Get valid item_meal item_ids for this meal
                                $validItemIds = \App\Models\ItemMeal::where('item_id', $item->item_id)
                                    ->where('meal_id', $meal->id)
                                    ->pluck('item_id')
                                    ->toArray();

                                // If current item is NOT a valid meal item, remove all its related mappings
                                if (!in_array($item->item_id, $validItemIds)) {
                                    // Remove user_item_meals
                                    \DB::table('user_item_meals')
                                        ->where('user_id', $request->user_id)
                                        ->where('meal_id', $meal->id)
                                        ->where('item_id', $item->id)
                                        ->delete();

                                    // Remove user_swap_items
                                    \DB::table('user_swap_items')
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_meal_id', $meal->id)
                                        ->where('user_item_id', $item->id)
                                        ->delete();

                                    // Remove user_item_swaps
                                    \DB::table('user_item_swaps')
                                        ->where('user_id', $request->user_id)
                                        ->where('meal_id', $meal->id)
                                        ->where('item_id', $item->id)
                                        ->delete();

                                    // ✅ Only delete user_items if item is not valid
                                    \DB::table('user_items')
                                        ->where('id', $item->id)
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_meal_id', $meal->id)
                                        ->delete();
                                }
                            }
                        }

                        $meals = \DB::table('user_meals')
                            ->where('user_plan_id', $userPlan->id)
                            ->whereIn('meal_id', $mealsToRemove)
                            ->delete();
                    }

                    // Check if meal_times exists for this plan
                    if (isset($request->meal_times[$planId])) {
                        foreach (array_unique($request->meal_times[$planId]) as $mealTimeId) {
                            $userMealTime = \DB::table('user_categories')->updateOrInsert(
                                ['user_plan_id' => $userPlan->id, 'id' => $mealTimeId],
                                ['created_at' => now(), 'updated_at' => now()]
                            );
            
                            $userMealTime = \DB::table('user_categories')
                                ->where('user_plan_id', $userPlan->id)
                                ->where('id', $mealTimeId)
                                ->first();

                            // Check if categories exist for this plan and meal time
                            if (isset($categories[$planId][$mealTimeId])) {
                                foreach ($categories[$planId][$mealTimeId] as $categoryId) {
                                    \DB::table('user_sub_categories')->updateOrInsert(
                                        ['user_plan_id' => $userPlan->id, 'user_category_id' => $userMealTime->id, 'id' => $categoryId],
                                        ['created_at' => now(), 'updated_at' => now()]
                                    );
            
                                    $userCategory = \DB::table('user_sub_categories')
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_category_id', $userMealTime->id)
                                        ->where('id', $categoryId)
                                        ->first();
            
                                    // Check if meals exist for this plan, meal time, and category
                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        foreach ($meals[$planId][$mealTimeId][$categoryId] as $mealId) {
                                            // ✅ FIX: Check for meal with full context (meal_time + category)
                                            $userMeal = \DB::table('user_meals')->where([
                                                'user_plan_id' => $userPlan->id,
                                                'user_category_id' => $userMealTime->id,
                                                'user_sub_category_id' => $userCategory->id,
                                                'id' => $mealId,
                                            ])->first();
                                            // dd($userMeal);
                                            
                                            if (!$userMeal) {
                                                $userMeal = \DB::table('user_meals')->updateOrInsert([
                                                    'user_plan_id' => $userPlan->id,
                                                    'user_category_id' => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id' => $mealId,
                                                    'created_at' => now(),
                                                    'updated_at' => now(),
                                                ]);
                                                $userMeal = \DB::table('user_meals')->where([
                                                    'user_plan_id' => $userPlan->id,
                                                    'user_category_id' => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id' => $mealId,
                                                ])->first();
                                            }
                                            $userMealId = $userMeal->id ?? null;
                                            
                                            $mealItems = \App\Models\ItemMeal::where('meal_id', $mealId)->get();
                                            foreach ($mealItems as $mealItem) {
                                                $mealExist = \DB::table('user_item_meals')
                                                ->where('user_id', $request->user_id)
                                                ->where('meal_id', $mealId)
                                                ->where('item_id', $mealItem->item_id)
                                                ->first();

                                                if (!$mealExist) {
                                                    // Insert meal items for the user
                                                    UserItemMeal::create([
                                                        'user_id' => $request->user_id,
                                                        'meal_id' => $mealId,
                                                        'item_id' => $mealItem->item_id,
                                                        'qty' => $mealItem->item_qty,
                                                        'unit' => $mealItem->item_qty_unit,
                                                        'carbs' => $mealItem->carbs,
                                                        'protein' => $mealItem->protein,
                                                        'fat' => $mealItem->fat,
                                                        'energy' => $mealItem->energy,
                                                        'selected_qty_unit' => $mealItem->selected_qty_unit,
                                                        'created_at' => now(),
                                                        'updated_at' => now(),
                                                    ]);

                                                    $itemSwapIds = \DB::table('item_swaps')->where('item_id', $mealItem->item_id)->pluck('swap_item_id')->toArray();
                                                    $itemSwaps = Item::whereIn('id', $itemSwapIds)->get();
                                                    // dd($itemSwaps);
                                                    foreach ($itemSwaps as $itemSwap) {
                                                        $swapItemExist = \DB::table('user_item_swaps')
                                                        ->where('user_id', $request->user_id)
                                                        ->where('meal_id', $mealId)
                                                        ->where('item_id', $mealItem->item_id)
                                                        ->where('swap_item_id', $itemSwap->id)
                                                        ->first();
                                                        // Insert item swaps for the user
                                                        if(!$swapItemExist) {
                                                            UserItemSwap::create([
                                                                'user_id' => $request->user_id,
                                                                'meal_id' => $mealId,
                                                                'item_id' => $mealItem->item_id,
                                                                'swap_item_id' => $itemSwap->id,
                                                                'qty' => $itemSwap->qty,
                                                                'carbs' => $itemSwap->carbs,
                                                                'protein' => $itemSwap->protein,
                                                                'fat' => $itemSwap->fat,
                                                                'energy' => $itemSwap->energy,
                                                                'unit' => $itemSwap->unit,
                                                                'selected_qty_unit' => $itemSwap->selected_qty_unit,
                                                                'created_at' => now(),
                                                                'updated_at' => now(),
                                                            ]);
                                                        }
                                                    }
                                                }
                                            }

                                            $existingItems = \DB::table('user_items')
                                                ->where('user_meal_id', $userMealId)
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                                ->pluck('id')
                                                ->toArray();

                                            // Check if items exist for this plan, meal time, category, and meal
                                            $currentItems = isset($items[$planId][$mealTimeId][$categoryId][$mealId])
                                                ? $items[$planId][$mealTimeId][$categoryId][$mealId]
                                                : [];
            
                                            $itemsToRemove = array_diff($existingItems, $currentItems);
                                            
                                            if (!empty($itemsToRemove)) {
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
                                            
                                            $an = \DB::table('user_item_meals')
                                                ->where('user_id', $request->user_id)
                                                ->where('meal_id', $mealId)
                                                ->whereNotIn('item_id', $currentItems)
                                                ->delete();

                                            $existingItems = \DB::table('user_items')
                                                ->where('user_meal_id', $userMealId)
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                                //->pluck('id')
                                                ->delete();
                                            // dd($currentItems);
                                            foreach ($currentItems as $itemId) {
                                                $a = \DB::table('user_items')->updateOrInsert(
                                                    [
                                                        'user_plan_id' => $userPlan->id,
                                                        'user_category_id' => $userMealTime->id,
                                                        'user_sub_category_id' => $userCategory->id,
                                                        'user_meal_id' => $userMealId,
                                                        'id' => $itemId
                                                    ],
                                                    ['created_at' => now(), 'updated_at' => now()]
                                                );
            
                                                $userItem = \DB::table('user_items')
                                                    ->where('user_plan_id', $userPlan->id)
                                                    ->where('user_category_id', $userMealTime->id)
                                                    ->where('user_sub_category_id', $userCategory->id)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('id', $itemId)
                                                    ->first();
                                               
                                                $existingSwapItems = \DB::table('user_swap_items')
                                                    ->where('user_plan_id', $userPlan->id)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('user_item_id', $userItem->id)
                                                    ->where('user_category_id', $userMealTime->id)
                                                    ->where('user_sub_category_id', $userCategory->id)
                                                    ->pluck('id')
                                                    ->toArray();

                                                // Check if swap items exist for this plan, meal time, category, meal, and item
                                                $currentSwapItems = isset($swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId])
                                                    ? $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId]
                                                    : [];
                                                // dd($currentSwapItems);
                                                // Remove nulls from the array
                                                $currentSwapItems = array_filter($currentSwapItems, function ($value) {
                                                    return !is_null($value);
                                                });
                                                // dd($currentSwapItems);
                                                if (!empty($currentSwapItems)) {
                                                    $query = \DB::table('user_item_swaps')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('item_id', $itemId)
                                                    ->where('meal_id', $mealId)
                                                    ->whereNotIn('swap_item_id', $currentSwapItems)
                                                    ->delete();
                                                }
                                                    // dd($p);
                                                $swapItemsToRemove = array_diff($existingSwapItems, $currentSwapItems);
                                               
                                                if (!empty($swapItemsToRemove)) {
                                                    $j =\DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan->id)
                                                        // ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItem->id)
                                                        ->whereIn('id', $swapItemsToRemove)
                                                        ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_sub_category_id', $userCategory->id)
                                                        ->delete();
                                                }
                                               
                                                foreach ($currentSwapItems as $swapItemId) {
                                                   $k = \DB::table('user_swap_items')->updateOrInsert(
                                                        [
                                                            'user_plan_id' => $userPlan->id,
                                                            'user_meal_id' => $userMealId,
                                                            'user_item_id' => $userItem->id,
                                                            'user_category_id' => $userMealTime->id,
                                                            'user_sub_category_id' => $userCategory->id,
                                                            'id' => $swapItemId
                                                        ],
                                                        ['created_at' => now(), 'updated_at' => now()]
                                                    );
                                                }
                                                $b = \DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan->id)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItem->id)
                                                        ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_sub_category_id', $userCategory->id)
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
            
            \DB::commit();

            if ($action === 'save_exit') {
                return redirect()->route('admin.purchase-plans.index')
                                ->with('success', 'User Plan updated successfully.');
            }

            return redirect()->back()->with('success', 'User Plan updated successfully.');

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
        // dd($userId);
        if ($request->type == 'edit') {
            $userMeal = \App\Models\UserItemMeal::where('meal_id', $request->meal_id)
                ->where('user_id', $userId)
                ->get();
            // dd($userMeal);
            if ($userMeal->isEmpty()) {
                // dd($userMeal);
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
                $totalEnergy = 0;
                // dd($meal->userMealItems);
                $data = $meal->items->map(function ($item) use (&$totalCarbs, &$totalProtein, &$totalFat, &$totalEnergy, $request) {
                    $totalCarbs += $item->pivot->carbs ?? $item->carbs;
                    $totalProtein += $item->pivot->protein ?? $item->protein;
                    $totalFat += $item->pivot->fat ?? $item->fat;
                    $totalEnergy += floatval($item->energy) ?? floatval($item->energy);
                    // dd($item->pivot);
                    $swapItems = $item->swapItems->map(function ($swapItem){
                        // $totalCarbs += $swapItem->carbs;
                        // $totalProtein += $swapItem->protein;
                        // $totalFat += $swapItem->fat;
                    
                        return [
                            'id' => $swapItem->id,
                            'name' => $swapItem->title,
                            'category_id' => $swapItem->category_id,
                            'qty' => $swapItem->qty ?? 0,
                            'unit' => $swapItem->unit ?? '',
                            'carbs' => $swapItem->carbs,
                            'protein' => $swapItem->protein,
                            'fat' => $swapItem->fat,
                            'energy' => $swapItem->energy ?? 0,
                            'description' => $swapItem->description,
                            'selected_qty_unit' => $swapItem->selected_qty_unit,
                        ];
                    });

                    $isNew = \App\Models\ItemMeal::where('meal_id', $request->meal_id)
                                ->where('item_id', $item->id)
                                ->exists() ? 0 : 1;

                    return [
                        'id' => $item->id,
                        'name' => $item->title,
                        'category_id' => $item->category_id,
                        'qty'  => isset($item->pivot->item_qty) ? $item->pivot->item_qty : $item->qty,
                        'unit' => isset($item->pivot->item_qty_unit) ? $item->pivot->item_qty_unit : $item->unit,
                        'carbs' => isset($item->pivot->carbs) ? $item->pivot->carbs : $item->carbs,
                        'protein' => isset($item->pivot->protein) ? $item->pivot->protein : $item->protein,
                        'fat' => isset($item->pivot->fat) ? $item->pivot->fat : $item->fat,
                        'energy' => isset($item->pivot->energy) ? $item->pivot->energy : $item->energy ?? 0,
                        'description' => isset($item->description) ? $item->description : null,
                        'selected_qty_unit' => $this->decodeSelectedQtyUnit($item->pivot->selected_qty_unit ?? $item->selected_qty_unit),
                        'swapItems' => $swapItems,
                        'is_new' => $isNew
                    ];
                });
                
            } else {
               
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
                // dd($userPlan);
                $userMealTimes = null;
                $userUpdateMeal = null;
                if ($userPlan) {
                    $userMealTimes = \App\Models\UserCategory::where('user_plan_id', $userPlan->id)
                        ->where('id', $request->meal_time_id)
                        ->first();

                    if ($userMealTimes) {
                        $userUpdateMeal = \App\Models\UserMeal::where('user_category_id', $userMealTimes->id)
                            ->where('id', $request->meal_id)
                            ->first();
                    }
                }
                
                $mealName = $userUpdateMeal->meal_name ?? $meal->title;
                $mealId = $meal->id;

                $totalCarbs = 0;
                $totalProtein = 0;
                $totalFat = 0;
                $totalEnergy = 0;
                $data = $userMeal->map(function ($item) use($userId, &$totalCarbs, &$totalProtein, &$totalFat, &$totalEnergy, $request ,$userMealTimes) {
                    $isNew = \App\Models\ItemMeal::where('meal_id', $request->meal_id)
                        ->where('item_id', $item->item_id)
                        ->exists() ? 0 : 1;

                    $totalCarbs += isset($item->carbs) ? $item->carbs : $item->items->carbs;
                    $totalProtein += isset($item->protein) ? $item->protein : $item->items->protein;
                    $totalFat += isset($item->fat) ? $item->fat : $item->items->fat;
                    $totalEnergy += isset($item->energy) ? floatval($item->energy) : (isset($item->items->energy) ? floatval($item->items->energy) : 0);

                    $swapItems = \App\Models\UserItemSwap::with('swapItem')
                        ->where('item_id', $item->item_id)
                        ->where('user_id', $userId)
                        ->where('meal_id', $request->meal_id)
                        ->get();
                    if ($swapItems->isEmpty()) {
                        $swapItems = optional(optional($item->items)->swapItems)->map(function ($swapItem) {
                            return [
                                'id' => $swapItem->id,
                                'name' => $swapItem->title,
                                'category_id' => $swapItem->category_id,
                                'qty' => $swapItem->qty ?? 0,
                                'unit' => $swapItem->unit ?? '',
                                'carbs' => $swapItem->carbs,
                                'protein' => $swapItem->protein,
                                'fat' => $swapItem->fat,
                                'energy' => $swapItem->energy ?? 0,
                                'description' => $swapItem->description,
                                'selected_qty_unit' => $swapItem->selected_qty_unit,
                            ];
                        });
                        // $swapItems = [];
                    } else {
                        $swapItems = $swapItems->map(function ($swapFood) {
                            $swapItem = optional($swapFood->swapItem);

                            return [    
                                'id' => $swapItem->id,
                                'name' => $swapItem->title,
                                'qty' => $swapFood->qty,
                                'unit' => $swapFood->unit ?? '',
                                'carbs' => $swapFood->carbs ?? $swapItem->carbs,
                                'protein' => $swapFood->protein ?? $swapItem->protein,
                                'fat' => $swapFood->fat ?? $swapItem->fat,
                                'energy' => $swapFood->energy ?? $swapItem->energy ?? 0,
                                'description' => $swapItem->description ?? null,
                                'selected_qty_unit' => $this->decodeSelectedQtyUnit($swapFood->selected_qty_unit ?? $swapItem->selected_qty_unit ?? ''),
                            ];
                        });
                    }
                    // dd($swapItems);
                    return [
                        'id' => $item->items->id ?? $item->item_id,
                        'name' => $item->items->title ?? '',
                        'qty' => $item->qty ?? $item->items->item_qty ?? 0,
                        'unit' => $item->unit ?? $item->items->unit ?? '',
                        'carbs' => $item->carbs ?? $item->items->carbs,
                        'protein' => $item->protein ?? $item->items->protein,
                        'fat' => $item->fat ?? $item->items->fat,
                        'energy' => $item->energy ?? $item->items->energy ?? 0,
                        'description' => $item->items->description ?? null,
                        'selected_qty_unit' => $item->selected_qty_unit ?? $item->items->selected_qty_unit ?? '',
                        'is_new' => $isNew,
                        'swapItems' => $swapItems,
                    ];
                });
                // dd($data);
            }
        } else {
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
            $totalEnergy = 0;

            $data = $meal->items->map(function ($item) use (&$totalCarbs, &$totalProtein, &$totalFat, &$totalEnergy, $request) {
                $totalCarbs += $item->pivot->carbs ?? $item->carbs;
                $totalProtein += $item->pivot->protein ?? $item->protein;
                $totalFat += $item->pivot->fat ?? $item->fat;
                $totalEnergy += floatval($item->energy) ?? floatval($item->energy);
                
                $swapItems = optional($item->swapItems)->map(function ($swapItem) {
                    // $totalCarbs += $swapItem->carbs;
                    // $totalProtein += $swapItem->protein;
                    // $totalFat += $swapItem->fat;
                
                    return [
                        'id' => $swapItem->id,
                        'name' => $swapItem->title,
                        'category_id' => $swapItem->category_id,
                        'qty' => $swapItem->qty ?? 0,
                        'unit' => $swapItem->unit ?? '',
                        'carbs' => $swapItem->carbs,
                        'protein' => $swapItem->protein,
                        'fat' => $swapItem->fat,
                        'energy' => $swapItem->energy ?? 0,
                        'description' => $swapItem->description,
                        'selected_qty_unit' => $swapItem->selected_qty_unit,
                    ];
                });

                $isNew = \App\Models\ItemMeal::where('meal_id', $request->meal_id)
                            ->where('item_id', $item->id)
                            ->exists() ? 0 : 1;

                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'category_id' => $item->category_id,
                    'qty'  => isset($item->pivot->item_qty) ? $item->pivot->item_qty : $item->qty,
                    'unit' => isset($item->pivot->item_qty_unit) ? $item->pivot->item_qty_unit : $item->unit,
                    'carbs' => isset($item->pivot->carbs) ? $item->pivot->carbs : $item->carbs,
                    'protein' => isset($item->pivot->protein) ? $item->pivot->protein : $item->protein,
                    'fat' => isset($item->pivot->fat) ? $item->pivot->fat : $item->fat,
                    'energy' => isset($item->pivot->energy) ? $item->pivot->energy : $item->energy ?? 0,
                    'description' => isset($item->description) ? $item->description : null,
                    'selected_qty_unit' => isset($item->pivot->selected_qty_unit) ? $item->pivot->selected_qty_unit : $item->selected_qty_unit,
                    'swapItems' => $swapItems,
                    'is_new' => $isNew
                ];
            });

        }
        return response()->json([
            'success' => true,
            'meal_id' => $mealId,
            'meal_name' => $mealName,
            'meal_note' => $meal->note,
            'data' => $data,
            'total_carbs' => number_format($totalCarbs, 2),
            'total_protein' => number_format($totalProtein, 2),
            'total_fat' => number_format($totalFat, 2),
            'total_energy' => number_format($totalEnergy, 2)
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    public function decodeSelectedQtyUnit($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($value) ? $value : [];
    }
    
    // public function getMealsByMealTime(Request $request)
    // {
    //     try {
    //         // Validate request
    //         $request->validate([
    //             'meal_time_id' => 'required|integer',
    //             'user_id' => 'required|integer',
    //             'plan_id' => 'required|integer'
    //         ]);

    //         // Get the category (meal time) with its meals and items
    //         $category = Category::with(['meals' => function($query) use ($request) {
    //             $query->with(['items' => function($q) {
    //                 $q->with('swapItems');
    //             }])
    //             ->where(function($q) use ($request) {
    //                 $q->whereNull('user_id')
    //                   ->orWhere('user_id', $request->user_id)
    //                   ->orWhere('user_id', 7)
    //                   ->orWhere('user_id', 3);
    //             });
    //         }])->find($request->meal_time_id);

    //         if (!$category) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Category not found.'
    //             ], 404);
    //         }

    //         // Get user plan and check for custom meal names
    //         $userPlan = UserPlan::where('user_id', $request->user_id)
    //             ->where('plan_id', $request->plan_id)
    //             ->first();

    //         // Get user's food preferences
    //         $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
    //             $query->where('form_slug', 'food_preference');
    //         }])->where('user_id', $request->user_id)->first();

    //         $restrictedFoods = [];
    //         if ($userPrePlan && $userPrePlan->prePlanDetails) {
    //             foreach ($userPrePlan->prePlanDetails as $detail) {
    //                 if ($detail->answer) {
    //                     $answers = json_decode($detail->answer, true);
    //                     if (is_array($answers)) {
    //                         $restrictedFoods = array_merge($restrictedFoods, $answers);
    //                     }
    //                 }
    //             }
    //         }

    //         // Filter meals based on restrictions
    //         $filteredMeals = $category->meals->filter(function($meal) use ($restrictedFoods) {
    //             // Check if meal has any restricted items
    //             $hasRestrictedItems = $meal->items->contains(function($item) use ($restrictedFoods) {
    //                 return in_array($item->id, $restrictedFoods);
    //             });

    //             if ($hasRestrictedItems) {
    //                 // Check if meal has alternative items
    //                 return $meal->items->contains(function($item) use ($restrictedFoods) {
    //                     return $item->swapItems->contains(function($swapItem) use ($restrictedFoods) {
    //                         return !in_array($swapItem->id, $restrictedFoods);
    //                     });
    //                 });
    //             }

    //             return true;
    //         });

    //         // Map meals to response format
    //         $meals = $filteredMeals->map(function($meal) use ($userPlan) {
    //             $mealData = [
    //                 'id' => $meal->id,
    //                 'name' => $meal->title,
    //                 'items' => $meal->items->map(function($item) {
    //                     return [
    //                         'id' => $item->id,
    //                         'name' => $item->title,
    //                         'swapItems' => $item->swapItems->map(function($swapItem) {
    //                             return [
    //                                 'id' => $swapItem->id,
    //                                 'name' => $swapItem->title
    //                             ];
    //                         })
    //                     ];
    //                 })
    //             ];

    //             // Add custom meal name if exists
    //             if ($userPlan) {
    //                 $userCategory = UserCategory::where('user_plan_id', $userPlan->id)
    //                     ->where('category_id', $meal->category_id)
    //                     ->first();

    //                 if ($userCategory) {
    //                     $userMeal = UserMeal::where('user_category_id', $userCategory->id)
    //                         ->where('meal_id', $meal->id)
    //                         ->first();

    //                     if ($userMeal && !empty($userMeal->meal_name)) {
    //                         $mealData['name'] = $userMeal->meal_name;
    //                     }
    //                 }
    //             }

    //             return $mealData;
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'meals' => $meals,
    //             'restricted_foods' => $restrictedFoods
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getMealsByMealTime(Request $request)
    {
        $mealTime = Category::with('subCategories.meals.items')->where('id', $request->meal_time_id)->first();

        if (!$mealTime) {
            return response()->json([
                'success' => false,
                'message' => 'MealTime not found.'
            ], 404);
        }

        $search = strtolower($request->search);
        $userId = $request->user_id;

        $meals = collect();

        foreach ($mealTime->subCategories as $category) {
            foreach ($category->meals as $meal) {
                // Check if the meal should be included based on user ID
                $allowedUser = is_null($meal->user_id) || in_array($meal->user_id, [$userId, 7, 3]);

                // Check search match (in meal title or category title)
                $matchesSearch = empty($search) ||
                    str_contains(strtolower($meal->title), $search) ||
                    str_contains(strtolower($category->title), $search);

                if ($allowedUser && $matchesSearch) {
                    // Sum nutrition values
                    $carbs = 0;
                    $protein = 0;
                    $fat = 0;
                    $energy = 0;

                    foreach ($meal->items as $item) {
                        $carbs += $item->pivot->carbs ?? $item->carbs ?? 0;
                        $protein += $item->pivot->protein ?? $item->protein ?? 0;
                        $fat += $item->pivot->fat ?? $item->fat ?? 0;
                        $energy += $item->pivot->energy ?? floatval($item->energy ?? 0);
                    }

                    $meals->push([
                        'id' => $meal->id,
                        'name' => $meal->title,
                        'image' => $meal->image ? asset('private/public/storage/' . $meal->image) : null,
                        'carbs' => round($carbs, 2),
                        'protein' => round($protein, 2),
                        'fat' => round($fat, 2),
                        'energy' => round($energy, 2),
                    ]);
                }
            }
        }

        // Replace names with user meal names if found
        $userPlan = \App\Models\UserPlan::where('user_id', $userId)->where('plan_id', $request->plan_id)->first();

        if ($userPlan) {
            $userMealTimes = \App\Models\UserCategory::where('user_plan_id', $userPlan->id)
                ->where('id', $request->meal_time_id)->first();

            $userMeals = collect();

            if ($userMealTimes && $userMealTimes->userSubCategories) {
                $userMeals = $userMealTimes->userSubCategories->flatMap(function ($category) use ($userPlan) {
                    return $category->userMeals->map(function ($userMeal) use ($userPlan) {
                        $carbs = 0;
                        $protein = 0;
                        $fat = 0;
                        $energy = 0;

                        foreach ($userMeal->userItems->where('user_plan_id', $userPlan->id) as $userItem) {
                            $item = \App\Models\Item::find($userItem->id);
                            if ($item) {
                                $carbs += $item->carbs ?? 0;
                                $fat += $item->fat ?? 0;
                                $protein += $item->protein ?? 0;
                                $energy += floatval($item->energy ?? 0);
                            }
                        }

                        return [
                            'id' => $userMeal->id,
                            'name' => $userMeal->meal_name,
                            'image' => $userMeal->meal && $userMeal->meal->image ? asset('private/public/storage/' . $userMeal->meal->image) : null,
                            'carbs' => round($carbs, 2),
                            'protein' => round($protein, 2),
                            'fat' => round($fat, 2),
                            'energy' => round($energy, 2),
                        ];
                    });
                });
            }

            // Replace meal names with user meal names if matched
            $meals = $meals->map(function ($meal) use ($userMeals) {
                $match = $userMeals->firstWhere('id', $meal['id']);
                if ($match && !empty($match['name'])) {
                    $meal['name'] = $match['name'];
                }
                return $meal;
            });
        }

        return response()->json([
            'success' => true,
            'meals' => $meals->values()
        ]);
    }


    // public function getMealsByMealTime(Request $request)
    // {
    //     // Retrieve the MealTime along with its related categories and meals
    //     $mealTime = Category::with('subCategories') // Load categories and meals
    //                 ->where('id', $request->meal_time_id)
    //                 ->first();
        
    //     // Check if MealTime exists
    //     if (!$mealTime) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'MealTime not found.'
    //         ], 404);
    //     }

    //     // Apply search filter if provided
    //     $search = $request->search;
    //     $userId = $request->user_id;

    //     $filteredCategories = $mealTime->subCategories->filter(function ($category) use ($search) {
    //         return empty($search) || stripos($category->title, $search) !== false;
    //     });
        
    //     // dd($filteredCategories);
    //     // Prepare the response: Flatten and collect only meals from filtered categories
    //     $meals = $filteredCategories->flatMap(function ($category) use ($request, $search, $userId) {
    //         return $category->meals->filter(function ($meal) use ($search, $userId) {
    //         $matchesTitle = empty($search) || stripos($meal->title, $search) !== false;
    //         $allowedUser = is_null($meal->user_id) || in_array($meal->user_id, [$userId, 7, 3]);
    //         return $matchesTitle && $allowedUser;
    //         })->map(function ($meal) {
    //             $carbs = 0;
    //             $protein = 0;
    //             $fat = 0;
    //             $energy = 0;

    //             foreach ($meal->items as $item) {
    //                 $carbs += $item->pivot->carbs ?? $item->carbs ?? 0;
    //                 $protein += $item->pivot->protein ?? $item->protein ?? 0;
    //                 $fat += $item->pivot->fat ?? $item->fat ?? 0;
    //                 $energy += $item->pivot->energy ?? floatval($item->energy) ?? 0;
    //             }

    //             return [
    //                 'id' => $meal->id,
    //                 'name' => $meal->title,
    //                 'image' => $meal->image ? asset('private/public/storage/' . $meal->image) : null,
    //                 'carbs' => round($carbs, 2),
    //                 'protein' => round($protein, 2),
    //                 'fat' => round($fat, 2),
    //                 'energy' => round($energy, 2),
    //             ];
    //         });
    //     });
    //     // dd($meals);
    //     $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)
    //                 ->where('plan_id', $request->plan_id)->first();

    //     $carbs = 0;
    //     $protein = 0;
    //     $fat = 0;
    //     $energy = 0;
    //     if ($userPlan) {
    //         $userMealTimes = \App\Models\UserCategory::where('user_plan_id', $userPlan->id)
    //                             ->where('id', $request->meal_time_id)->first();

    //         $userMeals = [];
    //         if (isset($userMealTimes->userSubCategories)) {
    //             $userMeals = $userMealTimes->userSubCategories->flatMap(function ($category) use($userPlan, &$carbs, &$protein, &$fat, &$energy) {
    //                 return $category->userMeals->map(function ($userMeal) use($userPlan, &$carbs, &$protein, &$fat, &$energy) {
    //                     foreach ($userMeal->userItems->where('user_plan_id', $userPlan->id) as $userItem) {
    //                         $item = Item::find($userItem->id);
    //                         if ($item) {
    //                             $carbs += $item->carbs ?? 0;
    //                             $fat += $item->fat ?? 0;
    //                             $protein += $item->protein ?? 0;
    //                             $energy += floatval($item->energy ?? 0);
    //                         }
    //                     }
    //                     return [
    //                         'id' => $userMeal->id,
    //                         'name' => $userMeal->meal_name,
    //                         'image' => $userMeal->meal && $userMeal->meal->image ? asset('private/public/storage/' . $userMeal->meal->image) : null,
    //                         'carbs' => round($carbs, 2),
    //                         'protein' => round($protein, 2),
    //                         'fat' => round($fat, 2),
    //                         'energy' => round($energy, 2),
    //                     ];
    //                 });
    //             });
    //         }

    //         $userMeals = collect($userMeals);
    //         $updatedMeals = $meals->map(function ($meal) use ($userMeals) {
    //             // Check if the meal exists in $userMeals
    //             $matchingUserMeal = $userMeals->firstWhere('id', $meal['id']);
    //             if ($matchingUserMeal && !empty($matchingUserMeal['name'])) {
    //                 // Replace the name if a valid name is found in $userMeals
    //                 $meal['name'] = $matchingUserMeal['name'];
    //             }
    //             return $meal;
    //         });
    //     } else {
    //         $updatedMeals = $filteredCategories->flatMap(function ($category) use ($request, $search, $userId) {
    //             return $category->meals->filter(function ($meal) use ($search, $userId) {
    //             $matchesTitle = empty($search) || stripos($meal->title, $search) !== false;
    //             $allowedUser = is_null($meal->user_id) || in_array($meal->user_id, [$userId, 7, 3]);
    //             return $matchesTitle && $allowedUser;
    //             })->map(function ($meal) {
    //                  $carbs = 0;
    //                 $protein = 0;
    //                 $fat = 0;
    //                 $energy = 0;

    //                 foreach ($meal->items as $item) {
    //                     $carbs += $item->pivot->carbs ?? $item->carbs ?? 0;
    //                     $protein += $item->pivot->protein ?? $item->protein ?? 0;
    //                     $fat += $item->pivot->fat ?? $item->fat ?? 0;
    //                     $energy += $item->pivot->energy ?? floatval($item->energy) ?? 0;
    //                 }

    //                 return [
    //                     'id' => $meal->id,
    //                     'name' => $meal->title,
    //                     'image' => $meal->image ? asset('private/public/storage/' . $meal->image) : null,
    //                     'carbs' => round($carbs, 2),
    //                     'protein' => round($protein, 2),
    //                     'fat' => round($fat, 2),
    //                     'energy' => round($energy, 2),
    //                 ];
    //             });
    //         });
    //     }

    //     // Return only the meals in the desired structure
    //     return response()->json([
    //         'success' => true,
    //         'meals' => $updatedMeals
    //     ]);
    // }

    public function getPrePlanDetails($id)
    {
        $userPrePlan = \App\Models\UserPrePlan::with('prePlanDetails','payment')->where('payment_id', $id)->first();
        $prePlanDetails = $userPrePlan->prePlanDetails ?? [];
        if(!$userPrePlan) {
            return response()->json([
                'success' => false,
                'userDetails' => null,
                'data' => null
            ]);
        }
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

        $foodGroups = [
            'Grains' => [
                'Cereals',
                'Pasta & Noodles',
                'Small Grains',
                'Bread & Rolls',
                'Specialty Breads',
                'Flat Bread',
            ],
            'Legumes & Beans' => [
                'Legumes & Beans',
            ],
            'Nuts' => [
                'Nuts',
            ],
            'Seeds' => [
                'Seeds',
            ],
            'Eggs' => [
                'Eggs',
            ],
            'Meat' => [
                'Beef',
                'Chicken',
                'Lamb',
                'Pork',
                'Turkey',
                'Deli Meat',
            ],
            'Plant Based' => [
                'Meat Alternatives',
            ],
            'Seafood' => [
                'Fresh Seafood',
                'Tinned Seafood',
            ],
            'Dairy' => [
                'Milk',
                'Cheese',
                'Yoghurt',
            ],
            'Fruit' => [
                'Fruit',
            ],
            'Vegetables' => [
                'Vegetables',
            ],
            'Oils / Butter' => [
                'Butters',
                'Oils',
            ],
            'Snacks' => [
                'Fruit & Nut bars',
                'Muesli bars',
                'Other Snacks',
                'Chocolate bars',
                'Lollies',
            ],
            'Drinks' => [
                'Cold Drinks',
                'Hot Drinks',
            ],
            'Cuisines' => [
                'Japanese',
                'Chinese',
                'Thai',
                'Indian',
                'Italian',
                'Mexican',
                'Greek',
                'Other',
            ],
        ];

        return response()->json([
            'success' => true,
            'userDetails' => $userDetails,
            'data' => $groupedData,
            'foodGroups' => $foodGroups

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

            $selectedSwapItems = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->pluck('swap_item_id')->toArray();
            
            $selectedSwapItems = Item::whereIn('id', $selectedSwapItems)->get();
            $selectedItemArr = [];
            foreach ($selectedSwapItems as $swapItem) {
                $Item = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
                $selectedItemArr[] = [
                    'id' => $swapItem->id,
                    'name' => $swapItem->title,
                    'qty' => $Item->qty,
                    'unit' => $Item->unit,
                    'image' => $swapItem->image,
                    'carbs' => $swapItem->carbs,
                    'protein' => $swapItem->protein,
                    'description' => $swapItem->description,
                    'fat' => $swapItem->fat,
                    'energy' => $swapItem->energy,

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

            $selectedSwapItems = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->pluck('swap_item_id')->toArray();
            
            $selectedSwapItems = Item::whereIn('id', $selectedSwapItems)->get();
            $selectedItemArr = [];
            foreach ($selectedSwapItems as $swapItem) {
                $Item = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
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

            $selectedQtyUnit = collect($request->selected_qty_unit)
             ->firstWhere('checked', true);

            $qty = isset($selectedQtyUnit['qty']) ? $selectedQtyUnit['qty'] : null;
            $unit = isset($selectedQtyUnit['unit']) ? $selectedQtyUnit['unit'] : null;
            // ✅ Update or create UserItemMeal
            if ($userItemMeal) {
                $userItemMeal->update([
                    'qty' => $qty,
                    'unit' => $unit,
                    'carbs' => $request->food_carbs,
                    'protein' => $request->food_protein,
                    'fat' => $request->food_fat,
                    'energy' => $request->food_energy,
                    'selected_qty_unit' => $request->selected_qty_unit,
                    'updated_at' => now(),
                ]);
                $savedItem = $userItemMeal->load('items');
            } else {

                $savedItem = UserItemMeal::create([
                    'user_id' => $request->user_id,
                    'item_id' => $request->item_id,
                    'qty' => $qty,
                    'unit' => $unit,
                    'carbs' => $request->food_carbs,
                    'protein' => $request->food_protein,
                    'fat' => $request->food_fat,
                    'energy' => $request->food_energy,
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
            $selectedQtyUnit = collect($request->swap_selected_qty_unit)
             ->firstWhere('checked', true);

            $qty = isset($selectedQtyUnit['qty']) ? $selectedQtyUnit['qty'] : null;
            $unit = isset($selectedQtyUnit['unit']) ? $selectedQtyUnit['unit'] : null;

            $existingSwap = UserItemSwap::with('swapItem')
                ->where('item_id', $request->item_id)
                ->where('meal_id', $request->meal_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->where('user_id', $request->user_id)
                ->first();
                // dd($existingSwap);
            if ($existingSwap) {
                $existingSwap->update([
                    'qty' => $qty,
                    'carbs' => $request->swap_food_carbs,
                    'protein' => $request->swap_food_protein,
                    'fat' => $request->swap_food_fat,
                    'energy' => $request->swap_food_energy,
                    'unit' => $unit,
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
                    'qty' => $qty,
                    'carbs' => $request->swap_food_carbs,
                    'protein' => $request->swap_food_protein,
                    'fat' => $request->swap_food_fat,
                    'energy' => $request->swap_food_energy,
                    'unit' => $unit,
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
            
            $userItemMealDeleted = DB::table('user_item_meals')->where('user_id', $request->user_id)->where('meal_id', $request->meal_id)->where('item_id', $request->item_id)->delete();

            $userItemSwapDeleted = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->delete();
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
            // dd($request->all());
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
                $userItemMeal->is_swiped = 0;
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
                    
                    $exists = DB::table('user_item_swaps')
                        ->where('user_id', $request->user_id)
                        ->where('item_id', $item->id)
                        ->where('meal_id', $request->meal_id)
                        ->where('swap_item_id', $swapItem->id)
                        ->first();

                    if (!$exists) {
                        // $deleteSwapFood = UserItemSwap::with('swapItem')
                        // ->where('item_id', $item->id)
                        // ->where('user_id', $request->user_id)
                        // ->delete();

                        $swap = UserItemSwap::create([
                            'user_id' => $request->user_id,
                            'item_id' => $item->id,
                            'meal_id' => $request->meal_id,
                            'swap_item_id' => $swapItem->id,
                            'qty' => $swapItem->qty,
                            'unit' => $swapItem->unit,
                            'carbs' => $swapItem->carbs,
                            'fat' => $swapItem->fat,
                            'protein' => $swapItem->protein,
                            'selected_qty_unit' => $swapItem->selected_qty_unit, // ✅
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
                    $userItemMeal->is_swiped = 0;
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
                        
                        $exists = DB::table('user_item_swaps')
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
                            // dd($swapItem->selected_qty_unit);
                            $swap = UserItemSwap::create([
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
                    $userItemMeal->is_swiped = 0;
                    $userItemMeal->item_id = $food->id;
                    $userItemMeal->save();
                }

                // Step 4: Redirect with success message
                return response()->json(['success' => true, 'data' => $food, 'message' => 'Food added successfully.']);
            } catch (\Exception $e) {
                // dd($e->getMessage());
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
                    
                    $exists = DB::table('user_item_swaps')
                        ->where('user_id', $request->user_id)
                        ->where('item_id', $item->id)
                        ->where('swap_item_id', $swapItem->id)
                        ->exists();

                    if (!$exists) {
                        $deleteSwapFood = UserItemSwap::with('swapItem')
                        ->where('item_id', $item->id)
                        ->where('user_id', $request->user_id)
                        ->delete();

                        DB::table('user_item_swaps')->insert([
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
  
    public function handlePlanAction(Request $request)
    {
        $action = $request->action;

        // Validate required inputs
        if (!$request->user_id || !$request->payment_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID or Payment ID is missing.'
            ], 400);
        }

        // Fetch payment
        $payment = Payment::find($request->payment_id);
        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found.'
            ], 404);
        }

        $email = $payment->user->email;
        $planName = Plan::where('id', $payment->plan_id)->value('name');

        if ($action === 'view') {
            // Get the user details
            $user = User::findOrFail($request->user_id);
            $planIds = DB::table('payments')
                ->where('email', $user->email)
                ->where(function($query) {
                    $query->where('status', 'succeeded')
                        ->orWhere('status', 'discount_applied');
                })
                ->pluck('plan_id')
                ->toArray();

            $redirectUrl = route('front.profile', ['id' => $user->id]);

            Auth::login($user); // Login user (no password check)

            return response()->json([
                'status' => 'success',
                'redirect_url' => $redirectUrl
            ]);
        }

        if ($action === 'send') {
            // Send meal plan to user
            $user = $payment->user;
            $userPlan = UserPlan::where('plan_id', $payment->plan_id)->where('user_id', $user->id)->first();
            if (!$userPlan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User plan not found.'
                ], 404);
            }
            
            try {
                Mail::to($email)->send(new ActivePlanMail($user, $planName));

                $userPlan->update(['is_mail_sent' => 1,
                    'mail_sent_at' => now()
                ]);
                $userPlan->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Meal plan mail sent successfully!'
                ]);
            } catch (\Exception $e) {
                Log::error('Error sending meal plan mail: ' . $e->getMessage());
                return response()->json(['error' => 'Error sending meal plan mail'], 500);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid action.'
        ], 400);
    }

    // public function removeUserMeal(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'user_id' => 'required|integer',
    //             'meal_id' => 'required|integer',
    //             'plan_id' => 'required|integer',
    //         ]);

    //         DB::beginTransaction();

    //         $userPlanId = UserPlan::where('user_id', $request->user_id)
    //             ->where('plan_id', $request->plan_id)
    //             ->value('id');

    //         if (!$userPlanId) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'User plan not found'
    //             ], 404);
    //         }

    //         $userMeal = UserMeal::where('user_plan_id', $userPlanId)
    //             ->where('id', $request->meal_id)
    //             ->first();

    //         if (!$userMeal) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Meal not found in user plan'
    //             ], 404);
    //         }

    //         // Delete associated items and swap items
    //         UserItem::where('user_meal_id', $userMeal->id)->delete();
    //         UserSwapItem::where('user_meal_id', $userMeal->id)->delete();

    //         // Delete the meal
    //         $userMeal->delete();

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Meal removed successfully'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Illuminate\Support\Facades\Log::error('Error removing meal: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to remove meal: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function removeUserMeal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'meal_id' => 'required|integer',
            'plan_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $userPlanId = \App\Models\UserPlan::where('user_id', $request->user_id)
                ->where('plan_id', $request->plan_id)
                ->value('id');
            // dd($userPlanId);
            if (!$userPlanId) {
                // DB::rollBack();
                // return response()->json(['success' => false, 'message' => 'User plan not found.']);
                $validItemIds = \App\Models\ItemMeal::where('meal_id', $request->meal_id)
                        ->pluck('item_id')
                        ->toArray();

                $userItemMeals = DB::table('user_item_meals')
                    ->where('user_id', $request->user_id)
                    ->where('meal_id', $request->meal_id)
                    ->get();

                foreach ($userItemMeals as $userItemMeal) {
                    Log::info("Deleting leftover user_item_meal", ['id' => $userItemMeal->id]);

                    DB::table('user_item_meals')
                        ->where('id', $userItemMeal->id)
                        ->delete();

                    DB::table('user_item_swaps')
                        ->where('user_id', $request->user_id)
                        ->where('meal_id', $request->meal_id)
                        ->where('item_id', $userItemMeal->item_id)
                        ->delete();
                }
            } else {

                $meal = UserMeal::where('user_plan_id', $userPlanId)
                    ->where('id', $request->meal_id)
                    ->first();
                // dd($meal);
                if ($meal) {
                    $items = UserItem::where('user_plan_id', $userPlanId)
                        ->where('user_meal_id', $meal->id)
                        ->get();
                    // dd($items);
                    $validItemIds = \App\Models\ItemMeal::where('meal_id', $meal->id)
                        ->pluck('item_id')
                        ->toArray();
                    // dd($validItemIds);
                    foreach ($items as $item) {
                        if (!in_array($item->id, $validItemIds)) {
                            // dd('11');
                            Log::info("Deleting invalid item", ['item_id' => $item->id]);

                            UserSwapItem::where('user_meal_id', $meal->id)
                                ->where('user_item_id', $item->id)
                                ->where('user_plan_id', $userPlanId)
                                ->forceDelete();

                            UserItemSwap::where('user_id', $request->user_id)
                                ->where('meal_id', $meal->id)
                                ->where('item_id', $item->id)
                                ->forceDelete();

                            $userItemMeals = DB::table('user_item_meals')
                                ->where('user_id', $request->user_id)
                                ->where('meal_id', $meal->id)
                                ->where('item_id', $item->id)
                                ->delete();

                            UserItem::where('id', $item->id)
                                ->forceDelete();
                        } else {
                            // dd('33');
                            $validSwapItemIds = DB::table('item_swaps')
                                ->where('item_id', $item->id)
                                ->pluck('swap_item_id')
                                ->toArray();
                            // dd($validSwapItemIds);
                            $userSwapItems = UserItemSwap::where('user_id', $request->user_id)
                                ->where('meal_id', $request->meal_id)
                                ->where('item_id', $item->id)
                                ->get();

                            // dd($userSwapItems);
                            foreach ($userSwapItems as $userSwapItem) {
                                UserItemSwap::where('user_id', $request->user_id)
                                    ->where('meal_id', $request->meal_id)
                                    ->where('item_id', $item->id)
                                    ->where('swap_item_id', $userSwapItem->swap_item_id)
                                    ->forceDelete();

                                UserSwapItem::where('user_item_id', $item->id)
                                    ->where('id', $userSwapItem->swap_item_id)
                                    ->where('user_plan_id', $userPlanId)
                                    ->forceDelete();

                                // if (!in_array($userSwapItem->swap_item_id, $validSwapItemIds)) {
                                //     // dd('44');
                                //     Log::info("Deleting invalid swap item", [
                                //         'item_id' => $item->id,
                                //         'swap_item_id' => $userSwapItem->swap_item_id,
                                //     ]);

                                //     UserItemSwap::where('user_id', $request->user_id)
                                //         ->where('meal_id', $request->meal_id)
                                //         ->where('item_id', $item->id)
                                //         ->where('swap_item_id', $userSwapItem->swap_item_id)
                                //         ->forceDelete();
                                //     UserSwapItem::where('user_item_id', $item->id)
                                //         ->where('id', $userSwapItem->swap_item_id)
                                //         ->forceDelete();

                                // } else {
                                //     // dd('55');
                                //     // If the swap item is valid, we can keep it
                                //     // But we need to ensure that the swap item is not already associated with another item
                                //     $existingSwapItem = UserSwapItem::where('user_item_id', $item->id)
                                //         ->where('id', $userSwapItem->swap_item_id)
                                //         ->first();
                                //     // dd($existingSwapItem);
                                //     if (!$existingSwapItem) {
                                //         // If the swap item is not associated with the current item, we can delete it
                                //         // dd('66');
                                //         Log::info("Deleting orphaned swap item", [
                                //             'item_id' => $item->id,
                                //             'swap_item_id' => $userSwapItem->swap_item_id,
                                //         ]);
                                //         UserSwapItem::where('user_item_id', $item->id)
                                //             ->where('id', $userSwapItem->swap_item_id)
                                //             ->forceDelete();
                                //     }
                                // }
                                // dd($userSwapItem);
                                // Log::info("Deleting swap item", [
                                //     'item_id' => $item->id,
                                //     'swap_item_id' => $userSwapItem->id,
                                // ]);

                                // DB::table('user_item_swaps')
                                //     ->where('user_id', $request->user_id)
                                //     ->where('meal_id', $request->meal_id)
                                //     ->where('item_id', $item->id)
                                //     ->where('swap_item_id', $userSwapItem->swap_item_id)
                                //     ->delete();

                                // DB::table('user_swap_items')
                                //     ->where('user_item_id', $item->id)
                                //     ->where('id', $userSwapItem->swap_item_id)
                                //     ->delete();
                            }

                            $userItemMeals = DB::table('user_item_meals')
                                ->where('user_id', $request->user_id)
                                ->where('meal_id', $meal->id)
                                ->where('item_id', $item->id)
                                ->delete();
                        // dd('44');
                        }
                    }

                    UserMeal::where('id', $meal->id)
                            ->where('user_plan_id', $userPlanId)
                            ->delete();
                } else {
                    Log::info("Meal not found in user_meals, cleaning leftovers");

                    $validItemIds = \App\Models\ItemMeal::where('meal_id', $request->meal_id)
                        ->pluck('item_id')
                        ->toArray();

                    $userItemMeals = DB::table('user_item_meals')
                        ->where('user_id', $request->user_id)
                        ->where('meal_id', $request->meal_id)
                        ->get();

                    foreach ($userItemMeals as $userItemMeal) {
                        Log::info("Deleting leftover user_item_meal", ['id' => $userItemMeal->id]);

                        DB::table('user_item_meals')
                            ->where('id', $userItemMeal->id)
                            ->delete();

                        DB::table('user_item_swaps')
                            ->where('user_id', $request->user_id)
                            ->where('meal_id', $request->meal_id)
                            ->where('item_id', $userItemMeal->item_id)
                            ->delete();
                    }
                }
            }
            // Optional: clear related cache if you're using caching
            // Cache::forget("meal_items_user_{$request->user_id}_{$request->meal_id}");

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invalid items and meal removed successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error in removeUserMeal", [
                'message' => $e->getMessage(),
                'user_id' => $request->user_id,
                'meal_id' => $request->meal_id,
                'plan_id' => $request->plan_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove meal. Please try again.'
            ]);
        }
    }

    public function updateNutritionFalg(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|integer',
            'nutrition_info_flag' => 'required|boolean',
        ]);

        $payment = Payment::where('id', $request->payment_id)
                    ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'User plan not found.'], 404);
        }

        $userPlan = UserPlan::where('user_id', $payment->user_id)
                            ->where('plan_id', $payment->plan_id)
                            ->first();
        if (!$userPlan) {
            return response()->json(['success' => false, 'message' => 'User plan not found.'], 404);
        }

        // Update the nutrition_info_flag
        $userPlan->nutrition_info_flag = $request->nutrition_info_flag;
        $userPlan->save();

        return response()->json(['success' => true, 'message' => 'Nutrition flag updated successfully.']);
    }

     public function updateSwapItem(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'item_id' => 'required|integer',
            'swap_item_id' => 'required|integer',
            'meal_id' => 'required|integer',
            'ratio' => 'required|numeric',
        ]);

        try {
            $userItemSwap = UserItemSwap::where('user_id', $request->user_id)
                ->where('item_id', $request->item_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->where('meal_id', $request->meal_id)
                ->first();

            if (!$userItemSwap) {
                // return response()->json(['success' => false, 'message' => 'Swap item not found.'], 404);
                $item = Item::find($request->swap_item_id);
                if (!$item) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $userItemSwap = new UserItemSwap();
                $userItemSwap->user_id = $request->user_id;
                $userItemSwap->item_id = $request->item_id;
                $userItemSwap->swap_item_id = $request->swap_item_id;
                $userItemSwap->meal_id = $request->meal_id;

                $selectedQty = $item->selected_qty_unit; // Assuming this is an array of selected quantities
                $ratio = $request->ratio; // The ratio to adjust the quantities
                $updatedSelectedQty = [];
                // dd($selectedQty);
                foreach ($selectedQty as $unitData) {
                    $originalQty = floatval($unitData['qty']);
                    $adjustedQty = $originalQty / floatval($ratio);

                    // Optionally round or format:
                    $adjustedQty = round($adjustedQty, 2); // keep 2 decimal places

                    $updatedSelectedQty[] = [
                        'qty' => (string) $adjustedQty,
                        'unit' => $unitData['unit'],
                        'checked' => $unitData['checked'] ?? false, // Preserve the checked state if it exists
                    ];
                }

                // Update the swap item
                $userItemSwap->qty = $updatedSelectedQty[0]['qty']; 
                $userItemSwap->unit = $updatedSelectedQty[0]['unit'];
                $userItemSwap->selected_qty_unit = $updatedSelectedQty;
                $userItemSwap->carbs = $request->food_carbs ?? 0;
                $userItemSwap->protein = $request->food_protein ?? 0;
                $userItemSwap->fat = $request->food_fat ?? 0;
                $userItemSwap->energy = $request->food_energy ?? 0;
                $userItemSwap->save();
            } else {

                $selectedQty = $userItemSwap->selected_qty_unit; // Assuming this is an array of selected quantities
                $ratio = $request->ratio; // The ratio to adjust the quantities
                $updatedSelectedQty = [];
                // dd($selectedQty);
                foreach ($selectedQty as $unitData) {
                    $originalQty = floatval($unitData['qty']);
                    $adjustedQty = $originalQty / floatval($ratio);
    
                    // Optionally round or format:
                    $adjustedQty = round($adjustedQty, 2); // keep 2 decimal places
    
                    $updatedSelectedQty[] = [
                        'qty' => (string) $adjustedQty,
                        'unit' => $unitData['unit'],
                        'checked' => $unitData['checked'] ?? false, // Preserve the checked state if it exists
                    ];
                }
    
                // Update the swap item
                $userItemSwap->qty = $updatedSelectedQty[0]['qty']; 
                $userItemSwap->unit = $updatedSelectedQty[0]['unit'];
                $userItemSwap->selected_qty_unit = $updatedSelectedQty;
                $userItemSwap->carbs = $request->food_carbs ?? 0;
                $userItemSwap->protein = $request->food_protein ?? 0;
                $userItemSwap->fat = $request->food_fat ?? 0;
                $userItemSwap->energy = $request->food_energy ?? 0;
                $userItemSwap->save();
            }

            return response()->json(['success' => true, 'message' => 'Swap item updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating swap item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update swap item.'], 500);
        }
    }
}