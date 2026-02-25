<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()
            ->with('images', 'vendor', 'category')
            ->when($request->category, fn($q, $c) => $q->where('category_id', $c))
            ->when($request->vendor, fn($q, $v) => $q->where('vendor_id', $v))
            ->when($request->search, fn($q, $s) => $q->search($s))
            ->when($request->min_price, fn($q, $p) => $q->where('price', '>=', $p))
            ->when($request->max_price, fn($q, $p) => $q->where('price', '<=', $p))
            ->when($request->sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'newest', fn($q) => $q->latest())
            ->when($request->sort === 'popular', fn($q) => $q->orderByDesc('views_count'))
            ->when(!$request->sort, fn($q) => $q->latest())
            ->paginate(12);

        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();

        return view('customer.shop.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with('images', 'vendor', 'category', 'variants.options', 'reviews.user')
            ->firstOrFail();

        $product->increment('views_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->take(4)
            ->get();

        return view('customer.shop.show', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');

        $products = Product::active()
            ->search($term)
            ->with('images')
            ->take(10)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->price,
                'image' => $p->primary_image?->image_path,
            ]);

        return response()->json($products);
    }

    public function vendorShop(string $slug)
    {
        $vendor = Vendor::where('slug', $slug)->where('status', 'approved')->firstOrFail();
        $products = $vendor->products()->active()->with('images')->paginate(12);

        return view('customer.shop.vendor', compact('vendor', 'products'));
    }
}
