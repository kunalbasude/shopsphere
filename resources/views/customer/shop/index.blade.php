@extends('customer.layouts.app')
@section('title', 'Shop')
@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Categories</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.index') }}" class="text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : 'text-dark' }}">All Categories</a></li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="text-decoration-none {{ request('category') == $category->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <h6 class="fw-bold mt-4">Price Range</h6>
                    <form action="{{ route('shop.index') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 mt-2">Filter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Products ({{ $products->total() }})</h5>
                <select class="form-select form-select-sm w-auto" onchange="window.location.href=this.value">
                    <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>
            </div>

            <div class="row g-3">
                @forelse($products as $product)
                    <div class="col-md-4">
                        <div class="card product-card h-100 position-relative">
                            @if($product->discount_percentage)
                                <span class="badge bg-danger badge-discount">-{{ $product->discount_percentage }}%</span>
                            @endif
                            @auth
                                <button class="btn-wishlist btn-wishlist-toggle" data-product-id="{{ $product->id }}">
                                    <i class="bi bi-heart"></i>
                                </button>
                            @endauth
                            <a href="{{ route('shop.show', $product->slug) }}">
                                <img src="{{ $product->primary_image ? asset($product->primary_image->image_path) : 'https://via.placeholder.com/300x220' }}" class="card-img-top" alt="{{ $product->name }}">
                            </a>
                            <div class="card-body">
                                <small class="text-muted">{{ $product->vendor->shop_name ?? '' }}</small>
                                <h6 class="card-title mb-1">
                                    <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($product->name, 40) }}</a>
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price)
                                        <small class="text-muted text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</small>
                                    @endif
                                </div>
                                <button class="btn btn-primary btn-sm w-100 mt-2 btn-add-to-cart" data-product-id="{{ $product->id }}">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-search fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No products found.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
