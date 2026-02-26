<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class SlipVerifier
{
    /**
     * Max Hamming distance to consider two perceptual hashes as "similar".
     * 16x16 hash = 256 bits. Threshold 25 ≈ ~10% difference.
     */
    private const HAMMING_THRESHOLD = 25;

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

        // ── 1. Valid image check (20 pts) ──
        $maxScore += 20;
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo) {
            $checks[] = ['name' => 'valid_image', 'passed' => true, 'detail' => "ไฟล์เป็นรูปภาพจริง ({$imageInfo['mime']})"];
            $score += 20;
        } else {
            $checks[] = ['name' => 'valid_image', 'passed' => false, 'detail' => 'ไฟล์ไม่ใช่รูปภาพที่ถูกต้อง'];
            return self::result(false, $score, $maxScore, $checks, ['ไฟล์ไม่ใช่รูปภาพ'], null, false, false);
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // ── 2. Transfer amount validation (CRITICAL — 30 pts) ──
        $maxScore += 30;
        if ($transferAmount > 0) {
            $diff = abs($transferAmount - $expectedAmount);
            $tolerance = 0.99; // Allow rounding differences up to ~1 baht
            if ($diff <= $tolerance) {
                $checks[] = ['name' => 'amount_match', 'passed' => true, 'detail' => "ยอดโอน ฿" . number_format($transferAmount, 2) . " ตรงกับยอดสั่งซื้อ ฿" . number_format($expectedAmount, 2)];
                $score += 30;
                $amountMatched = true;
            } else {
                $checks[] = ['name' => 'amount_match', 'passed' => false, 'detail' => "ยอดโอน ฿" . number_format($transferAmount, 2) . " ไม่ตรงกับยอดสั่งซื้อ ฿" . number_format($expectedAmount, 2) . " (ต่างกัน ฿" . number_format($diff, 2) . ")"];
                $warnings[] = "ยอดเงินที่โอนไม่ตรงกับยอดสั่งซื้อ (ต่างกัน ฿" . number_format($diff, 2) . ")";
            }
        } else {
            $checks[] = ['name' => 'amount_match', 'passed' => false, 'detail' => 'ไม่ได้ระบุจำนวนเงินที่โอน'];
            $warnings[] = 'ไม่ได้ระบุจำนวนเงินที่โอน';
        }

        // ── 3. Transfer date/time reasonableness (10 pts) ──
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

        // ── 4. Dimension check (10 pts) ──
        $maxScore += 10;
        $isPortrait = $height > $width;
        $isLargeEnough = $width >= 300 && $height >= 400;
        if ($isPortrait && $isLargeEnough) {
            $checks[] = ['name' => 'dimensions', 'passed' => true, 'detail' => "ขนาด {$width}x{$height}px (แนวตั้ง)"];
            $score += 10;
        } elseif ($isLargeEnough) {
            $checks[] = ['name' => 'dimensions', 'passed' => true, 'detail' => "ขนาด {$width}x{$height}px"];
            $score += 7;
            $warnings[] = 'สลิปไม่ใช่แนวตั้ง อาจเป็นภาพ screenshot';
        } else {
            $checks[] = ['name' => 'dimensions', 'passed' => false, 'detail' => "ขนาด {$width}x{$height}px เล็กเกินไป"];
            $warnings[] = 'รูปมีขนาดเล็กเกินไปสำหรับสลิปโอนเงิน';
        }

        // ── 5. File size check (5 pts) ──
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

        // ── 6. Duplicate slip check — exact + fuzzy Hamming (25 pts) ──
        $maxScore += 25;
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
            $fuz = $duplicateResult['fuzzy_match'];
            $dist = $duplicateResult['fuzzy_distance'];
            $checks[] = ['name' => 'duplicate', 'passed' => false, 'detail' => "สลิปคล้ายกับคำสั่งซื้อ {$fuz->order_number} (ความต่าง {$dist}/256 bits)"];
            $warnings[] = "สลิปคล้ายกับสลิปที่เคยใช้ในคำสั่งซื้อ {$fuz->order_number} (อาจถูก crop/resize)";
            if ($duplicateResult['is_cross_user']) {
                $warnings[] = "⚠ สลิปที่คล้ายนี้เคยถูกใช้โดยลูกค้าคนอื่น";
            }
        } else {
            $checks[] = ['name' => 'duplicate', 'passed' => true, 'detail' => 'ไม่พบสลิปซ้ำหรือคล้ายในระบบ'];
            $score += 25;
        }
        $hasDuplicate = $duplicateResult['exact_match'] || $duplicateResult['fuzzy_match'];

        // ── 7. Color analysis (10 pts) ──
        $maxScore += 10;
        $colorResult = self::analyzeColors($filePath, $imageInfo);
        if ($colorResult['looks_like_slip']) {
            $checks[] = ['name' => 'color_analysis', 'passed' => true, 'detail' => $colorResult['detail']];
            $score += 10;
        } else {
            $checks[] = ['name' => 'color_analysis', 'passed' => false, 'detail' => $colorResult['detail']];
            $warnings[] = 'สีของรูปไม่ตรงกับรูปแบบสลิปธนาคาร';
        }

        // ── 8. EXIF timestamp check (5 pts, bonus) ──
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

        // ── 9. Rate limiting per user (warning only, no pts) ──
        if ($userId) {
            $rateLimitResult = self::checkUserRateLimit($userId);
            $checks[] = ['name' => 'rate_limit', 'passed' => $rateLimitResult['passed'], 'detail' => $rateLimitResult['detail']];
            if (!$rateLimitResult['passed']) {
                $warnings[] = $rateLimitResult['warning'];
            }
        }

        // ── 10. Suspicious same-amount detection (warning only, no pts) ──
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
        // 1. Amount matches exactly
        // 2. No duplicate or fuzzy-similar slip
        // 3. Transfer date/time is reasonable
        // 4. Overall score >= 70%
        // 5. Not rate-limited
        // 6. No suspicious same-amount pattern
        $noRateLimitIssue = !$userId || (self::checkUserRateLimit($userId)['passed'] ?? true);
        $noSuspiciousAmount = !($transferAmount > 0 && $userId) || (self::checkSuspiciousAmount($transferAmount, $transferDate, $userId)['passed'] ?? true);

        $canAutoVerify = $amountMatched
            && !$hasDuplicate
            && $transferDateTimePassed
            && ($score / $maxScore >= 0.7)
            && $noRateLimitIssue
            && $noSuspiciousAmount;

        return self::result($passed, $score, $maxScore, $checks, $warnings, $slipHash, $amountMatched, $canAutoVerify);
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

    private static function result(bool $passed, int $score, int $maxScore, array $checks, array $warnings, ?string $hash = null, bool $amountMatched = false, bool $canAutoVerify = false): array
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
        ];
    }
}
