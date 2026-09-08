<?php

use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\Master\MasterController;
use App\Http\Controllers\Api\Report\ReportController;
use App\Http\Controllers\Api\Vendor\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('inspections')->group(function () {
        Route::get('/', [InspectionController::class, 'index']);
        Route::post('/', [InspectionController::class, 'store']);
        Route::get('/{inspection}', [InspectionController::class, 'show']);
        Route::post('/{inspection}/draft', [InspectionController::class, 'saveDraft']);
        Route::post('/{inspection}/submit', [InspectionController::class, 'submit']);
        Route::get('/{inspection}/summary', [InspectionController::class, 'summary']);
    });

    Route::prefix('master')->group(function () {
        Route::get('/users', [MasterController::class, 'users']);
        Route::get('/roles', [MasterController::class, 'roles']);
        Route::get('/locations', [MasterController::class, 'locations']);
        Route::get('/forklifts', [MasterController::class, 'forklifts']);
        Route::get('/inspection-categories', [MasterController::class, 'inspectionCategories']);
        Route::get('/inspection-items', [MasterController::class, 'inspectionItems']);
    });

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/vendor/ping', [VendorController::class, 'ping']);
});
