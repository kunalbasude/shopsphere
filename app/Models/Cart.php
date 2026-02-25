<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_id',
        'reward_points_used',
    ];

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn ($item) => $item->unit_price * $item->quantity);
    }

    public function getDiscountAttribute(): float
    {
        if (!$this->coupon) {
            return 0;
        }

        $subtotal = $this->subtotal;

        if ($subtotal < $this->coupon->min_cart_value) {
            return 0;
        }

        if ($this->coupon->type === 'fixed') {
            return min($this->coupon->value, $subtotal);
        }

        $discount = ($subtotal * $this->coupon->value) / 100;

        if ($this->coupon->max_discount) {
            $discount = min($discount, $this->coupon->max_discount);
        }

        return round($discount, 2);
    }

    public function getRewardDiscountAttribute(): float
    {
        return $this->reward_points_used * config('shopsphere.reward_points.value', 0.01);
    }

    public function getTotalAttribute(): float
    {
        return max(0, $this->subtotal - $this->discount - $this->reward_discount);
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
