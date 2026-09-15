<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\InspectionCategory;
use App\Models\InspectionDetail;
use App\Models\InspectionItem;
use App\Models\InspectionPhoto;
use App\Services\InspectionNumberService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InspectionController extends Controller
{
    /**
     * Menampilkan daftar/histori inspeksi harian.
     * Driver hanya melihat miliknya sendiri (Own), Supervisor & Admin melihat semua (All).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Inspection::with(['forklift', 'operator']);

        // Jika role Driver/Operator, batasi hanya data miliknya sendiri (BR-Own History)
        if ($user->isDriver()) {
            $query->where('operator_id', $user->id);
        }

        // Filter berdasarkan Tanggal jika ada
        if ($request->filled('date')) {
            $query->whereDate('inspection_date', $request->date);
        }

        // Filter berdasarkan Status (Draft, Submitted, Approved, Rejected)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inspections = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Langkah 1: Form Pemilihan Unit Forklift / Scan QR Code.
     */
    public function create(Request $request)
{
    $user = Auth::user();

    // Hanya Admin dan Operator/Driver yang boleh membuat inspeksi
    if (!$user->isAdmin() && !$user->isDriver()) {
        abort(403, 'Anda tidak memiliki akses untuk membuat inspeksi.');
    }

    // Admin dapat melihat semua forklift aktif
    // Operator hanya melihat forklift di lokasi kerjanya
    $forklifts = Forklift::with('location')
        ->active()
        ->when(!$user->isAdmin(), function ($q) use ($user) {
            $q->where('location_id', $user->location_id);
        })
        ->orderBy('forklift_code')
        ->get();

    // Operator untuk dipilih Admin
    $operators = collect();

    if ($user->isAdmin()) {
        $operators = \App\Models\User::whereHas('role', function ($query) {
                $query->whereIn('role_name', ['Operator', 'Driver']);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    $selectedForklift = null;

            // QR Code
        if ($request->filled('qr_token')) {

            $selectedForklift = Forklift::with('location')->where(
                'qr_token',
                $request->qr_token
            )->first();

            if (!$selectedForklift) {
                return redirect()
                    ->route('inspections.create')
                    ->with(
                        'error',
                        'QR Code forklift tidak ditemukan dalam sistem.'
                    );
            }

            if (!$selectedForklift->is_active) {
                return redirect()
                    ->route('inspections.create')
                    ->with(
                        'error',
                        "Forklift {$selectedForklift->forklift_code} sedang nonaktif dan tidak dapat digunakan untuk inspeksi."
                    );
            }

                    if (!$user->isAdmin() && (int) $selectedForklift->location_id !== (int) $user->location_id) {
                    return redirect()
                        ->route('inspections.create')
                        ->with('error', 'Forklift tidak sesuai dengan lokasi kerja Anda.');
                    }

        } elseif ($request->filled('forklift_id')) {

            $selectedForklift = Forklift::find($request->forklift_id);

            if (!$selectedForklift) {
                return redirect()
                    ->route('inspections.create')
                    ->with(
                        'error',
                        'Forklift tidak ditemukan dalam sistem.'
                    );
            }

            if (!$selectedForklift->is_active) {
                return redirect()
                    ->route('inspections.create')
                    ->with(
                        'error',
                        "Forklift {$selectedForklift->forklift_code} sedang nonaktif dan tidak dapat digunakan untuk inspeksi."
                    );
            }

                    if (!$user->isAdmin() && (int) $selectedForklift->location_id !== (int) $user->location_id) {
                    return redirect()
                        ->route('inspections.create')
                        ->with('error', 'Forklift tidak sesuai dengan lokasi kerja Anda.');
                    }
        }

        return view('inspections.create', compact(
            'forklifts',
            'operators',
            'selectedForklift'
        ));
    }

    /**
     * Langkah 2: Menampilkan Formulir Lembar Kerja (Checklist) Inspeksi.
     */
    public function checklist(Request $request, Inspection $inspection = null)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isDriver()) {
        abort(403, 'Anda tidak memiliki akses ke checklist inspeksi.');
    }
        $forkliftId = $request->query('forklift_id');
        $shift = $request->query('shift', 'Shift 1');
        $today = Carbon::today()->toDateString();

        /// Cari forklift berdasarkan ID
        $forklift = Forklift::with('location')
            ->when(
                !$user->isAdmin(),
                fn ($query) => $query->where('location_id', $user->location_id)
            )
            ->find($forkliftId);

        if (!$forklift) {
            return redirect()
                ->route('inspections.create')
                ->with('error', 'Forklift tidak ditemukan atau tidak sesuai dengan lokasi kerja Anda.');
        }

        // Tolak jika forklift berstatus nonaktif
        if (!$forklift->is_active) {
            return redirect()
                ->route('inspections.create')
                ->with(
                    'error',
                    "Forklift {$forklift->forklift_code} sedang nonaktif dan tidak dapat digunakan untuk inspeksi."
                );
        }

        // ATURAN BISNIS BR-012: Satu forklift hanya punya 1 inspeksi per tanggal & shift
        $existingInspection = Inspection::where('forklift_id', $forklift->id)
            ->whereDate('inspection_date', $today)
            ->where('inspection_shift', $shift)
            ->first();

        if ($existingInspection && $existingInspection->status !== 'Draft') {
            return redirect()->route('inspections.show', $existingInspection->id)
                ->with('info', "Inspeksi untuk forklift {$forklift->forklift_code} pada {$shift} hari ini telah di-submit.");
        }

        // Ambil kategori & item inspeksi aktif yang berlaku untuk tipe bahan bakar forklift ini
        $categories = InspectionCategory::with(['inspectionItems' => function ($query) use ($forklift) {
            $query->where('is_active', true)
                ->whereIn('applicable_fuel_type', ['All', $forklift->fuel_type])
                ->orderBy('sort_order');
        }])->where('is_active', true)->orderBy('sort_order')->get();

        return view('inspections.checklist', compact(
            'forklift',
            'categories',
            'shift',
            'today',
            'existingInspection'
        ));
    }

   /**
 * Langkah 3: Memproses & Menyimpan Hasil Inspeksi (Checklist & Foto).
 */
public function store(Request $request)
{
    // =========================================================
    // 1. VALIDASI INPUT
    // =========================================================
    $request->validate([
        'forklift_id' => [
            'required',
            'exists:forklifts,id',
        ],

        'inspection_shift' => [
            'required',
            'in:Shift 1,Shift 2,Shift 3',
        ],

        'remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],

        'items' => [
            'required',
            'array',
            'min:1',
        ],

        'items.*' => [
            'required',
            'array',
        ],

        'items.*.value' => [
            'required',
            'string',
        ],

        'items.*.note' => [
            'nullable',
            'string',
            'max:255',
        ],

        'photos' => [
            'nullable',
            'array',
        ],

        'photos.*' => [
            'nullable',
            'file',
            'image',
            'mimes:jpg,jpeg,png',
            'max:2048',
        ],

        'operator_id' => [
            'nullable',
            'exists:users,id',
        ],
    ]);


    $items = $request->input('items', []);

    // =========================================================
    // 2. DATA USER & FORKLIFT
    // =========================================================
    $user = Auth::user();

    $forklift = Forklift::with('location')
        ->when(
            !$user->isAdmin(),
            function ($query) use ($user) {
                $query->where('location_id', $user->location_id);
            }
        )
        ->findOrFail($request->forklift_id);


    // =========================================================
    // 3. CEK STATUS FORKLIFT TERKINI
    // =========================================================
    if (!$forklift->is_active) {
        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                "Forklift {$forklift->forklift_code} sedang nonaktif dan tidak dapat digunakan untuk inspeksi."
            );
    }

    $itemMasters = InspectionItem::with('category')
        ->whereIn('id', array_keys($items))
        ->get()
        ->keyBy('id');

    foreach ($items as $itemId => $itemData) {
        $itemMaster = $itemMasters->get($itemId);
        $value = $itemData['value'] ?? null;

        if (!$itemMaster || !$itemMaster->is_active || !$itemMaster->category?->is_active) {
            throw ValidationException::withMessages([
                "items.{$itemId}.value" => 'Item checklist tidak valid atau sudah tidak aktif.',
            ]);
        }

        if (!in_array($itemMaster->applicable_fuel_type, ['All', $forklift->fuel_type], true)) {
            throw ValidationException::withMessages([
                "items.{$itemId}.value" => 'Item checklist tidak sesuai dengan jenis bahan bakar forklift.',
            ]);
        }

        $isFailed = in_array($itemMaster->input_type, ['OK_NG', 'YES_NO'], true)
            && in_array($value, ['NG', 'NO'], true);

        $photo = $request->file("photos.{$itemId}");

        if ($isFailed && (!$photo || !$photo->isValid())) {
            throw ValidationException::withMessages([
                "photos.{$itemId}" => "Foto wajib diunggah untuk item {$itemMaster->item_name} yang berstatus NG.",
            ]);
        }
    }


    // =========================================================
    // 4. TANGGAL INSPEKSI
    // =========================================================
    $today = Carbon::today()->toDateString();


    // =========================================================
    // 5. TRANSACTION
    // =========================================================
    try {

        DB::beginTransaction();

        // =====================================================
        // 6. CEK APAKAH SUDAH ADA INSPEKSI
        // =====================================================
        $inspection = Inspection::where('forklift_id', $forklift->id)
            ->whereDate('inspection_date', $today)
            ->where('inspection_shift', $request->inspection_shift)
            ->first();


        $operator = $inspection?->operator;

        // =====================================================
        // 5. JIKA SUDAH ADA DAN BUKAN DRAFT
        //    MAKA TIDAK BOLEH SUBMIT ULANG
        // =====================================================
        if ($inspection && $inspection->status !== 'Draft') {

            DB::rollBack();

            return redirect()
                ->route('inspections.show', $inspection->id)
                ->with(
                    'info',
                    "Inspeksi forklift {$forklift->forklift_code} pada {$request->inspection_shift} hari ini sudah di-submit."
                );
        }


        // =====================================================
        // 6. JIKA BELUM ADA, BUAT HEADER INSPEKSI
        // =====================================================
        if (!$inspection) {

            $inspection = new Inspection();

            $inspection->forklift_id = $forklift->id;

            $inspection->inspection_date = $today;

            $inspection->inspection_shift = $request->inspection_shift;

            $inspection->inspection_number =
                InspectionNumberService::generate($forklift);

            if ($user->isAdmin()) {
                $operatorId = $request->input('operator_id');

                if (!$operatorId) {
                    throw new \Exception('Operator harus dipilih oleh Administrator.');
                }

                $operator = \App\Models\User::where('id', $operatorId)
                    ->whereHas('role', function ($query) {
                        $query->whereIn('role_name', ['Operator', 'Driver']);
                    })
                    ->where('is_active', true)
                    ->first();

                if (!$operator) {
                    throw ValidationException::withMessages([
                        'operator_id' => 'Operator yang dipilih tidak valid atau tidak aktif.',
                    ]);
                }

                $inspection->operator_id = $operator->id;

            } else {

                $operator = $user;
                $inspection->operator_id = $user->id;
            }

            $inspection->inspection_started_at = now();

            // Simpan sementara sebagai Draft
            // agar ID inspection sudah tersedia
            $inspection->status = 'Draft';

            $inspection->save();
        }


        // =====================================================
        // 7. PROSES CHECKLIST
        // =====================================================
        $isOverallReady = true;


        foreach ($items as $itemId => $itemData) {

            // -------------------------------------------------
            // Ambil master item
            // -------------------------------------------------
            $itemMaster = $itemMasters->get($itemId);


            // -------------------------------------------------
            // Pastikan item memang aktif
            // -------------------------------------------------
            if (!$itemMaster || !$itemMaster->is_active) {
                continue;
            }


            // -------------------------------------------------
            // Buat / ambil detail
            // -------------------------------------------------
            $detail = InspectionDetail::firstOrNew([
                'inspection_id' => $inspection->id,
                'inspection_item_id' => $itemId,
            ]);


            $value = $itemData['value'] ?? null;

            $isPassed = true;


            // =================================================
            // 8. TENTUKAN HASIL BERDASARKAN INPUT TYPE
            // =================================================
            if (in_array($itemMaster->input_type, ['OK_NG', 'YES_NO'])) {

                $detail->result_status = $value;

                $isPassed = (
                    $value === 'OK' ||
                    $value === 'YES'
                );

            } elseif (
                in_array(
                    $itemMaster->input_type,
                    ['NUMBER', 'DECIMAL', 'PERCENTAGE']
                )
            ) {

                $detail->result_value = $value;

            } else {

                $detail->result_text = $value;
            }


            // =================================================
            // 9. CATATAN ITEM
            // =================================================
            $detail->note = $itemData['note'] ?? null;

            $detail->is_passed = $isPassed;

            $detail->save();


            // =================================================
            // 10. CEK ITEM CRITICAL
            // =================================================
            if (
                !$isPassed &&
                $itemMaster->is_critical
            ) {

                $isOverallReady = false;
            }


            // =================================================
            // 11. UPLOAD FOTO
            // =================================================
            $file = $request->file("photos.{$itemId}");

            if ($request->hasFile("photos.{$itemId}") && $file && $file->isValid()) {
                $path = $file->store(
                    "inspection_photos/{$today}",
                    'public'
                );


                InspectionPhoto::create([
                    'inspection_detail_id' => $detail->id,

                    'uploaded_by' => $user->id,

                    'photo_name' => $file->getClientOriginalName(),

                    'photo_path' => $path,

                    'mime_type' => $file->getClientMimeType(),

                    'photo_size' => $file->getSize(),

                    'caption' => "Bukti item: {$itemMaster->item_name}",

                    'taken_at' => now(),
                ]);
            }
        }


        // =====================================================
        // 12. UPDATE HEADER INSPEKSI
        // =====================================================
        $inspection->overall_result =
            $isOverallReady
                ? 'Ready'
                : 'Not Ready';


        $inspection->status = 'Submitted';

        $inspection->remarks = $request->remarks;

        $inspection->submitted_by = $user->id;

        $inspection->submitted_at = now();

        $inspection->inspection_completed_at = now();

        $inspection->save();


        // =====================================================
        // 13. UPDATE LAST INSPECTION FORKLIFT
        // =====================================================
        $forklift->update([
            'last_inspection_at' => now(),
        ]);


        // =====================================================
        // 14. ACTIVITY LOG
        // =====================================================
        ActivityLog::create([
            'user_id' => $user->id,
            'module' => 'Daily Inspection',
            'action' => 'Submit',
            'description' =>
                "{$user->name} melakukan submit inspeksi harian "
                . "{$inspection->inspection_number} "
                . "untuk operator {$operator?->name} "
                . "(Status: {$inspection->overall_result}).",
            'ip_address' => $request->ip(),
        ]);


        // =====================================================
        // 15. COMMIT TRANSACTION
        // =====================================================
        DB::commit();


        // =====================================================
        // 16. REDIRECT KE DETAIL INSPEKSI
        // =====================================================
        return redirect()
            ->route('inspections.show', $inspection->id)
            ->with(
                'success',
                'Inspeksi harian berhasil di-submit dan menunggu review Supervisor.'
            );


    } catch (\Exception $e) {

        // =====================================================
        // ROLLBACK JIKA TERJADI ERROR
        // =====================================================
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        report($e);


        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'Gagal menyimpan data inspeksi. Silakan periksa kembali data checklist dan foto yang diunggah.'
            );
    }
}

    /**
     * Menampilkan Detail Hasil Inspeksi (Beserta Detail Item & Foto).
     */
    public function show(Inspection $inspection)
    {
        $inspection->load([
            'forklift.location',
            'operator',
            'submittedBy',
            'approval.approvedBy',
            'details.inspectionItem.category',
            'details.photos',
        ]);

        return view('inspections.show', compact('inspection'));
    }
            /**
         * Mengubah status inspeksi oleh Administrator.
         *
         * Status yang diperbolehkan:
         * - Draft
         * - Submitted
         * - Rejected
         * - Approved
         */
        public function updateStatus(Request $request, Inspection $inspection)
        {
            // Pastikan hanya Administrator yang dapat melakukan perubahan status
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Anda tidak memiliki hak untuk mengubah status inspeksi.');
            }

            // Validasi status baru
            $validated = $request->validate([
                'status' => [
                    'required',
                    'string',
                    'in:Draft,Submitted,Rejected,Approved',
                ],
            ]);

            $oldStatus = $inspection->status;
            $newStatus = $validated['status'];

            if ($inspection->isFinalStatus()) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        "Inspeksi dengan status {$oldStatus} tidak dapat diubah lagi."
                    );
            }

            // Jika status sama, tidak perlu melakukan perubahan
            if ($oldStatus === $newStatus) {
                return redirect()
                    ->back()
                    ->with('warning', 'Status inspeksi tidak mengalami perubahan.');
            }

            // Update status
            $inspection->status = $newStatus;

            // Jika status dikembalikan menjadi Draft,
            // kosongkan informasi approval/submission bila diperlukan.
            if ($newStatus === 'Draft') {

                $inspection->submitted_at = null;
                $inspection->submitted_by = null;
            }

            // Jika status menjadi Submitted dan field tersedia,
            // catat waktu submit.
            if ($newStatus === 'Submitted') {

                if (!$inspection->submitted_at) {
                    $inspection->submitted_at = now();
                }

                if (!$inspection->submitted_by) {
                    $inspection->submitted_by = Auth::id();
                }
            }

            $inspection->save();


            // Catat perubahan ke Activity Log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'module' => 'Inspection',
                'action' => 'Update',
                'description' =>
                    "Administrator mengubah status inspeksi {$inspection->inspection_number} "
                    . "dari {$oldStatus} menjadi {$newStatus}.",
                'ip_address' => $request->ip(),
            ]);


            return redirect()
                ->back()
                ->with(
                    'success',
                    "Status inspeksi berhasil diubah dari {$oldStatus} menjadi {$newStatus}."
                );
        }
}