<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            // Merge guest cart if exists
            $sessionId = Session::getId();
            $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();

            if ($guestCart) {
                foreach ($guestCart->items as $item) {
                    $existing = $cart->items()->where('product_id', $item->product_id)
                        ->where('product_variant_id', $item->product_variant_id)->first();

                    if ($existing) {
                        $existing->update(['quantity' => $existing->quantity + $item->quantity]);
                    } else {
                        $item->update(['cart_id' => $cart->id]);
                    }
                }
                $guestCart->items()->delete();
                $guestCart->delete();
            }

            return $cart->load('items.product', 'items.variant', 'coupon');
        }

        return Cart::firstOrCreate(
            ['session_id' => Session::getId()],
            ['user_id' => null]
        )->load('items.product', 'items.variant', 'coupon');
    }

    public function addItem(int $productId, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($productId);
        $price = $product->price;

        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            $price = $variant->price;
        }

        $existing = $cart->items()
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $quantity]);
            return $existing->fresh();
        }

        return $cart->items()->create([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'unit_price' => $price,
        ]);
    }

    public function updateItem(int $itemId, int $quantity): CartItem
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();
            return $item;
        }

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }

    public function removeItem(int $itemId): void
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->where('id', $itemId)->delete();
    }

    public function applyCoupon(string $code): array
    {
        $cart = $this->getOrCreateCart();
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        if (!$coupon->isValid()) {
            return ['success' => false, 'message' => 'This coupon is expired or inactive.'];
        }

        if ($cart->subtotal < $coupon->min_cart_value) {
            return ['success' => false, 'message' => "Minimum cart value is \${$coupon->min_cart_value}."];
        }

        if (Auth::check() && $coupon->hasUserExceededLimit(Auth::id())) {
            return ['success' => false, 'message' => 'You have already used this coupon.'];
        }

        $cart->update(['coupon_id' => $coupon->id]);

        return ['success' => true, 'message' => 'Coupon applied successfully.', 'cart' => $cart->fresh()];
    }

    public function removeCoupon(): void
    {
        $cart = $this->getOrCreateCart();
        $cart->update(['coupon_id' => null]);
    }

    public function applyRewardPoints(int $points): array
    {
        $cart = $this->getOrCreateCart();

        if (!Auth::check()) {
            return ['success' => false, 'message' => 'Login required.'];
        }

        $rewardPoints = Auth::user()->rewardPoints;
        $available = $rewardPoints?->balance ?? 0;

        if ($points > $available) {
            return ['success' => false, 'message' => 'Insufficient reward points.'];
        }

        $cart->update(['reward_points_used' => $points]);

        return ['success' => true, 'message' => 'Reward points applied.', 'cart' => $cart->fresh()];
    }

    public function getCartSummary(): array
    {
        $cart = $this->getOrCreateCart();

        return [
            'items' => $cart->items,
            'items_count' => $cart->items->sum('quantity'),
            'subtotal' => $cart->subtotal,
            'discount' => $cart->discount,
            'reward_discount' => $cart->reward_discount,
            'total' => $cart->total,
            'coupon' => $cart->coupon,
        ];
    }

    public function clearCart(): void
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
        $cart->update(['coupon_id' => null, 'reward_points_used' => 0]);
    }
}
