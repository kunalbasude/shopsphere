@extends('customer.layouts.app')
@section('title', 'My Orders')
@section('content')
<div class="container">
    <h4 class="mb-4">My Orders</h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Order #</th><th>Date</th><th>Total</th><th>Status</th><th>Payment</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td><span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                        <td><span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('orders.track', $order) }}" class="btn btn-sm btn-outline-info">Track</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection
