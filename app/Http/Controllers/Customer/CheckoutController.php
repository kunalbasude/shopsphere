<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected PaymentService $paymentService;

    public function __construct(CartService $cartService, OrderService $orderService, PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $cartSummary = $this->cartService->getCartSummary();

        if ($cartSummary['items_count'] === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('customer.checkout.index', compact('cartSummary'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:stripe,razorpay',
        ]);

        $order = $this->orderService->createOrder($validated, $validated['payment_method']);

        if ($validated['payment_method'] === 'stripe') {
            $session = $this->paymentService->createStripeSession($order);
            return response()->json(['checkout_url' => $session->url]);
        }

        if ($validated['payment_method'] === 'razorpay') {
            $razorpayOrder = $this->paymentService->createRazorpayOrder($order);
            return response()->json([
                'razorpay' => $razorpayOrder,
                'order_id' => $order->id,
            ]);
        }
    }

    public function success(Request $request, int $order)
    {
        $order = \App\Models\Order::where('id', $order)->where('user_id', auth()->id())->firstOrFail();
        return view('customer.checkout.success', compact('order'));
    }

    public function cancel(int $order)
    {
        $order = \App\Models\Order::where('id', $order)->where('user_id', auth()->id())->firstOrFail();
        return view('customer.checkout.cancel', compact('order'));
    }

    public function razorpayCallback(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        $verified = $this->paymentService->verifyRazorpayPayment(
            $request->razorpay_payment_id,
            $request->razorpay_order_id,
            $request->razorpay_signature
        );

        if ($verified) {
            $this->paymentService->handleRazorpaySuccess(
                $request->razorpay_payment_id,
                $request->razorpay_order_id
            );

            return response()->json(['success' => true, 'message' => 'Payment successful.']);
        }

        return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
    }
}
