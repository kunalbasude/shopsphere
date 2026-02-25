@extends('customer.layouts.app')
@section('title', 'My Wishlist')
@section('content')
<div class="container">
    <h4 class="mb-4">My Wishlist</h4>
    <div class="row g-3">
        @forelse($wishlists as $wishlist)
            <div class="col-md-3">
                <div class="card product-card h-100">
                    <a href="{{ route('shop.show', $wishlist->product->slug) }}">
                        <img src="{{ $wishlist->product->primary_image ? asset($wishlist->product->primary_image->image_path) : 'https://via.placeholder.com/300x220' }}" class="card-img-top" alt="">
                    </a>
                    <div class="card-body">
                        <h6><a href="{{ route('shop.show', $wishlist->product->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($wishlist->product->name, 35) }}</a></h6>
                        <span class="fw-bold text-primary">${{ number_format($wishlist->product->price, 2) }}</span>
                        <div class="mt-2 d-flex gap-1">
                            <button class="btn btn-sm btn-primary flex-grow-1 btn-move-to-cart" data-wishlist-id="{{ $wishlist->id }}">Move to Cart</button>
                            <button class="btn btn-sm btn-outline-danger btn-wishlist-toggle" data-product-id="{{ $wishlist->product_id }}"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-heart fs-1 text-muted"></i>
                <p class="text-muted mt-2">Your wishlist is empty.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse Products</a>
            </div>
        @endforelse
    </div>
    {{ $wishlists->links() }}
</div>
@endsection
