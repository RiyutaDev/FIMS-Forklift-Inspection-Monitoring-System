@extends('layouts.app')

@section('page_title', 'Buat Inspeksi')
@section('page_subtitle', 'Mulai pemeriksaan harian forklift')

@section('content')

<div class="inspection-create-page">

    {{-- HEADER --}}
    <div class="create-header">

        <div class="create-header-left">

            <a href="{{ route('inspections.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <h1>Buat Inspeksi Baru</h1>
                <p>Lengkapi informasi forklift sebelum memulai checklist inspeksi.</p>
            </div>

        </div>

    </div>


    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="form-alert">

            <div class="form-alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <div>
                <strong>Periksa kembali data Anda</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    @endif


    {{-- MAIN FORM --}}
    <form
        action="{{ route('inspections.checklist') }}"
        method="GET"
        id="inspectionCreateForm"
    >

        <div class="create-grid">

            {{-- LEFT --}}
            <div class="create-main">

                {{-- FORKLIFT CARD --}}
                <div class="create-card">

                    <div class="card-heading">

                        <div class="card-heading-icon">
                            <i class="fas fa-truck-moving"></i>
                        </div>

                        <div>
                            <h2>Informasi Forklift</h2>
                            <p>Pilih unit forklift yang akan diperiksa.</p>
                        </div>

                    </div>


                    <div class="form-group">

                        <label for="forklift_id">
                            Forklift
                            <span>*</span>
                        </label>

                        <div class="select-wrapper">

                            <select
                                name="forklift_id"
                                id="forklift_id"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    -- Pilih Forklift --
                                </option>

                                @foreach ($forklifts as $forklift)

                                    <option
                                        value="{{ $forklift->id }}"
                                        data-location="{{ $forklift->location?->location_name ?? '-' }}"
                                        data-brand="{{ $forklift->brand }}"
                                        data-model="{{ $forklift->model ?? '-' }}"
                                        data-fuel="{{ $forklift->fuel_type }}"
                                        @selected(
                                            old('forklift_id', $selectedForklift?->id) == $forklift->id
                                        )
                                    >
                                        {{ $forklift->forklift_code }}
                                        — {{ $forklift->brand }}
                                        {{ $forklift->model ?? '' }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <small class="form-help">
                            Pilih unit forklift yang akan menjalani inspeksi.
                        </small>

                    </div>


                    {{-- FORKLIFT PREVIEW --}}
                    <div
                        id="forkliftPreview"
                        class="forklift-preview"
                        style="display:none;"
                    >

                        <div class="preview-icon">
                            <i class="fas fa-truck-moving"></i>
                        </div>

                        <div class="preview-content">

                            <strong id="previewCode">-</strong>

                            <span id="previewName">-</span>

                            <div class="preview-meta">

                                <span>
                                    <i class="fas fa-location-dot"></i>
                                    <span id="previewLocation">-</span>
                                </span>

                                <span>
                                    <i class="fas fa-bolt"></i>
                                    <span id="previewFuel">-</span>
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- INSPECTION SCHEDULE --}}
                <div class="create-card">

                    <div class="card-heading">

                        <div class="card-heading-icon schedule">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <div>
                            <h2>Jadwal Inspeksi</h2>
                            <p>Tentukan shift pemeriksaan forklift.</p>
                        </div>

                    </div>


                    {{-- DATE --}}
                    <div class="form-group">

                        <label>
                            Tanggal Inspeksi
                        </label>

                        <div class="readonly-field">

                            <div class="readonly-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>

                            <div>
                                <strong>
                                    {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
                                </strong>

                                <small>
                                    Tanggal otomatis mengikuti hari ini
                                </small>
                            </div>

                            <i class="fas fa-lock lock-icon"></i>

                        </div>

                    </div>


                    {{-- SHIFT --}}
                    <div class="form-group">

                        <label for="shift">
                            Shift
                            <span>*</span>
                        </label>

                        <select
                            name="shift"
                            id="shift"
                            class="form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Shift --
                            </option>

                            <option value="Shift 1"
                                @selected(old('shift') === 'Shift 1')>
                                Shift 1
                            </option>

                            <option value="Shift 2"
                                @selected(old('shift') === 'Shift 2')>
                                Shift 2
                            </option>

                            <option value="Shift 3"
                                @selected(old('shift') === 'Shift 3')>
                                Shift 3
                            </option>

                        </select>

                        <small class="form-help">
                            Pilih shift sesuai jadwal operasional forklift.
                        </small>

                    </div>

                </div>

            </div>


            {{-- RIGHT SIDE --}}
            <div class="create-side">

                <div class="info-card">

                    <div class="info-card-icon">
                        <i class="fas fa-circle-info"></i>
                    </div>

                    <div>

                        <h3>Informasi</h3>

                        <p>
                            Pastikan forklift yang dipilih sesuai dengan unit
                            yang akan diperiksa.
                        </p>

                        <p>
                            Setelah memilih unit dan shift, Anda akan diarahkan
                            ke checklist inspeksi.
                        </p>

                    </div>

                </div>


                <div class="process-card">

                    <h3>Alur Inspeksi</h3>

                    <div class="process-item active">

                        <div class="process-number">
                            1
                        </div>

                        <div>
                            <strong>Pilih Forklift</strong>
                            <span>Tentukan unit yang diperiksa</span>
                        </div>

                    </div>

                    <div class="process-line"></div>

                    <div class="process-item">

                        <div class="process-number">
                            2
                        </div>

                        <div>
                            <strong>Checklist</strong>
                            <span>Periksa seluruh komponen</span>
                        </div>

                    </div>

                    <div class="process-line"></div>

                    <div class="process-item">

                        <div class="process-number">
                            3
                        </div>

                        <div>
                            <strong>Submit</strong>
                            <span>Kirim untuk review Supervisor</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER ACTION --}}
        <div class="form-footer">

            <a
                href="{{ route('inspections.index') }}"
                class="btn-cancel"
            >
                Batal
            </a>

            <button
                type="submit"
                class="btn-start"
            >
                <span>Mulai Inspeksi</span>
                <i class="fas fa-arrow-right"></i>
            </button>

        </div>

    </form>

