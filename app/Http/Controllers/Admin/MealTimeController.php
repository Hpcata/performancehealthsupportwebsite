<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MealTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $rules = [
            'title' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Define custom error messages (optional)
        $messages = [
            'title.required' => 'The title is mandatory.',
            'time.required' => 'The time field is required.',
            'time.date_format' => 'The time must be in the format HH:mm.',
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
                $validatedData['image'] = $request->file('image')->store('meal_times', 'public');
            }
    
            MealTime::create($validatedData);

            return redirect()->route('admin.meal-times.index')
                ->with('success', 'Meal Time updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, MealTime $mealTime)
    {
       // dd($id);
       $mealTime = MealTime::findOrFail($id); // Fetch the MealTime record
       return view('backend.pages.mealtime.form', compact('mealTime'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MealTime $mealTime)
    {
        // Fetch the MealTime record by ID
        $mealTime = MealTime::findOrFail($request->id);

        // Define validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Define custom error messages (optional)
        $messages = [
            'title.required' => 'The title is mandatory.',
            'time.required' => 'The time field is required.',
            'time.date_format' => 'The time must be in the format HH:mm.',
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
                if ($mealTime->image) {
                    Storage::disk('public')->delete($mealTime->image);
                }
                // Store the new image
                $validatedData['image'] = $request->file('image')->store('meal_times', 'public');
            }

            // Update the MealTime record with validated data
            $mealTime->update($validatedData);

            return redirect()->route('admin.meal-times.index')
                ->with('success', 'Meal Time updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
