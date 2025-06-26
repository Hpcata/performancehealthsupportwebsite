<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('order', 'asc')->get(); // Fetch all MealTime records ordered by 'order' field
        return view('backend.pages.mealtime.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.mealtime.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            // 'time' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order' => 'nullable|integer', // Optional order field
        ];

        // Define custom error messages (optional)
        $messages = [
            'title.required' => 'The title is mandatory.',
            'image.image' => 'The uploaded file must be an image.',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check for validation errors
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator) // Pass validation errors
                ->withInput();          // Retain old input values
        }

        try {
            // Handle image upload if a new file is provided
            $validatedData = $validator->validated();

            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('categories', 'public');
            }
    
            Category::create($validatedData);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       // dd($id);
       $category = Category::findOrFail($id); // Fetch the Category record
       return view('backend.pages.mealtime.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Fetch the Category record by ID
        $category = Category::findOrFail($id);

        // Define validation rules
        $rules = [
            'title' => 'required|string|max:255',
            // 'time' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer', // Optional order field

        ];

        // Define custom error messages (optional)
        $messages = [
            'title.required' => 'The title is mandatory.',
            'image.image' => 'The uploaded file must be an image.',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check for validation errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // Pass validation errors
                ->withInput();          // Retain old input values
        }

        try {
            // Handle image upload if a new file is provided
            $validatedData = $validator->validated();

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                // Store the new image
                $validatedData['image'] = $request->file('image')->store('categories', 'public');
            }

            // Update the Category record with validated data
            $category->update($validatedData);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        
        try {
            $category = Category::findOrFail($id);
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            \Log::error('Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Deletion failed!');
        }
    }
}
