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
use App\Models\User;
use App\Models\UserCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityTracker;
use App\Models\TrackingType;
use App\Models\UserPrePlan;
use App\Models\SportGame;

class PlanController extends Controller
{
    public function show(Request $request, $id)
    {
        $plan = Plan::find($id);
        if (!$plan) return back()->with('error', 'Plan not found.');

        $user = User::find($request->user_id);
        if (!$user) return back()->with('error', 'User not found.');

        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::where('user_id', $user->id)
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

        foreach ($userPlans as $userPlan) {
            $userPlan->load([
                'plan',
                'userCategories' => function ($query) use ($userPlan) {
                    $query->where('user_plan_id', $userPlan->id)
                        ->whereHas('userSubCategories', function ($subQ) use ($userPlan) {
                            $subQ->where('user_plan_id', $userPlan->id)
                                ->whereHas('userMeals', fn ($mealQ) =>
                                    $mealQ->where('user_plan_id', $userPlan->id)
                                );
                        })
                        ->with([
                            'category',
                            'userSubCategories' => function ($subQ) use ($userPlan) {
                                $subQ->where('user_plan_id', $userPlan->id)
                                    ->whereHas('userMeals', fn ($mealQ) =>
                                        $mealQ->where('user_plan_id', $userPlan->id)
                                    )
                                    ->with(['userMeals' => fn ($mealQ) =>
                                        $mealQ->where('user_plan_id', $userPlan->id)
                                    ]);
                            }
                            ]);
                        //   ->orderByRaw('COALESCE(`order`, 0)');
                }
            ]);
        }

        $click = ActivityTracker::click('view_plan_button_click', $user->id);

        ActivityTracker::log(TrackingType::PLAN_VIEWED, $user->id, [
            'user_click_id' => $click->id,
            'section_element_id' => $click->section_element_id,
            'plan_id' => $plan->id,
        ]);

        $userPrePlan = $user->userPrePlans()->first();

        $sportGame = null;

        if ($userPrePlan && $userPrePlan->occupation) {
            $sportGame = SportGame::with('categories')
                            ->where('name', $userPrePlan->occupation)
                            ->first();
        }

        $category = isset($sportGame->categories) ? $sportGame->categories->first() : null;
        $sportImagePath = null;
        if ($category) {
            $sportImagePath = ($category->pivot->image_path) ? $category->pivot->image_path : '';
        }

        return view('front.pages.plan-details', compact('userPlans', 'plan', 'user', 'sportImagePath'));
    }

    public function mealTimeDetails(Request $request, $id, $plan_id)
    {
        $userPlan = UserPlan::with([
            'plan',
            // ---- userCategories sorted by categories.order -------------
            'userCategories' => function ($q) {
                $q->leftJoin('categories', 'categories.id', '=', 'user_categories.category_id')
                ->orderBy('categories.order')
                ->select('user_categories.*');          // keep only UC columns
            },
            'userCategories.category',                     // still eager‑load the Category model
            'userCategories.userSubCategories.userMeals'
        ])->where('id', $plan_id)->first();
        
        $userMealTime = UserCategory::with('userSubCategories.userMeals')->where('id', $id)
        ->where('user_plan_id', $plan_id)
        ->first();
       
        // $mealtime = MealTime::with('categories','categories.subcategories')->findOrFail($id);
        return view('front.sub-category-details', compact('userMealTime','userPlan'));
    }

