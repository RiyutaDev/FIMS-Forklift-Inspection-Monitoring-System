<?php

namespace App\Http\Resources\Inspection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'inspection_id' => $this->inspection_id ?? $this->id,
            'total_items' => $this->total_items ?? 0,
            'passed_items' => $this->passed_items ?? 0,
            'failed_items' => $this->failed_items ?? 0,
            'na_items' => $this->na_items ?? 0,
            'completion_percentage' => $this->completion_percentage ?? 0,
            'overall_result' => $this->overall_result,
        ];
    }
}
