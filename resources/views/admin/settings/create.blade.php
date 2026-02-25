@extends('admin.layouts.app')
@section('title', 'Create Setting')
@section('content')
<div class="mb-4"><h4>Create New Setting</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Key (unique identifier) *</label>
                <input type="text" class="form-control" name="key" required placeholder="e.g., reward_points_per_dollar">
            </div>
            <div class="mb-3">
                <label class="form-label">Value</label>
                <textarea class="form-control" name="value" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Type *</label>
                <select class="form-select" name="type" required>
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                    <option value="number">Number</option>
                    <option value="boolean">Boolean (Yes/No)</option>
                    <option value="select">Select</option>
                    <option value="file">File</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Group *</label>
                <input type="text" class="form-control" name="group" required value="general" placeholder="e.g., general, reward_points, wallet">
            </div>
            <div class="mb-3">
                <label class="form-label">Sort Order</label>
                <input type="number" class="form-control" name="sort_order" value="0">
            </div>
            <button type="submit" class="btn btn-primary">Create Setting</button>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
