@extends('admin.layouts.app')
@section('title', 'Coupons')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Coupons</h4>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Create Coupon</a>
</div>
<div class="card stat-card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Used</th><th>Limit</th><th>Expires</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>{{ $coupon->type === 'percentage' ? $coupon->value.'%' : '$'.$coupon->value }}</td>
                        <td>{{ $coupon->used_count }}</td>
                        <td>{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                        <td>{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}</td>
                        <td><span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No coupons.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $coupons->links() }}
    </div>
</div>
@endsection
