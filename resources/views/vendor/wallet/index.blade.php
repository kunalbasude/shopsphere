@extends('vendor.layouts.app')
@section('title', 'Wallet')
@section('content')
<h4 class="mb-4">Wallet</h4>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card stat-card bg-primary text-white"><div class="card-body"><h6 class="opacity-75">Balance</h6><h3>${{ number_format($wallet->balance, 2) }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card bg-success text-white"><div class="card-body"><h6 class="opacity-75">Total Earned</h6><h3>${{ number_format($wallet->total_earned, 2) }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card bg-warning text-dark"><div class="card-body"><h6 class="opacity-75">Commission Paid</h6><h3>${{ number_format($wallet->total_commission_paid, 2) }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card bg-info text-white"><div class="card-body"><h6 class="opacity-75">Withdrawn</h6><h3>${{ number_format($wallet->total_withdrawn, 2) }}</h3></div></div></div>
</div>
<div class="card stat-card"><div class="card-body">
    <h6 class="fw-bold">Transaction History</h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Balance After</th><th>Description</th></tr></thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr>
                        <td>{{ $tx->created_at->format('M d, Y') }}</td>
                        <td><span class="badge bg-{{ $tx->type === 'credit' ? 'success' : 'danger' }}">{{ ucfirst($tx->type) }}</span></td>
                        <td>${{ number_format($tx->amount, 2) }}</td>
                        <td>${{ number_format($tx->balance_after, 2) }}</td>
                        <td>{{ $tx->description }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No transactions.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $transactions->links() }}
</div></div>
@endsection
