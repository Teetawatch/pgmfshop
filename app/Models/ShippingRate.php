<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'name',
        'min_quantity',
        'max_quantity',
        'price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the shipping cost for a given quantity of items.
     */
    public static function getCostForQuantity(int $quantity): float
    {
        $rate = static::where('is_active', true)
            ->where('min_quantity', '<=', $quantity)
            ->where(function ($q) use ($quantity) {
                $q->whereNull('max_quantity')
                  ->orWhere('max_quantity', '>=', $quantity);
            })
            ->orderBy('sort_order')
            ->first();

        return $rate ? (float) $rate->price : 0;
    }

    /**
     * Get all active rates ordered by sort_order.
     */
    public static function getActiveRates()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
