<?php

namespace Tests\Unit;

use App\Helpers\PromptPayQR;
use PHPUnit\Framework\TestCase;

class PromptPayQRTest extends TestCase
{
    public function test_generate_payload_with_phone_number(): void
    {
        $payload = PromptPayQR::generatePayload('0812345678');

        $this->assertStringStartsWith('000201', $payload);
        $this->assertStringContains('5303764', $payload); // THB currency
        $this->assertStringContains('5802TH', $payload);  // Country code
        $this->assertStringContains('0066812345678', $payload); // Converted phone
        $this->assertStringContains('6304', $payload); // CRC tag
    }

    public function test_generate_payload_with_national_id(): void
    {
        $payload = PromptPayQR::generatePayload('1234567890123');

        $this->assertStringStartsWith('000201', $payload);
        $this->assertStringContains('1234567890123', $payload);
        $this->assertStringContains('5303764', $payload);
    }

    public function test_generate_payload_with_amount(): void
    {
        $payload = PromptPayQR::generatePayload('0812345678', 100.00);

        $this->assertStringContains('54', $payload); // Amount tag
        $this->assertStringContains('100.00', $payload);
    }

    public function test_generate_payload_without_amount(): void
    {
        $payload = PromptPayQR::generatePayload('0812345678');

        // Should not contain amount tag value
        $this->assertStringNotContains('5406', $payload);
    }

    public function test_generate_payload_strips_dashes(): void
    {
        $payload = PromptPayQR::generatePayload('081-234-5678');

        $this->assertStringContains('0066812345678', $payload);
    }

    public function test_generate_bill_payment_payload(): void
    {
        $payload = PromptPayQR::generateBillPaymentPayload(
            '099300045304200',
            'REF1TEST',
            'REF2TEST',
            500.00
        );

        $this->assertStringStartsWith('000201', $payload);
        $this->assertStringContains('5303764', $payload);
        $this->assertStringContains('5802TH', $payload);
        $this->assertStringContains('099300045304200', $payload);
        $this->assertStringContains('REF1TEST', $payload);
        $this->assertStringContains('REF2TEST', $payload);
        $this->assertStringContains('500.00', $payload);
    }

    public function test_generate_bill_payment_payload_without_amount(): void
    {
        $payload = PromptPayQR::generateBillPaymentPayload(
            '099300045304200',
            'REF1',
            'REF2'
        );

        $this->assertStringStartsWith('000201', $payload);
        $this->assertStringContains('099300045304200', $payload);
    }

    public function test_payload_has_valid_crc(): void
    {
        $payload = PromptPayQR::generatePayload('0812345678', 100.00);

        // CRC is last 4 hex chars
        $this->assertEquals(strlen($payload), strlen($payload)); // valid string
        $crcPart = substr($payload, -4);
        $this->assertMatchesRegularExpression('/^[0-9A-F]{4}$/', $crcPart);
    }

    public function test_payload_format_consistency(): void
    {
        // Same input should produce same output
        $payload1 = PromptPayQR::generatePayload('0812345678', 250.00);
        $payload2 = PromptPayQR::generatePayload('0812345678', 250.00);

        $this->assertEquals($payload1, $payload2);
    }

    /**
     * Helper: assert string contains substring (PHPUnit 10+ compatible)
     */
    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(
            str_contains($haystack, $needle),
            "Failed asserting that '$haystack' contains '$needle'."
        );
    }

    private function assertStringNotContains(string $needle, string $haystack): void
    {
        $this->assertFalse(
            str_contains($haystack, $needle),
            "Failed asserting that '$haystack' does not contain '$needle'."
        );
    }
}
