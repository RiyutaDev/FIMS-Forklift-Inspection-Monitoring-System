<?php

namespace App\Services\Report;

class ExportExcelService
{
    public function export(array $data): array
    {
        return ['format' => 'excel', 'data' => $data];
    }
}
