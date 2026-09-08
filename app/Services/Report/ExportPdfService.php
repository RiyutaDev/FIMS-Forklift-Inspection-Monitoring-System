<?php

namespace App\Services\Report;

class ExportPdfService
{
    public function export(array $data): array
    {
        return ['format' => 'pdf', 'data' => $data];
    }
}