    public function getMeals(Request $request, $id)
    {
        $request->validate([
            'user_category_id' => 'required|integer',
            'user_plan_id' => 'required|integer',
        ]);

        $categories = \App\Models\UserSubCategory::with('userMeals.meal') // Ensure 'meal' relation is loaded
            ->where('user_plan_id', $request->user_plan_id)
            ->where('user_category_id', $request->user_category_id)
            ->where('id', $id)
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'User sub-category not found.',
                'meals' => [],
            ], 404);
        }

        $meals = $categories->flatMap(function ($subCategory) use($request, $id){
            return $subCategory->userMeals->where('user_plan_id', $request->user_plan_id)
            ->where('user_category_id', $request->user_category_id)
            ->where('user_sub_category_id', $id)
            ->map(function ($userMeal) {
                $meal = optional($userMeal->meal);
                return [
                    'user_meal_id' => $userMeal->id,
                    'id' => $meal->id,
                    'name' => $userMeal->meal_name ?? $meal->title,
                    'description' => $meal->description,
                    'image' => $meal && $meal->image
                        ? webAssets('storage/' . $meal->image)
                        : 'https://via.placeholder.com/300x200?text=No+Image',
                    'user_sub_category_id' => $userMeal->user_sub_category_id
                ];
            });
        })->values();

        return response()->json([
            'success' => true,
            'meals' => $meals,
        ]);
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

    public function getMealItems(Request $request)
    {
        $userMeal = \App\Models\UserMeal::with([
            'userItems' => function ($query) use ($request) {
                $query->where('user_plan_id', $request->user_plan_id)
                    ->where('user_sub_category_id', $request->user_sub_category_id);
            },
            'userItems.item',
            'userItems.userSwapItems' => function ($query) use ($request) {
                $query->where('user_plan_id', $request->user_plan_id)
                    ->where('user_sub_category_id', $request->user_sub_category_id)
                    ->where('user_category_id', $request->user_category_id);
            }
        ])
        ->where('id', $request->user_meal_id)
        ->where('user_plan_id', $request->user_plan_id)
        ->where('user_sub_category_id', $request->user_sub_category_id)
        ->first();

        if (!$userMeal) {
            return response()->json(['message' => 'User meal not found'], 404);
        }

        $userPlan = \App\Models\UserPlan::where('id', $request->user_plan_id)
            ->where('status', 'active')
            ->first();

        if (!$userPlan) {
            return response()->json(['message' => 'User plan not found or inactive'], 404);
        }

        $items = $userMeal->userItems->filter(function ($userItem) use ($request) {
            return $userItem->user_plan_id == $request->user_plan_id
                && $userItem->user_sub_category_id == $request->user_sub_category_id
                && $userItem->user_category_id == $request->user_category_id;
        })->map(function ($userItem) use ($userPlan, $userMeal) {
            $userItemMeal = \App\Models\UserItemMeal::where('user_id', $userPlan->user_id)
                ->where('meal_id', $userMeal->id)
                ->where('item_id', $userItem->id)
                ->first();

            $qty = $userItemMeal->qty ?? ($userItem->pivot->item_qty ?? 0);
            $unit = $userItemMeal->unit ?? ($userItem->pivot->item_qty_unit ?? '');

            return [
                'user_meal_id' => $userItem->userMeal->id,
                'user_item_id' => $userItem->id,
                'id' => $userItem->item->id ?? null,
                'name' => $userItem->item->title ?? null,
                'protein' => $userItem->item->protein ?? 0,
                'carbs' => $userItem->item->carbs ?? 0,
                'qty' => $qty,
                'unit' => $unit,
                'selected_qty_unit' => $userItemMeal->selected_qty_unit ?? null,
                'description' => $userItem->item->description ?? null,
                'note' => $userItem->item->note ?? 'Nil',
                'image' => isset($userItem->item->image)
                    ? webAssets('storage/' . $userItem->item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
                'swapItems' => $userItem->userSwapItems,
            ];
        })->values();

        return response()->json([
            'meal' => $userMeal->meal->title,
            'items' => $items,
        ]);
    }

    public function getSwapItems(Request $request, $id)
    {
        // Validate inputs (optional but good practice)
        $request->validate([
            'user_item_id' => 'required|integer',
            'user_plan_id' => 'required|integer',
        ]);

        // Fetch the UserItem with userSwapItems filtered by user_plan_id
        $userItem = \App\Models\UserItem::with([
            'userSwapItems' => function ($query) use ($request) {
                $query->where('user_plan_id', $request->user_plan_id)
                    ->where('user_sub_category_id', $request->sub_category_id)
                    ->where('user_meal_id', $request->user_meal_id);
            },
            'item' // Assuming userItem belongs to item
        ])
        ->where('id', $request->user_item_id)
        ->where('user_plan_id', $request->user_plan_id)
        ->where('user_sub_category_id', $request->sub_category_id)
        ->where('user_meal_id', $request->user_meal_id)
        ->first();

        // Check if userItem exists
        if (!$userItem) {
            return response()->json(['message' => 'User item not found.'], 404);
        }
        // Map the swap items
        $items = $userItem->userSwapItems->map(function ($swapItem) {
            return [
                'swap_item_id' => $swapItem->swapItem->id ?? null,
                'swap_item_name' => $swapItem->swapItem->title ?? null,
                'swap_item_qty' => $swapItem->swapItem->qty ?? null,
                'swap_item_protein' => $swapItem->swapItem->protein ?? null,
                'swap_item_carbs' => $swapItem->swapItem->carbs ?? null,
                'swap_item_description' => $swapItem->swapItem->description ?? null,
                'swap_item_image' => isset($swapItem->swapItem->image)
                    ? webAssets('storage/' . $swapItem->swapItem->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        $item = $userItem->item;

        $item_image = $item && $item->image
            ? webAssets('storage/' . $item->image)
            : 'https://via.placeholder.com/300x200?text=No+Image';

        // Final response
        return response()->json([
            'item_id' => $item->id ?? null,
            'item_name' => $item->title ?? null,
            'item_image' => $item_image,
            'user_item_id' => $request->user_item_id,
            'items' => $items,
            'item' => $item
        ]);
    }

    public function applySwaps(Request $request)
    {
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
        $categoryId = $request->user_category_id;
        $subCategoryId = $request->user_sub_category_id;
        $userPlanId = $request->user_plan_id;
        $userMealId = $request->user_meal_id;

        try {
            \DB::beginTransaction();

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

                $userItem = \App\Models\UserItem::where('id', $swap['swap_id'])
                    ->where('user_plan_id', $userPlanId)
                    ->where('user_category_id', $categoryId)
                    ->where('user_sub_category_id', $subCategoryId)
                    ->where('user_meal_id', $userMealId)
                    ->first();

                if ($userItem) {
                    $userMealId = $userItem->user_meal_id;

                    $swapItems = \DB::table('user_swap_items')
                        ->where('user_item_id', $userItem->id)
                        ->where('user_category_id', $categoryId)
                        ->where('user_sub_category_id', $subCategoryId)
                        ->where('user_plan_id', $userPlanId)
                        ->where('user_meal_id', $userMealId)
                        ->get();
                    // dd($swapItems);
                    foreach ($swapItems as $swapItem) {
                        // dd($swapItem->id);
                        $a = \DB::table('user_swap_items')
                            ->where('id', $swap['main_id'])
                            ->where('user_item_id', $userItem->id)
                            ->where('user_category_id', $categoryId)
                            ->where('user_sub_category_id', $subCategoryId)
                            ->where('user_plan_id', $userPlanId)
                            ->where('user_meal_id', $userMealId)
                            // ->get();
                            ->update([
                                'id' => $swap['swap_id'],
                                'user_item_id' => $swap['main_id'],
                            ]);
                        
                        $b = \DB::table('user_swap_items')
                            ->where('id', $swapItem->id)
                            ->where('user_item_id', $userItem->id)
                            ->where('user_category_id', $categoryId)
                            ->where('user_sub_category_id', $subCategoryId)
                            ->where('user_plan_id', $userPlanId)
                            ->where('user_meal_id', $userMealId)
                            ->update([
                                'user_item_id' => $swap['main_id'],
                            ]);
                            
                    }
                    $userItem = \App\Models\UserItem::where('id', $swap['swap_id'])
                    ->where('user_plan_id', $userPlanId)
                    ->where('user_category_id', $categoryId)
                    ->where('user_sub_category_id', $subCategoryId)
                    ->where('user_meal_id', $userMealId)
                    ->update(['id' => $swap['main_id']]);
                   
                } else {
                    throw new \Exception("Item to swap not found in the meal for swap_id {$swap['swap_id']}");
                }
            }

            $click = ActivityTracker::click('button_applied_swap', $request->user_id);

            ActivityTracker::log(TrackingType::PRODUCT_SWAP, $request->user_id, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'meal_id' => $mealId,
                'meal_name' => $mealName,
                'swaps' => $swaps,
                'user_plan_id' => $userPlanId,
                'user_meal_id' => $userMealId,
                'user_category_id' => $categoryId,
                'user_sub_category_id' => $subCategoryId,
                'user_id' => $userId,
                'action' => 'apply_swaps',
            ]);

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
            Log::error('Error fetching apply swaps : ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to apply swaps. Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function generatePdf(Request $request, $id)
    {
        $plan = Plan::find($id);
        $groupedData = json_decode($request->grouped_data, true); // ← decoded as associative array

        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::with('plan')
            ->where('user_id', $request->user_id) // Ensure user_id is always applied
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

        // Sort userMealTimes by mealTime.order ASC
        $userPlans->each(function ($userPlan) {
            $userPlan->userCategories = $userPlan->userCategories->where('user_plan_id', $userPlan->id)
                ->sortBy(fn($mt) => $mt->category->order ?? 0)
                ->values(); // reindex
        });
       
        $pdf = Pdf::loadView('front.plan-pdf', compact('userPlans', 'groupedData'))
        ->setPaper('A4', 'portrait'); // Set page size and layout

        // Download the generated PDF
        return $pdf->download('plan_' . $id . '.pdf');
        // return $pdf->stream('plan.pdf');

    }

    public function preview(Request $request, $id)
    {
        $plan = Plan::find($id);
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::with('plan')
            ->where('user_id', $request->user_id)
            ->where(function ($query) use ($id, $subPlans) {
                $query->where('plan_id', $id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

        $userPlans->each(function ($userPlan) {
            $userPlan->userCategories = $userPlan->userCategories->where('user_plan_id', $userPlan->id)
                ->sortBy(fn($mt) => $mt->category->order ?? 0)
                ->values(); // reindex
        });
        $payment = \App\Models\Payment::where('user_id', $request->user_id)->where('plan_id', $id)->first();
        $userPrePlan = \App\Models\UserPrePlan::where('user_id', $request->user_id)->where('payment_id', $payment->id)->first();

        $sportGame = \App\Models\SportGame::with('categories')->where('name', $userPrePlan->occupation)->first();
        $category = isset($sportGame->categories) ? $sportGame->categories->first() : null;
        $sportImagePath = null;
        if ($category) {
            $sportImagePath = ($category->pivot->image_path) ? $category->pivot->image_path : '';
        }
        $printAllmeal = true;
        return view('front.plan-preview', compact('userPlans', 'printAllmeal', 'sportImagePath'));
    }

    public function planPreview(Request $request)
    {
        $groupedData = $request->input('grouped_data');

        $plan = Plan::find($request->plan_id);
        $subPlans = $plan->subPlans ? $plan->subPlans()->pluck('sub_plan_id')->toArray() : [];

        $userPlans = UserPlan::with('plan')
            ->where('user_id', $request->user_id)
            ->where(function ($query) use ($request, $subPlans) {
                $query->where('plan_id', $request->plan_id)
                    ->orWhereIn('plan_id', $subPlans);
            })
            ->get();

        // Sort userMealTimes
        $userPlans->each(function ($userPlan) {
            $userPlan->userCategories = $userPlan->userCategories->where('user_plan_id', $userPlan->id)
                ->sortBy(fn($mt) => $mt->category->order ?? 0)
                ->values();
        });

        $payment = \App\Models\Payment::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->first();
        $userPrePlan = \App\Models\UserPrePlan::where('user_id', $request->user_id)->where('payment_id', $payment->id)->first();

        $sportGame = \App\Models\SportGame::with('categories')->where('name', $userPrePlan->occupation)->first();
        $category = isset($sportGame->categories) ? $sportGame->categories->first() : null;
        $sportImagePath = null;
        if ($category) {
            $sportImagePath = ($category->pivot->image_path) ? $category->pivot->image_path : '';
        }
        
        $printAllmeal = false;

        return view('front.plan-preview', compact('userPlans', 'groupedData', 'printAllmeal', 'sportImagePath'));
    }

    public function getDefaultPlanDetails($id)
    {
        $plan = Plan::with([
            'subPlans.categories.subCategories.meals.items.swapItems',
            'categories.subCategories.meals.items.swapItems'
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
                'mealTimes' => $plan->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->title,
                        'categories' => $category->subCategories->map(function ($subcategory) {
                            return [
                                'id' => $subcategory->id,
                                'name' => $subcategory->title,
                                'meals' => $subcategory->meals->map(function ($meal) {
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

        $userMealTime = \App\Models\UserCategory::with('userMeals.meal')  // Assuming you want meal info
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
            'userCategories.userSubCategories.userMeals.meal',
        ])
        ->where('user_id', $user->id)
        ->where('plan_id', $plan->id)
        ->firstOrFail();

        $result = [];

        $addedSubCategoryIds = [];

        foreach ($userPlan->userCategories as $userCategory) {
            foreach ($userCategory->userSubCategories as $userSubCategory) {

                // Skip duplicate subcategory entries
                if (in_array($userSubCategory->id, $addedSubCategoryIds)) {
                    continue;
                }

                $addedSubCategoryIds[] = $userSubCategory->id;

                $categoryName = optional($userSubCategory->subCategory)->title;

                $meals = $userSubCategory->userMeals
                    ->where('user_plan_id', $userPlan->id)
                    ->map(function ($userMeal) {
                        $meal = optional($userMeal->meal);
                        return [
                            'id'          => $meal->id,
                            'title'       => $meal->title,
                            'description' => $meal->description,
                            'image_url'   => $meal && $meal->image
                                ? webAssets('storage/' . $meal->image)
                                : 'https://via.placeholder.com/300x200?text=No+Image',
                        ];
                    })
                    ->unique('id') // Remove duplicate meals by ID
                    ->values();

                $result[] = [
                    'user_plan_id'         => $userPlan->id,
                    'user_category_id'     => $userCategory->id,
                    'user_sub_category_id' => $userSubCategory->id,
                    'category_id'          => $userCategory->id ?? null,
                    'id'                   => $userSubCategory->id,
                    'name'                 => $categoryName,
                    'meals'                => $meals,
                ];
            }
        }

        return response()->json([
            'categories' => $result
        ]);
    }

    public function trackClick(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $click = ActivityTracker::click('button_meal_smart_swap', $request->user_id);

        ActivityTracker::log(TrackingType::PRODUCT_SWAP, $request->user_id, [
            'section_element_id' => $click->section_element_id,
            'user_click_id' => $click->id,
            'meal_id' => $request->meal_id ?? null,
            'meal_name' => $request->meal_name ?? null,
            'user_plan_id' => $request->user_plan_id ?? null,
            'user_meal_id' => $request->user_meal_id ?? null,
            'user_category_id' => $request->user_category_id ?? null,
            'user_sub_category_id' => $request->user_sub_category_id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $click,
            'message' => 'Click tracked successfully.',
        ]);
    }
}
