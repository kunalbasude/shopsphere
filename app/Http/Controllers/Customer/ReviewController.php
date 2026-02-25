<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Verify the user purchased this product
        $purchased = OrderItem::where('product_id', $validated['product_id'])
            ->whereHas('order', fn($q) => $q->where('user_id', Auth::id())
                ->where('id', $validated['order_id'])
                ->where('status', 'delivered'))
            ->exists();

        if (!$purchased) {
            return response()->json(['success' => false, 'message' => 'You can only review purchased products.'], 403);
        }

        // Check if already reviewed
        $existing = Review::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->where('order_id', $validated['order_id'])
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'You have already reviewed this product for this order.'], 409);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve verified purchase reviews
        ]);

        return response()->json(['success' => true, 'message' => 'Review submitted.', 'review' => $review]);
    }
}
