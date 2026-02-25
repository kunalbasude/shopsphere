@extends('customer.layouts.app')
@section('title', 'Track Order')
@section('content')
<div class="container">
    <h4 class="mb-4">Track Order #{{ $order->order_number }}</h4>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @foreach($order->statusHistories->sortByDesc('created_at') as $history)
                <div class="d-flex gap-3 mb-3">
                    <div class="text-center" style="width: 40px;">
                        <i class="bi bi-circle-fill text-primary"></i>
                        @if(!$loop->last)<div style="width: 2px; height: 30px; background: #dee2e6; margin: 0 auto;"></div>@endif
                    </div>
                    <div>
                        <strong>{{ ucfirst($history->status) }}</strong>
                        <br><small class="text-muted">{{ $history->created_at->format('M d, Y h:i A') }}</small>
                        @if($history->comment)<br><small>{{ $history->comment }}</small>@endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
