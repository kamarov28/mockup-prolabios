<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ImageWatermarkService
{
    /**
     * Apply diagonal Prolabios logo watermark with ~20% opacity onto GD image resource.
     *
     * @param  resource|\GdImage  $destImg
     */
    public static function apply(&$destImg, float $opacity = 0.20, int $angle = 25): void
    {
        $logoPath = public_path('images/logo-prolabios.png');
        if (! file_exists($logoPath)) {
            return;
        }

        $logo = @imagecreatefrompng($logoPath);
        if (! $logo) {
            return;
        }

        $dw = imagesx($destImg);
        $dh = imagesy($destImg);
        $lw = imagesx($logo);
        $lh = imagesy($logo);

        if ($dw <= 0 || $dh <= 0 || $lw <= 0 || $lh <= 0) {
            imagedestroy($logo);

            return;
        }

        // Calculate proportional watermark target width to span nicely across destination
        $targetW = max(180, min(1400, (int) round(max($dw, $dh) * 0.90)));
        $targetH = (int) round(($lh / $lw) * $targetW);

        $scaled = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($scaled, false);
        imagesavealpha($scaled, true);
        $trans = imagecolorallocatealpha($scaled, 0, 0, 0, 127);
        imagefill($scaled, 0, 0, $trans);
        imagecopyresampled($scaled, $logo, 0, 0, 0, 0, $targetW, $targetH, $lw, $lh);
        imagedestroy($logo);

        // Adjust alpha channel of every non-transparent pixel to specified opacity (0.20)
        for ($x = 0; $x < $targetW; $x++) {
            for ($y = 0; $y < $targetH; $y++) {
                $color = imagecolorat($scaled, $x, $y);
                $rgba = imagecolorsforindex($scaled, $color);
                $currAlpha = $rgba['alpha']; // 0 = opaque, 127 = full transparent
                if ($currAlpha < 127) {
                    $effectiveOpacity = (127 - $currAlpha) / 127.0;
                    $newOpacity = $effectiveOpacity * $opacity;
                    $newAlpha = 127 - (int) round($newOpacity * 127);
                    $newColor = imagecolorallocatealpha($scaled, $rgba['red'], $rgba['green'], $rgba['blue'], $newAlpha);
                    imagesetpixel($scaled, $x, $y, $newColor);
                }
            }
        }

        // Rotate by $angle degrees counter-clockwise (so it slants from bottom-left to top-right)
        $rotated = imagerotate($scaled, $angle, $trans);
        imagealphablending($rotated, false);
        imagesavealpha($rotated, true);
        imagedestroy($scaled);

        $rw = imagesx($rotated);
        $rh = imagesy($rotated);
        $dstX = (int) round(($dw - $rw) / 2);
        $dstY = (int) round(($dh - $rh) / 2);

        // Merge onto destination preserving alpha blending
        imagealphablending($destImg, true);
        imagecopy($destImg, $rotated, $dstX, $dstY, 0, 0, $rw, $rh);
        imagedestroy($rotated);
    }

    /**
     * Apply watermark directly to an image file on disk and save back in original format.
     */
    public static function applyToFile(string $absolutePath, float $opacity = 0.20, int $angle = 25): bool
    {
        if (! file_exists($absolutePath) || ! is_file($absolutePath)) {
            return false;
        }

        $raw = @file_get_contents($absolutePath);
        if ($raw === false || $raw === '') {
            return false;
        }

        $img = @imagecreatefromstring($raw);
        if (! $img) {
            return false;
        }

        self::apply($img, $opacity, $angle);

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        imagealphablending($img, false);
        imagesavealpha($img, true);

        $success = false;
        if ($ext === 'webp') {
            $success = imagewebp($img, $absolutePath, 85);
        } elseif ($ext === 'png') {
            $success = imagepng($img, $absolutePath, 8);
        } else {
            $success = imagejpeg($img, $absolutePath, 85);
        }

        imagedestroy($img);

        return $success;
    }
}
