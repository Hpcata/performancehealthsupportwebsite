<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Payment;
use App\Models\SportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPlan;
use App\Models\User;
use App\Models\UserCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityTracker;
use App\Models\TrackingType;
use App\Models\SportGame;
use App\Models\UserPrePlan;
use App\Models\UserItem;
use App\Models\UserItemSwap;
use App\Models\UserItemMeal;
use App\Models\UserMeal;
use App\Models\UserSwapItem;
use App\Models\UserSubCategory;

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

        $sportGameData = null;
        if ($userPrePlan && $userPrePlan->occupation) {
            $occupation = strtolower($userPrePlan->occupation); // "bmx freestyle"

            // Step 1: Try full match first (case-insensitive)
            $sportGame = SportGame::with('categories')
                ->whereRaw('LOWER(name) = ?', [$occupation])
                ->first();

            // Step 2: If no full match, split into keywords and check each
            if (!$sportGame) {
                $keywords = explode(' ', $occupation);

                foreach ($keywords as $keyword) {
                    $sportGame = SportGame::with('categories')
                        ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                        ->first();

                    if ($sportGame) {
                        break; // first matching keyword wins
                    }
                }
            }

            // Step 3: Extract image if found
            $sportGameData = null;

            if ($sportGame && $sportGame->categories->isNotEmpty()) {
                $category = $sportGame->categories->first();

                $sportGameData = [
                    'sport_name' => $sportGame->name,
                    'sport_image' => $category->pivot->image_path ?? null,
                ];
            }

        }

        return view('front.pages.plan-details', compact('userPlans', 'plan', 'user', 'sportGameData'));
    }

    public function mealTimeDetails(Request $request, $id, $plan_id)
    {
        $userPlan = UserPlan::with([
            'plan',
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
        return view('front.pages.sub-category-details', compact('userMealTime','userPlan'));
    }

    public function getMeals(Request $request, $id)
    {
        $request->validate([
            'user_category_id' => 'required|integer',
            'user_plan_id' => 'required|integer',
        ]);

        // Load the UserSubCategory along with related meals and their items
        $categories = UserSubCategory::with('userMeals.meal') // Ensure 'meal' relation is loaded
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
        // Fetch the meal with its items and filtered relationships
        $userMeal = UserMeal::with([
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

        $userPlan = UserPlan::where('id', $request->user_plan_id)
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
            $userItemMeal = UserItemMeal::where('user_id', $userPlan->user_id)
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
        // Validate request
        $request->validate([
            'user_item_id' => 'required|integer',
            'user_plan_id' => 'required|integer',
            'user_category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'user_meal_id' => 'required|integer',
        ]);

        $userPlan = UserPlan::where('id', $request->user_plan_id)
            ->where('status', 'active')
            ->first();

        if (!$userPlan) {
            return response()->json(['message' => 'User plan not found or inactive'], 404);
        }
        $userId = $userPlan->user_id;
        // Fetch UserItem with only required fields and relationships
        $userItem = UserItem::with([
            'userSwapItems' => function ($query) use ($request) {
                $query->where('user_plan_id', $request->user_plan_id)
                    ->where('user_category_id', $request->user_category_id)
                    ->where('user_sub_category_id', $request->sub_category_id)
                    ->where('user_meal_id', $request->user_meal_id)
                    ->select('id', 'swap_item_id', 'user_item_id');
            },
            'userSwapItems.swapItem' => function ($query) {
                $query->select('id', 'title', 'qty', 'unit', 'protein', 'carbs', 'fat','energy', 'description', 'selected_qty_unit', 'image');
            },
            'item' => function ($query) {
                $query->select('id', 'title', 'qty', 'unit', 'protein', 'carbs', 'fat', 'energy', 'description', 'selected_qty_unit', 'image');
            }
        ])
        ->where('id', $request->user_item_id)
        ->where('user_plan_id', $request->user_plan_id)
        ->where('user_category_id', $request->user_category_id)
        ->where('user_sub_category_id', $request->sub_category_id)
        ->where('user_meal_id', $request->user_meal_id)
        ->select('id','user_plan_id', 'user_category_id', 'user_sub_category_id', 'user_meal_id')
        ->first();

        // Check if userItem exists
        if (!$userItem) {
            return response()->json(['message' => 'User item not found.'], 404);
        }

        // Prepare swap items list
        $swapItems = $userItem->userSwapItems->map(function ($swapItem) use ($userId) {
            $item = $swapItem->swapItem;
           
            $userSwapItem = UserItemSwap::where('user_id', $userId)
                        ->where('item_id', $swapItem->user_item_id)
                        ->where('swap_item_id', $swapItem->id)
                        ->first();
            return [
                'swap_item_id' => $item->id ?? null,
                'swap_item_name' => $item->title ?? null,
                'swap_item_qty' => isset($userSwapItem->qty) ? $userSwapItem->qty : ($item->qty ?? null),
                'swap_item_unit' => isset($userSwapItem->unit) ? $userSwapItem->unit : ($item->unit ?? null),
                'swap_item_protein' => isset($userSwapItem->protein) ? $userSwapItem->protein : ($item->protein ?? null),
                'swap_item_carbs' => isset($userSwapItem->carbs) ? $userSwapItem->carbs : ($item->carbs ?? null),
                'swap_item_fat' => isset($userSwapItem->fat) ? $userSwapItem->fat : ($item->fat ?? null),
                'swap_item_energy' => isset($userSwapItem->energy) ? $userSwapItem->energy : ($item->energy ?? null),
                'selected_qty_unit' => is_array($userSwapItem->selected_qty_unit)
                    ? $userSwapItem->selected_qty_unit
                    : json_decode($item->selected_qty_unit, true),
                'swap_item_description' => $item->description ?? null,
                'swap_item_image' => isset($item->image)
                    ? webAssets('storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        $item = $userItem->item;

        return response()->json([
            'item_id' => $item->id ?? null,
            'item_name' => $item->title ?? null,
            'item_image' => $item && $item->image
                ? webAssets('storage/' . $item->image)
                : 'https://via.placeholder.com/300x200?text=No+Image',
            'user_item_id' => $request->user_item_id,
            'items' => $swapItems,
            'item' => [
                'id' => $item->id ?? null,
                'name' => $item->title ?? null,
                'qty' => $item->qty ?? null,
                'unit' => $item->unit ?? null,
                'protein' => $item->protein ?? 0,
                'carbs' => $item->carbs ?? 0,
                'fat' => $item->fat ?? 0,
                'energy' => $item->energy ?? 0,
                'description' => $item->description ?? null,
                'selected_qty_unit' => is_array($item->selected_qty_unit)
                    ? $item->selected_qty_unit
                    : json_decode($item->selected_qty_unit, true),
            ]
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
                $userItemMeal = UserItemMeal::where('meal_id', $mealId)
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

                $userItemSwap = UserItemSwap::where('item_id', $swap['swap_id'])
                    ->where('swap_item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->where('meal_id', $mealId)
                    ->first();

                if (!$userItemSwap) {
                    $userItemSwap = UserItemSwap::where('item_id', $swap['swap_id'])
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

                $existingSwaps = UserItemSwap::where('item_id', $swap['swap_id'])
                    ->where('user_id', $userId)
                    ->where('meal_id', $mealId)
                    ->get();

                if ($existingSwaps->isNotEmpty()) {
                    foreach ($existingSwaps as $existingSwap) {
                        $existingSwap->item_id = $swap['main_id'];
                        $existingSwap->save();
                    }
                } else {
                    $fallbackSwaps = UserItemSwap::where('item_id', $swap['swap_id'])
                        ->where('user_id', $userId)
                        ->get();

                    foreach ($fallbackSwaps as $fallback) {
                        $selectedUnit = is_array($fallback->selected_qty_unit)
                            ? $fallback->selected_qty_unit
                            : json_decode($fallback->selected_qty_unit, true);

                        UserItemSwap::create([
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

                UserItemSwap::where('swap_item_id', $swap['main_id'])
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

                $updateUserItemMeal = UserItemMeal::where('meal_id', $mealId)
                    ->where('item_id', $swap['main_id'])
                    ->where('user_id', $userId)
                    ->first();

                if ($updateUserItemMeal) {
                    $updateUserItemMeal->is_swiped = 0;
                    $updateUserItemMeal->save();
                }

                $userItem = UserItem::where('id', $swap['swap_id'])
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
                    foreach ($swapItems as $swapItem) {
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
                    $userItem = UserItem::where('id', $swap['swap_id'])
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
       
        $pdf = Pdf::loadView('front.pages.plan-pdf', compact('userPlans', 'groupedData'))
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
        $payment = Payment::where('user_id', $request->user_id)->where('plan_id', $id)->first();
        $userPrePlan = UserPrePlan::where('user_id', $request->user_id)->where('payment_id', $payment->id)->first();

        $sportImagePath = null;
       
        if (isset($userPrePlan) && isset($userPrePlan->occupation)) {
            $occupation = strtolower(trim($userPrePlan->occupation));
            
            // Step 1: Full match
            $sportGame = SportGame::with('categories')
                ->whereRaw('LOWER(name) = ?', [$occupation])
                ->first();

            // Step 2: If no full match, try keyword match
            if (!$sportGame) {
                $keywords = explode(' ', $occupation);

                foreach ($keywords as $keyword) {
                    $sportGame = SportGame::with('categories')
                        ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                        ->first();

                    if ($sportGame) {
                        break; // first keyword match wins
                    }
                }
            }

            // Step 3: Get category and image path
            $category = isset($sportGame->categories) ? $sportGame->categories->first() : null;

            if ($category && isset($category->pivot->image_path)) {
                $sportImagePath = $category->pivot->image_path;
            }
        }
        
        $printAllmeal = true;
        return view('front.pages.plan-preview', compact('userPlans', 'printAllmeal', 'sportImagePath'));
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

        $payment = Payment::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->first();
        $userPrePlan = UserPrePlan::where('user_id', $request->user_id)->where('payment_id', $payment->id)->first();

        $sportImagePath = null;
       
        if (isset($userPrePlan) && isset($userPrePlan->occupation)) {
            $occupation = strtolower(trim($userPrePlan->occupation));
            
            // Step 1: Full match
            $sportGame = SportGame::with('categories')
                ->whereRaw('LOWER(name) = ?', [$occupation])
                ->first();

            // Step 2: If no full match, try keyword match
            if (!$sportGame) {
                $keywords = explode(' ', $occupation);

                foreach ($keywords as $keyword) {
                    $sportGame = SportGame::with('categories')
                        ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                        ->first();

                    if ($sportGame) {
                        break; // first keyword match wins
                    }
                }
            }

            // Step 3: Get category and image path
            $category = isset($sportGame->categories) ? $sportGame->categories->first() : null;

            if ($category && isset($category->pivot->image_path)) {
                $sportImagePath = $category->pivot->image_path;
            }
        }

        $printAllmeal = false;

        return view('front.pages.plan-preview', compact('userPlans', 'groupedData', 'printAllmeal', 'sportImagePath'));
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

        $userMealTime = UserCategory::with('userMeals.meal')  // Assuming you want meal info
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

    /**
     * Returns the meal details for a given user meal ID, plan ID, sub category ID, and category ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMealDetails(Request $request)
    {
        $request->validate([
            'user_meal_id' => 'required|integer',
            'user_plan_id' => 'required|integer',
            'user_sub_category_id' => 'required|integer',
            'user_category_id' => 'required|integer',
        ]);

        $isFreeUser = false;
        $userPlan = UserPlan::where('id', $request->user_plan_id)->first();
        if($userPlan->user_id) {
            $user = User::find($userPlan->user_id);
            if($user->free_user) {
                $isFreeUser = true;
            }
        }

        $userMeal = UserMeal::with([
            'meal:id,title,image,description,note',
            'userItems' => function ($query) use ($request) {
                $query->where('user_meal_id', $request->user_meal_id)
                    ->where('user_plan_id', $request->user_plan_id)
                    ->where('user_sub_category_id', $request->user_sub_category_id)
                    ->where('user_category_id', $request->user_category_id)
                    ->with(['item:id,title,protein,carbs,fat,energy,image,qty,unit,selected_qty_unit']);
            }
        ])
        ->select('id', 'user_plan_id', 'user_category_id', 'user_sub_category_id', 'meal_name', 'meal_id')
        ->where('id', $request->user_meal_id)
        ->where('user_plan_id', $request->user_plan_id)
        ->where('user_sub_category_id', $request->user_sub_category_id)
        ->where('user_category_id', $request->user_category_id)
        ->first();

        if (!$userMeal) {
            return response()->json(['message' => 'User meal not found'], 404);
        }

        return response()->json([
            'meal' => $userMeal,
            'totalEnergy' => $userMeal->meal?->getTotalEnergyAttribute() ?? 0,
            'totalProtein' => $userMeal->meal?->getTotalProteinsAttribute() ?? 0,
            'totalCarbs' => $userMeal->meal?->getTotalCarbsAttribute() ?? 0,
            'totalFats' => $userMeal->meal?->getTotalFatsAttribute() ?? 0,
            'isFreeUser' => $isFreeUser,
        ]);
    }

    public function getMealSmartSwaps(Request $request)
    {
        $request->validate([
            'user_meal_id' => 'required|integer',
            'user_plan_id' => 'required|integer',
            'user_sub_category_id' => 'required|integer',
            'user_category_id' => 'required|integer',
        ]);

        // Eager load the item and swapItems without filtering here
        $userItems = UserItem::with([
            'item:id,title,protein,carbs,fat,energy,image,qty,unit,selected_qty_unit,description,note',
            'userSwapItems.swapItem' // Include item for swaps
        ])
        ->where('user_meal_id', $request->user_meal_id)
        ->where('user_plan_id', $request->user_plan_id)
        ->where('user_sub_category_id', $request->user_sub_category_id)
        ->where('user_category_id', $request->user_category_id)
        ->get();

        if ($userItems->isEmpty()) {
            return response()->json(['message' => 'No user items found'], 404);
        }
        $userPlan = UserPlan::where('id', $request->user_plan_id)
            ->where('status', 'active')
            ->first();
        $items = $userItems->map(function ($userItem) use ($request, $userPlan) {
            $item = $userItem->item;
            $userItemMeal = UserItemMeal::where('user_id', $userPlan->user_id)
                ->where('meal_id', $request->user_meal_id)
                ->where('item_id', $userItem->id)
                ->first();

            // Filter swap items manually
            $swapItems = $userItem->userSwapItems
                ->where('user_plan_id', $request->user_plan_id)
                ->where('user_sub_category_id', $request->user_sub_category_id)
                ->where('user_meal_id', $request->user_meal_id)
                ->values();

            return [
                'user_item_id' => $userItem->id,
                'user_meal_id' => $userItem->userMeal->id ?? null,
                'user_category_id' => $userItem->user_category_id,
                'user_sub_category_id' => $userItem->user_sub_category_id,
                'user_plan_id' => $userItem->user_plan_id,
                'id' => $item->id,
                'name' => $item->title,
                'protein' => $item->protein,
                'carbs' => $item->carbs,
                'fat' => $item->fat,
                'energy' => $item->energy,
                'qty' => $userItemMeal->qty,
                'unit' => $userItemMeal->unit,
                'selected_qty_unit' => is_array($userItemMeal->selected_qty_unit)
                    ? $userItemMeal->selected_qty_unit
                    : json_decode($item->selected_qty_unit, true),
                'description' => $item->description,
                'note' => $item->note ?? 'Nil',
                'image' => isset($item->image)
                    ? webAssets('storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',

                // Include filtered swap items (with optional nested item data)
                'swapItems' => $swapItems->map(function ($swap) {
                    return [
                        'swap_item_id' => $swap->id ?? null,
                        'title' => $swap->swapItem->title ?? '',
                        'image' => isset($swap->swapItem->image)
                            ? webAssets('storage/' . $swap->swapItem->image)
                            : 'https://via.placeholder.com/300x200?text=No+Image',
                        
                    ];
                }),
            ];
        });

        return response()->json(['items' => $items]);
    }

}
