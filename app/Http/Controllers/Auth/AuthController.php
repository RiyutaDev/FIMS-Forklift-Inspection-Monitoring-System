<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        // Jika user sudah login, alihkan ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Memproses login pengguna menggunakan employee_number & password.
     */
    public function login(Request $request)
    {
        // 1. Validasi input form (menggunakan employee_number)
        $credentials = $request->validate([
            'employee_number' => ['required', 'string'],
            'password'        => ['required', 'string'],
        ], [
            'employee_number.required' => 'Nomor Pegawai (NIK) wajib diisi.',
            'password.required'        => 'Password wajib diisi.',
        ]);

        // 2. Coba autentikasi via Auth::attempt
        $attemptSuccess = Auth::attempt([
            'employee_number' => $request->employee_number,
            'password'        => $request->password,
        ], $request->boolean('remember'));

        if ($attemptSuccess) {
            $user = Auth::user();

            // 3. Aturan Bisnis: Cek apakah akun user aktif (is_active)
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'employee_number' => 'Akun Anda sedang tidak aktif. Silakan hubungi Administrator.',
                ])->onlyInput('employee_number');
            }

            // 4. Update timestamp last_login_at
            $user->update([
                'last_login_at' => now(),
            ]);

            // 5. Regenerasi session untuk perlindungan Session Fixation
            $request->session()->regenerate();

            // 6. Catat Activity Log Login (Sesuai BR-024)
            ActivityLog::create([
                'user_id'     => $user->id,
                'module'      => 'Authentication',
                'action'      => 'Login',
                'description' => "User {$user->name} ({$user->employee_number}) berhasil login.",
                'ip_address'  => $request->ip(),
            ]);

            // 7. Redirect ke dashboard sesuai Role
            return $this->redirectBasedOnRole($user);
        }

        // Jika kredensial salah
        return back()->withErrors([
            'employee_number' => 'Nomor Pegawai atau password yang Anda masukkan salah.',
        ])->onlyInput('employee_number');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Catat Activity Log Logout (Sesuai BR-024)
            ActivityLog::create([
                'user_id'     => $user->id,
                'module'      => 'Authentication',
                'action'      => 'Logout',
                'description' => "User {$user->name} ({$user->employee_number}) telah logout.",
                'ip_address'  => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Helper Method untuk redirect sesuai role pengguna.
     */
                
        protected function redirectBasedOnRole($user)
        {
            $roleName = $user->role?->role_name;

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */
            if (in_array($roleName, ['Admin', 'Administrator'])) {

                return redirect()
                    ->route('dashboard.admin')
                    ->with('welcome', [
                        'title' => 'Selamat Datang, ' . $user->name . '!',
                        'message' => 'Anda berhasil login sebagai Administrator.',
                        'role' => 'Administrator',
                        'icon' => 'fas fa-user-shield',
                        'type' => 'success',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | SUPERVISOR
            |--------------------------------------------------------------------------
            */
            if ($roleName === 'Supervisor') {

                return redirect()
                    ->route('dashboard.supervisor')
                    ->with('welcome', [
                        'title' => 'Selamat Datang, ' . $user->name . '!',
                        'message' => 'Anda berhasil login sebagai Supervisor.',
                        'role' => 'Supervisor',
                        'icon' => 'fas fa-user-check',
                        'type' => 'success',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | OPERATOR / DRIVER
            |--------------------------------------------------------------------------
            */
            if (in_array($roleName, ['Operator', 'Driver'])) {

                return redirect()
                    ->route('dashboard.driver')
                    ->with('welcome', [
                        'title' => 'Selamat Datang, ' . $user->name . '!',
                        'message' => 'Anda berhasil login sebagai Operator.',
                        'role' => 'Operator',
                        'icon' => 'fas fa-id-card',
                        'type' => 'success',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | ROLE TIDAK VALID
            |--------------------------------------------------------------------------
            */

            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'employee_number' =>
                        'Role pengguna tidak valid atau belum terdaftar.',
                ]);
        }
}