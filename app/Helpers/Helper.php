<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class Helper
{
    public function validateImageType(UploadedFile $image)
    {
        $type = exif_imagetype($image->getPathname());
        return $type !== false;
    }

    public function validateImageSize(UploadedFile $image)
    {
        $maxSize = 5 * 1024 * 1024; // 5 MB
        return $image->getSize() <= $maxSize;
    }

     public static function imageToBase64(UploadedFile $image): string
    {
        // Bersihkan EXIF
        $cleanImage = self::stripImageExif($image);

        return base64_encode($cleanImage);
    }

    public static function stripImageExif(UploadedFile $file): string
    {
        $binary = file_get_contents($file->getPathname());

        $image = imagecreatefromstring($binary);

        ob_start();

        switch (strtolower($file->getClientOriginalExtension())) {
            case 'png':
                imagepng($image, null, 9);
                break;

            case 'webp':
                imagewebp($image, null, 80);
                break;

            case 'jpg':
            case 'jpeg':
            default:
                imagejpeg($image, null, 90);
                break;
        }

        $clean = ob_get_clean();
        imagedestroy($image);

        return $clean;
    }

}
