<?php

namespace App\Services;

/**
 * Thai QR Payment (Bill Payment) generator following EMVCo / BOT standard.
 *
 * Reference: Bank of Thailand "Thai QR Code Standard" specification
 * https://www.bot.or.th/Thai/PaymentSystems/StandardPS/Documents/ThaiQRCode_Payment_Standard.pdf
 */
class ThaiQrPayment
{
    /**
     * Generate the EMVCo payload string for Bill Payment.
     *
     * @param  string  $billerId   15-digit Biller ID issued by the payment network
     * @param  string  $ref1       Reference 1 (max 20 chars)
     * @param  string  $ref2       Reference 2 (max 20 chars, use "0" if none)
     * @param  float   $amount     Transaction amount (baht, 2 decimal places)
     * @param  string  $ref3       Reference 3 / suffix (optional, max 20 chars)
     * @return string  Raw QR payload string
     */
    public static function buildPayload(
        string $billerId,
        string $ref1,
        string $ref2,
        float  $amount,
        string $ref3 = ''
    ): string {
        /*
         * EMVCo TLV helper: Tag + Length (2-digit zero-padded) + Value
         */
        $tlv = fn(string $tag, string $value): string =>
            $tag . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;

        // ID 00: Payload Format Indicator  → "01"
        $payloadFormatIndicator = $tlv('00', '01');

        // ID 01: Point of Initiation Method → "12" (dynamic, amount present)
        $pointOfInitiation = $tlv('01', '12');

        // ID 30: Bill Payment application (Thai BOT standard)
        // Sub-tag 00: Application ID  "A000000677010112" (Thai standard for Bill Payment)
        // Sub-tag 01: Biller ID
        // Sub-tag 02: Reference 1
        // Sub-tag 03: Reference 2
        $billPaymentSubData  = $tlv('00', 'A000000677010112');
        $billPaymentSubData .= $tlv('01', $billerId);
        $billPaymentSubData .= $tlv('02', $ref1);
        $billPaymentSubData .= $tlv('03', $ref2);

        $billPayment = $tlv('30', $billPaymentSubData);

        // ID 53: Transaction Currency → "764" (THB)
        $currency = $tlv('53', '764');

        // ID 54: Transaction Amount (formatted to 2 decimal places)
        $amountStr = number_format($amount, 2, '.', '');
        $transactionAmount = $tlv('54', $amountStr);

        // ID 58: Country Code → "TH"
        $countryCode = $tlv('58', 'TH');

        // Assemble payload without CRC first (CRC field occupies the last 4 chars)
        $payloadWithoutCrc =
            $payloadFormatIndicator .
            $pointOfInitiation .
            $billPayment .
            $currency .
            $transactionAmount .
            $countryCode .
            '6304'; // ID 63 + length "04" + placeholder (will be replaced by actual CRC)

        // ID 63: CRC-16/CCITT-FALSE (seed 0xFFFF, poly 0x1021)
        $crc = strtoupper(str_pad(dechex(self::crc16($payloadWithoutCrc)), 4, '0', STR_PAD_LEFT));

        return $payloadWithoutCrc . $crc;
    }

    /**
     * Generate QR Code as a base64-encoded PNG data URI.
     *
     * @param  string  $billerId
     * @param  string  $ref1
     * @param  string  $ref2
     * @param  float   $amount
     * @param  string  $ref3
     * @param  int     $size      Image size in pixels (default 300)
     * @return string  data:image/png;base64,…
     */
    public static function generatePng(
        string $billerId,
        string $ref1,
        string $ref2,
        float  $amount,
        string $ref3 = '',
        int    $size = 300
    ): string {
        $payload = self::buildPayload($billerId, $ref1, $ref2, $amount, $ref3);

        $builder = \Endroid\QrCode\Builder\Builder::create()
            ->writer(new \Endroid\QrCode\Writer\PngWriter())
            ->data($payload)
            ->size($size)
            ->margin(10)
            ->errorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::Medium)
            ->build();

        return 'data:image/png;base64,' . base64_encode($builder->getString());
    }

    /**
     * CRC-16/CCITT-FALSE  (initial value 0xFFFF, polynomial 0x1021, no input/output reflect)
     */
    private static function crc16(string $data): int
    {
        $crc = 0xFFFF;
        $len = strlen($data);

        for ($i = 0; $i < $len; $i++) {
            $crc ^= (ord($data[$i]) << 8);
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
