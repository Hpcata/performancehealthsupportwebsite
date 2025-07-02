<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SportCategory;
use Illuminate\Http\Request;

class SportCategoryController extends Controller
{
    public function index() {
        $categories = SportCategory::all();
        return view('backend.pages.sport-categories.index', compact('categories'));
    }

    public function create() {
        return view('backend.pages.sport-categories.form');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        SportCategory::create($request->all());
        return redirect()->route('admin.sports-categories.index')->with('success', 'Sport Category created.');
    }

    public function edit($id)
    {
        $category = SportCategory::findOrFail($id);
        return view('backend.pages.sport-categories.form', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        $category = SportCategory::findOrFail($id);
        $category->update($request->only('name'));

        return redirect()->route('admin.sports-categories.index')->with('success', 'Sport Category updated.');
    }

    public function destroy($id)
    {
        $category = SportCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.sports-categories.index')->with('success', 'Sport Category deleted.');
    }
}
