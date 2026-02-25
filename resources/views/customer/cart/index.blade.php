@extends('customer.layouts.app')
@section('title', 'Shopping Cart')
@section('content')
<div class="container">
    <h4 class="mb-4">Shopping Cart</h4>

    <div class="row">
        <div class="col-md-8">
            <div id="cartItems">
                @forelse($cartSummary['items'] as $item)
                    <div class="card border-0 shadow-sm mb-3 cart-item" data-item-id="{{ $item->id }}">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <img src="{{ $item->product->primary_image ? asset($item->product->primary_image->image_path) : 'https://via.placeholder.com/80' }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;" alt="">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    @if($item->variant)
                                        <small class="text-muted">Variant: {{ $item->variant->name }}</small>
                                    @endif
                                    <p class="text-primary fw-bold mb-1">${{ number_format($item->unit_price, 2) }}</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-sm btn-outline-secondary btn-qty" data-item-id="{{ $item->id }}" data-delta="-1">-</button>
                                            <input type="text" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" readonly>
                                            <button class="btn btn-sm btn-outline-secondary btn-qty" data-item-id="{{ $item->id }}" data-delta="1">+</button>
                                        </div>
                                        <span class="fw-bold">${{ number_format($item->line_total, 2) }}</span>
                                        <button class="btn btn-sm btn-outline-danger ms-auto btn-remove-item" data-item-id="{{ $item->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Your cart is empty.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Order Summary</h6>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span id="subtotal">${{ number_format($cartSummary['subtotal'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount</span>
                        <span id="discount">-${{ number_format($cartSummary['discount'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-info">
                        <span>Reward Points</span>
                        <span id="rewardDiscount">-${{ number_format($cartSummary['reward_discount'], 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span id="total">${{ number_format($cartSummary['total'], 2) }}</span>
                    </div>

                    <!-- Coupon -->
                    <div class="mt-3">
                        <div class="input-group">
                            <input type="text" id="couponCode" class="form-control" placeholder="Coupon code">
                            <button class="btn btn-outline-primary" id="applyCouponBtn">Apply</button>
                        </div>
                        <div id="couponMessage" class="small mt-1"></div>
                    </div>

                    <!-- Reward Points -->
                    @auth
                        <div class="mt-3">
                            <div class="input-group">
                                <input type="number" id="rewardPoints" class="form-control" placeholder="Reward points" min="0">
                                <button class="btn btn-outline-info" id="applyRewardsBtn">Redeem</button>
                            </div>
                        </div>
                    @endauth

                    @if($cartSummary['items_count'] > 0)
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-3">Proceed to Checkout</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush
@endsection
