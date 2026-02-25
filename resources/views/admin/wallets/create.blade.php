@extends('admin.layouts.app')
@section('title', 'Add Wallet Transaction')
@section('content')
<div class="mb-4"><h4>Add Wallet Transaction</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.wallets.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Select User *</label>
                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                    <option value="">Choose a user...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Amount *</label>
                <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" step="0.01" min="0.01" required>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Type *</label>
                <select class="form-select" name="type" required>
                    <option value="credit">Credit (Add Money)</option>
                    <option value="debit">Debit (Deduct Money)</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea class="form-control" name="description" rows="3" required placeholder="Reason for this transaction"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('admin.wallets.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
