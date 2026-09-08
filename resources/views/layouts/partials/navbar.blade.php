<nav class="main-header navbar navbar-expand fims-navbar">

    {{-- =====================================================
         LEFT NAVIGATION
    ====================================================== --}}

    <ul class="navbar-nav">

        <!-- {{-- Sidebar Toggle --}}

        <li class="nav-item">

            <a
                class="nav-link fims-menu-toggle"
                data-widget="pushmenu"
                href="#"
                role="button"
                title="Toggle Sidebar"
            >

                <i class="fas fa-bars"></i>

            </a>

        </li> -->


        {{-- FIMS Portal --}}

        <li class="nav-item d-none d-sm-inline-block">

            <span class="nav-link fims-portal-title">

                <span class="portal-icon">

                    <i class="fas fa-truck"></i>

                </span>

                <span>
                    FIMS PORTAL
                </span>

            </span>

        </li>

    </ul>


    {{-- =====================================================
         RIGHT NAVIGATION
    ====================================================== --}}

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown user-menu">


            {{-- =================================================
                 USER BUTTON
            ================================================== --}}

            <a
                href="#"
                class="nav-link dropdown-toggle fims-user-toggle"
                data-toggle="dropdown"
                aria-expanded="false"
            >

                <span class="fims-navbar-avatar">

                    @if(auth()->user()->photo_url)

                        <img
                            src="{{ auth()->user()->photo_url }}"
                            alt="User"
                        >

                    @else

                        <span class="fims-navbar-avatar-placeholder">

                            <i class="fas fa-user"></i>

                        </span>

                    @endif

                </span>


                <span class="d-none d-md-inline fims-user-name">

                    {{ auth()->user()->name }}

                </span>

            </a>


            {{-- =================================================
                 USER DROPDOWN
            ================================================== --}}

            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right fims-user-dropdown">


                {{-- USER HEADER --}}

                <li class="user-header fims-user-header">


                    <div class="fims-dropdown-avatar">

                        @if(auth()->user()->photo_url)

                            <img
                                src="{{ auth()->user()->photo_url }}"
                                alt="User"
                            >

                        @else

                            <div class="fims-dropdown-avatar-placeholder">

                                <i class="fas fa-user"></i>

                            </div>

                        @endif

                    </div>


                    <p>

                        {{ auth()->user()->name }}

                        <small>

                            {{ auth()->user()->employee_number ?? '-' }}

                            &bull;

                            {{ auth()->user()->role?->role_name ?? 'User' }}

                        </small>

                    </p>


                    <span class="fims-location-badge">

                        <i class="fas fa-map-marker-alt mr-1"></i>

                        {{ auth()->user()->location?->location_name ?? 'All Area' }}

                    </span>

                </li>


                {{-- USER FOOTER --}}

                <li class="user-footer fims-user-footer">


                    {{-- USERNAME --}}

                    <div class="fims-account-info">

                        <i class="fas fa-id-badge"></i>

                        <span>

                            {{ auth()->user()->username }}

                        </span>

                    </div>


                    {{-- LOGOUT --}}

                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="fims-logout-button"
                        >

                            <i class="fas fa-sign-out-alt"></i>

                            Logout

                        </button>

                    </form>


                </li>

            </ul>

        </li>

    </ul>

</nav>


{{-- =============================================================
     NAVBAR STYLE
============================================================= --}}

