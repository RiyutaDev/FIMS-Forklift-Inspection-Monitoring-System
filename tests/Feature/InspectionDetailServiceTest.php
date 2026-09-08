<?php

namespace Tests\Feature;

use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Services\ActivityLogService;
use App\Services\InspectionDetailService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InspectionDetailServiceTest extends TestCase
{
    protected function createService(): InspectionDetailService
    {
        return new class(new ActivityLogService()) extends InspectionDetailService
        {
            public function exposeNormalizePayload(array $details): array
            {
                return $this->normalizePayload($details);
            }

            public function exposeNormalizeRow(array $checklist): array
            {
                return $this->normalizeRow($checklist);
            }

            public function exposeBuildDraftPayload(Inspection $inspection, array $details): array
            {
                return $this->buildDraftPayload($inspection, $details);
            }

            protected function loadInspectionItems(array $itemIds): Collection
            {
                return new Collection([
                    11 => (new InspectionItem())->forceFill([
                        'id' => 11,
                        'item_name' => 'Brake Test',
                        'input_type' => self::INPUT_TEXT,
                        'is_active' => true,
                        'requires_photo' => false,
                        'requires_note' => false,
                    ]),
                ]);
            }

            protected function validateChecklist(Inspection $inspection, InspectionItem $item, array $payload): string
            {
                return $payload['result_status'] ?? self::STATUS_NA;
            }
        };
    }

    #[Test]
    public function it_normalizes_payload_and_checklist_values(): void
    {
        $service = $this->createService();

        $payload = $service->exposeNormalizePayload([
            [
                'inspection_item_id' => '11',
                'result_status' => ' ok ',
                'result_value' => '10',
                'result_text' => '  good  ',
                'note' => '  note ',
            ],
        ]);

        $this->assertSame(11, $payload[0]['inspection_item_id']);
        $this->assertSame('OK', $payload[0]['result_status']);
        $this->assertSame('good', $payload[0]['result_text']);
        $this->assertSame('note', $payload[0]['note']);

        $checklist = $service->exposeNormalizeRow([
            'inspection_item_id' => '11',
            'result_status' => ' ng ',
            'result_value' => 3,
            'result_text' => '  sample  ',
            'note' => '  some note  ',
        ]);

        $this->assertSame(11, $checklist['inspection_item_id']);
        $this->assertSame('NG', $checklist['result_status']);
        $this->assertSame('sample', $checklist['result_text']);
        $this->assertSame('some note', $checklist['note']);
    }

    #[Test]
    public function it_builds_draft_payload_with_consistent_rows(): void
    {
        $service = $this->createService();
        $inspection = new Inspection();
        $inspection->id = 1;

        $payload = $service->exposeBuildDraftPayload($inspection, [
            [
                'inspection_item_id' => 11,
                'result_status' => 'ok',
                'result_value' => 5,
                'result_text' => 'ok',
                'note' => 'checked',
            ],
        ]);

        $this->assertArrayHasKey('bulk_data', $payload);
        $this->assertArrayHasKey('item_ids', $payload);
        $this->assertSame([11], $payload['item_ids']);
        $this->assertSame(1, $payload['bulk_data'][0]['inspection_id']);
        $this->assertSame('OK', $payload['bulk_data'][0]['result_status']);
        $this->assertTrue($payload['bulk_data'][0]['is_passed']);
    }
}
