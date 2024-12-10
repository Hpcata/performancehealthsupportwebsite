<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the subcategories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCategories = SubCategory::with('categories')->get();
        return view('backend.pages.subcategory.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new subcategory.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.pages.subcategory.form', compact('categories'));
    }

    /**
     * Store a newly created subcategory in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Create the subcategory
        $subCategory = SubCategory::create($data);

        // Attach the selected categories
        $subCategory->categories()->sync($request->category_ids);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $subCategory->image = $request->file('image')->store('subcategories', 'public');
            $subCategory->save();
        }

        return redirect()->route('admin.subcategories.index')->with('success', 'SubCategory created successfully.');
    }

    /**
     * Show the form for editing the specified subcategory.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id, SubCategory $subCategory)
    {
        $categories = Category::all();
        $subCategory = SubCategory::findOrFail($id);

        return view('backend.pages.subcategory.form', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified subcategory in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $data = $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($subCategory->image) {
                Storage::delete('public/' . $subCategory->image);
            }
            $data['image'] = $request->file('image')->store('subcategories', 'public');
            //  $subCategory->save();
        }

        // Update the subcategory
        $subCategory->update($data);
        
        // Sync the selected categories
        $subCategory->categories()->sync($request->category_ids);

        return redirect()->route('admin.subcategories.index')->with('success', 'SubCategory updated successfully.');
    }

    /**
     * Remove the specified subcategory from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        // Delete the subcategory image if exists
        if ($subCategory->image) {
            Storage::delete('public/' . $subCategory->image);
        }

        // Detach the categories and delete the subcategory
        $subCategory->categories()->detach();
        $subCategory->delete();

        return redirect()->route('admin.subcategories.index')->with('success', 'SubCategory deleted successfully.');
    }
}
