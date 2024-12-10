<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MealTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mealTimes = MealTime::latest()->paginate(10);
        return view('backend.pages.mealtime.index', compact('mealTimes'));
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('meal_times', 'public');
        }

        MealTime::create($validated);

        return redirect()->route('admin.meal-times.index')->with('success', 'Meal Time created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MealTime $mealTime)
    {
        return view('backend.pages.mealtime.form', compact('mealTime'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MealTime $mealTime)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($mealTime->image) {
                Storage::disk('public')->delete($mealTime->image);
            }
            $validated['image'] = $request->file('image')->store('meal_times', 'public');
        }

        $mealTime->update($validated);

        return redirect()->route('admin.meal-times.index')->with('success', 'Meal Time updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealTime $mealTime)
    {
        if ($mealTime->image) {
            Storage::disk('public')->delete($mealTime->image);
        }

        $mealTime->delete();

        return redirect()->route('admin.meal-times.index')->with('success', 'Meal Time deleted successfully.');
    }
}
