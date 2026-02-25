<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('uploader')->latest();

        if ($request->filled('search')) {
            $query->where('original_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('mime_type', 'like', $request->type . '/%');
        }

        $media = $query->paginate(24)->withQueryString();

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:10240', // 10MB max per file
        ]);

        $uploaded = 0;

        foreach ($request->file('files') as $file) {
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $filename, 'public');

            Media::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'path' => $path,
                'size' => $file->getSize(),
                'disk' => 'public',
                'uploaded_by' => auth()->id(),
            ]);

            $uploaded++;
        }

        return redirect()->route('admin.media.index')
            ->with('success', $uploaded . ' file(s) uploaded successfully.');
    }

    public function destroy(Media $medium)
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'File deleted successfully.');
    }
}
