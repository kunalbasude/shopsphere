@extends('admin.layouts.app')
@section('title', 'Orders')
@section('content')
<h4 class="mb-4">Orders</h4>
<div class="card stat-card">
    <div class="card-body">
        <form class="row g-2 mb-3" method="GET">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search order #" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                            <td><span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($order->payment_status) }}</span></td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No orders.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
