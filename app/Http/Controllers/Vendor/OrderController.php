<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $orders = Order::whereHas('items', fn($q) => $q->where('vendor_id', $vendor->id))
            ->with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $vendor = Auth::user()->vendor;
        $vendorItems = $order->items()->where('vendor_id', $vendor->id)->with('product')->get();
        $order->load('user', 'statusHistories');

        return view('vendor.orders.show', compact('order', 'vendorItems'));
    }
}
