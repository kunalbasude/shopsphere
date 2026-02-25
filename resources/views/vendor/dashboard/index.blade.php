@extends('vendor.layouts.app')
@section('title', 'Vendor Dashboard')
@section('content')
<h4 class="mb-4">Welcome, {{ $vendor->shop_name }}</h4>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white"><div class="card-body">
            <h6 class="opacity-75">Total Earnings</h6>
            <h3>${{ number_format($totalEarnings, 2) }}</h3>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white"><div class="card-body">
            <h6 class="opacity-75">Total Orders</h6>
            <h3>{{ $totalOrders }}</h3>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-info text-white"><div class="card-body">
            <h6 class="opacity-75">Products</h6>
            <h3>{{ $totalProducts }}</h3>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-dark text-white"><div class="card-body">
            <h6 class="opacity-75">Wallet Balance</h6>
            <h3>${{ number_format($wallet->balance ?? 0, 2) }}</h3>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card stat-card"><div class="card-body">
            <h6 class="fw-bold">Monthly Earnings</h6>
            <canvas id="salesChart" height="120"></canvas>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body">
            <h6 class="fw-bold">Recent Orders</h6>
            @foreach($recentOrders as $order)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <a href="{{ route('vendor.orders.show', $order) }}">{{ $order->order_number }}</a>
                    <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                </div>
            @endforeach
        </div></div>
    </div>
</div>

@push('scripts')
<script>
const data = @json($monthlySales);
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: { labels: data.map(d => d.month+'/'+d.year), datasets: [{ label: 'Earnings ($)', data: data.map(d => parseFloat(d.total)), borderColor: '#0d6efd', fill: false, tension: 0.3 }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
@endsection
