@extends('customer.layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="container">
    <h4 class="mb-4">Checkout</h4>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Shipping Information</h6>
                    <form id="checkoutForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Zip Code</label>
                                <input type="text" name="zip" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <h6 class="fw-bold mb-3">Payment Method</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="stripe" id="payStripe" checked>
                            <label class="form-check-label" for="payStripe">Stripe (Credit/Debit Card)</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" value="razorpay" id="payRazorpay">
                            <label class="form-check-label" for="payRazorpay">Razorpay</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg" id="placeOrderBtn">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Order Summary</h6>
                    <hr>
                    @foreach($cartSummary['items'] as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ Str::limit($item->product->name, 30) }} x {{ $item->quantity }}</span>
                            <span>${{ number_format($item->line_total, 2) }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Subtotal</span>
                        <span>${{ number_format($cartSummary['subtotal'], 2) }}</span>
                    </div>
                    @if($cartSummary['discount'] > 0)
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Discount</span>
                            <span>-${{ number_format($cartSummary['discount'], 2) }}</span>
                        </div>
                    @endif
                    @if($cartSummary['reward_discount'] > 0)
                        <div class="d-flex justify-content-between mb-1 text-info">
                            <span>Reward Points</span>
                            <span>-${{ number_format($cartSummary['reward_discount'], 2) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>${{ number_format($cartSummary['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/checkout.js') }}"></script>
@endpush
@endsection
