@extends('customer.layouts.app')
@section('title', 'My Account')
@section('content')
<div class="container">
    <h4 class="mb-4">My Account</h4>
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
            <div class="card border-0 shadow-sm">
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
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Reward Points</h6>
                    <div class="text-center py-3">
                        <span class="fs-1 fw-bold text-primary">{{ $rewardPoints->balance }}</span>
                        <p class="text-muted">Available Points</p>
                    </div>
                    <div class="d-flex justify-content-around text-center">
                        <div><strong>{{ $rewardPoints->total_earned }}</strong><br><small class="text-muted">Total Earned</small></div>
                        <div><strong>{{ $rewardPoints->total_redeemed }}</strong><br><small class="text-muted">Total Redeemed</small></div>
                    </div>
                    <hr>
                    <h6>Recent History</h6>
                    @forelse($rewardHistory as $tx)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <small class="badge bg-{{ $tx->type === 'earned' ? 'success' : 'warning' }}">{{ ucfirst($tx->type) }}</small>
                                <small class="text-muted">{{ $tx->description }}</small>
                            </div>
                            <strong class="{{ $tx->type === 'earned' ? 'text-success' : 'text-danger' }}">{{ $tx->type === 'earned' ? '+' : '-' }}{{ $tx->points }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">No transactions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
