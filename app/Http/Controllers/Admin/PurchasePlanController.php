<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ActivePlanMail;
use App\Models\Category;
use App\Models\FoodCategory;
use App\Models\Item;
use App\Models\ItemMeal;
use App\Models\Meal;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\SubCategory;
use App\Models\TrackingType;
use App\Models\User;
use App\Models\UserCategory;
use App\Models\UserItem;
use App\Models\UserItemMeal;
use App\Models\UserItemSwap;
use App\Models\UserMeal;
use App\Models\UserPlan;
use App\Models\UserPrePlan;
use App\Models\UserSwapItem;
use App\Services\ActivityTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchasePlanController extends Controller
{
    // List all payments with pagination
    public function index()
    {
        // Fetch payments with pagination (you can adjust per page as needed)
        $payments = Payment::with('plan:id,name')->get();
        $planIds  = array_unique(array_column($payments->toArray(), 'plan_id'));
        $userIds  = array_unique(array_column($payments->toArray(), 'user_id'));

        $useWisePlanData = [];
        if ($userIds && $planIds) {
            $userPlanQuery = UserPlan::select([
                'id',
                'user_id',
                'plan_id',
            ])->whereIn('user_id', $userIds)->whereIn('plan_id', $planIds)->get()->toArray();

            if ($userPlanQuery) {
                foreach ($userPlanQuery as $plan) {
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
        $payment  = Payment::findOrFail($id);
        $plan     = Plan::find($payment->plan_id);
        $subPlans = $plan->subPlans()->pluck('sub_plan_id')->toArray();

        $plans = Plan::with([
            'subPlans.categories.subCategories.meals.items.swapItems',
        ])->where('id', $payment->plan_id)
            ->when($subPlans, function ($query) use ($subPlans) {
                return $query->orWhereIn('id', $subPlans);
            })->get();

        // Get all options for each relationship
        $categories    = Category::all();
        $subCategories = SubCategory::all();
        $meals         = Meal::all();
        $items         = Item::where('is_swiped', 0)->get();

        $step5Foods = Item::get();

        $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
            $query->where('form_slug', 'food_preference')
                ->orderBy('id', 'asc');
        }])
            ->where('payment_id', $payment->id)->first();

        $foodPreferences = collect();

        if (! empty($userPrePlan) && $userPrePlan->prePlanDetails) {
            foreach ($userPrePlan->prePlanDetails as $detail) {
                $question = $detail->question ?? 'Unknown'; // fallback if question missing
                $answers  = json_decode($detail->answer, true);

                // Make sure $answers is array and skip nulls
                if (is_array($answers)) {
                    $filteredAnswers = array_filter($answers); // remove nulls
                    if (! empty($filteredAnswers)) {
                        $foodPreferences->put($question, collect($filteredAnswers));
                    }
                }
            }
        }
        $otherFoods = $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
            $query->where('form_slug', 'food_preference')
                ->whereIn('question', ['Cuisines', 'Snacks']); // Add your question filters here
        }])->where('payment_id', $id)->first();

        $groupedAnswers = [];

        if (isset($otherFoods->prePlanDetails)) {
            foreach ($otherFoods->prePlanDetails as $detail) {
                $question = $detail->question ?? null;
                if (! $question) {
                    continue;
                }

                $answers = json_decode($detail->answer, true);

                // Only proceed if decoded answer is an array
                if (is_array($answers)) {
                    // Assign the full answer array preserving keys under the question name
                    // If multiple entries for the same question exist, merge them
                    if (! isset($groupedAnswers[$question])) {
                        $groupedAnswers[$question] = $answers;
                    } else {
                        // Merge arrays preserving keys; keys in later arrays override earlier
                        $groupedAnswers[$question] = array_merge($groupedAnswers[$question], $answers);
                    }
                }
            }
        }
        $perPlanSelectedFoods = [];
        $otherFoods           = $groupedAnswers;
        return view('backend.pages.plan.purchase-plan-create', compact(
            'payment',
            'plans',
            'categories',
            'subCategories',
            'meals',
            'items',
            'step5Foods', 'perPlanSelectedFoods', 'otherFoods', 'foodPreferences'
        ));
    }

    public function store(Request $request)
    {
        try {
            $payment = Payment::findOrFail($request->payment_id);
            DB::beginTransaction();

            // Get payment and related data
            $payment = Payment::with('user')->findOrFail($request->payment_id);

            $meals      = [];
            $categories = [];
            $items      = [];
            $swapItems  = [];

            // Step 1: Populate categories by planId and mealTimeId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = $request->meal_times[$planId];

                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($request->meals[$planId][$mealTimeId])) {
                            $mealIds = $request->meals[$planId][$mealTimeId];

                            $categoriesByMeal = DB::table('meal_sub_category')
                                ->whereIn('meal_id', $mealIds)
                                ->pluck('sub_category_id')
                                ->unique()
                                ->toArray();
                            $mealTimeCategories = DB::table('subcategory_category')
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
                                        $categoriesByMeal = DB::table('meal_sub_category')
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
                    $userPlan = DB::table('user_plans')->updateOrInsert(
                        ['user_id' => $request->user_id, 'plan_id' => $planId],
                        ['status' => 'active', 'modified_by' => auth()->id(), 'updated_at' => now()]
                    );

                    $userPlan = DB::table('user_plans')
                        ->where('user_id', $request->user_id)
                        ->where('plan_id', $planId)
                        ->first();

                    $existingMeals = DB::table('user_meals')
                        ->where('user_plan_id', $userPlan->id)
                        ->pluck('id')
                        ->toArray();

                    $newMeals      = isset($meals[$planId]) ? Arr::flatten($meals[$planId]) : [];
                    $mealsToRemove = array_diff($existingMeals, $newMeals);

                    if (! empty($mealsToRemove)) {
                        DB::table('user_meals')
                            ->where('user_plan_id', $userPlan->id)
                            ->whereIn('id', $mealsToRemove)
                            ->delete();
                    }

                    if (isset($request->meal_times[$planId])) {
                        foreach (array_unique($request->meal_times[$planId]) as $mealTimeId) {
                            $userMealTimeId = DB::table('user_categories')->updateOrInsert(
                                ['user_plan_id' => $userPlan->id, 'id' => $mealTimeId],
                                ['created_at' => now(), 'updated_at' => now()]
                            );

                            $userMealTime = DB::table('user_categories')
                                ->where('user_plan_id', $userPlan->id)
                                ->where('id', $mealTimeId)
                                ->first();

                            if (isset($categories[$planId][$mealTimeId])) {
                                foreach ($categories[$planId][$mealTimeId] as $categoryId) {
                                    DB::table('user_sub_categories')->updateOrInsert(
                                        ['user_plan_id' => $userPlan->id, 'user_category_id' => $userMealTime->id, 'id' => $categoryId],
                                        ['created_at' => now(), 'updated_at' => now()]
                                    );

                                    $userCategory = DB::table('user_sub_categories')
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_category_id', $userMealTime->id)
                                        ->where('id', $categoryId)
                                        ->first();

                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        foreach ($meals[$planId][$mealTimeId][$categoryId] as $mealId) {

                                            $userMeal = DB::table('user_meals')->where([
                                                'user_plan_id'         => $userPlan->id,
                                                'user_category_id'     => $userMealTime->id,
                                                'user_sub_category_id' => $userCategory->id,
                                                'id'                   => $mealId,
                                            ])->first();

                                            if (! $userMeal) {
                                                $userMeal = DB::table('user_meals')->insertGetId([
                                                    'user_plan_id'         => $userPlan->id,
                                                    'user_category_id'     => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id'                   => $mealId,
                                                    'created_at'           => now(),
                                                    'updated_at'           => now(),
                                                ]);

                                                $userMeal = DB::table('user_meals')->where([
                                                    'user_plan_id'         => $userPlan->id,
                                                    'user_category_id'     => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id'                   => $mealId,
                                                ])->first();
                                            }
                                            $userMealId = $userMeal->id ?? null;

                                            $mealItems = ItemMeal::where('meal_id', $mealId)->get();
                                            foreach ($mealItems as $mealItem) {
                                                $mealExist = DB::table('user_item_meals')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->where('item_id', $mealItem->item_id)
                                                    ->first();

                                                if (! $mealExist) {

                                                    UserItemMeal::create([
                                                        'user_id'           => $request->user_id,
                                                        'meal_id'           => $mealId,
                                                        'item_id'           => $mealItem->item_id,
                                                        'qty'               => $mealItem->item_qty,
                                                        'unit'              => $mealItem->item_qty_unit,
                                                        'carbs'             => $mealItem->carbs,
                                                        'protein'           => $mealItem->protein,
                                                        'fat'               => $mealItem->fat,
                                                        'energy'            => $mealItem->energy,
                                                        'selected_qty_unit' => $mealItem->selected_qty_unit,
                                                        'created_at'        => now(),
                                                        'updated_at'        => now(),
                                                    ]);

                                                    $itemSwapIds = DB::table('item_swaps')->where('item_id', $mealItem->item_id)->pluck('swap_item_id')->toArray();
                                                    $itemSwaps   = Item::whereIn('id', $itemSwapIds)->get();
                                                    foreach ($itemSwaps as $itemSwap) {
                                                        $swapItemExist = DB::table('user_item_swaps')
                                                            ->where('user_id', $request->user_id)
                                                            ->where('meal_id', $mealId)
                                                            ->where('item_id', $mealItem->item_id)
                                                            ->where('swap_item_id', $itemSwap->id)
                                                            ->first();
                                                        // Insert item swaps for the user
                                                        if (! $swapItemExist) {
                                                            UserItemSwap::create([
                                                                'user_id'           => $request->user_id,
                                                                'meal_id'           => $mealId,
                                                                'item_id'           => $mealItem->item_id,
                                                                'swap_item_id'      => $itemSwap->id,
                                                                'qty'               => $itemSwap->qty,
                                                                'carbs'             => $itemSwap->carbs,
                                                                'protein'           => $itemSwap->protein,
                                                                'fat'               => $itemSwap->fat,
                                                                'energy'            => $itemSwap->energy,
                                                                'unit'              => $itemSwap->unit,
                                                                'selected_qty_unit' => $itemSwap->selected_qty_unit,
                                                                'created_at'        => now(),
                                                                'updated_at'        => now(),
                                                            ]);
                                                        }
                                                    }
                                                }
                                            }

                                            $existingItems = DB::table('user_items')
                                                ->where('user_meal_id', $userMealId)
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                                ->pluck('id')
                                                ->toArray();
                                            $currentItems = isset($items[$planId][$mealTimeId][$categoryId][$mealId])
                                            ? $items[$planId][$mealTimeId][$categoryId][$mealId]
                                            : [];

                                            $itemsToRemove = array_diff($existingItems, $currentItems);
                                            if (! empty($itemsToRemove)) {

                                                DB::table('user_item_meals')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();

                                                DB::table('user_item_swaps')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();
                                            }

                                            $b = DB::table('user_item_meals')
                                                ->where('user_id', $request->user_id)
                                                ->where('meal_id', $mealId)
                                                ->whereNotIn('item_id', $currentItems)
                                                ->delete();

                                            DB::table('user_items')
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                                ->where('user_meal_id', $userMealId)
                                                ->delete();

                                            foreach ($currentItems as $itemId) {
                                                DB::table('user_items')->updateOrInsert(
                                                    [
                                                        'user_plan_id'         => $userPlan->id,
                                                        'user_category_id'     => $userMealTime->id,
                                                        'user_sub_category_id' => $userCategory->id,
                                                        'user_meal_id'         => $userMealId,
                                                        'id'                   => $itemId,
                                                    ]
                                                );

                                                $userItem = DB::table('user_items')
                                                    ->where('user_plan_id', $userPlan->id)
                                                    ->where('user_category_id', $userMealTime->id)
                                                    ->where('user_sub_category_id', $userCategory->id)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('id', $itemId)
                                                    ->first();

                                                $existingSwapItems = DB::table('user_swap_items')
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

                                                $p = DB::table('user_item_swaps')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('item_id', $itemId)
                                                    ->where('meal_id', $mealId)
                                                    ->whereNotIn('swap_item_id', $currentSwapItems)
                                                    ->delete();

                                                $swapItemsToRemove = array_diff($existingSwapItems, $currentSwapItems);
                                                if (! empty($swapItemsToRemove)) {
                                                    DB::table('user_swap_items')
                                                        ->where('user_plan_id', $userPlan->id)
                                                        ->where('user_meal_id', $userMealId)
                                                        ->where('user_item_id', $userItem->id)
                                                        ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_sub_category_id', $userCategory->id)
                                                        ->whereIn('id', $swapItemsToRemove)
                                                        ->delete();
                                                }

                                                foreach ($currentSwapItems as $swapItemId) {
                                                    DB::table('user_swap_items')->updateOrInsert(
                                                        [
                                                            'user_plan_id'         => $userPlan->id,
                                                            'user_meal_id'         => $userMealId,
                                                            'user_item_id'         => $userItem->id,
                                                            'user_category_id'     => $userMealTime->id,
                                                            'user_sub_category_id' => $userCategory->id,
                                                            'id'                   => $swapItemId,
                                                        ],
                                                        ['created_at' => now(), 'updated_at' => now()]
                                                    );
                                                }
                                                $a = DB::table('user_swap_items')
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

            DB::commit();

            $action = $request->input('action');
            if ($action === 'save_exit') {
                return redirect()->route('admin.purchase-plans.index')
                    ->with('success', 'User Plan created successfully.');
            }

            // For 'save' action, redirect back to edit page with correct parameters
            return redirect()->route('admin.purchase-plans.edit', [
                'user' => $payment->user_id,
                'plan' => $payment->id,
            ])->with('success', 'User Plan saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating User Plan: ' . $e->getMessage());
            Log::error('Request Data: ', $request->all());
            return redirect()->route('admin.purchase-plans.index')
                ->with('error', 'Failed to create User Plan. Error: ' . $e->getMessage());
        }
    }

    public function edit(User $user, $planId)
    {
        try {
            $payment    = Payment::find($planId);
            $plan       = $payment->plan;
            $subPlanIds = $plan->subPlans->pluck('id')->toArray();

            $userPlans = UserPlan::with([
                'plan.categories' => function ($query) {
                    $query->select('categories.id', 'categories.title');
                },
            ])
                ->where('user_id', $user->id)
                ->where('plan_id', $plan->id)
                ->when($subPlanIds, function ($query) use ($subPlanIds) {
                    return $query->orWhereIn('plan_id', $subPlanIds);
                })->get();

            if (! $userPlans) {
                return redirect()->route('admin.purchase-plans.index')->with('error', 'User Plan not found.');
            }
            $selectedMeals     = [];
            $selectedItems     = [];
            $selectedSwapItems = [];

            // Initialize Nutrition Totals
            $totalCarbs   = 0;
            $totalFat     = 0;
            $totalProtein = 0;
            $totalEnergy  = 0;

            foreach ($userPlans as $userPlan) {
                foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userCategory) {
                    $selectedMeals[$userPlan->plan_id][$userCategory->id] =
                    $userCategory->userMeals->where('user_plan_id', $userPlan->id)->pluck('id')->toArray();

                    foreach ($userCategory->userSubCategories->where('user_plan_id', $userPlan->id)->where('user_category_id', $userCategory->id) as $userSubCategory) {
                        foreach ($userSubCategory->userMeals->where('user_plan_id', $userPlan->id)->where('user_category_id', $userCategory->id) as $userMeal) {
                            $mealId                                    = $userMeal->id;
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
            }])->where('payment_id', $payment->id)->first();

            $foodPreferences = collect();

            if (! empty($userPrePlan)) {
                $usePrePlanDetails = $userPrePlan->prePlanDetails ?? null;
                if ($usePrePlanDetails) {
                    foreach ($usePrePlanDetails as $detail) {
                        $question = $detail->question ?? 'Unknown';
                        $answers  = json_decode($detail->answer, true);

                        if (is_array($answers)) {
                            $filteredAnswers = array_filter($answers);
                            if (! empty($filteredAnswers)) {
                                $foodPreferences->put($question, collect($filteredAnswers));
                            }
                        }
                    }
                }
            }

            $perPlanSelectedFoods = [];
            $step5Foods           = Item::select(['id', 'title'])->get();
            $otherFoods           = $userPrePlan           = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
                $query->where('form_slug', 'food_preference')->whereIn('question', ['Cuisines', 'Snacks']);
            }])->where('payment_id', $payment->id)->first();

            $groupedAnswers = [];

            $otherFoodsPrePlanDetails = $otherFoods->prePlanDetails ?? null;
            if (isset($otherFoodsPrePlanDetails)) {
                foreach ($otherFoodsPrePlanDetails as $detail) {
                    $question = $detail->question ?? null;
                    if (! $question) {
                        continue;
                    }

                    $answers = json_decode($detail->answer, true);

                    if (is_array($answers)) {
                        if (! isset($groupedAnswers[$question])) {
                            $groupedAnswers[$question] = $answers;
                        } else {
                            $groupedAnswers[$question] = array_merge($groupedAnswers[$question], $answers);
                        }
                    }
                }
            }
            $otherFoods = $groupedAnswers;
            return view('backend.pages.plan.purchase-plan-edit', compact(
                'userPlans',
                'selectedMeals', 'selectedItems', 'selectedSwapItems',
                'activity', 'payment', 'step5Foods', 'perPlanSelectedFoods',
                'totalCarbs', 'totalFat', 'totalProtein', 'totalEnergy', 'otherFoods', 'foodPreferences'
            ));
        } catch (\Exception $e) {
            Log::error('Error updating User Plan: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            // Find the UserPlan by ID
            $payment = Payment::findOrFail($request->payment_id);
            $action  = $request->input('action');
            // \DB::beginTransaction();
            // Initialize arrays
            $meals      = [];
            $categories = [];
            $items      = [];
            $swapItems  = [];

            // Step 1: Populate categories by planId and mealTimeId
            foreach ($request->plan_id as $planId) {
                if (isset($request->meal_times[$planId])) {
                    $mealTimeIds = array_unique($request->meal_times[$planId]);
                    foreach ($mealTimeIds as $mealTimeId) {
                        if (isset($request->meals[$planId][$mealTimeId])) {
                            $mealIds = $request->meals[$planId][$mealTimeId];
                            // Fetch categories for meals
                            $categoriesByMeal = DB::table('meal_sub_category')
                                ->whereIn('meal_id', $mealIds)
                                ->pluck('sub_category_id')
                                ->unique()
                                ->toArray();
                            $mealTimeCategories = DB::table('subcategory_category')
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
                            foreach ($categoryIds as $categoryId) {
                                if (isset($request->meals[$planId][$mealTimeId])) {
                                    $mealIds = $request->meals[$planId][$mealTimeId];
                                    foreach ($mealIds as $mealId) {
                                        $categoriesByMeal = DB::table('meal_sub_category')
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

            $userPlans = UserPlan::with([
                'plan',
                'userCategories.userSubcategories.userMeals.userItems',
            ])
                ->where('user_id', $request->user_id)
                ->whereIn('plan_id', $request->plan_id)
                ->get();

            // Check if the UserPlan exists
            if (! $userPlans) {
                return redirect()->route('admin.purchase-plans.index')
                    ->with('error', 'User Plan not found.');
            }

            DB::beginTransaction();
            // Update or Create Plans
            if (isset($request->plan_id) && is_array($request->plan_id)) {
                foreach ($request->plan_id as $planId) {
                    $userPlan = DB::table('user_plans')->updateOrInsert(
                        ['user_id' => $request->user_id, 'plan_id' => $planId],
                        ['status' => 'active', 'modified_by' => auth()->id(), 'updated_at' => now()]
                    );

                    $userPlan = DB::table('user_plans')
                        ->where('user_id', $request->user_id)
                        ->where('plan_id', $planId)
                        ->first();

                    // ✅ Get existing meals for the user plan
                    $existingMeals = DB::table('user_meals')
                        ->where('user_plan_id', $userPlan->id)
                        ->pluck('id')
                        ->toArray();

                    // Add check for meals array key
                    $newMeals = isset($meals[$planId]) ? Arr::flatten($meals[$planId]) : [];

                    // ✅ Remove meals not in the request
                    $mealsToRemove = array_diff($existingMeals, $newMeals);
                    if (! empty($mealsToRemove)) {
                        $meals = DB::table('user_meals')
                            ->where('user_plan_id', $userPlan->id)
                            ->whereIn('id', $mealsToRemove)
                            ->get();

                        foreach ($meals as $meal) {
                            $items = DB::table('user_items')
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_meal_id', $meal->id)
                                ->get();

                            foreach ($items as $item) {
                                // Get valid item_meal item_ids for this meal
                                $validItemIds = ItemMeal::where('item_id', $item->item_id)
                                    ->where('meal_id', $meal->id)
                                    ->pluck('item_id')
                                    ->toArray();

                                // If current item is NOT a valid meal item, remove all its related mappings
                                if (! in_array($item->item_id, $validItemIds)) {
                                    // Remove user_item_meals
                                    DB::table('user_item_meals')
                                        ->where('user_id', $request->user_id)
                                        ->where('meal_id', $meal->id)
                                        ->where('item_id', $item->id)
                                        ->delete();

                                    // Remove user_swap_items
                                    DB::table('user_swap_items')
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_meal_id', $meal->id)
                                        ->where('user_item_id', $item->id)
                                        ->delete();

                                    // Remove user_item_swaps
                                    DB::table('user_item_swaps')
                                        ->where('user_id', $request->user_id)
                                        ->where('meal_id', $meal->id)
                                        ->where('item_id', $item->id)
                                        ->delete();

                                    // ✅ Only delete user_items if item is not valid
                                    DB::table('user_items')
                                        ->where('id', $item->id)
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_meal_id', $meal->id)
                                        ->delete();
                                }
                            }
                        }

                        $meals = DB::table('user_meals')
                            ->where('user_plan_id', $userPlan->id)
                            ->whereIn('meal_id', $mealsToRemove)
                            ->delete();
                    }

                    // Check if meal_times exists for this plan
                    if (isset($request->meal_times[$planId])) {
                        foreach (array_unique($request->meal_times[$planId]) as $mealTimeId) {
                            $userMealTime = DB::table('user_categories')->updateOrInsert(
                                ['user_plan_id' => $userPlan->id, 'id' => $mealTimeId],
                                ['created_at' => now(), 'updated_at' => now()]
                            );

                            $userMealTime = DB::table('user_categories')
                                ->where('user_plan_id', $userPlan->id)
                                ->where('id', $mealTimeId)
                                ->first();

                            // Check if categories exist for this plan and meal time
                            if (isset($categories[$planId][$mealTimeId])) {
                                foreach ($categories[$planId][$mealTimeId] as $categoryId) {
                                    DB::table('user_sub_categories')->updateOrInsert(
                                        ['user_plan_id' => $userPlan->id, 'user_category_id' => $userMealTime->id, 'id' => $categoryId],
                                        ['created_at' => now(), 'updated_at' => now()]
                                    );

                                    $userCategory = DB::table('user_sub_categories')
                                        ->where('user_plan_id', $userPlan->id)
                                        ->where('user_category_id', $userMealTime->id)
                                        ->where('id', $categoryId)
                                        ->first();

                                    // Check if meals exist for this plan, meal time, and category
                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        foreach ($meals[$planId][$mealTimeId][$categoryId] as $mealId) {
                                            // ✅ FIX: Check for meal with full context (meal_time + category)
                                            $userMeal = DB::table('user_meals')->where([
                                                'user_plan_id'         => $userPlan->id,
                                                'user_category_id'     => $userMealTime->id,
                                                'user_sub_category_id' => $userCategory->id,
                                                'id'                   => $mealId,
                                            ])->first();

                                            if (! $userMeal) {
                                                $userMeal = DB::table('user_meals')->updateOrInsert([
                                                    'user_plan_id'         => $userPlan->id,
                                                    'user_category_id'     => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id'                   => $mealId,
                                                    'created_at'           => now(),
                                                    'updated_at'           => now(),
                                                ]);
                                                $userMeal = DB::table('user_meals')->where([
                                                    'user_plan_id'         => $userPlan->id,
                                                    'user_category_id'     => $userMealTime->id,
                                                    'user_sub_category_id' => $userCategory->id,
                                                    'id'                   => $mealId,
                                                ])->first();
                                            }
                                            $userMealId = $userMeal->id ?? null;

                                            $mealItems = ItemMeal::where('meal_id', $mealId)->get();
                                            foreach ($mealItems as $mealItem) {
                                                $mealExist = DB::table('user_item_meals')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->where('item_id', $mealItem->item_id)
                                                    ->first();

                                                if (! $mealExist) {
                                                    // Insert meal items for the user
                                                    UserItemMeal::create([
                                                        'user_id'           => $request->user_id,
                                                        'meal_id'           => $mealId,
                                                        'item_id'           => $mealItem->item_id,
                                                        'qty'               => $mealItem->item_qty,
                                                        'unit'              => $mealItem->item_qty_unit,
                                                        'carbs'             => $mealItem->carbs,
                                                        'protein'           => $mealItem->protein,
                                                        'fat'               => $mealItem->fat,
                                                        'energy'            => $mealItem->energy,
                                                        'selected_qty_unit' => $mealItem->selected_qty_unit,
                                                        'created_at'        => now(),
                                                        'updated_at'        => now(),
                                                    ]);

                                                    $itemSwapIds = DB::table('item_swaps')->where('item_id', $mealItem->item_id)->pluck('swap_item_id')->toArray();
                                                    $itemSwaps   = Item::whereIn('id', $itemSwapIds)->get();
                                                    foreach ($itemSwaps as $itemSwap) {
                                                        $swapItemExist = DB::table('user_item_swaps')
                                                            ->where('user_id', $request->user_id)
                                                            ->where('meal_id', $mealId)
                                                            ->where('item_id', $mealItem->item_id)
                                                            ->where('swap_item_id', $itemSwap->id)
                                                            ->first();
                                                        // Insert item swaps for the user
                                                        if (! $swapItemExist) {
                                                            UserItemSwap::create([
                                                                'user_id'           => $request->user_id,
                                                                'meal_id'           => $mealId,
                                                                'item_id'           => $mealItem->item_id,
                                                                'swap_item_id'      => $itemSwap->id,
                                                                'qty'               => $itemSwap->qty,
                                                                'carbs'             => $itemSwap->carbs,
                                                                'protein'           => $itemSwap->protein,
                                                                'fat'               => $itemSwap->fat,
                                                                'energy'            => $itemSwap->energy,
                                                                'unit'              => $itemSwap->unit,
                                                                'selected_qty_unit' => $itemSwap->selected_qty_unit,
                                                                'created_at'        => now(),
                                                                'updated_at'        => now(),
                                                            ]);
                                                        }
                                                    }
                                                }
                                            }

                                            $existingItems = DB::table('user_items')
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

                                            if (! empty($itemsToRemove)) {
                                                DB::table('user_item_meals')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();

                                                DB::table('user_item_swaps')
                                                    ->where('user_id', $request->user_id)
                                                    ->where('meal_id', $mealId)
                                                    ->whereIn('item_id', $itemsToRemove)
                                                    ->delete();
                                            }

                                            $an = DB::table('user_item_meals')
                                                ->where('user_id', $request->user_id)
                                                ->where('meal_id', $mealId)
                                                ->whereNotIn('item_id', $currentItems)
                                                ->delete();

                                            $existingItems = DB::table('user_items')
                                                ->where('user_meal_id', $userMealId)
                                                ->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userMealTime->id)
                                                ->where('user_sub_category_id', $userCategory->id)
                                            //->pluck('id')
                                                ->delete();
                                            foreach ($currentItems as $itemId) {
                                                $a = DB::table('user_items')->updateOrInsert(
                                                    [
                                                        'user_plan_id'         => $userPlan->id,
                                                        'user_category_id'     => $userMealTime->id,
                                                        'user_sub_category_id' => $userCategory->id,
                                                        'user_meal_id'         => $userMealId,
                                                        'id'                   => $itemId,
                                                    ],
                                                    ['created_at' => now(), 'updated_at' => now()]
                                                );

                                                $userItem = DB::table('user_items')
                                                    ->where('user_plan_id', $userPlan->id)
                                                    ->where('user_category_id', $userMealTime->id)
                                                    ->where('user_sub_category_id', $userCategory->id)
                                                    ->where('user_meal_id', $userMealId)
                                                    ->where('id', $itemId)
                                                    ->first();

                                                $existingSwapItems = DB::table('user_swap_items')
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
                                                // Remove nulls from the array
                                                $currentSwapItems = array_filter($currentSwapItems, function ($value) {
                                                    return ! is_null($value);
                                                });
                                                if (! empty($currentSwapItems)) {
                                                    $query = DB::table('user_item_swaps')
                                                        ->where('user_id', $request->user_id)
                                                        ->where('item_id', $itemId)
                                                        ->where('meal_id', $mealId)
                                                        ->whereNotIn('swap_item_id', $currentSwapItems)
                                                        ->delete();
                                                }
                                                $swapItemsToRemove = array_diff($existingSwapItems, $currentSwapItems);

                                                if (! empty($swapItemsToRemove)) {
                                                    $j = DB::table('user_swap_items')
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
                                                    $k = DB::table('user_swap_items')->updateOrInsert(
                                                        [
                                                            'user_plan_id'         => $userPlan->id,
                                                            'user_meal_id'         => $userMealId,
                                                            'user_item_id'         => $userItem->id,
                                                            'user_category_id'     => $userMealTime->id,
                                                            'user_sub_category_id' => $userCategory->id,
                                                            'id'                   => $swapItemId,
                                                        ],
                                                        ['created_at' => now(), 'updated_at' => now()]
                                                    );
                                                }
                                                $b = DB::table('user_swap_items')
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

            DB::commit();

            if ($action === 'save_exit') {
                return redirect()->route('admin.purchase-plans.index')
                    ->with('success', 'User Plan updated successfully.');
            }

            return redirect()->back()->with('success', 'User Plan updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating User Plan: ' . $e->getMessage());
            Log::error('Request Data: ', $request->all());

            return redirect()->route('admin.purchase-plans.index')
                ->with('error', 'Failed to update User Plan. Error: ' . $e->getMessage());
        }
    }

    public function getMealItems(Request $request)
    {
        $userId = $request->user_id;
        if ($request->type == 'edit') {
            $userMeal = UserItemMeal::where('meal_id', $request->meal_id)
                ->where('user_id', $userId)
                ->get();
            if ($userMeal->isEmpty()) {
                $meal = Meal::with('items.swapItems')->find($request->meal_id);

                if (! $meal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Meal not found.',
                    ], 404);
                }

                $mealName = $meal->title;
                $mealId   = $meal->id;

                $totalCarbs   = 0;
                $totalProtein = 0;
                $totalFat     = 0;
                $totalEnergy  = 0;
                $data         = $meal->items->map(function ($item) use (&$totalCarbs, &$totalProtein, &$totalFat, &$totalEnergy, $request) {
                    $totalCarbs += $item->pivot->carbs ?? $item->carbs;
                    $totalProtein += $item->pivot->protein ?? $item->protein;
                    $totalFat += $item->pivot->fat ?? $item->fat;
                    $totalEnergy += floatval($item->energy) ?? floatval($item->energy);
                    $swapItems = $item->swapItems->map(function ($swapItem) {
                        return [
                            'id'                => $swapItem->id,
                            'name'              => $swapItem->title,
                            'category_id'       => $swapItem->category_id,
                            'qty'               => $swapItem->qty ?? 0,
                            'unit'              => $swapItem->unit ?? '',
                            'carbs'             => $swapItem->carbs,
                            'protein'           => $swapItem->protein,
                            'fat'               => $swapItem->fat,
                            'energy'            => $swapItem->energy ?? 0,
                            'description'       => $swapItem->description,
                            'selected_qty_unit' => $swapItem->selected_qty_unit,
                        ];
                    });

                    $isNew = \App\Models\ItemMeal::where('meal_id', $request->meal_id)
                        ->where('item_id', $item->id)
                        ->exists() ? 0 : 1;

                    return [
                        'id'                => $item->id,
                        'name'              => $item->title,
                        'category_id'       => $item->category_id,
                        'qty'               => isset($item->pivot->item_qty) ? $item->pivot->item_qty : $item->qty,
                        'unit'              => isset($item->pivot->item_qty_unit) ? $item->pivot->item_qty_unit : $item->unit,
                        'carbs'             => isset($item->pivot->carbs) ? $item->pivot->carbs : $item->carbs,
                        'protein'           => isset($item->pivot->protein) ? $item->pivot->protein : $item->protein,
                        'fat'               => isset($item->pivot->fat) ? $item->pivot->fat : $item->fat,
                        'energy'            => isset($item->pivot->energy) ? $item->pivot->energy : $item->energy ?? 0,
                        'description'       => isset($item->description) ? $item->description : null,
                        'selected_qty_unit' => $this->decodeSelectedQtyUnit($item->pivot->selected_qty_unit ?? $item->selected_qty_unit),
                        'swapItems'         => $swapItems,
                        'is_new'            => $isNew,
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

                $userPlan = UserPlan::where('user_id', $request->user_id)
                    ->where('plan_id', $request->plan_id)
                    ->first();
                $userMealTimes  = null;
                $userUpdateMeal = null;
                if ($userPlan) {
                    $userMealTimes = UserCategory::where('user_plan_id', $userPlan->id)
                        ->where('id', $request->meal_time_id)
                        ->first();

                    if ($userMealTimes) {
                        $userUpdateMeal = UserMeal::select('meal_name')->where('user_category_id', $userMealTimes->id)
                            ->where('id', $request->meal_id)
                            ->first();
                    }
                }

                $mealName = $userUpdateMeal->meal_name ?? $meal->title;
                $mealId   = $meal->id;

                $totalCarbs   = 0;
                $totalProtein = 0;
                $totalFat     = 0;
                $totalEnergy  = 0;

                $itemIds   = $userMeal->toArray();
                $itemIds   = $itemIds ? array_column($itemIds, 'item_id') : [];
                $itemsList = [];
                if (! empty($itemIds)) {
                    $userItemMeal  = new UserItemMeal();
                    $itemsListTemp = $userItemMeal->getItems($itemIds);
                    foreach ($itemsListTemp as $item) {
                        $itemsList[$item['id']] = $item;
                    }
                }

                $meal_ids                  = array_unique(array_column($itemIds, 'meal_id'));
                $item_ids                  = array_unique(array_column($itemIds, 'item_id'));
                $existingMealAndItemsQuery = ItemMeal::select(['id', 'meal_id', 'item_id'])->whereIn('meal_id', $meal_ids)
                    ->whereIn('item_id', $item_ids)
                    ->get();
                $existingMealAndItems = [];
                if ($meal_ids && $item_ids) {
                    $existingMealAndItemsQuery = $existingMealAndItemsQuery->toArray();
                    foreach ($existingMealAndItemsQuery as $mealItem) {
                        $existingMealAndItems[] = $mealItem['meal_id'] . '_' . $mealItem['item_id'];
                    }
                }

                $data = $userMeal->map(function ($item) use ($userId, &$totalCarbs, &$totalProtein, &$totalFat, &$totalEnergy, $request, &$itemsList, $existingMealAndItems) {
                    $isNew = in_array($request->meal_id . '_' . $item->item_id, $existingMealAndItems) ? 0 : 1;

                    $totalCarbs += isset($item->carbs) ? $item->carbs : $item->items->carbs;
                    $totalProtein += isset($item->protein) ? $item->protein : $item->items->protein;
                    $totalFat += isset($item->fat) ? $item->fat : $item->items->fat;
                    $totalEnergy += isset($item->energy) ? floatval($item->energy) : (isset($item->items->energy) ? floatval($item->items->energy) : 0);

                    $swapItems = UserItemSwap::with('swapItem')
                        ->where('item_id', $item->item_id)
                        ->where('user_id', $userId)
                        ->where('meal_id', $request->meal_id)
                        ->get();
                    if ($swapItems->isEmpty()) {
                        $swapItems = optional(optional($item->items)->swapItems)->map(function ($swapItem) {
                            return [
                                'id'                => $swapItem->id,
                                'name'              => $swapItem->title,
                                'category_id'       => $swapItem->category_id,
                                'qty'               => $swapItem->qty ?? 0,
                                'unit'              => $swapItem->unit ?? '',
                                'carbs'             => $swapItem->carbs,
                                'protein'           => $swapItem->protein,
                                'fat'               => $swapItem->fat,
                                'energy'            => $swapItem->energy ?? 0,
                                'description'       => $swapItem->description,
                                'selected_qty_unit' => $swapItem->selected_qty_unit,
                            ];
                        });
                        // $swapItems = [];
                    } else {
                        $swapItems = $swapItems->map(function ($swapFood) {
                            $swapItem = optional($swapFood->swapItem);

                            return [
                                'id'                => $swapItem->id,
                                'name'              => $swapItem->title,
                                'qty'               => $swapFood->qty,
                                'unit'              => $swapFood->unit ?? '',
                                'carbs'             => $swapFood->carbs ?? $swapItem->carbs,
                                'protein'           => $swapFood->protein ?? $swapItem->protein,
                                'fat'               => $swapFood->fat ?? $swapItem->fat,
                                'energy'            => $swapFood->energy ?? $swapItem->energy ?? 0,
                                'description'       => $swapItem->description ?? null,
                                'selected_qty_unit' => $this->decodeSelectedQtyUnit($swapFood->selected_qty_unit ?? $swapItem->selected_qty_unit ?? ''),
                            ];
                        });
                    }

                    $itemData = isset($itemsList[$item->item_id]) ? $itemsList[$item->item_id] : [];
                    return [
                        'id'                => (isset($itemData['id']) ? $itemData['id'] : null) ?? $item->item_id,
                        'name'              => (isset($itemData['title']) ? $itemData['title'] : null) ?? '',
                        'qty'               => $item->qty ?? (isset($itemData['item_qty']) ? $itemData['item_qty'] : null) ?? 0,
                        'unit'              => $item->unit ?? (isset($itemData['unit']) ? $itemData['unit'] : null) ?? '',
                        'carbs'             => $item->carbs ?? (isset($itemData['carbs']) ? $itemData['carbs'] : null) ?? 0,
                        'protein'           => $item->protein ?? (isset($itemData['protein']) ? $itemData['protein'] : null) ?? 0,
                        'fat'               => $item->fat ?? (isset($itemData['fat']) ? $itemData['fat'] : null) ?? 0,
                        'energy'            => $item->energy ?? (isset($itemData['energy']) ? $itemData['energy'] : null) ?? 0,
                        'description'       => (isset($itemData['description']) ? $itemData['description'] : null) ?? null,
                        'selected_qty_unit' => $item->selected_qty_unit ?? (isset($itemData['selected_qty_unit']) ? $itemData['selected_qty_unit'] : null) ?? '',
                        'is_new'            => $isNew,
                        'swapItems'         => $swapItems,
                    ];
                });
            }
        } else {
            $meal = Meal::with('items.swapItems')->find($request->meal_id);

            if (! $meal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meal not found.',
                ], 404);
            }

            $mealName = $meal->title;
            $mealId   = $meal->id;

            $totalCarbs   = 0;
            $totalProtein = 0;
            $totalFat     = 0;
            $totalEnergy  = 0;

            $data = $meal->items->map(function ($item) use (&$totalCarbs, &$totalProtein, &$totalFat, &$totalEnergy, $request) {
                $totalCarbs += $item->pivot->carbs ?? $item->carbs;
                $totalProtein += $item->pivot->protein ?? $item->protein;
                $totalFat += $item->pivot->fat ?? $item->fat;
                $totalEnergy += floatval($item->energy) ?? floatval($item->energy);

                $swapItems = optional($item->swapItems)->map(function ($swapItem) {
                    return [
                        'id'                => $swapItem->id,
                        'name'              => $swapItem->title,
                        'category_id'       => $swapItem->category_id,
                        'qty'               => $swapItem->qty ?? 0,
                        'unit'              => $swapItem->unit ?? '',
                        'carbs'             => $swapItem->carbs,
                        'protein'           => $swapItem->protein,
                        'fat'               => $swapItem->fat,
                        'energy'            => $swapItem->energy ?? 0,
                        'description'       => $swapItem->description,
                        'selected_qty_unit' => $swapItem->selected_qty_unit,
                    ];
                });

                $isNew = ItemMeal::where('meal_id', $request->meal_id)
                    ->where('item_id', $item->id)
                    ->exists() ? 0 : 1;

                return [
                    'id'                => $item->id,
                    'name'              => $item->title,
                    'category_id'       => $item->category_id,
                    'qty'               => isset($item->pivot->item_qty) ? $item->pivot->item_qty : $item->qty,
                    'unit'              => isset($item->pivot->item_qty_unit) ? $item->pivot->item_qty_unit : $item->unit,
                    'carbs'             => isset($item->pivot->carbs) ? $item->pivot->carbs : $item->carbs,
                    'protein'           => isset($item->pivot->protein) ? $item->pivot->protein : $item->protein,
                    'fat'               => isset($item->pivot->fat) ? $item->pivot->fat : $item->fat,
                    'energy'            => isset($item->pivot->energy) ? $item->pivot->energy : $item->energy ?? 0,
                    'description'       => isset($item->description) ? $item->description : null,
                    'selected_qty_unit' => isset($item->pivot->selected_qty_unit) ? $item->pivot->selected_qty_unit : $item->selected_qty_unit,
                    'swapItems'         => $swapItems,
                    'is_new'            => $isNew,
                ];
            });

        }
        return response()->json([
            'success'       => true,
            'meal_id'       => $mealId,
            'meal_name'     => $mealName,
            'meal_note'     => $meal->note,
            'data'          => $data,
            'total_carbs'   => number_format($totalCarbs, 2),
            'total_protein' => number_format($totalProtein, 2),
            'total_fat'     => number_format($totalFat, 2),
            'total_energy'  => number_format($totalEnergy, 2),
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

    public function getMealsByMealTime(Request $request)
    {
        $mealTime = Category::with('subCategories.meals.items')->where('id', $request->meal_time_id)->first();

        if (! $mealTime) {
            return response()->json([
                'success' => false,
                'message' => 'MealTime not found.',
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
                    $carbs   = 0;
                    $protein = 0;
                    $fat     = 0;
                    $energy  = 0;

                    foreach ($meal->items as $item) {
                        $carbs += $item->pivot->carbs ?? $item->carbs ?? 0;
                        $protein += $item->pivot->protein ?? $item->protein ?? 0;
                        $fat += $item->pivot->fat ?? $item->fat ?? 0;
                        $energy += $item->pivot->energy ?? floatval($item->energy ?? 0);
                    }

                    $meals->push([
                        'id'      => $meal->id,
                        'name'    => $meal->title,
                        'image'   => $meal->image ? webAssets('storage/' . $meal->image) : null,
                        'carbs'   => round($carbs, 2),
                        'protein' => round($protein, 2),
                        'fat'     => round($fat, 2),
                        'energy'  => round($energy, 2),
                    ]);
                }
            }
        }

        // Replace names with user meal names if found
        $userPlan = UserPlan::where('user_id', $userId)->where('plan_id', $request->plan_id)->first();

        if ($userPlan) {
            $userMealTimes = UserCategory::where('user_plan_id', $userPlan->id)
                ->where('id', $request->meal_time_id)->first();

            $userMeals = collect();

            if ($userMealTimes && $userMealTimes->userSubCategories) {
                $userMeals = $userMealTimes->userSubCategories->flatMap(function ($category) use ($userPlan) {
                    return $category->userMeals->map(function ($userMeal) use ($userPlan) {
                        $carbs   = 0;
                        $protein = 0;
                        $fat     = 0;
                        $energy  = 0;

                        $userItemsData = $userMeal->userItems->where('user_plan_id', $userPlan->id)->toArray();
                        $itemIds       = $userItemsData ? array_column($userItemsData, 'id') : [];

                        if (! empty($itemIds)) {
                            $items = DB::table('items')
                                ->selectRaw('SUM(carbs) as carbs, SUM(protein) as protein, SUM(fat) as fat, SUM(energy) as energy')
                                ->whereIn('id', $itemIds)
                                ->first();
                        } else {
                            $items = (object) [
                                'carbs'   => 0,
                                'protein' => 0,
                                'fat'     => 0,
                                'energy'  => 0,
                            ];
                        }

                        return [
                            'id'      => $userMeal->id,
                            'name'    => $userMeal->meal_name,
                            'image'   => $userMeal->meal && $userMeal->meal->image ? asset('private/public/storage/' . $userMeal->meal->image) : null,
                            'carbs'   => round($items->carbs ?? 0, 2),
                            'protein' => round($items->protein ?? 0, 2),
                            'fat'     => round($items->fat ?? 0, 2),
                            'energy'  => round($items->energy ?? 0, 2),
                        ];
                    });
                });
            }

            // Replace meal names with user meal names if matched
            $meals = $meals->map(function ($meal) use ($userMeals) {
                $match = $userMeals->firstWhere('id', $meal['id']);
                if ($match && ! empty($match['name'])) {
                    $meal['name'] = $match['name'];
                }
                return $meal;
            });
        }

        return response()->json([
            'success' => true,
            'meals'   => $meals->values(),
        ]);
    }

    public function getMealsByMealTimeBatch(Request $request)
    {
        // Validate input
        $request->validate([
            'meal_times'                => 'required|array',
            'meal_times.*.plan_id'      => 'required|integer',
            'meal_times.*.meal_time_id' => 'required|integer',
            'meal_times.*.user_id'      => 'required|integer',
        ]);

        $mealTimesData = $request->meal_times;
        $search        = strtolower($request->input('search', ''));
        $mealTimeIds   = array_column($mealTimesData, 'meal_time_id');
        $userIds       = array_unique(array_column($mealTimesData, 'user_id'));
        $planIds       = array_unique(array_column($mealTimesData, 'plan_id'));

        // Fetch all meal times with related data in one query
        $mealTimes = Category::with('subCategories.meals.items')
            ->whereIn('id', $mealTimeIds)
            ->get()
            ->keyBy('id');

        // Prepare response structure
        $response = ['success' => true, 'meal_data' => []];

        // Fetch user plans for all users and plans
        $userPlans = UserPlan::whereIn('user_id', $userIds)
            ->whereIn('plan_id', $planIds)
            ->get()
            ->keyBy(function ($plan) {
                return "{$plan->user_id}_{$plan->plan_id}";
            });

        // Fetch user categories and their meals in one query
        $userCategories = UserCategory::whereIn('id', $mealTimeIds)
            ->whereIn('user_plan_id', $userPlans->pluck('id'))
            ->with(['userSubCategories.userMeals.userItems' => function ($query) use ($userPlans) {
                $query->whereIn('user_plan_id', $userPlans->pluck('id'));
            }])
            ->get()
            ->groupBy('id');

        foreach ($mealTimesData as $data) {
            $planId     = $data['plan_id'];
            $mealTimeId = $data['meal_time_id'];
            $userId     = $data['user_id'];
            $key        = "{$planId}_{$mealTimeId}";

            $response['meal_data'][$key] = [];

            $mealTime = $mealTimes->get($mealTimeId);
            if (! $mealTime) {
                continue; // Skip if meal time not found
            }

            $meals = collect();

            // Process meals for the meal time
            foreach ($mealTime->subCategories as $category) {
                foreach ($category->meals as $meal) {
                    $allowedUser   = is_null($meal->user_id) || in_array($meal->user_id, [$userId, 7, 3]);
                    $matchesSearch = empty($search) ||
                    str_contains(strtolower($meal->title), $search) ||
                    str_contains(strtolower($category->title), $search);

                    if ($allowedUser && $matchesSearch) {
                        $carbs   = 0;
                        $protein = 0;
                        $fat     = 0;
                        $energy  = 0;

                        foreach ($meal->items as $item) {
                            $carbs += $item->pivot->carbs ?? $item->carbs ?? 0;
                            $protein += $item->pivot->protein ?? $item->protein ?? 0;
                            $fat += $item->pivot->fat ?? $item->fat ?? 0;
                            $energy += $item->pivot->energy ?? floatval($item->energy ?? 0);
                        }

                        $meals->push([
                            'id'      => $meal->id,
                            'name'    => $meal->title,
                            'image'   => $meal->image ? webAssets('storage/' . $meal->image) : null,
                            'carbs'   => round($carbs, 2),
                            'protein' => round($protein, 2),
                            'fat'     => round($fat, 2),
                            'energy'  => round($energy, 2),
                        ]);
                    }
                }
            }

            // Replace names with user meal names if found
            $userPlan = $userPlans->get("{$userId}_{$planId}");
            if ($userPlan) {
                $userMealTimes = $userCategories->get($mealTimeId, collect());
                $userMeals     = collect();

                if ($userMealTimes->isNotEmpty()) {
                    $firstMealTime = $userMealTimes->first();

                    if ($firstMealTime) {
                        $userSubCategories = $firstMealTime->userSubCategories;

                        // Gather all item IDs for this user plan
                        $allItemIds = $userSubCategories->flatMap(function ($category) use ($userPlan) {
                            return $category->userMeals->flatMap(function ($userMeal) use ($userPlan) {
                                return $userMeal->userItems
                                    ->where('user_plan_id', $userPlan->id)
                                    ->pluck('id');
                            });
                        })->unique()->values();

                        // Fetch nutrition data in bulk and typecast values
                        $itemNutrition = DB::table('items')
                            ->selectRaw('id,
                                COALESCE(carbs, 0) as carbs,
                                COALESCE(protein, 0) as protein,
                                COALESCE(fat, 0) as fat,
                                COALESCE(energy, 0) as energy')
                            ->whereIn('id', $allItemIds)
                            ->get()
                            ->keyBy('id');

                        // Map meals
                        $userMeals = $userSubCategories->flatMap(function ($category) use ($userPlan, $itemNutrition) {
                            return $category->userMeals->map(function ($userMeal) use ($userPlan, $itemNutrition) {
                                $userItems = $userMeal->userItems
                                    ->where('user_plan_id', $userPlan->id);

                                $totals = ['carbs' => 0.0, 'protein' => 0.0, 'fat' => 0.0, 'energy' => 0.0];

                                foreach ($userItems as $item) {
                                    $nutrients = $itemNutrition->get($item->id);
                                    if ($nutrients) {
                                        $totals['carbs'] += (float) $nutrients->carbs;
                                        $totals['protein'] += (float) $nutrients->protein;
                                        $totals['fat'] += (float) $nutrients->fat;
                                        $totals['energy'] += (float) $nutrients->energy;
                                    }
                                }

                                return [
                                    'id'      => $userMeal->id,
                                    'name'    => $userMeal->meal_name,
                                    'image'   => $userMeal->meal && $userMeal->meal->image
                                    ? asset('private/public/storage/' . $userMeal->meal->image)
                                    : null,
                                    'carbs'   => round($totals['carbs'], 2),
                                    'protein' => round($totals['protein'], 2),
                                    'fat'     => round($totals['fat'], 2),
                                    'energy'  => round($totals['energy'], 2),
                                ];
                            });
                        });
                    }
                }

                $meals = $meals->map(function ($meal) use ($userMeals) {
                    $match = $userMeals->firstWhere('id', $meal['id']);
                    if ($match && ! empty($match['name'])) {
                        $meal['name'] = $match['name'];
                    }
                    return $meal;
                });
            }

            $response['meal_data'][$key] = $meals->values()->toArray();
        }

        return response()->json($response);
    }

    public function getPrePlanDetails($id)
    {
        $userPrePlan    = UserPrePlan::with('prePlanDetails', 'payment')->where('payment_id', $id)->first();
        $prePlanDetails = $userPrePlan->prePlanDetails ?? [];
        if (! $userPrePlan) {
            return response()->json([
                'success'     => false,
                'userDetails' => null,
                'data'        => null,
            ]);
        }
        $userDetails = [
            'name'       => isset($userPrePlan->user) ? $userPrePlan->user->name : '',
            'email'      => isset($userPrePlan->user) ? $userPrePlan->user->email : '',
            'phone'      => $userPrePlan->payment->phone,
            'dob'        => formatDate($userPrePlan->dob) ?? '',
            'address'    => $userPrePlan->address ?? '',
            'occupation' => $userPrePlan->occupation ?? '',
            'culture'    => $userPrePlan->culture ?? '',
            'referredBy' => $userPrePlan->referredBy ?? '',
            'other'      => $userPrePlan->other ?? '',
        ];
        // Group questions and answers by form_name
        $groupedData = [];
        foreach ($prePlanDetails as $detail) {
            $formName = $detail['form_name'];

            // Decode JSON answers where applicable
            $answer        = $detail['answer'];
            $decodedAnswer = json_decode($answer, true);
            $finalAnswer   = $decodedAnswer !== null ? $decodedAnswer : $answer;

            // Add to the grouped data
            if (! isset($groupedData[$formName])) {
                $groupedData[$formName] = [];
            }
            $groupedData[$formName][$detail['question']] = $finalAnswer;
        }

        $foodGroups = [
            'Grains'          => [
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
            'Nuts'            => [
                'Nuts',
            ],
            'Seeds'           => [
                'Seeds',
            ],
            'Eggs'            => [
                'Eggs',
            ],
            'Meat'            => [
                'Beef',
                'Chicken',
                'Lamb',
                'Pork',
                'Turkey',
                'Deli Meat',
            ],
            'Plant Based'     => [
                'Meat Alternatives',
            ],
            'Seafood'         => [
                'Fresh Seafood',
                'Tinned Seafood',
            ],
            'Dairy'           => [
                'Milk',
                'Cheese',
                'Yoghurt',
            ],
            'Fruit'           => [
                'Fruit',
            ],
            'Vegetables'      => [
                'Vegetables',
            ],
            'Oils / Butter'   => [
                'Butters',
                'Oils',
            ],
            'Snacks'          => [
                'Fruit & Nut bars',
                'Muesli bars',
                'Other Snacks',
                'Chocolate bars',
                'Lollies',
            ],
            'Drinks'          => [
                'Cold Drinks',
                'Hot Drinks',
            ],
            'Cuisines'        => [
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
            'success'     => true,
            'userDetails' => $userDetails,
            'data'        => $groupedData,
            'foodGroups'  => $foodGroups,

        ]);

    }

    public function getItems(Request $request)
    {
        $items = Item::where('is_swiped', 0)->get();

        return response()->json([
            'success' => true,
            'items'   => $items,
        ]);
    }

    public function getSwapitems(Request $request)
    {
        if ($request->has('type') && $request->type == "edit") {
            $item = UserItemMeal::with('items')
                ->where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->where('item_id', $request->item_id)
                ->first();
            if (! $item) {
                $userPlan = UserPlan::where('plan_id', $request->plan_id)->where('user_id', $request->user_id)->first();
                $userMeal = UserMeal::where('user_plan_id', $userPlan->id)->where('meal_id', $request->meal_id)->first();
                $item     = UserItem::where('user_meal_id', $userMeal->id)->where('user_plan_id', $userPlan->id)->first();
            }

            $items = Item::where('is_swiped', 1)->get();

            $selectedSwapItems = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->pluck('swap_item_id')->toArray();

            $selectedSwapItems = Item::whereIn('id', $selectedSwapItems)->get();
            $selectedItemArr   = [];
            foreach ($selectedSwapItems as $swapItem) {
                $Item              = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
                $selectedItemArr[] = [
                    'id'          => $swapItem->id,
                    'name'        => $swapItem->title,
                    'qty'         => $Item->qty,
                    'unit'        => $Item->unit,
                    'image'       => $swapItem->image,
                    'carbs'       => $swapItem->carbs,
                    'protein'     => $swapItem->protein,
                    'description' => $swapItem->description,
                    'fat'         => $swapItem->fat,
                    'energy'      => $swapItem->energy,

                ];
            }
            return response()->json([
                'success'           => true,
                'item'              => $item,
                'swapItems'         => $items,
                'selectedSwapItems' => $selectedItemArr,
            ]);
        } else {
            $item  = Item::find($request->item_id);
            $items = isset($item->swapItems) ? $item->swapItems : [];

            $selectedSwapItems = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->pluck('swap_item_id')->toArray();

            $selectedSwapItems = Item::whereIn('id', $selectedSwapItems)->get();
            $selectedItemArr   = [];
            foreach ($selectedSwapItems as $swapItem) {
                $Item              = DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
                $selectedItemArr[] = [
                    'id'    => $swapItem->id,
                    'name'  => $swapItem->title,
                    'qty'   => $Item->qty,
                    'unit'  => $Item->unit,
                    'image' => $swapItem->image,
                ];
            }

            return response()->json([
                'success'           => true,
                'item'              => $item,
                'swapItems'         => $items,
                'selectedSwapItems' => ! empty($selectedItemArr) ? $selectedItemArr : collect($items)->map(fn($item) => ['id' => $item->id, 'name' => $item->title, 'qty' => $item->qty, 'unit' => $item->unit, 'image' => $item->image])->toArray(),

            ]);
        }
    }

    public function updateFoodSwapFoods(Request $request)
    {
        $selectedQtyUnit = collect($request->selected_qty_unit)
            ->firstWhere('checked', true);

        $qty  = isset($selectedQtyUnit['qty']) ? $selectedQtyUnit['qty'] : null;
        $unit = isset($selectedQtyUnit['unit']) ? $selectedQtyUnit['unit'] : null;

        if ($request->type == "item-update") {
            $userItemMeal = UserItemMeal::with('items')
                ->where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->where('item_id', $request->item_id)
                ->first();

            $savedItem = null;

            // ✅ Update or create UserItemMeal
            if ($userItemMeal) {
                $userItemMeal->update([
                    'qty'               => $qty,
                    'unit'              => $unit,
                    'carbs'             => $request->food_carbs,
                    'protein'           => $request->food_protein,
                    'fat'               => $request->food_fat,
                    'energy'            => $request->food_energy,
                    'selected_qty_unit' => $request->selected_qty_unit,
                    'updated_at'        => now(),
                ]);
                $savedItem = $userItemMeal->load('items');
            } else {
                $savedItem = UserItemMeal::create([
                    'user_id'           => $request->user_id,
                    'item_id'           => $request->item_id,
                    'qty'               => $qty,
                    'unit'              => $unit,
                    'carbs'             => $request->food_carbs,
                    'protein'           => $request->food_protein,
                    'fat'               => $request->food_fat,
                    'energy'            => $request->food_energy,
                    'meal_id'           => $request->meal_id,
                    'selected_qty_unit' => $request->selected_qty_unit,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $savedItem->load('items');
            }
            return response()->json([
                'success' => true,
                'item'    => $savedItem,
                'message' => 'item updated successfully!',
            ]);
        }
        if ($request->type == "swap-food-update") {

            $existingSwap = UserItemSwap::with('swapItem')
                ->where('item_id', $request->item_id)
                ->where('meal_id', $request->meal_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->where('user_id', $request->user_id)
                ->first();
            if (! $existingSwap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Swap food not found.',
                ], 404);
            }

            $existingSwap->update([
                'qty'               => $qty,
                'carbs'             => $request->swap_food_carbs,
                'protein'           => $request->swap_food_protein,
                'fat'               => $request->swap_food_fat,
                'energy'            => $request->swap_food_energy,
                'unit'              => $unit,
                'selected_qty_unit' => $request->swap_selected_qty_unit,
                'updated_at'        => now(),
            ]);
            $savedSwapItems[] = $existingSwap->load('swapItem');
            return response()->json([
                'success'  => true,
                'swapItem' => $savedSwapItems,
                'message'  => 'Swap foods updated successfully!',
            ]);
        }
        if ($request->type == "add-swap-food") {

            $existingSwap = UserItemSwap::with('swapItem')
                ->where('item_id', $request->item_id)
                ->where('meal_id', $request->meal_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($existingSwap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Swap food already exists.',
                ]);
            }

            $swapItem = UserItemSwap::create([
                'item_id'           => $request->item_id,
                'swap_item_id'      => $request->swap_item_id,
                'user_id'           => $request->user_id,
                'meal_id'           => $request->meal_id,
                'qty'               => $qty,
                'carbs'             => $request->swap_food_carbs,
                'protein'           => $request->swap_food_protein,
                'fat'               => $request->swap_food_fat,
                'energy'            => $request->swap_food_energy,
                'unit'              => $unit,
                'selected_qty_unit' => $request->swap_selected_qty_unit,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $savedSwapItems[] = $swapItem->load('swapItem');

            return response()->json([
                'success'  => true,
                'swapItem' => $savedSwapItems,
                'message'  => 'Swap foods updated successfully!',
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
                    'message' => 'Food deleted successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching food items found to delete.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function addFood(Request $request)
    {
        // Validate the request
        if ($request->type == 'add-more-food') {
            $item = Item::with('swapItems')->find($request->item_id);
            if (! $item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }

            $userItemMeal = UserItemMeal::where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->where('item_id', $request->item_id)
                ->first();

            if ($userItemMeal) {
                return response()->json(['success' => false, 'message' => 'Food already added.']);
            } else {
                $userItemMeal                    = new UserItemMeal();
                $userItemMeal->user_id           = $request->user_id;
                $userItemMeal->meal_id           = $request->meal_id;
                $userItemMeal->is_swiped         = 0;
                $userItemMeal->item_id           = $request->item_id;
                $userItemMeal->qty               = $request->qty;
                $userItemMeal->unit              = $request->unit;
                $userItemMeal->carbs             = $request->carbs;
                $userItemMeal->fat               = $request->fat;
                $userItemMeal->protein           = $request->protein;
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

                    if (! $exists) {
                        $swap = UserItemSwap::create([
                            'user_id'           => $request->user_id,
                            'item_id'           => $item->id,
                            'meal_id'           => $request->meal_id,
                            'swap_item_id'      => $swapItem->id,
                            'qty'               => $swapItem->qty,
                            'unit'              => $swapItem->unit,
                            'carbs'             => $swapItem->carbs,
                            'fat'               => $swapItem->fat,
                            'protein'           => $swapItem->protein,
                            'selected_qty_unit' => $swapItem->selected_qty_unit, // ✅
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ]);
                    }
                }
                return response()->json([
                    'success' => true,
                    'item'    => $item,
                    'message' => 'Food added successfully.',
                ]);
            }
        } else if ($request->type == 'meal-food-add') {

            $item = Item::with('swapItems')->find($request->item_id);
            if (! $item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }

            $mealIds = $request->meal_ids;
            foreach ($mealIds as $mealId) {
                $userItemMeal = UserItemMeal::where('user_id', $request->user_id)
                    ->where('meal_id', $mealId)
                    ->where('item_id', $request->item_id)
                    ->first();

                if ($userItemMeal) {
                    return response()->json(['success' => false, 'message' => 'Food already added.']);
                } else {
                    $userItemMeal                    = new UserItemMeal();
                    $userItemMeal->user_id           = $request->user_id;
                    $userItemMeal->meal_id           = $mealId;
                    $userItemMeal->is_swiped         = 0;
                    $userItemMeal->item_id           = $request->item_id;
                    $userItemMeal->qty               = $request->qty;
                    $userItemMeal->unit              = $request->unit;
                    $userItemMeal->carbs             = $request->carbs;
                    $userItemMeal->fat               = $request->fat;
                    $userItemMeal->protein           = $request->protein;
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

                        if (! $exists) {
                            $swap = UserItemSwap::create([
                                'user_id'           => $request->user_id,
                                'item_id'           => $item->id,
                                'meal_id'           => $mealId,
                                'swap_item_id'      => $swapItem->id,
                                'qty'               => $swapItem->qty,
                                'unit'              => $swapItem->unit,
                                'carbs'             => $swapItem->carbs,
                                'fat'               => $swapItem->fat,
                                'protein'           => $swapItem->protein,
                                'selected_qty_unit' => $swapItem->selected_qty_unit,
                                'created_at'        => now(),
                                'updated_at'        => now(),
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'item'    => $item,
                'message' => 'Food added successfully.',
            ]);

        } else if ($request->type == 'woolworths') {
            $validated = $request->validate([
                'name'             => 'required|string|max:255',
                'image'            => 'nullable|url',
                'protein'          => 'nullable',
                'fat'              => 'nullable',
                'carbs'            => 'nullable',
                'category'         => 'nullable',
                'meal_id'          => 'required|exists:meals,id',
                'user_id'          => 'required|exists:users,id',
                'serving_per_pack' => 'nullable',
                'serving_size'     => 'nullable',
            ]);

            try {
                // Step 1: Download the image from the URL
                $imageContent = file_get_contents($validated['image']);
                if ($imageContent === false) {
                    return response()->json(['success' => false, 'message' => 'Unable to download the image.']);
                }

                                                       // Step 2: Generate a unique filename and save to storage
                $imageName = Str::random(32) . '.jpg'; // Generate a random 32-character string and append '.jpg'
                                                       // Full path to store the image in public storage
                $imagePath = 'items/' . $imageName;    // Define the folder and filename

                // Save the image to storage
                Storage::disk('public')->put($imagePath, $imageContent);

                $protein            = $validated['protein'] ? rtrim($validated['protein'], 'g') : 0;
                $carbs              = $validated['carbs'] ? rtrim($validated['carbs'], 'g') : 0;
                $fat                = $validated['fat'] ? rtrim($validated['fat'], 'g') : 0;
                $serving_size_parse = isset($validated['serving_size']) ? $this->parseServingSize($validated['serving_size']) : 0;
                $serving_size       = $serving_size_parse != 0 ? $serving_size_parse['serving_size'] : '0';
                $serving_size_unit  = $serving_size_parse != 0 ? $serving_size_parse['serving_size_unit'] : 'g';

                if (isDecimal($protein)) {
                    $protein = floatval($protein);
                } else {
                    $protein = formatDecimal($protein);
                }

                if (isDecimal($carbs)) {
                    $carbs = floatval($carbs);
                } else {
                    $carbs = formatDecimal($carbs);
                }

                if (isDecimal($fat)) {
                    $fat = floatval($fat);
                } else {
                    $fat = formatDecimal($fat);
                }

                $serving_size = isDecimal($serving_size) ? floatval($serving_size) : formatDecimal($serving_size);

                $keywords = explode(" ", strtolower($validated['category']));

                // Search for any matching keyword in the database
                $foodCategory = FoodCategory::where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhereRaw("LOWER(name) LIKE ?", ["%$keyword%"]);
                    }
                })->first();

                if (! $foodCategory) {

                    $foodCategory       = new FoodCategory();
                    $foodCategory->name = ucwords($validated['category']);
                    $foodCategory->save();
                }

                                                       // Step 3: Save food details in the database
                $food                    = new Item(); // Assuming you have a Food model
                $food->title             = $validated['name'];
                $food->protein           = cleanDecimal($protein);
                $food->carbs             = cleanDecimal($carbs);
                $food->fat               = cleanDecimal($fat);
                $food->qty               = cleanDecimal($serving_size);
                $food->unit              = $serving_size_unit;
                $food->serving_per_pack  = $validated['serving_per_pack'];
                $food->serving_size      = $serving_size;
                $food->serving_size_unit = $serving_size_unit;
                $food->image             = 'items/' . $imageName; // Path to the stored image
                $food->is_swiped         = 0;
                $food->category_id       = isset($foodCategory) ? $foodCategory->id : null;

                if ($food->save()) {
                    $userItemMeal            = new UserItemMeal();
                    $userItemMeal->user_id   = $request->user_id;
                    $userItemMeal->meal_id   = $request->meal_id;
                    $userItemMeal->protein   = cleanDecimal($protein);
                    $userItemMeal->carbs     = cleanDecimal($carbs);
                    $userItemMeal->fat       = cleanDecimal($fat);
                    $userItemMeal->qty       = cleanDecimal($serving_size);
                    $userItemMeal->unit      = $serving_size_unit;
                    $userItemMeal->is_swiped = 0;
                    $userItemMeal->item_id   = $food->id;
                    $userItemMeal->save();
                }

                // Step 4: Redirect with success message
                return response()->json(['success' => true, 'data' => $food, 'message' => 'Food added successfully.']);
            } catch (\Exception $e) {
                Log::error('Error adding food: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to add food ']);
            }
        } else {

            $data = $request->validate([
                'item_id' => 'required|exists:items,id',
                'user_id' => 'required|exists:users,id',
                'meal_id' => 'required|exists:meals,id',
            ]);

            $item = Item::with('swapItems')->find($data['item_id']);

            if (! $item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }

            $userItemMeal = UserItemMeal::where('user_id', $data['user_id'])
                ->where('meal_id', $data['meal_id'])
                ->where('item_id', $data['item_id'])
                ->first();

            if ($userItemMeal) {
                return response()->json(['success' => false, 'message' => 'Food already added.']);
            } else {
                $userItemMeal            = new UserItemMeal();
                $userItemMeal->user_id   = $data['user_id'];
                $userItemMeal->meal_id   = $data['meal_id'];
                $userItemMeal->is_swiped = $item->is_swiped;
                $userItemMeal->item_id   = $data['item_id'];
                $userItemMeal->qty       = $item->qty;
                $userItemMeal->unit      = $item->unit;
                $userItemMeal->save();

                $swapItems = $item->swapItems;
                foreach ($swapItems as $swapItem) {

                    $exists = DB::table('user_item_swaps')
                        ->where('user_id', $request->user_id)
                        ->where('item_id', $item->id)
                        ->where('swap_item_id', $swapItem->id)
                        ->exists();

                    if (! $exists) {
                        $deleteSwapFood = UserItemSwap::with('swapItem')
                            ->where('item_id', $item->id)
                            ->where('user_id', $request->user_id)
                            ->delete();

                        DB::table('user_item_swaps')->insert([
                            'user_id'      => $request->user_id,
                            'item_id'      => $item->id,
                            'swap_item_id' => $swapItem->id,
                            'qty'          => $swapItem->qty,
                            'unit'         => $swapItem->unit,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }
                }

                // Prepare simplified swapItems array
                $simplifiedSwapItems = $swapItems->map(function ($swapItem) {
                    return [
                        'id'      => $swapItem->id,    // Swap item ID
                        'name'    => $swapItem->title, // Swap item name (assuming 'title' holds the name)
                        'qty'     => $swapItem->qty,
                        'unit'    => $swapItem->unit,
                        'carbs'   => $swapItem->carbs,
                        'protein' => $swapItem->protein,
                        'fat'     => $swapItem->fat,
                    ];
                });

                // Include simplified swapItems in response
                return response()->json([
                    'success' => true,
                    'data'    => [
                        'id'        => $item->id,
                        'title'     => $item->title,
                        'qty'       => $item->qty,
                        'unit'      => $item->unit,
                        'carbs'     => $item->carbs,
                        'protein'   => $item->protein,
                        'fat'       => $item->fat,
                        'swapItems' => $simplifiedSwapItems, // Pass simplified swap items here
                    ],
                    'message' => 'Food added successfully.',
                ]);
            }
        }
    }

    private function parseServingSize($input)
    {
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
            'serving_size'      => $value,
            'serving_size_unit' => $unit,
        ];
    }

    public function getSwapFoods(Request $request)
    {
        $foodId = $request->food_id;
        $food   = Item::with('swapItems')->where('id', $foodId)->first();

        if (! $food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found.',
            ]);
        }

        $swapFoodIds = $request->swap_food_ids;

        // Get swap foods from the database
        $swapFoods = $food->swapItems->map(function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->title, // Assuming 'name' is the column for food name
                'qty'  => $item->qty,
            ];
        })->toArray();

        // If request contains additional swap food IDs, merge them
        if ($swapFoodIds) {
            if ($request->has('swap_food_ids') && is_array($request->swap_food_ids)) {

                foreach ($swapFoodIds as $swapId) {
                    // Ensure the food is not already in the swap list
                    if (! in_array($swapId, array_column($swapFoods, 'id'))) {
                        $item = \App\Models\Item::find($swapId);
                        if ($item) {
                            $swapFoods[] = [
                                'id'   => $item->id,
                                'name' => $item->title,
                                'qty'  => $item->qty,
                            ];
                        }
                    }
                }
            } else {
                $swapId = $request->swap_food_ids;
                $item   = \App\Models\Item::find($swapId);
                if ($item) {
                    $swapFoods[] = [
                        'id'   => $item->id,
                        'name' => $item->title,
                        'qty'  => $item->qty,
                    ];
                }
            }
        }
        return response()->json([
            'success'   => true,
            'swapFoods' => $swapFoods,
        ]);
    }

    public function saveSwapFood(Request $request)
    {
        $foodId      = $request->food_id;
        $swapFoodIds = $request->swap_foods;
        $mealIds     = $request->meal_ids;
        $foodQty     = $request->food_qty ?? null;
        $swapFoodQty = $request->swap_food_qty ?? null;

        // Initialize response data
        $savedItem      = null;
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
                    'qty'        => $foodQty,
                    'unit'       => $request->food_unit,
                    'carbs'      => $request->carbs,
                    'protein'    => $request->protein,
                    'fat'        => $request->fat,
                    'updated_at' => now(),
                ]);
                $savedItem = $existingMeal->load('items');
            } else {
                $savedItem = UserItemMeal::create([
                    'item_id'    => $foodId,
                    'meal_id'    => $mealId,
                    'user_id'    => $request->user_id,
                    'qty'        => $foodQty,
                    'unit'       => $request->food_unit,
                    'carbs'      => $request->carbs,
                    'protein'    => $request->protein,
                    'fat'        => $request->fat,
                    'created_at' => now(),
                    'is_swiped'  => 0,
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
                    if (! isset($swapFood['id'])) {
                        continue;
                    }

                    $existingSwap = UserItemSwap::with('swapItem')
                        ->where('item_id', $foodId)
                        ->where('swap_item_id', $swapFood['id'])
                        ->first();

                    if ($existingSwap) {
                        // ✅ Update swap item quantity if record exists
                        $existingSwap->update([
                            'qty'        => $swapFoodQty,
                            'carbs'      => $request->swap_food_carbs,
                            'protein'    => $request->swap_food_protein,
                            'fat'        => $request->swap_food_fat,
                            'unit'       => $request->swap_food_unit,
                            'updated_at' => now(),

                        ]);
                        $savedSwapItems[] = $existingSwap->load('swapItem');
                    } else {
                        $deleteSwapFood = UserItemSwap::with('swapItem')
                            ->where('item_id', $foodId)
                            ->where('user_id', $request->user_id)
                            ->delete();

                        $swapItem = UserItemSwap::create([
                            'item_id'      => $foodId,
                            'swap_item_id' => $swapFood['id'],
                            'user_id'      => $request->user_id,
                            'qty'          => $swapFoodQty,
                            'carbs'        => $request->swap_food_carbs,
                            'protein'      => $request->swap_food_protein,
                            'fat'          => $request->swap_food_fat,
                            'unit'         => $request->swap_food_unit,
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
                        'qty'        => $swapFoodQty,
                        'carbs'      => $request->swap_food_carbs,
                        'protein'    => $request->swap_food_protein,
                        'fat'        => $request->swap_food_fat,
                        'unit'       => $request->swap_food_unit,
                        'updated_at' => now(),
                    ]);
                    $savedSwapItems[] = $existingSwap->load('swapItem');
                } else {
                    $deleteSwapFood = UserItemSwap::with('swapItem')
                        ->where('item_id', $foodId)
                        ->where('user_id', $request->user_id)
                        ->delete();

                    $swapItem = UserItemSwap::create([
                        'item_id'      => $foodId,
                        'swap_item_id' => $swapFoodIds,
                        'user_id'      => $request->user_id,
                        'qty'          => $swapFoodQty,
                        'carbs'        => $request->swap_food_carbs,
                        'protein'      => $request->swap_food_protein,
                        'fat'          => $request->swap_food_fat,
                        'unit'         => $request->swap_food_unit,
                        'created_at'   => now(),
                    ]);

                    // Load related `swapItem` after saving
                    $swapItem->load('swapItem');
                    $savedSwapItems[] = $swapItem;
                }
            }
        } else {
            $item           = Item::with('swapItems')->find($foodId);
            $savedSwapItems = $item->swapItems;
        }

        return response()->json([
            'success'   => true,
            'item'      => $savedItem,
            'swapItems' => $savedSwapItems,
        ]);
    }

    public function handlePlanAction(Request $request)
    {
        $action = $request->action;

        // Validate required inputs
        if (! $request->user_id || ! $request->payment_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID or Payment ID is missing.',
            ], 400);
        }

        // Fetch payment
        $payment = Payment::find($request->payment_id);
        if (! $payment) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Payment not found.',
            ], 404);
        }

        $email    = $payment->user->email;
        $planName = Plan::where('id', $payment->plan_id)->value('name');

        if ($action === 'view') {
            // Get the user details
            $user    = User::findOrFail($request->user_id);
            $planIds = DB::table('payments')
                ->where('email', $user->email)
                ->where(function ($query) {
                    $query->where('status', 'succeeded')
                        ->orWhere('status', 'discount_applied');
                })
                ->pluck('plan_id')
                ->toArray();

            $redirectUrl = route('front.profile', ['id' => $user->id]);

            Auth::login($user); // Login user (no password check)

            return response()->json([
                'status'       => 'success',
                'redirect_url' => $redirectUrl,
            ]);
        }

        if ($action === 'send') {
            // Send meal plan to user
            $user     = $payment->user;
            $userPlan = UserPlan::where('plan_id', $payment->plan_id)->where('user_id', $user->id)->first();
            if (! $userPlan) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User plan not found.',
                ], 404);
            }

            try {
                Mail::to($email)->send(new ActivePlanMail($user, $planName));

                $userPlan->update(['is_mail_sent' => 1,
                    'mail_sent_at'                    => now(),
                ]);
                $userPlan->save();

                $click = ActivityTracker::click('plan_create_mail_send', $user->id);

                // Log in trackings with click reference
                ActivityTracker::log(TrackingType::PLAN_EMAILED, $user->id, [
                    'user_click_id'      => $click->id,
                    'section_element_id' => $click->section_element_id,
                    'user_plan_id'       => $userPlan->id,
                    'plan_id'            => $payment->plan_id,
                ]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Meal plan mail sent successfully!',
                ]);
            } catch (\Exception $e) {
                Log::error('Error sending meal plan mail: ' . $e->getMessage());
                return response()->json(['error' => 'Error sending meal plan mail'], 500);
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Invalid action.',
        ], 400);
    }

    public function removeUserMeal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'meal_id' => 'required|integer',
            'plan_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $userPlanId = UserPlan::where('user_id', $request->user_id)
                ->where('plan_id', $request->plan_id)
                ->value('id');
            if (! $userPlanId) {
                $validItemIds = ItemMeal::where('meal_id', $request->meal_id)
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
                if ($meal) {
                    $items = UserItem::where('user_plan_id', $userPlanId)
                        ->where('user_meal_id', $meal->id)
                        ->get();
                    $validItemIds = ItemMeal::where('meal_id', $meal->id)
                        ->pluck('item_id')
                        ->toArray();
                    foreach ($items as $item) {
                        if (! in_array($item->id, $validItemIds)) {
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
                            $validSwapItemIds = DB::table('item_swaps')
                                ->where('item_id', $item->id)
                                ->pluck('swap_item_id')
                                ->toArray();
                            $userSwapItems = UserItemSwap::where('user_id', $request->user_id)
                                ->where('meal_id', $request->meal_id)
                                ->where('item_id', $item->id)
                                ->get();

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

                            }

                            $userItemMeals = DB::table('user_item_meals')
                                ->where('user_id', $request->user_id)
                                ->where('meal_id', $meal->id)
                                ->where('item_id', $item->id)
                                ->delete();
                        }
                    }

                    UserMeal::where('id', $meal->id)
                        ->where('user_plan_id', $userPlanId)
                        ->delete();
                } else {
                    Log::info("Meal not found in user_meals, cleaning leftovers");

                    $validItemIds = ItemMeal::where('meal_id', $request->meal_id)
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
                'message' => 'Invalid items and meal removed successfully.',
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
                'message' => 'Failed to remove meal. Please try again.',
            ]);
        }
    }

    public function updateNutritionFalg(Request $request)
    {
        $request->validate([
            'payment_id'          => 'required|integer',
            'nutrition_info_flag' => 'required|boolean',
        ]);

        $payment = Payment::where('id', $request->payment_id)
            ->first();

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'User plan not found.'], 404);
        }

        $userPlan = UserPlan::where('user_id', $payment->user_id)
            ->where('plan_id', $payment->plan_id)
            ->first();
        if (! $userPlan) {
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
            'user_id'      => 'required|integer',
            'item_id'      => 'required|integer',
            'swap_item_id' => 'required|integer',
            'meal_id'      => 'required|integer',
            'ratio'        => 'required|numeric',
        ]);

        try {
            $userItemSwap = UserItemSwap::where('user_id', $request->user_id)
                ->where('item_id', $request->item_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->where('meal_id', $request->meal_id)
                ->first();

            if (! $userItemSwap) {
                // return response()->json(['success' => false, 'message' => 'Swap item not found.'], 404);
                $item = Item::find($request->swap_item_id);
                if (! $item) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $userItemSwap               = new UserItemSwap();
                $userItemSwap->user_id      = $request->user_id;
                $userItemSwap->item_id      = $request->item_id;
                $userItemSwap->swap_item_id = $request->swap_item_id;
                $userItemSwap->meal_id      = $request->meal_id;

                $selectedQty        = $item->selected_qty_unit; // Assuming this is an array of selected quantities
                $ratio              = $request->ratio;          // The ratio to adjust the quantities
                $updatedSelectedQty = [];
                foreach ($selectedQty as $unitData) {
                    $originalQty = floatval($unitData['qty']);
                    $adjustedQty = $originalQty * floatval($ratio);

                                                           // Optionally round or format:
                    $adjustedQty = round($adjustedQty, 2); // keep 2 decimal places

                    $updatedSelectedQty[] = [
                        'qty'     => (string) $adjustedQty,
                        'unit'    => $unitData['unit'],
                        'checked' => $unitData['checked'] ?? false, // Preserve the checked state if it exists
                    ];
                }

                // Update the swap item
                $userItemSwap->qty               = $updatedSelectedQty[0]['qty'];
                $userItemSwap->unit              = $updatedSelectedQty[0]['unit'];
                $userItemSwap->selected_qty_unit = $updatedSelectedQty;
                $userItemSwap->carbs             = $request->food_carbs ?? 0;
                $userItemSwap->protein           = $request->food_protein ?? 0;
                $userItemSwap->fat               = $request->food_fat ?? 0;
                $userItemSwap->energy            = $request->food_energy ?? 0;
                $userItemSwap->save();
            } else {

                $selectedQty        = $userItemSwap->selected_qty_unit; // Assuming this is an array of selected quantities
                $ratio              = $request->ratio;                  // The ratio to adjust the quantities
                $updatedSelectedQty = [];
                foreach ($selectedQty as $unitData) {
                    $originalQty = floatval($unitData['qty']);
                    $adjustedQty = $originalQty * floatval($ratio);

                                                           // Optionally round or format:
                    $adjustedQty = round($adjustedQty, 2); // keep 2 decimal places

                    $updatedSelectedQty[] = [
                        'qty'     => (string) $adjustedQty,
                        'unit'    => $unitData['unit'],
                        'checked' => $unitData['checked'] ?? false, // Preserve the checked state if it exists
                    ];
                }

                // Update the swap item
                $userItemSwap->qty               = $updatedSelectedQty[0]['qty'];
                $userItemSwap->unit              = $updatedSelectedQty[0]['unit'];
                $userItemSwap->selected_qty_unit = $updatedSelectedQty;
                $userItemSwap->carbs             = $request->food_carbs ?? 0;
                $userItemSwap->protein           = $request->food_protein ?? 0;
                $userItemSwap->fat               = $request->food_fat ?? 0;
                $userItemSwap->energy            = $request->food_energy ?? 0;
                $userItemSwap->save();
            }

            return response()->json(['success' => true, 'message' => 'Swap item updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating swap item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update swap item.'], 500);
        }
    }

    public function deleteUserMealFood(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'meal_id' => 'required|integer',
            'item_id' => 'required|integer',
        ]);

        try {
            // Find the UserItemMeal entry
            $userItemMeal = UserItemMeal::where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->where('item_id', $request->item_id)
                ->first();

            if (! $userItemMeal) {
                return response()->json(['success' => false, 'message' => 'Food item not found in user meal.']);
            }

            // Delete the UserItemMeal entry
            $userItemMeal->delete();

            $swapItems = UserItemSwap::with('swapItem')
                ->where('item_id', $request->item_id)
                ->where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->get();

            if ($swapItems->isNotEmpty()) {
                foreach ($swapItems as $swapItem) {
                    // Delete the UserItemSwap entries associated with this item
                    UserItemSwap::where('user_id', $request->user_id)
                        ->where('item_id', $swapItem->item_id)
                        ->where('swap_item_id', $swapItem->swap_item_id)
                        ->where('meal_id', $request->meal_id)
                        ->delete();
                }
            }

            return response()->json(['success' => true, 'message' => 'Food item deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting food item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete food item.'], 500);
        }
    }

    public function deleteUserMealSwapFood(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|integer',
            'meal_id'      => 'required|integer',
            'swap_item_id' => 'required|integer',
        ]);

        try {
            // Find the UserItemSwap entry
            $userItemSwap = UserItemSwap::where('user_id', $request->user_id)
                ->where('meal_id', $request->meal_id)
                ->where('swap_item_id', $request->swap_item_id)
                ->first();

            if (! $userItemSwap) {
                return response()->json(['success' => false, 'message' => 'Swap food item not found in user meal.']);
            }

            // Delete the UserItemSwap entry
            $userItemSwap->delete();

            return response()->json(['success' => true, 'message' => 'Swap food item deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting swap food item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete swap food item.'], 500);
        }
    }
}
