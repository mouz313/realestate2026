<?php

namespace App\Helpers;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

class QrHelper
{
    public static function pngDataUri(string $data, int $size = 140): string
    {
        $matrix = Encoder::encode($data, ErrorCorrectionLevel::M())->getMatrix();

        $width = $matrix->getWidth();
        $scale = max(1, (int) round($size / $width));
        $padding = 2;
        $imgSize = ($width + $padding * 2) * $scale;

        $img = imagecreatetruecolor($imgSize, $imgSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $white);

        for ($y = 0; $y < $width; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($matrix->get($x, $y)) {
                    imagefilledrectangle(
                        $img,
                        ($x + $padding) * $scale,
                        ($y + $padding) * $scale,
                        ($x + $padding + 1) * $scale - 1,
                        ($y + $padding + 1) * $scale - 1,
                        $black
                    );
                }
            }
        }

        ob_start();
        imagepng($img);
        $png = ob_get_clean();

        return 'data:image/png;base64,'.base64_encode($png);
    }
}
