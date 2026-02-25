@extends('vendor.layouts.app')
@section('title', 'My Coupons')
@section('content')
<h4 class="mb-4">My Coupons</h4>
<div class="card stat-card mb-4"><div class="card-body">
    <h6 class="fw-bold">Create Coupon</h6>
    <form method="POST" action="{{ route('vendor.coupons.store') }}">
        @csrf
        <div class="row g-2">
            <div class="col-md-2"><input type="text" name="code" class="form-control" placeholder="CODE" required></div>
            <div class="col-md-2">
                <select name="type" class="form-select"><option value="fixed">Fixed</option><option value="percentage">Percentage</option></select>
            </div>
            <div class="col-md-2"><input type="number" name="value" class="form-control" placeholder="Value" step="0.01" required></div>
            <div class="col-md-2"><input type="number" name="min_cart_value" class="form-control" placeholder="Min Cart" step="0.01"></div>
            <div class="col-md-2"><input type="datetime-local" name="expires_at" class="form-control"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Create</button></div>
        </div>
    </form>
</div></div>
<div class="card stat-card"><div class="card-body table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Used</th><th>Expires</th><th>Action</th></tr></thead>
        <tbody>
            @forelse($coupons as $coupon)
                <tr>
                    <td><strong>{{ $coupon->code }}</strong></td>
                    <td>{{ ucfirst($coupon->type) }}</td>
                    <td>{{ $coupon->type === 'percentage' ? $coupon->value.'%' : '$'.$coupon->value }}</td>
                    <td>{{ $coupon->used_count }}</td>
                    <td>{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}</td>
                    <td>
                        <form action="{{ route('vendor.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No coupons yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $coupons->links() }}
</div></div>
@endsection
