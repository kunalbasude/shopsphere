<div class="card ss-product-card h-100">
    <div class="card-img-wrapper">
        @if($product->discount_percentage)
            <span class="badge-discount">-{{ $product->discount_percentage }}%</span>
        @endif
        @auth
            <button class="btn-wishlist btn-wishlist-toggle" data-product-id="{{ $product->id }}">
                <i class="bi bi-heart"></i>
            </button>
        @endauth
        <a href="{{ route('shop.show', $product->slug) }}">
            <img src="{{ $product->primary_image ? asset($product->primary_image->image_path) : 'https://via.placeholder.com/400x300/f1f5f9/94a3b8?text=' . urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
        </a>
    </div>
    <div class="card-body d-flex flex-column">
        <span class="product-vendor">{{ $product->vendor->shop_name ?? '' }}</span>
        <h6 class="product-title">
            <a href="{{ route('shop.show', $product->slug) }}">{{ Str::limit($product->name, 45) }}</a>
        </h6>
        <div class="d-flex align-items-center gap-1 mb-2">
            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi {{ $i <= ($product->average_rating ?? 0) ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
            </div>
            <small class="text-muted">({{ $product->reviews_count ?? $product->reviews->count() ?? 0 }})</small>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="product-price">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_price)
                <span class="product-price old-price">${{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
        <button class="btn btn-primary btn-add-cart w-100 mt-auto btn-add-to-cart" data-product-id="{{ $product->id }}">
            <i class="bi bi-cart-plus me-1"></i> Add to Cart
        </button>
    </div>
</div>
