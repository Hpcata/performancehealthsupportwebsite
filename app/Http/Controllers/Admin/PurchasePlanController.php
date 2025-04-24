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
            
            $mealItems = \App\Models\ItemMeal::with('item') // Load the related Item data
            ->get();
        
            foreach ($mealItems as $mealItem) {
                $existingMealItem = \DB::table('user_item_meals')
                    ->where('item_id', $mealItem->item_id)
                    ->where('meal_id', $mealItem->meal_id)
                    ->where('user_id', $payment->user_id)
                    ->exists();
            
                if (!$existingMealItem) {
                    \DB::table('user_item_meals')->insert([
                        'item_id' => $mealItem->item_id,
                        'meal_id' => $mealItem->meal_id,
                        'user_id' => $payment->user_id,
                        'qty'     => $mealItem->item_qty,
                        'is_swiped' => $mealItem->item->is_swiped ?? 0, // Assuming 'is_swiped' exists
                    ]);
                }
            }
            
            $swapItems = \DB::table('item_swaps')->get();
            foreach ($swapItems as $swapItem) {
                $existingSwapItem = \DB::table('user_item_swaps')
                    ->where('item_id', $swapItem->item_id)
                    ->where('swap_item_id', $swapItem->swap_item_id)
                    ->where('user_id', $payment->user_id)
                    ->exists();
            
                if (!$existingSwapItem) {
                    \DB::table('user_item_swaps')->insert([
                        'item_id' => $swapItem->item_id,
                        'swap_item_id' => $swapItem->swap_item_id,
                        'user_id' => $payment->user_id
                    ]);
                }
            }
            
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
                            // dd($mealIds);
                            // Fetch categories for meals
                            $categoriesByMeal = \DB::table('meal_category')
                                    ->whereIn('meal_id', $mealIds)
                                    ->pluck('category_id') // Get a collection of category IDs
                                    ->unique() // Remove duplicate values
                                    ->toArray();
                            foreach ($categoriesByMeal as $categoryId) {
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
            
            //  dd('123');
            
            if (isset($request->plan_id) && is_array($request->plan_id)) {
                foreach ($request->plan_id as $planId) {
                    $userPlan = \DB::table('user_plans')->insertGetId([
                        'user_id' => $payment->user_id,
                        'plan_id' => $planId,
                        'status' => 'active',
                        'modified_by' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
    
                    if (isset($request->meal_times[$planId])) {
                        $mealTimeIds = $request->meal_times[$planId];
    
                        foreach ($mealTimeIds as $mealTimeId) {
                            // Insert into user_meal_times
                            $userMealTimeId = \DB::table('user_meal_times')->insertGetId([
                                'user_plan_id' => $userPlan,
                                'meal_time_id' => $mealTimeId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            if (isset($categories[$planId][$mealTimeId])) {
                                $categoryIds = $categories[$planId][$mealTimeId];
                                foreach ($categoryIds as $categoryId) {
                                    $userCategoryId = \DB::table('user_categories')->insertGetId([
                                        'user_plan_id' => $userPlan,
                                        'meal_time_id' => $userMealTimeId,
                                        'category_id' => $categoryId,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);

                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        $mealIds = $meals[$planId][$mealTimeId][$categoryId];
    
                                        foreach ($mealIds as $mealId) {
                                            // Insert meal into user_meals
                                            $userMealId = \DB::table('user_meals')->insertGetId([
                                                'user_plan_id' => $userPlan,
                                                'user_meal_time_id' => $userMealTimeId,
                                                'user_category_id' => $userCategoryId,
                                                'user_subcategory_id' => null,
                                                'meal_id' => $mealId,
                                                'created_at' => now(),
                                                'updated_at' => now(),
                                            ]);

                                            if (isset($items[$planId][$mealTimeId][$categoryId][$mealId])) {
                                                $itemIds = $items[$planId][$mealTimeId][$categoryId][$mealId];
    
                                                foreach ($itemIds as $itemId) {
                                                    $userItemId = \DB::table('user_items')->insertGetId([
                                                        'user_plan_id' => $userPlan,
                                                        'user_meal_time_id' => $userMealTimeId,
                                                        'user_category_id' => $userCategoryId,
                                                        'user_subcategory_id' => null,
                                                        'user_meal_id' => $userMealId,
                                                        'item_id' => $itemId,
                                                        'created_at' => now(),
                                                        'updated_at' => now(),
                                                    ]);

                                                    if (isset($swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId])) {
                                                        $swapItemIds = $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId];
    
                                                        foreach ($swapItemIds as $swapItemId) {
                                                            \DB::table('user_swap_items')->insert([
                                                                'user_plan_id' => $userPlan,
                                                                'user_meal_time_id' => $userMealTimeId,
                                                                'user_category_id' => $userCategoryId,
                                                                'user_subcategory_id' => null,
                                                                'user_meal_id' => $userMealId,
                                                                'user_item_id' => $userItemId,
                                                                'swap_item_id' => $swapItemId,
                                                                'created_at' => now(),
                                                                'updated_at' => now(),
                                                            ]);
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
            }
                           
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

        foreach ($userPlans as $userPlan) {
            foreach ($userPlan->userMealTimes as $mealTime) {
                $selectedMeals[$userPlan->plan_id][$mealTime->meal_time_id] = 
                    $mealTime->userMeals->pluck('id','meal_id')->toArray();

                foreach ($mealTime->userMeals as $userMeal) {
                    $mealId = $userMeal->meal_id;

                    // Store user items
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
                        foreach ($userItem->userSwapItems as $swapItem) {
                            $swap = Item::find($swapItem->swap_item_id); // Assuming Item model stores nutrition data
                            if ($swap) {
                                $totalCarbs += $swap->carbs ?? 0;
                                $totalFat += $swap->fat ?? 0;
                                $totalProtein += $swap->protein ?? 0;
                            }
                        }
                    }
                }
            }
        }

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

    
    // public function edit(User $user, $planId)
    // {
    //     // Fetch the user's plan with all necessary relationships
    //     $payment = Payment::find($planId);
    //     $plan = Plan::find($payment->plan_id);
    //     // dd($plan);
    //     $subPlanIds = $plan->subPlans->pluck('id')->toArray();
    //     // dd($subPlanIds);
    //     $userPlans = UserPlan::with([
    //         'plan', 
    //         'userMealTimes.userCategories.userMeals.userItems.userSwapItems', // Correctly load subcategories through pivot table
    //     ])
    //     ->where('user_id', $user->id)
    //     ->where('plan_id', $plan->id)
    //     ->when($subPlanIds, function ($query) use ($subPlanIds) {
    //         return $query->orWhereIn('plan_id', $subPlanIds);
    //     })
    //     ->get();
        
    //     // dd($userPlans);
    //     // Redirect if the plan doesn't exist
    //     if (!$userPlans) {
    //         return redirect()->route('admin.purchase-plans.index')
    //                          ->with('error', 'User Plan not found.');
    //     }

    //     $selectedMeals = [];
    //     $selectedItems = []; // To store pre-selected user items
    //     $selectedSwapItems = []; // To store pre-selected swap items

    //     foreach ($userPlans as $userPlan) {
    //         foreach ($userPlan->userMealTimes as $mealTime) {
    //             $selectedMeals[$userPlan->plan_id][$mealTime->meal_time_id] = $mealTime->userMeals->pluck('id','meal_id')->toArray();

    //             foreach ($mealTime->userMeals as $userMeal) {
    //                 $mealId = $userMeal->meal_id;

    //                 // Store user items
    //                 $selectedItems[$mealTime->meal_time_id][$mealId] = $userMeal->userItems->pluck('item_id')->toArray();

    //                 // Store user swap items
    //                 foreach ($userMeal->userItems as $userItem) {
    //                     $selectedSwapItems[$mealTime->meal_time_id][$mealId][$userItem->item_id] = $userItem->userSwapItems->pluck('swap_item_id')->toArray();
    //                 }
    //             }
    //         }
    //     }
    //     // dd($selectedSwapItems);
    //     $mealTimes = MealTime::all();
    //     $categories = Category::all();
        
    //     $meals = Meal::all();
    //     $items = Item::where('is_swiped',0)->get();
    //     // dd($selectedItems);

    //     $activity = UserPlan::with([
    //         'modifiedBy', 
    //     ])
    //     ->where('user_id', $payment->user_id)
    //     ->where('plan_id', $payment->plan_id)
    //     ->orderBy('updated_at', 'desc') // Order by updated_at in descending order
    //     ->first();

    //     $step5Foods = Item::with('category')->where('is_extra', 0)->get()->groupBy('category_id');

    //     $userPrePlan = \App\Models\UserPrePlan::with(['prePlanDetails' => function ($query) {
    //         $query->where('form_slug', 'food_preference')
    //               ->whereIn('question', ['Grains', 'Legumes, beans and pulses','Eggs','Meat','Meat Alternatives','Seafood','Dairy','Non-Dairy','Fruit','Vegetable','Oils / Butter']); // Add your condition here
    //     }])->where('payment_id', $payment->id)->first();       

    //     if(isset($userPrePlan->prePlanDetails)){
    //         $prePlanDetails = $userPrePlan->prePlanDetails;
    //         $perPlanSelectedFoods = $prePlanDetails->map(function ($detail) {
    //             return json_decode($detail->answer, true); // Decode JSON into an array
    //         })->flatten()->toArray();
    //     }else {
    //         $perPlanSelectedFoods = [];
    //     }
    //     return view('backend.pages.plan.purchase-plan-edit', compact(
    //         'userPlans', 'mealTimes', 'categories', 'meals', 'items', 'selectedMeals', 'selectedItems', 'selectedSwapItems', 'activity', 'payment', 'step5Foods', 'perPlanSelectedFoods'
    //     ));
    // }   
    
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
            // dd($request->all());
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
                    $mealTimeIds = $request->meal_times[$planId];

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
                            foreach ($categoriesByMeal as $categoryId) {
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
                    // dd($userPlan);
                    // Delete related records in dependent tables
                    \DB::table('user_meal_times')->where('user_plan_id', $userPlan)->delete();
                    \DB::table('user_categories')->where('user_plan_id', $userPlan)->delete();
                    \DB::table('user_meals')->where('user_plan_id', $userPlan)->delete();
                    \DB::table('user_items')->where('user_plan_id', $userPlan)->delete();
                    \DB::table('user_swap_items')->where('user_plan_id', $userPlan)->delete();

                    if (isset($request->meal_times[$planId])) {
                        $mealTimeIds = $request->meal_times[$planId];
    
                        foreach ($mealTimeIds as $mealTimeId) {
                            // Insert into user_meal_times
                            $userMealTimeId = \DB::table('user_meal_times')->insertGetId([
                                'user_plan_id' => $userPlan,
                                'meal_time_id' => $mealTimeId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            // dd($userMealTimeId);
                            if (isset($categories[$planId][$mealTimeId])) {
                                $categoryIds = $categories[$planId][$mealTimeId];
                                foreach ($categoryIds as $categoryId) {
                                    $userCategoryId = \DB::table('user_categories')->insertGetId([
                                        'user_plan_id' => $userPlan,
                                        'meal_time_id' => $userMealTimeId,
                                        'category_id' => $categoryId,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);

                                    if (isset($meals[$planId][$mealTimeId][$categoryId])) {
                                        $mealIds = $meals[$planId][$mealTimeId][$categoryId];
    
                                        foreach ($mealIds as $mealId) {
                                            // Insert meal into user_meals
                                            $userMealId = \DB::table('user_meals')->insertGetId([
                                                'user_plan_id' => $userPlan,
                                                'user_meal_time_id' => $userMealTimeId,
                                                'user_category_id' => $userCategoryId,
                                                'user_subcategory_id' => null,
                                                'meal_id' => $mealId,
                                                'created_at' => now(),
                                                'updated_at' => now(),
                                            ]);

                                            if (isset($items[$planId][$mealTimeId][$categoryId][$mealId])) {
                                                $itemIds = $items[$planId][$mealTimeId][$categoryId][$mealId];
    
                                                foreach ($itemIds as $itemId) {
                                                    $userItemId = \DB::table('user_items')->insertGetId([
                                                        'user_plan_id' => $userPlan,
                                                        'user_meal_time_id' => $userMealTimeId,
                                                        'user_category_id' => $userCategoryId,
                                                        'user_subcategory_id' => null,
                                                        'user_meal_id' => $userMealId,
                                                        'item_id' => $itemId,
                                                        'created_at' => now(),
                                                        'updated_at' => now(),
                                                    ]);

                                                    if (isset($swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId])) {
                                                        $swapItemIds = $swapItems[$planId][$mealTimeId][$categoryId][$mealId][$itemId];
    
                                                        foreach ($swapItemIds as $swapItemId) {
                                                            \DB::table('user_swap_items')->insert([
                                                                'user_plan_id' => $userPlan,
                                                                'user_meal_time_id' => $userMealTimeId,
                                                                'user_category_id' => $userCategoryId,
                                                                'user_subcategory_id' => null,
                                                                'user_meal_id' => $userMealId,
                                                                'user_item_id' => $userItemId,
                                                                'swap_item_id' => $swapItemId,
                                                                'created_at' => now(),
                                                                'updated_at' => now(),
                                                            ]);
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

                $data = $meal->userMealItems->map(function ($item) use ($userId, &$totalCarbs, &$totalProtein, &$totalFat) {
                    $userSwapItem = \DB::table('user_item_swaps')
                        ->where('user_id', $userId)
                        ->where('item_id', $item->id)
                        ->first();

                    $swapItems = $item->userItemSwaps->map(function ($swapItem) use ($userSwapItem, &$totalCarbs, &$totalProtein, &$totalFat) {
                        $totalCarbs += $swapItem->carbs;
                        $totalProtein += $swapItem->protein;
                        $totalFat += $swapItem->fat;

                        return [
                            'id' => $swapItem->id,
                            'name' => $swapItem->title,
                            'qty' => $swapItem->qty ?? 0,
                            'carbs' => $swapItem->carbs,
                            'protein' => $swapItem->protein,
                            'fat' => $swapItem->fat,
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
                        'carbs' => $item->carbs,
                        'protein' => $item->protein,
                        'fat' => $item->fat,
                        'swapItems' => $swapItems
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

                $data = $userMeal->map(function ($item) use($userId, &$totalCarbs, &$totalProtein, &$totalFat) {
                    $swapItems = \App\Models\UserItemSwap::with('item')
                        ->where('item_id', $item->item_id)
                        ->where('user_id', $userId)
                        ->get();
                    // dd($item);
                    $totalCarbs += isset($item->carbs) ? $item->carbs : $item->items->carbs;
                    $totalProtein += isset($item->protein) ? $item->protein : $item->items->protein;
                    $totalFat += isset($item->fat) ? $item->fat : $item->items->fat;

                    return [
                        'id' => $item->items->id,
                        'name' => $item->items->title,
                        'qty' => $item->qty ?? 0,
                        'unit' => $item->unit ?? '',
                        'carbs' => isset($item->carbs) ? $item->carbs : $item->items->carbs,
                        'protein' =>  isset($item->protein) ? $item->protein : $item->items->protein,
                        'fat' => isset($item->fat) ? $item->fat : $item->items->fat,
                        'swapItems' => $swapItems->map(function ($swapFood) use (&$totalCarbs, &$totalProtein, &$totalFat) {
                            $totalCarbs += isset($swapFood->carbs) ? $swapFood->carbs : $swapFood->swapItem->carbs;
                            $totalProtein += isset($swapFood->protein) ? $swapFood->protein : $swapFood->swapItem->protein;
                            $totalFat += isset($swapFood->fat) ? $swapFood->fat : $swapFood->swapItem->fat;

                            return [
                                'id' => $swapFood->swapItem->id,
                                'name' => $swapFood->swapItem->title,
                                'qty' => $swapFood->qty,
                                'unit' => $swapFood->unit ?? '',
                                'carbs' => isset($swapFood->carbs) ? $swapFood->carbs : $swapFood->swapItem->carbs,
                                'protein' => isset($swapFood->protein) ? $swapFood->protein : $swapFood->swapItem->protein,
                                'fat' => isset($swapFood->fat) ? $swapFood->fat : $swapFood->swapItem->fat,
                            ];
                        })
                    ];
                });
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

            $data = $meal->items->map(function ($item) use (&$totalCarbs, &$totalProtein, &$totalFat) {
                $totalCarbs += $item->carbs;
                $totalProtein += $item->protein;
                $totalFat += $item->fat;

                $swapItems = $item->swapItems->map(function ($swapItem) use (&$totalCarbs, &$totalProtein, &$totalFat) {
                    $totalCarbs += $swapItem->carbs;
                    $totalProtein += $swapItem->protein;
                    $totalFat += $swapItem->fat;

                    return [
                        'id' => $swapItem->id,
                        'name' => $swapItem->title,
                        'qty' => $swapItem->qty ?? 0,
                        'carbs' => $swapItem->carbs,
                        'protein' => $swapItem->protein,
                        'fat' => $swapItem->fat,
                    ];
                });

                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'qty'  => $item->pivot->item_qty ?? 0,
                    'carbs' => $item->carbs,
                    'protein' => $item->protein,
                    'fat' => $item->fat,
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


    // public function getMealItems(Request $request)
    // {
    //     $userId = $request->user_id;

    //     if ($request->type == 'edit') {
    //         $userMeal = \App\Models\UserItemMeal::where('meal_id', $request->meal_id)
    //             ->where('user_id', $userId)
    //             ->get();

    //         if (!$userMeal) {
    //             $meal = Meal::with('items.swapItems')->find($request->meal_id);

    //             if (!$meal) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Meal not found.'
    //                 ], 404);
    //             }

    //             $mealName = $meal->title;
    //             $mealId = $meal->id;

    //             $data = $meal->userMealItems->map(function ($item) use ($userId) {
    //                 $userSwapItem = \DB::table('user_item_swaps')
    //                     ->where('user_id', $userId)
    //                     ->where('item_id', $item->id)
    //                     ->first();

    //                 return [
    //                     'id' => $item->id,
    //                     'name' => $item->title,
    //                     'qty'  => $item->pivot->qty 
    //                         ?? optional(\App\Models\ItemMeal::where('item_id', $item->id)->first())->item_qty 
    //                         ?? optional($userSwapItem)->qty 
    //                         ?? 0,
    //                     'carbs' => $item->carbs,
    //                     'protein' => $item->protein,
    //                     'fat' => $item->fat,
    //                     'swapItems' => $item->userItemSwaps->map(function ($swapItem) use ($userSwapItem) {
    //                         return [
    //                             'id' => $swapItem->id,
    //                             'name' => $swapItem->title,
    //                             'qty' => $swapItem->qty 
    //                                 ?? optional(\App\Models\ItemMeal::where('item_id', $swapItem->id)->first())->item_qty 
    //                                 ?? optional($userSwapItem)->qty 
    //                                 ?? 0,
    //                             'carbs' => $swapItem->carbs,
    //                             'protein' => $swapItem->protein,
    //                             'fat' => $swapItem->fat,
    //                         ];
    //                     })
    //                 ];
    //             });
    //         } else {
    //             // dd('23');
    //             $meal = Meal::where('id', $request->meal_id)
    //                 ->with(['userMealItems' => function ($query) use ($userId) {
    //                     $query->where('user_id', $userId)
    //                         ->with(['userItemSwaps' => function ($subQuery) use ($userId) {
    //                             $subQuery->where('user_id', $userId);
    //                         }]);
    //                 }])
    //                 ->first();

    //             $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)
    //                 ->where('plan_id', $request->plan_id)
    //                 ->first();

    //             $userMealTimes = null;
    //             $userUpdateMeal = null;
    //             if ($userPlan) {
    //                 $userMealTimes = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
    //                     ->where('meal_time_id', $request->meal_time_id)
    //                     ->first();
    //                     if($userMealTimes) {
    //                         $userUpdateMeal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTimes->id)->where('meal_id', $request->meal_id)->first();
    //                     }
    //             }

    //             $mealName = $userUpdateMeal->meal_name ?? $meal->title;
    //             $mealId = $meal->id;

    //             $data = $userMeal->map(function ($item) use($userId) {
    //                 $swapItems = \App\Models\UserItemSwap::with('item')
    //                 ->where('item_id', $item->item_id)
    //                 ->where('user_id', $userId)
    //                 ->get();
    //                 return [
    //                     'id' => $item->items->id,
    //                     'name' => $item->items->title,
    //                     'qty' => $item->qty ?? 0,
    //                     'carbs' => $item->items->carbs,
    //                     'protein' => $item->items->protein,
    //                     'fat' => $item->items->fat,
    //                     'swapItems' => $swapItems->map(function ($swapFood) {
    //                         return [
    //                             'id' => $swapFood->swapItem->id,
    //                             'name' => $swapFood->swapItem->title,
    //                             'qty' => $swapFood->qty,
    //                             'carbs' => $swapFood->swapItem->carbs,
    //                             'protein' => $swapFood->swapItem->protein,
    //                             'fat' => $swapFood->swapItem->fat,
    //                         ];
    //                     })
    //                 ];
    //             });
    //         }
    //     } else {
    //         $meal = Meal::with('items.swapItems')->find($request->meal_id);

    //         if (!$meal) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Meal not found.'
    //             ], 404);
    //         }

    //         $mealName = $meal->title;
    //         $mealId = $meal->id;

    //         $data = $meal->items->map(function ($item) {
    //             return [
    //                 'id' => $item->id,
    //                 'name' => $item->title,
    //                 'qty'  => $item->pivot->item_qty ?? 0,
    //                 'carbs' => $item->carbs,
    //                 'protein' => $item->protein,
    //                 'fat' => $item->fat,
    //                 'swapItems' => $item->swapItems->map(function ($swapItem) {
    //                     return [
    //                         'id' => $swapItem->id,
    //                         'name' => $swapItem->title,
    //                         'qty' => $swapItem->qty 
    //                             ?? optional(\App\Models\ItemMeal::where('item_id', $swapItem->id)->first())->item_qty 
    //                             ?? 0,
    //                         'carbs' => $swapItem->carbs,
    //                         'protein' => $swapItem->protein,
    //                         'fat' => $swapItem->fat,
    //                     ];
    //                 })
    //             ];
    //         });
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'meal_id' => $mealId,
    //         'meal_name' => $mealName,
    //         'data' => $data
    //     ]);
    // }


    // public function getMealItems(Request $request)
    // {
    //     // Fetch the meal with related items and their swap items
    //     // $meal = Meal::with('items.swapItems')->where('id', $request->meal_id)->first();
    //     // dd(\Auth::user()->id);
    //     $userId = $request->user_id;
    //     // dd($request->all());
    //     if($request->type == 'edit') {
    //         // dd('12');
    //         $userMeal = \App\Models\UserItemMeal::where('meal_id', $request->meal_id)->where('user_id',$request->user_id)->first();
    //         // $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->first();
    //         // $userMeal = \App\Models\UserMeal::where('meal_id', $request->meal_id)->where('user_plan_id',$userPlan->id)->first();

    //         if(!$userMeal) {
    //             $meal = Meal::with('items.swapItems')->where('id', $request->meal_id)->first();

    //             // Check if meal exists
    //             if (!$meal) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Meal not found.'
    //                 ], 404);
    //             }

    //             $mealName = $meal->title;
    //             $mealId = $meal->id;

    //             $data = $meal->userMealItems->map(function ($item) use ($userId) {
    //                 $userSwapItem = \DB::table('user_item_swaps')->where('user_id', $userId)->where('item_id', $item->id)->first();
    //                 return [
    //                     'id' => $item->id,
    //                     'name' => $item->title,
    //                     'qty'  => $item->pivot->qty ?? \App\Models\ItemMeal::where('item_id',$item->id)->first()->item_qty,
    //                     'swapItems' => $item->userItemSwaps->map(function ($swapItem) use ($userSwapItem) {
    //                         return [
    //                             'id' => $swapItem->id,
    //                             'name' => $swapItem->title,
    //                             'qty' => $swapItem->qty 
    //                                 ?? optional(\App\Models\ItemMeal::where('item_id', $swapItem->id)->first())->item_qty 
    //                                 ?? optional($userSwapItem)->qty 
    //                                 ?? 0
    //                         ];
    //                     })
    //                 ];
    //             });
    //             // Prepare the data array
    //             // $data = $meal->items->map(function ($item) {
    //             //     return [
    //             //         'id' => $item->id,
    //             //         'name' => $item->title,
    //             //         'qty'  => $item->pivot->item_qty,
    //             //         'swapItems' => $item->swapItems->map(function ($swapItem) {
    //             //             return [
    //             //                 'id' => $swapItem->id,
    //             //                 'name' => $swapItem->title
    //             //             ];
    //             //         })
    //             //     ];
    //             // });
    //         }else {
    //                 $meal = Meal::where('id', $request->meal_id)
    //                     ->with(['userMealItems' => function ($query) use ($userId) {
    //                         $query->where('user_id', $userId) // Filter userMealItems by user_id
    //                         ->with(['userItemSwaps' => function ($subQuery) use($userId){
    //                             $subQuery->where('user_id', $userId); // Add condition to filter userItemSwaps
    //                         }]);
    //                     }])
    //                     ->first();
    //             // }
    //             // $meal = Meal::where('id', $meal->meal_id)
    //             //     ->with(['userMealItems' => function ($query) use ($userId) {
    //             //         $query->where('user_id', $userId) // Filter userMealItems by user_id
    //             //         ->with(['userItemSwaps' => function ($subQuery) use($userId){
    //             //             $subQuery->where('user_id', $userId); // Add condition to filter userItemSwaps
    //             //         }]);
    //             //     }])
    //             //     ->first();
           
    //             $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->first();
    //             // $userMeal = null;
    //             if($userPlan) {
    //                 $userMealTimes = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
    //                                 ->where('meal_time_id', $request->meal_time_id)->first();
    //                 if($userMealTimes) {
    //                     // $userMeal = \App\Models\UserMeal::where('user_meal_time_id', $userMealTimes->id)->where('meal_id', $request->meal_id)->first();
    //                 }
    //             }

    //             $mealName = isset($userMeal->meal_name) ? $userMeal->meal_name : $meal->title;
    //             $mealId = $meal->id;
    //             // $meal = $userMeal;
    //             // dd($userMeal);
    //             // Prepare the data array
    //             // $data = $meal->userMealItems->map(function ($userItem) use ($userId, $userMeal) {
    //                 // dd($userItem);
    //                 // $userSwapItem = \DB::table('user_item_swaps')->where('user_id', $userId)->where('item_id', $userItem->item_id)->first();
    //                 // $itemQty = \App\Models\UserItemMeal::where('item_id',$userItem->item_id)->where('meal_id', $userMeal->meal_id)->where('user_id', $userId)->first();
    //                 $swapItems = \App\Models\UserItemSwap::with('item')->where('item_id',$userMeal->item_id)->where('user_id', $userId)->get();
    //                 // dd($swapItems);
    //                 $data =  [
    //                     'id' => $userMeal->items->id,
    //                     'name' => $userMeal->items->title,
    //                     'qty'  => isset($userItem->qty) ? $userItem->qty : 0,
    //                     'swapItems' => $swapItems->map(function ($swapItem) {
    //                         return [
    //                             'id' => $swapItem->item->id,
    //                             'name' => $swapItem->item->title,
    //                             'qty' => $swapItem->qty,
    //                         ];
    //                     })
    //                 ];
    //             // });
    //             // $data = $meal->userMealItems->map(function ($userItem) use ($userId, $userMeal) {
    //             //     // dd($userItem);
    //             //     $userSwapItem = \DB::table('user_item_swaps')->where('user_id', $userId)->where('item_id', $userItem->pivot->item_id)->first();
    //             //     $itemQty = \App\Models\UserItemMeal::where('item_id',$userItem->pivot->item_id)->where('meal_id', $userMeal->meal_id)->where('user_id', $userId)->first();
    //             //     $swapItems = \App\Models\UserItemSwap::with('item')->where('item_id',$userItem->pivot->item_id)->where('user_id', $userId)->get();
    //             //     // dd($swapItems);
    //             //     return [
    //             //         'ID' => $userItem->pivot->item_id,
    //             //         'id' => $userItem->id,
    //             //         'name' => $userItem->title,
    //             //         'qty'  => isset($userItem->pivot->qty) ? $userItem->pivot->qty : 0,
    //             //         'swapItems' => $swapItems->map(function ($swapItem) use ($userSwapItem) {
    //             //             return [
    //             //                 'ID' => $swapItem->id,
    //             //                 'id' => $swapItem->item->id,
    //             //                 'name' => $swapItem->item->title,
    //             //                 'qty' => $swapItem->qty,
    //             //             ];
    //             //         })
    //             //     ];
    //             // });
    //         }
           

    //         // dd($data);
    //     } else {
            
    //         $meal = Meal::with('items.swapItems')->where('id', $request->meal_id)->first();
            
    //         // Check if meal exists
    //         if (!$meal) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Meal not found.'
    //             ], 404);
    //         }

    //         $mealName = $meal->title;
    //         $mealId = $meal->id;

    //         // Prepare the data array
    //         $data = $meal->items->map(function ($item) {
    //             return [
    //                 'id' => $item->id,
    //                 'name' => $item->title,
    //                 'qty'  => $item->pivot->item_qty,
    //                 'swapItems' => $item->swapItems->map(function ($swapItem) {
    //                     return [
    //                         'id' => $swapItem->id,
    //                         'name' => $swapItem->title,
    //                         'qty' => $swapItem->qty ?? (\App\Models\ItemMeal::where('item_id',$swapItem->id)->first() ? \App\Models\ItemMeal::where('item_id',$swapItem->id)->first()->item_qty : 0) ?? 0
    //                     ];
    //                 })
    //             ];
    //         });
    //     }

    //     // Return the response
    //     return response()->json([
    //         'success' => true,
    //         'meal_id' => $mealId,
    //         'meal_name' => $mealName,
    //         'data' => $data
    //     ]);
    // }

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

        // Prepare the response: Flatten and collect only meals from all categories
        $meals = $mealTime->categories->flatMap(function ($category) {
            return $category->meals->map(function ($meal) {
                return [
                    'id' => $meal->id,
                    'name' => $meal->title
                ];
            });
        });

        $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->first();
        if($userPlan){

            $userMealTimes = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)
                                ->where('meal_time_id', $request->meal_time_id)->first();

            $userMeals = [];
            if(isset($userMealTimes->userCategories)) {
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
            // dd($userMeals);
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
            $updatedMeals = $mealTime->categories->flatMap(function ($category) {
                return $category->meals->map(function ($meal) {
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
                    'image' => $swapItem->image
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
       
            $items = $item->swapItems;

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
                'selectedSwapItems' => $selectedItemArr
            ]);
        }
    }

    public function updateFoodSwapFoods(Request $request) 
    {
        // dd($request->all());
        // if($request->has('type') && $request->type == 'add') {
        //     $itemMeal = \App\Models\ItemMeal::with('item')->where('item_id', $request->item_id)->where('meal_id', $request->meal_id)->first();
        //     if($itemMeal) {
        //         \App\Models\ItemMeal::with('item')->where('id', $itemMeal->id)->where('item_id', $request->item_id)->where('meal_id', $request->meal_id)->update([
        //             'item_qty' => $request->item_qty,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }

        //     $swapFoods = $request->swap_items;
        //     if($request->has('swap_items') && is_array($request->swap_items)) {

        //         foreach ($swapFoods as $swapFood) {
        //             $swapItem = \DB::table('item_swaps')->where('item_id', $request->item_id)->where('swap_item_id', $swapFood)->first();
        //             if($swapItem) {
        //                 \DB::table('item_swaps')->where('item_id', $request->item_id)->where('swap_item_id', $swapFood)->update([
        //                     'swap_item_qty' => $request->swap_item_qty,
        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ]);
        //             }else {
        //                 $swapItem = \DB::table('item_swaps')->insert([
        //                     'item_id' => $request->item_id,
        //                     'swap_item_qty' => $request->swap_item_qty,
        //                     'swap_item_id' => $swapFood,
        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ]); 
        //             }
        //         }
        //         $foods = Item::whereIn('id', $swapFoods)->get();
        //     } else {
        //         $swapItem = \DB::table('item_swaps')->where('item_id', $request->item_id)->where('swap_item_id', $request->swap_item_id)->first();
        //         if($swapItem) {
        //             \DB::table('item_swaps')->where('item_id', $request->item_id)->where('swap_item_id', $request->swap_item_id)->update([
        //                 'swap_item_qty' => $request->swap_item_qty,
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);
        //         }else {
        //             $swapItem = \DB::table('item_swaps')->insert([
        //                 'item_id' => $request->item_id,
        //                 'swap_item_qty' => $request->swap_item_qty,
        //                 'swap_item_id' => $request->swap_items,
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]); 
        //         }
        //         $foods = Item::where('id', $swapFoods)->get();
        //     }
        //     $swapItem = \DB::table('item_swaps')->where('item_id', $request->item_id)->where('swap_item_id', $request->swap_item_id)->first();
        //     return response()->json([
        //         'success' => true,
        //         'foods' => $foods,
        //         'item' => $itemMeal,
        //         'swapItem' => $swapItem,
        //         'message' => 'Swap foods updated successfully!'
        //     ]);
        // } else {

            $userItemSwap = \DB::table('user_item_swaps')->where('user_id', $request->user_id)->where('item_id', $request->item_id)->delete();

            $userItemMeal = \App\Models\UserItemMeal::with('items')->where('user_id', $request->user_id)->where('meal_id', $request->meal_id)->where('item_id', $request->item_id)->first();

            $savedItem = null;
            $savedSwapItems = [];

            if($userItemMeal) {
                $userItemMeal->update([
                    'qty' => $request->item_qty,
                    'unit' => $request->item_unit,
                    'carbs' => $request->food_carbs,
                    'protein' => $request->food_protein,
                    'fat' => $request->food_fat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $savedItem = $userItemMeal->load('items');

            }else {
                $savedItem = \App\Models\UserItemMeal::create([
                    'user_id' => $request->user_id,
                    'item_id' => $request->item_id,
                    'qty' => $request->item_qty,
                    'unit' => $request->item_unit,
                    'carbs' => $request->food_carbs,
                    'protein' => $request->food_protein,
                    'fat' => $request->food_fat,
                    'meal_id' => $request->meal_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $savedItem->load('items');

            }

            $swapFoods = $request->swap_items;
            if($request->has('swap_items') && is_array($request->swap_items)) {
                foreach ($swapFoods as $swapFood) {
                    // dd($swapFood);
                    $existingSwap = UserItemSwap::with('swapItem')
                        ->where('item_id', $request->item_id)
                        ->where('swap_item_id', $swapFood)
                        ->first();
    
                    if ($existingSwap) {
                        // ✅ Update swap item quantity if record exists
                        $existingSwap->update([
                            'qty' => $request->swap_item_qty,
                            'carbs'=> $request->swap_food_carbs,
                            'protein' => $request->swap_food_protein,
                            'fat' => $request->swap_food_fat,
                            'unit' => $request->swap_item_unit,
                            'created_at' => now(),
                            'updated_at' => now(),

                        ]);
    
                        // Load related `swapItem` after updating
                        $savedSwapItems[] = $existingSwap->load('swapItem');
                    } else {
                        $deleteSwapFood = UserItemSwap::with('swapItem')
                        ->where('item_id', $request->item_id)
                        ->where('user_id', $request->user_id)
                        ->delete();

                        $swapItem = UserItemSwap::create([
                            'item_id' => $request->item_id,
                            'swap_item_id' => $swapFood,
                            'user_id' => $request->user_id,
                            'qty' => $request->swap_item_qty,
                            'carbs'=> $request->swap_food_carbs,
                            'protein' => $request->swap_food_protein,
                            'fat' => $request->swap_food_fat,
                            'unit' => $request->swap_item_unit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
    
                        // Load related `swapItem` after saving
                        $savedSwapItems[] = $swapItem->load('swapItem');
                    }
                }
                // $foods = Item::whereIn('id', $swapFoods)->get();
            } else {
                $swapItem = UserItemSwap::create([
                    'user_id' => $request->user_id,
                    'item_id' => $request->item_id,
                    'qty' => $request->swap_item_qty,
                    'unit' => $request->swap_item_unit,
                    'carbs' => $request->swap_food_carbs,
                    'protein' => $request->swap_food_protein,
                    'fat' => $request->swap_food_fat,
                    'swap_item_id' => $swapFoods,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $savedSwapItems[] = $swapItem->load('swapItem');
            }
            
            return response()->json([
                'success' => true,
                // 'foods' => $foods,
                'item' => $savedItem,
                'swapItem' => $savedSwapItems,
                'message' => 'Swap foods updated successfully!'
            ]);
        // }

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
        if($request->type == 'woolworths') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|url',
                'protein' => 'nullable',
                'fat' => 'nullable',
                'carbs' => 'nullable',
                'category' => 'nullable',
                'meal_id' => 'required|exists:meals,id',
                'user_id' => 'required|exists:users,id',
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
                $food->protein = $protein;
                $food->carbs = $carbs;
                $food->fat = $fat;
                $food->image = 'items/' . $imageName; // Path to the stored image
                $food->is_swiped = 0;
                $food->category_id = isset($foodCategory) ? $foodCategory->id : null;

                if($food->save()) {
                    $userItemMeal = new UserItemMeal();
                    $userItemMeal->user_id = $request->user_id;
                    $userItemMeal->meal_id = $request->meal_id;
                    $userItemMeal->is_swiped = $food->is_swiped;
                    $userItemMeal->item_id = $food->id;
                    $userItemMeal->save();
                }

                // Step 4: Redirect with success message
                return response()->json(['success' => true, 'data' => $food, 'message' => 'Food added successfully.']);
            } catch (\Exception $e) {
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
                        'qty' => $swapItem->qty
                    ];
                });

                // Include simplified swapItems in response
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'qty'  => $item->qty,
                        'carbs' => $item->carbs,
                        'protein' => $item->protein,
                        'fat'     => $item->fat,
                        'swapItems' => $simplifiedSwapItems  // Pass simplified swap items here
                    ],
                    'message' => 'Food added successfully.'
                ]);
            }
        }
        // }
        //     }
        //     // Create item

        //     $userItemMeal = new UserItemMeal();
        //     $userItemMeal->user_id = $request->user_id;
        //     $userItemMeal->meal_id = $request->meal_id;
        //     $userItemMeal->is_swiped = $request->is_swiped;
        //     // $userItemMeal->qty = $request->qty;
        //     $userItemMeal->item_id = $item->id;
        //     $userItemMeal->save();

        //     // $userPlan = \App\Models\UserPlan::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->first();

        //     // $userMealTime = \App\Models\UserMealTime::where('user_plan_id', $userPlan->id)->where('meal_time_id', $request->meal_time_id)->first();
        //     // // $userCategories = $userMealTime->userCategories->pluck('id')->toArray();

        //     // $userMeal = \App\Models\UserMeal::where('user_plan_id', $userPlan->id)->where('user_meal_time_id', $userMealTime->id)
        //     //                    ->where('meal_id', $request->meal_id)->first();

        //     // Sync swap items
        //     if ($request->is_swiped == 1 && $request->has('swap_item_ids')) {
        //         $item->swapItems()->sync($request->swap_item_ids);
        //         foreach ($request->swap_item_ids as $swapItemId) {
        //             $exists = \DB::table('user_item_swaps')
        //                 ->where('user_id', $request->user_id)
        //                 ->where('item_id', $swapItemId)
        //                 ->where('swap_item_id', $item->id)
        //                 ->exists();

        //             if (!$exists) {
        //                 \DB::table('user_item_swaps')->insert([
        //                     'user_id' => $request->user_id,
        //                     'item_id' => $swapItemId,
        //                     'swap_item_id' => $item->id,
        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ]);
        //             }
        //         }
        //     }

        //     return response()->json([
        //         'success' => true,
        //         'item' => $item,
        //         'message' => 'Food added successfully.'
        //     ]);
        // } catch (\Exception $e) {
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