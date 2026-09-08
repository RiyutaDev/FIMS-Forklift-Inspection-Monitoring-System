@extends('layouts.app')

@section('title', 'Detail Inspeksi')

@section('content')

<div class="inspection-show-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    {{-- HEADER --}}
    <div class="inspection-page-header">

        <h1 class="inspection-page-title">
            Detail Inspeksi
        </h1>

        <a
            href="{{ route('inspections.index') }}"
            class="inspection-btn inspection-btn-secondary"
        >
            ← Kembali
        </a>

    </div>


    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="inspection-alert inspection-alert-success">

            <div class="inspection-alert-icon">
                ✓
            </div>

            <div>
                <strong>
                    Berhasil
                </strong>

                <p>
                    {{ session('success') }}
                </p>
            </div>

        </div>

    @endif


    @if(session('info'))

        <div class="inspection-alert inspection-alert-warning">

            <div class="inspection-alert-icon">
                !
            </div>

            <div>
                <strong>
                    Informasi
                </strong>

                <p>
                    {{ session('info') }}
                </p>
            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="inspection-alert inspection-alert-danger">

            <div class="inspection-alert-icon">
                !
            </div>

            <div>
                <strong>
                    Terjadi Kesalahan
                </strong>

                <p>
                    {{ session('error') }}
                </p>
            </div>

        </div>

    @endif


    {{-- =========================================================
        INSPECTION SUMMARY
    ========================================================== --}}
    <div class="inspection-detail-summary">

        {{-- NOMOR --}}
        <div class="inspection-detail-summary-main">

            <div class="inspection-detail-number-label">
                NOMOR INSPEKSI
            </div>

            <div class="inspection-detail-number">
                {{ $inspection->inspection_number }}
            </div>

            <div class="inspection-detail-date">

                {{ optional($inspection->inspection_date)->format('d/m/Y') }}

                <span>•</span>

                {{ $inspection->inspection_shift }}

            </div>

        </div>


        {{-- STATUS --}}
        <div class="inspection-detail-status">

            <span class="inspection-detail-status-label">
                STATUS
            </span>

            @php
                $statusClass = match($inspection->status) {
                    'Approved' => 'status-approved',
                    'Rejected' => 'status-rejected',
                    'Submitted' => 'status-submitted',
                    'Draft' => 'status-draft',
                    default => 'status-default',
                };
            @endphp

            <span class="inspection-status {{ $statusClass }}">
                {{ $inspection->status }}
            </span>

        </div>

    </div>


    {{-- =========================================================
        GENERAL INFORMATION
    ========================================================== --}}
    <div class="inspection-detail-card">

        <div class="inspection-detail-card-header">

            <div>
                <h2>
                    Informasi Inspeksi
                </h2>

                <p>
                    Informasi dasar pemeriksaan forklift.
                </p>
            </div>

        </div>


        <div class="inspection-detail-grid">

            <div class="inspection-detail-item">

                <span>
                    Forklift
                </span>

                <strong>
                    {{ $inspection->forklift?->forklift_code ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Operator
                </span>

                <strong>
                    {{ $inspection->operator?->name ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Lokasi
                </span>

                <strong>
                    {{ $inspection->forklift?->location?->name ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Fuel Type
                </span>

                <strong>
                    {{ $inspection->forklift?->fuel_type ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Tanggal Inspeksi
                </span>

                <strong>
                    {{ optional($inspection->inspection_date)->format('d/m/Y') ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Shift
                </span>

                <strong>
                    {{ $inspection->inspection_shift ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Mulai Inspeksi
                </span>

                <strong>
                    {{ $inspection->inspection_started_at
                        ? \Carbon\Carbon::parse($inspection->inspection_started_at)->format('d/m/Y H:i')
                        : '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Selesai Inspeksi
                </span>

                <strong>
                    {{ $inspection->inspection_completed_at
                        ? \Carbon\Carbon::parse($inspection->inspection_completed_at)->format('d/m/Y H:i')
                        : '-' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RESULT SUMMARY
    ========================================================== --}}
    <div class="inspection-result-grid">

        @php
            $totalItems = $inspection->details->count();

            $passedItems = $inspection->details
                ->where('is_passed', true)
                ->count();

            $failedItems = $inspection->details
                ->where('is_passed', false)
                ->count();
        @endphp


        <div class="inspection-result-card">

            <div class="inspection-result-icon">
                📋
            </div>

            <div>

                <span>
                    Total Checklist
                </span>

                <strong>
                    {{ $totalItems }}
                </strong>

            </div>

        </div>


        <div class="inspection-result-card">

            <div class="inspection-result-icon inspection-result-success">
                ✓
            </div>

            <div>

                <span>
                    Lulus
                </span>

                <strong>
                    {{ $passedItems }}
                </strong>

            </div>

        </div>


        <div class="inspection-result-card">

            <div class="inspection-result-icon inspection-result-danger">
                !
            </div>

            <div>

                <span>
                    Tidak Lulus
                </span>

                <strong>
                    {{ $failedItems }}
                </strong>

            </div>

        </div>


        <div class="inspection-result-card">

            <div class="inspection-result-icon">
                🚜
            </div>

            <div>

                <span>
                    Hasil Akhir
                </span>

                <strong>
                    {{ $inspection->overall_result ?? '-' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CHECKLIST RESULT
    ========================================================== --}}
    <div class="inspection-detail-card">

        <div class="inspection-detail-card-header">

            <div>

                <h2>
                    Hasil Checklist
                </h2>

                <p>
                    Hasil pemeriksaan setiap item pada forklift.
                </p>

            </div>

        </div>


        <div class="inspection-result-list">

            @forelse($inspection->details as $detail)

                <div class="inspection-result-item">

                    {{-- ITEM --}}
                    <div class="inspection-result-item-content">

                        <div class="inspection-result-category">

                            {{ $detail->inspectionItem?->category?->name ?? 'Checklist' }}

                        </div>

                        <h3>
                            {{ $detail->inspectionItem?->item_name
                                ?? $detail->inspectionItem?->name
                                ?? '-' }}
                        </h3>

                        @if($detail->note)

                            <p class="inspection-result-note">
                                Catatan: {{ $detail->note }}
                            </p>

                        @endif

                    </div>


                    {{-- VALUE --}}
                    <div class="inspection-result-value">

                        @if($detail->result_status)

                            @if(in_array($detail->result_status, ['OK', 'YES']))

                                <span class="inspection-result-badge result-pass">
                                    ✓
                                    {{ $detail->result_status }}
                                </span>

                            @else

                                <span class="inspection-result-badge result-fail">
                                    !
                                    {{ $detail->result_status }}
                                </span>

                            @endif


                        @elseif(!is_null($detail->result_value))

                            <span class="inspection-result-value-number">
                                {{ $detail->result_value }}
                            </span>


                        @elseif($detail->result_text)

                            <span class="inspection-result-text">
                                {{ $detail->result_text }}
                            </span>


                        @else

                            <span class="inspection-result-empty">
                                -
                            </span>

                        @endif

                    </div>


                    {{-- PHOTO --}}
                    @if($detail->photos && $detail->photos->count())

                        <div class="inspection-result-photos">

                            @foreach($detail->photos as $photo)

                                <a
                                    href="{{ asset('storage/' . $photo->photo_path) }}"
                                    target="_blank"
                                    class="inspection-photo-preview"
                                >

                                    <img
                                        src="{{ asset('storage/' . $photo->photo_path) }}"
                                        alt="{{ $photo->caption ?? 'Foto inspeksi' }}"
                                    >

                                </a>

                            @endforeach

                        </div>

                    @endif

                </div>

            @empty

                <div class="inspection-empty-state">

                    <div class="inspection-empty-icon">
                        📋
                    </div>

                    <h3>
                        Belum ada hasil checklist
                    </h3>

                    <p>
                        Data checklist belum tersedia untuk inspeksi ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- =========================================================
        GENERAL REMARKS
    ========================================================== --}}
    <div class="inspection-detail-card">

        <div class="inspection-detail-card-header">

            <div>

                <h2>
                    Catatan Umum
                </h2>

                <p>
                    Catatan tambahan dari operator.
                </p>

            </div>

        </div>


        <div class="inspection-remarks-content">

            @if($inspection->remarks)

                {{ $inspection->remarks }}

            @else

                <span class="inspection-muted">
                    Tidak ada catatan tambahan.

                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        SUBMISSION INFORMATION
    ========================================================== --}}
    <div class="inspection-detail-card">

        <div class="inspection-detail-card-header">

            <div>

                <h2>
                    Informasi Pengajuan
                </h2>

                <p>
                    Informasi pengiriman dan proses review inspeksi.
                </p>

            </div>

        </div>


        <div class="inspection-detail-grid">

            <div class="inspection-detail-item">

                <span>
                    Submitted By
                </span>

                <strong>
                    {{ $inspection->submittedBy?->name ?? '-' }}
                </strong>

            </div>


            <div class="inspection-detail-item">

                <span>
                    Waktu Submit
                </span>

                <strong>
                    {{ $inspection->submitted_at
                        ? \Carbon\Carbon::parse($inspection->submitted_at)->format('d/m/Y H:i')
                        : '-' }}
                </strong>

            </div>


            @if($inspection->approval)

                <div class="inspection-detail-item">

                    <span>
                        Reviewer
                    </span>

                    <strong>
                        {{ $inspection->approval->approvedBy?->name ?? '-' }}
                    </strong>

                </div>


                <div class="inspection-detail-item">

                    <span>
                        Waktu Review
                    </span>

                    <strong>
                        {{ $inspection->approval->approved_at
                            ? \Carbon\Carbon::parse($inspection->approval->approved_at)->format('d/m/Y H:i')
                            : '-' }}
                    </strong>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        FOOTER ACTION
    ========================================================== --}}
    <div class="inspection-detail-footer">

        <a
            href="{{ route('inspections.index') }}"
            class="inspection-btn inspection-btn-secondary"
        >
            ← Kembali ke Riwayat
        </a>

    </div>

</div>

@endsection