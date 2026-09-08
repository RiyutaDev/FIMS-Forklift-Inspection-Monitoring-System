@extends('layouts.app')

@section('page_title', 'Approval Inspeksi')
@section('page_subtitle', 'Review dan persetujuan hasil inspeksi forklift')

@section('content')

<div class="approval-page">

    {{-- HEADER --}}
    <div class="approval-header">
        <div>
            <div class="approval-title-row">
                <div class="approval-title-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>

                <div>
                    <h1>Approval Inspeksi</h1>
                    <p>Review inspeksi forklift yang menunggu persetujuan Supervisor.</p>
                </div>
            </div>
        </div>
    </div>


    {{-- SUMMARY --}}
    <div class="approval-summary">

        <div class="approval-stat-card">
            <div class="approval-stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>

            <div>
                <span class="approval-stat-label">Menunggu Approval</span>
                <strong>{{ $inspections->count() }}</strong>
            </div>
        </div>

        <div class="approval-stat-card">
            <div class="approval-stat-icon forklift">
                <i class="fas fa-truck-moving"></i>
            </div>

            <div>
                <span class="approval-stat-label">Inspeksi Ditampilkan</span>
                <strong>{{ $inspections->count() }}</strong>
            </div>
        </div>

        <div class="approval-stat-card">
            <div class="approval-stat-icon review">
                <i class="fas fa-search"></i>
            </div>

            <div>
                <span class="approval-stat-label">Perlu Ditinjau</span>
                <strong>{{ $inspections->count() }}</strong>
            </div>
        </div>

    </div>


    {{-- TABLE CARD --}}
    <div class="approval-card">

        <div class="approval-card-header">

            <div>
                <h2>
                    <i class="fas fa-list-check"></i>
                    Daftar Inspeksi
                </h2>

                <p>
                    Inspeksi dengan status <strong>Submitted</strong> menunggu tindakan Anda.
                </p>
            </div>

            <div class="approval-filter">
                <span>
                    <i class="fas fa-filter"></i>
                    Pending
                </span>
            </div>

        </div>


        <div class="approval-table-wrapper">

            <table class="approval-table">

                <thead>
                    <tr>
                        <th width="55">No</th>
                        <th>Nomor Inspeksi</th>
                        <th>Forklift</th>
                        <th>Operator</th>
                        <th>Shift</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($inspections as $inspection)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <span class="row-number">
                                    {{ $loop->iteration }}
                                </span>
                            </td>


                            {{-- INSPECTION NUMBER --}}
                            <td>
                                <div class="inspection-number">
                                    <span class="inspection-icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </span>

                                    <div>
                                        <strong>
                                            {{ $inspection->inspection_number }}
                                        </strong>

                                        <small>
                                            ID Inspeksi
                                        </small>
                                    </div>
                                </div>
                            </td>


                            {{-- FORKLIFT --}}
                            <td>
                                <div class="data-primary">
                                    <i class="fas fa-truck-moving"></i>
                                    {{ $inspection->forklift?->forklift_code ?? '-' }}
                                </div>
                            </td>


                            {{-- OPERATOR --}}
                            <td>
                                <div class="operator-info">

                                    <div class="operator-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $inspection->operator?->name ?? '-' }}
                                        </strong>

                                        <small>
                                            Operator
                                        </small>
                                    </div>

                                </div>
                            </td>


                            {{-- SHIFT --}}
                            <td>
                                <span class="shift-badge">
                                    {{ $inspection->shift ?? '-' }}
                                </span>
                            </td>


                            {{-- DATE --}}
                            <td>
                                <div class="date-info">

                                    <strong>
                                        {{ $inspection->inspection_date
                                            ? \Carbon\Carbon::parse($inspection->inspection_date)->format('d M Y')
                                            : '-' }}
                                    </strong>

                                    @if($inspection->submitted_at)
                                        <small>
                                            {{ \Carbon\Carbon::parse($inspection->submitted_at)->format('H:i') }}
                                        </small>
                                    @endif

                                </div>
                            </td>


                            {{-- STATUS --}}
                            <td>
                                @if($inspection->status === 'Submitted')

                                    <span class="status-badge submitted">
                                        <span class="status-dot"></span>
                                        Menunggu Approval
                                    </span>

                                @elseif($inspection->status === 'Approved')

                                    <span class="status-badge approved">
                                        <span class="status-dot"></span>
                                        Approved
                                    </span>

                                @elseif($inspection->status === 'Rejected')

                                    <span class="status-badge rejected">
                                        <span class="status-dot"></span>
                                        Rejected
                                    </span>

                                @else

                                    <span class="status-badge">
                                        {{ $inspection->status }}
                                    </span>

                                @endif
                            </td>


                            {{-- ACTION --}}
                            <td>
                                <a href="{{ route('inspections.show', $inspection) }}"
                                   class="approval-action"
                                   title="Review Inspeksi">

                                    <i class="fas fa-eye"></i>
                                    <span>Review</span>

                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">

                                <div class="approval-empty">

                                    <div class="empty-icon">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>

                                    <h3>Tidak Ada Inspeksi Pending</h3>

                                    <p>
                                        Semua inspeksi telah diproses.
                                        Tidak ada inspeksi yang menunggu approval saat ini.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@push('styles')
