@extends('customer.layouts.app')
@section('title', 'My Account')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('customer.partials.account-sidebar')
        </div>
        <div class="col-md-9">
            <h3 class="mb-4">My Account</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold">Profile</h6>
                            <form method="POST" action="{{ route('account.update') }}">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold">Change Password</h6>
                            <form method="POST" action="{{ route('account.password') }}">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Quick Stats</h6>
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <i class="bi bi-wallet2 text-success fs-4"></i>
                                    <div class="mt-2">
                                        <strong>${{ $user->wallet->balance ?? '0.00' }}</strong><br>
                                        <small class="text-muted">Wallet Balance</small>
                                    </div>
                                </div>
                                <div>
                                    <i class="bi bi-star text-warning fs-4"></i>
                                    <div class="mt-2">
                                        <strong>{{ $rewardPoints->balance ?? 0 }}</strong><br>
                                        <small class="text-muted">Reward Points</small>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-outline-success me-2">View Wallet</a>
                            <a href="{{ route('reward-points.index') }}" class="btn btn-sm btn-outline-warning">View Points</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
