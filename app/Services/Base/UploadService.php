<?php

namespace App\Services\Base;

class UploadService extends BaseService
{
    public function upload(mixed $file, string $path = 'uploads'): string
    {
        if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
            return rtrim($path, '/') . '/' . $file->getClientOriginalName();
        }

        return $path;
    }
}