<style>

    /* ================================
       APPROVAL PAGE
    ================================= */

    .approval-page {
        width: 100%;
    }


    /* HEADER */

    .approval-header {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 22px 24px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .approval-title-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .approval-title-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 19px;
    }

    .approval-header h1 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .approval-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
    }


    /* SUMMARY */

    .approval-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 18px;
    }

    .approval-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .approval-stat-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 16px;
    }

    .approval-stat-icon.pending {
        background: #fff7ed;
        color: #f59e0b;
    }

    .approval-stat-icon.forklift {
        background: #eff6ff;
        color: #2563eb;
    }

    .approval-stat-icon.review {
        background: #ecfdf5;
        color: #10b981;
    }

    .approval-stat-label {
        display: block;
        margin-bottom: 3px;
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .approval-stat-card strong {
        display: block;
        font-size: 22px;
        line-height: 1;
        color: #0f172a;
        font-weight: 700;
    }


    /* MAIN CARD */

    .approval-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .approval-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .approval-card-header h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    .approval-card-header h2 i {
        margin-right: 7px;
        color: #2563eb;
    }

    .approval-card-header p {
        margin: 5px 0 0;
        font-size: 12px;
        color: #64748b;
    }

    .approval-card-header p strong {
        color: #475569;
    }

    .approval-filter {
        flex-shrink: 0;
    }

    .approval-filter span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 11px;
        font-weight: 600;
    }


    /* TABLE */

    .approval-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .approval-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .approval-table thead {
        background: #f8fafc;
    }

    .approval-table th {
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .approval-table td {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
        font-size: 12px;
    }

    .approval-table tbody tr {
        transition: background .15s ease;
    }

    .approval-table tbody tr:hover {
        background: #f8fafc;
    }

    .approval-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* ROW NUMBER */

    .row-number {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 600;
    }


    /* INSPECTION */

    .inspection-number {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .inspection-icon {
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

    .inspection-number strong {
        display: block;
        color: #1e293b;
        font-size: 12px;
        font-weight: 700;
    }

    .inspection-number small {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9px;
    }


    /* DATA */

    .data-primary {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #334155;
        font-weight: 600;
    }

    .data-primary i {
        color: #64748b;
        font-size: 11px;
    }


    /* OPERATOR */

    .operator-info {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .operator-avatar {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
    }

    .operator-info strong {
        display: block;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .operator-info small {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9px;
    }


    /* SHIFT */

    .shift-badge {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        font-size: 10px;
        font-weight: 600;
    }


    /* DATE */

    .date-info strong {
        display: block;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .date-info small {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 9px;
    }


    /* STATUS */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.submitted {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-badge.approved {
        background: #ecfdf5;
        color: #047857;
    }

    .status-badge.rejected {
        background: #fef2f2;
        color: #dc2626;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }


    /* ACTION */

    .approval-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 7px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all .15s ease;
    }

    .approval-action:hover {
        background: #2563eb;
        color: #ffffff;
    }


    /* EMPTY */

    .approval-empty {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #ecfdf5;
        color: #10b981;
        font-size: 22px;
    }

    .approval-empty h3 {
        margin: 0;
        color: #334155;
        font-size: 14px;
        font-weight: 700;
    }

    .approval-empty p {
        max-width: 400px;
        margin: 6px auto 0;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.6;
    }


    /* RESPONSIVE */

    @media (max-width: 900px) {

        .approval-summary {
            grid-template-columns: 1fr;
        }

        .approval-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

    }


    @media (max-width: 576px) {

        .approval-header {
            padding: 18px;
        }

        .approval-title-icon {
            width: 42px;
            height: 42px;
        }

        .approval-header h1 {
            font-size: 17px;
        }

        .approval-header p {
            font-size: 11px;
        }

        .approval-stat-card {
            padding: 15px;
        }

        .approval-card-header {
            padding: 16px;
        }

    }

</style>
@endpush

@endsection