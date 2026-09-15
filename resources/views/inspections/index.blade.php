@extends('layouts.app')

@section('title', 'Daftar Inspeksi')
@section('page_title', 'Daftar Inspeksi')
@section('page_subtitle', 'Kelola dan pantau seluruh pemeriksaan forklift')

@section('breadcrumb')
    <li class="breadcrumb-item active">Inspeksi</li>
@endsection

@section('content')

<div class="inspection-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="inspection-page-header">

        <div class="inspection-header-content">

            <div class="inspection-header-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>

            <div>
                <h2 class="inspection-title">
                    Daftar Inspeksi
                </h2>

                <p class="inspection-subtitle">
                    Kelola dan pantau seluruh pemeriksaan forklift
                </p>
            </div>

        </div>


        {{-- CREATE INSPECTION --}}

        <a
            href="{{ route('inspections.create') }}"
            class="inspection-create-button"
        >

            <i class="fas fa-plus"></i>

            <span>Buat Inspeksi</span>

        </a>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <div class="inspection-summary-grid">

        {{-- TOTAL --}}

        <div class="inspection-summary-card">

            <div class="inspection-summary-icon inspection-icon-blue">
                <i class="fas fa-clipboard-list"></i>
            </div>

            <div class="inspection-summary-content">

                <span class="inspection-summary-label">
                    Total Inspeksi
                </span>

                <strong class="inspection-summary-value">
                    {{ $inspections->count() }}
                </strong>

            </div>

        </div>


        {{-- SUBMITTED --}}

        <div class="inspection-summary-card">

            <div class="inspection-summary-icon inspection-icon-warning">
                <i class="fas fa-clock"></i>
            </div>

            <div class="inspection-summary-content">

                <span class="inspection-summary-label">
                    Menunggu Review
                </span>

                <strong class="inspection-summary-value">

                    {{ $inspections->where('status', 'Submitted')->count() }}

                </strong>

            </div>

        </div>


        {{-- APPROVED --}}

        <div class="inspection-summary-card">

            <div class="inspection-summary-icon inspection-icon-success">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="inspection-summary-content">

                <span class="inspection-summary-label">
                    Approved
                </span>

                <strong class="inspection-summary-value">

                    {{ $inspections->where('status', 'Approved')->count() }}

                </strong>

            </div>

        </div>


        {{-- REJECTED --}}

        <div class="inspection-summary-card">

            <div class="inspection-summary-icon inspection-icon-danger">
                <i class="fas fa-times-circle"></i>
            </div>

            <div class="inspection-summary-content">

                <span class="inspection-summary-label">
                    Rejected
                </span>

                <strong class="inspection-summary-value">

                    {{ $inspections->where('status', 'Rejected')->count() }}

                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
         INSPECTION TABLE CARD
    ========================================================== --}}

    <div class="inspection-table-card">

        {{-- CARD HEADER --}}

        <div class="inspection-table-header">

            <div class="inspection-table-title">

                <div class="inspection-table-title-icon">
                    <i class="fas fa-list-alt"></i>
                </div>

                <div>

                    <h3>
                        Data Inspeksi
                    </h3>

                    <p>
                        Riwayat pemeriksaan forklift
                    </p>

                </div>

            </div>


            <div class="inspection-table-count">

                {{ $inspections->count() }} Data

            </div>

        </div>


        {{-- TABLE --}}

        <div class="inspection-table-wrapper">

            <table class="inspection-table">

                <thead>

                    <tr>

                        <th>
                            No. Inspeksi
                        </th>

                        <th>
                            Forklift
                        </th>

                        <th>
                            Operator
                        </th>

                        <th>
                            Shift
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="inspection-action-column">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($inspections as $inspection)

                        <tr>

                            {{-- INSPECTION NUMBER --}}

                            <td>

                                <div class="inspection-number">

                                    <span class="inspection-number-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </span>

                                    <span>
                                        {{ $inspection->inspection_number }}
                                    </span>

                                </div>

                            </td>


                            {{-- FORKLIFT --}}

                            <td>

                                <div class="inspection-forklift">

                                    <span class="inspection-forklift-icon">
                                        <i class="fas fa-truck"></i>
                                    </span>

                                    <span>
                                        {{ $inspection->forklift?->forklift_code ?? '-' }}
                                    </span>

                                </div>

                            </td>


                            {{-- OPERATOR --}}

                            <td>

                                <div class="inspection-operator">

                                    <span class="inspection-user-icon">
                                        <i class="fas fa-user"></i>
                                    </span>

                                    <span>
                                        {{ $inspection->operator?->name ?? '-' }}
                                    </span>

                                </div>

                            </td>


                            {{-- SHIFT --}}

                            <td>

                                <span class="inspection-shift">

                                    <i class="far fa-clock"></i>

                                    {{ $inspection->inspection_shift }}

                                </span>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if ($inspection->status === 'Approved')

                                    <span class="inspection-status status-approved">

                                        <i class="fas fa-check-circle"></i>

                                        Approved

                                    </span>

                                @elseif ($inspection->status === 'Rejected')

                                    <span class="inspection-status status-rejected">

                                        <i class="fas fa-times-circle"></i>

                                        Rejected

                                    </span>

                                @elseif ($inspection->status === 'Submitted')

                                    <span class="inspection-status status-submitted">

                                        <i class="fas fa-clock"></i>

                                        Submitted

                                    </span>

                                @else

                                    <span class="inspection-status status-draft">

                                        <i class="fas fa-edit"></i>

                                        {{ $inspection->status }}

                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                        <td class="inspection-action-column">

                            <div class="inspection-action-buttons">

                                {{-- DETAIL --}}
                                <a
                                    href="{{ route('inspections.show', $inspection->id) }}"
                                    class="inspection-detail-button"
                                    title="Lihat Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                    <span>Detail</span>
                                </a>


                                {{-- CHANGE STATUS --}}
                                @if(auth()->user()->isAdmin() && !$inspection->isFinalStatus())

                                    <button
                                        type="button"
                                        class="inspection-status-button"
                                        onclick="openStatusModal({{ $inspection->id }}, '{{ $inspection->status }}')"
                                        title="Ubah Status"
                                    >
                                        <i class="fas fa-edit"></i>
                                        <span>Status</span>
                                    </button>

                                @endif

                            </div>

                        </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="inspection-empty"
                            >

                                <div class="inspection-empty-icon">

                                    <i class="fas fa-inbox"></i>

                                </div>

                                <strong>
                                    Belum ada data inspeksi
                                </strong>

                                <span>
                                    Data pemeriksaan forklift akan tampil di sini.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- =========================================================
     CHANGE STATUS MODAL
