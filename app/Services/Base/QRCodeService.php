<?php

namespace App\Services\Base;

class QRCodeService extends BaseService
{
    public function generate(string $payload): string
    {
        return 'qr:' . $payload;
    }
}
