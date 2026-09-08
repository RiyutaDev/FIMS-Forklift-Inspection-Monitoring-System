<?php

namespace App\Services\Inspection;

use App\Models\Approval;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InspectionApprovalService
{
    public function approve(Inspection $inspection, User $approver, ?string $note = null): Approval
    {
        if (!$inspection->isSubmitted()) {
            throw new RuntimeException('Inspection belum disubmit.');
        }

        return DB::transaction(function () use ($inspection, $approver, $note) {
            $approval = Approval::updateOrCreate(
                ['inspection_id' => $inspection->id],
                [
                    'approved_by' => $approver->id,
                    'approval_status' => 'Approved',
                    'approval_note' => $note,
                    'approved_at' => now(),
                ]
            );

            $inspection->forceFill([
                'status' => 'Approved',
                'overall_result' => $inspection->overall_result ?? 'Ready',
            ])->save();

            return $approval;
        });
    }

    public function reject(Inspection $inspection, User $approver, string $reason): Approval
    {
        if (!$inspection->isSubmitted()) {
            throw new RuntimeException('Inspection belum disubmit.');
        }

        return DB::transaction(function () use ($inspection, $approver, $reason) {
            $approval = Approval::updateOrCreate(
                ['inspection_id' => $inspection->id],
                [
                    'approved_by' => $approver->id,
                    'approval_status' => 'Rejected',
                    'approval_note' => $reason,
                    'approved_at' => now(),
                ]
            );

            $inspection->forceFill([
                'status' => 'Rejected',
            ])->save();

            return $approval;
        });
    }
}
