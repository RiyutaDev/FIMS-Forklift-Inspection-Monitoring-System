<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Forklift;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ForkliftController extends Controller
{
    /**
     * Menampilkan daftar unit forklift dengan pencarian dan filter.
     */
    public function index(Request $request)
    {
        $query = Forklift::with('location');

        // Filter berdasarkan pencarian (Kode Forklift, No. Aset, Brand, Model, Vendor)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('forklift_code', 'like', "%{$search}%")
                  ->orWhere('asset_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Lokasi Area
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter berdasarkan Tipe Bahan Bakar (Electric / Diesel / LPG)
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        // Filter berdasarkan Status Operasional (Aktif / Non-Aktif)
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $forklifts = $query->orderBy('forklift_code', 'asc')->paginate(10)->withQueryString();
        $locations = Location::where('is_active', true)->get();

        return view('master.forklifts.index', compact('forklifts', 'locations'));
    }

    /**
 * Menampilkan formulir pendaftaran unit forklift baru.
 */
public function create()
{
    $locations = Location::where('is_active', true)->get();

    return view(
        'master.forklifts.create',
        compact('locations')
    );
}


/**
 * Memproses penyimpanan unit forklift baru.
 */
public function store(Request $request)
{
    // =========================================================
    // 1. VALIDASI INPUT
    // =========================================================

    $validated = $request->validate([

        'forklift_code' => [
            'required',
            'string',
            'max:30',
        ],

        'asset_number' => [
            'required',
            'string',
            'max:50',
        ],

        'brand' => [
            'required',
            'string',
            'max:100',
        ],

        'model' => [
            'nullable',
            'string',
            'max:100',
        ],

        'serial_number' => [
            'nullable',
            'string',
            'max:100',
        ],

        'location_id' => [
            'required',
            'exists:locations,id',
        ],

        'capacity' => [
            'required',
            'numeric',
            'min:0',
        ],

        'fuel_type' => [
            'required',
            'in:Electric,Diesel,LPG',
        ],

        'manufacture_year' => [
            'nullable',
            'integer',
            'min:1900',
            'max:' . date('Y'),
        ],

        'commissioning_date' => [
            'nullable',
            'date',
        ],

        'vendor_name' => [
            'nullable',
            'string',
            'max:150',
        ],

        'description' => [
            'nullable',
            'string',
        ],

    ]);


    // =========================================================
    // 2. NORMALISASI KODE
    // =========================================================

    $validated['forklift_code'] = strtoupper(
        trim($validated['forklift_code'])
    );

    $validated['asset_number'] = strtoupper(
        trim($validated['asset_number'])
    );


    // =========================================================
    // 3. CEK KODE FORKLIFT
    //    TERMASUK DATA SOFT DELETE
    // =========================================================

    $existingForklift = Forklift::withTrashed()
        ->where(
            'forklift_code',
            $validated['forklift_code']
        )
        ->first();


    // =========================================================
    // 4. JIKA FORKLIFT PERNAH DIHAPUS
    // =========================================================

    if (
        $existingForklift &&
        $existingForklift->trashed()
    ) {

        /*
        |--------------------------------------------------------------------------
        | Jangan langsung restore.
        |
        | Kembalikan Admin ke form.
        |
        | Data form tetap dipertahankan menggunakan withInput().
        |
        | ID dan kode forklift dikirim ke halaman create agar
        | SweetAlert dapat menawarkan pilihan "Pulihkan".
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->withInput()
            ->with([
                'restore_forklift_id' =>
                    $existingForklift->id,

                'restore_forklift_code' =>
                    $existingForklift->forklift_code,
            ]);
    }


    // =========================================================
    // 5. JIKA FORKLIFT MASIH AKTIF
    // =========================================================

    if ($existingForklift) {

        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'forklift_code' =>
                    'Kode forklift '
                    . $validated['forklift_code']
                    . ' sudah digunakan oleh forklift lain.',
            ]);
    }


    // =========================================================
    // 6. CEK ASSET NUMBER
    // =========================================================

    $existingAsset = Forklift::withTrashed()
        ->where(
            'asset_number',
            $validated['asset_number']
        )
        ->first();


    if ($existingAsset) {

        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'asset_number' =>
                    'Nomor aset '
                    . $validated['asset_number']
                    . ' sudah digunakan.',
            ]);
    }


    // =========================================================
    // 7. STATUS
    // =========================================================

    $validated['is_active'] = $request->boolean(
        'is_active',
        true
    );


    // =========================================================
    // 8. CREATE FORKLIFT BARU
    // =========================================================

    $forklift = Forklift::create($validated);


    // =========================================================
    // 9. ACTIVITY LOG
    // =========================================================

    ActivityLog::create([

        'user_id' =>
            Auth::id(),

        'module' =>
            'Master Forklift',

        'action' =>
            'Create',

        'subject_type' =>
            Forklift::class,

        'subject_id' =>
            $forklift->id,

        'description' =>
            Auth::user()->name
            . ' merilis unit forklift baru: '
            . $forklift->forklift_code
            . ' ('
            . $forklift->brand
            . ' '
            . $forklift->model
            . ').',

        'ip_address' =>
            $request->ip(),

        'user_agent' =>
            $request->userAgent(),

    ]);


    // =========================================================
    // 10. REDIRECT
    // =========================================================

    return redirect()
        ->route('master.forklifts.index')
        ->with(
            'success',
            'Forklift berhasil ditambahkan.'
        );
}

/**
 * Memulihkan kembali unit forklift yang sebelumnya dihapus.
 */
public function restore(Request $request, $id)
{
    // =========================================================
    // 1. CARI DATA SOFT DELETED
    // =========================================================

    $forklift = Forklift::withTrashed()
        ->findOrFail($id);


    // =========================================================
    // Pastikan memang sudah dihapus
    // =========================================================

    if (!$forklift->trashed()) {

        return redirect()
            ->route('master.forklifts.index')
            ->with(
                'error',
                'Forklift tersebut masih aktif.'
            );
    }


    // =========================================================
    // 2. VALIDASI DATA FORM
    //
    // Data dikirim langsung dari create.blade.php
    // setelah Admin menekan "Ya, Pulihkan".
    //
    // Tidak lagi menggunakan:
    // session('restore_forklift_data')
    // =========================================================

    $validated = $request->validate([

        'forklift_code' => [
            'required',
            'string',
            'max:30',
        ],

        'asset_number' => [
            'required',
            'string',
            'max:50',
        ],

        'brand' => [
            'required',
            'string',
            'max:100',
        ],

        'model' => [
            'nullable',
            'string',
            'max:100',
        ],

        'serial_number' => [
            'nullable',
            'string',
            'max:100',
        ],

        'location_id' => [
            'required',
            'exists:locations,id',
        ],

        'capacity' => [
            'required',
            'numeric',
            'min:0',
        ],

        'fuel_type' => [
            'required',
            'in:Electric,Diesel,LPG',
        ],

        'manufacture_year' => [
            'nullable',
            'integer',
            'min:1900',
            'max:' . date('Y'),
        ],

        'commissioning_date' => [
            'nullable',
            'date',
        ],

        'vendor_name' => [
            'nullable',
            'string',
            'max:150',
        ],

        'description' => [
            'nullable',
            'string',
        ],

        'is_active' => [
            'nullable',
            'boolean',
        ],

    ]);


    // =========================================================
    // 3. NORMALISASI DATA
    // =========================================================

    $validated['forklift_code'] = strtoupper(
        trim($validated['forklift_code'])
    );

    $validated['asset_number'] = strtoupper(
        trim($validated['asset_number'])
    );


    // =========================================================
    // 4. PASTIKAN KODE FORKLIFT SESUAI
    //
    // Kode yang dipulihkan harus tetap merupakan kode
    // dari record yang ditemukan.
    //
    // Admin boleh mengubah data teknis,
    // tetapi tidak boleh mengubah identitas forklift
    // melalui proses Restore.
    // =========================================================

    if (
        $validated['forklift_code']
        !== strtoupper(
            trim($forklift->forklift_code)
        )
    ) {

        return redirect()
            ->route('master.forklifts.create')
            ->withInput()
            ->withErrors([

                'forklift_code' =>
                    'Kode forklift tidak sesuai '
                    . 'dengan data yang akan dipulihkan.',

            ]);
    }


    // =========================================================
    // 5. CEK ASSET NUMBER
    //
    // Jangan sampai asset number digunakan oleh
    // forklift lain.
    //
    // Termasuk data yang masih aktif maupun Soft Delete.
    // =========================================================

    $assetUsedByOther = Forklift::withTrashed()
        ->where(
            'asset_number',
            $validated['asset_number']
        )
        ->where(
            'id',
            '!=',
            $forklift->id
        )
        ->exists();


    if ($assetUsedByOther) {

        return redirect()
            ->route('master.forklifts.create')
            ->withInput()
            ->withErrors([

                'asset_number' =>
                    'Nomor aset '
                    . $validated['asset_number']
                    . ' sudah digunakan oleh forklift lain.',

            ]);
    }


    // =========================================================
    // 6. RESTORE
    // =========================================================
    //
    // Hanya menghapus nilai deleted_at.
    //
    // ID forklift tetap.
    // QR token tetap.
    // Riwayat inspeksi tetap.
    //
    // =========================================================

    $forklift->restore();


    // =========================================================
    // 7. UPDATE DATA DENGAN INPUT TERAKHIR
    //
    // PENTING:
    //
    // qr_token TIDAK DISENTUH.
    //
    // Dengan demikian QR Code lama tetap berlaku.
    // =========================================================

    $forklift->update([

        'forklift_code' =>
            $validated['forklift_code'],

        'asset_number' =>
            $validated['asset_number'],

        'brand' =>
            $validated['brand'],

        'model' =>
            $validated['model'] ?? null,

        'serial_number' =>
            $validated['serial_number'] ?? null,

        'location_id' =>
            $validated['location_id'],

        'capacity' =>
            $validated['capacity'],

        'fuel_type' =>
            $validated['fuel_type'],

        'manufacture_year' =>
            $validated['manufacture_year'] ?? null,

        'commissioning_date' =>
            $validated['commissioning_date'] ?? null,

        'vendor_name' =>
            $validated['vendor_name'] ?? null,

        'description' =>
            $validated['description'] ?? null,

        'is_active' =>
            $request->boolean('is_active'),

    ]);


    // =========================================================
    // 8. ACTIVITY LOG
    // =========================================================
    //
    // action "Restore" sesuai dengan ENUM activity_logs.action:
    //
    // Login
    // Logout
    // Create
    // Update
    // Delete
    // Submit
    // Approve
    // Reject
    // Restore
    // Export
    //
    // =========================================================

    ActivityLog::create([

        'user_id' =>
            Auth::id(),

        'module' =>
            'Master Forklift',

        'action' =>
            'Restore',

        'subject_type' =>
            Forklift::class,

        'subject_id' =>
            $forklift->id,

        'description' =>
            Auth::user()->name
            . ' memulihkan kembali forklift '
            . $forklift->forklift_code
            . ' dari data yang sebelumnya dihapus.',

        'ip_address' =>
            $request->ip(),

        'user_agent' =>
            $request->userAgent(),

    ]);


    // =========================================================
    // 9. REDIRECT
    // =========================================================

    return redirect()
        ->route('master.forklifts.index')
        ->with(
            'success',
            'Forklift '
            . $forklift->forklift_code
            . ' berhasil dipulihkan kembali.'
        );
}
    /**
     * Menampilkan detail spesifikasi unit forklift, QR Code, dan riwayat inspeksi.
     */
    public function show(Forklift $forklift)
    {
        $forklift->load(['location', 'inspections' => function ($q) {
            $q->with(['operator', 'approval'])->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('master.forklifts.show', compact('forklift'));
    }

    /**
     * Menampilkan formulir edit spesifikasi unit forklift.
     */
    public function edit(Forklift $forklift)
    {
        $locations = Location::where('is_active', true)->get();

        return view('master.forklifts.edit', compact('forklift', 'locations'));
    }

    /**
     * Memproses pembaruan data spesifikasi unit forklift.
     */
    public function update(Request $request, Forklift $forklift)
    {
        // 1. Validasi Input Data
        $validated = $request->validate([
            'forklift_code'      => ['required', 'string', 'max:30', Rule::unique('forklifts', 'forklift_code')->ignore($forklift->id)],
            'asset_number'       => ['nullable', 'string', 'max:50'],
            'location_id'        => ['required', 'exists:locations,id'],
            'brand'              => ['required', 'string', 'max:50'],
            'model'              => ['required', 'string', 'max:50'],
            'serial_number'      => ['nullable', 'string', 'max:50'],
            'manufacture_year'   => ['nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'commissioning_date' => ['nullable', 'date'],
            'capacity'           => ['required', 'numeric', 'min:0.1', 'max:50'],
            'fuel_type'          => ['required', 'string', Rule::in(['Electric', 'Diesel', 'LPG'])],
            'vendor_name'        => ['nullable', 'string', 'max:100'],
            'is_active'          => ['boolean'],
            'description'        => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        // 2. Update Data
        $forklift->update($validated);

        // 3. Catat Activity Log (BR-024)
        $admin = Auth::user();
        ActivityLog::create([
            'user_id'     => $admin->id,
            'module'      => 'Master Forklift',
            'action'      => 'Update',
            'description' => "Admin {$admin->name} memperbarui spesifikasi forklift: {$forklift->forklift_code}.",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('master.forklifts.index')
            ->with('success', "Data unit forklift {$forklift->forklift_code} berhasil diperbarui.");
    }

    /**
     * Menghapus (Soft Delete) unit forklift dari sistem.
     */
    public function destroy(Request $request, Forklift $forklift)
    {
        $admin = Auth::user();
        $code = $forklift->forklift_code;

        // Lakukan Soft Delete
        $forklift->delete();

        // Catat Activity Log (BR-024)
        ActivityLog::create([
            'user_id'     => $admin->id,
            'module'      => 'Master Forklift',
            'action'      => 'Delete',
            'description' => "Admin {$admin->name} menghapus unit forklift: {$code}.",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('master.forklifts.index')
            ->with('success', "Unit Forklift {$code} berhasil dihapus.");
    }

    /**
     * Memperbarui/Regenerasi QR Code Token unik untuk forklift (BR-005).
     */
   /**
 * Cetak ulang QR Code forklift.
 *
 * PENTING:
 * Method ini TIDAK mengubah qr_token.
 * QR baru dibuat dari token yang sama,
 * sehingga QR lama tetap valid.
 */
public function regenerateQr(Forklift $forklift)
{
    // Pastikan token tersedia.
    // Jika data lama ternyata tidak memiliki token,
    // baru kita buat token baru.
    if (empty($forklift->qr_token)) {

        $forklift->qr_token = (string) \Illuminate\Support\Str::uuid();

        $forklift->save();
    }

    ActivityLog::create([
        'user_id'      => Auth::id(),
        'module'       => 'Master Forklift',
        'action'       => 'Export',
        'subject_type' => Forklift::class,
        'subject_id'   => $forklift->id,
        'description'  => Auth::user()->name
            . ' mencetak ulang QR Code forklift '
            . $forklift->forklift_code
            . '. QR token tetap dipertahankan.',
        'ip_address'   => request()->ip(),
        'user_agent'   => request()->userAgent(),
    ]);

    return redirect()
        ->route('master.forklifts.print_qr', $forklift->id)
        ->with(
            'success',
            'QR Code berhasil dibuat ulang. QR Code lama tetap aktif.'
        );
}

    /**
     * Tampilan cetak stiker label QR Code Forklift untuk ditempel di unit operasional.
     */
    public function printQr(Forklift $forklift)
    {
        return view('master.forklifts.print_qr', compact('forklift'));
    }
}