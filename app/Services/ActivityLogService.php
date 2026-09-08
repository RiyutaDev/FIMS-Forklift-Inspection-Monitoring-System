<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Simpan activity log.
     */
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

    /**
     * Login
     */
    public static function login(Model $user): ActivityLog
    {
        return self::log(
            module: 'Authentication',
            action: 'Login',
            description: "{$user->name} berhasil login.",
            subject: $user,
            userId: $user->id
        );
    }

    /**
     * Logout
     */
    public static function logout(Model $user): ActivityLog
    {
        return self::log(
            module: 'Authentication',
            action: 'Logout',
            description: "{$user->name} logout dari sistem.",
            subject: $user,
            userId: $user->id
        );
    }

    /**
     * Create
     */
    public static function created(
        string $module,
        Model $model,
        ?array $properties = null
    ): ActivityLog {

        return self::log(

            module: $module,

            action: 'Create',

            description: "{$module} berhasil dibuat.",

            subject: $model,

            properties: $properties

        );
    }

    /**
     * Update
     */
    public static function updated(
        string $module,
        Model $model,
        ?array $properties = null
    ): ActivityLog {

        return self::log(

            module: $module,

            action: 'Update',

            description: "{$module} berhasil diperbarui.",

            subject: $model,

            properties: $properties

        );
    }

    /**
     * Delete
     */
    public static function deleted(
        string $module,
        Model $model
    ): ActivityLog {

        return self::log(

            module: $module,

            action: 'Delete',

            description: "{$module} berhasil dihapus.",

            subject: $model

        );
    }

    /**
     * Restore
     */
    public static function restored(
        string $module,
        Model $model
    ): ActivityLog {

        return self::log(

            module: $module,

            action: 'Restore',

            description: "{$module} berhasil dipulihkan.",

            subject: $model

        );
    }

    /**
     * Submit Inspection
     */
    public static function submitInspection(Model $inspection): ActivityLog
    {
        return self::log(

            module: 'Inspection',

            action: 'Submit',

            description: 'Inspection berhasil dikirim.',

            subject: $inspection

        );
    }

    /**
     * Approve Inspection
     */
    public static function approveInspection(Model $inspection): ActivityLog
    {
        return self::log(

            module: 'Inspection',

            action: 'Approve',

            description: 'Inspection disetujui Supervisor.',

            subject: $inspection

        );
    }

    /**
     * Reject Inspection
     */
    public static function rejectInspection(
        Model $inspection,
        string $reason
    ): ActivityLog {

        return self::log(

            module: 'Inspection',

            action: 'Reject',

            description: 'Inspection ditolak Supervisor.',

            subject: $inspection,

            properties: [

                'reason' => $reason

            ]

        );
    }

    /**
     * Export Report
     */
    public static function export(
        string $reportName
    ): ActivityLog {

        return self::log(

            module: 'Report',

            action: 'Export',

            description: "Export laporan {$reportName}"

        );
    }
}