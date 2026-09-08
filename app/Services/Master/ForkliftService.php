<?php

namespace App\Services\Master;

use App\Models\Forklift;

class ForkliftService
{
    public function findById(int $id): ?Forklift
    {
        return Forklift::find($id);
    }
}
