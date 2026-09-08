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
        |
        | Model User kamu menggunakan kolom "is_active",
        | bukan "status".
        |
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
        |
        | Struktur database:
        |
        | users.role_id
        |       ↓
        | roles.id
        |       ↓
        | roles.role_name
        |
        */

        $userRole = $user->role?->role_name;


        /*
        |--------------------------------------------------------------------------
        | ROLE TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$userRole) {

            abort(
                403,
                'Role pengguna tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CEK HAK AKSES
        |--------------------------------------------------------------------------
        */

        if (in_array($userRole, $roles, true)) {

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