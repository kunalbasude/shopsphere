@extends('admin.layouts.app')
@section('title', 'Media Manager')

@push('styles')
<style>
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .media-card { border: 2px solid transparent; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.06); transition: all 0.2s; cursor: pointer; }
    .media-card:hover { border-color: #0d6efd; box-shadow: 0 4px 12px rgba(13,110,253,0.15); }
    .media-thumb { width: 100%; height: 160px; object-fit: cover; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
    .media-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .media-thumb .file-icon { font-size: 3rem; color: #94a3b8; }
    .media-info { padding: 10px 12px; }
    .media-info .filename { font-size: 0.82rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .media-info .meta { font-size: 0.75rem; color: #94a3b8; }
    .permalink-input { font-size: 0.82rem; }
    .drop-zone { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px; text-align: center; background: #f8fafc; transition: all 0.2s; cursor: pointer; }
    .drop-zone.dragover { border-color: #0d6efd; background: #eff6ff; }
    .drop-zone i { font-size: 2.5rem; color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Media Manager</h4>
        <small class="text-muted">Upload files and copy permalinks</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload me-1"></i> Upload Files
    </button>
</div>

{{-- Filters --}}
<div class="card mb-4" style="border:none; border-radius:12px;">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-3 align-items-center">
            <div class="flex-grow-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by filename..." value="{{ request('search') }}">
            </div>
            <select name="type" class="form-select form-select-sm" style="width:auto;">
                <option value="">All Types</option>
                <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
                <option value="application" {{ request('type') === 'application' ? 'selected' : '' }}>Documents</option>
            </select>
            <button class="btn btn-sm btn-outline-primary">Filter</button>
            @if(request()->hasAny(['search', 'type']))
                <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Media Grid --}}
@if($media->count())
    <div class="media-grid mb-4">
        @foreach($media as $item)
            <div class="media-card" data-bs-toggle="modal" data-bs-target="#mediaDetail{{ $item->id }}">
                <div class="media-thumb">
                    @if($item->is_image)
                        <img src="{{ $item->url }}" alt="{{ $item->original_name }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 w-100">
                            @if(str_contains($item->mime_type, 'pdf'))
                                <i class="bi bi-file-earmark-pdf file-icon text-danger"></i>
                            @elseif(str_contains($item->mime_type, 'video'))
                                <i class="bi bi-file-earmark-play file-icon text-primary"></i>
                            @elseif(str_contains($item->mime_type, 'zip') || str_contains($item->mime_type, 'rar'))
                                <i class="bi bi-file-earmark-zip file-icon text-warning"></i>
                            @else
                                <i class="bi bi-file-earmark file-icon"></i>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="media-info">
                    <div class="filename" title="{{ $item->original_name }}">{{ $item->original_name }}</div>
                    <div class="meta">{{ $item->human_size }} &middot; {{ $item->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Detail Modal --}}
            <div class="modal fade" id="mediaDetail{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                        <div class="modal-body p-0">
                            @if($item->is_image)
                                <img src="{{ $item->url }}" class="w-100" style="max-height:360px; object-fit:contain; background:#f1f5f9;" alt="">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height:200px; background:#f1f5f9;">
                                    <i class="bi bi-file-earmark" style="font-size:4rem; color:#94a3b8;"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <h6 class="mb-1">{{ $item->original_name }}</h6>
                                <p class="text-muted small mb-3">{{ $item->mime_type }} &middot; {{ $item->human_size }} &middot; Uploaded {{ $item->created_at->format('M d, Y') }}</p>
                                <label class="form-label small fw-semibold">Permalink</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control permalink-input" value="{{ $item->url }}" readonly id="permalink{{ $item->id }}">
                                    <button class="btn btn-outline-primary btn-copy" type="button" onclick="copyPermalink({{ $item->id }})">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $item->url }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Open
                                    </a>
                                    <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this file permanently?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $media->links() }}
@else
    <div class="text-center py-5">
        <i class="bi bi-images" style="font-size:3rem; color:#cbd5e1;"></i>
        <p class="text-muted mt-2">No media files found. Upload your first file!</p>
    </div>
@endif

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="drop-zone mb-3" id="dropZone">
                        <i class="bi bi-cloud-arrow-up d-block mb-2"></i>
                        <p class="mb-1 fw-semibold">Drag & drop files here</p>
                        <p class="text-muted small mb-2">or click to browse</p>
                        <input type="file" name="files[]" multiple id="fileInput" class="d-none" accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                        <span class="text-muted" style="font-size:0.75rem;">Max 10MB per file</span>
                    </div>
                    <div id="fileList" class="mb-3"></div>
                    <button type="submit" class="btn btn-primary w-100" id="uploadBtn" disabled>
                        <i class="bi bi-cloud-upload me-1"></i> Upload
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const fileList = document.getElementById('fileList');
const uploadBtn = document.getElementById('uploadBtn');

if (dropZone) {
    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        fileInput.files = e.dataTransfer.files;
        showFileList(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', () => {
        showFileList(fileInput.files);
    });
}

function showFileList(files) {
    fileList.innerHTML = '';
    if (files.length === 0) { uploadBtn.disabled = true; return; }
    uploadBtn.disabled = false;
    Array.from(files).forEach(f => {
        const size = (f.size / 1024).toFixed(1);
        const el = document.createElement('div');
        el.className = 'd-flex justify-content-between align-items-center py-1 border-bottom';
        el.innerHTML = `<small class="text-truncate me-2">${f.name}</small><small class="text-muted text-nowrap">${size} KB</small>`;
        fileList.appendChild(el);
    });
}

function copyPermalink(id) {
    const input = document.getElementById('permalink' + id);
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = input.nextElementSibling;
        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        setTimeout(() => { btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy'; }, 2000);
    });
}

// Disable button while uploading
const uploadForm = document.getElementById('uploadForm');
if (uploadForm) {
    uploadForm.addEventListener('submit', function() {
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Uploading...';
    });
}
</script>
@endpush
