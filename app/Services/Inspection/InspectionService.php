<?php

namespace App\Services\Inspection;

use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\User;
use App\Services\Base\ActivityLogService;
use App\Services\Inspection\InspectionNumberService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InspectionService
{
    public const STATUS_DRAFT = 'Draft';
    public const STATUS_SUBMITTED = 'Submitted';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    public const RESULT_READY = 'Ready';
    public const RESULT_NOT_READY = 'Not Ready';

    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function createInspection(Forklift $forklift, User $operator, string $shift, ?string $remarks = null): Inspection
    {
        if (!$forklift->is_active) {
            throw new RuntimeException('Forklift tidak aktif dan tidak dapat dilakukan inspection.');
        }

        if (!$operator->is_active) {
            throw new RuntimeException('Operator tidak aktif.');
        }

        DB::beginTransaction();

        try {
            $inspection = Inspection::create([
                'inspection_number' => InspectionNumberService::generate($forklift),
                'forklift_id' => $forklift->id,
                'operator_id' => $operator->id,
                'inspection_date' => today(),
                'inspection_shift' => $shift,
                'inspection_started_at' => now(),
                'status' => self::STATUS_DRAFT,
                'remarks' => $remarks,
            ]);

            $this->activityLogService::created('Inspection', $inspection, [
                'inspection_number' => $inspection->inspection_number,
            ]);

            DB::commit();

            return $inspection;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
