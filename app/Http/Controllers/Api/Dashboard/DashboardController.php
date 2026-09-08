<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardSummaryResource;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => new DashboardSummaryResource($this->dashboardService->summary()),
        ]);
    }
}
