@extends('admin.layouts.app')
@section('title', 'Order #' . $order->order_number)
@section('content')
<h4 class="mb-4">Order #{{ $order->order_number }}</h4>
<div class="row">
    <div class="col-md-8">
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Product</th><th>Vendor</th><th>Price</th><th>Qty</th><th>Total</th><th>Commission</th></tr></thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->vendor->shop_name ?? 'N/A' }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                    <td>${{ number_format($item->commission_amount, 2) }} ({{ $item->commission_rate }}%)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Update Status</h6>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="row g-2">
                    @csrf @method('PUT')
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5"><input type="text" name="comment" class="form-control" placeholder="Comment (optional)"></div>
                    <div class="col-md-3"><button class="btn btn-primary w-100">Update</button></div>
                </form>
            </div>
        </div>
        @if($order->payment_status === 'paid')
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold">Process Refund</h6>
                <form action="{{ route('admin.orders.refund', $order) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-3"><input type="number" name="amount" class="form-control" placeholder="Amount" step="0.01" max="{{ $order->total }}" required></div>
                    <div class="col-md-6"><input type="text" name="reason" class="form-control" placeholder="Reason" required></div>
                    <div class="col-md-3"><button class="btn btn-danger w-100">Refund</button></div>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Summary</h6>
                <div class="d-flex justify-content-between"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                <div class="d-flex justify-content-between text-success"><span>Discount</span><span>-${{ number_format($order->discount_amount, 2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>
                <hr>
                <p class="mb-1"><strong>Customer:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Payment:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }} - <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold">Status History</h6>
                @foreach($order->statusHistories->sortByDesc('created_at') as $h)
                    <div class="border-bottom py-2">
                        <strong>{{ ucfirst($h->status) }}</strong> <small class="text-muted">{{ $h->created_at->format('M d, h:i A') }}</small>
                        @if($h->comment)<br><small>{{ $h->comment }}</small>@endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
