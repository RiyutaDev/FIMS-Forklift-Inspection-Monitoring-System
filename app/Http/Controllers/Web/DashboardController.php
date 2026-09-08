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
                'operator'
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

                    $q->where(
                        'location_id',
                        $locationId
                    );
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
            ->whereDate(
                'inspection_date',
                $today
            )
            ->where('status', 'Approved')
            ->count();


        $rejectedToday = (clone $inspectionQuery)
            ->whereDate(
                'inspection_date',
                $today
            )
            ->where('status', 'Rejected')
            ->count();


        $notReadyForklifts = (clone $inspectionQuery)
            ->whereDate(
                'inspection_date',
                $today
            )
            ->where(
                'overall_result',
                'Not Ready'
            )
            ->count();


        // =========================================================
        // INSPECTION YANG MENUNGGU APPROVAL
        // =========================================================

        $recentSubmissions = (clone $inspectionQuery)
            ->with([
                'forklift',
                'operator'
            ])
            ->where(
                'status',
                'Submitted'
            )
            ->orderBy(
                'submitted_at',
                'desc'
            )
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
        // INSPEKSI HARI INI
        // =========================================================

        $todayInspections = Inspection::with([
                'forklift'
            ])
            ->where(
                'operator_id',
                $user->id
            )
            ->whereDate(
                'inspection_date',
                $today
            )
            ->orderBy(
                'created_at',
                'desc'
            )
            ->get();


        // =========================================================
        // TOTAL INSPEKSI OPERATOR
        // =========================================================

        $totalMyInspections = Inspection::where(
            'operator_id',
            $user->id
        )->count();


        return view(
            'dashboard.driver',
            compact(
                'todayInspections',
                'totalMyInspections',
                'today'
            )
        );
    }
}