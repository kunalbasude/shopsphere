@extends('customer.layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)
@section('content')

<!-- Breadcrumb -->
<div class="bg-white border-bottom">
    <div class="container py-3">
        <nav aria-label="breadcrumb" class="ss-breadcrumb mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row g-5">
        <!-- Product Gallery -->
        <div class="col-lg-6">
            <div class="ss-product-gallery">
                <div class="main-image">
                    @php $primaryImg = $product->primary_image; @endphp
                    <img id="mainImage" src="{{ $primaryImg ? asset($primaryImg->image_path) : 'https://via.placeholder.com/600x450/f1f5f9/94a3b8?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                </div>
                @if($product->images->count() > 1)
                    <div class="thumb-list">
                        @foreach($product->images as $image)
                            <div class="thumb-item {{ $loop->first ? 'active' : '' }}" onclick="changeMainImage(this, '{{ asset($image->image_path) }}')">
                                <img src="{{ asset($image->image_path) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="ss-product-info">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="{{ route('shop.index', ['category' => $product->category_id]) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none" style="border-radius: 50px; padding: 0.4em 0.9em;">
                        {{ $product->category->name }}
                    </a>
                    @if($product->is_featured)
                        <span class="badge bg-warning bg-opacity-10 text-warning" style="border-radius: 50px; padding: 0.4em 0.9em;">
                            <i class="bi bi-star-fill me-1"></i>Featured
                        </span>
                    @endif
                </div>

                <h1 class="product-title">{{ $product->name }}</h1>

                <p class="product-meta mt-2">
                    by <a href="{{ route('shop.vendor', $product->vendor->slug) }}">{{ $product->vendor->shop_name }}</a>
                    <span class="mx-2">|</span>
                    SKU: {{ $product->sku }}
                </p>

                <!-- Rating -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $product->average_rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <span class="text-muted">({{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }})</span>
                    <span class="mx-1">|</span>
                    <span class="text-muted"><i class="bi bi-eye me-1"></i>{{ number_format($product->views_count) }} views</span>
                </div>

                <!-- Price -->
                <div class="price-block d-flex align-items-center gap-3 mb-4 p-3 bg-light" style="border-radius: 12px;">
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                    @if($product->compare_price)
                        <span class="old-price">${{ number_format($product->compare_price, 2) }}</span>
                        <span class="discount-badge">-{{ $product->discount_percentage }}% OFF</span>
                    @endif
                </div>

                <!-- Short Description -->
                <p class="text-muted mb-3" style="line-height: 1.7;">{{ $product->short_description }}</p>

                <!-- Stock Status -->
                <div class="mb-4">
                    <span class="stock-badge {{ $product->isInStock() ? 'in-stock' : 'out-of-stock' }}">
                        <i class="bi {{ $product->isInStock() ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                        {{ $product->isInStock() ? 'In Stock ('.$product->quantity.' available)' : 'Out of Stock' }}
                    </span>
                </div>

                <!-- Variants -->
                @if($product->variants->count())
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Select Variant</label>
                        <select id="variantSelect" class="form-select" style="border-radius: 10px;">
                            <option value="">-- Choose an option --</option>
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                    {{ $variant->name }} - ${{ number_format($variant->price, 2) }} ({{ $variant->quantity }} left)
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Add to Cart -->
                <div class="d-flex gap-3 align-items-center mb-4">
                    <div class="ss-qty-input">
                        <button type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" id="quantity" value="1" min="1">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                    <button class="btn ss-btn-primary flex-grow-1" id="addToCartBtn" data-product-id="{{ $product->id }}" {{ !$product->isInStock() ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus me-2"></i> Add to Cart
                    </button>
                    @auth
                        <button class="btn btn-outline-danger btn-wishlist-toggle" data-product-id="{{ $product->id }}" style="border-radius: 10px; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-heart fs-5"></i>
                        </button>
                    @endauth
                </div>

                <!-- Product Meta Info -->
                <div class="border-top pt-3">
                    <div class="row g-3">
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-truck"></i> Free shipping over $50
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-shield-check"></i> Secure checkout
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-arrow-counterclockwise"></i> 30-day returns
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs: Description & Reviews -->
    <div class="ss-tabs mt-5">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#description">
                    <i class="bi bi-file-text me-1"></i> Description
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#reviews">
                    <i class="bi bi-chat-dots me-1"></i> Reviews ({{ $product->reviews->where('is_approved', true)->count() }})
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="description">
                <div class="bg-white rounded-3 p-4 shadow-sm">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
            <div class="tab-pane fade" id="reviews">
                <div class="bg-white rounded-3 p-4 shadow-sm">
                    @forelse($product->reviews->where('is_approved', true) as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: 600;">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }} small"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 mt-2 ms-5">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots fs-1 text-muted opacity-50"></i>
                            <p class="text-muted mt-2">No reviews yet. Be the first to review this product!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count())
        <section class="mt-5">
            <div class="ss-section-header">
                <h2>You May Also Like</h2>
                <p>Similar products in {{ $product->category->name }}</p>
            </div>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-lg-3 col-md-4 col-6">
                        @include('customer.partials.product-card', ['product' => $related])
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('quantity');
    const newVal = Math.max(1, parseInt(input.value) + delta);
    input.value = newVal;
}

function changeMainImage(thumb, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

document.getElementById('addToCartBtn').addEventListener('click', function() {
    const productId = this.dataset.productId;
    const quantity = document.getElementById('quantity').value;
    const variantSelect = document.getElementById('variantSelect');
    const variantId = variantSelect ? variantSelect.value : null;

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity),
            variant_id: variantId || null,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cartCount').textContent = data.cart.items_count;
            showToast(data.message);
        }
    });
});
</script>
@endpush
@endsection
