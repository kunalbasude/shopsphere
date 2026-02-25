@extends('admin.layouts.app')
@section('title', 'Edit Plan')
@section('content')
<h4 class="mb-4">Edit Plan: {{ $plan->name }}</h4>
<div class="card stat-card"><div class="card-body">
    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
        @csrf @method('PUT') @include('admin.plans._form')
        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $plan->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Update Plan</button>
    </form>
</div></div>
@endsection
