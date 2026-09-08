<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inspection\SaveDraftInspectionRequest;
use App\Http\Requests\Inspection\StoreInspectionRequest;
use App\Http\Requests\Inspection\SubmitInspectionRequest;
use App\Http\Resources\Inspection\InspectionResource;
use App\Http\Resources\Inspection\InspectionSummaryResource;
use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\User;
use App\Services\Inspection\InspectionDetailService;
use App\Services\Inspection\InspectionService;
use App\Services\Inspection\InspectionWorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    protected InspectionService $inspectionService;
    protected InspectionDetailService $detailService;
    protected InspectionWorkflowService $workflowService;

    public function __construct(
        InspectionService $inspectionService,
        InspectionDetailService $detailService,
        InspectionWorkflowService $workflowService
    ) {
        $this->inspectionService = $inspectionService;
        $this->detailService = $detailService;
        $this->workflowService = $workflowService;
    }

    public function index(): JsonResponse
    {
        $inspections = Inspection::query()->with(['forklift', 'operator', 'details'])->latest()->get();

        return response()->json([
            'data' => InspectionResource::collection($inspections),
        ]);
    }

    public function show(Inspection $inspection): JsonResponse
    {
        $inspection->load(['forklift', 'operator', 'details.inspectionItem']);

        return response()->json([
            'data' => new InspectionResource($inspection),
        ]);
    }

    public function store(StoreInspectionRequest $request): JsonResponse
    {
        $forklift = Forklift::findOrFail($request->forklift_id);
        $operator = User::findOrFail($request->operator_id);

        $inspection = $this->inspectionService->createInspection($forklift, $operator, $request->inspection_shift, $request->remarks);

        return response()->json([
            'message' => 'Inspection created successfully',
            'data' => new InspectionResource($inspection),
        ], 201);
    }

    public function saveDraft(SaveDraftInspectionRequest $request, Inspection $inspection): JsonResponse
    {
        $updated = $this->detailService->saveDraft($inspection, $request->input('details', []));

        return response()->json([
            'message' => 'Draft saved successfully',
            'data' => new InspectionResource($inspection->load(['forklift', 'operator', 'details.inspectionItem'])),
        ]);
    }

    public function submit(SubmitInspectionRequest $request, Inspection $inspection): JsonResponse
    {
        $submitted = $this->workflowService->submit(
            $inspection,
            $request->input('details', []),
            Auth::user()
        );

        return response()->json([
            'message' => 'Inspection submitted successfully',
            'data' => new InspectionResource($submitted->load(['forklift', 'operator', 'details.inspectionItem'])),
        ]);
    }

    public function summary(Inspection $inspection): JsonResponse
    {
        $summary = $this->detailService->calculateSummary($inspection);

        return response()->json([
            'data' => new InspectionSummaryResource($summary),
        ]);
    }
}
