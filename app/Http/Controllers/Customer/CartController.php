<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartSummary = $this->cartService->getCartSummary();
        return view('customer.cart.index', compact('cartSummary'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $item = $this->cartService->addItem(
            $request->product_id,
            $request->quantity ?? 1,
            $request->variant_id
        );

        $summary = $this->cartService->getCartSummary();

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart.',
            'item' => $item,
            'cart' => $summary,
        ]);
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        $this->cartService->updateItem($itemId, $request->quantity);
        $summary = $this->cartService->getCartSummary();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated.',
            'cart' => $summary,
        ]);
    }

    public function remove(int $itemId)
    {
        $this->cartService->removeItem($itemId);
        $summary = $this->cartService->getCartSummary();

        return response()->json([
            'success' => true,
            'message' => 'Item removed.',
            'cart' => $summary,
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $result = $this->cartService->applyCoupon($request->code);
        $result['cart'] = $this->cartService->getCartSummary();

        return response()->json($result);
    }

    public function removeCoupon()
    {
        $this->cartService->removeCoupon();

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'cart' => $this->cartService->getCartSummary(),
        ]);
    }

    public function applyRewardPoints(Request $request)
    {
        $request->validate(['points' => 'required|integer|min:1']);
        $result = $this->cartService->applyRewardPoints($request->points);
        $result['cart'] = $this->cartService->getCartSummary();

        return response()->json($result);
    }
}
