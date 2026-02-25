@extends('vendor.layouts.app')
@section('title', 'Pending Approval')
@section('content')
<div class="text-center py-5">
    <i class="bi bi-hourglass-split text-warning" style="font-size: 4rem;"></i>
    <h3 class="mt-3">Account Pending Approval</h3>
    <p class="text-muted">Your vendor account is under review. You'll receive access once approved by the admin.</p>
    <p><strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($vendor->status ?? 'pending') }}</span></p>
    @if($vendor->admin_note)
        <div class="alert alert-info d-inline-block">
            <strong>Admin Note:</strong> {{ $vendor->admin_note }}
        </div>
    @endif
</div>
@endsection
