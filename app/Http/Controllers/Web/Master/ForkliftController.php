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

        return view('master.forklifts.create', compact('locations'));
    }

    /**
     * Memproses penyimpanan unit forklift baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Data Forklift
        $validated = $request->validate([
            'forklift_code'      => ['required', 'string', 'max:30', 'unique:forklifts,forklift_code'],
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
        ], [
            'forklift_code.unique' => 'Kode Forklift sudah digunakan oleh unit lain.',
            'location_id.required' => 'Lokasi/Area kerja wajib dipilih.',
            'capacity.numeric'     => 'Kapasitas harus berupa angka desimal (contoh: 2.5).',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        // 2. Token QR Code otomatis di-generate oleh event boot() pada Model Forklift jika kosong
        if (empty($validated['qr_token'])) {
            $validated['qr_token'] = (string) Str::uuid();
        }

        // 3. Simpan Forklift Baru
        $forklift = Forklift::create($validated);

        // 4. Catat Activity Log (BR-024)
        $admin = Auth::user();
        ActivityLog::create([
            'user_id'     => $admin->id,
            'module'      => 'Master Forklift',
            'action'      => 'Create Forklift',
            'description' => "Admin {$admin->name} merilis unit forklift baru: {$forklift->forklift_code} ({$forklift->brand} {$forklift->model}).",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('master.forklifts.index')
            ->with('success', "Unit Forklift {$forklift->forklift_code} berhasil ditambahkan.");
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
            'action'      => 'Update Forklift',
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
            'action'      => 'Delete Forklift',
            'description' => "Admin {$admin->name} menghapus unit forklift: {$code}.",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('master.forklifts.index')
            ->with('success', "Unit Forklift {$code} berhasil dihapus.");
    }

    /**
     * Memperbarui/Regenerasi QR Code Token unik untuk forklift (BR-005).
     */
    public function regenerateQr(Request $request, Forklift $forklift)
    {
        $newToken = (string) Str::uuid();

        $forklift->update([
            'qr_token' => $newToken,
        ]);

        $admin = Auth::user();
        ActivityLog::create([
            'user_id'     => $admin->id,
            'module'      => 'Master Forklift',
            'action'      => 'Regenerate QR Token',
            'description' => "Admin {$admin->name} meriset QR Code token untuk unit forklift: {$forklift->forklift_code}.",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->back()
            ->with('success', "QR Code token untuk unit {$forklift->forklift_code} berhasil diperbarui.");
    }

    /**
     * Tampilan cetak stiker label QR Code Forklift untuk ditempel di unit operasional.
     */
    public function printQr(Forklift $forklift)
    {
        return view('master.forklifts.print_qr', compact('forklift'));
    }
}