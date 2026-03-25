<?php

namespace Tests\Unit;

use App\Models\OrderItem;
use PHPUnit\Framework\TestCase;

class OrderItemModelTest extends TestCase
{
    public function test_fillable_attributes(): void
    {
        $item = new OrderItem();
        $fillable = $item->getFillable();
        $this->assertContains('order_id', $fillable);
        $this->assertContains('product_id', $fillable);
        $this->assertContains('variant_id', $fillable);
        $this->assertContains('product_name', $fillable);
        $this->assertContains('product_image', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('quantity', $fillable);
        $this->assertContains('options', $fillable);
        $this->assertContains('total', $fillable);
    }

    public function test_casts_configuration(): void
    {
        $item = new OrderItem();
        $casts = $item->getCasts();
        $this->assertEquals('decimal:2', $casts['price']);
        $this->assertEquals('decimal:2', $casts['total']);
        $this->assertEquals('array', $casts['options']);
    }
}