<style>

    /* =========================================================
       NAVBAR
    ========================================================= */

    .fims-navbar {

        height: 60px !important;

        min-height: 60px !important;

        padding:
            0 18px !important;

        margin: 0 !important;

        background: #ffffff !important;

        border: 0 !important;

        border-bottom:
            1px solid #e2e8f0 !important;

        box-shadow:
            0 2px 8px
            rgba(15, 23, 42, 0.04) !important;

        display: flex;

        align-items: center;

    }


    /* =========================================================
       NAVBAR LINKS
    ========================================================= */

    .fims-navbar .nav-link {

        height: 60px;

        display: flex;

        align-items: center;

        color: #64748b !important;

        padding:
            0 12px !important;

    }


    .fims-navbar .nav-link:hover {

        color: #2563eb !important;

    }


    /* =========================================================
       MENU TOGGLE
    ========================================================= */

    .fims-menu-toggle {

        width: 42px;

        justify-content: center;

        border-radius: 8px;

        transition:
            background 0.2s ease;

    }


    .fims-menu-toggle:hover {

        background: #f1f5f9;

    }


    .fims-menu-toggle i {

        font-size: 16px;

    }


    /* =========================================================
       FIMS PORTAL
    ========================================================= */

    .fims-portal-title {

        display: flex !important;

        align-items: center;

        gap: 8px;

        color: #1e293b !important;

        font-family:
            'Montserrat',
            sans-serif;

        font-size: 12px;

        font-weight: 700;

        letter-spacing: 0.3px;

    }


    .portal-icon {

        width: 27px;

        height: 27px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 7px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 12px;

    }


    /* =========================================================
       USER TOGGLE
    ========================================================= */

    .fims-user-toggle {

        gap: 8px;

        padding-left: 10px !important;

        padding-right: 4px !important;

    }


    .fims-user-name {

        max-width: 180px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        color: #334155 !important;

        font-family:
            'Montserrat',
            sans-serif;

        font-size: 11px;

        font-weight: 600;

    }


    /* =========================================================
       NAVBAR AVATAR
    ========================================================= */

    .fims-navbar-avatar {

        width: 34px;

        height: 34px;

        flex-shrink: 0;

        display: flex;

        align-items: center;

        justify-content: center;

        overflow: hidden;

        border-radius: 50%;

        background: #e2e8f0;

        border:
            2px solid #e2e8f0;

    }


    .fims-navbar-avatar img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        display: block;

    }


    .fims-navbar-avatar-placeholder {

        width: 100%;

        height: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #64748b;

        font-size: 13px;

    }


    /* =========================================================
       DROPDOWN
    ========================================================= */

    .fims-user-dropdown {

        width: 300px;

        margin-top: 4px !important;

        padding: 0 !important;

        overflow: hidden;

        border:
            1px solid #e2e8f0 !important;

        border-radius: 12px !important;

        box-shadow:
            0 15px 35px
            rgba(15, 23, 42, 0.14) !important;

    }


    /* =========================================================
       USER HEADER
    ========================================================= */

    .fims-user-header {

        min-height: 150px !important;

        height: auto !important;

        padding:
            20px !important;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            ) !important;

        color: #ffffff !important;

    }


    .fims-dropdown-avatar {

        width: 58px;

        height: 58px;

        margin-bottom: 9px;

        overflow: hidden;

        border-radius: 50%;

        background: #ffffff;

        border:
            3px solid
            rgba(255,255,255,0.75);

    }


    .fims-dropdown-avatar img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        display: block;

    }


    .fims-dropdown-avatar-placeholder {

        width: 100%;

        height: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #2563eb;

        font-size: 22px;

    }


    .fims-user-header p {

        margin: 0;

        color: #ffffff !important;

        text-align: center;

        font-family:
            'Montserrat',
            sans-serif;

        font-size: 13px;

        font-weight: 700;

        line-height: 1.4;

    }


    .fims-user-header p small {

        display: block;

        margin-top: 3px;

        color:
            rgba(255,255,255,0.78);

        font-family:
            'Source Sans Pro',
            sans-serif;

        font-size: 10px;

        font-weight: 400;

    }


    /* =========================================================
       LOCATION
    ========================================================= */

    .fims-location-badge {

        display: inline-flex;

        align-items: center;

        margin-top: 8px;

        padding:
            4px 9px;

        border-radius: 20px;

        background:
            rgba(255,255,255,0.15);

        border:
            1px solid
            rgba(255,255,255,0.20);

        color: #ffffff;

        font-size: 9px;

        font-weight: 600;

    }


    /* =========================================================
       USER FOOTER
    ========================================================= */

    .fims-user-footer {

        min-height: 58px;

        padding:
            10px 14px !important;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 10px;

        background: #ffffff !important;

        border-top:
            1px solid #e2e8f0;

    }


    /* =========================================================
       ACCOUNT INFO
    ========================================================= */

    .fims-account-info {

        min-width: 0;

        display: flex;

        align-items: center;

        gap: 7px;

        color: #64748b;

        font-size: 10px;

    }


    .fims-account-info i {

        color: #94a3b8;

        font-size: 12px;

    }


    .fims-account-info span {

        max-width: 100px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

    }


    /* =========================================================
       LOGOUT BUTTON
    ========================================================= */

    .fims-logout-button {

        border: 0;

        border-radius: 7px;

        padding:
            7px 11px;

        background: #fee2e2;

        color: #dc2626;

        font-size: 10px;

        font-weight: 600;

        cursor: pointer;

        transition:
            background 0.2s ease,
            color 0.2s ease;

    }


    .fims-logout-button:hover {

        background: #dc2626;

        color: #ffffff;

    }


    .fims-logout-button i {

        margin-right: 4px;

    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 575.98px) {

        .fims-navbar {

            padding:
                0 8px !important;

        }


        .fims-portal-title {

            display: none !important;

        }


        .fims-user-name {

            display: none !important;

        }


        .fims-user-dropdown {

            width: 280px;

            right: 5px !important;

        }

    }

</style>