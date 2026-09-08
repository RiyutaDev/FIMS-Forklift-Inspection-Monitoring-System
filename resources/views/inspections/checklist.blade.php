@extends('layouts.app')

@section('title', 'Checklist Inspeksi')

@section('content')

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

            <a href="{{ route('inspections.create') }}"
               class="inspection-btn inspection-btn-secondary">
                <span>←</span>
                Kembali
            </a>

        </div>

    </div>


    {{-- =========================================================
        FORKLIFT INFORMATION
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
                <span class="inspection-info-label">
                    Kode Forklift
                </span>

                <strong>
                    {{ $forklift->forklift_code }}
                </strong>
            </div>


            <div class="inspection-info-item">
                <span class="inspection-info-label">
                    Nama / Model
                </span>

                <strong>
                    {{ $forklift->name ?? $forklift->model ?? '-' }}
                </strong>
            </div>


            <div class="inspection-info-item">
                <span class="inspection-info-label">
                    Fuel Type
                </span>

                <strong>
                    {{ $forklift->fuel_type ?? '-' }}
                </strong>
            </div>


            <div class="inspection-info-item">
                <span class="inspection-info-label">
                    Lokasi
                </span>

                <strong>
                    {{ optional($forklift->location)->name ?? '-' }}
                </strong>
            </div>


            <div class="inspection-info-item">
                <span class="inspection-info-label">
                    Tanggal
                </span>

                <strong>
                    {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}
                </strong>
            </div>


            <div class="inspection-info-item">
                <span class="inspection-info-label">
                    Shift
                </span>

                <strong class="inspection-shift-badge">
                    {{ $shift }}
                </strong>
            </div>

        </div>

    </div>


    {{-- =========================================================
        EXISTING DRAFT NOTICE
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
    @if ($errors->any())

        <div class="inspection-alert inspection-alert-danger">

            <div class="inspection-alert-icon">
                !
            </div>

            <div>

                <strong>
                    Periksa kembali data checklist
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
        CHECKLIST FORM
    ========================================================== --}}
    <form
        action="{{ route('inspections.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="inspectionChecklistForm"
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
            CHECKLIST CATEGORIES
        ====================================================== --}}
        @forelse($categories as $category)

            @if($category->items->count() > 0)

                <div class="inspection-checklist-card">

                    {{-- CATEGORY HEADER --}}
                    <div class="inspection-checklist-card-header">

                        <div class="inspection-category-number">
                            {{ $loop->iteration }}
                        </div>

                        <div>

                            <h2>
                                {{ $category->name }}
                            </h2>

                            @if(!empty($category->description))

                                <p>
                                    {{ $category->description }}
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- CHECKLIST ITEMS --}}
                    <div class="inspection-checklist-body">

                        @foreach($category->items as $item)

                            <div class="inspection-checklist-item">

                                {{-- ITEM INFORMATION --}}
                                <div class="inspection-item-content">

                                    <div class="inspection-item-number">
                                        {{ $loop->iteration }}
                                    </div>

                                    <div class="inspection-item-question">

                                        <label>
                                            {{ $item->name }}
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


                                {{-- =================================================
                                    INPUT BERDASARKAN INPUT TYPE
                                ================================================== --}}
                                <div class="inspection-item-input">

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

                                                <span>
                                                    OK
                                                </span>

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

                                                <span>
                                                    NG
                                                </span>

                                            </label>

                                        </div>


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

                                                <span>
                                                    Ya
                                                </span>

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

                                                <span>
                                                    Tidak
                                                </span>

                                            </label>

                                        </div>


                                    @elseif($item->input_type === 'NUMBER')

                                        <div class="inspection-input-with-unit">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][value]"
                                                value="{{ old("items.{$item->id}.value") }}"
                                                class="inspection-form-control"
                                                step="1"
                                                required
                                            >

                                        </div>


                                    @elseif($item->input_type === 'DECIMAL')

                                        <div class="inspection-input-with-unit">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][value]"
                                                value="{{ old("items.{$item->id}.value") }}"
                                                class="inspection-form-control"
                                                step="0.01"
                                                required
                                            >

                                        </div>


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
                                            >

                                            <span class="inspection-input-unit">
                                                %
                                            </span>

                                        </div>


                                    @else

                                        <input
                                            type="text"
                                            name="items[{{ $item->id }}][value]"
                                            value="{{ old("items.{$item->id}.value") }}"
                                            class="inspection-form-control"
                                            placeholder="Masukkan hasil pemeriksaan"
                                            required
                                        >

                                    @endif


                                    {{-- =================================================
                                        NOTE
                                    ================================================== --}}
                                    <div class="inspection-item-note">

                                        <input
                                            type="text"
                                            name="items[{{ $item->id }}][note]"
                                            value="{{ old("items.{$item->id}.note") }}"
                                            class="inspection-form-control"
                                            placeholder="Catatan pemeriksaan (opsional)"
                                        >

                                    </div>


                                    {{-- =================================================
                                        PHOTO
                                    ================================================== --}}
                                    <div class="inspection-item-photo">

                                        <label class="inspection-photo-label">

                                            <span>
                                                📷
                                            </span>

                                            <span>
                                                Foto bukti
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

                </div>

            @endif

        @empty

            {{-- EMPTY CHECKLIST --}}
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
            GENERAL REMARKS
        ========================================================== --}}
        @if($categories->sum(fn($category) => $category->items->count()) > 0)

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
                SUBMIT AREA
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
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="inspection-btn inspection-btn-primary"
                        id="submitInspectionButton"
                    >
                        <span>
                            ✓
                        </span>

                        Submit Inspeksi
                    </button>

                </div>

            </div>

        @endif

    </form>

</div>


{{-- =============================================================
    SUBMIT CONFIRMATION
============================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('inspectionChecklistForm');
    const button = document.getElementById('submitInspectionButton');

    if (!form || !button) {
        return;
    }

    form.addEventListener('submit', function (event) {

        const confirmed = confirm(
            'Apakah Anda yakin seluruh checklist sudah diperiksa dan ingin mengirim inspeksi ini?'
        );

        if (!confirmed) {
            event.preventDefault();
            return;
        }

        button.disabled = true;

        button.innerHTML = `
            <span>⏳</span>
            Menyimpan...
        `;

    });

});
</script>

@endpush

@endsection