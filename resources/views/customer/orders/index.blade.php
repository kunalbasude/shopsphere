@extends('customer.layouts.app')
@section('title', 'My Orders')
@section('content')
<div class="bg-white border-bottom">
    <div class="container py-3">
        <nav aria-label="breadcrumb" class="ss-breadcrumb mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item active">My Orders</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <h4 class="fw-bold mb-4">My Orders</h4>

    @forelse($orders as $order)
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <small class="text-muted">Order Number</small>
                        <div class="fw-bold">{{ $order->order_number }}</div>
                        <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Total</small>
                        <div class="fw-bold text-primary">${{ number_format($order->total, 2) }}</div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Status</small>
                        <div>
                            @php
                                $statusColors = [
                                    'pending' => 'warning', 'confirmed' => 'info', 'processing' => 'info',
                                    'shipped' => 'primary', 'delivered' => 'success', 'cancelled' => 'danger', 'refunded' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Payment</small>
                        <div>
                            @php
                                $paymentColors = ['paid' => 'success', 'cod' => 'info', 'pending' => 'warning', 'failed' => 'danger', 'refunded' => 'secondary'];
                                $paymentLabels = ['paid' => 'Paid', 'cod' => 'COD', 'pending' => 'Pending', 'failed' => 'Failed', 'refunded' => 'Refunded'];
                            @endphp
                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">{{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary me-1" style="border-radius: 8px;"><i class="bi bi-eye me-1"></i>View</a>
                        <a href="{{ route('orders.track', $order) }}" class="btn btn-sm btn-outline-info" style="border-radius: 8px;"><i class="bi bi-geo-alt me-1"></i>Track</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="ss-empty-state">
            <div class="icon"><i class="bi bi-bag-x"></i></div>
            <h5>No orders yet</h5>
            <p>You haven't placed any orders. Start shopping to see your orders here.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary" style="border-radius: 8px;">
                <i class="bi bi-bag me-1"></i> Start Shopping
            </a>
        </div>
    @endforelse

    @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
