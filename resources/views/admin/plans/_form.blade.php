<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Plan Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $plan->price ?? '0') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Billing Cycle</label>
        <select name="billing_cycle" class="form-select">
            <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $plan->description ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Product Limit</label>
        <input type="number" name="product_limit" class="form-control" value="{{ old('product_limit', $plan->product_limit ?? '10') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Commission Rate (%)</label>
        <input type="number" name="commission_rate" class="form-control" step="0.01" value="{{ old('commission_rate', $plan->commission_rate ?? '10') }}" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <div class="form-check">
            <input type="hidden" name="featured_products" value="0">
            <input type="checkbox" name="featured_products" value="1" class="form-check-input" {{ old('featured_products', $plan->featured_products ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Featured Products</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-check">
            <input type="hidden" name="analytics_access" value="0">
            <input type="checkbox" name="analytics_access" value="1" class="form-check-input" {{ old('analytics_access', $plan->analytics_access ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Analytics Access</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-check">
            <input type="hidden" name="priority_support" value="0">
            <input type="checkbox" name="priority_support" value="1" class="form-check-input" {{ old('priority_support', $plan->priority_support ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Priority Support</label>
        </div>
    </div>
</div>
@if($errors->any())
    <div class="alert alert-danger">@foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach</div>
@endif
