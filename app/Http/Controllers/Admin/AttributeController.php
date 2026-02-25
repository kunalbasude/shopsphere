<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::latest()->paginate(20);
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attributes,slug',
            'type' => 'required|in:select,radio,checkbox,text,color',
            'values' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully');
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attributes,slug,' . $attribute->id,
            'type' => 'required|in:select,radio,checkbox,text,color',
            'values' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully');
    }
}
