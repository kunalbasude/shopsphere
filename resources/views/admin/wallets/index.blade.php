@extends('admin.layouts.app')
@section('title', 'Wallet Management')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Wallet Management</h4>
    <a href="{{ route('admin.wallets.create') }}" class="btn btn-primary">Add Transaction</a>
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
                <thead><tr><th>User</th><th>Balance</th><th>Total Earned</th><th>Total Withdrawn</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($wallets as $wallet)
                        <tr>
                            <td>
                                <strong>{{ $wallet->user->name }}</strong><br>
                                <small class="text-muted">{{ $wallet->user->email }}</small>
                            </td>
                            <td><span class="badge bg-success">${{ number_format($wallet->balance, 2) }}</span></td>
                            <td>${{ number_format($wallet->total_earned, 2) }}</td>
                            <td>${{ number_format($wallet->total_withdrawn, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.wallets.show', $wallet->user_id) }}" class="btn btn-sm btn-outline-primary">View History</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No wallet records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $wallets->links() }}
        </div>
    </div>
</div>
@endsection
