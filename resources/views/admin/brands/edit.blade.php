@extends('admin.layouts.app')
@section('title', 'Edit Brand')
@section('content')
<div class="mb-4"><h4>Edit Brand: {{ $brand->name }}</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $brand->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" value="{{ old('slug', $brand->slug) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4">{{ old('description', $brand->description) }}</textarea>
            </div>
            @if($brand->logo)
                <div class="mb-3">
                    <label class="form-label">Current Logo</label><br>
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 200px;">
                </div>
            @endif
            <div class="mb-3">
                <label class="form-label">{{ $brand->logo ? 'Change' : 'Upload' }} Logo</label>
                <input type="file" class="form-control" name="logo" accept="image/*">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $brand->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Update Brand</button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
