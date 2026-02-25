<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'options' => 'required|array|min:1',
            'options.*.attribute_name' => 'required|string|max:50',
            'options.*.attribute_value' => 'required|string|max:100',
        ]);

        $sku = 'SS-' . strtoupper(Str::random(8));
        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = 'SS-' . strtoupper(Str::random(8));
        }

        $variant = $product->variants()->create([
            'name' => $request->name,
            'sku' => $sku,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        foreach ($request->options as $option) {
            $variant->options()->create($option);
        }

        return redirect()->back()->with('success', 'Variant added.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        if ($variant->product->vendor_id !== Auth::user()->vendor->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $variant->update($request->only('name', 'price', 'quantity'));
        return redirect()->back()->with('success', 'Variant updated.');
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->product->vendor_id !== Auth::user()->vendor->id) {
            abort(403);
        }

        $variant->delete();
        return redirect()->back()->with('success', 'Variant deleted.');
    }
}
