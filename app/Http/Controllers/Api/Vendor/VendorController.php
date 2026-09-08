<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\VendorResource;
use App\Services\Vendor\VendorService;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    public function __construct(protected VendorService $vendorService)
    {
    }

    public function ping(): JsonResponse
    {
        return response()->json([
            'data' => new VendorResource($this->vendorService->ping()),
        ]);
    }
}
