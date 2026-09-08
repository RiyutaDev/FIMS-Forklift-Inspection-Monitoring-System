@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Administrator')
@section('page_subtitle', 'Statistik Operasional & Analitik Sistem FIMS')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Admin</li>
@endsection

@section('content')

<style>
    /* =========================================================
       ADMIN DASHBOARD
    ========================================================= */

    .admin-dashboard {
        color: #1e293b;
    }

    /* =========================================================
       HERO
    ========================================================= */

    .dashboard-hero {
        position: relative;
        min-height: 150px;

        margin-bottom: 20px;

        border-radius: 14px;

        overflow: hidden;

        background:
            linear-gradient(
                90deg,
                rgba(15, 23, 42, 0.95) 0%,
                rgba(15, 23, 42, 0.82) 42%,
                rgba(15, 23, 42, 0.45) 100%
            ),
            url('{{ asset('images/login/forklift-background.png') }}')
            center 58% / cover no-repeat;

        box-shadow:
            0 8px 24px rgba(15, 23, 42, 0.12);
    }

    .dashboard-hero-content {
        position: relative;
        z-index: 2;

        min-height: 150px;

        display: flex;
        align-items: center;

        padding: 25px 28px;
    }

    .dashboard-hero-text {
        max-width: 580px;
    }

    .dashboard-hero-title {
        margin: 0 0 5px;

        color: #ffffff;

        font-family: 'Montserrat', sans-serif;

        font-size: 28px;
        font-weight: 800;

        line-height: 1.2;
    }

    .dashboard-hero-subtitle {
        margin: 0;

        color: rgba(255, 255, 255, 0.82);

        font-size: 14px;
    }

    .dashboard-hero-tagline {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        margin-top: 14px;

        color: #bae6fd;

        font-family: 'Montserrat', sans-serif;

        font-size: 10px;
        font-weight: 700;

        letter-spacing: 1.5px;

        text-transform: uppercase;
    }

    .dashboard-hero-tagline::before {
        content: "";

        width: 28px;
        height: 2px;

        background: #0ea5e9;

        border-radius: 10px;
    }

    /* =========================================================
       KPI CARDS
    ========================================================= */

    .dashboard-kpi {
        position: relative;

        min-height: 158px;

        margin-bottom: 20px;

        border: none;

        border-radius: 13px;

        overflow: hidden;

        color: #ffffff;

        box-shadow:
            0 6px 18px rgba(15, 23, 42, 0.10);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .dashboard-kpi:hover {
        transform: translateY(-2px);

        box-shadow:
            0 10px 25px rgba(15, 23, 42, 0.14);
    }

    .dashboard-kpi .inner {
        position: relative;
        z-index: 2;

        padding: 19px 20px 14px;
    }

    .dashboard-kpi h3 {
        margin: 0 0 5px;

        font-family: 'Montserrat', sans-serif;

        font-size: 29px;
        font-weight: 800;

        line-height: 1.1;
    }

    .dashboard-kpi h3 sup {
        font-size: 13px;
        font-weight: 500;
    }

    .dashboard-kpi p {
        margin: 0 0 2px;

        font-family: 'Montserrat', sans-serif;

        font-size: 14px;
        font-weight: 700;
    }

    .dashboard-kpi .kpi-description {
        font-size: 11px;

        opacity: 0.82;
    }

    .dashboard-kpi .icon {
        position: absolute;

        top: 20px;
        right: 18px;

        z-index: 1;

        opacity: 0.18;
    }

    .dashboard-kpi .icon i {
        font-size: 62px;
    }

    .dashboard-kpi .small-box-footer {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        height: 37px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(0, 0, 0, 0.10);

        color: #ffffff;

        font-size: 11px;
        font-weight: 600;

        text-decoration: none;

        transition: background 0.2s ease;
    }

    .dashboard-kpi .small-box-footer:hover {
        background: rgba(0, 0, 0, 0.18);

        color: #ffffff;
    }

    .dashboard-kpi .small-box-footer i {
        margin-left: 6px;
    }

    .kpi-forklift {
        background:
            linear-gradient(
                135deg,
                #0ea5e9 0%,
                #0284c7 55%,
                #0369a1 100%
            );
    }

    .kpi-users {
        background:
            linear-gradient(
                135deg,
                #10b981 0%,
                #059669 55%,
                #047857 100%
            );
    }

    .kpi-inspection {
        background:
            linear-gradient(
                135deg,
                #2563eb 0%,
                #1d4ed8 55%,
                #1e40af 100%
            );
    }

    .kpi-approval {
        background:
            linear-gradient(
                135deg,
                #f59e0b 0%,
                #d97706 55%,
                #b45309 100%
            );
    }

    /* =========================================================
       DASHBOARD CARD
    ========================================================= */

    .dashboard-card {
        background: #ffffff;

        border: 1px solid #e2e8f0;

        border-radius: 13px;

        overflow: hidden;

        box-shadow:
            0 4px 16px rgba(15, 23, 42, 0.06);

        margin-bottom: 20px;
    }

    .dashboard-card-header {
        min-height: 58px;

        padding: 16px 19px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        border-bottom: 1px solid #f1f5f9;

        background: #ffffff;
    }

    .dashboard-card-title {
        display: flex;

        align-items: center;

        margin: 0;

        color: #0f172a;

        font-family: 'Montserrat', sans-serif;

        font-size: 15px;
        font-weight: 700;
    }

    .dashboard-card-title i {
        margin-right: 10px;

        font-size: 17px;

        color: #2563eb;
    }

    .dashboard-card-tools {
        display: flex;

        align-items: center;

        gap: 8px;
    }

    .dashboard-period {
        display: inline-flex;

        align-items: center;

        padding: 7px 10px;

        border: 1px solid #e2e8f0;

        border-radius: 7px;

        background: #f8fafc;

        color: #64748b;

        font-size: 11px;
    }

    .dashboard-card-body {
        padding: 20px;
    }

    /* =========================================================
       CHART
    ========================================================= */

    .chart-wrapper {
        position: relative;

        width: 100%;

        height: 290px;
    }

    .chart-wrapper-small {
        position: relative;

        width: 100%;

        height: 250px;
    }

    .chart-wrapper canvas,
    .chart-wrapper-small canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .chart-legend {
        display: flex;

        justify-content: center;

        align-items: center;

        gap: 20px;

        margin-top: 12px;

        color: #64748b;

        font-size: 11px;
    }

    .chart-legend-item {
        display: inline-flex;

        align-items: center;

        gap: 6px;
    }

    .chart-legend-dot {
        width: 9px;
        height: 9px;

        border-radius: 50%;
    }

    .chart-legend-ready {
        background: #10b981;
    }

    .chart-legend-not-ready {
        background: #ef4444;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .inspection-table {
        margin: 0;

        width: 100%;

        border-collapse: separate;

        border-spacing: 0;
    }

    .inspection-table thead th {
        padding: 12px 14px;

        border-top: none;
        border-bottom: 1px solid #e2e8f0;

        background: #f8fafc;

        color: #475569;

        font-family: 'Montserrat', sans-serif;

        font-size: 10px;
        font-weight: 700;

        letter-spacing: 0.3px;

        white-space: nowrap;
    }

    .inspection-table tbody td {
        padding: 12px 14px;

        border-bottom: 1px solid #f1f5f9;

        color: #475569;

        font-size: 12px;

        vertical-align: middle;

        white-space: nowrap;
    }

    .inspection-table tbody tr:last-child td {
        border-bottom: none;
    }

    .inspection-table tbody tr {
        transition: background 0.15s ease;
    }

    .inspection-table tbody tr:hover {
        background: #f8fafc;
    }

    .inspection-number {
        color: #2563eb;

        font-weight: 700;
    }

    .forklift-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 5px 9px;

        border-radius: 20px;

        background: #f1f5f9;

        color: #475569;

        font-size: 10px;

        font-weight: 700;
    }

    .forklift-badge i {
        color: #2563eb;
    }

    .status-badge {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 5px;

        min-width: 76px;

        padding: 5px 9px;

        border-radius: 20px;

        font-size: 10px;

        font-weight: 700;
    }

    .status-ready {
        background: #d1fae5;

        color: #047857;
    }

    .status-not-ready {
        background: #fee2e2;

        color: #b91c1c;
    }

    .status-approved {
        background: #d1fae5;

        color: #047857;
    }

    .status-submitted {
        background: #fef3c7;

        color: #b45309;
    }

    .status-rejected {
        background: #fee2e2;

        color: #b91c1c;
    }

    .btn-detail {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 5px 9px;

        border: 1px solid #bfdbfe;

        border-radius: 6px;

        background: #ffffff;

        color: #2563eb;

        font-size: 10px;

        font-weight: 600;

        text-decoration: none;

        transition: all 0.15s ease;
    }

    .btn-detail:hover {
        background: #eff6ff;

        color: #1d4ed8;

        border-color: #93c5fd;

        text-decoration: none;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 40px 20px;

        text-align: center;

        color: #94a3b8;
    }

    .empty-state i {
        display: block;

        margin-bottom: 10px;

        font-size: 34px;
    }

    .empty-state p {
        margin: 0;

        font-size: 12px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .dashboard-hero-title {
            font-size: 24px;
        }

        .chart-wrapper {
            height: 270px;
        }

        .chart-wrapper-small {
            height: 250px;
        }
    }

    @media (max-width: 767px) {

        .dashboard-hero {
            min-height: 145px;

            background-position: 65% center;
        }

        .dashboard-hero-content {
            min-height: 145px;

            padding: 22px 20px;
        }

        .dashboard-hero-title {
            font-size: 22px;
        }

        .dashboard-hero-subtitle {
            font-size: 12px;
        }

        .dashboard-hero-tagline {
            font-size: 8px;
        }

        .dashboard-kpi {
            min-height: 145px;
        }

        .dashboard-card-header {
            padding: 14px;
        }

        .dashboard-card-body {
            padding: 14px;
        }

        .inspection-table {
            min-width: 850px;
        }

        .chart-wrapper {
            height: 250px;
        }
    }

    @media (max-width: 480px) {

        .dashboard-hero {
            border-radius: 10px;
        }

        .dashboard-hero-title {
            font-size: 20px;
        }

        .dashboard-hero-tagline {
            display: none;
        }

        .dashboard-kpi h3 {
            font-size: 25px;
        }

        .dashboard-card-title {
            font-size: 13px;
        }
    }
