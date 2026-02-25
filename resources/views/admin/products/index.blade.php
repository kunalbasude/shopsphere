@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')
<h4 class="mb-4">Products</h4>
<div class="card stat-card">
    <div class="card-body">
        <form class="row g-2 mb-3" method="GET">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All</option>
                    @foreach(['active','inactive','draft'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Product</th><th>Vendor</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Featured</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ Str::limit($product->name, 30) }}</td>
                            <td>{{ $product->vendor->shop_name ?? 'N/A' }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td><span class="badge bg-{{ $product->quantity > 0 ? 'success' : 'danger' }}">{{ $product->quantity }}</span></td>
                            <td><span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span></td>
                            <td>
                                <form action="{{ route('admin.products.featured', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm {{ $product->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"><i class="bi bi-star{{ $product->is_featured ? '-fill' : '' }}"></i></button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.products.status', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-{{ $product->status === 'active' ? 'danger' : 'success' }}">{{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No products.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
