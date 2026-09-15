@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('page_title', 'Dashboard Operator')

@section('page_subtitle')
    Kelola pemeriksaan forklift dan pantau riwayat inspeksi Anda.
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Operator</li>
@endsection

@section('content')

<style>
    .driver-dashboard {
        max-width: 1380px;
        margin: 0 auto;
        padding-bottom: 25px;
    }

    .driver-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 24px;
        margin-bottom: 22px;
        border-radius: 18px;
        background:
            linear-gradient(
                135deg,
                #1d4ed8 0%,
                #2563eb 55%,
                #3b82f6 100%
            );
        color: #ffffff;
        box-shadow: 0 10px 28px rgba(37, 99, 235, 0.22);
    }

    .driver-welcome-content {
        min-width: 0;
    }

    .driver-welcome-label {
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        opacity: 0.82;
    }

    .driver-welcome-title {
        margin: 0;
        font-size: 26px;
        line-height: 1.25;
        font-weight: 800;
    }

    .driver-welcome-description {
        margin: 9px 0 0;
        max-width: 620px;
        font-size: 14px;
        line-height: 1.6;
        opacity: 0.9;
    }

    .driver-welcome-icon {
        width: 76px;
        height: 76px;
        flex: 0 0 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.22);
        font-size: 34px;
    }

    .driver-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .driver-stat-card {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
    }

    .driver-stat-icon {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 19px;
        font-weight: 800;
    }

    .stat-blue {
        color: #1d4ed8;
        background: #dbeafe;
    }

    .stat-yellow {
        color: #a16207;
        background: #fef3c7;
    }

    .stat-green {
        color: #15803d;
        background: #dcfce7;
    }

    .stat-red {
        color: #b91c1c;
        background: #fee2e2;
    }

    .driver-stat-label {
        margin-bottom: 4px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    .driver-stat-value {
        color: #0f172a;
        font-size: 25px;
        line-height: 1;
        font-weight: 800;
    }

    .driver-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
        gap: 20px;
        align-items: start;
    }

    .driver-card {
        min-width: 0;
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 17px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
    }

    .driver-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .driver-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #1e293b;
        font-size: 15px;
        font-weight: 800;
    }

    .driver-card-title-icon {
        color: #2563eb;
        font-size: 17px;
    }

    .driver-card-body {
        padding: 20px;
    }

    .driver-profile {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .driver-avatar {
        width: 70px;
        height: 70px;
        flex: 0 0 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 20px;
        color: #1d4ed8;
        background: #dbeafe;
        font-size: 26px;
        font-weight: 800;
    }

    .driver-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .driver-profile-name {
        margin: 0;
        color: #0f172a;
        font-size: 18px;
        font-weight: 800;
        word-break: break-word;
    }

    .driver-profile-role {
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
    }

    .driver-profile-badge {
        display: inline-flex;
        margin-top: 7px;
        padding: 4px 9px;
        border-radius: 999px;
        color: #166534;
        background: #dcfce7;
        font-size: 11px;
        font-weight: 800;
    }

    .driver-info-list {
        display: grid;
        gap: 0;
        margin: 0;
    }

    .driver-info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 11px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .driver-info-row:last-child {
        border-bottom: 0;
    }

    .driver-info-label {
        color: #64748b;
    }

    .driver-info-value {
        color: #1e293b;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    .forklift-panel {
        padding: 18px;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        background:
            linear-gradient(
                135deg,
                #eff6ff 0%,
                #f8fbff 100%
            );
    }

    .forklift-panel-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 16px;
    }

    .forklift-label {
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
    }

    .forklift-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 999px;
        color: #166534;
        background: #dcfce7;
        font-size: 11px;
        font-weight: 800;
    }

    .forklift-code {
        margin: 0;
        color: #172554;
        font-size: 24px;
        font-weight: 800;
    }

    .forklift-description {
        margin: 6px 0 17px;
        color: #64748b;
        font-size: 13px;
    }

    .forklift-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 17px;
    }

    .forklift-detail {
        padding: 11px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.82);
        border: 1px solid #dbeafe;
    }

    .forklift-detail-label {
        margin-bottom: 4px;
        color: #64748b;
        font-size: 11px;
    }

    .forklift-detail-value {
        color: #1e293b;
        font-size: 13px;
        font-weight: 800;
        word-break: break-word;
    }

    .primary-button,
    .secondary-button,
    .detail-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .primary-button {
        width: 100%;
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 5px 14px rgba(37, 99, 235, 0.2);
    }

    .primary-button:hover {
        color: #ffffff;
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
    }

    .secondary-button {
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
    }

    .secondary-button:hover {
        color: #1e40af;
        background: #dbeafe;
    }

    .detail-button {
        color: #1d4ed8;
        border: 1px solid #dbeafe;
        background: #eff6ff;
    }

    .detail-button:hover {
        color: #1e40af;
        background: #dbeafe;
    }

    .empty-state {
        padding: 25px 15px;
        color: #64748b;
        text-align: center;
    }

    .empty-state-icon {
        margin-bottom: 9px;
        color: #94a3b8;
        font-size: 30px;
    }

    .empty-state-title {
        margin-bottom: 5px;
        color: #334155;
        font-size: 14px;
        font-weight: 800;
    }

    .empty-state-description {
        font-size: 12px;
        line-height: 1.6;
    }

    .inspection-list {
        display: grid;
        gap: 12px;
    }

    .inspection-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #ffffff;
    }

    .inspection-item-main {
        min-width: 0;
    }

    .inspection-item-code {
        margin-bottom: 5px;
        color: #1e293b;
        font-size: 13px;
        font-weight: 800;
    }

    .inspection-item-meta {
        color: #64748b;
        font-size: 11px;
        line-height: 1.7;
    }

    .inspection-item-side {
        flex: 0 0 auto;
        text-align: right;
    }

    .inspection-status {
        display: inline-flex;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-draft {
        color: #475569;
        background: #e2e8f0;
    }

    .status-submitted {
        color: #92400e;
        background: #fef3c7;
    }

    .status-approved {
        color: #166534;
        background: #dcfce7;
    }

    .status-rejected {
        color: #991b1b;
        background: #fee2e2;
    }

    .status-default {
        color: #1d4ed8;
        background: #dbeafe;
    }

    .inspection-result {
        display: block;
        margin-top: 5px;
        font-size: 10px;
        font-weight: 800;
    }

    .result-ready {
        color: #15803d;
    }

    .result-not-ready {
        color: #dc2626;
    }

    .quick-actions {
        display: grid;
        gap: 10px;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #1e293b;
        background: #ffffff;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .quick-action:hover {
        color: #1d4ed8;
        border-color: #bfdbfe;
        background: #eff6ff;
    }

    .quick-action-icon {
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        color: #1d4ed8;
        background: #dbeafe;
        font-size: 17px;
    }

    .quick-action-title {
        font-size: 13px;
        font-weight: 800;
    }

    .quick-action-description {
        margin-top: 3px;
        color: #64748b;
        font-size: 11px;
    }

    .quick-action-arrow {
        margin-left: auto;
        color: #94a3b8;
    }

    .driver-note {
        padding: 14px;
        border-left: 4px solid #3b82f6;
        border-radius: 10px;
        color: #1e40af;
        background: #eff6ff;
        font-size: 12px;
        line-height: 1.7;
    }

    @media (max-width: 1100px) {
        .driver-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .driver-main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .driver-welcome {
            align-items: flex-start;
            padding: 19px;
        }

        .driver-welcome-title {
            font-size: 21px;
        }

        .driver-welcome-description {
            font-size: 12px;
        }

        .driver-welcome-icon {
            width: 52px;
            height: 52px;
            flex-basis: 52px;
            border-radius: 15px;
            font-size: 23px;
        }

        .driver-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .driver-stat-card {
            padding: 13px;
            gap: 9px;
        }

        .driver-stat-icon {
            width: 36px;
            height: 36px;
            flex-basis: 36px;
            border-radius: 10px;
            font-size: 15px;
        }

        .driver-stat-label {
            font-size: 10px;
        }

        .driver-stat-value {
            font-size: 21px;
        }

        .driver-card-header,
        .driver-card-body {
            padding: 16px;
        }

        .driver-profile {
            align-items: flex-start;
        }

        .driver-avatar {
            width: 58px;
            height: 58px;
            flex-basis: 58px;
            border-radius: 16px;
        }

        .driver-profile-name {
            font-size: 16px;
        }

        .driver-info-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 3px;
        }

        .driver-info-value {
            text-align: left;
        }

        .forklift-detail-grid {
            grid-template-columns: 1fr;
        }

        .inspection-item {
            align-items: flex-start;
            flex-direction: column;
        }

        .inspection-item-side {
            width: 100%;
            text-align: left;
        }

        .detail-button {
            width: 100%;
        }
    }