</div>


@push('styles')
<style>

    /* ========================================
       CREATE INSPECTION
    ======================================== */

    .inspection-create-page {
        width: 100%;
    }


    /* HEADER */

    .create-header {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 22px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .create-header-left {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .back-button {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #f8fafc;
        color: #64748b;
        text-decoration: none !important;
        transition: .15s ease;
    }

    .back-button:hover {
        background: #eff6ff;
        color: #2563eb;
    }

    .create-header h1 {
        margin: 0;
        color: #0f172a;
        font-size: 20px;
        font-weight: 700;
    }

    .create-header p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
    }


    /* ERROR */

    .form-alert {
        display: flex;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 4px solid #ef4444;
        border-radius: 10px;
        color: #991b1b;
    }

    .form-alert-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 8px;
        background: #fee2e2;
        color: #dc2626;
    }

    .form-alert strong {
        font-size: 12px;
    }

    .form-alert ul {
        margin: 5px 0 0;
        padding-left: 16px;
        font-size: 11px;
    }


    /* GRID */

    .create-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 18px;
    }

    .create-main {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }


    /* CARD */

    .create-card,
    .info-card,
    .process-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .create-card {
        padding: 22px;
    }


    /* CARD HEADING */

    .card-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 18px;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-heading-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 15px;
    }

    .card-heading-icon.schedule {
        background: #ecfdf5;
        color: #059669;
    }

    .card-heading h2 {
        margin: 0;
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
    }

    .card-heading p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }


    /* FORM */

    .form-group {
        margin-bottom: 18px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
    }

    .form-group label span {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        height: 42px;
        padding: 0 13px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-size: 12px;
        outline: none;
        transition: .15s ease;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .form-help {
        display: block;
        margin-top: 6px;
        color: #94a3b8;
        font-size: 10px;
    }


    /* FORKLIFT PREVIEW */

    .forklift-preview {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px;
        margin-top: 16px;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        background: #f8fbff;
    }

    .preview-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: #eff6ff;
        color: #2563eb;
    }

    .preview-content {
        min-width: 0;
    }

    .preview-content > strong {
        display: block;
        color: #1e293b;
        font-size: 12px;
    }

    .preview-content > span {
        display: block;
        margin-top: 2px;
        color: #64748b;
        font-size: 10px;
    }

    .preview-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 6px;
    }

    .preview-meta span {
        color: #64748b;
        font-size: 9px;
    }

    .preview-meta i {
        margin-right: 3px;
        color: #94a3b8;
    }


    /* READONLY DATE */

    .readonly-field {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 55px;
        padding: 9px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }

    .readonly-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #e2e8f0;
        color: #64748b;
        font-size: 12px;
    }

    .readonly-field strong {
        display: block;
        color: #334155;
        font-size: 11px;
    }

    .readonly-field small {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9px;
    }

    .lock-icon {
        margin-left: auto;
        color: #94a3b8;
        font-size: 10px;
    }


    /* INFO */

    .create-side {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .info-card {
        display: flex;
        gap: 11px;
        padding: 17px;
        background: #f8fbff;
        border-color: #dbeafe;
    }

    .info-card-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
    }

    .info-card h3,
    .process-card h3 {
        margin: 0;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .info-card p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 10px;
        line-height: 1.6;
    }


    /* PROCESS */

    .process-card {
        padding: 18px;
    }

    .process-card h3 {
        margin-bottom: 16px;
    }

    .process-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .process-number {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 700;
    }

    .process-item.active .process-number {
        background: #eff6ff;
        color: #2563eb;
    }

    .process-item strong {
        display: block;
        color: #475569;
        font-size: 10px;
    }

    .process-item span {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9px;
    }

    .process-line {
        width: 1px;
        height: 15px;
        margin: 3px 0 3px 14px;
        background: #e2e8f0;
    }


    /* FOOTER */

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 0 4px;
    }

    .btn-cancel,
    .btn-start {
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none !important;
        cursor: pointer;
        transition: .15s ease;
    }

    .btn-cancel {
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #334155;
    }

    .btn-start {
        border: 0;
        background: #2563eb;
        color: #fff;
        box-shadow: 0 3px 8px rgba(37, 99, 235, .18);
    }

    .btn-start:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }


    /* RESPONSIVE */

    @media (max-width: 900px) {

        .create-grid {
            grid-template-columns: 1fr;
        }

        .create-side {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

    }

    @media (max-width: 600px) {

        .create-header {
            padding: 16px;
        }

        .create-header h1 {
            font-size: 17px;
        }

        .create-card {
            padding: 17px;
        }

        .create-side {
            display: flex;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-start {
            width: 100%;
        }

    }

</style>
@endpush


@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {

    const forkliftSelect = document.getElementById('forklift_id');

    const preview = document.getElementById('forkliftPreview');

    const previewCode = document.getElementById('previewCode');
    const previewName = document.getElementById('previewName');
    const previewLocation = document.getElementById('previewLocation');
    const previewFuel = document.getElementById('previewFuel');


    function updateForkliftPreview() {

        const selected = forkliftSelect.options[forkliftSelect.selectedIndex];

        if (!forkliftSelect.value) {

            preview.style.display = 'none';

            return;
        }


        previewCode.textContent =
            selected.text.split(' — ')[0] || '-';

        previewName.textContent =
            selected.dataset.brand + ' ' +
            selected.dataset.model;

        previewLocation.textContent =
            selected.dataset.location || '-';

        previewFuel.textContent =
            selected.dataset.fuel || '-';

        preview.style.display = 'flex';
    }


    forkliftSelect.addEventListener(
        'change',
        updateForkliftPreview
    );


    updateForkliftPreview();

});

</script>
@endpush

@endsection