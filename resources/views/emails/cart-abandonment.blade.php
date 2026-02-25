<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: #0d6efd; color: #fff; text-align: center; padding: 20px; }
        .content { padding: 30px; }
        .btn { display: inline-block; background: #0d6efd; color: #fff; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .footer { text-align: center; padding: 20px; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ShopSphere</h1>
        </div>
        <div class="content">
            <h2>You left something behind!</h2>
            <p>Hi {{ $userName }},</p>
            <p>You have {{ $itemCount }} item(s) in your cart worth <strong>${{ number_format($cartTotal, 2) }}</strong>. Complete your purchase before they're gone!</p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $cartUrl }}" class="btn">Complete Your Purchase</a>
            </p>
            <p>If you have any questions, reply to this email or contact us at support@shopsphere.com.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ShopSphere. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
