@extends('admin.layouts.app')
@section('title', 'Vendors')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Vendors</h4>
    <div>
        <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning">Pending</a>
        <a href="{{ route('admin.vendors.index', ['status' => 'approved']) }}" class="btn btn-sm btn-success">Approved</a>
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-sm btn-secondary">All</a>
    </div>
</div>
<div class="card stat-card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Shop Name</th><th>Owner</th><th>Status</th><th>Commission</th><th>Products</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->shop_name }}</td>
                        <td>{{ $vendor->user->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-{{ $vendor->status === 'approved' ? 'success' : ($vendor->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($vendor->status) }}</span></td>
                        <td>{{ $vendor->commission_rate }}%</td>
                        <td>{{ $vendor->products_count ?? $vendor->products()->count() }}</td>
                        <td><a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No vendors found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $vendors->withQueryString()->links() }}
    </div>
</div>
@endsection
