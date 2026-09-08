<?php

namespace App\Services\Inspection;

use App\Models\Inspection;
use App\Models\InspectionItem;
use RuntimeException;

class InspectionValidationService
{
    public function ensureDraft(Inspection $inspection): void
    {
        if (!$inspection->isDraft()) {
            throw new RuntimeException('Inspection tidak dalam status Draft.');
        }
    }

    public function ensureRequiredChecklist(Inspection $inspection): void
    {
        $requiredCount = (new InspectionDetailService(app('App\\Services\\Base\\ActivityLogService')))->requiredChecklistCount($inspection);

        $filledCount = $inspection->details()->count();

        if ($filledCount < $requiredCount) {
            throw new RuntimeException('Checklist belum lengkap untuk submit.');
        }
    }

    public function validateItem(InspectionItem $item, mixed $value): void
    {
        if ($item->requires_photo && empty($value['photo'] ?? null)) {
            throw new RuntimeException("{$item->item_name} wajib melampirkan foto.");
        }

        if ($item->requires_note && blank($value['note'] ?? null)) {
            throw new RuntimeException("{$item->item_name} wajib mengisi catatan.");
        }
    }
}
