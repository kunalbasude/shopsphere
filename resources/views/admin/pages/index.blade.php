@extends('admin.layouts.app')
@section('title', 'CMS Pages')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>CMS Pages</h4>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Create Page</a>
</div>
<div class="card stat-card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td><code>/page/{{ $page->slug }}</code></td>
                        <td><span class="badge bg-{{ $page->is_active ? 'success' : 'secondary' }}">{{ $page->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No pages.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $pages->links() }}
    </div>
</div>
@endsection
