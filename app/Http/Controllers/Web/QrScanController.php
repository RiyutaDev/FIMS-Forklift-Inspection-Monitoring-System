<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Forklift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrScanController extends Controller
{
    /**
     * Proses ketika QR Code forklift dipindai.
     */
    public function scan(Request $request, string $qr_token)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Cari forklift berdasarkan QR token
        |--------------------------------------------------------------------------
        */
        $forklift = Forklift::query()
            ->where('qr_token', $qr_token)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | 2. QR tidak ditemukan
        |--------------------------------------------------------------------------
        */
        if (!$forklift) {
            return redirect()
                ->route('login')
                ->with('error', 'QR Code forklift tidak ditemukan atau tidak valid.');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Tolak forklift yang tidak aktif
        |--------------------------------------------------------------------------
        */
        if (!$forklift->is_active) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    "Forklift {$forklift->forklift_code} sedang nonaktif dan tidak dapat digunakan untuk inspeksi."
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Simpan forklift hasil scan ke session
        |--------------------------------------------------------------------------
        |
        | Session ini akan dibaca oleh DashboardController@driver.
        |
        */
        session([
            'pending_qr_token'    => $forklift->qr_token,
            'pending_forklift_id'  => $forklift->id,
            'pending_forklift_code' => $forklift->forklift_code,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. Jika belum login, arahkan ke halaman login
        |--------------------------------------------------------------------------
        */
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with(
                    'info',
                    "QR forklift {$forklift->forklift_code} berhasil dibaca. Silakan login untuk melanjutkan."
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Jika sudah login, arahkan ke dashboard
        |--------------------------------------------------------------------------
        */
        $user = Auth::user();

        if (method_exists($user, 'isDriver') && $user->isDriver()) {
            return redirect()->route('dashboard.driver');
        }

        if (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return redirect()->route('dashboard.supervisor');
        }

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        }

        return redirect()
            ->route('dashboard.index')
            ->with('info', 'QR Code berhasil dibaca.');
    }
}
