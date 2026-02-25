@extends('customer.layouts.app')
@section('title', 'My Wallet')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('customer.partials.account-sidebar')
        </div>
        <div class="col-md-9">
            <h3 class="mb-4">My Wallet</h3>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Current Balance</h6>
                            <h2 class="text-success">${{ number_format($wallet->balance, 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Total Earned</h6>
                            <h4>${{ number_format($wallet->total_earned, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Total Withdrawn</h6>
                            <h4>${{ number_format($wallet->total_withdrawn, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Transaction History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr><th>Date</th><th>Type</th><th>Amount</th><th>Balance</th><th>Description</th></tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type == 'credit' ? 'success' : 'danger' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-{{ $transaction->type == 'credit' ? 'success' : 'danger' }}">
                                                {{ $transaction->type == 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($transaction->balance_after, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No transactions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
