@extends('admin.layouts.app')
@section('title', 'Attributes')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Attributes</h4>
    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">Create Attribute</a>
</div>
<div class="card stat-card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Name</th><th>Type</th><th>Values</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($attributes as $attribute)
                    <tr>
                        <td><strong>{{ $attribute->name }}</strong></td>
                        <td>{{ ucfirst($attribute->type) }}</td>
                        <td>{{ is_array($attribute->values) ? implode(', ', $attribute->values) : '-' }}</td>
                        <td><span class="badge bg-{{ $attribute->is_active ? 'success' : 'secondary' }}">{{ $attribute->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No attributes found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $attributes->links() }}
    </div>
</div>
@endsection
