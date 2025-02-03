<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('backend.pages.coupons.index', compact('coupons'));
    }

    public function create()
    {   
        $plans = \App\Models\Plan::all();
        return view('backend.pages.coupons.form', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'required|integer|min:0',
            'uses_per_user' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'description' => 'nullable',
            'plans' => 'nullable|array', // Accept multiple plan IDs
            'plans.*' => 'exists:plans,id', // Ensure each plan ID exists in the 'plans' table
    
        ]);

        $coupon = Coupon::create($validated);

        // Attach the plans to the coupon
        if (!empty($validated['plans'])) {
            $coupon->plans()->sync($validated['plans']); // Sync adds records to the pivot table
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully!');
    }

    public function edit(Coupon $coupon)
    {
        $plans = \App\Models\Plan::all();
        return view('backend.pages.coupons.form', compact('coupon','plans'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_uses' => 'nullable|integer',
            'plans' => 'nullable|array', // Accept multiple plan IDs
            'plans.*' => 'exists:plans,id', // Ensure each plan ID exists in the 'plans' table
        ]);

        $coupon->update($validated);

        // Attach the plans to the coupon
        if (!empty($validated['plans'])) {
            $coupon->plans()->sync($validated['plans']); // Sync adds records to the pivot table
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully!');
    }
}
