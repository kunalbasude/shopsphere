@extends('customer.layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="card border-0">
                @php $primaryImg = $product->primary_image; @endphp
                <img id="mainImage" src="{{ $primaryImg ? asset($primaryImg->image_path) : 'https://via.placeholder.com/500x400' }}" class="card-img-top rounded" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
            </div>
            @if($product->images->count() > 1)
                <div class="d-flex gap-2 mt-2 overflow-auto">
                    @foreach($product->images as $image)
                        <img src="{{ asset($image->image_path) }}" class="rounded border" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('mainImage').src=this.src" alt="">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h3>{{ $product->name }}</h3>
            <p class="text-muted">by <a href="{{ route('shop.vendor', $product->vendor->slug) }}">{{ $product->vendor->shop_name }}</a> | SKU: {{ $product->sku }}</p>

            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $product->average_rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>
                <span class="text-muted">({{ $product->reviews->count() }} reviews)</span>
            </div>

            <div class="mb-3">
                <span class="fs-3 fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                    <span class="fs-5 text-muted text-decoration-line-through ms-2">${{ number_format($product->compare_price, 2) }}</span>
                    <span class="badge bg-danger ms-2">-{{ $product->discount_percentage }}%</span>
                @endif
            </div>

            <p>{{ $product->short_description }}</p>

            <div class="mb-3">
                <span class="badge {{ $product->isInStock() ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->isInStock() ? 'In Stock ('.$product->quantity.')' : 'Out of Stock' }}
                </span>
            </div>

            <!-- Variants -->
            @if($product->variants->count())
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Variant</label>
                    <select id="variantSelect" class="form-select">
                        <option value="">-- Select --</option>
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                {{ $variant->name }} - ${{ number_format($variant->price, 2) }} ({{ $variant->quantity }} left)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="d-flex gap-2 mb-4">
                <div class="input-group" style="width: 130px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                </div>
                <button class="btn btn-primary flex-grow-1" id="addToCartBtn" data-product-id="{{ $product->id }}">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
                @auth
                    <button class="btn btn-outline-danger btn-wishlist-toggle" data-product-id="{{ $product->id }}">
                        <i class="bi bi-heart"></i>
                    </button>
                @endauth
            </div>

            <!-- Description -->
            <div class="mt-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reviews">Reviews ({{ $product->reviews->count() }})</a></li>
                </ul>
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="description">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                    <div class="tab-pane fade" id="reviews">
                        @forelse($product->reviews->where('is_approved', true) as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $review->user->name }}</strong>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }} small"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 mt-1">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-muted">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count())
        <h5 class="mt-5 mb-3">Related Products</h5>
        <div class="row g-3">
            @foreach($relatedProducts as $related)
                <div class="col-md-3">
                    <div class="card product-card h-100">
                        <a href="{{ route('shop.show', $related->slug) }}">
                            <img src="{{ $related->primary_image ? asset($related->primary_image->image_path) : 'https://via.placeholder.com/300x220' }}" class="card-img-top" alt="{{ $related->name }}">
                        </a>
                        <div class="card-body">
                            <h6><a href="{{ route('shop.show', $related->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($related->name, 35) }}</a></h6>
                            <span class="fw-bold text-primary">${{ number_format($related->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('quantity');
    const newVal = Math.max(1, parseInt(input.value) + delta);
    input.value = newVal;
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
            alert(data.message);
        }
    });
});
</script>
@endpush
@endsection
