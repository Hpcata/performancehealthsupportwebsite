<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SportCategory;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class SportCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = SportCategory::all();
            return view('backend.pages.sport-categories.index', compact('categories'));
        } catch (Exception $e) {
            Log::error('Error fetching sport categories: ' . $e->getMessage());
            return back()->with('error', 'Failed to load sport categories.');
        }
    }

    public function create()
    {
        return view('backend.pages.sport-categories.form');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        try {
            SportCategory::create($request->all());
            return redirect()->route('admin.sports-categories.index')->with('success', 'Sport Category created.');
        } catch (Exception $e) {
            Log::error('Error creating sport category: ' . $e->getMessage());
            return back()->with('error', 'Failed to create sport category.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = SportCategory::findOrFail($id);
            return view('backend.pages.sport-categories.form', compact('category'));
        } catch (Exception $e) {
            Log::error('Error loading category for edit: ' . $e->getMessage());
            return redirect()->route('admin.sports-categories.index')->with('error', 'Sport Category not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        try {
            $category = SportCategory::findOrFail($id);
            $category->update($request->only('name'));

            return redirect()->route('admin.sports-categories.index')->with('success', 'Sport Category updated.');
        } catch (Exception $e) {
            Log::error('Error updating sport category: ' . $e->getMessage());
            return back()->with('error', 'Failed to update sport category.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = SportCategory::findOrFail($id);
            $category->delete();

            return redirect()->route('admin.sports-categories.index')->with('success', 'Sport Category deleted.');
        } catch (Exception $e) {
            Log::error('Error deleting sport category: ' . $e->getMessage());
            return redirect()->route('admin.sports-categories.index')->with('error', 'Failed to delete sport category.');
        }
    }
}
