<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product.images', 'items.vendor', 'statusHistories', 'coupon');
        return view('customer.orders.show', compact('order'));
    }

    public function track(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('statusHistories');
        return view('customer.orders.track', compact('order'));
    }

    public function invoice(Order $order, InvoiceService $invoiceService)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return $invoiceService->downloadPdf($order);
    }
}
