<?php

namespace App\Helpers;

class PromptPayQR
{
    /**
     * Generate PromptPay EMVCo QR payload string.
     *
     * @param string $target Phone number (0xxxxxxxxx) or National ID (13 digits)
     * @param float|null $amount Amount in THB (null = no amount)
     * @return string EMVCo payload
     */
    public static function generatePayload(string $target, ?float $amount = null): string
    {
        // Sanitize target: remove dashes and spaces
        $target = preg_replace('/[^0-9]/', '', $target);

        // Determine if phone or national ID
        $isPhone = strlen($target) <= 10;

        if ($isPhone) {
            // Convert 0x to 0066x format (EMVCo PromptPay standard)
            if (str_starts_with($target, '0')) {
                $target = '0066' . substr($target, 1);
            }
            $aid = '0016A000000677010111';
            $targetTag = '01' . str_pad(strlen($target), 2, '0', STR_PAD_LEFT) . $target;
        } else {
            $aid = '0016A000000677010111';
            $targetTag = '02' . str_pad(strlen($target), 2, '0', STR_PAD_LEFT) . $target;
        }

        // Build merchant account info (tag 29)
        $merchantInfo = $aid . $targetTag;
        $tag29 = '29' . str_pad(strlen($merchantInfo), 2, '0', STR_PAD_LEFT) . $merchantInfo;

        // Build payload
        $payload = '';
        $payload .= '000201'; // Payload Format Indicator
        $payload .= '010212'; // Point of Initiation (12 = dynamic)
        $payload .= $tag29;   // Merchant Account Info
        $payload .= '5303764'; // Transaction Currency (764 = THB)
        $payload .= '5802TH';  // Country Code

        if ($amount !== null && $amount > 0) {
            $amountStr = number_format($amount, 2, '.', '');
            $payload .= '54' . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
        }

        // CRC placeholder
        $payload .= '6304';
        $crc = self::crc16($payload);
        $payload .= strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));

        return $payload;
    }

    /**
     * Generate Thai Bill Payment EMVCo QR payload string (Tag 30).
     *
     * @param string $billerId Biller ID (15 digits, e.g. Tax ID + suffix)
     * @param string $ref1 Reference 1
     * @param string $ref2 Reference 2
     * @param float|null $amount Amount in THB (null = no amount)
     * @return string EMVCo payload
     */
    public static function generateBillPaymentPayload(
        string $billerId,
        string $ref1,
        string $ref2,
        ?float $amount = null
    ): string {
        $billerId = preg_replace('/[^0-9]/', '', $billerId);

        // AID for Thai Bill Payment
        $aid = '0016A000000677010112';

        // Sub-tags inside Tag 30
        $billerTag = '01' . str_pad(strlen($billerId), 2, '0', STR_PAD_LEFT) . $billerId;
        $ref1Tag = '02' . str_pad(strlen($ref1), 2, '0', STR_PAD_LEFT) . $ref1;
        $ref2Tag = '03' . str_pad(strlen($ref2), 2, '0', STR_PAD_LEFT) . $ref2;

        $merchantInfo = $aid . $billerTag . $ref1Tag . $ref2Tag;
        $tag30 = '30' . str_pad(strlen($merchantInfo), 2, '0', STR_PAD_LEFT) . $merchantInfo;

        // Build payload
        $payload = '';
        $payload .= '000201'; // Payload Format Indicator
        $payload .= '010212'; // Point of Initiation (12 = dynamic)
        $payload .= $tag30;   // Bill Payment Merchant Account Info
        $payload .= '5303764'; // Transaction Currency (764 = THB)
        $payload .= '5802TH';  // Country Code

        if ($amount !== null && $amount > 0) {
            $amountStr = number_format($amount, 2, '.', '');
            $payload .= '54' . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
        }

        // CRC placeholder
        $payload .= '6304';
        $crc = self::crc16($payload);
        $payload .= strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));

        return $payload;
    }

    /**
     * CRC-16/CCITT-FALSE
     */
    private static function crc16(string $data): int
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc <<= 1;
                }
                $crc &= 0xFFFF;
            }
        }
        return $crc;
    }
}
