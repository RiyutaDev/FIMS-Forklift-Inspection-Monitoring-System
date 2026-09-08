<?php

namespace App\Services\Inspection;

use App\Models\Inspection;
use App\Models\InspectionDetail;
use App\Models\InspectionItem;
use App\Models\User;
use App\Services\Base\ActivityLogService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class InspectionDetailService
{
    private const MODULE = 'Inspection Detail';

    public const STATUS_OK = 'OK';
    public const STATUS_NG = 'NG';
    public const STATUS_NA = 'N/A';

    public const INPUT_CHECKBOX = 'checkbox';
    public const INPUT_NUMBER = 'number';
    public const INPUT_TEXT = 'text';
    public const INPUT_SELECT = 'select';

    protected array $allowedResultStatus = [self::STATUS_OK, self::STATUS_NG, self::STATUS_NA];
    protected array $allowedInputTypes = [self::INPUT_CHECKBOX, self::INPUT_NUMBER, self::INPUT_TEXT, self::INPUT_SELECT];

    protected array $uniqueBy = ['inspection_id', 'inspection_item_id'];
    protected array $updatableColumns = ['result_status', 'result_value', 'result_text', 'note', 'is_passed', 'updated_at'];

    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    protected function validateDraft(Inspection $inspection): void
    {
        if (!$inspection->isDraft()) {
            throw new RuntimeException('Inspection sudah disubmit sehingga checklist tidak dapat diubah.');
        }
    }

    protected function validateInspectionItem(InspectionItem $item): void
    {
        if (!$item->is_active) {
            throw new RuntimeException("Inspection Item '{$item->item_name}' sudah tidak aktif.");
        }
    }

    protected function validateFuelType(Inspection $inspection, InspectionItem $item): void
    {
        $fuelType = strtoupper((string) $inspection->forklift->fuel_type);
        $applicable = strtoupper((string) $item->applicable_fuel_type);

        if (blank($applicable) || $applicable === 'ALL') {
            return;
        }

        if ($fuelType !== $applicable) {
            throw new RuntimeException("Item '{$item->item_name}' tidak berlaku untuk forklift dengan fuel type '{$inspection->forklift->fuel_type}'.");
        }
    }

    protected function validateResultStatus(string $status): string
    {
        $status = strtoupper(trim((string) $status));

        if ($status === '') {
            $status = self::STATUS_NA;
        }

        if (!in_array($status, $this->allowedResultStatus, true)) {
            throw new RuntimeException("Result Status '{$status}' tidak valid.");
        }

        return $status;
    }

    protected function validateInputType(InspectionItem $item): void
    {
        if (!in_array($item->input_type, $this->allowedInputTypes, true)) {
            throw new RuntimeException("Input Type '{$item->input_type}' pada item '{$item->item_name}' tidak dikenali.");
        }
    }

    protected function validateInputValue(InspectionItem $item, mixed $value): void
    {
        switch ($item->input_type) {
            case self::INPUT_CHECKBOX:
                if (!is_bool($value)) {
                    throw new RuntimeException("{$item->item_name} harus bernilai TRUE atau FALSE.");
                }
                break;

            case self::INPUT_NUMBER:
                if ($value !== null && !is_numeric($value)) {
                    throw new RuntimeException("{$item->item_name} harus berupa angka.");
                }
                break;

            case self::INPUT_TEXT:
                if ($value !== null && !is_string($value)) {
                    throw new RuntimeException("{$item->item_name} harus berupa text.");
                }
                break;

            case self::INPUT_SELECT:
                if ($value !== null && !is_string($value)) {
                    throw new RuntimeException("{$item->item_name} memiliki nilai pilihan yang tidak valid.");
                }
                break;
        }
    }

    protected function validatePhotoRequirement(InspectionItem $item, array $payload): void
    {
        if ($item->requires_photo && empty($payload['photo'] ?? null)) {
            throw new RuntimeException("{$item->item_name} wajib melampirkan foto.");
        }
    }

    protected function validateNoteRequirement(InspectionItem $item, array $payload): void
    {
        if ($item->requires_note && blank($payload['note'] ?? null)) {
            throw new RuntimeException("{$item->item_name} wajib mengisi catatan.");
        }
    }

    protected function validateDuplicateItems(array $details): void
    {
        $duplicates = collect($details)->pluck('inspection_item_id')->duplicates();

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException('Terdapat checklist yang dikirim lebih dari satu kali.');
        }
    }

    protected function validateChecklist(Inspection $inspection, InspectionItem $item, array $payload): string
    {
        $this->validateDraft($inspection);
        $this->validateInspectionItem($item);
        $this->validateFuelType($inspection, $item);
        $this->validateInputType($item);

        $status = $this->validateResultStatus($payload['result_status'] ?? self::STATUS_NA);

        $this->validateInputValue($item, $payload['result_value'] ?? $payload['result_text'] ?? null);
        $this->validatePhotoRequirement($item, $payload);
        $this->validateNoteRequirement($item, $payload);

        return $status;
    }

    protected function normalizePayload(array $details): array
    {
        return collect($details)
            ->map(fn (array $row): array => $this->normalizeRow($row))
            ->values()
            ->all();
    }

    protected function normalizeRow(array $row): array
    {
        return [
            'inspection_item_id' => (int) ($row['inspection_item_id'] ?? 0),
            'result_status' => $this->validateResultStatus($row['result_status'] ?? self::STATUS_NA),
            'result_value' => $row['result_value'] ?? null,
            'result_text' => trim((string) ($row['result_text'] ?? '')),
            'note' => trim((string) ($row['note'] ?? '')),
        ];
    }

    protected function loadInspectionItems(array $itemIds): Collection
    {
        return InspectionItem::query()
            ->where('is_active', true)
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');
    }

    protected function buildDraftPayload(Inspection $inspection, array $details): array
    {
        if (empty($details)) {
            throw new RuntimeException('Checklist inspection tidak boleh kosong.');
        }

        $normalized = $this->normalizePayload($details);
        $this->validateDuplicateItems($normalized);

        $itemIds = collect($normalized)
            ->pluck('inspection_item_id')
            ->filter(fn ($id) => (int) $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($itemIds === []) {
            throw new RuntimeException('Checklist inspection tidak boleh kosong.');
        }

        $items = $this->loadInspectionItems($itemIds);

        if ($items->count() !== count($itemIds)) {
            throw new RuntimeException('Sebagian Inspection Item tidak ditemukan atau sudah tidak aktif.');
        }

        $bulkData = [];

        foreach ($normalized as $row) {
            $item = $items->get($row['inspection_item_id']);

            if (!$item) {
                throw new RuntimeException("Inspection Item ID {$row['inspection_item_id']} tidak ditemukan.");
            }

            $status = $this->validateChecklist($inspection, $item, $row);

            $bulkData[] = [
                'inspection_id' => $inspection->id,
                'inspection_item_id' => $item->id,
                'result_status' => $status,
                'result_value' => $row['result_value'] ?? null,
                'result_text' => $row['result_text'] ?? null,
                'note' => $row['note'] ?? null,
                'is_passed' => $this->calculatePassStatus($status),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return [
            'bulk_data' => $bulkData,
            'item_ids' => $itemIds,
        ];
    }

    protected function buildSubmitPayload(Inspection $inspection, array $details): array
    {
        return $this->buildDraftPayload($inspection, $details);
    }

    protected function executeBulkUpsert(Inspection $inspection, array $rows): void
    {
        if ($rows === []) {
            return;
        }

        InspectionDetail::query()->upsert($rows, $this->uniqueBy, $this->updatableColumns);
    }

    protected function cleanupRemovedItems(Inspection $inspection, array $incomingItemIds): void
    {
        InspectionDetail::query()
            ->where('inspection_id', $inspection->id)
            ->whereNotIn('inspection_item_id', $incomingItemIds)
            ->delete();
    }

    protected function refreshInspection(Inspection $inspection): Inspection
    {
        return $inspection->fresh(['details', 'details.inspectionItem', 'forklift', 'operator']);
    }

    protected function calculatePassStatus(string $resultStatus): bool
    {
        $resultStatus = $this->validateResultStatus($resultStatus);

        return in_array($resultStatus, [self::STATUS_OK, self::STATUS_NA], true);
    }

    public function calculateProgress(int $filledChecklist, int $totalChecklist): float
    {
        if ($totalChecklist <= 0) {
            return 0;
        }

        return round(($filledChecklist / $totalChecklist) * 100, 2);
    }

    public function calculateCompletion(int $filledChecklist, int $totalChecklist): bool
    {
        if ($totalChecklist <= 0) {
            return false;
        }

        return $filledChecklist >= $totalChecklist;
    }

    public function calculateSummary(Inspection $inspection): array
    {
        $details = $inspection->details()->get();
        $requiredChecklist = $this->requiredChecklistCount($inspection);
        $total = $details->count();
        $ok = $details->where('result_status', self::STATUS_OK)->count();
        $ng = $details->where('result_status', self::STATUS_NG)->count();
        $na = $details->where('result_status', self::STATUS_NA)->count();
        $passed = $details->where('is_passed', true)->count();
        $failed = $details->where('is_passed', false)->count();

        return [
            'total_required' => $requiredChecklist,
            'filled' => $total,
            'ok' => $ok,
            'ng' => $ng,
            'na' => $na,
            'passed' => $passed,
            'failed' => $failed,
            'progress' => $this->calculateProgress($total, $requiredChecklist),
            'is_completed' => $this->calculateCompletion($total, $requiredChecklist),
        ];
    }

    public function calculateOverallResult(Inspection $inspection): string
    {
        $summary = $this->calculateSummary($inspection);

        return $summary['failed'] > 0 ? InspectionService::RESULT_NOT_READY : InspectionService::RESULT_READY;
    }

    public function calculatePassPercentage(Inspection $inspection): float
    {
        $summary = $this->calculateSummary($inspection);

        if ($summary['filled'] === 0) {
            return 0;
        }

        return round(($summary['passed'] / $summary['filled']) * 100, 2);
    }

    public function saveDraft(Inspection $inspection, array $details): Inspection
    {
        $this->validateDraft($inspection);

        $prepared = $this->buildDraftPayload($inspection, $details);

        DB::beginTransaction();

        try {
            $this->executeBulkUpsert($inspection, $prepared['bulk_data']);
            $this->cleanupRemovedItems($inspection, $prepared['item_ids']);
            $inspection = $this->refreshInspection($inspection);
            $this->logDraftSaved($inspection);
            DB::commit();

            return $inspection;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function submitInspection(Inspection $inspection, array $details, ?User $submittedBy = null): Inspection
    {
        $this->validateDraft($inspection);

        $prepared = $this->buildSubmitPayload($inspection, $details);

        DB::beginTransaction();

        try {
            $this->executeBulkUpsert($inspection, $prepared['bulk_data']);
            $this->cleanupRemovedItems($inspection, $prepared['item_ids']);

            $inspection->forceFill([
                'overall_result' => $this->calculateOverallResult($inspection),
                'status' => InspectionService::STATUS_SUBMITTED,
                'submitted_by' => $submittedBy?->id,
                'submitted_at' => now(),
                'inspection_completed_at' => now(),
            ])->save();

            $inspection = $this->refreshInspection($inspection);
            $this->logSubmitted($inspection);
            DB::commit();

            return $inspection;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findDetail(Inspection $inspection, InspectionItem $item): ?InspectionDetail
    {
        return InspectionDetail::query()
            ->where('inspection_id', $inspection->id)
            ->where('inspection_item_id', $item->id)
            ->first();
    }

    public function findByInspection(Inspection $inspection): Collection
    {
        return InspectionDetail::query()
            ->with('inspectionItem')
            ->where('inspection_id', $inspection->id)
            ->orderBy('inspection_item_id')
            ->get();
    }

    public function findByItem(InspectionItem $item): Collection
    {
        return InspectionDetail::query()
            ->where('inspection_item_id', $item->id)
            ->get();
    }

    public function getFailedItems(Inspection $inspection): Collection
    {
        return InspectionDetail::query()
            ->with('inspectionItem')
            ->where('inspection_id', $inspection->id)
            ->where('is_passed', false)
            ->get();
    }

    public function getPassedItems(Inspection $inspection): Collection
    {
        return InspectionDetail::query()
            ->with('inspectionItem')
            ->where('inspection_id', $inspection->id)
            ->where('is_passed', true)
            ->get();
    }

    public function requiredChecklistCount(Inspection $inspection): int
    {
        return InspectionItem::query()
            ->where('is_active', true)
            ->where(function ($query) use ($inspection) {
                $query
                    ->whereNull('applicable_fuel_type')
                    ->orWhere('applicable_fuel_type', '')
                    ->orWhere('applicable_fuel_type', 'ALL')
                    ->orWhere('applicable_fuel_type', $inspection->forklift->fuel_type);
            })
            ->count();
    }

    public function isCompleted(Inspection $inspection): bool
    {
        return $this->findByInspection($inspection)->count() >= $this->requiredChecklistCount($inspection);
    }

    protected function logDraftSaved(Inspection $inspection): void
    {
        $this->activityLogService->updated(self::MODULE, $inspection, [
            'action' => 'draft_saved',
            'status' => $inspection->status,
        ]);
    }

    protected function logSubmitted(Inspection $inspection): void
    {
        $this->activityLogService->submitInspection($inspection);
    }
}
