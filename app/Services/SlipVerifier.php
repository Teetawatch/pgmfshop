<?php

namespace App\Services;

use App\Models\Order;

class SlipVerifier
{
    /**
     * Verify a payment slip image with basic checks.
     *
     * Returns ['passed' => bool, 'score' => int, 'checks' => [...], 'warnings' => [...]]
     */
    public static function verify(string $filePath, float $expectedAmount): array
    {
        $checks = [];
        $warnings = [];
        $score = 0;
        $maxScore = 0;

        // 1. Valid image check
        $maxScore += 20;
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo) {
            $checks[] = ['name' => 'valid_image', 'passed' => true, 'detail' => "ไฟล์เป็นรูปภาพจริง ({$imageInfo['mime']})"];
            $score += 20;
        } else {
            $checks[] = ['name' => 'valid_image', 'passed' => false, 'detail' => 'ไฟล์ไม่ใช่รูปภาพที่ถูกต้อง'];
            return self::result(false, $score, $maxScore, $checks, ['ไฟล์ไม่ใช่รูปภาพ']);
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // 2. Dimension check — slips are typically portrait and at least 300px wide
        $maxScore += 15;
        $isPortrait = $height > $width;
        $isLargeEnough = $width >= 300 && $height >= 400;
        if ($isPortrait && $isLargeEnough) {
            $checks[] = ['name' => 'dimensions', 'passed' => true, 'detail' => "ขนาด {$width}x{$height}px (แนวตั้ง)"];
            $score += 15;
        } elseif ($isLargeEnough) {
            $checks[] = ['name' => 'dimensions', 'passed' => true, 'detail' => "ขนาด {$width}x{$height}px"];
            $score += 10;
            $warnings[] = 'สลิปไม่ใช่แนวตั้ง อาจเป็นภาพ screenshot';
        } else {
            $checks[] = ['name' => 'dimensions', 'passed' => false, 'detail' => "ขนาด {$width}x{$height}px เล็กเกินไป"];
            $warnings[] = 'รูปมีขนาดเล็กเกินไปสำหรับสลิปโอนเงิน';
        }

        // 3. File size check — real slips are usually 50KB-5MB
        $maxScore += 10;
        $fileSize = filesize($filePath);
        $fileSizeKB = round($fileSize / 1024);
        if ($fileSize >= 30 * 1024 && $fileSize <= 10 * 1024 * 1024) {
            $checks[] = ['name' => 'file_size', 'passed' => true, 'detail' => "{$fileSizeKB} KB"];
            $score += 10;
        } else {
            $checks[] = ['name' => 'file_size', 'passed' => false, 'detail' => "{$fileSizeKB} KB (ผิดปกติ)"];
            $warnings[] = $fileSize < 30 * 1024 ? 'ไฟล์เล็กเกินไป อาจไม่ใช่สลิปจริง' : 'ไฟล์ใหญ่เกินไป';
        }

        // 4. Duplicate slip check (perceptual hash)
        $maxScore += 25;
        $slipHash = self::imageHash($filePath);
        $duplicate = Order::where('slip_hash', $slipHash)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->first();

        if (!$duplicate) {
            $checks[] = ['name' => 'duplicate', 'passed' => true, 'detail' => 'ไม่พบสลิปซ้ำในระบบ'];
            $score += 25;
        } else {
            $checks[] = ['name' => 'duplicate', 'passed' => false, 'detail' => "สลิปซ้ำกับคำสั่งซื้อ {$duplicate->order_number}"];
            $warnings[] = "สลิปนี้เคยใช้กับคำสั่งซื้อ {$duplicate->order_number} แล้ว";
        }

        // 5. Color analysis — bank slips usually have specific dominant colors
        $maxScore += 15;
        $colorResult = self::analyzeColors($filePath, $imageInfo);
        if ($colorResult['looks_like_slip']) {
            $checks[] = ['name' => 'color_analysis', 'passed' => true, 'detail' => $colorResult['detail']];
            $score += 15;
        } else {
            $checks[] = ['name' => 'color_analysis', 'passed' => false, 'detail' => $colorResult['detail']];
            $warnings[] = 'สีของรูปไม่ตรงกับรูปแบบสลิปธนาคาร';
        }

        // 6. EXIF timestamp check — if available, should be recent
        $maxScore += 15;
        $exifResult = self::checkExifTimestamp($filePath);
        $checks[] = ['name' => 'timestamp', 'passed' => $exifResult['passed'], 'detail' => $exifResult['detail']];
        if ($exifResult['passed']) {
            $score += 15;
        } else {
            if (!empty($exifResult['warning'])) {
                $warnings[] = $exifResult['warning'];
            }
        }

        // Determine pass/fail: need at least 50% score AND no duplicate
        $passed = ($score / $maxScore >= 0.5) && !$duplicate;

        return self::result($passed, $score, $maxScore, $checks, $warnings, $slipHash);
    }

    /**
     * Generate a perceptual hash of the image for deduplication.
     * Uses average hash (aHash) — resize to 8x8, grayscale, compare to mean.
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

    private static function result(bool $passed, int $score, int $maxScore, array $checks, array $warnings, ?string $hash = null): array
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
        ];
    }
}
