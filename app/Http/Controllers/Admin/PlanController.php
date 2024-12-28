<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MealTime;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::with('user')->latest()->paginate(10);
        return view('backend.pages.plan.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mealTimes = MealTime::all(); // Fetch all meal times
        $subPlans = Plan::all();
        return view('backend.pages.plan.form', compact('mealTimes', 'subPlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meal_times' => 'nullable|array', // Validate meal times
            'meal_times.*' => 'exists:meal_times,id',
        ]);
    
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('plans', 'public');
        }
    
        $plan = Plan::create($validated);
    
        if ($request->has('meal_times')) {
            $plan->mealTimes()->sync($request->meal_times); // Sync meal times
        }

        // Sync Sub-Plans
        $plan->subPlans()->sync($data['sub_plan_ids'] ?? []);
    
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }
    
    public function edit(Plan $plan)
    {
        $mealTimes = MealTime::all(); // Fetch all meal times
        $subPlans = Plan::where('id', '!=', $plan->id)->get();
        return view('backend.pages.plan.form', compact('plan', 'mealTimes','subPlans'));
    }
    
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meal_times' => 'nullable|array',
            'meal_times.*' => 'exists:meal_times,id',
        ]);
        // dd($request->all());
        if ($request->hasFile('image')) {
            if ($plan->image) {
                Storage::disk('public')->delete($plan->image);
            }
            $validated['image'] = $request->file('image')->store('plans', 'public');
        }
    
        $plan->update($validated);
    
        if ($request->has('meal_times')) {
            $plan->mealTimes()->sync($request->meal_times); // Sync meal times
        }

        // Sync Sub-Plans
        $plan->subPlans()->sync($request->sub_plan_ids ?? []);
        
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        if ($plan->image) {
            Storage::disk('public')->delete($plan->image);
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
