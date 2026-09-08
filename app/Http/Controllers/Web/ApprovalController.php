<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    /**
     * Menampilkan daftar pengajuan inspeksi yang membutuhkan review / riwayat approval.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Query utama inspeksi yang sudah disubmit atau telah diproses approval
        $query = Inspection::with(['forklift.location', 'operator', 'approval.approvedBy'])
            ->whereIn('status', ['Submitted', 'Approved', 'Rejected']);

        // Filter lokasi berdasarkan Working Area Supervisor jika diset
        if ($user->location_id) {
            $query->whereHas('forklift', function ($q) use ($user) {
                $q->where('location_id', $user->location_id);
            });
        }

        // Filter berdasarkan Status Approval (default prioritaskan 'Submitted' / Pending)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Urutkan status 'Submitted' paling atas agar mudah ditinjau
            $query->orderByRaw("FIELD(status, 'Submitted', 'Approved', 'Rejected') ASC");
        }

        // Filter berdasarkan Tanggal Inspeksi
        if ($request->filled('date')) {
            $query->whereDate('inspection_date', $request->date);
        }

        // Filter berdasarkan Hasil Keseluruhan (Ready / Not Ready)
        if ($request->filled('result')) {
            $query->where('overall_result', $request->result);
        }

        $inspections = $query->orderBy('submitted_at', 'desc')->paginate(10);

        return view('approvals.index', compact('inspections'));
    }

    /**
     * Menampilkan detail inspeksi untuk di-review oleh Supervisor.
     */
    public function show(Inspection $inspection)
    {
        // Load seluruh relasi detail checklist, foto bukti, dan riwayat approval
        $inspection->load([
            'forklift.location',
            'operator',
            'submittedBy',
            'approval.approvedBy',
            'details.inspectionItem.category',
            'details.photos',
        ]);

        return view('approvals.show', compact('inspection'));
    }

    /**
     * Memproses persetujuan (Approve) hasil inspeksi harian.
     */
    public function approve(Request $request, Inspection $inspection)
    {
        // Pastikan status inspeksi saat ini adalah 'Submitted'
        if ($inspection->status !== 'Submitted') {
            return redirect()->back()->with('error', 'Inspeksi ini sudah pernah diproses sebelumnya.');
        }

        $request->validate([
            'approval_note' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            // 1. Simpan atau perbarui data Approval
            Approval::updateOrCreate(
                ['inspection_id' => $inspection->id],
                [
                    'approved_by'     => $user->id,
                    'approval_status' => 'Approved',
                    'approval_note'   => $request->approval_note ?? 'Inspeksi telah diverifikasi dan disetujui.',
                    'approved_at'     => now(),
                ]
            );

            // 2. Perbarui status pada header Inspeksi
            $inspection->update([
                'status' => 'Approved',
            ]);

            // 3. Catat Activity Log (BR-024)
            ActivityLog::create([
                'user_id'     => $user->id,
                'module'      => 'Approval',
                'action'      => 'Approve',
                'description' => "Supervisor {$user->name} menyetujui inspeksi {$inspection->inspection_number} (Forklift: {$inspection->forklift->forklift_code}).",
                'ip_address'  => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('approvals.index')
                ->with('success', "Inspeksi {$inspection->inspection_number} berhasil disetujui (Approved).");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal memproses approval: ' . $e->getMessage());
        }
    }

    /**
     * Memproses penolakan (Reject) hasil inspeksi harian.
     */
    public function reject(Request $request, Inspection $inspection)
    {
        // Pastikan status inspeksi saat ini adalah 'Submitted'
        if ($inspection->status !== 'Submitted') {
            return redirect()->back()->with('error', 'Inspeksi ini sudah pernah diproses sebelumnya.');
        }

        // ATURAN BISNIS BR-017 / BR-022: Supervisor WAJIB menyertakan alasan penolakan
        $request->validate([
            'approval_note' => 'required|string|min:5|max:500',
        ], [
            'approval_note.required' => 'Alasan penolakan wajib diisi oleh Supervisor.',
            'approval_note.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            // 1. Simpan atau perbarui data Approval
            Approval::updateOrCreate(
                ['inspection_id' => $inspection->id],
                [
                    'approved_by'     => $user->id,
                    'approval_status' => 'Rejected',
                    'approval_note'   => $request->approval_note,
                    'approved_at'     => now(),
                ]
            );

            // 2. Perbarui status pada header Inspeksi
            $inspection->update([
                'status' => 'Rejected',
            ]);

            // 3. Catat Activity Log (BR-024)
            ActivityLog::create([
                'user_id'     => $user->id,
                'module'      => 'Approval',
                'action'      => 'Reject',
                'description' => "Supervisor {$user->name} menolak inspeksi {$inspection->inspection_number} dengan alasan: {$request->approval_note}.",
                'ip_address'  => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('approvals.index')
                ->with('warning', "Inspeksi {$inspection->inspection_number} telah ditolak (Rejected).");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal memproses penolakan: ' . $e->getMessage());
        }
    }
}