@extends('layouts.app')

@section('title', 'Checklist Inspeksi')

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | RESET KHUSUS HALAMAN CHECKLIST
    |--------------------------------------------------------------------------
    */

    .inspection-checklist-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 24px;
        color: #172554;
    }

    .inspection-checklist-page *,
    .inspection-checklist-page *::before,
    .inspection-checklist-page *::after {
        box-sizing: border-box;
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .inspection-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .inspection-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
        color: #64748b;
        font-size: 13px;
    }

    .inspection-breadcrumb a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 700;
    }

    .inspection-page-title {
        margin: 0;
        color: #172554;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.25;
    }

    .inspection-page-subtitle {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 15px;
        line-height: 1.6;
    }

    /*
    |--------------------------------------------------------------------------
    | BUTTON UMUM
    |--------------------------------------------------------------------------
    */

    .inspection-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 0 20px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .inspection-btn:focus-visible,
    .inspection-form-control:focus-visible,
    .inspection-radio:focus-within {
        outline: 3px solid rgba(37, 99, 235, 0.25);
        outline-offset: 2px;
    }

    .inspection-btn-primary {
        color: #ffffff;
        background: #16a34a;
        border-color: #16a34a;
    }

    .inspection-btn-primary:hover {
        color: #ffffff;
        background: #15803d;
        border-color: #15803d;
    }

    .inspection-btn-primary:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .inspection-btn-secondary {
        color: #1e3a8a;
        background: #eff6ff;
        border-color: #cbdcf5;
    }

    .inspection-btn-secondary:hover {
        color: #1e3a8a;
        background: #dbeafe;
        border-color: #a8c7ef;
    }

    /*
    |--------------------------------------------------------------------------
    | INFORMASI FORKLIFT
    |--------------------------------------------------------------------------
    */

    .inspection-info-card {
        margin-bottom: 22px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #dbe5f0;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }

    .inspection-info-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 22px;
        background: linear-gradient(135deg, #eff6ff, #ffffff);
        border-bottom: 1px solid #e2e8f0;
    }

    .inspection-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        flex: 0 0 54px;
        border-radius: 14px;
        background: #dbeafe;
        font-size: 27px;
    }

    .inspection-info-card-header h2 {
        margin: 0;
        color: #172554;
        font-size: 23px;
        font-weight: 800;
    }

    .inspection-info-card-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .inspection-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    .inspection-info-item {
        min-width: 0;
        padding: 17px 22px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }

    .inspection-info-item:nth-child(3n) {
        border-right: none;
    }

    .inspection-info-label {
        margin-bottom: 6px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .inspection-info-value {
        color: #172554;
        font-size: 15px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .inspection-status-active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        color: #15803d;
        background: #dcfce7;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    /*
    |--------------------------------------------------------------------------
    | ALERT
    |--------------------------------------------------------------------------
    */

    .inspection-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
        padding: 16px 18px;
        border: 1px solid transparent;
        border-radius: 12px;
    }

    .inspection-alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 50%;
        font-size: 17px;
        font-weight: 800;
    }

    .inspection-alert strong {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .inspection-alert p,
    .inspection-alert ul {
        margin: 0;
        color: inherit;
        font-size: 13px;
        line-height: 1.6;
    }

    .inspection-alert-warning {
        color: #92400e;
        background: #fffbeb;
        border-color: #fde68a;
    }

    .inspection-alert-warning .inspection-alert-icon {
        color: #92400e;
        background: #fde68a;
    }

    .inspection-alert-danger {
        color: #991b1b;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .inspection-alert-danger .inspection-alert-icon {
        color: #991b1b;
        background: #fecaca;
    }

    /*
    |--------------------------------------------------------------------------
    | KATEGORI CHECKLIST
    |--------------------------------------------------------------------------
    */

    .inspection-checklist-card {
        margin-bottom: 24px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #dbe5f0;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }

    .inspection-checklist-card-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 21px 23px;
        background: linear-gradient(135deg, #eff6ff, #f8fafc);
        border-bottom: 1px solid #dbe5f0;
    }

    .inspection-category-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        color: #ffffff;
        background: #2563eb;
        border-radius: 50%;
        font-size: 21px;
        font-weight: 800;
    }

    .inspection-category-heading {
        flex: 1;
        min-width: 0;
    }

    .inspection-category-title {
        margin: 0;
        color: #172554;
        font-size: 21px;
        font-weight: 800;
        line-height: 1.4;
    }

    .inspection-category-description {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .inspection-category-count {
        flex: 0 0 auto;
        padding: 8px 13px;
        color: #1d4ed8;
        background: #dbeafe;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    /*
    |--------------------------------------------------------------------------
    | ITEM CHECKLIST
    |--------------------------------------------------------------------------
    */

    .inspection-checklist-body {
        padding: 0 22px;
    }

    .inspection-checklist-item {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(310px, 0.85fr);
        gap: 25px;
        padding: 24px 0;
        border-bottom: 1px solid #e2e8f0;
        scroll-margin-top: 25px;
    }

    .inspection-checklist-item:last-child {
        border-bottom: none;
    }

    .inspection-item-content {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-width: 0;
    }

    .inspection-item-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        color: #1e3a8a;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 50%;
        font-size: 14px;
        font-weight: 800;
    }

    .inspection-item-question {
        min-width: 0;
    }

    .inspection-item-question label {
        display: block;
        margin: 2px 0 0;
        color: #172554;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.65;
    }

    .inspection-item-question p {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    .inspection-critical-badge {
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
        padding: 5px 10px;
        color: #b91c1c;
        background: #fee2e2;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
    }

    .inspection-item-controls {
        min-width: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | RADIO OK / NG
    |--------------------------------------------------------------------------
    */

    .inspection-radio-group {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 13px;
    }

    .inspection-radio {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 58px;
        padding: 12px;
        border: 2px solid #dbe5f0;
        border-radius: 12px;
        color: #334155;
        background: #ffffff;
        font-size: 17px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .inspection-radio input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .inspection-radio-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        margin-right: 9px;
        border: 2px solid currentColor;
        border-radius: 50%;
        font-size: 14px;
    }

    .inspection-radio input:checked + .inspection-radio-mark::after {
        content: "";
        width: 10px;
        height: 10px;
        background: currentColor;
        border-radius: 50%;
    }

    .inspection-radio-ok {
        color: #15803d;
    }

    .inspection-radio-ng {
        color: #b91c1c;
    }

    .inspection-radio-ok:has(input:checked) {
        background: #dcfce7;
        border-color: #16a34a;
    }

    .inspection-radio-ng:has(input:checked) {
        background: #fee2e2;
        border-color: #dc2626;
    }

    /*
    |--------------------------------------------------------------------------
    | INPUT TEXT / NUMBER
    |--------------------------------------------------------------------------
    */

    .inspection-form-control {
        display: block;
        width: 100%;
        min-height: 48px;
        padding: 12px 14px;
        color: #172554;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        outline: none;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .inspection-form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .inspection-form-control::placeholder {
        color: #94a3b8;
    }

    .inspection-input-with-unit {
        display: flex;
        align-items: stretch;
        gap: 8px;
        margin-bottom: 13px;
    }

    .inspection-input-with-unit .inspection-form-control {
        flex: 1;
        min-width: 0;
    }

    .inspection-input-unit {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        padding: 0 12px;
        color: #1e3a8a;
        background: #eff6ff;
        border: 1px solid #cbdcf5;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
    }

    .inspection-item-note {
        margin-bottom: 13px;
    }

    .inspection-note-label,
    .inspection-photo-label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 12px;
        font-weight: 800;
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    */

    .inspection-item-photo {
        padding: 13px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
    }

    .inspection-photo-label {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0;
        color: #334155;
        cursor: pointer;
    }

    .inspection-photo-label span {
        flex: 0 0 auto;
    }

    .inspection-photo-label input[type="file"] {
        min-width: 0;
        max-width: 100%;
        color: #475569;
        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY STATE
    |--------------------------------------------------------------------------
    */

    .inspection-empty-state {
        padding: 45px 25px;
        text-align: center;
        background: #ffffff;
        border: 1px solid #dbe5f0;
        border-radius: 16px;
    }

    .inspection-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 15px;
        color: #2563eb;
        background: #dbeafe;
        border-radius: 50%;
        font-size: 28px;
        font-weight: 800;
    }

    .inspection-empty-state h3 {
        margin: 0 0 8px;
        color: #172554;
        font-size: 20px;
        font-weight: 800;
    }

    .inspection-empty-state p {
        max-width: 500px;
        margin: 0 auto 20px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    /*
    |--------------------------------------------------------------------------
    | CATATAN UMUM
    |--------------------------------------------------------------------------
    */

    .inspection-remarks-card {
        margin-top: 25px;
        padding: 22px;
        background: #ffffff;
        border: 1px solid #dbe5f0;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }

    .inspection-section-title {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
    }

    .inspection-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        background: #dbeafe;
        border-radius: 12px;
        font-size: 20px;
    }

    .inspection-section-title h2 {
        margin: 0;
        color: #172554;
        font-size: 19px;
        font-weight: 800;
    }

    .inspection-section-title p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    /*
    |--------------------------------------------------------------------------
    | AREA SUBMIT - TIDAK FIXED / TIDAK STICKY
    |--------------------------------------------------------------------------
    */

    .inspection-submit-card {
        width: 100%;
        margin-top: 25px;
        margin-bottom: 35px;
        padding: 22px;
        background: #ffffff;
        border: 1px solid #dbe5f0;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);

        /*
        | Sangat penting:
        | Tombol mengikuti alur halaman dan tidak menutupi checklist.
        */
        position: static !important;
        bottom: auto !important;
        left: auto !important;
        right: auto !important;
        z-index: auto !important;
    }

    .inspection-submit-info {
        margin-bottom: 18px;
    }

    .inspection-submit-info strong {
        display: block;
        color: #172554;
        font-size: 17px;
        font-weight: 800;
    }

    .inspection-submit-info p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .inspection-submit-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;

        /*
        | Tidak menggunakan fixed maupun sticky.
        */
        position: static !important;
        width: 100%;
        padding: 0;
        background: transparent;
        box-shadow: none;
    }

    .inspection-submit-actions .inspection-btn {
        min-height: 50px;
    }

    .inspection-submit-actions .inspection-btn-secondary {
        min-width: 145px;
    }

    .inspection-submit-actions .inspection-btn-primary {
        min-width: 210px;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE TABLET DAN HP
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {
        .inspection-checklist-item {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .inspection-item-controls {
            padding-left: 56px;
        }

        .inspection-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .inspection-info-item:nth-child(3n) {
            border-right: 1px solid #e2e8f0;
        }

        .inspection-info-item:nth-child(2n) {
            border-right: none;
        }
    }

    @media (max-width: 640px) {
        .inspection-checklist-page {
            padding: 14px;
        }

        .inspection-page-header {
            display: block;
        }

        .inspection-page-title {
            font-size: 23px;
        }

        .inspection-page-subtitle {
            font-size: 13px;
        }

        .inspection-header-actions {
            margin-top: 15px;
        }

        .inspection-header-actions .inspection-btn {
            width: 100%;
        }

        .inspection-info-card-header {
            padding: 17px;
        }

        .inspection-info-card-header h2 {
            font-size: 20px;
        }

        .inspection-info-grid {
            grid-template-columns: 1fr;
        }

        .inspection-info-item,
        .inspection-info-item:nth-child(2n),
        .inspection-info-item:nth-child(3n) {
            border-right: none;
        }

        .inspection-checklist-card-header {
            gap: 11px;
            padding: 17px;
        }

        .inspection-category-number {
            width: 40px;
            height: 40px;
            flex-basis: 40px;
            font-size: 18px;
        }

        .inspection-category-title {
            font-size: 17px;
        }

        .inspection-category-description {
            font-size: 12px;
        }

        .inspection-category-count {
            display: none;
        }

        .inspection-checklist-body {
            padding: 0 14px;
        }

        .inspection-checklist-item {
            padding: 21px 0;
        }

        .inspection-item-content {
            gap: 11px;
        }

        .inspection-item-number {
            width: 36px;
            height: 36px;
            flex-basis: 36px;
            font-size: 12px;
        }

        .inspection-item-question label {
            font-size: 15px;
            line-height: 1.65;
        }

        .inspection-item-question p {
            font-size: 13px;
        }

        .inspection-item-controls {
            padding-left: 0;
        }

        .inspection-radio {
            min-height: 58px;
            font-size: 16px;
        }

        .inspection-submit-card {
            padding: 17px;
            margin-bottom: 25px;
        }

        .inspection-submit-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .inspection-submit-actions .inspection-btn,
        .inspection-submit-actions .inspection-btn-primary,
        .inspection-submit-actions .inspection-btn-secondary {
            width: 100%;
            min-width: 0;
            min-height: 54px;
            font-size: 16px;
        }
    }
</style>

<div class="inspection-checklist-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="inspection-page-header">

        <div>
            <div class="inspection-breadcrumb">
                <a href="{{ route('inspections.index') }}">
                    Inspeksi
                </a>

                <span>/</span>

                <span>Checklist</span>
            </div>

            <h1 class="inspection-page-title">
                Checklist Inspeksi
            </h1>

            <p class="inspection-page-subtitle">
                Lakukan pemeriksaan kondisi forklift sebelum digunakan.
            </p>
        </div>

        <div class="inspection-header-actions">
            <a
                href="{{ route('inspections.create') }}"
                class="inspection-btn inspection-btn-secondary"
            >
                <span>←</span>
                Kembali
            </a>
        </div>

    </div>


    {{-- =========================================================
        INFORMASI FORKLIFT
    ========================================================== --}}

    <div class="inspection-info-card">

        <div class="inspection-info-card-header">

            <div class="inspection-info-icon">
                🚜
            </div>

            <div>
                <h2>
                    {{ $forklift->forklift_code }}
                </h2>

                <p>
                    Informasi unit yang sedang diperiksa
                </p>
            </div>

        </div>

        <div class="inspection-info-grid">

            <div class="inspection-info-item">
                <div class="inspection-info-label">
                    Kode Forklift
                </div>

                <div class="inspection-info-value">
                    {{ $forklift->forklift_code }}
                </div>
            </div>

            <div class="inspection-info-item">
                <div class="inspection-info-label">
                    Merek / Model
                </div>

                <div class="inspection-info-value">
                    {{ $forklift->brand ?? '-' }}
                    {{ $forklift->model ?? '' }}
                </div>
            </div>

            <div class="inspection-info-item">
                <div class="inspection-info-label">
                    Fuel Type
                </div>

                <div class="inspection-info-value">
                    {{ $forklift->fuel_type ?? '-' }}
                </div>
            </div>

            <div class="inspection-info-item">
                <div class="inspection-info-label">
                    Lokasi
                </div>

                <div class="inspection-info-value">
                    {{ optional($forklift->location)->location_name
                        ?? optional($forklift->location)->name
                        ?? '-' }}
                </div>
            </div>

            <div class="inspection-info-item">
                <div class="inspection-info-label">
                    Tanggal
                </div>

                <div class="inspection-info-value">
                    {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}
                </div>
            </div>

            <div class="inspection-info-item">
                <div class="inspection-info-label">
                    Shift
                </div>

                <div class="inspection-info-value">
                    {{ $shift }}
                </div>
            </div>

        </div>

    </div>


    {{-- =========================================================
        DRAFT INSPEKSI
    ========================================================== --}}

    @if($existingInspection)

        <div class="inspection-alert inspection-alert-warning">

            <div class="inspection-alert-icon">
                ⚠
            </div>

            <div>
                <strong>
                    Draft inspeksi ditemukan
                </strong>

                <p>
                    Terdapat inspeksi draft untuk forklift ini pada
                    {{ $shift }} hari ini. Data akan dilanjutkan pada
                    inspeksi tersebut.
                </p>
            </div>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERROR
    ========================================================== --}}

    @if($errors->any())

        <div class="inspection-alert inspection-alert-danger">

            <div class="inspection-alert-icon">
                !
            </div>

            <div>
                <strong>
                    Periksa kembali data checklist
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    @endif


    {{-- =========================================================
        FORM CHECKLIST
    ========================================================== --}}

    <form
        id="inspectionChecklistForm"
        action="{{ route('inspections.store') }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >

        @csrf

        <input
            type="hidden"
            name="forklift_id"
            value="{{ $forklift->id }}"
        >

        <input
            type="hidden"
            name="inspection_shift"
            value="{{ $shift }}"
        >


        {{-- =====================================================
            KATEGORI CHECKLIST
        ====================================================== --}}

        @forelse($categories as $category)

            @if($category->inspectionItems->count() > 0)

                <section class="inspection-checklist-card">

                    {{-- HEADER KATEGORI --}}

                    <div class="inspection-checklist-card-header">

                        <div class="inspection-category-number">
                            {{ $loop->iteration }}
                        </div>

                        <div class="inspection-category-heading">

                            <h2 class="inspection-category-title">
                                {{ $category->category_name }}
                            </h2>

                            @if(!empty($category->description))

                                <p class="inspection-category-description">
                                    {{ $category->description }}
                                </p>

                            @else

                                <p class="inspection-category-description">
                                    Periksa seluruh komponen pada kategori ini
                                    sebelum forklift digunakan.
                                </p>

                            @endif

                        </div>

                        <div class="inspection-category-count">
                            {{ $category->inspectionItems->count() }} Item
                        </div>

                    </div>


                    {{-- DAFTAR ITEM --}}

                    <div class="inspection-checklist-body">

                        @foreach($category->inspectionItems as $item)

                            <div
                                class="inspection-checklist-item"
                                data-checklist-item="{{ $item->id }}"
                            >

                                {{-- INFORMASI ITEM --}}

                                <div class="inspection-item-content">

                                    <div class="inspection-item-number">
                                        {{ $loop->iteration }}
                                    </div>

                                    <div class="inspection-item-question">

                                        <label>
                                            {{ $item->item_name }}
                                        </label>

                                        @if(!empty($item->description))

                                            <p>
                                                {{ $item->description }}
                                            </p>

                                        @endif

                                        @if($item->is_critical)

                                            <span class="inspection-critical-badge">
                                                Critical
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                {{-- KONTROL PEMERIKSAAN --}}

                                <div class="inspection-item-controls">

                                    {{-- =================================================
                                        OK / NG
                                    ================================================== --}}

                                    @if($item->input_type === 'OK_NG')

                                        <div class="inspection-radio-group">

                                            <label class="inspection-radio inspection-radio-ok">

                                                <input
                                                    type="radio"
                                                    name="items[{{ $item->id }}][value]"
                                                    value="OK"
                                                    required
                                                    {{ old("items.{$item->id}.value") === 'OK' ? 'checked' : '' }}
                                                >

                                                <span class="inspection-radio-mark"></span>

                                                <span>✓ OK</span>

                                            </label>

                                            <label class="inspection-radio inspection-radio-ng">

                                                <input
                                                    type="radio"
                                                    name="items[{{ $item->id }}][value]"
                                                    value="NG"
                                                    required
                                                    {{ old("items.{$item->id}.value") === 'NG' ? 'checked' : '' }}
                                                >

                                                <span class="inspection-radio-mark"></span>

                                                <span>✕ NG</span>

                                            </label>

                                        </div>


                                    {{-- =================================================
                                        YES / NO
                                    ================================================== --}}

                                    @elseif($item->input_type === 'YES_NO')

                                        <div class="inspection-radio-group">

                                            <label class="inspection-radio inspection-radio-ok">

                                                <input
                                                    type="radio"
                                                    name="items[{{ $item->id }}][value]"
                                                    value="YES"
                                                    required
                                                    {{ old("items.{$item->id}.value") === 'YES' ? 'checked' : '' }}
                                                >

                                                <span class="inspection-radio-mark"></span>

                                                <span>✓ Ya</span>

                                            </label>

                                            <label class="inspection-radio inspection-radio-ng">

                                                <input
                                                    type="radio"
                                                    name="items[{{ $item->id }}][value]"
                                                    value="NO"
                                                    required
                                                    {{ old("items.{$item->id}.value") === 'NO' ? 'checked' : '' }}
                                                >

                                                <span class="inspection-radio-mark"></span>

                                                <span>✕ Tidak</span>

                                            </label>

                                        </div>


                                    {{-- =================================================
                                        NUMBER
                                    ================================================== --}}

                                    @elseif($item->input_type === 'NUMBER')

                                        <div class="inspection-input-with-unit">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][value]"
                                                value="{{ old("items.{$item->id}.value") }}"
                                                class="inspection-form-control"
                                                step="1"
                                                required
                                                placeholder="Masukkan angka"
                                            >

                                            @if(!empty($item->unit))

                                                <span class="inspection-input-unit">
                                                    {{ $item->unit }}
                                                </span>

                                            @endif

                                        </div>


                                    {{-- =================================================
                                        DECIMAL
                                    ================================================== --}}

                                    @elseif($item->input_type === 'DECIMAL')

                                        <div class="inspection-input-with-unit">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][value]"
                                                value="{{ old("items.{$item->id}.value") }}"
                                                class="inspection-form-control"
                                                step="0.01"
                                                required
                                                placeholder="Masukkan nilai"
                                            >

                                            @if(!empty($item->unit))

                                                <span class="inspection-input-unit">
                                                    {{ $item->unit }}
                                                </span>

                                            @endif

                                        </div>


                                    {{-- =================================================
                                        PERCENTAGE
                                    ================================================== --}}

                                    @elseif($item->input_type === 'PERCENTAGE')

                                        <div class="inspection-input-with-unit">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][value]"
                                                value="{{ old("items.{$item->id}.value") }}"
                                                class="inspection-form-control"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                required
                                                placeholder="0 sampai 100"
                                            >

                                            <span class="inspection-input-unit">
                                                %
                                            </span>

                                        </div>


                                    {{-- =================================================
                                        TEXT
                                    ================================================== --}}

                                    @else

                                        <input
                                            type="text"
                                            name="items[{{ $item->id }}][value]"
                                            value="{{ old("items.{$item->id}.value") }}"
                                            class="inspection-form-control"
                                            required
                                            placeholder="Masukkan hasil pemeriksaan"
                                        >

                                    @endif


                                    {{-- =================================================
                                        CATATAN ITEM
                                    ================================================== --}}

                                    <div class="inspection-item-note">

                                        <label class="inspection-note-label">
                                            Catatan Pemeriksaan
                                            @if($item->requires_note)
                                                <span class="text-danger">*</span>
                                            @else
                                                <span>(Opsional)</span>
                                            @endif
                                        </label>

                                        <input
                                            type="text"
                                            name="items[{{ $item->id }}][note]"
                                            value="{{ old("items.{$item->id}.note") }}"
                                            class="inspection-form-control"
                                            placeholder="Tuliskan catatan jika diperlukan"
                                            @if($item->requires_note) required @endif
                                        >

                                    </div>


                                    {{-- =================================================
                                        FOTO ITEM
                                    ================================================== --}}

                                    <div class="inspection-item-photo">

                                        <label class="inspection-photo-label">

                                            <span>📷</span>

                                            <span>
                                                Foto Bukti
                                                <small>(Opsional, wajib jika hasil NG)</small>
                                            </span>

                                            <input
                                                type="file"
                                                name="photos[{{ $item->id }}]"
                                                accept="image/jpeg,image/png,image/jpg"
                                            >

                                        </label>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </section>

            @endif

        @empty

            {{-- =================================================
                EMPTY CHECKLIST
            ================================================== --}}

            <div class="inspection-empty-state">

                <div class="inspection-empty-icon">
                    ✓
                </div>

                <h3>
                    Checklist belum tersedia
                </h3>

                <p>
                    Belum terdapat item checklist aktif untuk forklift
                    {{ $forklift->forklift_code }}.
                </p>

                <a
                    href="{{ route('inspections.index') }}"
                    class="inspection-btn inspection-btn-secondary"
                >
                    Kembali
                </a>

            </div>

        @endforelse


        {{-- =========================================================
            CATATAN UMUM DAN SUBMIT
        ========================================================== --}}

        @if($categories->sum(fn($category) => $category->inspectionItems->count()) > 0)

            <div class="inspection-remarks-card">

                <div class="inspection-section-title">

                    <div class="inspection-section-icon">
                        📝
                    </div>

                    <div>
                        <h2>
                            Catatan Umum
                        </h2>

                        <p>
                            Tambahkan informasi tambahan mengenai kondisi
                            forklift jika diperlukan.
                        </p>
                    </div>

                </div>

                <textarea
                    name="remarks"
                    rows="4"
                    class="inspection-form-control"
                    maxlength="1000"
                    placeholder="Contoh: Ditemukan kondisi tertentu pada unit, tindakan yang perlu dilakukan, atau informasi tambahan lainnya..."
                >{{ old('remarks') }}</textarea>

            </div>


            {{-- =====================================================
                SUBMIT AREA PALING BAWAH FORM
            ====================================================== --}}

            <div class="inspection-submit-card">

                <div class="inspection-submit-info">

                    <strong>
                        Pastikan seluruh checklist telah diperiksa
                    </strong>

                    <p>
                        Setelah dikirim, hasil inspeksi akan masuk ke proses
                        review Supervisor.
                    </p>

                </div>

                <div class="inspection-submit-actions">

                    <a
                        href="{{ route('inspections.create') }}"
                        class="inspection-btn inspection-btn-secondary"
                    >
                        ← Kembali
                    </a>

                    <button
                        type="submit"
                        class="inspection-btn inspection-btn-primary"
                        id="submitInspectionButton"
                    >
                        <span>✓</span>
                        <span>Simpan Inspeksi</span>
                    </button>

                </div>

            </div>

        @endif

    </form>

</div>


{{-- =============================================================
    JAVASCRIPT VALIDASI DAN PROTEKSI DOUBLE SUBMIT
============================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('inspectionChecklistForm');
    const submitButton = document.getElementById('submitInspectionButton');

    if (!form || !submitButton) {
        return;
    }

    form.addEventListener('input', function (event) {
        if (event.target.matches('[name^="items["][name$="[value]"]')) {
            event.target.classList.remove('is-invalid');
        }
    });

    form.addEventListener('change', function (event) {
        if (event.target.matches('[name^="items["][name$="[value]"]')) {
            const group = form.querySelectorAll(
                '[name="' + CSS.escape(event.target.name) + '"]'
            );

            group.forEach(function (input) {
                input.classList.remove('is-invalid');
            });
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const incompleteInputs = [];

        form.querySelectorAll('[data-checklist-item]').forEach(function (item) {
            const answerInputs = item.querySelectorAll(
                '[name^="items["][name$="[value]"]'
            );

            if (answerInputs.length === 0) {
                return;
            }

            const firstInput = answerInputs[0];
            const isRadioGroup = firstInput.type === 'radio';
            const isComplete = isRadioGroup
                ? Array.from(answerInputs).some(function (input) {
                    return input.checked;
                })
                : Array.from(answerInputs).some(function (input) {
                    return input.value.trim() !== '';
                });

            if (!isComplete) {
                answerInputs.forEach(function (input) {
                    input.classList.add('is-invalid');
                });
                incompleteInputs.push(firstInput);
            }
        });

        if (incompleteInputs.length > 0) {
            incompleteInputs[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            Swal.fire({
                icon: 'warning',
                title: 'Checklist Belum Lengkap',
                text: 'Silakan isi seluruh hasil pemeriksaan sebelum mengirim inspeksi.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });

            return;
        }

        Swal.fire({
            icon: 'question',
            title: 'Kirim Inspeksi?',
            text: 'Pastikan seluruh hasil pemeriksaan sudah benar.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim Inspeksi',
            cancelButtonText: 'Periksa Kembali',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span>⏳</span>
                <span>Mengirim...</span>
            `;

            form.submit();
        });

    });

});
</script>

@endpush

@endsection