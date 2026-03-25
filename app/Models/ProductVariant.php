<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'size', 'color', 'stock', 'sku', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'variant_id');
    }

    /**
     * Get display label like "M / ดำ" or "M" or "ดำ"
     */
    public function getLabel(): string
    {
        $parts = [];
        if ($this->size) $parts[] = $this->size;
        if ($this->color) $parts[] = $this->color;
        return implode(' / ', $parts) ?: '-';
    }

    /**
     * Adjust stock with audit trail (variant-level)
     */
    public function adjustStock(int $quantity, string $type, ?string $reason = null, ?string $referenceType = null, ?string $referenceId = null, ?int $userId = null): StockMovement
    {
        $before = $this->stock;
        $this->stock = max(0, $before + $quantity);
        $this->save();

        // Also sync the parent product's total stock
        $this->product->syncStockFromVariants();

        return StockMovement::create([
            'product_id' => $this->product_id,
            'variant_id' => $this->id,
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $before,
            'stock_after' => $this->stock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'reason' => $reason,
        ]);
    }
}
