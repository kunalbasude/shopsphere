@extends('customer.layouts.app')
@section('title', 'Order #' . $order->order_number)
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Order #{{ $order->order_number }}</h4>
        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download"></i> Invoice</a>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">Items</h6>
                    @foreach($order->items as $item)
                        <div class="d-flex gap-3 py-2 border-bottom">
                            <div class="flex-grow-1">
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->variant_name) <small class="text-muted">({{ $item->variant_name }})</small> @endif
                                <br><small class="text-muted">Sold by: {{ $item->vendor->shop_name ?? 'N/A' }}</small>
                                <br>${{ number_format($item->unit_price, 2) }} x {{ $item->quantity }} = <strong>${{ number_format($item->total, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">Summary</h6>
                    <div class="d-flex justify-content-between"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between text-success"><span>Discount</span><span>-${{ number_format($order->discount_amount, 2) }}</span></div>
                    @endif
                    @if($order->reward_discount > 0)
                        <div class="d-flex justify-content-between text-info"><span>Rewards</span><span>-${{ number_format($order->reward_discount, 2) }}</span></div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>
                    <hr>
                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                    <p class="mb-1"><strong>Payment:</strong> <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                    <p class="mb-0"><strong>Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Shipping</h6>
                    <p class="mb-1">{{ $order->shipping_name }}</p>
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p class="mb-0">{{ $order->shipping_country }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
