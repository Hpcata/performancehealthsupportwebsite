<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCategories = SubCategory::with('categories')->get();
        return view('backend.pages.category.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.pages.category.form', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mealtime_ids' => 'required|array',
            'mealtime_ids.*' => 'exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Create the category
        $subCategory = SubCategory::create($data);

        // Attach the selected mealtimes
        $subCategory->categories()->sync($request->mealtime_ids);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $subCategory->image = $request->file('image')->store('sub_categories', 'public');
            $subCategory->save();
        }

        return redirect()->route('admin.categories.index')->with('success', 'SubCategory created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\SubCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $subCategory)
    {
        // dd($subCategory);
        $categories = Category::all();
        // dd($subcategory->categories->pluck('id')->toArray());
        return view('backend.pages.category.form', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $data = $request->validate([
            'mealtime_ids' => 'required|array',
            'mealtime_ids.*' => 'exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Update the category
        $subCategory->update($data);

        // Sync the selected mealtimes
        $subCategory->categories()->sync($request->mealtime_ids);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($subCategory->image) {
                Storage::delete('public/' . $subCategory->image);
            }
            $subCategory->image = $request->file('image')->store('sub_categories', 'public');
            $subCategory->save();
        }

        return redirect()->route('admin.categories.index')->with('success', 'SubCategory updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\SubCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        // Delete the category image if exists
        if ($subCategory->image) {
            Storage::delete('public/' . $subCategory->image);
        }

        // Detach the mealtimes and delete the subCategory
        $subCategory->categories()->detach();
        $subCategory->delete();

        return redirect()->route('admin.categories.index')->with('success', 'SubCategory deleted successfully.');
    }
}
