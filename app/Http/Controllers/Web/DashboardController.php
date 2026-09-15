<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Administrator
     */
    public function admin()
    {
        $today = Carbon::today()->toDateString();

        // =========================================================
        // MASTER DATA
        // =========================================================

        $totalForklifts = Forklift::count();

        $activeForklifts = Forklift::active()->count();

        $totalUsers = User::count();

        $activeUsers = User::active()->count();

        // =========================================================
        // INSPECTION STATISTICS
        // =========================================================

        $totalInspectionsToday = Inspection::whereDate(
            'inspection_date',
            $today
        )->count();

        $pendingApprovalsAll = Inspection::where(
            'status',
            'Submitted'
        )->count();

        // =========================================================
        // LATEST INSPECTIONS
        // =========================================================

        $latestInspections = Inspection::with([
                'forklift',
                'operator',
            ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalForklifts',
            'activeForklifts',
            'totalUsers',
            'activeUsers',
            'totalInspectionsToday',
            'pendingApprovalsAll',
            'latestInspections'
        ));
    }

    /**
     * Dashboard Supervisor
     */
    public function supervisor()
    {
        $user = Auth::user();

        $today = Carbon::today()->toDateString();

        // =========================================================
        // FILTER LOKASI SUPERVISOR
        // =========================================================

        $locationId = $user->location_id;

        $inspectionQuery = Inspection::query();

        if ($locationId) {
            $inspectionQuery->whereHas(
                'forklift',
                function ($q) use ($locationId) {
                    $q->where('location_id', $locationId);
                }
            );
        }

        // =========================================================
        // STATISTIK SUPERVISOR
        // =========================================================

        $pendingApprovals = (clone $inspectionQuery)
            ->where('status', 'Submitted')
            ->count();

        $approvedToday = (clone $inspectionQuery)
            ->whereDate('inspection_date', $today)
            ->where('status', 'Approved')
            ->count();

        $rejectedToday = (clone $inspectionQuery)
            ->whereDate('inspection_date', $today)
            ->where('status', 'Rejected')
            ->count();

        $notReadyForklifts = (clone $inspectionQuery)
            ->whereDate('inspection_date', $today)
            ->where('overall_result', 'Not Ready')
            ->count();

        // =========================================================
        // INSPECTION MENUNGGU APPROVAL
        // =========================================================

        $recentSubmissions = (clone $inspectionQuery)
            ->with([
                'forklift',
                'operator',
            ])
            ->where('status', 'Submitted')
            ->orderBy('submitted_at', 'desc')
            ->limit(5)
            ->get();

        return view(
            'dashboard.supervisor',
            compact(
                'pendingApprovals',
                'approvedToday',
                'rejectedToday',
                'notReadyForklifts',
                'recentSubmissions'
            )
        );
    }

    /**
     * Dashboard Operator / Driver
     */
    public function driver()
    {
        $user = Auth::user();

        $today = Carbon::today()->toDateString();

        // =========================================================
        // DATA USER LOGIN
        // =========================================================
        // Data user dikirim ke view agar nantinya dapat menampilkan:
        // nama, email, NIK, foto, lokasi, dan role jika tersedia.

        // =========================================================
        // INSPEKSI OPERATOR HARI INI
        // =========================================================

        $todayInspections = Inspection::with([
                'forklift',
            ])
            ->where('operator_id', $user->id)
            ->whereDate('inspection_date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // =========================================================
        // TOTAL INSPEKSI OPERATOR
        // =========================================================

        $totalMyInspections = Inspection::where(
            'operator_id',
            $user->id
        )->count();

        // =========================================================
        // INSPEKSI MENUNGGU REVIEW SUPERVISOR
        // =========================================================

        $pendingReviewCount = Inspection::where(
                'operator_id',
                $user->id
            )
            ->where('status', 'Submitted')
            ->count();

        // =========================================================
        // INSPEKSI DISETUJUI
        // =========================================================

        $approvedCount = Inspection::where(
                'operator_id',
                $user->id
            )
            ->where('status', 'Approved')
            ->count();

        // =========================================================
        // INSPEKSI DITOLAK
        // =========================================================

        $rejectedCount = Inspection::where(
                'operator_id',
                $user->id
            )
            ->where('status', 'Rejected')
            ->count();

        // =========================================================
        // INSPEKSI TERBARU OPERATOR
        // =========================================================

        $latestInspections = Inspection::with([
                'forklift',
            ])
            ->where('operator_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // =========================================================
        // INSPEKSI HARI INI BERDASARKAN STATUS
        // =========================================================

        $submittedToday = Inspection::where(
                'operator_id',
                $user->id
            )
            ->whereDate('inspection_date', $today)
            ->where('status', 'Submitted')
            ->count();

        $approvedToday = Inspection::where(
                'operator_id',
                $user->id
            )
            ->whereDate('inspection_date', $today)
            ->where('status', 'Approved')
            ->count();

        $rejectedToday = Inspection::where(
                'operator_id',
                $user->id
            )
            ->whereDate('inspection_date', $today)
            ->where('status', 'Rejected')
            ->count();

        // =========================================================
        // FORKLIFT HASIL SCAN QR
        // =========================================================
        /*
        | QR Token seharusnya disimpan ke session setelah QR forklift
        | berhasil dibaca dan sebelum Operator masuk ke dashboard.
        |
        | Contoh session:
        | session(['pending_qr_token' => $forklift->qr_token]);
        */

        $pendingQrToken = session('pending_qr_token');

        $selectedForklift = null;

        if ($pendingQrToken) {
            $forkliftQuery = Forklift::query()
                ->active()
                ->where('qr_token', $pendingQrToken);

            /*
            |--------------------------------------------------------------------------
            | Pembatasan lokasi Operator
            |--------------------------------------------------------------------------
            | Jika Operator mempunyai location_id, forklift harus berada
            | pada lokasi yang sama.
            |--------------------------------------------------------------------------
            */

            if (!empty($user->location_id)) {
                $forkliftQuery->where(
                    'location_id',
                    $user->location_id
                );
            }

            $selectedForklift = $forkliftQuery->first();
        }

        // =========================================================
        // STATUS FORKLIFT HASIL QR
        // =========================================================

        $hasSelectedForklift = $selectedForklift !== null;

        // =========================================================
        // DATA UNTUK VIEW DASHBOARD OPERATOR
        // =========================================================

        return view(
            'dashboard.driver',
            compact(
                'user',
                'today',
                'todayInspections',
                'totalMyInspections',
                'pendingReviewCount',
                'approvedCount',
                'rejectedCount',
                'latestInspections',
                'submittedToday',
                'approvedToday',
                'rejectedToday',
                'selectedForklift',
                'hasSelectedForklift'
            )
        );
    }
}