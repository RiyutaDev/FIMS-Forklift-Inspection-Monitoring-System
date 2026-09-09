<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna (User List) dengan pencarian & filter.
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'location']);

        // Filter berdasarkan kata kunci (Pencarian NIK, Nama, Username, Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter berdasarkan Lokasi Kerja
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter berdasarkan Status Akun (Aktif / Non-Aktif)
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        $roles = Role::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('master.users.index', compact('users', 'roles', 'locations'));
    }

    /**
     * Menampilkan formulir tambah pengguna baru.
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('master.users.create', compact('roles', 'locations'));
    }

    /**
     * Memproses penyimpanan data pengguna baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Data Pengguna
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'max:20', 'unique:users,employee_number'],
            'name'            => ['required', 'string', 'max:100'],
            'username'        => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'           => ['required', 'email', 'max:100', 'unique:users,email'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'role_id'         => ['required', 'exists:roles,id'],
            'location_id'     => ['nullable', 'exists:locations,id'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
            'photo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
            'is_active'       => ['boolean'],
        ], [
            'employee_number.unique' => 'Nomor Pegawai (NIK) sudah terdaftar dalam sistem.',
            'username.unique'        => 'Username sudah digunakan oleh akun lain.',
            'email.unique'           => 'Alamat Email sudah terdaftar dalam sistem.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Upload Foto Profil jika diunggah
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('user_photos', 'public');
        }

        // 3. Hash Password
        $validated['password'] = Hash::make($request->password);
        $validated['is_active'] = $request->boolean('is_active');

        // 4. Simpan User Baru
        $user = User::create($validated);

        // 5. Catat Activity Log (BR-024)
        $admin = Auth::user();
        ActivityLog::create([
            'user_id'     => $admin->id,
            'module'      => 'Master User',
            'action'      => 'Create',
            'description' => "Admin {$admin->name} membuat akun pengguna baru: {$user->name} ({$user->employee_number}).",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('master.users.index')
            ->with('success', "Pengguna baru {$user->name} berhasil ditambahkan.");
    }

    /**
     * Menampilkan detail informasi pengguna.
     */
    public function show(User $user)
    {
        $user->load(['role', 'location', 'inspections' => function ($q) {
            $q->latest()->limit(5);
        }, 'activityLogs' => function ($q) {
            $q->latest()->limit(10);
        }]);

        return view('master.users.show', compact('user'));
    }

    /**
     * Menampilkan formulir edit pengguna.
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('master.users.edit', compact('user', 'roles', 'locations'));
    }

    /**
     * Memproses pembaruan data pengguna.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'max:20', Rule::unique('users', 'employee_number')->ignore($user->id)],
            'name'            => ['required', 'string', 'max:100'],
            'username'        => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email'           => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'           => ['nullable', 'string', 'max:20'],
            'role_id'         => ['required', 'exists:roles,id'],
            'location_id'     => ['nullable', 'exists:locations,id'],
            'password'        => ['nullable', 'string', 'min:8', 'confirmed'], // Opsional saat edit
            'photo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'is_active'       => ['boolean'],
        ]);

        // 2. Upload Foto Baru & Hapus Foto Lama (jika ada)
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('user_photos', 'public');
        } else {
            unset($validated['photo']);
        }

        // 3. Update Password Hanya Jika Diisi
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // Status akun
        $validated['is_active'] = $request->boolean('is_active');

        // 4. Update Data User
        $user->update($validated);

        // 5. Catat Activity Log
        $admin = Auth::user();

        ActivityLog::create([
            'user_id'     => $admin->id,
            'module'      => 'Master User',
            'action'      => 'Update',
            'description' => "Admin {$admin->name} memperbarui data pengguna: {$user->name} ({$user->employee_number}).",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('master.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Menghapus atau menonaktifkan pengguna.
     */
    public function destroy(Request $request, User $user)
{
    // 1. Mencegah Admin menghapus akunnya sendiri
    if ($user->id === Auth::id()) {
        return redirect()->back()->with(
            'error',
            'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.'
        );
    }

    // 2. Simpan informasi sebelum user dihapus permanen
    $admin = Auth::user();
    $userName = $user->name;
    $employeeNumber = $user->employee_number;

    // 3. Hapus user secara PERMANEN
    $user->forceDelete();

    // 4. Catat Activity Log
    ActivityLog::create([
        'user_id'     => $admin->id,
        'module'      => 'Master User',
        'action'      => 'Delete',
        'description' => "Admin {$admin->name} menghapus pengguna: {$userName} ({$employeeNumber}).",
        'ip_address'  => $request->ip(),
        'user_agent'  => $request->userAgent(),
    ]);

    // 5. Kembali ke daftar user
    return redirect()->route('master.users.index')
        ->with(
            'success',
            "Pengguna {$userName} berhasil dihapus secara permanen."
        );
}
}