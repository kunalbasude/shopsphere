<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Refund;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Refund as StripeRefund;
use Razorpay\Api\Api as RazorpayApi;

class PaymentService
{
    // ─── STRIPE ────────────────────────────────────────────

    public function createStripeSession(Order $order): StripeSession
    {
        Stripe::setApiKey(config('shopsphere.payment.stripe.secret'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product_name,
                    ],
                    'unit_amount' => (int) ($item->unit_price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);

        // Log transaction
        Transaction::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'payment_gateway' => 'stripe',
            'gateway_transaction_id' => $session->id,
            'amount' => $order->total,
            'currency' => 'USD',
            'status' => 'pending',
        ]);

        return $session;
    }

    public function handleStripeWebhook(array $payload): void
    {
        $session = $payload['data']['object'];
        $orderId = $session['metadata']['order_id'] ?? null;

        if (!$orderId) return;

        $order = Order::find($orderId);
        if (!$order) return;

        $transaction = $order->transactions()
            ->where('payment_gateway', 'stripe')
            ->where('gateway_transaction_id', $session['id'])
            ->first();

        if ($payload['type'] === 'checkout.session.completed') {
            $transaction?->update([
                'status' => 'success',
                'gateway_response' => $session,
            ]);

            app(OrderService::class)->markAsPaid($order, $session['payment_intent']);
        }

        if ($payload['type'] === 'checkout.session.async_payment_failed') {
            $transaction?->update([
                'status' => 'failed',
                'gateway_response' => $session,
            ]);
            $order->update(['payment_status' => 'failed']);
        }
    }

    public function refundStripe(Order $order, float $amount, string $reason = ''): Refund
    {
        Stripe::setApiKey(config('shopsphere.payment.stripe.secret'));

        $transaction = $order->transactions()
            ->where('payment_gateway', 'stripe')
            ->where('status', 'success')
            ->firstOrFail();

        $stripeRefund = StripeRefund::create([
            'payment_intent' => $order->payment_id,
            'amount' => (int) ($amount * 100),
        ]);

        return Refund::create([
            'order_id' => $order->id,
            'transaction_id' => $transaction->id,
            'refund_id' => 'REF-' . strtoupper(Str::random(12)),
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'processed',
            'gateway_refund_id' => $stripeRefund->id,
        ]);
    }

    // ─── RAZORPAY ──────────────────────────────────────────

    public function createRazorpayOrder(Order $order): array
    {
        $api = new RazorpayApi(
            config('shopsphere.payment.razorpay.key'),
            config('shopsphere.payment.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) ($order->total * 100),
            'currency' => 'INR',
        ]);

        Transaction::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'payment_gateway' => 'razorpay',
            'gateway_transaction_id' => $razorpayOrder->id,
            'amount' => $order->total,
            'currency' => 'INR',
            'status' => 'pending',
        ]);

        return [
            'order_id' => $razorpayOrder->id,
            'amount' => $razorpayOrder->amount,
            'currency' => $razorpayOrder->currency,
            'key' => config('shopsphere.payment.razorpay.key'),
        ];
    }

    public function verifyRazorpayPayment(string $razorpayPaymentId, string $razorpayOrderId, string $razorpaySignature): bool
    {
        $api = new RazorpayApi(
            config('shopsphere.payment.razorpay.key'),
            config('shopsphere.payment.razorpay.secret')
        );

        $attributes = [
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature,
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function handleRazorpaySuccess(string $razorpayPaymentId, string $razorpayOrderId): void
    {
        $transaction = Transaction::where('gateway_transaction_id', $razorpayOrderId)
            ->where('payment_gateway', 'razorpay')
            ->first();

        if (!$transaction) return;

        $transaction->update([
            'status' => 'success',
            'gateway_response' => ['razorpay_payment_id' => $razorpayPaymentId],
        ]);

        $order = $transaction->order;
        app(OrderService::class)->markAsPaid($order, $razorpayPaymentId);
    }

    public function refundRazorpay(Order $order, float $amount, string $reason = ''): Refund
    {
        $api = new RazorpayApi(
            config('shopsphere.payment.razorpay.key'),
            config('shopsphere.payment.razorpay.secret')
        );

        $transaction = $order->transactions()
            ->where('payment_gateway', 'razorpay')
            ->where('status', 'success')
            ->firstOrFail();

        $razorpayRefund = $api->payment->fetch($order->payment_id)->refund([
            'amount' => (int) ($amount * 100),
        ]);

        return Refund::create([
            'order_id' => $order->id,
            'transaction_id' => $transaction->id,
            'refund_id' => 'REF-' . strtoupper(Str::random(12)),
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'processed',
            'gateway_refund_id' => $razorpayRefund->id,
        ]);
    }
}
