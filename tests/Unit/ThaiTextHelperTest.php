<?php

namespace Tests\Unit;

use App\Helpers\ThaiTextHelper;
use PHPUnit\Framework\TestCase;

class ThaiTextHelperTest extends TestCase
{
    public function test_zero_amount(): void
    {
        $this->assertEquals('ศูนย์บาทถ้วน', ThaiTextHelper::bahtText(0));
    }

    public function test_whole_number(): void
    {
        $this->assertEquals('หนึ่งบาทถ้วน', ThaiTextHelper::bahtText(1));
    }

    public function test_tens(): void
    {
        $this->assertEquals('สิบบาทถ้วน', ThaiTextHelper::bahtText(10));
        $this->assertEquals('สิบเอ็ดบาทถ้วน', ThaiTextHelper::bahtText(11));
        $this->assertEquals('ยี่สิบบาทถ้วน', ThaiTextHelper::bahtText(20));
        $this->assertEquals('ยี่สิบเอ็ดบาทถ้วน', ThaiTextHelper::bahtText(21));
    }

    public function test_hundreds(): void
    {
        $this->assertEquals('หนึ่งร้อยบาทถ้วน', ThaiTextHelper::bahtText(100));
        $this->assertEquals('สองร้อยเจ็ดสิบสี่บาทถ้วน', ThaiTextHelper::bahtText(274));
    }

    public function test_thousands(): void
    {
        $this->assertEquals('หนึ่งพันบาทถ้วน', ThaiTextHelper::bahtText(1000));
        $this->assertEquals('หนึ่งพันสองร้อยสามสิบสี่บาทถ้วน', ThaiTextHelper::bahtText(1234));
    }

    public function test_ten_thousands(): void
    {
        $this->assertEquals('หนึ่งหมื่นบาทถ้วน', ThaiTextHelper::bahtText(10000));
    }

    public function test_hundred_thousands(): void
    {
        $this->assertEquals('หนึ่งแสนบาทถ้วน', ThaiTextHelper::bahtText(100000));
    }

    public function test_millions(): void
    {
        $this->assertEquals('หนึ่งล้านบาทถ้วน', ThaiTextHelper::bahtText(1000000));
    }

    public function test_with_satang(): void
    {
        $this->assertEquals('หนึ่งร้อยบาทห้าสิบสตางค์', ThaiTextHelper::bahtText(100.50));
        $this->assertEquals('สองร้อยเจ็ดสิบสี่บาทยี่สิบห้าสตางค์', ThaiTextHelper::bahtText(274.25));
    }

    public function test_only_satang(): void
    {
        $this->assertEquals('ห้าสิบสตางค์', ThaiTextHelper::bahtText(0.50));
        $this->assertEquals('เจ็ดสิบห้าสตางค์', ThaiTextHelper::bahtText(0.75));
    }

    public function test_negative_amount(): void
    {
        $this->assertEquals('ลบหนึ่งร้อยบาทถ้วน', ThaiTextHelper::bahtText(-100));
    }

    public function test_large_amount(): void
    {
        $result = ThaiTextHelper::bahtText(1500000);
        $this->assertEquals('หนึ่งล้านห้าแสนบาทถ้วน', $result);
    }

    public function test_common_prices(): void
    {
        // 35 baht (thaipost shipping)
        $this->assertEquals('สามสิบห้าบาทถ้วน', ThaiTextHelper::bahtText(35));
        // 50 baht (flash shipping)
        $this->assertEquals('ห้าสิบบาทถ้วน', ThaiTextHelper::bahtText(50));
        // 60 baht (kerry shipping)
        $this->assertEquals('หกสิบบาทถ้วน', ThaiTextHelper::bahtText(60));
        // 299 baht
        $this->assertEquals('สองร้อยเก้าสิบเก้าบาทถ้วน', ThaiTextHelper::bahtText(299));
    }
}
