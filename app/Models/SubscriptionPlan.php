<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'product_limit',
        'commission_rate',
        'featured_products',
        'analytics_access',
        'priority_support',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'featured_products' => 'boolean',
        'analytics_access' => 'boolean',
        'priority_support' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function isFree(): bool
    {
        return $this->price == 0;
    }

    public function vendorSubscriptions()
    {
        return $this->hasMany(VendorSubscription::class);
    }
}
