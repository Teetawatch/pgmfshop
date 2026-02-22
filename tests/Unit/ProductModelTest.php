<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductModelTest extends TestCase
{
    public function test_is_book_returns_true_for_book_type(): void
    {
        $product = new Product(['product_type' => Product::TYPE_BOOK]);
        $this->assertTrue($product->isBook());
        $this->assertFalse($product->isClothing());
        $this->assertFalse($product->isOther());
    }

    public function test_is_clothing_returns_true_for_clothing_type(): void
    {
        $product = new Product(['product_type' => Product::TYPE_CLOTHING]);
        $this->assertFalse($product->isBook());
        $this->assertTrue($product->isClothing());
        $this->assertFalse($product->isOther());
    }

    public function test_is_other_returns_true_for_other_type(): void
    {
        $product = new Product(['product_type' => Product::TYPE_OTHER]);
        $this->assertFalse($product->isBook());
        $this->assertFalse($product->isClothing());
        $this->assertTrue($product->isOther());
    }

    public function test_product_type_label_for_book(): void
    {
        $product = new Product(['product_type' => Product::TYPE_BOOK]);
        $this->assertEquals('หนังสือ', $product->product_type_label);
    }

    public function test_product_type_label_for_clothing(): void
    {
        $product = new Product(['product_type' => Product::TYPE_CLOTHING]);
        $this->assertEquals('เสื้อผ้า', $product->product_type_label);
    }

    public function test_product_type_label_for_other(): void
    {
        $product = new Product(['product_type' => Product::TYPE_OTHER]);
        $this->assertEquals('อื่นๆ', $product->product_type_label);
    }

    public function test_product_type_label_for_unknown_defaults_to_other(): void
    {
        $product = new Product(['product_type' => 'unknown']);
        $this->assertEquals('อื่นๆ', $product->product_type_label);
    }

    public function test_product_types_constant(): void
    {
        $this->assertArrayHasKey('book', Product::PRODUCT_TYPES);
        $this->assertArrayHasKey('clothing', Product::PRODUCT_TYPES);
        $this->assertArrayHasKey('other', Product::PRODUCT_TYPES);
        $this->assertCount(3, Product::PRODUCT_TYPES);
    }

    public function test_type_constants(): void
    {
        $this->assertEquals('book', Product::TYPE_BOOK);
        $this->assertEquals('clothing', Product::TYPE_CLOTHING);
        $this->assertEquals('other', Product::TYPE_OTHER);
    }

    public function test_fillable_attributes(): void
    {
        $product = new Product();
        $this->assertContains('name', $product->getFillable());
        $this->assertContains('price', $product->getFillable());
        $this->assertContains('stock', $product->getFillable());
        $this->assertContains('is_active', $product->getFillable());
        $this->assertContains('product_type', $product->getFillable());
    }

    public function test_casts_configuration(): void
    {
        $product = new Product();
        $casts = $product->getCasts();
        $this->assertEquals('decimal:2', $casts['price']);
        $this->assertEquals('array', $casts['images']);
        $this->assertEquals('array', $casts['tags']);
        $this->assertEquals('boolean', $casts['is_active']);
        $this->assertEquals('boolean', $casts['is_featured']);
        $this->assertEquals('boolean', $casts['is_new']);
    }
}