</style>


<div class="admin-dashboard">

    {{-- =========================================================
         HERO / HEADER DASHBOARD
    ========================================================== --}}

    <div class="dashboard-hero">

        <div class="dashboard-hero-content">

            <div class="dashboard-hero-text">

                <h1 class="dashboard-hero-title">
                    Dashboard Administrator
                </h1>

                <p class="dashboard-hero-subtitle">
                    Statistik Operasional &amp; Analitik Sistem FIMS
                </p>

                <span class="dashboard-hero-tagline">
                    Reliable Equipment &amp; Safer Operations
                </span>

            </div>

        </div>

    </div>


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}

    <div class="row">

        {{-- Forklift Aktif --}}

        <div class="col-xl-3 col-md-6 col-12">

            <div class="dashboard-kpi kpi-forklift">

                <div class="inner">

                    <h3>
                        {{ $activeForklifts }}

                        <sup>
                            / {{ $totalForklifts }}
                        </sup>
                    </h3>

                    <p>
                        Forklift Aktif
                    </p>

                    <span class="kpi-description">
                        Unit Siap Beroperasi
                    </span>

                </div>

                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>

                <a
                    href="{{ route('master.forklifts.index') }}"
                    class="small-box-footer"
                >
                    Kelola Master Forklift

                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>

        </div>


        {{-- Pengguna Aktif --}}

        <div class="col-xl-3 col-md-6 col-12">

            <div class="dashboard-kpi kpi-users">

                <div class="inner">

                    <h3>
                        {{ $activeUsers }}

                        <sup>
                            / {{ $totalUsers }}
                        </sup>
                    </h3>

                    <p>
                        Pengguna Aktif
                    </p>

                    <span class="kpi-description">
                        Driver, Supervisor &amp; Admin
                    </span>

                </div>

                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>

                <a
                    href="{{ route('master.users.index') }}"
                    class="small-box-footer"
                >
                    Kelola User

                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>

        </div>


        {{-- Inspeksi Hari Ini --}}

        <div class="col-xl-3 col-md-6 col-12">

            <div class="dashboard-kpi kpi-inspection">

                <div class="inner">

                    <h3>
                        {{ $totalInspectionsToday }}
                    </h3>

                    <p>
                        Inspeksi Hari Ini
                    </p>

                    <span class="kpi-description">
                        Tanggal: {{ date('d M Y') }}
                    </span>

                </div>

                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>

                <a
                    href="{{ route('inspections.index') }}"
                    class="small-box-footer"
                >
                    Lihat Seluruh Inspeksi

                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>

        </div>


        {{-- Pending Approval --}}

        <div class="col-xl-3 col-md-6 col-12">

            <div class="dashboard-kpi kpi-approval">

                <div class="inner">

                    <h3>
                        {{ $pendingApprovalsAll }}
                    </h3>

                    <p>
                        Menunggu Approval
                    </p>

                    <span class="kpi-description">
                        Perlu Review Supervisor
                    </span>

                </div>

                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>

                <a
                    href="{{ route('approvals.index') }}"
                    class="small-box-footer"
                >
                    Proses Approval

                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CHART SECTION
    ========================================================== --}}

    <div class="row">

        {{-- Trend Inspection --}}

        <div class="col-lg-8 col-12">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h3 class="dashboard-card-title">

                        <i class="fas fa-chart-line"></i>

                        Analitik Tren Inspeksi Harian

                    </h3>

                    <div class="dashboard-card-tools">

                        <span class="dashboard-period">

                            <i class="far fa-calendar-alt mr-1"></i>

                            Bulan Ini

                        </span>

                    </div>

                </div>

                <div class="dashboard-card-body">

                    <div class="chart-wrapper">

                        <canvas
                            id="inspectionTrendChart"
                        ></canvas>

                    </div>

                </div>

            </div>

        </div>


        {{-- Status Forklift --}}

        <div class="col-lg-4 col-12">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h3 class="dashboard-card-title">

                        <i class="fas fa-chart-pie"></i>

                        Status Kelayakan Unit

                    </h3>

                </div>

                <div class="dashboard-card-body">

                    <div class="chart-wrapper-small">

                        <canvas
                            id="forkliftStatusChart"
                        ></canvas>

                    </div>

                    <div class="chart-legend">

                        <span class="chart-legend-item">

                            <span class="chart-legend-dot chart-legend-ready"></span>

                            Ready

                        </span>

                        <span class="chart-legend-item">

                            <span class="chart-legend-dot chart-legend-not-ready"></span>

                            Not Ready

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RECENT INSPECTION TRANSACTIONS
    ========================================================== --}}

    <div class="row">

        <div class="col-lg-12 col-12">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h3 class="dashboard-card-title">

                        <i class="fas fa-history"></i>

                        5 Transaksi Inspeksi Terakhir

                    </h3>

                    <div class="dashboard-card-tools">

                        <a
                            href="{{ route('inspections.index') }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            <i class="fas fa-external-link-alt mr-1"></i>

                            Lihat Semua

                        </a>

                    </div>

                </div>


                <div class="card-body p-0 table-responsive">

                    <table class="inspection-table">

                        <thead>

                            <tr>

                                <th style="width: 5%;">
                                    #
                                </th>

                                <th>
                                    No. Inspeksi
                                </th>

                                <th>
                                    Kode Forklift
                                </th>

                                <th>
                                    Operator / Driver
                                </th>

                                <th>
                                    Tanggal &amp; Shift
                                </th>

                                <th class="text-center">
                                    Hasil Unit
                                </th>

                                <th class="text-center">
                                    Status Review
                                </th>

                                <th
                                    class="text-center"
                                    style="width: 10%;"
                                >
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($latestInspections as $index => $inspection)

                                <tr>

                                    {{-- Number --}}

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- Inspection Number --}}

                                    <td>

                                        <span class="inspection-number">
                                            {{ $inspection->inspection_number }}
                                        </span>

                                    </td>


                                    {{-- Forklift --}}

                                    <td>

                                        <span class="forklift-badge">

                                            <i class="fas fa-truck"></i>

                                            {{ $inspection->forklift->forklift_code }}

                                        </span>

                                    </td>


                                    {{-- Operator --}}

                                    <td>

                                        {{ $inspection->operator->name ?? '-' }}

                                    </td>


                                    {{-- Date & Shift --}}

                                    <td>

                                        {{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d/m/Y') }}

                                        <span class="text-muted ml-1">

                                            ({{ $inspection->inspection_shift }})

                                        </span>

                                    </td>


                                    {{-- Overall Result --}}

                                    <td class="text-center">

                                        @if($inspection->overall_result === 'Ready')

                                            <span class="status-badge status-ready">

                                                <i class="fas fa-check-circle"></i>

                                                Ready

                                            </span>

                                        @else

                                            <span class="status-badge status-not-ready">

                                                <i class="fas fa-times-circle"></i>

                                                Not Ready

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Review Status --}}

                                    <td class="text-center">

                                        @if($inspection->status === 'Approved')

                                            <span class="status-badge status-approved">
                                                Approved
                                            </span>

                                        @elseif($inspection->status === 'Rejected')

                                            <span class="status-badge status-rejected">
                                                Rejected
                                            </span>

                                        @else

                                            <span class="status-badge status-submitted">
                                                Submitted
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-center">

                                        <a
                                            href="{{ route('inspections.show', $inspection->id) }}"
                                            class="btn-detail"
                                            title="Detail"
                                        >

                                            <i class="fas fa-eye"></i>

                                            Detail

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="p-0"
                                    >

                                        <div class="empty-state">

                                            <i class="fas fa-inbox"></i>

                                            <p>
                                                Belum ada data transaksi inspeksi.
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

    </div>

