<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Dashboard')
        | FIMS - Forklift Inspection & Monitoring System
    </title>


    {{-- =====================================================
         GOOGLE FONT
    ====================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Source+Sans+Pro:wght@400;600;700&display=swap"
        rel="stylesheet"
    >


    {{-- =====================================================
         FONT AWESOME
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >


    {{-- =====================================================
         ADMINLTE 3
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"
    >


    {{-- =====================================================
         FIMS VITE
    ====================================================== --}}

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    @stack('styles')


    {{-- =====================================================
         FIMS LAYOUT OVERRIDE
    ====================================================== --}}

    <style>

        /* =================================================
           GLOBAL
        ================================================= */

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;

            background: #f8fafc;

            font-family:
                'Source Sans Pro',
                sans-serif;

            color: #1e293b;

            overflow-x: hidden;
        }


        /* =================================================
           ADMINLTE WRAPPER
        ================================================= */

        .wrapper {
            min-height: 100vh;

            position: relative;
        }


        /* =================================================
           SIDEBAR
        ================================================= */

        .main-sidebar {
            width: 250px !important;

            min-height: 100vh !important;

            position: fixed !important;

            top: 0;
            bottom: 0;
            left: 0;

            z-index: 1038;

            background:
                linear-gradient(
                    180deg,
                    #0f172a 0%,
                    #111c31 55%,
                    #0f172a 100%
                ) !important;

            overflow: hidden;
        }


        .main-sidebar .sidebar {
            height: calc(100vh - 82px);

            overflow-y: auto;

            overflow-x: hidden;

            background: transparent !important;
        }


        /* =================================================
           BRAND
        ================================================= */

        .main-sidebar .brand-link {
            height: 82px !important;

            padding: 12px 15px !important;

            display: flex !important;

            align-items: center !important;

            background:
                rgba(15, 23, 42, 0.50) !important;

            border-bottom:
                1px solid rgba(255,255,255,0.08) !important;

            color: #ffffff !important;

            overflow: hidden;
        }


        /* =================================================
           NAVIGATION
        ================================================= */

        .main-sidebar .nav-sidebar .nav-link {
            margin: 2px 8px;

            border-radius: 8px;

            color: #94a3b8 !important;

            font-size: 12px;

            transition:
                background 0.2s ease,
                color 0.2s ease;
        }


        .main-sidebar .nav-sidebar .nav-link:hover {
            background:
                rgba(255,255,255,0.07) !important;

            color: #ffffff !important;
        }


        .main-sidebar .nav-sidebar .nav-link.active {
            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                ) !important;

            color: #ffffff !important;

            box-shadow:
                0 5px 12px
                rgba(37,99,235,0.25);
        }


        .main-sidebar .nav-sidebar .nav-icon {
            color: #64748b;

            font-size: 13px;

            margin-right: 7px;
        }


        .main-sidebar .nav-sidebar .nav-link.active .nav-icon {
            color: #ffffff !important;
        }


        .main-sidebar .nav-sidebar .nav-header {
            padding:
                17px 17px 7px !important;

            color: #64748b !important;

            font-size: 8px !important;

            font-weight: 700 !important;

            letter-spacing: 1px;

            background: transparent !important;
        }


        /* =================================================
           CONTENT WRAPPER
        ================================================= */

        .content-wrapper {
            min-height: 100vh !important;

            margin-left: 250px !important;

            background: #f8fafc !important;
        }


        /* =================================================
           HEADER
        ================================================= */

        .content-header {
            min-height: 104px;

            padding:
                25px 25px 20px;

            background: #ffffff;

            border-bottom:
                1px solid #e2e8f0;
        }


        .content-header h1 {
            margin: 0;

            color: #0f172a;

            font-family:
                'Montserrat',
                sans-serif;

            font-size: 27px;

            font-weight: 800;

            line-height: 1.2;
        }


        .content-header small {
            display: block;

            margin-top: 6px;

            color: #64748b;

            font-size: 13px;
        }


        /* =================================================
           BREADCRUMB
        ================================================= */

        .content-header .breadcrumb {
            margin: 5px 0 0;

            padding: 0;

            background: transparent;

            font-size: 12px;
        }


        .content-header .breadcrumb-item a {
            color: #2563eb;

            text-decoration: none;
        }


        .content-header .breadcrumb-item.active {
            color: #64748b;
        }


        /* =================================================
           CONTENT
        ================================================= */

        .content {
            padding:
                20px 24px 30px;
        }


        .content > .container-fluid {
            padding-left: 0;

            padding-right: 0;
        }


        /* =================================================
           ALERT
        ================================================= */

        .alert {
            border-radius: 9px;

            font-size: 12px;
        }


        /* =================================================
           WELCOME ALERT
        ================================================= */

        .welcome-alert {
            position: relative;

            display: flex;

            align-items: center;

            gap: 14px;

            width: 100%;

            padding: 16px 18px;

            margin-bottom: 16px;

            background: #ffffff;

            border: 1px solid #dbeafe;

            border-left: 4px solid #2563eb;

            border-radius: 10px;

            box-shadow:
                0 4px 14px
                rgba(15, 23, 42, 0.06);
        }


        .welcome-alert-icon {
            width: 42px;
            height: 42px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #eff6ff;

            color: #2563eb;

            border-radius: 10px;

            font-size: 17px;
        }


        .welcome-alert-content {
            flex: 1;

            display: flex;

            flex-direction: column;

            gap: 2px;
        }


        .welcome-alert-content strong {
            color: #1e293b;

            font-size: 14px;

            font-weight: 700;
        }


        .welcome-alert-content span {
            color: #64748b;

            font-size: 12px;
        }


        .welcome-alert-role {
            padding: 6px 11px;

            background: #eff6ff;

            color: #2563eb;

            border-radius: 999px;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;
        }


        .welcome-alert-close {
            border: 0;

            background: transparent;

            color: #94a3b8;

            font-size: 20px;

            line-height: 1;

            padding: 4px 7px;

            cursor: pointer;
        }


        .welcome-alert-close:hover {
            color: #334155;
        }


        /* =================================================
           FOOTER
        ================================================= */

        .main-footer {
            margin-left: 250px !important;

            padding:
                14px 24px;

            background: #ffffff;

            border-top:
                1px solid #e2e8f0;

            color: #94a3b8;

            font-size: 11px;
        }


        /* =================================================
           SCROLLBAR SIDEBAR
        ================================================= */

        .main-sidebar .sidebar::-webkit-scrollbar {
            width: 5px;
        }


        .main-sidebar .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }


        .main-sidebar .sidebar::-webkit-scrollbar-thumb {
            background:
                rgba(148,163,184,0.25);

            border-radius: 10px;
        }


        /* =================================================
           MOBILE
        ================================================= */

        @media (max-width: 991.98px) {

            .content-wrapper {
                margin-left: 0 !important;
            }

            .main-footer {
                margin-left: 0 !important;
            }

            .content-header {
                padding:
                    20px 18px;
            }

            .content {
                padding:
                    18px;
            }

        }


        @media (max-width: 576px) {

            .content-header {
                min-height: auto;
            }

            .content-header h1 {
                font-size: 22px;
            }

            .content-header .breadcrumb {
                margin-top: 10px;
            }

            .welcome-alert {
                align-items: flex-start;
            }

            .welcome-alert-role {
                display: none;
            }

        }

    </style>

</head>


<body class="hold-transition sidebar-mini layout-fixed text-sm">


<div class="wrapper">


    {{-- =====================================================
         NAVBAR
    ====================================================== --}}

    @include('layouts.partials.navbar')


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    @include('layouts.partials.sidebar')


    {{-- =====================================================
         CONTENT WRAPPER
    ====================================================== --}}

    <div class="content-wrapper">


        {{-- =================================================
             PAGE HEADER
        ================================================== --}}

        <div class="content-header">

            <div class="container-fluid">

                <div class="row align-items-center">


                    {{-- PAGE TITLE --}}

                    <div class="col-sm-7">

                        <h1>

                            @yield(
                                'page_title',
                                'Dashboard'
                            )

                        </h1>


                        @hasSection('page_subtitle')

                            <small>

                                @yield('page_subtitle')

                            </small>

                        @endif

                    </div>


                    {{-- BREADCRUMB --}}

                    <div class="col-sm-5">

                        <ol class="breadcrumb float-sm-right">

                            <li class="breadcrumb-item">

                                <a
                                    href="{{ route('dashboard.index') }}"
                                >
                                    Home
                                </a>

                            </li>

                            @yield('breadcrumb')

                        </ol>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             SESSION ALERT
        ================================================== --}}

        <div class="container-fluid">


            {{-- =================================================
                 WELCOME NOTIFICATION
            ================================================== --}}

            @if(session('welcome'))

                <div
                    class="welcome-alert"
                    role="alert"
                >

                    {{-- ICON --}}

                    <div class="welcome-alert-icon">

                        <i
                            class="{{ session('welcome.icon', 'fas fa-check-circle') }}"
                        ></i>

                    </div>


                    {{-- MESSAGE --}}

                    <div class="welcome-alert-content">

                        <strong>
                            {{ session('welcome.title') }}
                        </strong>

                        <span>
                            {{ session('welcome.message') }}
                        </span>

                    </div>


                    {{-- ROLE --}}

                    @if(session('welcome.role'))

                        <div class="welcome-alert-role">

                            {{ session('welcome.role') }}

                        </div>

                    @endif


                    {{-- CLOSE --}}

                    <button
                        type="button"
                        class="welcome-alert-close"
                        onclick="this.closest('.welcome-alert').remove()"
                        aria-label="Tutup"
                    >

                        &times;

                    </button>

                </div>

            @endif


            {{-- =================================================
                 SUCCESS
            ================================================== --}}

            @if(session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3"
                    role="alert"
                >

                    <i class="fas fa-check-circle mr-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>

            @endif


            {{-- =================================================
                 ERROR
            ================================================== --}}

            @if(session('error'))

                <div
                    class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3"
                    role="alert"
                >

                    <i class="fas fa-exclamation-triangle mr-2"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>

            @endif


            {{-- =================================================
                 WARNING
            ================================================== --}}

            @if(session('warning'))

                <div
                    class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-3"
                    role="alert"
                >

                    <i class="fas fa-exclamation-circle mr-2"></i>

                    {{ session('warning') }}

                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>

            @endif


        </div>


        {{-- =================================================
             MAIN CONTENT
        ================================================== --}}

        <section class="content">

            <div class="container-fluid">

                @yield('content')

            </div>

        </section>


    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    @include('layouts.partials.footer')


</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')


<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-forklift-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            const forkliftCode = form.dataset.forkliftCode;

            Swal.fire({
                title: 'Hapus Forklift?',
                html: `
                    Anda yakin ingin menghapus forklift
                    <strong>${forkliftCode}</strong>?
                    <br><br>
                    <small class="text-muted">
                        Data forklift akan dihapus dari daftar master.
                    </small>
                `,
                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',

                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',

                reverseButtons: true,

                focusCancel: true
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,

                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    form.submit();
                }

            });

        });

    });

});
</script>
</body>

</html>