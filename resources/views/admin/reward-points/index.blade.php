@extends('admin.layouts.app')
@section('title', 'Reward Points Management')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Reward Points Management</h4>
    <a href="{{ route('admin.reward-points.create') }}" class="btn btn-primary">Add Points</a>
</div>
<div class="card stat-card">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by name or email" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary">Search</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>User</th><th>Balance</th><th>Total Earned</th><th>Total Redeemed</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($rewardPoints as $rp)
                        <tr>
                            <td>
                                <strong>{{ $rp->user->name }}</strong><br>
                                <small class="text-muted">{{ $rp->user->email }}</small>
                            </td>
                            <td><span class="badge bg-primary">{{ $rp->balance }} points</span></td>
                            <td>{{ $rp->total_earned }}</td>
                            <td>{{ $rp->total_redeemed }}</td>
                            <td>
                                <a href="{{ route('admin.reward-points.show', $rp->user_id) }}" class="btn btn-sm btn-outline-primary">View History</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No reward points records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $rewardPoints->links() }}
        </div>
    </div>
</div>
@endsection
