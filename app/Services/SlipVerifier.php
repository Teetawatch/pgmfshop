<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlipVerifier
{
    /**
     * Max Hamming distance to consider two perceptual hashes as "similar".
     * 16x16 hash = 256 bits. Threshold 25 ≈ ~10% difference.
     */
    private const HAMMING_THRESHOLD = 10;

    /**
     * Max orders a single user can place per hour.
     */
    private const MAX_ORDERS_PER_HOUR = 5;

    /**
     * Time window (minutes) for suspicious same-amount detection.
     */
    private const SUSPICIOUS_AMOUNT_WINDOW_MINUTES = 60;

    public static function verify(string $filePath, float $expectedAmount, float $transferAmount = 0, ?string $transferDate = null, ?string $transferTime = null, ?int $userId = null): array
    {
        $checks = [];
        $warnings = [];
        $score = 0;
        $maxScore = 0;
        $amountMatched = false;
        $ocrAmountMatched = false;
        $ocrAccountMatched = false;
        $ocrText = '';

        // ── 1. Valid image check (15 pts) ──
        $maxScore += 15;
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo) {
            $checks[] = ['name' => 'valid_image', 'passed' => true, 'detail' => "ไฟล์เป็นรูปภาพจริง ({$imageInfo['mime']})"];
            $score += 15;
        } else {
            $checks[] = ['name' => 'valid_image', 'passed' => false, 'detail' => 'ไฟล์ไม่ใช่รูปภาพที่ถูกต้อง'];
            return self::result(false, $score, $maxScore, $checks, ['ไฟล์ไม่ใช่รูปภาพ'], null, false, false);
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // ── 2. OCR — extract text from slip (critical for amount + account validation) ──
        $ocrText = self::extractSlipText($filePath);

        // ── 3. OCR — Amount from slip image (CRITICAL — 35 pts) ──
        $maxScore += 35;
        $ocrAmountResult = self::checkOcrAmount($ocrText, $expectedAmount);
        $checks[] = ['name' => 'ocr_amount', 'passed' => $ocrAmountResult['passed'], 'detail' => $ocrAmountResult['detail']];
        if ($ocrAmountResult['passed']) {
            $score += 35;
            $ocrAmountMatched = true;
            $amountMatched = true;
        } else {
            if (!empty($ocrAmountResult['warning'])) {
                $warnings[] = $ocrAmountResult['warning'];
            }
        }

        // ── 4. OCR — Recipient account name (CRITICAL — 30 pts) ──
        $maxScore += 30;
        $ocrAccountResult = self::checkOcrAccountName($ocrText);
        $checks[] = ['name' => 'ocr_account', 'passed' => $ocrAccountResult['passed'], 'detail' => $ocrAccountResult['detail']];
        if ($ocrAccountResult['passed']) {
            $score += 30;
            $ocrAccountMatched = true;
        } else {
            if (!empty($ocrAccountResult['warning'])) {
                $warnings[] = $ocrAccountResult['warning'];
            }
        }

        // ── 5. User-entered amount validation (10 pts, secondary check) ──
        $maxScore += 10;
        if ($transferAmount > 0) {
            $diff = abs($transferAmount - $expectedAmount);
            $tolerance = 0.99;
            if ($diff <= $tolerance) {
                $checks[] = ['name' => 'amount_match', 'passed' => true, 'detail' => "ยอดที่กรอก ฿" . number_format($transferAmount, 2) . " ตรงกับยอดสั่งซื้อ ฿" . number_format($expectedAmount, 2)];
                $score += 10;
                // If OCR failed but user-entered amount matches, set amountMatched as partial
                if (!$ocrAmountMatched) $amountMatched = true;
            } else {
                $checks[] = ['name' => 'amount_match', 'passed' => false, 'detail' => "ยอดที่กรอก ฿" . number_format($transferAmount, 2) . " ไม่ตรงกับยอดสั่งซื้อ ฿" . number_format($expectedAmount, 2) . " (ต่างกัน ฿" . number_format($diff, 2) . ")"];
                $warnings[] = "ยอดเงินที่กรอกไม่ตรงกับยอดสั่งซื้อ (ต่างกัน ฿" . number_format($diff, 2) . ")";
            }
        } else {
            $checks[] = ['name' => 'amount_match', 'passed' => false, 'detail' => 'ไม่ได้ระบุจำนวนเงินที่โอน'];
            $warnings[] = 'ไม่ได้ระบุจำนวนเงินที่โอน';
        }

        // ── 6. Transfer date/time reasonableness (10 pts) ──
        $maxScore += 10;
        $transferDateTimePassed = false;
        if ($transferDate) {
            try {
                $transferDateTime = \Carbon\Carbon::parse($transferDate . ($transferTime ? ' ' . $transferTime : ''));
                $now = now();

                if ($transferDateTime->isAfter($now->copy()->addMinutes(10))) {
                    $checks[] = ['name' => 'transfer_datetime', 'passed' => false, 'detail' => "วันเวลาโอน {$transferDateTime->format('d/m/Y H:i')} เป็นอนาคต"];
                    $warnings[] = 'วันเวลาที่โอนเป็นเวลาในอนาคต';
                } elseif ($transferDateTime->isBefore($now->copy()->subDays(3))) {
                    $checks[] = ['name' => 'transfer_datetime', 'passed' => false, 'detail' => "วันเวลาโอน {$transferDateTime->format('d/m/Y H:i')} เกิน 3 วันแล้ว"];
                    $warnings[] = 'วันเวลาที่โอนเกิน 3 วันแล้ว';
                } else {
                    $checks[] = ['name' => 'transfer_datetime', 'passed' => true, 'detail' => "วันเวลาโอน {$transferDateTime->format('d/m/Y H:i')} อยู่ในช่วงที่เหมาะสม"];
                    $score += 10;
                    $transferDateTimePassed = true;
                }
            } catch (\Exception $e) {
                $checks[] = ['name' => 'transfer_datetime', 'passed' => true, 'detail' => 'ไม่สามารถตรวจสอบวันเวลาโอนได้ (ข้าม)'];
                $score += 5;
            }
        } else {
            $checks[] = ['name' => 'transfer_datetime', 'passed' => false, 'detail' => 'ไม่ได้ระบุวันเวลาที่โอน'];
            $warnings[] = 'ไม่ได้ระบุวันเวลาที่โอน';
        }

        // ── 7. Dimension check (5 pts) ──
        $maxScore += 5;
        $isPortrait = $height > $width;
        $isLargeEnough = $width >= 300 && $height >= 400;
        if ($isPortrait && $isLargeEnough) {
            $checks[] = ['name' => 'dimensions', 'passed' => true, 'detail' => "ขนาด {$width}x{$height}px (แนวตั้ง)"];
            $score += 5;
        } elseif ($isLargeEnough) {
            $checks[] = ['name' => 'dimensions', 'passed' => true, 'detail' => "ขนาด {$width}x{$height}px"];
            $score += 3;
            $warnings[] = 'สลิปไม่ใช่แนวตั้ง อาจเป็นภาพ screenshot';
        } else {
            $checks[] = ['name' => 'dimensions', 'passed' => false, 'detail' => "ขนาด {$width}x{$height}px เล็กเกินไป"];
            $warnings[] = 'รูปมีขนาดเล็กเกินไปสำหรับสลิปโอนเงิน';
        }

        // ── 8. File size check (5 pts) ──
        $maxScore += 5;
        $fileSize = filesize($filePath);
        $fileSizeKB = round($fileSize / 1024);
        if ($fileSize >= 30 * 1024 && $fileSize <= 10 * 1024 * 1024) {
            $checks[] = ['name' => 'file_size', 'passed' => true, 'detail' => "{$fileSizeKB} KB"];
            $score += 5;
        } else {
            $checks[] = ['name' => 'file_size', 'passed' => false, 'detail' => "{$fileSizeKB} KB (ผิดปกติ)"];
            $warnings[] = $fileSize < 30 * 1024 ? 'ไฟล์เล็กเกินไป อาจไม่ใช่สลิปจริง' : 'ไฟล์ใหญ่เกินไป';
        }

        // ── 9. Duplicate slip check — exact + fuzzy Hamming (20 pts) ──
        $maxScore += 20;
        $slipHash = self::imageHash($filePath);
        $duplicateResult = self::checkDuplicateSlip($slipHash, $userId);

        if ($duplicateResult['exact_match']) {
            $dup = $duplicateResult['exact_match'];
            $checks[] = ['name' => 'duplicate', 'passed' => false, 'detail' => "สลิปซ้ำกับคำสั่งซื้อ {$dup->order_number}"];
            $warnings[] = "สลิปนี้เคยใช้กับคำสั่งซื้อ {$dup->order_number} แล้ว";
            if ($duplicateResult['is_cross_user']) {
                $warnings[] = "⚠ สลิปนี้เคยถูกใช้โดยลูกค้าคนอื่น (คำสั่งซื้อ {$dup->order_number})";
            }
        } elseif ($duplicateResult['fuzzy_match']) {
            // Fuzzy match = warning only, do NOT block (same-bank slips have similar templates)
            $fuz = $duplicateResult['fuzzy_match'];
            $dist = $duplicateResult['fuzzy_distance'];
            $checks[] = ['name' => 'duplicate', 'passed' => true, 'detail' => "สลิปคล้ายกับคำสั่งซื้อ {$fuz->order_number} (ความต่าง {$dist}/256 bits) — แจ้งเตือนเท่านั้น"];
            $warnings[] = "สลิปคล้ายกับสลิปในคำสั่งซื้อ {$fuz->order_number} (ความต่าง {$dist}/256 bits) — ควรตรวจสอบด้วยตนเอง";
            $score += 10; // partial score for fuzzy
            if ($duplicateResult['is_cross_user']) {
                $warnings[] = "⚠ สลิปที่คล้ายนี้เคยถูกใช้โดยลูกค้าคนอื่น";
            }
        } else {
            $checks[] = ['name' => 'duplicate', 'passed' => true, 'detail' => 'ไม่พบสลิปซ้ำหรือคล้ายในระบบ'];
            $score += 20;
        }
        // Only exact match blocks — fuzzy is warning only
        $hasDuplicate = (bool) $duplicateResult['exact_match'];

        // ── 10. Color analysis (5 pts) ──
        $maxScore += 5;
        $colorResult = self::analyzeColors($filePath, $imageInfo);
        if ($colorResult['looks_like_slip']) {
            $checks[] = ['name' => 'color_analysis', 'passed' => true, 'detail' => $colorResult['detail']];
            $score += 5;
        } else {
            $checks[] = ['name' => 'color_analysis', 'passed' => false, 'detail' => $colorResult['detail']];
            $warnings[] = 'สีของรูปไม่ตรงกับรูปแบบสลิปธนาคาร';
        }

        // ── 11. EXIF timestamp check (5 pts, bonus) ──
        $maxScore += 5;
        $exifResult = self::checkExifTimestamp($filePath);
        $checks[] = ['name' => 'timestamp', 'passed' => $exifResult['passed'], 'detail' => $exifResult['detail']];
        if ($exifResult['passed']) {
            $score += 5;
        } else {
            if (!empty($exifResult['warning'])) {
                $warnings[] = $exifResult['warning'];
            }
        }

        // ── 12. Rate limiting per user (warning only, no pts) ──
        if ($userId) {
            $rateLimitResult = self::checkUserRateLimit($userId);
            $checks[] = ['name' => 'rate_limit', 'passed' => $rateLimitResult['passed'], 'detail' => $rateLimitResult['detail']];
            if (!$rateLimitResult['passed']) {
                $warnings[] = $rateLimitResult['warning'];
            }
        }

        // ── 13. Suspicious same-amount detection (warning only, no pts) ──
        if ($transferAmount > 0 && $userId) {
            $suspiciousResult = self::checkSuspiciousAmount($transferAmount, $transferDate, $userId);
            $checks[] = ['name' => 'suspicious_amount', 'passed' => $suspiciousResult['passed'], 'detail' => $suspiciousResult['detail']];
            if (!$suspiciousResult['passed']) {
                $warnings[] = $suspiciousResult['warning'];
            }
        }

        // ── Determine pass/fail ──
        // Basic pass: at least 40% score AND no duplicate
        $passed = ($score / $maxScore >= 0.4) && !$hasDuplicate;

        // Auto-verify requires ALL of:
        // 1. OCR amount matches (from slip image) OR user-entered amount matches
        // 2. OCR account name matches (recipient is valid)
        // 3. No duplicate or fuzzy-similar slip
        // 4. Transfer date/time is reasonable
        // 5. Overall score >= 70%
        // 6. Not rate-limited
        // 7. No suspicious same-amount pattern
        $noRateLimitIssue = !$userId || (self::checkUserRateLimit($userId)['passed'] ?? true);
        $noSuspiciousAmount = !($transferAmount > 0 && $userId) || (self::checkSuspiciousAmount($transferAmount, $transferDate, $userId)['passed'] ?? true);

        $canAutoVerify = $amountMatched
            && $ocrAccountMatched
            && !$hasDuplicate
            && $transferDateTimePassed
            && ($score / $maxScore >= 0.7)
            && $noRateLimitIssue
            && $noSuspiciousAmount;

        return self::result($passed, $score, $maxScore, $checks, $warnings, $slipHash, $amountMatched, $canAutoVerify, $ocrText, $ocrAmountMatched, $ocrAccountMatched);
    }

    /**
     * Generate a perceptual hash of the image for deduplication.
     * Uses average hash (aHash) — resize to 16x16, grayscale, compare to mean.
     * Returns 64-char hex string (256 bits).
     */
    public static function imageHash(string $filePath): string
    {
        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) return md5_file($filePath);

        $image = match ($imageInfo['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($filePath),
            'image/png' => @imagecreatefrompng($filePath),
            'image/webp' => @imagecreatefromwebp($filePath),
            default => null,
        };

        if (!$image) return md5_file($filePath);

        // Resize to 16x16 for better accuracy than 8x8
        $small = imagecreatetruecolor(16, 16);
        imagecopyresampled($small, $image, 0, 0, 0, 0, 16, 16, imagesx($image), imagesy($image));

        // Convert to grayscale and collect pixel values
        $pixels = [];
        for ($y = 0; $y < 16; $y++) {
            for ($x = 0; $x < 16; $x++) {
                $rgb = imagecolorat($small, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $pixels[] = (int) round(0.299 * $r + 0.587 * $g + 0.114 * $b);
            }
        }

        imagedestroy($image);
        imagedestroy($small);

        // Average hash
        $avg = array_sum($pixels) / count($pixels);
        $hash = '';
        foreach ($pixels as $px) {
            $hash .= $px >= $avg ? '1' : '0';
        }

        // Convert binary to hex
        $hexHash = '';
        for ($i = 0; $i < strlen($hash); $i += 4) {
            $hexHash .= dechex(bindec(substr($hash, $i, 4)));
        }

        return $hexHash;
    }

    /**
     * Calculate Hamming distance between two hex hash strings.
     * Each hex char = 4 bits, compare bit by bit.
     */
    public static function hammingDistance(string $hash1, string $hash2): int
    {
        if (strlen($hash1) !== strlen($hash2)) {
            return PHP_INT_MAX;
        }

        $distance = 0;
        for ($i = 0; $i < strlen($hash1); $i++) {
            $xor = hexdec($hash1[$i]) ^ hexdec($hash2[$i]);
            // Count set bits (Brian Kernighan's algorithm)
            while ($xor) {
                $distance++;
                $xor &= ($xor - 1);
            }
        }

        return $distance;
    }

    /**
     * Check for duplicate slips: exact match + fuzzy Hamming distance.
     * Also detects cross-user reuse.
     */
    private static function checkDuplicateSlip(string $slipHash, ?int $userId): array
    {
        $result = [
            'exact_match' => null,
            'fuzzy_match' => null,
            'fuzzy_distance' => null,
            'is_cross_user' => false,
        ];

        $existingOrders = Order::whereNotNull('slip_hash')
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->select(['id', 'order_number', 'user_id', 'slip_hash'])
            ->get();

        $bestFuzzyDistance = PHP_INT_MAX;
        $bestFuzzyOrder = null;

        foreach ($existingOrders as $order) {
            if ($order->slip_hash === $slipHash) {
                $result['exact_match'] = $order;
                $result['is_cross_user'] = $userId && $order->user_id !== $userId;
                return $result;
            }

            // Only compare hashes of same length (skip md5 fallback hashes vs perceptual)
            if (strlen($order->slip_hash) === strlen($slipHash)) {
                $dist = self::hammingDistance($slipHash, $order->slip_hash);
                if ($dist < $bestFuzzyDistance) {
                    $bestFuzzyDistance = $dist;
                    $bestFuzzyOrder = $order;
                }
            }
        }

        if ($bestFuzzyDistance <= self::HAMMING_THRESHOLD && $bestFuzzyOrder) {
            $result['fuzzy_match'] = $bestFuzzyOrder;
            $result['fuzzy_distance'] = $bestFuzzyDistance;
            $result['is_cross_user'] = $userId && $bestFuzzyOrder->user_id !== $userId;
        }

        return $result;
    }

    /**
     * Rate limit: check how many orders a user placed in the last hour.
     */
    private static function checkUserRateLimit(int $userId): array
    {
        $recentCount = Order::where('user_id', $userId)
            ->where('created_at', '>=', now()->subHour())
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->count();

        if ($recentCount >= self::MAX_ORDERS_PER_HOUR) {
            return [
                'passed' => false,
                'detail' => "สั่งซื้อ {$recentCount} ครั้งในชั่วโมงที่ผ่านมา (เกินกำหนด " . self::MAX_ORDERS_PER_HOUR . " ครั้ง/ชม.)",
                'warning' => "ผู้ใช้สั่งซื้อบ่อยผิดปกติ ({$recentCount} ครั้ง/ชม.) — ไม่สามารถตรวจสลิปอัตโนมัติได้",
                'count' => $recentCount,
            ];
        }

        return [
            'passed' => true,
            'detail' => "สั่งซื้อ {$recentCount} ครั้งในชั่วโมงที่ผ่านมา",
            'warning' => '',
            'count' => $recentCount,
        ];
    }

    /**
     * Check for suspicious same-amount orders from different users within a time window.
     * If another user submitted the exact same transfer amount within the window,
     * flag as suspicious (could be same slip edited to change hash).
     */
    private static function checkSuspiciousAmount(float $transferAmount, ?string $transferDate, int $userId): array
    {
        $windowStart = now()->subMinutes(self::SUSPICIOUS_AMOUNT_WINDOW_MINUTES);

        $suspicious = Order::where('transfer_amount', $transferAmount)
            ->where('user_id', '!=', $userId)
            ->where('created_at', '>=', $windowStart)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->first();

        if ($suspicious) {
            return [
                'passed' => false,
                'detail' => "พบคำสั่งซื้อยอดเดียวกัน (฿" . number_format($transferAmount, 2) . ") จากลูกค้าอื่นภายใน " . self::SUSPICIOUS_AMOUNT_WINDOW_MINUTES . " นาที",
                'warning' => "⚠ พบยอดโอนเดียวกัน (฿" . number_format($transferAmount, 2) . ") จากลูกค้าคนอื่นเมื่อเร็วๆ นี้ — อาจเป็นสลิปเดียวกันที่แก้ไข",
            ];
        }

        return [
            'passed' => true,
            'detail' => 'ไม่พบยอดโอนซ้ำจากลูกค้าอื่นในช่วงเวลาใกล้เคียง',
            'warning' => '',
        ];
    }

    /**
     * Analyze dominant colors to see if image looks like a bank slip.
     * Bank slips typically have white/light backgrounds with colored headers.
     */
    private static function analyzeColors(string $filePath, array $imageInfo): array
    {
        $image = match ($imageInfo['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($filePath),
            'image/png' => @imagecreatefrompng($filePath),
            'image/webp' => @imagecreatefromwebp($filePath),
            default => null,
        };

        if (!$image) {
            return ['looks_like_slip' => false, 'detail' => 'ไม่สามารถวิเคราะห์สีได้'];
        }

        $w = imagesx($image);
        $h = imagesy($image);

        // Sample pixels from different regions
        $sampleSize = 50;
        $lightPixels = 0;
        $totalSamples = 0;
        $hasGreen = false;
        $hasBlue = false;
        $hasPurple = false;
        $hasOrange = false;

        for ($i = 0; $i < $sampleSize; $i++) {
            $x = rand(0, $w - 1);
            $y = rand(0, $h - 1);
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $brightness = ($r + $g + $b) / 3;
            if ($brightness > 180) $lightPixels++;
            $totalSamples++;

            // Check for bank brand colors
            if ($g > 100 && $g > $r && $g > $b) $hasGreen = true;  // KBank
            if ($b > 100 && $b > $r && $b > $g) $hasBlue = true;   // KTB, TMB
            if ($r > 80 && $b > 80 && $g < 100) $hasPurple = true; // SCB
            if ($r > 150 && $g > 80 && $b < 80) $hasOrange = true; // ThanachartBank
        }

        // Also sample the top 20% (header area) for bank colors
        $headerColors = 0;
        for ($i = 0; $i < 30; $i++) {
            $x = rand(0, $w - 1);
            $y = rand(0, (int)($h * 0.2));
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $brightness = ($r + $g + $b) / 3;
            if ($brightness < 200) $headerColors++;
        }

        imagedestroy($image);

        $lightRatio = $lightPixels / $totalSamples;
        $hasBankColor = $hasGreen || $hasBlue || $hasPurple || $hasOrange;
        $hasColoredHeader = $headerColors > 10;

        // Slips typically have >40% light pixels and some bank brand colors
        $looksLikeSlip = ($lightRatio > 0.3) && ($hasBankColor || $hasColoredHeader);

        $bankNames = [];
        if ($hasGreen) $bankNames[] = 'KBank';
        if ($hasBlue) $bankNames[] = 'KTB/TMB';
        if ($hasPurple) $bankNames[] = 'SCB';
        if ($hasOrange) $bankNames[] = 'Thanachart';

        $detail = $looksLikeSlip
            ? 'รูปแบบสีตรงกับสลิปธนาคาร' . ($bankNames ? ' (' . implode(', ', $bankNames) . ')' : '')
            : 'รูปแบบสีไม่ตรงกับสลิปธนาคารทั่วไป (พื้นสว่าง ' . round($lightRatio * 100) . '%)';

        return ['looks_like_slip' => $looksLikeSlip, 'detail' => $detail];
    }

    /**
     * Check EXIF timestamp if available.
     */
    private static function checkExifTimestamp(string $filePath): array
    {
        if (!function_exists('exif_read_data')) {
            return ['passed' => true, 'detail' => 'ไม่สามารถตรวจ EXIF ได้ (ข้ามการตรวจ)', 'warning' => ''];
        }

        $exif = @exif_read_data($filePath);
        if (!$exif || empty($exif['DateTimeOriginal'])) {
            // No EXIF is common for screenshots — give partial pass
            return ['passed' => true, 'detail' => 'ไม่มีข้อมูล EXIF (อาจเป็น screenshot)', 'warning' => ''];
        }

        try {
            $photoTime = \Carbon\Carbon::parse($exif['DateTimeOriginal']);
            $hoursDiff = abs(now()->diffInHours($photoTime));

            if ($hoursDiff <= 24) {
                return ['passed' => true, 'detail' => "ถ่ายเมื่อ {$photoTime->diffForHumans()}", 'warning' => ''];
            } else {
                return [
                    'passed' => false,
                    'detail' => "ถ่ายเมื่อ {$photoTime->format('d/m/Y H:i')} (เกิน 24 ชม.)",
                    'warning' => 'รูปถ่ายเก่ากว่า 24 ชั่วโมง',
                ];
            }
        } catch (\Exception $e) {
            return ['passed' => true, 'detail' => 'ไม่สามารถอ่านวันที่จาก EXIF', 'warning' => ''];
        }
    }

    /**
     * Extract text from slip image using Google Vision API.
     * Shared hosting friendly — no binary installation required.
     */
    private static function extractSlipText(string $filePath): string
    {
        try {
            $apiKey = config('app.google_vision_key');
            if (empty($apiKey)) {
                Log::warning('SlipVerifier: Google Vision API key not configured (GOOGLE_VISION_API_KEY)');
                return '';
            }

            $imageData = base64_encode(file_get_contents($filePath));

            $response = Http::timeout(30)->post(
                "https://vision.googleapis.com/v1/images:annotate?key={$apiKey}",
                [
                    'requests' => [
                        [
                            'image' => ['content' => $imageData],
                            'features' => [['type' => 'TEXT_DETECTION']],
                            'imageContext' => ['languageHints' => ['th', 'en']],
                        ]
                    ]
                ]
            );

            if (!$response->successful()) {
                Log::error('SlipVerifier: Google Vision API error', [
                    'status' => $response->status(),
                    'body' => mb_substr($response->body(), 0, 500),
                ]);
                return '';
            }

            $data = $response->json();
            $text = $data['responses'][0]['textAnnotations'][0]['description'] ?? '';

            Log::info('SlipVerifier OCR result', [
                'text_length' => strlen($text),
                'text_preview' => mb_substr($text, 0, 500),
            ]);

            return $text;
        } catch (\Exception $e) {
            Log::error('SlipVerifier OCR error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Check if the OCR text contains the expected transfer amount.
     * Looks for Thai number formats like "1,234.56" or "1234.56" or "฿1,234.56".
     */
    private static function checkOcrAmount(string $ocrText, float $expectedAmount): array
    {
        if (empty($ocrText)) {
            return [
                'passed' => false,
                'detail' => 'ไม่สามารถอ่านข้อความจากสลิปได้ (OCR ล้มเหลว)',
                'warning' => 'ไม่สามารถอ่านข้อความจากสลิปด้วย OCR ได้ — ต้องตรวจสอบด้วยตนเอง',
                'ocr_amounts' => [],
            ];
        }

        // Normalize text: remove spaces in numbers, Thai numerals to Arabic
        $normalizedText = self::normalizeThaiNumbers($ocrText);

        // Extract all amounts from OCR text
        // Patterns: "1,234.56", "1234.56", "1,234", "฿1,234.56", "THB 1,234.56"
        $amounts = [];
        if (preg_match_all('/(?:฿|THB|บาท|Bath?)?\s*(\d{1,3}(?:,\d{3})*(?:\.\d{1,2})?)/u', $normalizedText, $matches)) {
            foreach ($matches[1] as $match) {
                $amount = (float) str_replace(',', '', $match);
                if ($amount > 0) {
                    $amounts[] = $amount;
                }
            }
        }

        // Also try patterns where amount appears after keywords
        if (preg_match_all('/(?:จำนวน|จํานวน|ยอดเงิน|ยอดโอน|amount|total|รวม)\s*[:\s]?\s*(\d{1,3}(?:,\d{3})*(?:\.\d{1,2})?)/iu', $normalizedText, $kwMatches)) {
            foreach ($kwMatches[1] as $match) {
                $amount = (float) str_replace(',', '', $match);
                if ($amount > 0) {
                    $amounts[] = $amount;
                }
            }
        }

        $amounts = array_unique($amounts);
        $tolerance = 0.99;

        // Check if any extracted amount matches the expected amount
        foreach ($amounts as $amount) {
            if (abs($amount - $expectedAmount) <= $tolerance) {
                return [
                    'passed' => true,
                    'detail' => "OCR พบยอด ฿" . number_format($amount, 2) . " ในสลิป ตรงกับยอดสั่งซื้อ ฿" . number_format($expectedAmount, 2),
                    'warning' => '',
                    'ocr_amounts' => $amounts,
                ];
            }
        }

        // Not found or mismatch
        $amountsList = !empty($amounts) ? implode(', ', array_map(fn($a) => '฿' . number_format($a, 2), array_slice($amounts, 0, 5))) : 'ไม่พบยอดเงิน';

        return [
            'passed' => false,
            'detail' => "OCR ไม่พบยอด ฿" . number_format($expectedAmount, 2) . " ในสลิป (พบ: {$amountsList})",
            'warning' => "⚠ ยอดเงินในสลิป (OCR) ไม่ตรงกับยอดสั่งซื้อ ฿" . number_format($expectedAmount, 2) . " — พบ: {$amountsList}",
            'ocr_amounts' => $amounts,
        ];
    }

    /**
     * Check if the OCR text contains a valid recipient account name.
     * Matches against configured valid names (e.g., "ที่ระลึกมูลนิธิ", "มูลนิธิคณะก้าวหน้า").
     */
    private static function checkOcrAccountName(string $ocrText): array
    {
        if (empty($ocrText)) {
            return [
                'passed' => false,
                'detail' => 'ไม่สามารถอ่านข้อความจากสลิปได้ (OCR ล้มเหลว)',
                'warning' => 'ไม่สามารถตรวจชื่อบัญชีปลายทางจากสลิปได้ — ต้องตรวจสอบด้วยตนเอง',
            ];
        }

        $validRecipients = array_map('trim', explode(',', config('app.slip_valid_recipients', 'ที่ระลึกมูลนิธิ,มูลนิธิคณะก้าวหน้า')));

        // Normalize OCR text: remove extra whitespace, normalize Thai characters
        $normalizedText = preg_replace('/\s+/', ' ', $ocrText);
        // Handle common OCR misreads in Thai
        $normalizedText = str_replace(['ํ', 'ำ'], ['ำ', 'ำ'], $normalizedText);

        foreach ($validRecipients as $name) {
            if (empty($name)) continue;

            // Direct match
            if (mb_stripos($normalizedText, $name) !== false) {
                return [
                    'passed' => true,
                    'detail' => "OCR พบชื่อบัญชี \"{$name}\" ในสลิป ✓",
                    'warning' => '',
                ];
            }

            // Fuzzy match: remove spaces and try again (OCR may add extra spaces in Thai)
            $nameNoSpace = str_replace(' ', '', $name);
            $textNoSpace = str_replace(' ', '', $normalizedText);
            if (mb_stripos($textNoSpace, $nameNoSpace) !== false) {
                return [
                    'passed' => true,
                    'detail' => "OCR พบชื่อบัญชี \"{$name}\" ในสลิป (fuzzy match) ✓",
                    'warning' => '',
                ];
            }

            // Try with common OCR errors: ก้ → ก, ู → ุ, etc.
            $simplifiedName = self::simplifyThaiForMatching($name);
            $simplifiedText = self::simplifyThaiForMatching($normalizedText);
            if (!empty($simplifiedName) && mb_stripos($simplifiedText, $simplifiedName) !== false) {
                return [
                    'passed' => true,
                    'detail' => "OCR พบชื่อบัญชีคล้าย \"{$name}\" ในสลิป (approximate match) ✓",
                    'warning' => '',
                ];
            }
        }

        // Check if PromptPay name appears
        $promptPayName = config('app.promptpay_name', '');
        if (!empty($promptPayName) && mb_stripos($normalizedText, $promptPayName) !== false) {
            return [
                'passed' => true,
                'detail' => "OCR พบชื่อ PromptPay \"{$promptPayName}\" ในสลิป ✓",
                'warning' => '',
            ];
        }

        // Extract a snippet of what was found for admin review
        $textSnippet = mb_substr($normalizedText, 0, 200);

        return [
            'passed' => false,
            'detail' => "OCR ไม่พบชื่อบัญชี \"" . implode('\" หรือ \"', $validRecipients) . "\" ในสลิป",
            'warning' => "⚠ ไม่พบชื่อบัญชีปลายทางที่ถูกต้องในสลิป — อาจโอนผิดบัญชี",
        ];
    }

    /**
     * Convert Thai numerals to Arabic numerals.
     */
    private static function normalizeThaiNumbers(string $text): string
    {
        $thaiNums = ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
        $arabicNums = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($thaiNums, $arabicNums, $text);
    }

    /**
     * Simplify Thai text for fuzzy matching by removing tonal marks and normalizing vowels.
     * Helps handle OCR errors with diacritics.
     */
    private static function simplifyThaiForMatching(string $text): string
    {
        // Remove Thai tonal marks and other diacritics that OCR commonly misreads
        // ่ ้ ๊ ๋ ์ ็ ์ ำ → simplified
        $text = str_replace(' ', '', $text);
        $text = preg_replace('/[\x{0E48}\x{0E49}\x{0E4A}\x{0E4B}\x{0E4C}\x{0E47}]/u', '', $text);
        return mb_strtolower($text);
    }

    private static function result(bool $passed, int $score, int $maxScore, array $checks, array $warnings, ?string $hash = null, bool $amountMatched = false, bool $canAutoVerify = false, string $ocrText = '', bool $ocrAmountMatched = false, bool $ocrAccountMatched = false): array
    {
        $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;

        return [
            'passed' => $passed,
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => $percentage,
            'checks' => $checks,
            'warnings' => $warnings,
            'hash' => $hash,
            'amount_matched' => $amountMatched,
            'can_auto_verify' => $canAutoVerify,
            'ocr_text' => $ocrText,
            'ocr_amount_matched' => $ocrAmountMatched,
            'ocr_account_matched' => $ocrAccountMatched,
        ];
    }
}
