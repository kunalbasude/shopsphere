@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="mb-4">Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <h6 class="opacity-75">Total Revenue</h6>
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <h6 class="opacity-75">Total Orders</h6>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <h6 class="opacity-75">Total Vendors</h6>
                <h3>{{ $totalVendors }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
                <h6 class="opacity-75">Pending Vendors</h6>
                <h3>{{ $pendingVendors }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold">Monthly Sales (Last 12 Months)</h6>
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold">Top Products</h6>
                @foreach($topProducts as $product)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ Str::limit($product->name, 25) }}</span>
                        <span class="badge bg-primary">{{ $product->order_items_count }} sold</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <h6 class="fw-bold">Recent Orders</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->created_at->format('M d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
const salesData = @json($monthlySales);
const labels = salesData.map(d => d.month + '/' + d.year);
const values = salesData.map(d => parseFloat(d.total));

new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: { labels, datasets: [{ label: 'Revenue ($)', data: values, backgroundColor: '#0d6efd' }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
@endsection
