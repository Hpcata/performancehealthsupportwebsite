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
        $items = Item::with('subcategories', 'swapItems')->get();
        return view('backend.pages.item.index', compact('items'));
    }


    public function create()
    {
        $meals = Meal::all(); // Fetch all meals
        $allItems = Item::all(); // Fetch all items for the swap dropdown
        return view('backend.pages.item.form', compact('meals', 'allItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meal_ids' => 'nullable|array',
            'meal_ids.*' => 'exists:meals,id',
            'swap_item_ids' => 'nullable|array',
            'swap_item_ids.*' => 'exists:items,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        // Create item
        $item = Item::create($data);

        // Sync meals
        if (isset($data['meal_ids'])) {
            $item->meals()->sync($data['meal_ids']);
        }

        // Sync swap items
        if (isset($data['swap_item_ids'])) {
            $item->swapItems()->sync($data['swap_item_ids']);
        }

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $meals = Meal::all(); // Fetch all meals
        $allItems = Item::all(); // Fetch all items for the swap dropdown
        return view('backend.pages.item.form', compact('item', 'meals', 'allItems'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meal_ids' => 'nullable|array',
            'meal_ids.*' => 'exists:meals,id',
            'swap_item_ids' => 'nullable|array',
            'swap_item_ids.*' => 'exists:items,id',
            'image' => 'nullable|image|max:2048',
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

        // Sync meals
        if (isset($data['meal_ids'])) {
            $item->meals()->sync($data['meal_ids']);
        }

        // Sync swap items
        if (isset($data['swap_item_ids'])) {
            $item->swapItems()->sync($data['swap_item_ids']);
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
