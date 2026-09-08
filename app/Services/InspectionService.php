<?php

namespace App\Services;

use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InspectionService
{
    /**
     * ==========================================================
     * MODULE
     * ==========================================================
     */
    private const MODULE = 'Inspection';

    /**
     * ==========================================================
     * STATUS
     * ==========================================================
     */
    public const STATUS_DRAFT = 'Draft';
    public const STATUS_SUBMITTED = 'Submitted';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    /**
     * ==========================================================
     * RESULT
     * ==========================================================
     */
    public const RESULT_READY = 'Ready';
    public const RESULT_NOT_READY = 'Not Ready';

    /**
     * ==========================================================
     * SHIFT
     * ==========================================================
     */
    public const SHIFT_1 = 'Shift 1';
    public const SHIFT_2 = 'Shift 2';
    public const SHIFT_3 = 'Shift 3';

    /**
     * Shift yang diperbolehkan.
     */
    private array $availableShift = [
        self::SHIFT_1,
        self::SHIFT_2,
        self::SHIFT_3,
    ];

    /**
     * Activity Log Service.
     */
    protected ActivityLogService $activityLogService;

    /**
     * Constructor.
     */
    public function __construct(
        ActivityLogService $activityLogService
    ) {
        $this->activityLogService = $activityLogService;
    }

    /**
     * ==========================================================
     * HELPER
     * ==========================================================
     */

    /**
     * Validasi Shift.
     *
     * @throws RuntimeException
     */
    protected function validateShift(string $shift): void
    {
        if (!in_array($shift, $this->availableShift, true)) {
            throw new RuntimeException(
                "Shift '{$shift}' tidak valid."
            );
        }
    }

    /**
     * Pastikan inspection masih Draft.
     *
     * @throws RuntimeException
     */
    protected function ensureDraft(
        Inspection $inspection
    ): void {

        if (!$inspection->isDraft()) {
            throw new RuntimeException(
                'Inspection yang telah disubmit tidak dapat diubah.'
            );
        }

    }

    /**
     * Cek duplicate inspection.
     */
    protected function checkDuplicateInspection(
        Forklift $forklift,
        string $shift,
        ?Carbon $date = null
    ): ?Inspection {

        $date ??= today();

        return Inspection::where('forklift_id', $forklift->id)
            ->whereDate('inspection_date', $date)
            ->where('inspection_shift', $shift)
            ->first();

    }

    /**
     * Generate Inspection Number.
     */
    protected function generateInspectionNumber(
        Forklift $forklift
    ): string {

        return InspectionNumberService::generate(
            $forklift
        );

    }

    /**
     * Simpan Activity Log.
     */
    protected function logCreated(
        Inspection $inspection,
        Forklift $forklift,
        User $operator
    ): void {

        $this->activityLogService->created(
            self::MODULE,
            $inspection,
            [
                'inspection_number' => $inspection->inspection_number,
                'forklift_code'     => $forklift->forklift_code,
                'operator'          => $operator->name,
                'shift'             => $inspection->inspection_shift,
            ]
        );

    }

    /**
     * Log Update.
     */
    protected function logUpdated(
        Inspection $inspection,
        array $before
    ): void {

        $this->activityLogService->updated(
            self::MODULE,
            $inspection,
            [
                'before' => $before,
                'after'  => $inspection->fresh()->toArray(),
            ]
        );

    }

        /*
    |--------------------------------------------------------------------------
    | CREATE INSPECTION
    |--------------------------------------------------------------------------
    */

    /**
     * Membuat Draft Inspection Baru.
     *
     * @throws \Throwable
     */
    public function createInspection(
        Forklift $forklift,
        User $operator,
        string $shift,
        ?string $remarks = null
    ): Inspection {

        $this->validateShift($shift);

        if (!$forklift->is_active) {
            throw new RuntimeException(
                'Forklift tidak aktif dan tidak dapat dilakukan inspection.'
            );
        }

        if (!$operator->is_active) {
            throw new RuntimeException(
                'Operator tidak aktif.'
            );
        }

        if ($this->checkDuplicateInspection($forklift, $shift)) {
            throw new RuntimeException(
                'Inspection untuk forklift ini pada shift yang sama sudah tersedia.'
            );
        }

        DB::beginTransaction();

        try {

            $inspection = Inspection::create([

                'inspection_number'      => $this->generateInspectionNumber($forklift),

                'forklift_id'            => $forklift->id,

                'operator_id'            => $operator->id,

                'inspection_date'        => today(),

                'inspection_shift'       => $shift,

                'inspection_started_at'  => now(),

                'inspection_completed_at'=> null,

                'overall_result'         => null,

                'status'                 => self::STATUS_DRAFT,

                'remarks'                => $remarks,

                'submitted_by'           => null,

                'submitted_at'           => null,

            ]);

            /**
             * Activity Log
             */

            $this->logCreated(
                $inspection,
                $forklift,
                $operator
            );

            DB::commit();

            return $inspection->fresh();

        } catch (\Throwable $exception) {

            DB::rollBack();

            report($exception);

            throw $exception;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FROM REQUEST
    |--------------------------------------------------------------------------
    */

    /**
     * Membuat inspection menggunakan array request.
     */
    public function createFromArray(
        array $data
    ): Inspection {

        $forklift = Forklift::findOrFail(
            $data['forklift_id']
        );

        $operator = User::findOrFail(
            $data['operator_id']
        );

        return $this->createInspection(
            forklift: $forklift,
            operator: $operator,
            shift: $data['inspection_shift'],
            remarks: $data['remarks'] ?? null
        );

    }
        /*
    |--------------------------------------------------------------------------
    | UPDATE INSPECTION
    |--------------------------------------------------------------------------
    */

    /**
     * Update Draft Inspection.
     *
     * @throws \Throwable
     */
    public function updateInspection(
        Inspection $inspection,
        array $data
    ): Inspection {

        $this->ensureDraft($inspection);

        DB::beginTransaction();

        try {

            $before = $inspection->only([
                'inspection_shift',
                'remarks',
            ]);

            $updateData = [];

            /*
            |--------------------------------------------------------------------------
            | Update Shift
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists('inspection_shift', $data)
                && $data['inspection_shift'] !== $inspection->inspection_shift
            ) {

                $this->validateShift($data['inspection_shift']);

                $duplicate = $this->checkDuplicateInspection(
                    $inspection->forklift,
                    $data['inspection_shift'],
                    Carbon::parse($inspection->inspection_date)
                );

                if ($duplicate && $duplicate->id !== $inspection->id) {
                    throw new RuntimeException(
                        'Shift tersebut sudah memiliki inspection.'
                    );
                }

                $updateData['inspection_shift'] =
                    $data['inspection_shift'];
            }

            /*
            |--------------------------------------------------------------------------
            | Update Remarks
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists('remarks', $data)
                && $data['remarks'] !== $inspection->remarks
            ) {

                $updateData['remarks'] = $data['remarks'];

            }

            /*
            |--------------------------------------------------------------------------
            | Tidak ada perubahan
            |--------------------------------------------------------------------------
            */

            if (empty($updateData)) {

                DB::rollBack();

                return $inspection;

            }

            /*
            |--------------------------------------------------------------------------
            | Save
            |--------------------------------------------------------------------------
            */

            $inspection->update($updateData);

            /*
            |--------------------------------------------------------------------------
            | Activity Log
            |--------------------------------------------------------------------------
            */

            $this->logUpdated(
                $inspection,
                $before
            );

            DB::commit();

            return $inspection->fresh();

        } catch (\Throwable $exception) {

            DB::rollBack();

            report($exception);

            throw $exception;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE REMARKS
    |--------------------------------------------------------------------------
    */

    /**
     * Update hanya remarks.
     */
    public function updateRemarks(
        Inspection $inspection,
        ?string $remarks
    ): Inspection {

        return $this->updateInspection(
            $inspection,
            [
                'remarks' => $remarks,
            ]
        );

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE SHIFT
    |--------------------------------------------------------------------------
    */

    /**
     * Update hanya shift.
     */
    public function updateShift(
        Inspection $inspection,
        string $shift
    ): Inspection {

        return $this->updateInspection(
            $inspection,
            [
                'inspection_shift' => $shift,
            ]
        );

    }
        /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    /**
     * Cari Inspection berdasarkan ID.
     */
    public function findById(int $id): ?Inspection
    {
        return Inspection::with([
            'forklift.location',
            'operator',
            'submittedBy',
            'details.inspectionItem',
            'approval',
        ])->find($id);
    }

    /**
     * Cari Inspection berdasarkan Nomor Inspection.
     */
    public function findByInspectionNumber(
        string $inspectionNumber
    ): ?Inspection {

        return Inspection::where(
            'inspection_number',
            $inspectionNumber
        )->first();

    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    /**
     * History Inspection Forklift.
     */
    public function getHistory(
        Forklift $forklift,
        int $limit = 20
    ): Collection {

        return Inspection::with([
                'operator',
                'approval',
            ])
            ->withCount('details')
            ->where('forklift_id', $forklift->id)
            ->latest('inspection_date')
            ->latest('id')
            ->limit($limit)
            ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | CHECK DRAFT
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah forklift memiliki draft.
     */
    public function hasDraft(
        Forklift $forklift,
        ?int $exceptInspectionId = null
    ): bool {

        $query = Inspection::draft()
            ->where('forklift_id', $forklift->id);

        if ($exceptInspectionId !== null) {
            $query->where('id', '!=', $exceptInspectionId);
        }

        return $query->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | TODAY INSPECTION
    |--------------------------------------------------------------------------
    */

    /**
     * Ambil inspection hari ini.
     */
    public function getTodayInspection(
        Forklift $forklift,
        string $shift
    ): ?Inspection {

        return Inspection::where('forklift_id', $forklift->id)
            ->whereDate('inspection_date', today())
            ->where('inspection_shift', $shift)
            ->first();

    }

    /**
     * Ambil draft inspection hari ini.
     */
    public function getTodayDraft(
        Forklift $forklift,
        string $shift
    ): ?Inspection {

        return Inspection::draft()
            ->where('forklift_id', $forklift->id)
            ->whereDate('inspection_date', today())
            ->where('inspection_shift', $shift)
            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Hapus Draft Inspection.
     *
     * @throws \Throwable
     */
    public function deleteDraft(
        Inspection $inspection
    ): bool {

        $this->ensureDraft($inspection);

        DB::beginTransaction();

        try {

            $inspection->delete();

            $this->activityLogService->deleted(
                self::MODULE,
                $inspection,
                [
                    'inspection_number' => $inspection->inspection_number,
                ]
            );

            DB::commit();

            return true;

        } catch (\Throwable $exception) {

            DB::rollBack();

            report($exception);

            throw $exception;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSION HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Apakah inspection masih dapat diedit.
     */
    public function canEdit(
        Inspection $inspection
    ): bool {

        return $inspection->isDraft();

    }

    /**
     * Apakah inspection masih dapat dihapus.
     */
    public function canDelete(
        Inspection $inspection
    ): bool {

        return $inspection->isDraft();

    }

}