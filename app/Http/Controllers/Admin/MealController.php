<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Meal;
use App\Models\SubCategory; // Import SubCategory model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::with('subCategories')->get(); // Eager load subCategories
        return view('backend.pages.meal.index', compact('meals'));
    }

    public function create()
    {
        $subCategories = SubCategory::all(); // Fetch all subcategories
        return view('backend.pages.meal.form', compact('subCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:subcategories,id', // Validate subcategory IDs
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal = Meal::create($data);

        if ($request->has('sub_categories')) {
            $meal->subCategories()->sync($request->sub_categories); // Sync subcategories
        }

        return redirect()->route('admin.meals.index')->with('success', 'Meal created successfully.');
    }

    public function edit(Meal $meal)
    {
        $subCategories = SubCategory::all(); // Fetch all subcategories
        return view('backend.pages.meal.form', compact('meal', 'subCategories'));
    }

    public function update(Request $request, Meal $meal)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:subcategories,id', // Validate subcategory IDs
        ]);

        if ($request->hasFile('image')) {
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal->update($data);

        if ($request->has('sub_categories')) {
            $meal->subCategories()->sync($request->sub_categories); // Sync subcategories
        }

        return redirect()->route('admin.meals.index')->with('success', 'Meal updated successfully.');
    }

    public function destroy(Meal $meal)
    {
        if ($meal->image) {
            Storage::disk('public')->delete($meal->image);
        }

        $meal->delete();

        return redirect()->route('admin.meals.index')->with('success', 'Meal deleted successfully.');
    }
}
