<?php

namespace App\Services\Vendor;

class VendorService
{
    public function ping(): array
    {
        return ['status' => 'ok'];
    }
}
