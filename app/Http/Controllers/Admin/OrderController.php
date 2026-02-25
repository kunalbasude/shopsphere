<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected FirebaseNotificationService $notificationService;

    public function __construct(OrderService $orderService, FirebaseNotificationService $notificationService)
    {
        $this->orderService = $orderService;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) => $q->where('order_number', 'LIKE', "%{$s}%"))
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'items.vendor', 'user', 'statusHistories.changedBy', 'transactions', 'refunds');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'comment' => 'nullable|string|max:500',
        ]);

        $this->orderService->updateStatus($order, $request->status, $request->comment);

        // Send push notification
        $this->notificationService->sendOrderStatusUpdate(
            $order->user_id, $order->order_number, $request->status
        );

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->total,
            'reason' => 'required|string|max:500',
        ]);

        $paymentService = app(PaymentService::class);

        if ($order->payment_method === 'stripe') {
            $paymentService->refundStripe($order, $request->amount, $request->reason);
        } elseif ($order->payment_method === 'razorpay') {
            $paymentService->refundRazorpay($order, $request->amount, $request->reason);
        }

        if ($request->amount >= $order->total) {
            $order->update(['payment_status' => 'refunded', 'status' => 'refunded']);
        }

        return redirect()->back()->with('success', 'Refund processed successfully.');
    }
}
