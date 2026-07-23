<?php

use App\Helpers\Toastr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

if (! function_exists('toastr')) {
    function toastr(?string $message = null, string $type = 'success'): Toastr
    {
        $instance = app(Toastr::class);
        if ($message) {
            return $instance->$type($message);
        }

        return $instance;
    }
}

if (! function_exists('crop_and_save')) {
    function crop_and_save(UploadedFile $file, string $path, int $width = 1920, int $height = 800): string
    {
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        if ($image === false) {
            throw new RuntimeException('Unable to process image');
        }

        $origW = imagesx($image);
        $origH = imagesy($image);

        $srcRatio = $origW / $origH;
        $dstRatio = $width / $height;

        if ($srcRatio > $dstRatio) {
            $newH = $origH;
            $newW = (int) round($origH * $dstRatio);
        } else {
            $newW = $origW;
            $newH = (int) round($origW / $dstRatio);
        }

        $srcX = (int) round(($origW - $newW) / 2);
        $srcY = (int) round(($origH - $newH) / 2);

        $thumb = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumb, $image, 0, 0, $srcX, $srcY, $width, $height, $newW, $newH);
        imagedestroy($image);

        $filename = pathinfo($file->hashName(), PATHINFO_FILENAME).'.webp';
        $savePath = $path.'/'.$filename;

        ob_start();
        imagewebp($thumb, null, 85);
        $contents = ob_get_clean();
        imagedestroy($thumb);

        Storage::disk('public')->put($savePath, $contents);

        return $savePath;
    }
}
