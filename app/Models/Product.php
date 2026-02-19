<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const TYPE_BOOK = 'book';
    const TYPE_CLOTHING = 'clothing';
    const TYPE_OTHER = 'other';

    const PRODUCT_TYPES = [
        self::TYPE_BOOK => 'หนังสือ',
        self::TYPE_CLOTHING => 'เสื้อผ้า',
        self::TYPE_OTHER => 'อื่นๆ',
    ];

    protected $fillable = [
        'category_id', 'product_type', 'name', 'slug', 'description',
        'publisher', 'genres', 'authors', 'pages',
        'sizes', 'colors', 'material', 'weight', 'sku',
        'price', 'original_price', 'stock', 'sold', 'rating', 'review_count', 'images', 'tags',
        'is_featured', 'is_new', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'images' => 'array',
        'tags' => 'array',
        'genres' => 'array',
        'authors' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function isBook(): bool
    {
        return $this->product_type === self::TYPE_BOOK;
    }

    public function isClothing(): bool
    {
        return $this->product_type === self::TYPE_CLOTHING;
    }

    public function isOther(): bool
    {
        return $this->product_type === self::TYPE_OTHER;
    }

    public function getProductTypeLabelAttribute(): string
    {
        return self::PRODUCT_TYPES[$this->product_type] ?? 'อื่นๆ';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Check if this product uses variant-level stock (clothing with variants)
     */
    public function hasVariants(): bool
    {
        return $this->isClothing() && $this->variants()->exists();
    }

    /**
     * Find a specific variant by size and color
     */
    public function findVariant(?string $size, ?string $color): ?ProductVariant
    {
        return $this->variants()
            ->where('size', $size ?: null)
            ->where('color', $color ?: null)
            ->first();
    }

    /**
     * Sync the product-level stock from the sum of all active variants
     */
    public function syncStockFromVariants(): void
    {
        if ($this->hasVariants()) {
            $this->stock = $this->variants()->where('is_active', true)->sum('stock');
            $this->saveQuietly();
        }
    }

    /**
     * Adjust stock with audit trail
     */
    public function adjustStock(int $quantity, string $type, ?string $reason = null, ?string $referenceType = null, ?string $referenceId = null, ?int $userId = null): StockMovement
    {
        $before = $this->stock;
        $this->stock = max(0, $before + $quantity);
        $this->save();

        return StockMovement::create([
            'product_id' => $this->id,
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

    public function updateRating(): void
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->review_count = $this->reviews()->count();
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true);
    }
}
