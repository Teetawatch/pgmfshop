<?php

namespace Tests\Unit;

use App\Models\Coupon;
use PHPUnit\Framework\TestCase;

class CouponModelTest extends TestCase
{
    /**
     * Create a Coupon instance with raw attributes set directly
     * to bypass the decimal cast which requires a DB connection.
     */
    private function makeCoupon(array $attrs = []): Coupon
    {
        $defaults = [
            'code' => 'TEST10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => 100,
            'min_purchase' => 0,
            'usage_limit' => 0,
            'used_count' => 0,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ];

        $merged = array_merge($defaults, $attrs);

        $coupon = new Coupon();
        foreach ($merged as $key => $value) {
            $coupon->setRawAttributes(array_merge($coupon->getAttributes(), [$key => $value]));
        }

        return $coupon;
    }

    // --- isValid() tests ---

    public function test_is_valid_returns_true_for_active_coupon_within_date_range(): void
    {
        $coupon = $this->makeCoupon();
        $this->assertTrue($coupon->isValid());
    }

    public function test_is_valid_returns_false_for_inactive_coupon(): void
    {
        $coupon = $this->makeCoupon(['is_active' => false]);
        $this->assertFalse($coupon->isValid());
    }

    public function test_is_valid_returns_false_when_before_start_date(): void
    {
        $coupon = $this->makeCoupon([
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(5),
        ]);
        $this->assertFalse($coupon->isValid());
    }

    public function test_is_valid_returns_false_when_after_end_date(): void
    {
        $coupon = $this->makeCoupon([
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(2),
        ]);
        $this->assertFalse($coupon->isValid());
    }

    public function test_is_valid_returns_false_when_usage_limit_reached(): void
    {
        $coupon = $this->makeCoupon([
            'usage_limit' => 5,
            'used_count' => 5,
        ]);
        $this->assertFalse($coupon->isValid());
    }

    public function test_is_valid_returns_true_when_usage_limit_not_reached(): void
    {
        $coupon = $this->makeCoupon([
            'usage_limit' => 5,
            'used_count' => 3,
        ]);
        $this->assertTrue($coupon->isValid());
    }

    public function test_is_valid_returns_true_when_usage_limit_is_zero_unlimited(): void
    {
        $coupon = $this->makeCoupon([
            'usage_limit' => 0,
            'used_count' => 999,
        ]);
        $this->assertTrue($coupon->isValid());
    }

    // --- calculateDiscount() tests ---

    public function test_percentage_discount(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => null,
            'min_purchase' => 0,
        ]);

        $this->assertEquals(100.00, $coupon->calculateDiscount(1000));
    }

    public function test_percentage_discount_with_max_cap(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'max_discount' => 200,
            'min_purchase' => 0,
        ]);

        // 50% of 1000 = 500, but max is 200
        $this->assertEquals(200.00, $coupon->calculateDiscount(1000));
    }

    public function test_fixed_discount(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'fixed',
            'discount_value' => 150,
            'min_purchase' => 0,
        ]);

        $this->assertEquals(150.00, $coupon->calculateDiscount(1000));
    }

    public function test_fixed_discount_cannot_exceed_subtotal(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'fixed',
            'discount_value' => 500,
            'min_purchase' => 0,
        ]);

        // Discount should not exceed the subtotal
        $this->assertEquals(200.00, $coupon->calculateDiscount(200));
    }

    public function test_discount_returns_zero_when_below_min_purchase(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_purchase' => 500,
        ]);

        $this->assertEquals(0, $coupon->calculateDiscount(300));
    }

    public function test_discount_applies_when_at_min_purchase(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => null,
            'min_purchase' => 500,
        ]);

        // Exactly at min_purchase: 10% of 500 = 50
        $this->assertEquals(50.00, $coupon->calculateDiscount(500));
    }

    public function test_percentage_discount_without_max_cap(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'max_discount' => null,
            'min_purchase' => 0,
        ]);

        // 20% of 5000 = 1000, no cap
        $this->assertEquals(1000.00, $coupon->calculateDiscount(5000));
    }
}
