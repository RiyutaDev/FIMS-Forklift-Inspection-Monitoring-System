<?php

namespace App\Http\Resources\Inspection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inspection_id' => $this->inspection_id,
            'inspection_item_id' => $this->inspection_item_id,
            'result_status' => $this->result_status,
            'result_value' => $this->result_value,
            'result_text' => $this->result_text,
            'note' => $this->note,
            'is_passed' => $this->is_passed,
            'inspection_item' => $this->whenLoaded('inspectionItem', function () {
                return [
                    'id' => $this->inspectionItem?->id,
                    'item_name' => $this->inspectionItem?->item_name,
                    'input_type' => $this->inspectionItem?->input_type,
                    'requires_photo' => (bool) $this->inspectionItem?->requires_photo,
                    'requires_note' => (bool) $this->inspectionItem?->requires_note,
                ];
            }),
        ];
    }
}
