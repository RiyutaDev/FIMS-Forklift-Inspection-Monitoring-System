<?php

namespace App\Services\Inspection;

use App\Models\Inspection;
use App\Models\User;
use App\Services\Base\ActivityLogService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InspectionWorkflowService
{
    protected InspectionDetailService $detailService;
    protected InspectionValidationService $validationService;
    protected ActivityLogService $activityLogService;

    public function __construct(
        InspectionDetailService $detailService,
        InspectionValidationService $validationService,
        ActivityLogService $activityLogService
    ) {
        $this->detailService = $detailService;
        $this->validationService = $validationService;
        $this->activityLogService = $activityLogService;
    }

    public function submit(Inspection $inspection, array $details, ?User $submittedBy = null): Inspection
    {
        $this->validationService->ensureDraft($inspection);
        $this->validationService->ensureRequiredChecklist($inspection);

        return DB::transaction(function () use ($inspection, $details, $submittedBy) {
            $this->detailService->saveDraft($inspection, $details);

            $inspection->forceFill([
                'status' => 'Submitted',
                'submitted_by' => $submittedBy?->id,
                'submitted_at' => now(),
                'inspection_completed_at' => now(),
                'overall_result' => $this->detailService->calculateOverallResult($inspection),
            ])->save();

            $this->activityLogService::submitInspection($inspection);

            return $inspection->fresh();
        });
    }
}
