<?php

namespace Tests\Unit;

use App\Models\StockMovement;
use PHPUnit\Framework\TestCase;

class StockMovementModelTest extends TestCase
{
    public function test_type_constants(): void
    {
        $this->assertEquals('in', StockMovement::TYPE_IN);
        $this->assertEquals('out', StockMovement::TYPE_OUT);
        $this->assertEquals('adjust', StockMovement::TYPE_ADJUST);
        $this->assertEquals('return', StockMovement::TYPE_RETURN);
        $this->assertEquals('initial', StockMovement::TYPE_INITIAL);
    }

    public function test_reference_constants(): void
    {
        $this->assertEquals('order', StockMovement::REF_ORDER);
        $this->assertEquals('manual', StockMovement::REF_MANUAL);
        $this->assertEquals('import', StockMovement::REF_IMPORT);
        $this->assertEquals('return', StockMovement::REF_RETURN);
    }

    public function test_get_type_label_in(): void
    {
        $sm = new StockMovement(['type' => StockMovement::TYPE_IN]);
        $this->assertEquals('รับเข้า', $sm->getTypeLabel());
    }

    public function test_get_type_label_out(): void
    {
        $sm = new StockMovement(['type' => StockMovement::TYPE_OUT]);
        $this->assertEquals('จ่ายออก', $sm->getTypeLabel());
    }

    public function test_get_type_label_adjust(): void
    {
        $sm = new StockMovement(['type' => StockMovement::TYPE_ADJUST]);
        $this->assertEquals('ปรับสต็อก', $sm->getTypeLabel());
    }

    public function test_get_type_label_return(): void
    {
        $sm = new StockMovement(['type' => StockMovement::TYPE_RETURN]);
        $this->assertEquals('คืนสต็อก', $sm->getTypeLabel());
    }

    public function test_get_type_label_initial(): void
    {
        $sm = new StockMovement(['type' => StockMovement::TYPE_INITIAL]);
        $this->assertEquals('ตั้งต้น', $sm->getTypeLabel());
    }

    public function test_get_type_label_unknown_returns_raw_type(): void
    {
        $sm = new StockMovement(['type' => 'custom']);
        $this->assertEquals('custom', $sm->getTypeLabel());
    }

    public function test_get_reference_label_order(): void
    {
        $sm = new StockMovement(['reference_type' => StockMovement::REF_ORDER]);
        $this->assertEquals('คำสั่งซื้อ', $sm->getReferenceLabel());
    }

    public function test_get_reference_label_manual(): void
    {
        $sm = new StockMovement(['reference_type' => StockMovement::REF_MANUAL]);
        $this->assertEquals('ปรับมือ', $sm->getReferenceLabel());
    }

    public function test_get_reference_label_import(): void
    {
        $sm = new StockMovement(['reference_type' => StockMovement::REF_IMPORT]);
        $this->assertEquals('นำเข้า', $sm->getReferenceLabel());
    }

    public function test_get_reference_label_return(): void
    {
        $sm = new StockMovement(['reference_type' => StockMovement::REF_RETURN]);
        $this->assertEquals('คืนสินค้า', $sm->getReferenceLabel());
    }

    public function test_get_reference_label_null_returns_dash(): void
    {
        $sm = new StockMovement();
        $this->assertEquals('-', $sm->getReferenceLabel());
    }

    public function test_fillable_attributes(): void
    {
        $sm = new StockMovement();
        $fillable = $sm->getFillable();
        $this->assertContains('product_id', $fillable);
        $this->assertContains('variant_id', $fillable);
        $this->assertContains('type', $fillable);
        $this->assertContains('quantity', $fillable);
        $this->assertContains('stock_before', $fillable);
        $this->assertContains('stock_after', $fillable);
        $this->assertContains('reason', $fillable);
    }
}
