@extends('admin.layouts.app')
@section('title', 'Add Reward Points')
@section('content')
<div class="mb-4"><h4>Add/Deduct Reward Points</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.reward-points.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Select User *</label>
                <select class="form-select" name="user_id" required>
                    <option value="">Choose a user...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Points *</label>
                <input type="number" class="form-control" name="points" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type *</label>
                <select class="form-select" name="type" required>
                    <option value="credit">Credit (Add Points)</option>
                    <option value="debit">Debit (Deduct Points)</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea class="form-control" name="description" rows="3" required placeholder="Reason for this transaction"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('admin.reward-points.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
