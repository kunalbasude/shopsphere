@extends('vendor.layouts.app')
@section('title', 'Subscription Plans')
@section('content')
<h4 class="mb-4">Subscription Plans</h4>
@if($currentSubscription)
    <div class="alert alert-info">
        Current Plan: <strong>{{ $currentSubscription->plan->name ?? 'N/A' }}</strong>
        @if($currentSubscription->ends_at)
            | Expires: {{ $currentSubscription->ends_at->format('M d, Y') }}
        @endif
    </div>
@endif
<div class="row g-3">
    @foreach($plans as $plan)
        <div class="col-md-4">
            <div class="card stat-card {{ $currentSubscription && $currentSubscription->subscription_plan_id === $plan->id ? 'border-primary border-2' : '' }}">
                <div class="card-body text-center">
                    <h5>{{ $plan->name }}</h5>
                    <h3 class="text-primary">${{ number_format($plan->price, 2) }}<small class="fs-6 text-muted">/{{ $plan->billing_cycle }}</small></h3>
                    <p class="text-muted">{{ $plan->description }}</p>
                    <ul class="list-unstyled text-start">
                        <li><i class="bi bi-check-circle text-success"></i> {{ $plan->product_limit }} Products</li>
                        <li><i class="bi bi-check-circle text-success"></i> {{ $plan->commission_rate }}% Commission</li>
                        <li><i class="bi {{ $plan->featured_products ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i> Featured Products</li>
                        <li><i class="bi {{ $plan->analytics_access ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i> Analytics</li>
                    </ul>
                    @if(!$currentSubscription || $currentSubscription->subscription_plan_id !== $plan->id)
                        <form action="{{ route('vendor.subscription.subscribe', $plan) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary w-100">{{ $plan->isFree() ? 'Activate Free' : 'Subscribe' }}</button>
                        </form>
                    @else
                        <button class="btn btn-success w-100" disabled>Current Plan</button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
