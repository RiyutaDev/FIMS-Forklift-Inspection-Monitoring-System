<?php

namespace Tests\Feature;

use App\Http\Requests\Inspection\SaveDraftInspectionRequest;
use App\Http\Requests\Inspection\StoreInspectionRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InspectionRequestValidationTest extends TestCase
{
    #[Test]
    public function it_requires_valid_store_payload(): void
    {
        $request = new StoreInspectionRequest();

        $validator = Validator::make([
            'forklift_id' => 9999,
            'operator_id' => 9999,
            'inspection_shift' => 'Night Shift',
            'remarks' => 123,
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('forklift_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('operator_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('inspection_shift', $validator->errors()->toArray());
        $this->assertArrayHasKey('remarks', $validator->errors()->toArray());
    }

    #[Test]
    public function it_requires_draft_payload_to_contain_valid_detail_rows(): void
    {
        $request = new SaveDraftInspectionRequest();

        $validator = Validator::make([
            'details' => [
                [
                    'result_status' => 'bad-value',
                ],
            ],
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('details.0.inspection_item_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('details.0.result_status', $validator->errors()->toArray());
    }
}
