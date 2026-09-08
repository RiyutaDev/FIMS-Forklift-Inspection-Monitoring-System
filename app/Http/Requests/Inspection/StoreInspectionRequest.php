<?php

namespace App\Http\Requests\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'forklift_id' => ['required', 'integer', 'exists:forklifts,id'],
            'operator_id' => ['required', 'integer', 'exists:users,id'],
            'inspection_shift' => ['required', 'string', 'in:Shift 1,Shift 2,Shift 3'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'forklift_id.required' => 'Forklift is required.',
            'forklift_id.exists' => 'The selected forklift does not exist.',
            'operator_id.required' => 'Operator is required.',
            'operator_id.exists' => 'The selected operator does not exist.',
            'inspection_shift.in' => 'Inspection shift must be one of: Shift 1, Shift 2, Shift 3.',
        ];
    }
}
