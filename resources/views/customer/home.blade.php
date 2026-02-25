@extends('customer.layouts.app')
@section('title', 'Welcome to ShopSphere')
@section('meta_description', 'ShopSphere - Your trusted multi-vendor electronics marketplace. Shop smartphones, laptops, headphones and more.')
@section('content')

<!-- Hero Section -->
<section class="ss-hero">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-white text-primary fw-semibold px-3 py-2 mb-3" style="border-radius: 50px;">New Arrivals 2025</span>
                <h1>Discover the Latest Electronics</h1>
                <p class="mt-3 mb-4">Shop premium smartphones, laptops, headphones and more from trusted vendors. Free shipping on orders over $50.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('shop.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-bag me-2"></i>Shop Now
                    </a>
                    <a href="{{ route('shop.index', ['sort' => 'popular']) }}" class="btn btn-outline-light btn-lg">
                        Trending
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <div class="position-relative d-inline-block">
                    <div class="bg-white rounded-4 p-4 shadow-lg" style="max-width: 400px;">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-phone text-primary" style="font-size: 2.5rem;"></i>
                                    <div class="fw-bold text-dark mt-1">Phones</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-laptop text-danger" style="font-size: 2.5rem;"></i>
                                    <div class="fw-bold text-dark mt-1">Laptops</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-headphones text-success" style="font-size: 2.5rem;"></i>
                                    <div class="fw-bold text-dark mt-1">Audio</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="bi bi-smartwatch text-warning" style="font-size: 2.5rem;"></i>
                                    <div class="fw-bold text-dark mt-1">Watches</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Badges -->
<section class="py-4" style="margin-top: -2rem; position: relative; z-index: 3;">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="ss-feature-badge">
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h6>Free Shipping</h6>
                        <small>On orders over $50</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="ss-feature-badge">
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h6>Secure Payment</h6>
                        <small>100% protected</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="ss-feature-badge">
                    <div class="icon-wrapper bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </div>
                    <div>
                        <h6>Easy Returns</h6>
                        <small>30-day returns</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="ss-feature-badge">
                    <div class="icon-wrapper bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <h6>24/7 Support</h6>
                        <small>Dedicated help</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Browse Categories -->
<section class="py-5">
    <div class="container">
        <div class="ss-section-header d-flex justify-content-between align-items-end">
            <div>
                <h2>Browse Categories</h2>
                <p>Find what you need from our wide selection</p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            @php
                $categoryIcons = [
                    'electronics' => ['bi-cpu', 'cat-smartphones'],
                    'smartphones' => ['bi-phone', 'cat-smartphones'],
                    'laptops' => ['bi-laptop', 'cat-laptops'],
                    'headphones' => ['bi-headphones', 'cat-headphones'],
                    'tablets' => ['bi-tablet', 'cat-tablets'],
                    'smartwatches' => ['bi-smartwatch', 'cat-smartwatches'],
                    'cameras' => ['bi-camera', 'cat-cameras'],
                    'fashion' => ['bi-bag-heart', 'cat-fashion'],
                    'home-garden' => ['bi-house', 'cat-home'],
                    'sports-outdoors' => ['bi-bicycle', 'cat-smartphones'],
                    'books' => ['bi-book', 'cat-laptops'],
                    'health-beauty' => ['bi-heart-pulse', 'cat-cameras'],
                ];
            @endphp
            @foreach($categories as $category)
                <div class="col-lg-2 col-md-3 col-4">
                    <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="ss-category-card {{ $categoryIcons[$category->slug][1] ?? 'cat-smartphones' }}">
                        <div class="category-overlay">
                            <i class="bi {{ $categoryIcons[$category->slug][0] ?? 'bi-grid' }} category-icon"></i>
                            <h5>{{ $category->name }}</h5>
                            <small>{{ $category->products_count ?? '' }} {{ Str::plural('product', $category->products_count ?? 0) }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
@if($featuredProducts->count())
<section class="py-5 bg-white">
    <div class="container">
        <div class="ss-section-header d-flex justify-content-between align-items-end">
            <div>
                <h2>Featured Products</h2>
                <p>Handpicked products just for you</p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                See All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    @include('customer.partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Products -->
@if($latestProducts->count())
<section class="py-5">
    <div class="container">
        <div class="ss-section-header d-flex justify-content-between align-items-end">
            <div>
                <h2>New Arrivals</h2>
                <p>The latest additions to our store</p>
            </div>
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    @include('customer.partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter / CTA -->
<section class="py-5">
    <div class="container">
        <div class="ss-newsletter text-center">
            <h4 class="mb-2">Stay Updated with Latest Deals</h4>
            <p class="mb-4 opacity-75">Get notified about new products and exclusive offers.</p>
            <div class="input-group mx-auto">
                <input type="email" class="form-control" placeholder="Enter your email address">
                <button class="btn btn-dark" type="button">Subscribe</button>
            </div>
        </div>
    </div>
</section>

@endsection
