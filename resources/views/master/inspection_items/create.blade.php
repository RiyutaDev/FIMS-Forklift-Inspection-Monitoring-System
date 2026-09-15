@extends('layouts.app')

@section('title', 'Tambah Item Ceklis')

@section('content')
<style>
    .inspection-create-page {
        max-width: 1180px;
        margin: 0 auto;
        padding: 28px;
    }

    .inspection-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .inspection-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .inspection-header-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
        font-size: 23px;
    }

    .inspection-title {
        margin: 0;
        color: #172554;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .inspection-subtitle {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        color: #334155;
        background: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: 0.2s ease;
    }

    .back-button:hover {
        color: #1d4ed8;
        border-color: #93c5fd;
        background: #eff6ff;
    }

    .form-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fbff, #eff6ff);
    }

    .form-card-header h2 {
        margin: 0;
        color: #1e3a8a;
        font-size: 17px;
        font-weight: 800;
    }

    .form-card-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .form-card-body {
        padding: 25px;
    }

    .error-alert {
        display: flex;
        gap: 12px;
        margin-bottom: 22px;
        padding: 16px 18px;
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
    }

    .error-alert strong {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .error-alert ul {
        margin: 0;
        padding-left: 18px;
        font-size: 13px;
        line-height: 1.7;
    }

    .form-section {
        margin-bottom: 26px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-number {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: #1d4ed8;
        background: #dbeafe;
        font-size: 13px;
        font-weight: 800;
    }

    .section-heading h3 {
        margin: 0;
        color: #1e293b;
        font-size: 15px;
        font-weight: 800;
    }

    .section-heading p {
        margin: 2px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px;
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

    .required-mark {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        min-height: 43px;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        outline: none;
        color: #1e293b;
        background: #ffffff;
        font-family: inherit;
        font-size: 13px;
        transition: 0.2s ease;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    select.form-control {
        cursor: pointer;
    }

    .field-help {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .settings-panel {
        padding: 20px;
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: #f8fbff;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .setting-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px;
        border: 1px solid #dbe3ef;
        border-radius: 11px;
        background: #ffffff;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .setting-option:hover {
        border-color: #93c5fd;
        background: #eff6ff;
    }

    .setting-option input {
        width: 16px;
        height: 16px;
        accent-color: #2563eb;
        cursor: pointer;
    }

    .setting-option span {
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding-top: 22px;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .action-left,
    .action-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 17px;
        border-radius: 10px;
        border: 1px solid transparent;
        font-family: inherit;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .button-secondary {
        color: #475569;
        border-color: #dbe3ef;
        background: #ffffff;
    }

    .button-secondary:hover {
        color: #1e40af;
        border-color: #93c5fd;
        background: #eff6ff;
    }

    .button-primary {
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.22);
    }

    .button-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
    }

    @media (max-width: 850px) {
        .inspection-create-page {
            padding: 20px;
        }

        .settings-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .inspection-create-page {
            padding: 14px;
        }

        .inspection-title {
            font-size: 21px;
        }

        .form-card-body {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .settings-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .action-left,
        .action-right {
            width: 100%;
        }

        .button {
            flex: 1;
        }
    }
</style>

<div class="inspection-create-page">

    {{-- Header --}}
    <div class="inspection-header">
        <div class="inspection-header-left">
            <div class="inspection-header-icon">
                ✓
            </div>

            <div>
                <h1 class="inspection-title">Tambah Item Ceklis</h1>
                <p class="inspection-subtitle">
                    Tambahkan informasi dan pengaturan item pemeriksaan forklift.
                </p>
            </div>
        </div>

        <a href="{{ route('master.inspection-items.index') }}" class="back-button">
            ← Kembali
        </a>
    </div>

    <div class="form-card">

        {{-- Card Header --}}
        <div class="form-card-header">
            <h2>Formulir Item Pemeriksaan</h2>
            <p>
                Lengkapi informasi item dan aturan penggunaannya pada checklist inspeksi.
            </p>
        </div>

        <div class="form-card-body">

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="error-alert">
                    <div style="font-size: 20px;">⚠</div>

                    <div>
                        <strong>Form belum dapat disimpan.</strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('master.inspection-items.store') }}" method="POST">
                @csrf

                {{-- Informasi Dasar --}}
                <div class="form-section">
                    <div class="section-heading">
                        <div class="section-number">01</div>

                        <div>
                            <h3>Informasi Dasar</h3>
                            <p>Identitas dan kategori item checklist.</p>
                        </div>
                    </div>

                    <div class="form-grid">

                        {{-- Kode Item --}}
                        <div class="form-group">
                            <label for="item_code" class="form-label">
                                Kode Item <span class="required-mark">*</span>
                            </label>

                            <input
                                type="text"
                                id="item_code"
                                name="item_code"
                                class="form-control"
                                value="{{ old('item_code') }}"
                                maxlength="20"
                                placeholder="Contoh: ENG-001"
                                required
                            >

                            <div class="field-help">
                                Maksimal 20 karakter.
                            </div>
                        </div>

                        {{-- Nama Item --}}
                        <div class="form-group">
                            <label for="item_name" class="form-label">
                                Nama Item <span class="required-mark">*</span>
                            </label>

                            <input
                                type="text"
                                id="item_name"
                                name="item_name"
                                class="form-control"
                                value="{{ old('item_name') }}"
                                maxlength="100"
                                placeholder="Contoh: Kondisi oli mesin"
                                required
                            >
                        </div>

                        {{-- Kategori --}}
                        <div class="form-group">
                            <label for="inspection_category_id" class="form-label">
                                Kategori <span class="required-mark">*</span>
                            </label>

                            <select
                                id="inspection_category_id"
                                name="inspection_category_id"
                                class="form-control"
                                required
                            >
                                <option value="">-- Pilih Kategori --</option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('inspection_category_id') == $category->id)
                                    >
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis Forklift --}}
                        <div class="form-group">
                            <label for="applicable_fuel_type" class="form-label">
                                Bahan Bakar yang Berlaku
                                <span class="required-mark">*</span>
                            </label>

                            <select
                                id="applicable_fuel_type"
                                name="applicable_fuel_type"
                                class="form-control"
                                required
                            >
                                @foreach (['All', 'Electric', 'Diesel', 'LPG'] as $fuelType)
                                    <option
                                        value="{{ $fuelType }}"
                                        @selected(old('applicable_fuel_type', 'All') === $fuelType)
                                    >
                                        {{ $fuelType === 'All' ? 'Semua Jenis Forklift' : $fuelType }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="field-help">
                                Pilih jenis forklift yang menggunakan item ini.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konfigurasi Input --}}
                <div class="form-section">
                    <div class="section-heading">
                        <div class="section-number">02</div>

                        <div>
                            <h3>Konfigurasi Input</h3>
                            <p>Tentukan bentuk jawaban dan satuan pemeriksaan.</p>
                        </div>
                    </div>

                    <div class="form-grid">

                        {{-- Tipe Input --}}
                        <div class="form-group">
                            <label for="input_type" class="form-label">
                                Tipe Input <span class="required-mark">*</span>
                            </label>

                            <select
                                id="input_type"
                                name="input_type"
                                class="form-control"
                                required
                            >
                                @foreach ([
                                    'OK_NG' => 'OK / NG',
                                    'YES_NO' => 'Ya / Tidak',
                                    'NUMBER' => 'Angka Bulat',
                                    'DECIMAL' => 'Angka Desimal',
                                    'PERCENTAGE' => 'Persentase',
                                    'TEXT' => 'Teks'
                                ] as $inputType => $inputLabel)
                                    <option
                                        value="{{ $inputType }}"
                                        @selected(old('input_type', 'OK_NG') === $inputType)
                                    >
                                        {{ $inputLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Satuan --}}
                        <div class="form-group">
                            <label for="unit" class="form-label">
                                Satuan
                            </label>

                            <input
                                type="text"
                                id="unit"
                                name="unit"
                                class="form-control"
                                value="{{ old('unit') }}"
                                maxlength="20"
                                placeholder="Contoh: %, bar, °C, jam"
                            >
                        </div>

                        {{-- Urutan Tampilan --}}
                        <div class="form-group">
                            <label for="sort_order" class="form-label">
                                Urutan Tampilan <span class="required-mark">*</span>
                            </label>

                            <input
                                type="number"
                                id="sort_order"
                                name="sort_order"
                                class="form-control"
                                min="0"
                                value="{{ old('sort_order', 1) }}"
                                required
                            >

                            <div class="field-help">
                                Angka lebih kecil akan ditampilkan lebih awal.
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                placeholder="Tambahkan penjelasan atau standar pemeriksaan item..."
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Checklist --}}
                <div class="form-section">
                    <div class="section-heading">
                        <div class="section-number">03</div>

                        <div>
                            <h3>Pengaturan Checklist</h3>
                            <p>Atur tingkat kepentingan dan kebutuhan data tambahan.</p>
                        </div>
                    </div>

                    <div class="settings-panel">
                        <div class="settings-grid">

                            <label class="setting-option" for="is_critical">
                                <input
                                    type="checkbox"
                                    id="is_critical"
                                    name="is_critical"
                                    value="1"
                                    @checked(old('is_critical'))
                                >

                                <span>Item Kritis</span>
                            </label>

                            <label class="setting-option" for="requires_photo">
                                <input
                                    type="checkbox"
                                    id="requires_photo"
                                    name="requires_photo"
                                    value="1"
                                    @checked(old('requires_photo'))
                                >

                                <span>Wajib Foto</span>
                            </label>

                            <label class="setting-option" for="requires_note">
                                <input
                                    type="checkbox"
                                    id="requires_note"
                                    name="requires_note"
                                    value="1"
                                    @checked(old('requires_note'))
                                >

                                <span>Wajib Catatan</span>
                            </label>

                            <label class="setting-option" for="is_active">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    @checked(old('is_active', true))
                                >

                                <span>Item Aktif</span>
                            </label>

                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="form-actions">
                    <div class="action-left">
                        <a
                            href="{{ route('master.inspection-items.index') }}"
                            class="button button-secondary"
                        >
                            Batal
                        </a>
                    </div>

                    <div class="action-right">
                        <button type="submit" class="button button-primary">
                            ✓ Simpan Item
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection