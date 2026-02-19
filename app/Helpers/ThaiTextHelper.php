<?php

namespace App\Helpers;

class ThaiTextHelper
{
    /**
     * Convert number to Thai baht text
     * e.g. 274.00 => "สองร้อยเจ็ดสิบสี่บาทถ้วน"
     */
    public static function bahtText(float $amount): string
    {
        if ($amount == 0) {
            return 'ศูนย์บาทถ้วน';
        }

        $isNegative = $amount < 0;
        $amount = abs($amount);

        $baht = floor($amount);
        $satang = round(($amount - $baht) * 100);

        $result = '';

        if ($isNegative) {
            $result .= 'ลบ';
        }

        if ($baht > 0) {
            $result .= self::numberToThai($baht) . 'บาท';
        }

        if ($satang > 0) {
            $result .= self::numberToThai($satang) . 'สตางค์';
        } else {
            $result .= 'ถ้วน';
        }

        return $result;
    }

    private static function numberToThai(int $number): string
    {
        if ($number == 0) {
            return 'ศูนย์';
        }

        $digits = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];

        // Split number into groups of 6 digits (millions)
        $result = '';
        $remaining = $number;
        $groups = [];

        while ($remaining > 0) {
            $groups[] = $remaining % 1000000;
            $remaining = intdiv($remaining, 1000000);
        }

        for ($g = count($groups) - 1; $g >= 0; $g--) {
            $group = $groups[$g];
            if ($group == 0 && $g > 0) {
                continue;
            }

            $groupStr = self::groupToThai($group, $digits, $g < count($groups) - 1);
            $result .= $groupStr;

            if ($g > 0) {
                $result .= str_repeat('ล้าน', $g);
            }
        }

        return $result;
    }

    private static function groupToThai(int $number, array $digits, bool $hasHigherGroup): string
    {
        if ($number == 0) {
            return '';
        }

        $positions = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน'];
        $result = '';
        $numberStr = (string) $number;
        $len = strlen($numberStr);

        for ($i = 0; $i < $len; $i++) {
            $digit = (int) $numberStr[$i];
            $pos = $len - $i - 1;

            if ($digit == 0) {
                continue;
            }

            // Special case: เอ็ด for 1 in ones position (except when the group is just "1" with no higher group)
            if ($digit == 1 && $pos == 0 && ($len > 1 || $hasHigherGroup)) {
                $result .= 'เอ็ด';
            }
            // Special case: ยี่ for 2 in tens position
            elseif ($digit == 2 && $pos == 1) {
                $result .= 'ยี่สิบ';
            }
            // Special case: omit หนึ่ง for 1 in tens position
            elseif ($digit == 1 && $pos == 1) {
                $result .= 'สิบ';
            }
            else {
                $result .= $digits[$digit] . $positions[$pos];
            }
        }

        return $result;
    }
}