</div>


@endsection


{{-- =============================================================
     CHART.JS
============================================================= --}}

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       CHART 1
       TREN INSPEKSI HARIAN
    ========================================================= */

    const trendCanvas =
        document.getElementById('inspectionTrendChart');

    if (trendCanvas) {

        const ctxTrend =
            trendCanvas.getContext('2d');

        new Chart(ctxTrend, {

            type: 'bar',

            data: {

                labels: [
                    'Sen',
                    'Sel',
                    'Rab',
                    'Kam',
                    'Jum',
                    'Sab',
                    'Min'
                ],

                datasets: [

                    {
                        label: 'Inspeksi Disetujui (Approved)',

                        backgroundColor: '#10b981',

                        borderColor: '#059669',

                        borderWidth: 0,

                        borderRadius: 6,

                        data: [
                            12,
                            19,
                            15,
                            17,
                            14,
                            8,
                            5
                        ]
                    },

                    {
                        label: 'Inspeksi Ditolak (Rejected)',

                        backgroundColor: '#ef4444',

                        borderColor: '#dc2626',

                        borderWidth: 0,

                        borderRadius: 6,

                        data: [
                            1,
                            2,
                            0,
                            1,
                            3,
                            0,
                            0
                        ]
                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: {

                    legend: {

                        position: 'top',

                        labels: {

                            usePointStyle: true,

                            pointStyle: 'rectRounded',

                            padding: 18,

                            font: {
                                size: 11
                            }

                        }

                    },

                    tooltip: {

                        backgroundColor: '#0f172a',

                        titleFont: {
                            size: 12
                        },

                        bodyFont: {
                            size: 11
                        },

                        padding: 10,

                        cornerRadius: 8
                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 10
                            }
                        }

                    },

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0,

                            color: '#64748b',

                            font: {
                                size: 10
                            }

                        },

                        grid: {
                            color: '#e2e8f0'
                        }

                    }

                }

            }

        });

    }


    /* =========================================================
       CHART 2
       STATUS KELAYAKAN FORKLIFT
    ========================================================= */

    const statusCanvas =
        document.getElementById('forkliftStatusChart');

    if (statusCanvas) {

        const ctxStatus =
            statusCanvas.getContext('2d');

        new Chart(ctxStatus, {

            type: 'doughnut',

            data: {

                labels: [
                    'Ready (Layak)',
                    'Not Ready (Rusak)'
                ],

                datasets: [

                    {

                        data: [
                            {{ $activeForklifts }},
                            {{ max(0, $totalForklifts - $activeForklifts) }}
                        ],

                        backgroundColor: [
                            '#10b981',
                            '#ef4444'
                        ],

                        borderColor: '#ffffff',

                        borderWidth: 4,

                        hoverOffset: 5

                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '70%',

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        backgroundColor: '#0f172a',

                        padding: 10,

                        cornerRadius: 8

                    }

                }

            }

        });

    }

});

</script>

@endpush