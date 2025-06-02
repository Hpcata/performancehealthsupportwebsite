<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::get();
        return view('backend.pages.tag.index', compact('tags'));
    }

    public function create()
    {
        return view('backend.pages.tag.form');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        // dd($request->all());
        $data = ['name' => $request->name];

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('tag_icons', 'public');
            $data['icon'] = $path;
        }

        Tag::create($data);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        return view('backend.pages.tag.form', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $tag->name = $request->name;

        if ($request->hasFile('icon')) {
            // Remove old icon if exists
            if ($tag->icon && Storage::disk('public')->exists($tag->icon)) {
                Storage::disk('public')->delete($tag->icon);
            }

            $path = $request->file('icon')->store('tag_icons', 'public');
            $tag->icon = $path;
        }

        $tag->save();

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        if ($tag->icon && Storage::disk('public')->exists($tag->icon)) {
            Storage::disk('public')->delete($tag->icon);
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted.');
    }
}
