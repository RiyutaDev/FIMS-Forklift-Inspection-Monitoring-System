<aside class="main-sidebar sidebar-dark-primary elevation-4">

    {{-- =========================================================
         BRAND
    ========================================================== --}}

    <a
        href="{{ route('dashboard.index') }}"
        class="brand-link"
    >

        <div class="fims-brand">

            <div class="fims-brand-logo">

                <img
                    src="{{ asset('images/logo/forklift-logo.png') }}"
                    alt="FIMS"
                >

            </div>

            <div class="fims-brand-text">

                <span class="fims-brand-name">
                    FIMS
                </span>

                <span class="fims-brand-subtitle">
                    Forklift Inspection
                </span>

                <span class="fims-brand-subtitle">
                    &amp; Monitoring System
                </span>

            </div>

        </div>

    </a>


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <div class="sidebar">

        {{-- =====================================================
             USER PANEL
        ====================================================== --}}

        <div class="user-panel-custom">

            <div class="user-avatar">

                @if(auth()->user()->photo_url)

                    <img
                        src="{{ auth()->user()->photo_url }}"
                        alt="User"
                    >

                @else

                    <div class="user-avatar-placeholder">

                        <i class="fas fa-user"></i>

                    </div>

                @endif

            </div>


            <div class="user-info">

                <div class="user-name">

                    {{ auth()->user()->name }}

                </div>

                <span class="user-role">

                    {{ auth()->user()->role?->role_name ?? 'User' }}

                </span>

            </div>

        </div>


        {{-- =====================================================
             NAVIGATION
        ====================================================== --}}

        <nav class="fims-sidebar-nav">

            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >

                {{-- =================================================
                     DASHBOARD
                ================================================== --}}

                <li class="nav-item">

                    <a
                        href="{{ route('dashboard.index') }}"
                        class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}"
                    >

                        <i class="nav-icon fas fa-tachometer-alt"></i>

                        <p>
                            Dashboard
                        </p>

                    </a>

                </li>


                {{-- =================================================
                     DRIVER / OPERATOR
                ================================================== --}}

                @if(auth()->user()->isDriver())

                    <li class="nav-header">
                        OPERASIONAL INSPEKSI
                    </li>


                    {{-- Inspeksi Baru --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('inspections.create') }}"
                            class="nav-link {{ request()->routeIs('inspections.create') || request()->routeIs('inspections.checklist') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-qrcode"></i>

                            <p>
                                Inspeksi Baru
                            </p>

                        </a>

                    </li>


                    {{-- Riwayat --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('inspections.index') }}"
                            class="nav-link {{ request()->routeIs('inspections.index') || request()->routeIs('inspections.show') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-history"></i>

                            <p>
                                Riwayat Saya
                            </p>

                        </a>

                    </li>

                @endif


                {{-- =================================================
                     SUPERVISOR / ADMIN
                ================================================== --}}

                @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())

                    <li class="nav-header">
                        VALIDASI &amp; MONITORING
                    </li>


                    {{-- Approval --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('approvals.index') }}"
                            class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-clipboard-check"></i>

                            <p>

                                Approval Inspeksi

                                @php

                                    $pendingCount =
                                        \App\Models\Inspection::where(
                                            'status',
                                            'Submitted'
                                        )->count();

                                @endphp


                                @if($pendingCount > 0)

                                    <span class="right fims-menu-badge">
                                        {{ $pendingCount }}
                                    </span>

                                @endif

                            </p>

                        </a>

                    </li>


                    {{-- Semua Inspeksi --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('inspections.index') }}"
                            class="nav-link {{ request()->routeIs('inspections.index') || request()->routeIs('inspections.show') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-list-alt"></i>

                            <p>
                                Seluruh Data Inspeksi
                            </p>

                        </a>

                    </li>


                    {{-- Reports --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('reports.index') }}"
                            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-file-pdf"></i>

                            <p>
                                Laporan &amp; Export PDF
                            </p>

                        </a>

                    </li>

                @endif


                {{-- =================================================
                     ADMIN MASTER DATA
                ================================================== --}}

                @if(auth()->user()->isAdmin())

                    <li class="nav-header">
                        MASTER DATA
                    </li>


                    {{-- Master User --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('master.users.index') }}"
                            class="nav-link {{ request()->routeIs('master.users.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-users"></i>

                            <p>
                                Master User
                            </p>

                        </a>

                    </li>


                    {{-- Master Forklift --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('master.forklifts.index') }}"
                            class="nav-link {{ request()->routeIs('master.forklifts.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-truck"></i>

                            <p>
                                Master Forklift &amp; QR
                            </p>

                        </a>

                    </li>


                    {{-- Master Inspection Items --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('master.inspection-items.index') }}"
                            class="nav-link {{ request()->routeIs('master.inspection-items.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-tasks"></i>

                            <p>
                                Master Item Checklist
                            </p>

                        </a>

                    </li>

                @endif


                {{-- =================================================
                     ACCOUNT
                ================================================== --}}

                <li class="nav-header">
                    AKUN
                </li>


                <li class="nav-item">

                    <a
                        href="#"
                        class="nav-link fims-logout-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                    >

                        <i class="nav-icon fas fa-sign-out-alt"></i>

                        <p>
                            Keluar
                        </p>

                    </a>


                    <form
                        id="logout-form-sidebar"
                        action="{{ route('logout') }}"
                        method="POST"
                        class="d-none"
                    >

                        @csrf

                    </form>

                </li>

            </ul>

        </nav>

    </div>