</style>

<div class="driver-dashboard">

    {{-- =====================================================
         WELCOME BANNER
    ====================================================== --}}
    <div class="driver-welcome">
        <div class="driver-welcome-content">
            <div class="driver-welcome-label">
                FIMS · Forklift Inspection & Monitoring System
            </div>

            <h2 class="driver-welcome-title">
                Halo, {{ $user->name ?? Auth::user()->name }} 👋
            </h2>

            <p class="driver-welcome-description">
                Selamat datang di Dashboard Operator. Pastikan pemeriksaan
                forklift dilakukan sesuai prosedur sebelum digunakan.
            </p>
        </div>

        <div class="driver-welcome-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
    </div>

    {{-- =====================================================
         STATISTICS
    ====================================================== --}}
    <div class="driver-grid">

        <div class="driver-stat-card">
            <div class="driver-stat-icon stat-blue">
                <i class="fas fa-clipboard-list"></i>
            </div>

            <div>
                <div class="driver-stat-label">Total Inspeksi</div>
                <div class="driver-stat-value">
                    {{ $totalMyInspections ?? 0 }}
                </div>
            </div>
        </div>

        <div class="driver-stat-card">
            <div class="driver-stat-icon stat-yellow">
                <i class="fas fa-clock"></i>
            </div>

            <div>
                <div class="driver-stat-label">Menunggu Review</div>
                <div class="driver-stat-value">
                    {{ $pendingReviewCount ?? 0 }}
                </div>
            </div>
        </div>

        <div class="driver-stat-card">
            <div class="driver-stat-icon stat-green">
                <i class="fas fa-check-circle"></i>
            </div>

            <div>
                <div class="driver-stat-label">Disetujui</div>
                <div class="driver-stat-value">
                    {{ $approvedCount ?? 0 }}
                </div>
            </div>
        </div>

        <div class="driver-stat-card">
            <div class="driver-stat-icon stat-red">
                <i class="fas fa-times-circle"></i>
            </div>

            <div>
                <div class="driver-stat-label">Ditolak</div>
                <div class="driver-stat-value">
                    {{ $rejectedCount ?? 0 }}
                </div>
            </div>
        </div>

    </div>

    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}
    <div class="driver-main-grid">

        {{-- =================================================
             LEFT COLUMN
        ================================================== --}}
        <div>

            {{-- PROFIL OPERATOR --}}
            <div class="driver-card">
                <div class="driver-card-header">
                    <h3 class="driver-card-title">
                        <i class="fas fa-user-circle driver-card-title-icon"></i>
                        Profil Operator
                    </h3>
                </div>

                <div class="driver-card-body">

                    <div class="driver-profile">
                        <div class="driver-avatar">
                            @if (!empty($user->profile_photo))
                                <img
                                    src="{{ asset('storage/' . $user->profile_photo) }}"
                                    alt="Foto {{ $user->name }}"
                                >
                            @else
                                {{ strtoupper(substr($user->name ?? 'OP', 0, 2)) }}
                            @endif
                        </div>

                        <div>
                            <h3 class="driver-profile-name">
                                {{ $user->name ?? '-' }}
                            </h3>

                            <div class="driver-profile-role">
                                {{ $user->role->role_name ?? 'Operator / Driver' }}
                            </div>

                            <span class="driver-profile-badge">
                                <i class="fas fa-circle mr-1"></i>
                                Akun Aktif
                            </span>
                        </div>
                    </div>

                    <div class="driver-info-list">

                        <div class="driver-info-row">
                            <span class="driver-info-label">
                                Nomor Induk Karyawan
                            </span>

                            <span class="driver-info-value">
                                {{ $user->employee_number ?? $user->employee_id ?? '-' }}
                            </span>
                        </div>

                        <div class="driver-info-row">
                            <span class="driver-info-label">
                                Email / Username
                            </span>

                            <span class="driver-info-value">
                                {{ $user->email ?? '-' }}
                            </span>
                        </div>

                        <div class="driver-info-row">
                            <span class="driver-info-label">
                                Lokasi Pengoperasian
                            </span>

                            <span class="driver-info-value">
                                {{ $user->location->location_name ?? $user->location->name ?? '-' }}
                            </span>
                        </div>

                        <div class="driver-info-row">
                            <span class="driver-info-label">
                                Tanggal Dashboard
                            </span>

                            <span class="driver-info-value">
                                {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- INSPEKSI HARI INI --}}
            <div class="driver-card">
                <div class="driver-card-header">
                    <h3 class="driver-card-title">
                        <i class="fas fa-calendar-day driver-card-title-icon"></i>
                        Inspeksi Hari Ini
                    </h3>

                    <span class="badge badge-primary">
                        {{ $todayInspections->count() }} Data
                    </span>
                </div>

                <div class="driver-card-body">

                    @if ($todayInspections->isNotEmpty())

                        <div class="inspection-list">
                            @foreach ($todayInspections as $inspection)

                                @php
                                    $statusClass = match ($inspection->status) {
                                        'Draft' => 'status-draft',
                                        'Submitted' => 'status-submitted',
                                        'Approved' => 'status-approved',
                                        'Rejected' => 'status-rejected',
                                        default => 'status-default',
                                    };

                                    $resultClass = $inspection->overall_result === 'Ready'
                                        ? 'result-ready'
                                        : 'result-not-ready';
                                @endphp

                                <div class="inspection-item">

                                    <div class="inspection-item-main">
                                        <div class="inspection-item-code">
                                            {{ $inspection->forklift->forklift_code ?? 'Forklift tidak tersedia' }}
                                        </div>

                                        <div class="inspection-item-meta">
                                            <div>
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ \Carbon\Carbon::parse($inspection->inspection_date)->translatedFormat('d F Y') }}
                                            </div>

                                            <div>
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $inspection->shift ?? '-' }}
                                            </div>

                                            <div>
                                                <i class="fas fa-hashtag mr-1"></i>
                                                {{ $inspection->inspection_number ?? $inspection->id }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inspection-item-side">
                                        <span class="inspection-status {{ $statusClass }}">
                                            {{ $inspection->status ?? '-' }}
                                        </span>

                                        @if (!empty($inspection->overall_result))
                                            <span class="inspection-result {{ $resultClass }}">
                                                {{ $inspection->overall_result }}
                                            </span>
                                        @endif

                                        <div style="margin-top: 9px;">
                                            <a
                                                href="{{ route('inspections.show', $inspection->id) }}"
                                                class="detail-button"
                                            >
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                        </div>
                                    </div>

                                </div>

                            @endforeach
                        </div>

                    @else

                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-clipboard"></i>
                            </div>

                            <div class="empty-state-title">
                                Belum ada inspeksi hari ini
                            </div>

                            <div class="empty-state-description">
                                Silakan mulai pemeriksaan forklift melalui QR Code
                                atau menu mulai inspeksi.
                            </div>
                        </div>

                    @endif

                </div>
            </div>

        </div>

        {{-- =================================================
             RIGHT COLUMN
        ================================================== --}}
        <div>

            {{-- FORKLIFT HASIL QR --}}
            <div class="driver-card">
                <div class="driver-card-header">
                    <h3 class="driver-card-title">
                        <i class="fas fa-qrcode driver-card-title-icon"></i>
                        Forklift yang Dipindai
                    </h3>
                </div>

                <div class="driver-card-body">

                    @if ($hasSelectedForklift && $selectedForklift)

                        <div class="forklift-panel">

                            <div class="forklift-panel-top">
                                <div class="forklift-label">
                                    QR Code Terdeteksi
                                </div>

                                <span class="forklift-status">
                                    <i class="fas fa-check-circle"></i>
                                    Aktif
                                </span>
                            </div>

                            <h3 class="forklift-code">
                                {{ $selectedForklift->forklift_code }}
                            </h3>

                            <p class="forklift-description">
                                Forklift ini dipilih berdasarkan QR Code yang dipindai.
                            </p>

                            <div class="forklift-detail-grid">

                                <div class="forklift-detail">
                                    <div class="forklift-detail-label">
                                        Jenis Forklift
                                    </div>

                                    <div class="forklift-detail-value">
                                        {{ $selectedForklift->fuel_type ?? $selectedForklift->applicable_fuel_type ?? '-' }}
                                    </div>
                                </div>

                                <div class="forklift-detail">
                                    <div class="forklift-detail-label">
                                        Lokasi
                                    </div>

                                    <div class="forklift-detail-value">
                                        {{ $selectedForklift->location->location_name ?? $selectedForklift->location->name ?? '-' }}
                                    </div>
                                </div>

                            </div>

                            <a
                                href="{{ route('inspections.create', ['forklift_id' => $selectedForklift->id]) }}"
                                class="primary-button"
                            >
                                <i class="fas fa-clipboard-check"></i>
                                Mulai Inspeksi
                            </a>

                        </div>

                    @else

                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>

                            <div class="empty-state-title">
                                Belum ada forklift dari QR Code
                            </div>

                            <div class="empty-state-description">
                                Silakan scan QR Code yang terpasang pada forklift
                                untuk memilih unit yang akan diperiksa.
                            </div>

                            <div style="margin-top: 15px;">
                                <a
                                    href="{{ route('inspections.create') }}"
                                    class="secondary-button"
                                >
                                    <i class="fas fa-search"></i>
                                    Pilih Forklift
                                </a>
                            </div>
                        </div>

                    @endif

                </div>
            </div>

            {{-- QUICK ACTIONS --}}
            <div class="driver-card">
                <div class="driver-card-header">
                    <h3 class="driver-card-title">
                        <i class="fas fa-bolt driver-card-title-icon"></i>
                        Akses Cepat
                    </h3>
                </div>

                <div class="driver-card-body">

                    <div class="quick-actions">

                        <a
                            href="{{ route('inspections.create') }}"
                            class="quick-action"
                        >
                            <div class="quick-action-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>

                            <div>
                                <div class="quick-action-title">
                                    Mulai Inspeksi
                                </div>

                                <div class="quick-action-description">
                                    Pilih forklift dan mulai checklist.
                                </div>
                            </div>

                            <i class="fas fa-chevron-right quick-action-arrow"></i>
                        </a>

                        <a
                            href="{{ route('inspections.index') }}"
                            class="quick-action"
                        >
                            <div class="quick-action-icon">
                                <i class="fas fa-history"></i>
                            </div>

                            <div>
                                <div class="quick-action-title">
                                    Riwayat Inspeksi
                                </div>

                                <div class="quick-action-description">
                                    Lihat seluruh inspeksi milik Anda.
                                </div>
                            </div>

                            <i class="fas fa-chevron-right quick-action-arrow"></i>
                        </a>

                        
                                                <div class="quick-action" style="cursor: default;">
                            <div class="quick-action-icon">
                                <i class="fas fa-user"></i>
                            </div>

                            <div>
                                <div class="quick-action-title">
                                    Profil Operator
                                </div>

                                <div class="quick-action-description">
                                    Informasi akun ditampilkan pada bagian profil dashboard.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- INFORMASI WORKFLOW --}}
            <div class="driver-card">
                <div class="driver-card-header">
                    <h3 class="driver-card-title">
                        <i class="fas fa-info-circle driver-card-title-icon"></i>
                        Informasi Pemeriksaan
                    </h3>
                </div>

                <div class="driver-card-body">

                    <div class="driver-note">
                        Pastikan forklift yang dipilih sesuai dengan QR Code.
                        Periksa seluruh item checklist, isi foto atau catatan
                        jika diwajibkan, kemudian kirim hasil pemeriksaan untuk
                        ditinjau oleh Supervisor.
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection