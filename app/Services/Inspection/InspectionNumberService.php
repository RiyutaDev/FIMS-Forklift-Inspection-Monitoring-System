<?php

namespace App\Services\Inspection;

use App\Models\Forklift;
use App\Models\Inspection;

class InspectionNumberService
{
    public static function generate(Forklift $forklift): string
    {
        $locationCode = strtoupper($forklift->location->location_code);
        $yearMonth = now()->format('Ym');

        $lastInspection = Inspection::query()
            ->whereHas('forklift', function ($query) use ($forklift) {
                $query->where('location_id', $forklift->location_id);
            })
            ->whereYear('inspection_date', now()->year)
            ->whereMonth('inspection_date', now()->month)
            ->orderByDesc('id')
            ->first();

        $runningNumber = 1;

        if ($lastInspection) {
            $runningNumber = intval(substr($lastInspection->inspection_number, -4)) + 1;
        }

        return sprintf('INS-%s-%s-%04d', $locationCode, $yearMonth, $runningNumber);
    }
}
