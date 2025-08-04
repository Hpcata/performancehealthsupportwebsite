<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use App\Models\FoodCategory;
use App\Models\Item;
use App\Models\Meal;
use App\Models\Tag;
use App\Models\UserItemSwap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query  = $request->input('query');
            $foodId = $request->input('food_id') ?? null;
            if ($foodId) {
                $items = Item::with('flags')->where('id', $foodId)
                    ->orderBy('updated_at', 'DESC')
                    ->first();
            } else {
                $items = Item::with('category', 'flags')
                    ->where(function ($q) use ($query) {
                        $words = preg_split('/\s+/', trim($query)); // Split query into words

                        foreach ($words as $word) {
                            $q->where(function ($subQ) use ($word) {
                                $subQ->where('title', 'LIKE', '%' . $word . '%')
                                    ->orWhereHas('category', function ($catQ) use ($word) {
                                        $catQ->where('name', 'LIKE', '%' . $word . '%');
                                    });
                            });
                        }
                    })
                    ->orderBy('updated_at', 'DESC')
                    ->get();
            }

            return response()->json(['items' => $items]);
        }

        $items = Item::with('meals', 'swapItems', 'flags')->orderBy('updated_at', 'DESC')->get();
        return view('backend.pages.item.index', compact('items'));
    }

    public function create()
    {
        $meals      = Meal::all();                        // Fetch all meals
        $allItems   = Item::where('is_swiped', 0)->get(); // Fetch all items for the swap dropdown
        $categories = FoodCategory::all();
        $tags       = Tag::all();  // Fetch all meals
        $flags      = Flag::all(); // Fetch all meals

        return view('backend.pages.item.form', compact('meals', 'allItems', 'categories', 'tags', 'flags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255|unique:items',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'qty'               => 'required|string',
            'is_swiped'         => 'required|boolean',
            'meal_ids'          => 'nullable|array',
            'meal_ids.*'        => 'exists:meals,id',
            'swap_item_ids'     => 'nullable|array',
            'swap_item_ids.*'   => 'exists:items,id',
            'image'             => 'nullable|image|max:2048',
            'protein'           => 'nullable|numeric',
            'carbs'             => 'nullable|numeric',
            'fat'               => 'nullable|numeric',
            'serving_per_pack'  => 'nullable|numeric',
            'serving_size'      => 'required|numeric',
            'category_id'       => 'nullable',
            'serving_size_unit' => 'required',
            'unit'              => 'required',
            'note'              => 'nullable',
            'energy'            => 'nullable',
            'saturated'         => 'nullable',
            'sugars'            => 'nullable',
            'dietary_fibre'     => 'nullable',
            'sodium'            => 'nullable',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        if ($request->has('is_locked')) {
            $data['is_locked'] = $request->is_locked;
        } else {
            $data['is_locked'] = 0;
        }

        if ($request->has('selected_qty_unit') && $request->selected_qty_unit != null) {
            $rawSelectedUnit = $request->selected_qty_unit;

            if (is_string($rawSelectedUnit)) {
                // Clean and decode the string in case it's escaped

                $cleaned = trim($rawSelectedUnit, '"'); // remove outer quotes
                $decoded = json_decode(stripslashes($cleaned), true);

            } elseif (is_array($rawSelectedUnit)) {
                $decoded = $rawSelectedUnit;

            } else {
                $decoded = [];
            }

            $data['selected_qty_unit'] = ($decoded);
        }

        // Create item
        $item = Item::create($data);
        $item->tags()->sync($request->input('tag_ids'));   // attaches tags via pivot
        $item->flags()->sync($request->input('flag_ids')); // attaches tags via pivot

        // Sync swap items
        if ($request->is_swiped == 1 && $request->has('swap_item_ids')) {
            $item->swapItems()->sync($request->swap_item_ids);

            $userIds = DB::table('user_item_swaps')
                ->distinct()
                ->pluck('user_id');

            if ($userIds->isNotEmpty()) {
                foreach ($userIds as $userId) {
                    // Check if the user has an active plan
                    $hasActivePlan = DB::table('user_plans')
                        ->where('user_id', $userId)
                        ->where('status', 'active') // Assuming 'status' indicates if the plan is active
                        ->exists();

                    // Only proceed if the user has an active plan
                    if ($hasActivePlan) {
                        foreach ($request->swap_item_ids as $swapItemId) {

                            $swapItem = Item::find($swapItemId);

                            $exists = DB::table('user_item_swaps')
                                ->where('user_id', $userId)
                                ->where('item_id', $item->id)
                                ->where('swap_item_id', $swapItemId)
                                ->exists();

                            if (! $exists) {
                                DB::table('user_item_swaps')->insert([
                                    'user_id'           => $userId,
                                    'item_id'           => $item->id,
                                    'swap_item_id'      => $swapItemId,
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
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $meals      = Meal::all(); // Fetch all meals
        $allItems   = Item::where('is_swiped', 0)->get(); // Fetch all items for the swap dropdown
        $categories = FoodCategory::all();
        $tags       = Tag::all();  // Fetch all meals
        $flags      = Flag::all(); // Fetch all meals

        return view('backend.pages.item.form', compact('item', 'meals', 'allItems', 'categories', 'tags', 'flags'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'qty'               => 'nullable|string',
            'is_swiped'         => 'required|boolean',
            'meal_ids'          => 'nullable|array',
            'meal_ids.*'        => 'exists:meals,id',
            'swap_item_ids'     => 'nullable|array',
            'swap_item_ids.*'   => 'exists:items,id',
            'image'             => 'nullable|image|max:2048',
            'protein'           => 'nullable|numeric',
            'carbs'             => 'nullable|numeric',
            'fat'               => 'nullable|numeric',
            'serving_per_pack'  => 'nullable|numeric',
            'serving_size'      => 'nullable|numeric',
            'category_id'       => 'nullable',
            'serving_size_unit' => 'nullable',
            'unit'              => 'nullable',
            'note'              => 'nullable',
            'energy'            => 'nullable',
            'saturated'         => 'nullable',
            'sugars'            => 'nullable',
            'dietary_fibre'     => 'nullable',
            'sodium'            => 'nullable',
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($item->image) {
                Storage::delete('public/' . $item->image);
            }
            $path = $request->file('image')->store('items', 'public'); // Store image

            $data['image'] = $path;
        }

        if ($request->has('selected_qty_unit') && $request->selected_qty_unit != null) {
            $rawSelectedUnit = $request->selected_qty_unit;

            if (is_string($rawSelectedUnit)) {
                // Clean and decode the string in case it's escaped
                $cleaned = trim($rawSelectedUnit, '"'); // remove outer quotes
                $decoded = json_decode(stripslashes($cleaned), true);
            } elseif (is_array($rawSelectedUnit)) {
                $decoded = $rawSelectedUnit;
            } else {
                $decoded = [];
            }

            // Re-encode to proper JSON format to store in DB
            $data['selected_qty_unit'] = ($decoded);
        }

        if ($request->has('is_locked')) {
            $data['is_locked'] = $request->is_locked;
        } else {
            $data['is_locked'] = 0;
        }
        // Update item
        $item->update($data);
        $item->tags()->sync($request->input('tag_ids'));   // attaches tags via pivot
        $item->flags()->sync($request->input('flag_ids')); // attaches tags via pivot

        if ($request->is_swiped == 1) {
            // Sync the swap items (this will attach new ones and detach the old ones)
            if ($request->has('swap_item_ids')) {
                $item->swapItems()->sync($request->swap_item_ids);
                $userIds = DB::table('user_item_swaps')
                    ->distinct()
                    ->pluck('user_id');

                if ($userIds->isNotEmpty()) {
                    foreach ($userIds as $userId) {
                        // Check if the user has an active plan
                        $hasActivePlan = DB::table('user_plans')
                            ->where('user_id', $userId)
                            ->where('status', 'active') // Assuming 'status' indicates if the plan is active
                            ->exists();

                        // Only proceed if the user has an active plan
                        if ($hasActivePlan) {
                            foreach ($request->swap_item_ids as $swapItemId) {
                                $swapItem = Item::find($swapItemId);

                                $exists = UserItemSwap::where('user_id', $userId)
                                    ->where('item_id', $item->id)
                                    ->where('swap_item_id', $swapItemId)
                                    ->first();

                                if (! $exists) {
                                    DB::table('user_item_swaps')->insert([
                                        'user_id'           => $userId,
                                        'item_id'           => $item->id,
                                        'swap_item_id'      => $swapItemId,
                                        'qty'               => $swapItem->qty,
                                        'unit'              => $swapItem->unit,
                                        'carbs'             => $swapItem->carbs,
                                        'fat'               => $swapItem->fat,
                                        'protein'           => $swapItem->protein,
                                        'selected_qty_unit' => is_array($swapItem->selected_qty_unit)
                                        ? json_encode($swapItem->selected_qty_unit)
                                        : $swapItem->selected_qty_unit,
                                        // 'protein' => $item->protein,
                                        'created_at'        => now(),
                                        'updated_at'        => now(),
                                    ]);
                                } else {
                                    $exists->qty               = $swapItem->qty;
                                    $exists->unit              = $swapItem->unit;
                                    $exists->carbs             = $swapItem->carbs;
                                    $exists->fat               = $swapItem->fat;
                                    $exists->protein           = $swapItem->protein;
                                    $exists->selected_qty_unit = $swapItem->selected_qty_unit;
                                    $exists->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        if (! $item->isDeletable()) {
            Log::warning("Attempt to delete item with ID {$item->id} that is in use.");
            return redirect()->route('admin.items.index')
                ->with('error', "Unable to delete this food. It is still linked to other records.");
        }

        // Delete item image if exists
        if ($item->image) {
            Storage::delete('public/' . $item->image);
        }

        // Delete item image if exists
        if ($item->image) {
            Storage::delete('public/' . $item->image);
        }

        // Detach pivot relationships
        $item->meals()->detach();
        $item->swapItems()->detach();
        $item->tags()->detach();
        $item->flags()->detach();

        // Delete the item
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }

    public function searchForm()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = strtolower($request->query('query'));

        // Extract nutritional values from query
        $searchValues = [
            'protein'      => $this->extractValue($query, 'protein'),
            'carbohydrate' => $this->extractValue($query, 'carbs|carbohydrate'),
            'fat'          => $this->extractValue($query, 'fat'),
        ];

        // Dynamic Search Conditions
        $foods = Item::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $key => $value) {
                if ($value !== null) {
                    $q->where($key, '=', $value);
                }
            }
        })->get();

        return response()->json(['foods' => $foods]);
    }

    // Extract value from query string using pattern matching
    private function extractValue($query, $term)
    {
        preg_match("/{$term}\s*[-:]?\s*([\d.]+)\s*g?/i", $query, $matches);
        return isset($matches[1]) ? (float) $matches[1] : null;
    }

    public function getFoodDetails(Request $request)
    {
        $foodId = $request->input('food_id');
        if (! $foodId) {
            return response()->json(['error' => 'Food ID is required'], 400);
        }

        $item = Item::with(['flags:id,name', 'category:id,name']) // Load only necessary fields from flags
            ->select('id', 'title', 'category_id')                    // Select only needed item fields
            ->find($foodId);

        if (! $item) {
            return response()->json(['error' => 'Food not found'], 404);
        }
        return response()->json([
            'item' => [
                'id'       => $item->id,
                'title'    => $item->title,
                'flags'    => $item->flags,    // Only id and name from related flags
                'category' => $item->category, // Only id and name from related flags
            ],
        ]);
    }

    public function getFoodDetailsBatch(Request $request)
    {
        $foodIds = $request->input('food_ids');
        $foodIds = explode(',', $foodIds);
        if (!$foodIds) {
            return response()->json(['error' => 'Food IDs is required'], 400);
        }

        // Fetch items with related flags and category in one query
        $items = Item::with([
            'flags:id,name', // Load only id and name from flags
            'category:id,name' // Load only id and name from category
        ])
            ->select('id', 'title', 'category_id') // Select only needed item fields
            ->whereIn('id', $foodIds)
            ->get()
            ->keyBy('id');

        // Prepare response with items keyed by ID
        $response = ['items' => []];

        foreach ($foodIds as $foodId) {
            $item = $items->get($foodId);
            $response['items'][$foodId] = $item ? [
                'id' => $item->id,
                'title' => $item->title,
                'flags' => $item->flags,
                'category' => $item->category,
            ] : null;
        }

        // If no items found, return error
        if ($items->isEmpty()) {
            return response()->json(['error' => 'No items found'], 404);
        }

        return response()->json($response);
    }
}
