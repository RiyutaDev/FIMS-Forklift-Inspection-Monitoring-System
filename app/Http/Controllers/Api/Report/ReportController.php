<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Http\Resources\Report\ReportResource;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => new ReportResource($this->reportService->build()),
        ]);
    }
}
