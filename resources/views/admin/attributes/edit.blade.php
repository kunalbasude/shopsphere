@extends('admin.layouts.app')
@section('title', 'Edit Attribute')
@section('content')
<div class="mb-4"><h4>Edit Attribute: {{ $attribute->name }}</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" value="{{ $attribute->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type *</label>
                <select class="form-select" name="type" required>
                    <option value="select" {{ $attribute->type == 'select' ? 'selected' : '' }}>Select Dropdown</option>
                    <option value="radio" {{ $attribute->type == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                    <option value="checkbox" {{ $attribute->type == 'checkbox' ? 'selected' : '' }}>Checkboxes</option>
                    <option value="text" {{ $attribute->type == 'text' ? 'selected' : '' }}>Text Input</option>
                    <option value="color" {{ $attribute->type == 'color' ? 'selected' : '' }}>Color Picker</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Values (comma-separated)</label>
                <input type="text" class="form-control" name="values[]" 
                       value="{{ is_array($attribute->values) ? implode(', ', $attribute->values) : '' }}">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $attribute->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Update Attribute</button>
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
