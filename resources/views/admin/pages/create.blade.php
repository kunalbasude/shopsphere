@extends('admin.layouts.app')
@section('title', 'Create Page')
@section('content')
<h4 class="mb-4">Create Page</h4>
<div class="card stat-card"><div class="card-body">
    <form method="POST" action="{{ route('admin.pages.store') }}">
        @csrf @include('admin.pages._form')
        <button type="submit" class="btn btn-primary">Create Page</button>
    </form>
</div></div>
@endsection
