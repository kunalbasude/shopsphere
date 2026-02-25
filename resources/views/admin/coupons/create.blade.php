@extends('admin.layouts.app')
@section('title', 'Create Coupon')
@section('content')
<h4 class="mb-4">Create Coupon</h4>
<div class="card stat-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf
            @include('admin.coupons._form')
            <button type="submit" class="btn btn-primary">Create Coupon</button>
        </form>
    </div>
</div>
@endsection
