<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\CouponUsage;
use App\Models\RewardPoint;
use App\Models\RewardTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    protected CartService $cartService;
    protected WalletService $walletService;

    public function __construct(CartService $cartService, WalletService $walletService)
    {
        $this->cartService = $cartService;
        $this->walletService = $walletService;
    }

    public function createOrder(array $shippingData, string $paymentMethod = 'stripe'): Order
    {
        return DB::transaction(function () use ($shippingData, $paymentMethod) {
            $cart = $this->cartService->getOrCreateCart();
            $user = Auth::user();

            if ($cart->items->isEmpty()) {
                throw new \Exception('Cart is empty.');
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount,
                'reward_discount' => $cart->reward_discount,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'total' => $cart->total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'coupon_id' => $cart->coupon_id,
                'reward_points_used' => $cart->reward_points_used,
                'reward_points_earned' => floor($cart->total * config('shopsphere.reward_points.per_dollar', 1)),
                'shipping_name' => $shippingData['name'],
                'shipping_email' => $shippingData['email'],
                'shipping_phone' => $shippingData['phone'],
                'shipping_address' => $shippingData['address'],
                'shipping_city' => $shippingData['city'],
                'shipping_state' => $shippingData['state'],
                'shipping_zip' => $shippingData['zip'],
                'shipping_country' => $shippingData['country'],
                'notes' => $shippingData['notes'] ?? null,
            ]);

            // Create order items with commission calculation
            foreach ($cart->items as $cartItem) {
                $vendor = $cartItem->product->vendor;
                $commissionRate = $vendor->commission_rate;
                $total = $cartItem->unit_price * $cartItem->quantity;
                $commissionAmount = round(($total * $commissionRate) / 100, 2);
                $vendorEarning = $total - $commissionAmount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'vendor_id' => $vendor->id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'variant_name' => $cartItem->variant?->name,
                    'unit_price' => $cartItem->unit_price,
                    'quantity' => $cartItem->quantity,
                    'total' => $total,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'vendor_earning' => $vendorEarning,
                ]);

                // Reduce stock
                if ($cartItem->product_variant_id) {
                    $cartItem->variant->decrement('quantity', $cartItem->quantity);
                } else {
                    $cartItem->product->decrement('quantity', $cartItem->quantity);
                }
            }

            // Record status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'comment' => 'Order placed.',
                'changed_by' => $user->id,
            ]);

            // Track coupon usage
            if ($cart->coupon_id && $cart->discount > 0) {
                CouponUsage::create([
                    'coupon_id' => $cart->coupon_id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $cart->discount,
                ]);
                $cart->coupon->increment('used_count');
            }

            // Deduct reward points
            if ($cart->reward_points_used > 0) {
                $rp = RewardPoint::firstOrCreate(['user_id' => $user->id], ['balance' => 0, 'total_earned' => 0, 'total_redeemed' => 0]);
                $rp->decrement('balance', $cart->reward_points_used);
                $rp->increment('total_redeemed', $cart->reward_points_used);

                RewardTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => 'redeemed',
                    'points' => $cart->reward_points_used,
                    'balance_after' => $rp->fresh()->balance,
                    'description' => "Redeemed for order {$order->order_number}",
                ]);
            }

            // Clear cart
            $this->cartService->clearCart();

            return $order->load('items');
        });
    }

    public function updateStatus(Order $order, string $status, ?string $comment = null): Order
    {
        $order->update(['status' => $status]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $status,
            'comment' => $comment,
            'changed_by' => Auth::id(),
        ]);

        // On delivery — credit vendor wallets and award reward points
        if ($status === 'delivered') {
            $this->processDelivery($order);
        }

        return $order->fresh();
    }

    public function markAsPaid(Order $order, string $paymentId): void
    {
        $order->update([
            'payment_status' => 'paid',
            'payment_id' => $paymentId,
            'status' => 'confirmed',
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'confirmed',
            'comment' => 'Payment received.',
            'changed_by' => null,
        ]);
    }

    protected function processDelivery(Order $order): void
    {
        // Credit vendor wallets
        foreach ($order->items as $item) {
            $this->walletService->creditVendor($item->vendor_id, $item->vendor_earning, $order->id, $item->commission_amount);
        }

        // Award reward points
        $points = $order->reward_points_earned;
        if ($points > 0) {
            $rp = RewardPoint::firstOrCreate(
                ['user_id' => $order->user_id],
                ['balance' => 0, 'total_earned' => 0, 'total_redeemed' => 0]
            );
            $rp->increment('balance', $points);
            $rp->increment('total_earned', $points);

            RewardTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'type' => 'earned',
                'points' => $points,
                'balance_after' => $rp->fresh()->balance,
                'description' => "Earned from order {$order->order_number}",
            ]);
        }
    }
}
