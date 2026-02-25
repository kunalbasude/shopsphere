@extends('customer.layouts.app')
@section('title', 'Payment Cancelled')
@section('content')
<div class="container text-center py-5">
    <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
    <h3 class="mt-3">Payment Cancelled</h3>
    <p class="text-muted">Your payment for order <strong>#{{ $order->order_number }}</strong> was cancelled.</p>
    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Try Again</a>
    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
</div>
@endsection
