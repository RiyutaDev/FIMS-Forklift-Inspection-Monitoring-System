<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\InspectionController;
use App\Http\Controllers\Web\ApprovalController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\VendorController;

use App\Http\Controllers\Web\Master\UserController;
use App\Http\Controllers\Web\Master\ForkliftController;
use App\Http\Controllers\Web\Master\InspectionItemController;


/*
|--------------------------------------------------------------------------
| Authentication (Guest Area)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::controller(AuthController::class)->group(function () {

        Route::get('/login', 'showLoginForm')
            ->name('login');

        Route::post('/login', 'login')
            ->name('login.process');

    });

});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated Area
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | ROOT
    |--------------------------------------------------------------------------
    |
    | Ketika user membuka "/",
    | arahkan ke gateway dashboard.
    |
    */

    Route::redirect('/', '/dashboard');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GATEWAY
    |--------------------------------------------------------------------------
    |
    | /dashboard akan menentukan dashboard berdasarkan role.
    |
    */

    Route::get('/dashboard', function () {

        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->isSupervisor()) {
            return redirect()->route('dashboard.supervisor');
        }

        if ($user->isDriver()) {
            return redirect()->route('dashboard.driver');
        }

        abort(403, 'Role pengguna tidak dikenali.');

    })->name('dashboard.index');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin', [
        DashboardController::class,
        'admin'
    ])
        ->middleware('role:Admin')
        ->name('dashboard.admin');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD SUPERVISOR
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/supervisor', [
        DashboardController::class,
        'supervisor'
    ])
        ->middleware('role:Supervisor')
        ->name('dashboard.supervisor');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD OPERATOR / DRIVER
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/driver', [
        DashboardController::class,
        'driver'
    ])
        ->middleware('role:Operator,Driver')
        ->name('dashboard.driver');



    /*
    |--------------------------------------------------------------------------
    | MASTER DATA
    | Akses: ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Admin'])
        ->prefix('master')
        ->name('master.')
        ->group(function () {


            /*
            |--------------------------------------------------------------------------
            | Users Management
            |--------------------------------------------------------------------------
            */

            Route::controller(UserController::class)
                ->prefix('users')
                ->name('users.')
                ->group(function () {

                    Route::get('/', 'index')
                        ->name('index');

                    Route::get('/create', 'create')
                        ->name('create');

                    Route::post('/', 'store')
                        ->name('store');

                    Route::get('/{user}', 'show')
                        ->name('show');

                    Route::get('/{user}/edit', 'edit')
                        ->name('edit');

                    Route::put('/{user}', 'update')
                        ->name('update');

                    Route::delete('/{user}', 'destroy')
                        ->name('destroy');

                });


            /*
            |--------------------------------------------------------------------------
            | Forklifts Management
            |--------------------------------------------------------------------------
            */

            Route::controller(ForkliftController::class)
                ->prefix('forklifts')
                ->name('forklifts.')
                ->group(function () {

                    Route::get('/', 'index')
                        ->name('index');

                    Route::get('/create', 'create')
                        ->name('create');

                    Route::post('/', 'store')
                        ->name('store');

                    Route::get('/{forklift}', 'show')
                        ->name('show');

                    Route::get('/{forklift}/edit', 'edit')
                        ->name('edit');

                    Route::put('/{forklift}', 'update')
                        ->name('update');

                    Route::delete('/{forklift}', 'destroy')
                        ->name('destroy');

                    // QR Code
                    Route::post(
                        '/{forklift}/regenerate-qr',
                        'regenerateQr'
                    )->name('regenerate_qr');

                    Route::get(
                        '/{forklift}/print-qr',
                        'printQr'
                    )->name('print_qr');

                });


            /*
            |--------------------------------------------------------------------------
            | Inspection Items Management
            |--------------------------------------------------------------------------
            */

            Route::controller(InspectionItemController::class)
                ->prefix('inspection-items')
                ->name('inspection-items.')
                ->group(function () {

                    Route::get('/', 'index')
                        ->name('index');

                    Route::get('/create', 'create')
                        ->name('create');

                    Route::post('/', 'store')
                        ->name('store');

                    Route::get('/{inspectionItem}', 'show')
                        ->name('show');

                    Route::get('/{inspectionItem}/edit', 'edit')
                        ->name('edit');

                    Route::put('/{inspectionItem}', 'update')
                        ->name('update');

                    Route::delete('/{inspectionItem}', 'destroy')
                        ->name('destroy');

                });

        });



    /*
    |--------------------------------------------------------------------------
    | INSPECTION MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('inspections')
        ->name('inspections.')
        ->group(function () {


            /*
            |--------------------------------------------------------------------------
            | VIEW INSPECTION
            | Akses: SEMUA ROLE
            |--------------------------------------------------------------------------
            */

            Route::controller(InspectionController::class)
                ->group(function () {

                    Route::get('/', 'index')
                        ->name('index');

                    Route::get('/show/{inspection}', 'show')
                        ->name('show');

                });


            /*
            |--------------------------------------------------------------------------
            | ADMIN - CHANGE INSPECTION STATUS
            |--------------------------------------------------------------------------
            |
            | Admin dapat mengubah:
            | Draft
            | Submitted
            | Rejected
            | Approved
            |
            */

            Route::middleware(['role:Admin'])
                ->controller(InspectionController::class)
                ->group(function () {

                    Route::patch(
                        '/{inspection}/status',
                        'updateStatus'
                    )->name('update-status');

                });


            /*
            |--------------------------------------------------------------------------
            | CREATE INSPECTION
            | Akses: OPERATOR / DRIVER
            |--------------------------------------------------------------------------
            */

            Route::middleware(['role:Driver,Operator'])
                ->controller(InspectionController::class)
                ->group(function () {

                    Route::get('/create', 'create')
                        ->name('create');

                    Route::get('/checklist', 'checklist')
                        ->name('checklist');

                    Route::post('/', 'store')
                        ->name('store');

                });

        });



    /*
    |--------------------------------------------------------------------------
    | APPROVAL MODULE
    | Akses: SUPERVISOR & ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Supervisor,Admin'])
        ->controller(ApprovalController::class)
        ->prefix('approvals')
        ->name('approvals.')
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/{inspection}', 'show')
                ->name('show');

            Route::post(
                '/{inspection}/approve',
                'approve'
            )->name('approve');

            Route::post(
                '/{inspection}/reject',
                'reject'
            )->name('reject');

        });



    /*
    |--------------------------------------------------------------------------
    | REPORT MODULE
    | Akses: SUPERVISOR & ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Supervisor,Admin'])
        ->controller(ReportController::class)
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get(
                '/inspection/{inspection}/pdf',
                'downloadSinglePdf'
            )->name('download_single_pdf');

            Route::get(
                '/summary/pdf',
                'exportSummaryPdf'
            )->name('export_summary_pdf');

        });



    /*
    |--------------------------------------------------------------------------
    | VENDOR MODULE
    | Akses: ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Admin'])
        ->controller(VendorController::class)
        ->prefix('vendors')
        ->name('vendors.')
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

        });

});