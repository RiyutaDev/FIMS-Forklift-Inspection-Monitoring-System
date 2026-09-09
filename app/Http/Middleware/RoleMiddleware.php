<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }


        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | CEK AKUN AKTIF
        |--------------------------------------------------------------------------
        */

        if (!$user->is_active) {

            auth()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Akun Anda sedang tidak aktif.');
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL ROLE USER
        |--------------------------------------------------------------------------
        */

        $userRole = $user->role?->role_name;


        /*
        |--------------------------------------------------------------------------
        | ROLE TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$userRole) {

            auth()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Role pengguna tidak ditemukan. Silakan hubungi Administrator.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI ROLE
        |--------------------------------------------------------------------------
        |
        | Admin dan Administrator dianggap sebagai role yang sama.
        | Driver dan Operator juga dianggap sebagai role yang sama.
        |
        */

        $normalizedUserRole = match ($userRole) {

            'Administrator' => 'Admin',

            'Operator' => 'Driver',

            default => $userRole,

        };


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI ROLE YANG DIMINTA ROUTE
        |--------------------------------------------------------------------------
        */

        $normalizedRoles = collect($roles)
            ->map(function ($role) {

                return match ($role) {

                    'Administrator' => 'Admin',

                    'Operator' => 'Driver',

                    default => $role,

                };

            })
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | CEK HAK AKSES
        |--------------------------------------------------------------------------
        */

        if (in_array($normalizedUserRole, $normalizedRoles, true)) {

            return $next($request);

        }


        /*
        |--------------------------------------------------------------------------
        | AKSES DITOLAK
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Anda tidak memiliki hak akses untuk halaman ini.'
        );
    }
}