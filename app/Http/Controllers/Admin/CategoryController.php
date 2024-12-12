<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MealTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('mealtimes')->get();
        return view('backend.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mealtimes = Mealtime::all();
        return view('backend.pages.category.form', compact('mealtimes'));
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
            'mealtime_ids.*' => 'exists:meal_times,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Create the category
        $category = Category::create($data);

        // Attach the selected mealtimes
        $category->mealtimes()->sync($request->mealtime_ids);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store('categories', 'public');
            $category->save();
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $mealtimes = MealTime::all();
        return view('backend.pages.category.form', compact('category', 'mealtimes'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'mealtime_ids' => 'required|array',
            'mealtime_ids.*' => 'exists:meal_times,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Update the category
        $category->update($data);

        // Sync the selected mealtimes
        $category->mealtimes()->sync($request->mealtime_ids);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image) {
                Storage::delete('public/' . $category->image);
            }
            $category->image = $request->file('image')->store('categories', 'public');
            $category->save();
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Delete the category image if exists
        if ($category->image) {
            Storage::delete('public/' . $category->image);
        }

        // Detach the mealtimes and delete the category
        $category->mealtimes()->detach();
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
