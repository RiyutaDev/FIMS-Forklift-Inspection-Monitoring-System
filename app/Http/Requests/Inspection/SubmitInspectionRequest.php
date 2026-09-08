<?php

namespace App\Http\Requests\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class SubmitInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'details' => ['required', 'array', 'min:1'],
            'details.*.inspection_item_id' => ['required', 'integer', 'exists:inspection_items,id'],
            'details.*.result_status' => ['nullable', 'string', 'in:OK,NG,NA'],
            'details.*.result_value' => ['nullable', 'numeric'],
            'details.*.result_text' => ['nullable', 'string', 'max:1000'],
            'details.*.note' => ['nullable', 'string', 'max:2000'],
            'details.*.checklist_payload' => ['nullable', 'array'],
            'details.*.checklist_payload.*.field' => ['nullable', 'string', 'max:255'],
            'details.*.checklist_payload.*.value' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'details.required' => 'At least one inspection detail row is required before submission.',
            'details.min' => 'At least one inspection detail row is required before submission.',
        ];
    }
}
