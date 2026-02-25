@extends('admin.layouts.app')
@section('title', 'Brands')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Brands</h4>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Create Brand</a>
</div>
<div class="card stat-card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Logo</th><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 50px;">
                            @else
                                <span class="text-muted">No logo</span>
                            @endif
                        </td>
                        <td><strong>{{ $brand->name }}</strong></td>
                        <td>{{ $brand->slug }}</td>
                        <td>{{ $brand->products_count ?? 0 }}</td>
                        <td><span class="badge bg-{{ $brand->is_active ? 'success' : 'secondary' }}">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No brands found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $brands->links() }}
    </div>
</div>
@endsection
