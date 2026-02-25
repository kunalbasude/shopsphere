<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('vendor', 'category')
            ->when($request->search, fn($q, $s) => $q->search($s))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('vendor', 'category', 'images', 'variants.options', 'reviews');
        return view('admin.products.show', compact('product'));
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return redirect()->back()->with('success', 'Product featured status updated.');
    }

    public function toggleStatus(Product $product)
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);
        return redirect()->back()->with('success', 'Product status updated.');
    }
}
