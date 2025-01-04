<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Meal;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('meals', 'swapItems')->get();
        return view('backend.pages.item.index', compact('items'));
    }


    public function create()
    {
        $meals = Meal::all(); // Fetch all meals
        $allItems = Item::where('is_swiped',0)->get(); // Fetch all items for the swap dropdown
        return view('backend.pages.item.form', compact('meals', 'allItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'qty' => 'nullable|string',
            'is_swiped' => 'required|boolean',
            'meal_ids' => 'nullable|array',
            'meal_ids.*' => 'exists:meals,id',
            'swap_item_ids' => 'nullable|array',
            'swap_item_ids.*' => 'exists:items,id',
            'image' => 'nullable|image|max:2048',
            'protein' => 'nullable|numeric',
            'carbs' => 'nullable|numeric',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        // Create item
        $item = Item::create($data);

        // Sync swap items
        if ($request->is_swiped == 1 && $request->has('swap_item_ids')) {
            $item->swapItems()->sync($request->swap_item_ids);

            $userIds = \DB::table('user_item_swaps')
                            ->distinct()
                            ->pluck('user_id');
                            
            if ($userIds->isNotEmpty()) {
                foreach ($userIds as $userId) {
                    // Check if the user has an active plan
                    $hasActivePlan = \DB::table('user_plans')
                        ->where('user_id', $userId)
                        ->where('status', 'active') // Assuming 'status' indicates if the plan is active
                        ->exists();
        
                    // Only proceed if the user has an active plan
                    if ($hasActivePlan) {
                        foreach ($request->swap_item_ids as $swapItemId) {
                            $exists = \DB::table('user_item_swaps')
                                ->where('user_id', $userId)
                                ->where('item_id', $swapItemId)
                                ->where('swap_item_id', $item->id)
                                ->exists();
            
                            if (!$exists) {
                                \DB::table('user_item_swaps')->insert([
                                    'user_id' => $userId,
                                    'item_id' => $swapItemId,
                                    'swap_item_id' => $item->id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
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
        $meals = Meal::all(); // Fetch all meals
        $allItems = Item::where('is_swiped',0)->get(); // Fetch all items for the swap dropdown
        return view('backend.pages.item.form', compact('item', 'meals', 'allItems'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'qty' => 'nullable|string',
            'is_swiped' => 'required|boolean',
            'meal_ids' => 'nullable|array',
            'meal_ids.*' => 'exists:meals,id',
            'swap_item_ids' => 'nullable|array',
            'swap_item_ids.*' => 'exists:items,id',
            'image' => 'nullable|image|max:2048',
            'protein' => 'nullable|numeric',
            'carbs' => 'nullable|numeric',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($item->image) {
                \Storage::delete('public/' . $item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        // Update item
        $item->update($data);

        if ($request->is_swiped == 1) {
            // Sync the swap items (this will attach new ones and detach the old ones)
            if ($request->has('swap_item_ids')) {
                $item->swapItems()->sync($request->swap_item_ids);

                $userIds = \DB::table('user_item_swaps')
                            ->distinct()
                            ->pluck('user_id');

                if ($userIds->isNotEmpty()) {
                    foreach ($userIds as $userId) {
                        // Check if the user has an active plan
                        $hasActivePlan = \DB::table('user_plans')
                            ->where('user_id', $userId)
                            ->where('status', 'active') // Assuming 'status' indicates if the plan is active
                            ->exists();
            
                        // Only proceed if the user has an active plan
                        if ($hasActivePlan) {
                            foreach ($request->swap_item_ids as $swapItemId) {
                                $exists = \DB::table('user_item_swaps')
                                    ->where('user_id', $userId)
                                    ->where('item_id', $swapItemId)
                                    ->where('swap_item_id', $item->id)
                                    ->exists();
            
                                if (!$exists) {
                                    \DB::table('user_item_swaps')->insert([
                                        'user_id' => $userId,
                                        'item_id' => $swapItemId,
                                        'swap_item_id' => $item->id,
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

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        // Delete item image if exists
        if ($item->image) {
            \Storage::delete('public/' . $item->image);
        }

        // Detach subcategories and swap items
        $item->subcategories()->detach();
        $item->swapItems()->detach();

        // Delete item
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
