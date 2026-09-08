<?php

namespace App\Http\Resources\Inspection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inspection_number' => $this->inspection_number,
            'forklift_id' => $this->forklift_id,
            'operator_id' => $this->operator_id,
            'inspection_date' => $this->inspection_date?->toDateString(),
            'inspection_shift' => $this->inspection_shift,
            'status' => $this->status,
            'overall_result' => $this->overall_result,
            'remarks' => $this->remarks,
            'submitted_by' => $this->submitted_by,
            'submitted_at' => $this->submitted_at?->toISOString(),
            'inspection_started_at' => $this->inspection_started_at?->toISOString(),
            'inspection_completed_at' => $this->inspection_completed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'forklift' => $this->whenLoaded('forklift', function () {
                return [
                    'id' => $this->forklift?->id,
                    'forklift_code' => $this->forklift?->forklift_code,
                    'name' => $this->forklift?->name,
                ];
            }),
            'operator' => $this->whenLoaded('operator', function () {
                return [
                    'id' => $this->operator?->id,
                    'name' => $this->operator?->name,
                ];
            }),
            'details' => InspectionDetailResource::collection($this->whenLoaded('details')),
        ];
    }
}
