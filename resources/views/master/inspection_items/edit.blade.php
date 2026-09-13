@extends('layouts.app')

@section('title', 'Edit Item Ceklis')

@section('content')

<style>
    .inspection-edit-page {
        min-height: 100vh;
        padding: 28px;
        background: #f1f5f9;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        padding: 28px;
        color: #ffffff;
        border-radius: 18px;
        background: linear-gradient(135deg, #0f172a, #1e3a8a, #2563eb);
        box-shadow: 0 10px 25px rgba(15, 23, 42, .15);
    }

    .page-header small {
        display: block;
        margin-bottom: 8px;
        color: #bfdbfe;
        font-size: 12px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
    }

    .page-header p {
        margin-top: 8px;
        color: #dbeafe;
        font-size: 13px;
    }

    .page-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        flex-shrink: 0;
        color: #ffffff;
        font-size: 28px;
        border-radius: 18px;
        background: rgba(255, 255, 255, .15);
    }

    .form-card {
        overflow: hidden;
        margin-bottom: 24px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .06);
    }

    .card-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-heading-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        color: #ffffff;
        font-weight: 800;
        border-radius: 12px;
        background: #2563eb;
    }

    .card-heading-number.green {
        background: #059669;
    }

    .card-heading h2 {
        margin: 0;
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
    }

    .card-heading p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        padding: 24px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .required {
        color: #dc2626;
    }

    .optional {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 400;
    }

    .form-control {
        display: block;
        width: 100%;
        min-height: 44px;
        box-sizing: border-box;
        padding: 11px 13px;
        color: #334155;
        font-size: 13px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        outline: none;
        transition: all .2s ease;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    textarea.form-control {
        min-height: 115px;
        resize: vertical;
    }

    .form-help {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .error-text {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        padding: 24px;
    }

    .setting-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 18px;
        cursor: pointer;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        transition: all .2s ease;
    }

    .setting-card:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        transform: translateY(-1px);
    }

    .setting-card input {
        width: 17px;
        height: 17px;
        margin-top: 2px;
        accent-color: #2563eb;
    }

    .setting-title {
        display: block;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
    }

    .setting-description {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.6;
    }

    .alert-error {
        margin-bottom: 24px;
        padding: 16px 20px;
        color: #991b1b;
        font-size: 13px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
    }

    .action-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 30px;
        padding: 22px 24px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .06);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 45px;
        padding: 12px 22px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        border: none;
        border-radius: 11px;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn-cancel {
        color: #475569;
        background: #e2e8f0;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    .btn-submit {
        color: #ffffff;
        background: #2563eb;
        box-shadow: 0 6px 14px rgba(37, 99, 235, .25);
    }

    .btn-submit:hover {
        background: #1d4ed8;
        box-shadow: 0 8px 18px rgba(37, 99, 235, .35);
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .inspection-edit-page {
            padding: 15px;
        }

        .page-header {
            padding: 22px;
        }

        .page-header h1 {
            font-size: 22px;
        }

        .form-grid,
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .action-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="inspection-edit-page">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <small>Master Data / Item Ceklis / Edit</small>

            <h1>Edit Item Ceklis</h1>

            <p>
                Perbarui informasi dan pengaturan item pemeriksaan forklift.
            </p>
        </div>

        <div class="page-header-icon">
            ✓
        </div>
    </div>

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="alert-error">
            <strong>Form belum dapat disimpan.</strong>

            <ul style="margin: 8px 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Utama --}}
    <form
        action="{{ route('master.inspection-items.update', ['inspectionItem' => $inspectionItem->id]) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        {{-- Informasi Dasar --}}
        <div class="form-card">
            <div class="card-heading">
                <div class="card-heading-number">1</div>

                <div>
                    <h2>Informasi Dasar Item</h2>
                    <p>Data identitas dan klasifikasi item pemeriksaan.</p>
                </div>
            </div>

            <div class="form-grid">

                {{-- Kode Item --}}
                <div class="form-group">
                    <label for="item_code" class="form-label">
                        Kode Item <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="item_code"
                        name="item_code"
                        value="{{ old('item_code', $inspectionItem->item_code) }}"
                        class="form-control"
                        placeholder="Contoh: HYD001"
                        required
                    >

                    @error('item_code')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama Item --}}
                <div class="form-group">
                    <label for="item_name" class="form-label">
                        Nama Item <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="item_name"
                        name="item_name"
                        value="{{ old('item_name', $inspectionItem->item_name) }}"
                        class="form-control"
                        placeholder="Contoh: Hydraulic Hose"
                        required
                    >

                    @error('item_name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        Kategori <span class="required">*</span>
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        class="form-control"
                        required
                    >
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    old('category_id', $inspectionItem->category_id) == $category->id
                                )
                            >
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($categories->isEmpty())
                        <div class="form-help" style="color: #dc2626;">
                            Belum ada kategori aktif yang tersedia.
                        </div>
                    @else
                        <div class="form-help">
                            Pilih kategori yang sesuai dengan item pemeriksaan.
                        </div>
                    @endif

                    @error('category_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Bahan Bakar --}}
                <div class="form-group">
                    <label for="applicable_fuel_type" class="form-label">
                        Bahan Bakar yang Berlaku <span class="required">*</span>
                    </label>

                    <select
                        id="applicable_fuel_type"
                        name="applicable_fuel_type"
                        class="form-control"
                        required
                    >
                        <option value="ALL"
                            @selected(old('applicable_fuel_type', $inspectionItem->applicable_fuel_type) == 'ALL')
                        >
                            Semua Jenis
                        </option>

                        <option value="ELECTRIC"
                            @selected(old('applicable_fuel_type', $inspectionItem->applicable_fuel_type) == 'ELECTRIC')
                        >
                            Electric
                        </option>

                        <option value="DIESEL"
                            @selected(old('applicable_fuel_type', $inspectionItem->applicable_fuel_type) == 'DIESEL')
                        >
                            Diesel
                        </option>

                        <option value="LPG"
                            @selected(old('applicable_fuel_type', $inspectionItem->applicable_fuel_type) == 'LPG')
                        >
                            LPG
                        </option>
                    </select>

                    @error('applicable_fuel_type')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tipe Input --}}
                <div class="form-group">
                    <label for="input_type" class="form-label">
                        Tipe Input <span class="required">*</span>
                    </label>

                    <select
                        id="input_type"
                        name="input_type"
                        class="form-control"
                        required
                    >
                        <option value="OK_NG"
                            @selected(old('input_type', $inspectionItem->input_type) == 'OK_NG')
                        >
                            OK / NG
                        </option>

                        <option value="YES_NO"
                            @selected(old('input_type', $inspectionItem->input_type) == 'YES_NO')
                        >
                            Ya / Tidak
                        </option>

                        <option value="NUMBER"
                            @selected(old('input_type', $inspectionItem->input_type) == 'NUMBER')
                        >
                            Angka
                        </option>

                        <option value="DECIMAL"
                            @selected(old('input_type', $inspectionItem->input_type) == 'DECIMAL')
                        >
                            Desimal
                        </option>

                        <option value="PERCENTAGE"
                            @selected(old('input_type', $inspectionItem->input_type) == 'PERCENTAGE')
                        >
                            Persentase
                        </option>

                        <option value="TEXT"
                            @selected(old('input_type', $inspectionItem->input_type) == 'TEXT')
                        >
                            Teks
                        </option>
                    </select>

                    @error('input_type')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Satuan --}}
                <div class="form-group">
                    <label for="unit" class="form-label">
                        Satuan <span class="optional">(Opsional)</span>
                    </label>

                    <input
                        type="text"
                        id="unit"
                        name="unit"
                        value="{{ old('unit', $inspectionItem->unit) }}"
                        class="form-control"
                        placeholder="Contoh: Volt, Liter, mm"
                    >

                    @error('unit')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        Deskripsi <span class="optional">(Opsional)</span>
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        placeholder="Tuliskan petunjuk atau keterangan pemeriksaan..."
                    >{{ old('description', $inspectionItem->description) }}</textarea>

                    @error('description')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Urutan Tampilan --}}
                <div class="form-group">
                    <label for="sort_order" class="form-label">
                        Urutan Tampilan <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        min="1"
                        value="{{ old('sort_order', $inspectionItem->sort_order) }}"
                        class="form-control"
                        placeholder="Contoh: 1"
                        required
                    >

                    @error('sort_order')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Pengaturan Checklist --}}
        <div class="form-card">
            <div class="card-heading">
                <div class="card-heading-number green">✓</div>

                <div>
                    <h2>Pengaturan Checklist</h2>
                    <p>Atur karakteristik dan perilaku item pemeriksaan.</p>
                </div>
            </div>

            <div class="settings-grid">

                {{-- Item Kritis --}}
                <label class="setting-card">
                    <input
                        type="checkbox"
                        name="is_critical"
                        value="1"
                        @checked(old('is_critical', $inspectionItem->is_critical))
                    >

                    <span>
                        <span class="setting-title">Item Kritis</span>

                        <span class="setting-description">
                            Tandai item yang memiliki tingkat kepentingan tinggi.
                        </span>
                    </span>
                </label>

                {{-- Wajib Foto --}}
                <label class="setting-card">
                    <input
                        type="checkbox"
                        name="requires_photo"
                        value="1"
                        @checked(old('requires_photo', $inspectionItem->requires_photo))
                    >

                    <span>
                        <span class="setting-title">Wajib Foto</span>

                        <span class="setting-description">
                            Pengemudi wajib melampirkan foto pada item ini.
                        </span>
                    </span>
                </label>

                {{-- Wajib Catatan --}}
                <label class="setting-card">
                    <input
                        type="checkbox"
                        name="requires_note"
                        value="1"
                        @checked(old('requires_note', $inspectionItem->requires_note))
                    >

                    <span>
                        <span class="setting-title">Wajib Catatan</span>

                        <span class="setting-description">
                            Pengemudi wajib memberikan catatan pemeriksaan.
                        </span>
                    </span>
                </label>

                {{-- Status Aktif --}}
                <label class="setting-card">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $inspectionItem->is_active))
                    >

                    <span>
                        <span class="setting-title">Item Aktif</span>

                        <span class="setting-description">
                            Item akan digunakan dalam checklist pemeriksaan.
                        </span>
                    </span>
                </label>

            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="action-footer">

            <a
                href="{{ route('master.inspection-items.index') }}"
                class="btn btn-cancel"
            >
                ← Batal
            </a>

            <button
                type="submit"
                class="btn btn-submit"
            >
                ✓ Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection