<?php

namespace App\Http\Controllers;

use App\Helpers\PromptPayQR;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Http\Request;

class PromptPayQRController extends Controller
{
    public function generate(Request $request)
    {
        $amount = (float) $request->query('amount', 0);

        $billerId = config('app.billpayment_biller_id', '099300045304207');
        $ref1 = config('app.billpayment_ref1', 'QR001');
        $ref2 = config('app.billpayment_ref2', '0');

        $payload = PromptPayQR::generateBillPaymentPayload(
            $billerId,
            $ref1,
            $ref2,
            $amount > 0 ? $amount : null
        );

        $qrCode = new QrCode(
            data: $payload,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 300,
            margin: 10,
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return response($result->getString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store',
        ]);
    }
}
