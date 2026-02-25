@extends('customer.layouts.app')
@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">{{ $page->title }}</h2>
            <div class="content">{!! $page->content !!}</div>
        </div>
    </div>
</div>
@endsection
