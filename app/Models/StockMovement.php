<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_id', 'user_id', 'type', 'quantity',
        'stock_before', 'stock_after',
        'reference_type', 'reference_id', 'reason',
    ];

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_ADJUST = 'adjust';
    const TYPE_RETURN = 'return';
    const TYPE_INITIAL = 'initial';

    const REF_ORDER = 'order';
    const REF_MANUAL = 'manual';
    const REF_IMPORT = 'import';
    const REF_RETURN = 'return';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_IN => 'รับเข้า',
            self::TYPE_OUT => 'จ่ายออก',
            self::TYPE_ADJUST => 'ปรับสต็อก',
            self::TYPE_RETURN => 'คืนสต็อก',
            self::TYPE_INITIAL => 'ตั้งต้น',
            default => $this->type,
        };
    }

    public function getReferenceLabel(): string
    {
        return match ($this->reference_type) {
            self::REF_ORDER => 'คำสั่งซื้อ',
            self::REF_MANUAL => 'ปรับมือ',
            self::REF_IMPORT => 'นำเข้า',
            self::REF_RETURN => 'คืนสินค้า',
            default => $this->reference_type ?? '-',
        };
    }
}
