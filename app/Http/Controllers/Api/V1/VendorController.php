<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::where('status', 'approved')
            ->with('user:id,name')
            ->withCount('products')
            ->paginate(15);

        return response()->json($vendors);
    }

    public function show(string $slug)
    {
        $vendor = Vendor::where('slug', $slug)
            ->where('status', 'approved')
            ->with('user:id,name')
            ->withCount('products')
            ->firstOrFail();

        return response()->json($vendor);
    }

    public function products(string $slug)
    {
        $vendor = Vendor::where('slug', $slug)->where('status', 'approved')->firstOrFail();
        $products = $vendor->products()->active()->with('images')->paginate(15);

        return response()->json($products);
    }
}
