<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function show($id)
    {
        $plan = Plan::with('categories')->findOrFail($id);
        return view('front.plan-details', compact('plan'));
    }

    public function getSubCategories($id)
    {
        $category = Category::with('subCategories')->findOrFail($id);

        $items = $category->subCategories->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->title,
                'description' => $item->description,
                'image' => $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image'
            ];
        });

        return response()->json(['subcategories' => $items]);
    }

    public function getSubcategoryItems($id)
    {
        // Fetch the subcategory with its items
        $subcategory = SubCategory::with('items')->findOrFail($id);
        // Map the items into a response-friendly structure
        $items = $subcategory->items->map(function ($item) {
            return [
                'name' => $item->title,
                'price' => $item->price,
                'description' => $item->description,
                'image' => $item->image
                    ? asset('storage/' . $item->image)
                    : 'https://via.placeholder.com/300x200?text=No+Image',
            ];
        });

        // Return the response
        return response()->json(['subcategory' => $subcategory->title, 'items' => $items]);
    }

}
