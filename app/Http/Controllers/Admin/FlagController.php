<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flag;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class FlagController extends Controller
{
    public function index()
    {
        $flags = Flag::get();
        return view('backend.pages.flags.index', compact('flags'));
    }

    public function create()
    {
        return view('backend.pages.flags.form');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            // 'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        // dd($request->all());
        $data = ['name' => $request->name];

        Flag::create($data);

        return redirect()->route('admin.flags.index')->with('success', 'Flag created successfully.');
    }

    public function edit(Flag $flag)
    {
        return view('backend.pages.flags.form', compact('flag'));
    }

    public function update(Request $request, Flag $flag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $flag->name = $request->name;
        $flag->save();

        return redirect()->route('admin.flags.index')->with('success', 'Flag updated successfully.');
    }

    public function destroy(Flag $flag)
    {
        $flag->delete();

        return redirect()->route('admin.flags.index')->with('success', 'Flag deleted.');
    }

    public function removeFood(Flag $flag, $item)
    {
        // Remove the food from the flag
        if (!$flag->items->contains($item)) {
            return response()->json(['success' => false, 'message' => 'Item not associated with this flag.'], 404);
        }

        $flag->items()->detach($item);

        return response()->json(['success' => true]);
    }

    public function addFoods(Request $request, Flag $flag)
    {
        // Add selected foods to the flag
        $flag->items()->attach($request->foods);

        return response()->json(['success' => true]);
    }

    public function foodLists(Request $request)
    {
        $query = Item::query();

        if ($request->has('query')) {
            $query->where('title', 'like', '%' . $request->query('query') . '%');
        }

        if ($request->has('exclude_ids')) {
            $excludeIds = explode(',', $request->query('exclude_ids'));
            $query->whereNotIn('id', $excludeIds);
        }

        return response()->json([
            'items' => $query->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $item->icon,
                ];
            }),
        ]);
    }

}
