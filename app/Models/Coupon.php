<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value',
        'max_discount', 'min_purchase', 'usage_limit', 'used_count',
        'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        return $this->is_active
            && now()->between($this->start_date, $this->end_date)
            && ($this->usage_limit === 0 || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_purchase) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = $subtotal * ($this->discount_value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            $discount = $this->discount_value;
        }

        return round(min($discount, $subtotal), 2);
    }
}
