@extends('vendor.layouts.app')
@section('title', 'My Products')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>My Products</h4>
    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">Add Product</a>
</div>
<div class="card stat-card">
    <div class="card-body">
        <form class="row g-2 mb-3" method="GET">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}"></div>
            <div class="col-md-1"><button class="btn btn-primary">Go</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Image</th><th>Name</th><th>SKU</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td><img src="{{ $product->primary_image ? asset($product->primary_image->image_path) : 'https://via.placeholder.com/40' }}" width="40" height="40" class="rounded" style="object-fit: cover;"></td>
                            <td>{{ Str::limit($product->name, 30) }}</td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td><span class="badge bg-{{ $product->quantity > 0 ? ($product->isLowStock() ? 'warning' : 'success') : 'danger' }}">{{ $product->quantity }}</span></td>
                            <td><span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span></td>
                            <td>
                                <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
