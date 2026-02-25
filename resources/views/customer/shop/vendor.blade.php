@extends('customer.layouts.app')
@section('title', $vendor->shop_name)
@section('content')
<div class="container">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            @if($vendor->logo)
                <img src="{{ asset($vendor->logo) }}" class="rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;" alt="">
            @endif
            <h4>{{ $vendor->shop_name }}</h4>
            <p class="text-muted">{{ $vendor->description }}</p>
        </div>
    </div>
    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-md-3">
                <div class="card product-card h-100">
                    <a href="{{ route('shop.show', $product->slug) }}">
                        <img src="{{ $product->primary_image ? asset($product->primary_image->image_path) : 'https://via.placeholder.com/300x220' }}" class="card-img-top" alt="{{ $product->name }}">
                    </a>
                    <div class="card-body">
                        <h6><a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($product->name, 35) }}</a></h6>
                        <span class="fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                        <button class="btn btn-primary btn-sm w-100 mt-2 btn-add-to-cart" data-product-id="{{ $product->id }}">Add to Cart</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5"><p class="text-muted">No products available.</p></div>
        @endforelse
    </div>
    {{ $products->links() }}
</div>
@endsection
