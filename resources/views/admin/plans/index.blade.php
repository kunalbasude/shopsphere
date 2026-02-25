@extends('admin.layouts.app')
@section('title', 'Subscription Plans')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Subscription Plans</h4>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">Create Plan</a>
</div>
<div class="row g-3">
    @foreach($plans as $plan)
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <h5>{{ $plan->name }}</h5>
                    <h3 class="text-primary">${{ number_format($plan->price, 2) }}<small class="fs-6 text-muted">/{{ $plan->billing_cycle }}</small></h3>
                    <ul class="list-unstyled text-start mt-3">
                        <li><i class="bi bi-check-circle text-success"></i> {{ $plan->product_limit }} Products</li>
                        <li><i class="bi bi-check-circle text-success"></i> {{ $plan->commission_rate }}% Commission</li>
                        <li><i class="bi {{ $plan->featured_products ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i> Featured Products</li>
                        <li><i class="bi {{ $plan->analytics_access ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i> Analytics</li>
                        <li><i class="bi {{ $plan->priority_support ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i> Priority Support</li>
                    </ul>
                    <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }} mb-2">{{ $plan->is_active ? 'Active' : 'Inactive' }}</span>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
