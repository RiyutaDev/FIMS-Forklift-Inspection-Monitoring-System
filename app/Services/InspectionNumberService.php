<?php

namespace App\Services;

use App\Models\Inspection;
use App\Models\Forklift;
use Carbon\Carbon;

class InspectionNumberService
{
    public static function generate(Forklift $forklift): string
    {
        // 1. Pastikan relasi location terisi (Safe Navigation)
        $locationCode = strtoupper($forklift->location?->location_code ?? 'GEN');

        $now = Carbon::now();
        $yearMonth = $now->format('Ym');

        // 2. Cari inspeksi terakhir di lokasi dan bulan/tahun yang sama
        $lastInspection = Inspection::whereHas('forklift', function ($query) use ($forklift) {
                $query->where('location_id', $forklift->location_id);
            })
            ->whereYear('inspection_date', $now->year)
            ->whereMonth('inspection_date', $now->month)
            ->orderByDesc('id')
            ->first();

        $runningNumber = 1;

        // 3. Ambil 4 digit terakhir jika data terdahulu ditemukan
        if ($lastInspection && $lastInspection->inspection_number) {
            $lastNumber = intval(substr($lastInspection->inspection_number, -4));
            $runningNumber = $lastNumber + 1;
        }

        // 4. Return format INS-LD-202607-0001
        return sprintf(
            'INS-%s-%s-%04d',
            $locationCode,
            $yearMonth,
            $runningNumber
        );
    }
}