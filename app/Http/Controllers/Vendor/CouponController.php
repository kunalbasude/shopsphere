<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $vendor = Auth::user()->vendor;
        $coupons = Coupon::where('vendor_id', $vendor->id)->latest()->paginate(15);

        return view('vendor.products.coupons', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_cart_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['vendor_id'] = Auth::user()->vendor->id;

        Coupon::create($validated);

        return redirect()->back()->with('success', 'Coupon created.');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->vendor_id !== Auth::user()->vendor->id) {
            abort(403);
        }

        $coupon->delete();
        return redirect()->back()->with('success', 'Coupon deleted.');
    }
}
