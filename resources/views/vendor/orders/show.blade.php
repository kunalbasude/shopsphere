@extends('vendor.layouts.app')
@section('title', 'Order #' . $order->order_number)
@section('content')
<h4 class="mb-4">Order #{{ $order->order_number }}</h4>
<div class="row">
    <div class="col-md-8">
        <div class="card stat-card mb-3"><div class="card-body">
            <h6 class="fw-bold">Your Items in This Order</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th>Your Earning</th></tr></thead>
                    <tbody>
                        @foreach($vendorItems as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->total, 2) }}</td>
                                <td class="text-success">${{ number_format($item->vendor_earning, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card mb-3"><div class="card-body">
            <h6 class="fw-bold">Order Info</h6>
            <p><strong>Customer:</strong> {{ $order->user->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
            <p><strong>Payment:</strong> <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
            <p><strong>Total Order:</strong> ${{ number_format($order->total, 2) }}</p>
        </div></div>
        <div class="card stat-card"><div class="card-body">
            <h6 class="fw-bold">Shipping</h6>
            <p class="mb-1">{{ $order->shipping_name }}</p>
            <p class="mb-1">{{ $order->shipping_address }}</p>
            <p class="mb-0">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
        </div></div>
    </div>
</div>
@endsection
