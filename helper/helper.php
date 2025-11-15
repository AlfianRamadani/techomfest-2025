<?php

class Helper
{
    public function validateImageType($image)
    {

        $type = exif_imagetype($image['tmp_name']); // get the type of image
        return $type !== false; // return false if not image 
    }
    public function validateImageSize($image)
    {
        $maxSize = 5 * 1024 * 1024; // 5 MB
        $size = filesize($image['tmp_name']); // get size of image
        if ($size > $maxSize) return false;  // return false if image size larger than 5mb
    }
    static public function imageToBase64($image)
    {
        $path = $image["tmp_name"];
        $type = mime_content_type($path);
        $data = file_get_contents($path);

        return 'data:' . $type . ';base64,' . base64_encode($data);
    }
}
