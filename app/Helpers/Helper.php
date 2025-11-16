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
        $path = $image->getPathname();
        $data = file_get_contents($path);

        return base64_encode($data);
    }
}
