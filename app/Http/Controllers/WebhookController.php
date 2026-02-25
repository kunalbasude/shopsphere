<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function stripeWebhook(Request $request)
    {
        $payload = $request->all();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('shopsphere.payment.stripe.webhook_secret');

        if ($webhookSecret) {
            try {
                \Stripe\Webhook::constructEvent(
                    $request->getContent(),
                    $sigHeader,
                    $webhookSecret
                );
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid signature.'], 400);
            }
        }

        $this->paymentService->handleStripeWebhook($payload);

        return response()->json(['status' => 'ok']);
    }

    public function razorpayWebhook(Request $request)
    {
        $webhookSecret = config('shopsphere.payment.razorpay.webhook_secret');

        if ($webhookSecret) {
            $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);
            $receivedSignature = $request->header('X-Razorpay-Signature');

            if ($expectedSignature !== $receivedSignature) {
                return response()->json(['error' => 'Invalid signature.'], 400);
            }
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        if ($event === 'payment.captured') {
            $paymentId = $payload['payload']['payment']['entity']['id'] ?? null;
            $orderId = $payload['payload']['payment']['entity']['notes']['razorpay_order_id'] ?? null;

            if ($paymentId && $orderId) {
                $this->paymentService->handleRazorpaySuccess($paymentId, $orderId);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
