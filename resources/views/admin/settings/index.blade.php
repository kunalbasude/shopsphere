@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Settings</h4>
    <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">Add Setting</a>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    @foreach($settings as $group => $groupSettings)
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ ucwords(str_replace('_', ' ', $group)) }}</h5>
            </div>
            <div class="card-body">
                @foreach($groupSettings as $setting)
                    <div class="mb-3">
                        <label class="form-label">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                        
                        @if($setting->type == 'textarea')
                            <textarea class="form-control" name="{{ $setting->key }}" rows="4">{{ $setting->value }}</textarea>
                        @elseif($setting->type == 'boolean')
                            <div class="form-check form-switch">
                                <input type="hidden" name="{{ $setting->key }}" value="0">
                                <input class="form-check-input" type="checkbox" name="{{ $setting->key }}" value="1" {{ $setting->value ? 'checked' : '' }}>
                            </div>
                        @elseif($setting->type == 'number')
                            <input type="number" class="form-control" name="{{ $setting->key }}" value="{{ $setting->value }}">
                        @else
                            <input type="text" class="form-control" name="{{ $setting->key }}" value="{{ $setting->value }}">
                        @endif
                        
                        <div class="d-flex justify-content-end mt-1">
                            <form action="{{ route('admin.settings.destroy', $setting) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this setting?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
@endsection