</aside>


{{-- =============================================================
     SIDEBAR CUSTOM STYLE
============================================================= --}}

<style>

    /* =========================================================
       MAIN SIDEBAR
    ========================================================= */

    .main-sidebar {
        background:
            linear-gradient(
                180deg,
                #0f172a 0%,
                #111c31 55%,
                #0f172a 100%
            ) !important;

        width: 250px !important;

        min-height: 100vh;

        border-right: 1px solid rgba(255, 255, 255, 0.05);

        box-shadow:
            4px 0 20px rgba(15, 23, 42, 0.12) !important;
    }


    /* =========================================================
       BRAND
    ========================================================= */

    .brand-link {
        height: 82px !important;

        padding: 13px 16px !important;

        display: flex !important;

        align-items: center !important;

        justify-content: flex-start !important;

        border-bottom:
            1px solid rgba(255, 255, 255, 0.08) !important;

        background:
            rgba(15, 23, 42, 0.35) !important;

        text-decoration: none !important;

        overflow: hidden;
    }


    .fims-brand {
        width: 100%;

        display: flex;

        align-items: center;

        gap: 11px;
    }


    .fims-brand-logo {
        width: 47px;
        height: 47px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        background: #ffffff;

        border-radius: 10px;

        padding: 4px;

        box-shadow:
            0 4px 12px rgba(0, 0, 0, 0.20);

        overflow: hidden;
    }


    .fims-brand-logo img {
        width: 100%;
        height: 100%;

        object-fit: contain;

        display: block;
    }


    .fims-brand-text {
        min-width: 0;

        display: flex;

        flex-direction: column;

        line-height: 1.1;
    }


    .fims-brand-name {
        color: #ffffff;

        font-family: 'Montserrat', sans-serif;

        font-size: 21px;

        font-weight: 800;

        letter-spacing: 1px;
    }


    .fims-brand-subtitle {
        color: #94a3b8;

        font-size: 8px;

        font-weight: 500;

        line-height: 1.45;

        white-space: nowrap;
    }


    /* =========================================================
       SIDEBAR AREA
    ========================================================= */

    .main-sidebar .sidebar {
        padding: 0 10px 20px;

        background: transparent !important;
    }


    /* =========================================================
       USER PANEL
    ========================================================= */

    .user-panel-custom {
        min-height: 73px;

        margin:
            12px 2px 13px;

        padding:
            10px 8px;

        display: flex;

        align-items: center;

        gap: 10px;

        border-bottom:
            1px solid rgba(255, 255, 255, 0.08);
    }


    .user-avatar {
        width: 39px;
        height: 39px;

        flex-shrink: 0;

        border-radius: 50%;

        overflow: hidden;

        background: #1e293b;

        border:
            2px solid rgba(255, 255, 255, 0.15);
    }


    .user-avatar img {
        width: 100%;
        height: 100%;

        display: block;

        object-fit: cover;
    }


    .user-avatar-placeholder {
        width: 100%;
        height: 100%;

        display: flex;

        align-items: center;
        justify-content: center;

        color: #94a3b8;

        font-size: 15px;
    }


    .user-info {
        min-width: 0;

        flex: 1;
    }


    .user-name {
        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        color: #f8fafc;

        font-family: 'Montserrat', sans-serif;

        font-size: 12px;

        font-weight: 600;

        margin-bottom: 5px;
    }


    .user-role {
        display: inline-flex;

        align-items: center;

        padding: 3px 8px;

        border-radius: 20px;

        background: rgba(14, 165, 233, 0.16);

        border:
            1px solid rgba(14, 165, 233, 0.25);

        color: #7dd3fc;

        font-size: 9px;

        font-weight: 600;

        text-transform: capitalize;
    }


    /* =========================================================
       NAVIGATION
    ========================================================= */

    .fims-sidebar-nav {
        margin-top: 3px;
    }


    .fims-sidebar-nav .nav {
        width: 100%;
    }


    .fims-sidebar-nav .nav-item {
        margin-bottom: 3px;
    }


    .fims-sidebar-nav .nav-link {
        min-height: 43px;

        margin: 0;

        padding:
            10px 11px !important;

        display: flex !important;

        align-items: center !important;

        border-radius: 8px !important;

        color: #94a3b8 !important;

        background: transparent !important;

        font-size: 12px;

        font-weight: 500;

        transition:
            background 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }


    .fims-sidebar-nav .nav-link:hover {
        color: #ffffff !important;

        background:
            rgba(255, 255, 255, 0.07) !important;

        transform: translateX(2px);
    }


    .fims-sidebar-nav .nav-link.active {
        color: #ffffff !important;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            ) !important;

        box-shadow:
            0 5px 12px rgba(37, 99, 235, 0.25);
    }


    .fims-sidebar-nav .nav-icon {
        width: 25px;

        margin-right: 8px !important;

        color: #64748b;

        font-size: 13px;

        text-align: center;

        transition: color 0.18s ease;
    }


    .fims-sidebar-nav .nav-link:hover .nav-icon {
        color: #60a5fa;
    }


    .fims-sidebar-nav .nav-link.active .nav-icon {
        color: #ffffff !important;
    }


    .fims-sidebar-nav .nav-link p {
        margin: 0 !important;

        color: inherit !important;

        font-size: 12px;

        line-height: 1.2;
    }


    /* =========================================================
       NAV HEADER
    ========================================================= */

    .fims-sidebar-nav .nav-header {
        padding:
            18px 9px 7px !important;

        margin: 0;

        color: #64748b !important;

        font-family: 'Montserrat', sans-serif;

        font-size: 8px !important;

        font-weight: 700 !important;

        letter-spacing: 1px;

        text-transform: uppercase;

        background: transparent !important;
    }


    /* =========================================================
       APPROVAL BADGE
    ========================================================= */

    .fims-menu-badge {
        position: absolute;

        right: 10px;

        top: 50%;

        transform: translateY(-50%);

        min-width: 19px;

        height: 19px;

        padding: 2px 5px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 20px;

        background: #f59e0b;

        color: #ffffff;

        font-size: 9px;

        font-weight: 700;

        box-shadow:
            0 3px 8px rgba(245, 158, 11, 0.25);
    }


    /* =========================================================
       LOGOUT
    ========================================================= */

    .fims-logout-link {
        color: #fca5a5 !important;
    }


    .fims-logout-link .nav-icon {
        color: #ef4444 !important;
    }


    .fims-logout-link:hover {
        color: #ffffff !important;

        background:
            rgba(239, 68, 68, 0.10) !important;
    }


    /* =========================================================
       SCROLLBAR
    ========================================================= */

    .main-sidebar .sidebar::-webkit-scrollbar {
        width: 5px;
    }


    .main-sidebar .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }


    .main-sidebar .sidebar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.25);

        border-radius: 10px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {

        .main-sidebar {
            width: 250px !important;
        }

    }

</style>