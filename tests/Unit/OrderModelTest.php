<?php

namespace Tests\Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderModelTest extends TestCase
{
    public function test_fillable_attributes(): void
    {
        $order = new Order();
        $fillable = $order->getFillable();
        $this->assertContains('order_number', $fillable);
        $this->assertContains('user_id', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('subtotal', $fillable);
        $this->assertContains('total', $fillable);
        $this->assertContains('discount', $fillable);
        $this->assertContains('shipping_cost', $fillable);
        $this->assertContains('coupon_code', $fillable);
        $this->assertContains('payment_method', $fillable);
        $this->assertContains('payment_slip', $fillable);
        $this->assertContains('slip_verified', $fillable);
        $this->assertContains('slip_hash', $fillable);
        $this->assertContains('shipping_method', $fillable);
        $this->assertContains('tracking_number', $fillable);
        $this->assertContains('shipping_address', $fillable);
        $this->assertContains('status_history', $fillable);
        $this->assertContains('payment_deadline', $fillable);
    }

    public function test_casts_configuration(): void
    {
        $order = new Order();
        $casts = $order->getCasts();
        $this->assertEquals('decimal:2', $casts['subtotal']);
        $this->assertEquals('decimal:2', $casts['discount']);
        $this->assertEquals('decimal:2', $casts['shipping_cost']);
        $this->assertEquals('decimal:2', $casts['total']);
        $this->assertEquals('array', $casts['shipping_address']);
        $this->assertEquals('array', $casts['status_history']);
        $this->assertEquals('array', $casts['slip_verification_data']);
        $this->assertEquals('datetime', $casts['payment_deadline']);
    }

    public function test_order_number_format(): void
    {
        // Order number should start with PGMF followed by date and sequence
        // Format: PGMF + YYYYMMDD + 4-digit sequence
        $pattern = '/^PGMF\d{8}\d{4}$/';
        $this->assertMatchesRegularExpression($pattern, 'PGMF202602210001');
        $this->assertMatchesRegularExpression($pattern, 'PGMF202512319999');
    }
}
