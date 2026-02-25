<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #0d6efd; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .totals { margin-top: 20px; text-align: right; }
        .totals td { border: none; padding: 4px 12px; }
        .totals .grand-total { font-size: 18px; font-weight: bold; color: #0d6efd; }
        .footer { text-align: center; margin-top: 30px; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ShopSphere</h1>
        <p>Invoice</p>
    </div>

    <p><strong>Order:</strong> {{ $order->order_number }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
    <p><strong>Customer:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>

    <p><strong>Ship To:</strong><br>
        {{ $order->shipping_name }}<br>
        {{ $order->shipping_address }}<br>
        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
        {{ $order->shipping_country }}
    </p>

    <table>
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}@if($item->variant_name) ({{ $item->variant_name }})@endif</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal:</td><td>${{ number_format($order->subtotal, 2) }}</td></tr>
        @if($order->discount_amount > 0)
            <tr><td>Discount:</td><td>-${{ number_format($order->discount_amount, 2) }}</td></tr>
        @endif
        @if($order->reward_discount > 0)
            <tr><td>Reward Points:</td><td>-${{ number_format($order->reward_discount, 2) }}</td></tr>
        @endif
        <tr><td class="grand-total">Total:</td><td class="grand-total">${{ number_format($order->total, 2) }}</td></tr>
    </table>

    <div class="footer">
        <p>Thank you for shopping with ShopSphere!</p>
        <p>support@shopsphere.com</p>
    </div>
</body>
</html>
