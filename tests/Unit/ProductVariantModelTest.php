<?php

namespace Tests\Unit;

use App\Models\ProductVariant;
use PHPUnit\Framework\TestCase;

class ProductVariantModelTest extends TestCase
{
    public function test_get_label_with_size_and_color(): void
    {
        $variant = new ProductVariant(['size' => 'M', 'color' => 'ดำ']);
        $this->assertEquals('M / ดำ', $variant->getLabel());
    }

    public function test_get_label_with_size_only(): void
    {
        $variant = new ProductVariant(['size' => 'L', 'color' => null]);
        $this->assertEquals('L', $variant->getLabel());
    }

    public function test_get_label_with_color_only(): void
    {
        $variant = new ProductVariant(['size' => null, 'color' => 'แดง']);
        $this->assertEquals('แดง', $variant->getLabel());
    }

    public function test_get_label_with_no_size_and_no_color(): void
    {
        $variant = new ProductVariant(['size' => null, 'color' => null]);
        $this->assertEquals('-', $variant->getLabel());
    }

    public function test_get_label_with_empty_strings(): void
    {
        $variant = new ProductVariant(['size' => '', 'color' => '']);
        $this->assertEquals('-', $variant->getLabel());
    }

    public function test_fillable_attributes(): void
    {
        $variant = new ProductVariant();
        $fillable = $variant->getFillable();
        $this->assertContains('product_id', $fillable);
        $this->assertContains('size', $fillable);
        $this->assertContains('color', $fillable);
        $this->assertContains('stock', $fillable);
        $this->assertContains('sku', $fillable);
        $this->assertContains('is_active', $fillable);
    }

    public function test_casts_configuration(): void
    {
        $variant = new ProductVariant();
        $casts = $variant->getCasts();
        $this->assertEquals('boolean', $casts['is_active']);
    }
}
