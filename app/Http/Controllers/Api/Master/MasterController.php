<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\Master\MasterResource;
use App\Models\Forklift;
use App\Models\InspectionCategory;
use App\Models\InspectionItem;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class MasterController extends Controller
{
    public function users(): JsonResponse
    {
        return response()->json(['data' => User::query()->latest()->get()->map(fn ($item) => new MasterResource($item))]);
    }

    public function roles(): JsonResponse
    {
        return response()->json(['data' => Role::query()->latest()->get()->map(fn ($item) => new MasterResource($item))]);
    }

    public function locations(): JsonResponse
    {
        return response()->json(['data' => Location::query()->latest()->get()->map(fn ($item) => new MasterResource($item))]);
    }

    public function forklifts(): JsonResponse
    {
        return response()->json(['data' => Forklift::query()->latest()->get()->map(fn ($item) => new MasterResource($item))]);
    }

    public function inspectionCategories(): JsonResponse
    {
        return response()->json(['data' => InspectionCategory::query()->latest()->get()->map(fn ($item) => new MasterResource($item))]);
    }

    public function inspectionItems(): JsonResponse
    {
        return response()->json(['data' => InspectionItem::query()->latest()->get()->map(fn ($item) => new MasterResource($item))]);
    }
}
