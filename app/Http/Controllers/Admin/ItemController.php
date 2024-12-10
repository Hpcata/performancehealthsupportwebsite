<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('subcategories')->get();
        return view('backend.pages.item.index', compact('items'));
    }

    public function create()
    {
        $subcategories = Subcategory::all();
        return view('backend.pages.item.form', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'subcategory_ids' => 'required|array',
            'subcategory_ids.*' => 'exists:subcategories,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        $item->subcategories()->sync($data['subcategory_ids']);

        return redirect()->route('admin.items.index');
    }

    public function edit(Item $item)
    {
        $subcategories = Subcategory::all();
        return view('backend.pages.item.form', compact('item', 'subcategories'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'subcategory_ids' => 'required|array',
            'subcategory_ids.*' => 'exists:subcategories,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        $item->subcategories()->sync($data['subcategory_ids']);

        return redirect()->route('admin.items.index');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
