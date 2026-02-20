<?php

return [

    'commission_rate' => env('DEFAULT_COMMISSION_RATE', 10),

    'reward_points' => [
        'per_dollar' => env('REWARD_POINTS_PER_DOLLAR', 1),
        'value' => env('REWARD_POINTS_VALUE', 0.01),
    ],

    'cart_abandonment' => [
        'hours' => env('CART_ABANDONMENT_HOURS', 24),
    ],

    'payment' => [
        'default' => env('PAYMENT_GATEWAY', 'stripe'),

        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],

        'razorpay' => [
            'key' => env('RAZORPAY_KEY'),
            'secret' => env('RAZORPAY_SECRET'),
            'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
        ],
    ],

    'subscription_plans' => [
        'free_product_limit' => 10,
    ],

];
