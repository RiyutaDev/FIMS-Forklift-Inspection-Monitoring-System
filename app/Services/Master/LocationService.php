<?php

namespace App\Services\Master;

use App\Models\Location;

class LocationService
{
    public function findById(int $id): ?Location
    {
        return Location::find($id);
    }
}
