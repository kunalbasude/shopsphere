<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Value</label>
        <input type="number" name="value" class="form-control" step="0.01" value="{{ old('value', $coupon->value ?? '') }}" required>
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Min Cart Value</label>
        <input type="number" name="min_cart_value" class="form-control" step="0.01" value="{{ old('min_cart_value', $coupon->min_cart_value ?? '0') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Max Discount</label>
        <input type="number" name="max_discount" class="form-control" step="0.01" value="{{ old('max_discount', $coupon->max_discount ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Usage Limit</label>
        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Per User Limit</label>
        <input type="number" name="per_user_limit" class="form-control" value="{{ old('per_user_limit', $coupon->per_user_limit ?? '1') }}">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Starts At</label>
        <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', isset($coupon) && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Expires At</label>
        <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)<p class="mb-0">{{ $error }}</p>@endforeach
    </div>
@endif
