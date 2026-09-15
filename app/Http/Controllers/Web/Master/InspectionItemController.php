<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\InspectionCategory;
use App\Models\InspectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InspectionItemController extends Controller
{
    /**
     * Menampilkan daftar item inspeksi dengan fitur pencarian dan filter.
     */
    public function index(Request $request)
    {
        $query = InspectionItem::with('category');

        // Filter Pencarian: Kode Item & Nama Item
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where(
                'inspection_category_id',
                $request->category_id
            );
        }

        // Filter berdasarkan Tipe Bahan Bakar
        if ($request->filled('applicable_fuel_type')) {
            $query->where(
                'applicable_fuel_type',
                $request->applicable_fuel_type
            );
        }

        // Filter berdasarkan Item Kritis
        if ($request->filled('is_critical')) {
            $query->where(
                'is_critical',
                $request->is_critical
            );
        }

        // Filter berdasarkan Status Aktif
        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                $request->is_active
            );
        }

        $items = $query
            ->orderBy('inspection_category_id')
            ->orderBy('sort_order', 'asc')
            ->paginate(15)
            ->withQueryString();

        $categories = InspectionCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view(
            'master.inspection_items.index',
            compact('items', 'categories')
        );
    }

    /**
     * Menampilkan formulir penambahan item inspeksi baru.
     */
    public function create()
    {
        $categories = InspectionCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view(
            'master.inspection_items.create',
            compact('categories')
        );
    }

    /**
     * Memproses penyimpanan item inspeksi baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Item Checklist
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:20',
                'unique:inspection_items,item_code',
            ],

            'inspection_category_id' => [
                'required',
                'exists:inspection_categories,id',
            ],

            'item_name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'applicable_fuel_type' => [
                'required',
                'string',
                Rule::in([
                    'All',
                    'Electric',
                    'Diesel',
                    'LPG',
                ]),
            ],

            'input_type' => [
                'required',
                'string',
                Rule::in([
                    'OK_NG',
                    'YES_NO',
                    'NUMBER',
                    'DECIMAL',
                    'PERCENTAGE',
                    'TEXT',
                ]),
            ],

            'unit' => [
                'nullable',
                'string',
                'max:20',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_critical' => [
                'nullable',
                'boolean',
            ],

            'requires_photo' => [
                'nullable',
                'boolean',
            ],

            'requires_note' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Normalisasi kode item
        $validated['item_code'] = strtoupper(
            trim($validated['item_code'])
        );

        // Parsing checkbox boolean
        $validated['is_critical'] = $request->boolean(
            'is_critical',
            false
        );

        $validated['requires_photo'] = $request->boolean(
            'requires_photo',
            false
        );

        $validated['requires_note'] = $request->boolean(
            'requires_note',
            false
        );

        $validated['is_active'] = $request->boolean(
            'is_active',
            true
        );

        // 2. Simpan Item Baru
        $item = InspectionItem::create($validated);

        // 3. Catat Activity Log
        $admin = Auth::user();

        ActivityLog::create([
            'user_id' => $admin->id,
            'module' => 'Master Inspection Item',
            'action' => 'Create',
            'description' => "Admin {$admin->name} menambahkan item inspeksi baru: {$item->item_code} - {$item->item_name}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('master.inspection-items.index')
            ->with(
                'success',
                "Item Inspeksi {$item->item_code} berhasil ditambahkan."
            );
    }

    /**
     * Menampilkan detail konfigurasi sebuah item inspeksi.
     */
    public function show(InspectionItem $inspectionItem)
    {
        $inspectionItem->load('category');

        return view(
            'master.inspection_items.show',
            compact('inspectionItem')
        );
    }

    /**
     * Menampilkan formulir edit item inspeksi.
     */
    public function edit(InspectionItem $inspectionItem)
    {
        $categories = InspectionCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view(
            'master.inspection_items.edit',
            compact('inspectionItem', 'categories')
        );
    }

    /**
     * Memproses pembaruan data item inspeksi.
     */
    public function update(
        Request $request,
        InspectionItem $inspectionItem
    ) {
        // 1. Validasi Input Data
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique(
                    'inspection_items',
                    'item_code'
                )->ignore($inspectionItem->id),
            ],

            'inspection_category_id' => [
                'required',
                'exists:inspection_categories,id',
            ],

            'item_name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'applicable_fuel_type' => [
                'required',
                'string',
                Rule::in([
                    'All',
                    'Electric',
                    'Diesel',
                    'LPG',
                ]),
            ],

            'input_type' => [
                'required',
                'string',
                Rule::in([
                    'OK_NG',
                    'YES_NO',
                    'NUMBER',
                    'DECIMAL',
                    'PERCENTAGE',
                    'TEXT',
                ]),
            ],

            'unit' => [
                'nullable',
                'string',
                'max:20',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_critical' => [
                'nullable',
                'boolean',
            ],

            'requires_photo' => [
                'nullable',
                'boolean',
            ],

            'requires_note' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Normalisasi kode item
        $validated['item_code'] = strtoupper(
            trim($validated['item_code'])
        );

        // Parsing checkbox boolean
        $validated['is_critical'] = $request->boolean(
            'is_critical',
            false
        );

        $validated['requires_photo'] = $request->boolean(
            'requires_photo',
            false
        );

        $validated['requires_note'] = $request->boolean(
            'requires_note',
            false
        );

        // Penting:
        // Tanpa default true, checkbox yang tidak dicentang
        // akan tersimpan sebagai false.
        $validated['is_active'] = $request->boolean(
            'is_active',
            false
        );

        // 2. Update Data Item
        $inspectionItem->update($validated);

        // 3. Catat Activity Log
        $admin = Auth::user();

        ActivityLog::create([
            'user_id' => $admin->id,
            'module' => 'Master Inspection Item',
            'action' => 'Update',
            'description' => "Admin {$admin->name} memperbarui data item inspeksi: {$inspectionItem->item_code}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('master.inspection-items.index')
            ->with(
                'success',
                "Item Inspeksi {$inspectionItem->item_code} berhasil diperbarui."
            );
    }

    /**
     * Menghapus item inspeksi.
     */
    public function destroy(
        Request $request,
        InspectionItem $inspectionItem
    ) {
        $admin = Auth::user();
        $code = $inspectionItem->item_code;

        // Lakukan Soft Delete jika model menggunakan SoftDeletes
        $inspectionItem->delete();

        // Catat Activity Log
        ActivityLog::create([
            'user_id' => $admin->id,
            'module' => 'Master Inspection Item',
            'action' => 'Delete',
            'description' => "Admin {$admin->name} menghapus item inspeksi: {$code}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('master.inspection-items.index')
            ->with(
                'success',
                "Item Inspeksi {$code} berhasil dihapus."
            );
    }
}