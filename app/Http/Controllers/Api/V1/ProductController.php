<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()
            ->with('images', 'vendor:id,shop_name,slug', 'category:id,name,slug')
            ->when($request->category, fn($q, $c) => $q->where('category_id', $c))
            ->when($request->search, fn($q, $s) => $q->search($s))
            ->when($request->min_price, fn($q, $p) => $q->where('price', '>=', $p))
            ->when($request->max_price, fn($q, $p) => $q->where('price', '<=', $p))
            ->when($request->featured, fn($q) => $q->featured())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($products);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with('images', 'vendor:id,shop_name,slug', 'category', 'variants.options', 'reviews.user:id,name')
            ->firstOrFail();

        $product->increment('views_count');

        return response()->json($product);
    }
}
