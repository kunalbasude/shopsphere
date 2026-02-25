@extends('admin.layouts.app')
@section('title', 'Create Brand')
@section('content')
<div class="mb-4"><h4>Create Brand</h4></div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" value="{{ old('slug') }}">
                <small class="text-muted">Leave blank to auto-generate</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Logo</label>
                <input type="file" class="form-control" name="logo" accept="image/*">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" checked>
                <label class="form-check-label">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Create Brand</button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
