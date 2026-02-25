@extends('admin.layouts.app')
@section('title', 'Create Plan')
@section('content')
<h4 class="mb-4">Create Subscription Plan</h4>
<div class="card stat-card"><div class="card-body">
    <form method="POST" action="{{ route('admin.plans.store') }}">
        @csrf @include('admin.plans._form')
        <button type="submit" class="btn btn-primary">Create Plan</button>
    </form>
</div></div>
@endsection
