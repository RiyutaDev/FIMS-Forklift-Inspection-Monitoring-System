<?php

namespace Tests\Feature;

use App\Http\Requests\Inspection\SaveDraftInspectionRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InspectionChecklistValidationTest extends TestCase
{
    #[Test]
    public function it_rejects_invalid_checklist_payload_shape(): void
    {
        $request = new SaveDraftInspectionRequest();

        $validator = Validator::make([
            'details' => [
                [
                    'inspection_item_id' => 1,
                    'result_status' => 'OK',
                    'checklist_payload' => [
                        ['field' => 'brake', 'value' => 'ok'],
                        ['field' => 123],
                    ],
                ],
            ],
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('details.0.checklist_payload.1.field', $validator->errors()->toArray());
    }
}
