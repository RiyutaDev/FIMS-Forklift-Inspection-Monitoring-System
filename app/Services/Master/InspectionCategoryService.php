<?php

namespace App\Services\Master;

use App\Models\InspectionCategory;

class InspectionCategoryService
{
    public function findById(int $id): ?InspectionCategory
    {
        return InspectionCategory::find($id);
    }
}
