@extends('admin.layouts.app')
@section('title', 'Create Attribute')
@section('content')
<div class="mb-4"><h4>Create Attribute</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.attributes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type *</label>
                <select class="form-select" name="type" required>
                    <option value="select">Select Dropdown</option>
                    <option value="radio">Radio Buttons</option>
                    <option value="checkbox">Checkboxes</option>
                    <option value="text">Text Input</option>
                    <option value="color">Color Picker</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Values (comma-separated)</label>
                <input type="text" class="form-control" name="values[]" placeholder="Red, Blue, Green">
                <small class="text-muted">Enter values separated by commas</small>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" checked>
                <label class="form-check-label">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Create Attribute</button>
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
