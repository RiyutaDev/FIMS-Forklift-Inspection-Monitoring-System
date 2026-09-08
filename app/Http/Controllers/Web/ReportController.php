<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\Location;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Menggunakan Package laravel-dompdf

class ReportController extends Controller
{
    /**
     * Menampilkan halaman filter dan rekapitulasi laporan inspeksi.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Default Filter Periode: Bulan Berjalan jika tidak diisi
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = Inspection::with(['forklift.location', 'operator', 'approval.approvedBy'])
            ->whereBetween('inspection_date', [$startDate, $endDate]);

        // Filter Wilayah Kerja (Location) jika akun Supervisor terikat pada lokasi tertentu
        if ($user->location_id) {
            $query->whereHas('forklift', function ($q) use ($user) {
                $q->where('location_id', $user->location_id);
            });
        } elseif ($request->filled('location_id')) {
            $query->whereHas('forklift', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        // Filter berdasarkan Forklift Spesifik
        if ($request->filled('forklift_id')) {
            $query->where('forklift_id', $request->forklift_id);
        }

        // Filter berdasarkan Status Approval (Approved, Rejected, Submitted)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Hasil Akhir (Ready / Not Ready)
        if ($request->filled('overall_result')) {
            $query->where('overall_result', $request->overall_result);
        }

        $inspections = $query->orderBy('inspection_date', 'desc')->paginate(15);

        // Ringkasan Statistik Laporan untuk Dashboard Report
        $summary = [
            'total_inspections' => (clone $query)->count(),
            'total_approved'    => (clone $query)->where('status', 'Approved')->count(),
            'total_rejected'    => (clone $query)->where('status', 'Rejected')->count(),
            'total_not_ready'   => (clone $query)->where('overall_result', 'Not Ready')->count(),
        ];

        $forklifts = Forklift::active()->get();
        $locations = Location::active()->get();

        return view('reports.index', compact(
            'inspections',
            'summary',
            'forklifts',
            'locations',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Cetak/Export PDF untuk 1 Lembar Hasil Inspeksi Harian (Individual Daily Inspection Sheet).
     */
    public function downloadSinglePdf(Inspection $inspection)
    {
        // Load seluruh relasi detail item, foto bukti, operator, dan supervisor approval
        $inspection->load([
            'forklift.location',
            'operator',
            'submittedBy',
            'approval.approvedBy',
            'details.inspectionItem.category',
            'details.photos',
        ]);

        $user = Auth::user();

        // Log Aktivitas Audit Trail (BR-024)
        ActivityLog::create([
            'user_id'     => $user->id,
            'module'      => 'Report',
            'action'      => 'Export Single PDF',
            'description' => "User {$user->name} mengunduh PDF lembar inspeksi {$inspection->inspection_number}.",
            'ip_address'  => request()->ip(),
        ]);

        // Generate PDF dari view template `reports.pdf_single`
        $pdf = Pdf::loadView('reports.pdf_single', compact('inspection'))
            ->setPaper('A4', 'portrait');

        $filename = "INSPECTION_SHEET_{$inspection->inspection_number}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Cetak/Export PDF Rekapitulasi Laporan Inspeksi Periode Tertentu (Summary Report).
     */
    public function exportSummaryPdf(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = Inspection::with(['forklift.location', 'operator', 'approval.approvedBy'])
            ->whereBetween('inspection_date', [$startDate, $endDate]);

        if ($user->location_id) {
            $query->whereHas('forklift', function ($q) use ($user) {
                $q->where('location_id', $user->location_id);
            });
        } elseif ($request->filled('location_id')) {
            $query->whereHas('forklift', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        if ($request->filled('forklift_id')) {
            $query->where('forklift_id', $request->forklift_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inspections = $query->orderBy('inspection_date', 'asc')->get();

        // Simpan log pencetakan ke tabel `reports` untuk arsip histori laporan
        Report::create([
            'generated_by' => $user->id,
            'title'        => "Laporan Rekapitulasi Inspeksi Forklift ({$startDate} s.d {$endDate})",
            'report_type'  => 'custom',
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'file_path'    => null, // Opsional jika PDF hanya di-stream direct download
        ]);

        // Log Aktivitas Audit Trail (BR-024)
        ActivityLog::create([
            'user_id'     => $user->id,
            'module'      => 'Report',
            'action'      => 'Export Summary PDF',
            'description' => "User {$user->name} mengunduh laporan rekapitulasi inspeksi periode {$startDate} s/d {$endDate}.",
            'ip_address'  => $request->ip(),
        ]);

        $pdf = Pdf::loadView('reports.pdf_summary', compact(
            'inspections',
            'startDate',
            'endDate',
            'user'
        ))->setPaper('A4', 'landscape'); // Format landscape untuk tabel rekap lebar

        $filename = "REKAP_INSPEKSI_FIMS_{$startDate}_TO_{$endDate}.pdf";

        return $pdf->download($filename);
    }
}