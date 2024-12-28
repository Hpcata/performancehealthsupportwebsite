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

class PurchasePlanController extends Controller
{
    // List all payments with pagination
    public function index()
    {
        // Fetch payments with pagination (you can adjust per page as needed)
        $payments = Payment::with('plan')->paginate(10);

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
        // dd($plans);
        // dd($plan->subPlans);
        // Get all options for each relationship (mealTimes, categories, etc.)
        $mealTimes = MealTime::all();
        $categories = Category::all();
        $meals = Meal::all();
        $items = Item::where('is_swiped',0)->get();

        return view('backend.pages.plan.purchase-plan-create', compact(
            'plans', 'mealTimes', 'categories', 'meals', 'items', 'payment', 'subPlans'
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
    
        try {
            $payment = Payment::findOrFail($request->payment_id);
            \DB::beginTransaction();
            // Initialize arrays
            
            $mealItems = \App\Models\ItemMeal::with('item') // Load the related Item data
            ->get();
        
            foreach ($mealItems as $mealItem) {
                \DB::table('user_item_meals')->insert([
                    'item_id' => $mealItem->item_id,
                    'meal_id' => $mealItem->meal_id,
                    'user_id' => $payment->user_id,
                    'is_swiped' => $mealItem->item->is_swiped ?? 0, // Assuming 'is_swiped' exists in the items table
                ]);
            }
            $swapItems = \DB::table('item_swaps')->get();
            foreach($swapItems as $swapItem) {
                \DB::table('user_item_swaps')->insert([
                    'item_id' => $swapItem->item_id,
                    'swap_item_id' => $swapItem->swap_item_id,
                    'user_id' => $payment->user_id
                ]);
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
        // Fetch the user's plan with all necessary relationships
        $payment = Payment::find($planId);
        // dd($user->id);
        $plan = Plan::find($payment->plan_id);
        // dd($plan);
        $subPlanIds = $plan->subPlans->pluck('id')->toArray();
        // dd($subPlanIds);
        $userPlans = UserPlan::with([
            'plan', 
            'userMealTimes.userCategories.userMeals.userItems.userSwapItems', // Correctly load subcategories through pivot table
        ])
        ->where('user_id', $user->id)
        ->where('plan_id', $plan->id)
        ->when($subPlanIds, function ($query) use ($subPlanIds) {
            return $query->orWhereIn('plan_id', $subPlanIds);
        })
        ->get();
        
        // dd($userPlans);
        // Redirect if the plan doesn't exist
        if (!$userPlans) {
            return redirect()->route('admin.purchase-plans.index')
                             ->with('error', 'User Plan not found.');
        }

        $selectedMeals = [];
        $selectedItems = []; // To store pre-selected user items
        $selectedSwapItems = []; // To store pre-selected swap items

        foreach ($userPlans as $userPlan) {
            foreach ($userPlan->userMealTimes as $mealTime) {
                $selectedMeals[$mealTime->meal_time_id] = $mealTime->userMeals->pluck('id','meal_id')->toArray();

                foreach ($mealTime->userMeals as $userMeal) {
                    $mealId = $userMeal->meal_id;

                    // Store user items
                    $selectedItems[$mealTime->meal_time_id][$mealId] = $userMeal->userItems->pluck('item_id')->toArray();

                    // Store user swap items
                    foreach ($userMeal->userItems as $userItem) {
                        $selectedSwapItems[$mealTime->meal_time_id][$mealId][$userItem->item_id] = $userItem->userSwapItems->pluck('swap_item_id')->toArray();
                    }
                }
            }
        }
        // dd($selectedSwapItems);
        $mealTimes = MealTime::all();
        $categories = Category::all();
        
        $meals = Meal::all();
        $items = Item::where('is_swiped',0)->get();
        // dd($selectedItems);

        $activity = UserPlan::with([
            'modifiedBy', 
        ])
        ->where('user_id', $payment->user_id)
        ->where('plan_id', $payment->plan_id)
        ->orderBy('updated_at', 'desc') // Order by updated_at in descending order
        ->first();
        return view('backend.pages.plan.purchase-plan-edit', compact(
            'userPlans', 'mealTimes', 'categories', 'meals', 'items', 'selectedMeals', 'selectedItems', 'selectedSwapItems', 'activity', 'payment'
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
        // Fetch the meal with related items and their swap items
        // $meal = Meal::with('items.swapItems')->where('id', $request->meal_id)->first();
        // dd(\Auth::user()->id);
        $userId = $request->user_id;
        // dd($request->all());
        if($request->type == 'edit') {
            // dd('12');
            $meal = \App\Models\UserItemMeal::where('meal_id', $request->meal_id)->where('user_id',$request->user_id)->first();
            // dd($meal);
            $meal = Meal::where('id', $meal->meal_id)
                    ->with(['userMealItems' => function ($query) use ($userId) {
                        $query->where('user_id', $userId) // Filter userMealItems by user_id
                        ->with(['userItemSwaps' => function ($subQuery) use($userId){
                            $subQuery->where('user_id', $userId); // Add condition to filter userItemSwaps
                        }]);
                    }])
                    ->first();
            // dd($meal);

            //   dd($meal->userMealItems);
            // Check if meal exists
            if (!$meal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meal not found.'
                ], 404);
            }

            // Prepare the data array
            $data = $meal->userMealItems->map(function ($item) {
                // dd($item->userItemSwaps);
                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'swapItems' => $item->userItemSwaps->map(function ($swapItem) {
                        return [
                            'id' => $swapItem->id,
                            'name' => $swapItem->title
                        ];
                    })
                ];
            });
        } else {
            $meal = Meal::with('items.swapItems')->where('id', $request->meal_id)->first();
            
            //   dd($meal);
            // Check if meal exists
            if (!$meal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meal not found.'
                ], 404);
            }

            // Prepare the data array
            $data = $meal->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'swapItems' => $item->swapItems->map(function ($swapItem) {
                        return [
                            'id' => $swapItem->id,
                            'name' => $swapItem->title
                        ];
                    })
                ];
            });
        }
        

        // Return the response
        return response()->json([
            'success' => true,
            'meal_id' => $meal->id,
            'meal_name' => $meal->title, // Assuming 'name' is a property of Meal
            'data' => $data
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

        // Prepare the response: Flatten and collect only meals from all categories
        $meals = $mealTime->categories->flatMap(function ($category) {
            return $category->meals->map(function ($meal) {
                return [
                    'id' => $meal->id,
                    'name' => $meal->title
                ];
            });
        });

        // Return only the meals in the desired structure
        return response()->json([
            'success' => true,
            'meals' => $meals
        ]);
    }

    public function getPrePlanDetails($id)
    {
        $userPrePlan = \App\Models\UserPrePlan::with('prePlanDetails')->where('payment_id', $id)->first();
        $prePlanDetails = $userPrePlan->prePlanDetails;

        $userDetails = [
            'name' => $userPrePlan->user->name,
            'email' => $userPrePlan->user->email,
            'phone' => $userPrePlan->user->phone,
            'dob' => $userPrePlan->dob,
            'address' => $userPrePlan->address,
            'occupation' => $userPrePlan->occupation,
            'referredBy' => $userPrePlan->referredBy,
            'other' => $userPrePlan->other
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

//     public function edit($userPlanId)
// {
//     try {
//         // Find the UserPlan and its associated data
//         $userPlan = UserPlan::with([
//             'mealTimes.categories.subcategories.meals.items', // Eager load the necessary relations
//             'mealTimes.categories.subcategories.meals',
//             'mealTimes.categories',
//         ])->findOrFail($userPlanId);

//         // Get all available Meal Times, Categories, Subcategories, Meals, and Items for the form
//         $mealTimes = MealTime::all();
//         $categories = Category::all();
//         $subcategories = Subcategory::all();
//         $meals = Meal::all();
//         $items = Item::all();

//         // Return the edit view with the necessary data
//         return view('backend.purchase-plans.edit', compact('userPlan', 'mealTimes', 'categories', 'subcategories', 'meals', 'items'));
//     } catch (\Exception $e) {
//         \Log::error('Error fetching UserPlan data: ' . $e->getMessage());
//         return redirect()->route('admin.purchase-plans.index')
//                          ->with('error', 'Failed to fetch User Plan data. Error: ' . $e->getMessage());
//     }
// }


    // public function update(Request $request)
    // {
    //     // Validate the incoming request data
    //     $validated = $request->validate([
    //         'meal_times' => 'required|array',
    //         'meal_times.*' => 'exists:meal_times,id',
    //         'categories.*.*' => 'exists:categories,id',
    //         'subcategories.*.*' => 'exists:subcategories,id',
    //         'meals.*.*' => 'exists:meals,id',
    //         'items.*.*' => 'exists:items,id',
    //     ]);

    //     // Find the user plan
    //     $userPlan = UserPlan::with([
    //         'plan', // Plan details
    //         'userMealTimes.userCategories.userSubcategories.userMeals.userItems', // Full hierarchy of meal times to items
    //     ])->where('user_id', $request->user_id)
    //       ->where('plan_id', $request->plan_id)
    //       ->first();

    //     if (!$userPlan) {
    //         return redirect()->route('admin.purchase-plans.index')
    //             ->with('error', 'User Plan not found.');
    //     }

    //     // Update the user plan's metadata
    //     $userPlan->modified_by = auth()->id();
    //     $userPlan->updated_at = now();
    //     $userPlan->save();

    //     // Update MealTimes
    //     $mealTimeIds = $validated['meal_times'];
    //     $userPlan->mealTimes()->sync($mealTimeIds);

    //     // Process each meal time
    //     foreach ($mealTimeIds as $mealTimeId) {
    //         $userMealTime = $userPlan->mealTimes()->where('meal_time_id', $mealTimeId)->first();

    //         if (isset($validated['categories'][$mealTimeId])) {
    //             $categoryIds = $validated['categories'][$mealTimeId];
    //             $userMealTime->categories()->sync($categoryIds);

    //             // Process categories
    //             foreach ($categoryIds as $categoryId) {
    //                 $userCategory = $userMealTime->categories()->where('category_id', $categoryId)->first();

    //                 if (isset($validated['subcategories'][$categoryId])) {
    //                     $subcategoryIds = $validated['subcategories'][$categoryId];
    //                     $userCategory->subcategories()->sync($subcategoryIds);

    //                     // Process subcategories
    //                     foreach ($subcategoryIds as $subcategoryId) {
    //                         $userSubcategory = $userCategory->subcategories()->where('subcategory_id', $subcategoryId)->first();

    //                         if (isset($validated['meals'][$subcategoryId])) {
    //                             $mealIds = $validated['meals'][$subcategoryId];
    //                             $userSubcategory->meals()->sync($mealIds);

    //                             // Process meals
    //                             foreach ($mealIds as $mealId) {
    //                                 $userMeal = $userSubcategory->meals()->where('meal_id', $mealId)->first();

    //                                 if (isset($validated['items'][$mealId])) {
    //                                     $itemIds = $validated['items'][$mealId];
    //                                     $userMeal->items()->sync($itemIds);
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // Redirect back with a success message
    //     return redirect()->route('admin.purchase-plans.index')
    //         ->with('success', 'User Plan updated successfully.');
    // }


}
