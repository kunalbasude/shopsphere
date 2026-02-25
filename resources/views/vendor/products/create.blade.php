@extends('vendor.layouts.app')
@section('title', 'Add Product')
@section('content')
<h4 class="mb-4">Add Product</h4>
<div class="card stat-card"><div class="card-body">
    @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach</div>
    @endif
    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Full Description</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Compare Price ($)</label>
                <input type="number" name="compare_price" class="form-control" step="0.01" value="{{ old('compare_price') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Images (max 5)</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div></div>
@endsection