========================================================== --}}

@if(auth()->user()->isAdmin())

    <div id="statusModal" class="inspection-modal">

        <div class="inspection-modal-content">

            <div class="inspection-modal-header">

                <div>
                    <h3>Ubah Status Inspeksi</h3>
                    <p>Pilih status baru untuk inspeksi.</p>
                </div>

                <button
                    type="button"
                    class="inspection-modal-close"
                    onclick="closeStatusModal()"
                >
                    &times;
                </button>

            </div>


            <form id="statusForm" method="POST">

                @csrf
                @method('PATCH')

                <div class="form-group">

                    <label for="inspectionStatus">
                        Status Inspeksi
                    </label>

                    <select
                        name="status"
                        id="inspectionStatus"
                        class="form-control"
                        required
                    >

                        <option value="Draft">
                            Draft
                        </option>

                        <option value="Submitted">
                            Submitted
                        </option>

                        <option value="Rejected">
                            Rejected
                        </option>

                        <option value="Approved">
                            Approved
                        </option>

                    </select>

                </div>


                <div class="inspection-modal-actions">

                    <button
                        type="button"
                        class="inspection-detail-button"
                        onclick="closeStatusModal()"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="inspection-status-button"
                    >
                        <i class="fas fa-save"></i>
                        Simpan Status
                    </button>

                </div>

            </form>

        </div>

    </div>

@endif
@push('scripts')

<script>

function openStatusModal(inspectionId, currentStatus)
{
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const status = document.getElementById('inspectionStatus');

    if (!modal || !form || !status) {
        console.error('Status modal tidak ditemukan.');
        return;
    }

    form.action =
        "{{ url('/inspections') }}/" +
        inspectionId +
        "/status";

    status.value = currentStatus;

    modal.style.display = 'flex';
}


function closeStatusModal()
{
    const modal = document.getElementById('statusModal');

    if (modal) {
        modal.style.display = 'none';
    }
}


window.addEventListener('click', function(event)
{
    const modal = document.getElementById('statusModal');

    if (event.target === modal) {
        closeStatusModal();
    }
});

</script>

@endpush

@endsection