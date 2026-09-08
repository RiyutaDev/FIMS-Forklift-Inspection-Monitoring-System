<?php

namespace App\Services\Base;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService extends BaseService
{
    public static function log(
        string $module,
        string $action,
        string $description,
        ?Model $subject = null,
        ?array $properties = null,
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId ?? Auth::id(),
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => $properties,
        ]);
    }

    public static function created(string $module, Model $model, ?array $properties = null): ActivityLog
    {
        return self::log(
            module: $module,
            action: 'Create',
            description: "{$module} berhasil dibuat.",
            subject: $model,
            properties: $properties
        );
    }

    public static function updated(string $module, Model $model, ?array $properties = null): ActivityLog
    {
        return self::log(
            module: $module,
            action: 'Update',
            description: "{$module} berhasil diperbarui.",
            subject: $model,
            properties: $properties
        );
    }

    public static function submitInspection(Model $inspection): ActivityLog
    {
        return self::log(
            module: 'Inspection',
            action: 'Submit',
            description: 'Inspection berhasil dikirim.',
            subject: $inspection
        );
    }
}
