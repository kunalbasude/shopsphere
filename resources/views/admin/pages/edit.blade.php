@extends('admin.layouts.app')
@section('title', 'Edit Page')
@section('content')
<h4 class="mb-4">Edit Page: {{ $page->title }}</h4>
<div class="card stat-card"><div class="card-body">
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf @method('PUT') @include('admin.pages._form')
        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $page->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Update Page</button>
    </form>
</div></div>
@endsection
