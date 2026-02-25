@extends('customer.layouts.app')
@section('title', 'Order Placed')
@section('content')
<div class="container text-center py-5">
    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
    <h3 class="mt-3">Order Placed Successfully!</h3>
    <p class="text-muted">Your order <strong>#{{ $order->order_number }}</strong> has been placed.</p>
    <p>Total: <strong>${{ number_format($order->total, 2) }}</strong></p>
    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">View Order</a>
    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
</div>
@endsection
