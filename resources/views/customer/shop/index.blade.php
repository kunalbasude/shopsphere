@extends('customer.layouts.app')
@section('title', 'Shop')
@section('content')

<!-- Shop Header -->
<div class="bg-white border-bottom">
    <div class="container py-3">
        <nav aria-label="breadcrumb" class="ss-breadcrumb mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="ss-filter-card mb-4">
                <div class="card-body">
                    <h6><i class="bi bi-grid me-1"></i> Categories</h6>
                    <ul class="list-unstyled category-list mb-0">
                        <li>
                            <a href="{{ route('shop.index') }}" class="{{ !request('category') ? 'active' : '' }}">
                                <i class="bi bi-collection me-2"></i> All Categories
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="{{ request('category') == $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                    <span class="count">{{ $category->products_count ?? '' }}</span>
                                </a>
                            </li>
                            @if($category->children->count())
                                @foreach($category->children as $child)
                                    <li>
                                        <a href="{{ route('shop.index', ['category' => $child->id]) }}" class="{{ request('category') == $child->id ? 'active' : '' }}" style="padding-left: 2rem;">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="ss-filter-card">
                <div class="card-body">
                    <h6><i class="bi bi-funnel me-1"></i> Price Range</h6>
                    <form action="{{ route('shop.index') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">$</span>
                                    <input type="number" name="min_price" class="form-control border-start-0" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">$</span>
                                    <input type="number" name="max_price" class="form-control border-start-0" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-3" style="border-radius: 8px; font-weight: 600;">
                            <i class="bi bi-filter me-1"></i> Apply Filter
                        </button>
                        @if(request('min_price') || request('max_price'))
                            <a href="{{ route('shop.index', request()->except(['min_price', 'max_price'])) }}" class="btn btn-outline-secondary btn-sm w-100 mt-2" style="border-radius: 8px;">
                                <i class="bi bi-x-circle me-1"></i> Clear Price Filter
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Toolbar -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white rounded-3 p-3 shadow-sm">
                <div>
                    <h5 class="mb-0 fw-bold">
                        @if(request('category'))
                            {{ $categories->firstWhere('id', request('category'))?->name ?? 'Products' }}
                        @else
                            All Products
                        @endif
                    </h5>
                    <small class="text-muted">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small text-nowrap">Sort by:</label>
                    <select class="form-select form-select-sm" style="width: auto; border-radius: 8px;" onchange="window.location.href=this.value">
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>

            <!-- Products -->
            <div class="row g-3">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-6">
                        @include('customer.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="ss-empty-state">
                            <div class="icon"><i class="bi bi-search"></i></div>
                            <h5>No products found</h5>
                            <p>Try adjusting your filters or search criteria.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary" style="border-radius: 8px;">
                                <i class="bi bi-arrow-left me-1"></i> Browse All Products
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
