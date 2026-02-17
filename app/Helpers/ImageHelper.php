<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getImageUrl(string $path): string
    {
        return asset(path: 'storage/'.$path);
    }
}
