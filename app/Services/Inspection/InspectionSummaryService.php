<?php

namespace App\Services\Inspection;

use App\Models\Inspection;

class InspectionSummaryService
{
    public function summary(Inspection $inspection): array
    {
        $detailService = new InspectionDetailService(app('App\\Services\\Base\\ActivityLogService'));

        return $detailService->calculateSummary($inspection);
    }
}
