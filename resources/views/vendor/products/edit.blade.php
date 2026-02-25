@extends('vendor.layouts.app')
@section('title', 'Edit Product')
@section('content')
<h4 class="mb-4">Edit: {{ $product->name }}</h4>
<div class="card stat-card mb-4"><div class="card-body">
    @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach</div>
    @endif
    <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Full Description</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Compare Price ($)</label>
                <input type="number" name="compare_price" class="form-control" step="0.01" value="{{ old('compare_price', $product->compare_price) }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['draft','active','inactive'] as $s)
                        <option value="{{ $s }}" {{ $product->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Existing Images -->
        @if($product->images->count())
            <div class="mb-3">
                <label class="form-label">Current Images</label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($product->images as $img)
                        <div class="position-relative">
                            <img src="{{ asset($img->image_path) }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="padding:0 4px;" onclick="deleteImage({{ $img->id }}, this)">&times;</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="mb-3">
            <label class="form-label">Add More Images</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $product->meta_description) }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div></div>

<!-- Variants Section -->
<div class="card stat-card"><div class="card-body">
    <h6 class="fw-bold">Variants</h6>
    @foreach($product->variants as $variant)
        <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $variant->name }}</strong> ({{ $variant->sku }}) - ${{ number_format($variant->price,2) }} - Stock: {{ $variant->quantity }}
                <br><small class="text-muted">{{ $variant->options->map(fn($o) => $o->attribute_name.': '.$o->attribute_value)->join(', ') }}</small>
            </div>
            <form action="{{ route('vendor.variants.destroy', $variant) }}" method="POST" onsubmit="return confirm('Delete variant?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
        </div>
    @endforeach

    <hr>
    <h6>Add Variant</h6>
    <form method="POST" action="{{ route('vendor.variants.store', $product) }}">
        @csrf
        <div class="row g-2">
            <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Variant Name (e.g. Red/XL)" required></div>
            <div class="col-md-2"><input type="number" name="price" class="form-control" placeholder="Price" step="0.01" required></div>
            <div class="col-md-2"><input type="number" name="quantity" class="form-control" placeholder="Stock" required></div>
            <div class="col-md-2"><input type="text" name="options[0][attribute_name]" class="form-control" placeholder="Attr (e.g. color)" required></div>
            <div class="col-md-2"><input type="text" name="options[0][attribute_value]" class="form-control" placeholder="Value (e.g. Red)" required></div>
            <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
        </div>
    </form>
</div></div>

@push('scripts')
<script>
function deleteImage(imageId, btn) {
    if (!confirm('Delete this image?')) return;
    fetch('/vendor/products/image/' + imageId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => { if (d.success) btn.closest('.position-relative').remove(); });
}
</script>
@endpush
@endsection
