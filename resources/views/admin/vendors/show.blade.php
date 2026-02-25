@extends('admin.layouts.app')
@section('title', $vendor->shop_name)
@section('content')
<h4 class="mb-4">{{ $vendor->shop_name }}</h4>
<div class="row">
    <div class="col-md-8">
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Vendor Details</h6>
                <p><strong>Owner:</strong> {{ $vendor->user->name }} ({{ $vendor->user->email }})</p>
                <p><strong>Status:</strong> <span class="badge bg-{{ $vendor->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($vendor->status) }}</span></p>
                <p><strong>Description:</strong> {{ $vendor->description ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $vendor->address }}, {{ $vendor->city }}, {{ $vendor->state }} {{ $vendor->zip_code }}, {{ $vendor->country }}</p>
                <p><strong>Products:</strong> {{ $vendor->products->count() }}</p>
                <p><strong>Wallet Balance:</strong> ${{ number_format($vendor->wallet->balance ?? 0, 2) }}</p>
                @if($vendor->subscription)
                    <p><strong>Plan:</strong> {{ $vendor->subscription->plan->name ?? 'N/A' }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Actions</h6>
                @if($vendor->status === 'pending')
                    <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="mb-2">
                        @csrf
                        <button class="btn btn-success w-100">Approve</button>
                    </form>
                    <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" class="mb-2">
                        @csrf
                        <textarea name="admin_note" class="form-control mb-2" placeholder="Rejection reason" required></textarea>
                        <button class="btn btn-danger w-100">Reject</button>
                    </form>
                @endif
                @if($vendor->status === 'approved')
                    <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST" class="mb-2">
                        @csrf
                        <button class="btn btn-warning w-100">Suspend</button>
                    </form>
                @endif
                <hr>
                <form action="{{ route('admin.vendors.commission', $vendor) }}" method="POST">
                    @csrf @method('PUT')
                    <label class="form-label">Commission Rate (%)</label>
                    <div class="input-group">
                        <input type="number" name="commission_rate" class="form-control" value="{{ $vendor->commission_rate }}" step="0.01" min="0" max="100">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
