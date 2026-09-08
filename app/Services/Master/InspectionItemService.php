<?php

namespace App\Services\Master;

use App\Models\InspectionItem;

class InspectionItemService
{
    public function findById(int $id): ?InspectionItem
    {
        return InspectionItem::find($id);
    }
}
