<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Login | FIMS - Forklift Inspection & Monitoring System</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Source+Sans+Pro:wght@400;600&display=swap"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <style>

        /* =========================================================
           RESET
        ========================================================= */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
        }

        /* =========================================================
           BODY
        ========================================================= */

        body {
            font-family: 'Source Sans Pro', sans-serif;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 24px;

            background-image:
                linear-gradient(
                    90deg,
                    rgba(15, 23, 42, 0.82) 0%,
                    rgba(15, 23, 42, 0.60) 45%,
                    rgba(15, 23, 42, 0.45) 100%
                ),
                url('/images/login/forklift-background.png');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            background-attachment: fixed;

            color: #1e293b;
        }

        /* =========================================================
           LOGIN CARD
        ========================================================= */

        .login-card {
            width: 100%;
            max-width: 450px;

            background: #ffffff;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.40);

            border: 1px solid rgba(255, 255, 255, 0.10);

            position: relative;
            z-index: 2;
        }

        /* =========================================================
           LOGIN HEADER
        ========================================================= */

        .login-header {
            position: relative;

            background:
                linear-gradient(
                    135deg,
                    #0284c7 0%,
                    #0369a1 55%,
                    #075985 100%
                );

            padding: 28px 24px 30px;

            text-align: center;

            color: #ffffff;

            overflow: hidden;
        }

        /* Decorative circle */

        .login-header::before {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.06);

            top: -100px;
            right: -70px;

            pointer-events: none;
        }

        .login-header::after {
            content: "";

            position: absolute;

            width: 140px;
            height: 140px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.04);

            bottom: -90px;
            left: -60px;

            pointer-events: none;
        }

        /* =========================================================
           FORKLIFT ICON
        ========================================================= */

        .brand-logo-wrapper {
            width: 64px;
            height: 64px;

            margin: 0 auto 14px;

            background: #ffffff;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 2px solid rgba(255, 255, 255, 0.40);

            box-shadow:
                0 6px 16px rgba(0, 0, 0, 0.18);

            overflow: hidden;

            position: relative;
            z-index: 2;
        }

        .forklift-logo {
            width: 48px;
            height: 48px;

            max-width: 48px;
            max-height: 48px;

            display: block;

            object-fit: contain;
        }

        /* =========================================================
           BRAND
        ========================================================= */

        .login-header h1 {
            position: relative;
            z-index: 2;

            font-family: 'Montserrat', sans-serif;

            font-size: 30px;
            font-weight: 800;

            line-height: 1;

            letter-spacing: 2px;

            margin: 0 0 8px;

            text-transform: uppercase;
        }

        .login-header p {
            position: relative;
            z-index: 2;

            margin: 0;

            font-family: 'Montserrat', sans-serif;

            font-size: 11px;
            font-weight: 600;

            line-height: 1.5;

            letter-spacing: 0.5px;

            opacity: 0.95;
        }

        .login-tagline {
            position: relative;
            z-index: 2;

            display: block;

            margin-top: 14px;

            font-family: 'Montserrat', sans-serif;

            font-size: 9px;
            font-weight: 600;

            letter-spacing: 2.5px;

            opacity: 0.75;
        }

        /* =========================================================
           LOGIN BODY
        ========================================================= */

        .login-body {
            padding: 30px 30px 26px;

            background: #ffffff;
        }

        /* =========================================================
           WELCOME TEXT
        ========================================================= */

        .welcome-text {
            text-align: center;

            margin-bottom: 26px;
        }

        .welcome-text h2 {
            font-family: 'Montserrat', sans-serif;

            font-size: 22px;
            font-weight: 700;

            line-height: 1.2;

            color: #0f172a;

            margin: 0 0 7px;
        }

        .welcome-text p {
            font-size: 14px;

            color: #64748b;

            margin: 0;
        }

        /* =========================================================
           ALERT
        ========================================================= */

        .alert {
            position: relative;

            padding: 11px 38px 11px 13px;

            margin-bottom: 20px;

            border-radius: 9px;

            font-size: 13px;

            line-height: 1.4;
        }

        .alert-danger {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .alert-success {
            color: #166534;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .alert .close {
            position: absolute;

            top: 50%;
            right: 10px;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            font-size: 20px;

            line-height: 1;

            color: inherit;

            opacity: 0.55;

            cursor: pointer;
        }

        .alert .close:hover {
            opacity: 1;
        }

        /* =========================================================
           FORM GROUP
        ========================================================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            font-family: 'Montserrat', sans-serif;

            font-size: 11px;
            font-weight: 700;

            color: #334155;

            letter-spacing: 0.6px;

            margin-bottom: 8px;
        }

        /* =========================================================
           INPUT GROUP
        ========================================================= */

        .input-group {
            width: 100%;

            min-height: 50px;

            display: flex;
            align-items: center;

            border: 1.5px solid #cbd5e1;

            border-radius: 11px;

            overflow: hidden;

            background: #ffffff;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .input-group:focus-within {
            border-color: #0284c7;

            box-shadow:
                0 0 0 4px rgba(2, 132, 199, 0.10);
        }

        .input-group-prepend {
            display: flex;
            align-items: stretch;

            height: 100%;
        }

        .input-group-text {
            width: 50px;

            min-width: 50px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            background: #f8fafc;

            border: none;

            color: #64748b;

            font-size: 15px;
        }

        .input-group:focus-within .input-group-text {
            color: #0284c7;
        }

        /* =========================================================
           FORM CONTROL
        ========================================================= */

        .form-control {
            flex: 1;

            width: 100%;

            height: 48px;

            border: none;

            outline: none;

            padding: 0 12px;

            font-family: 'Source Sans Pro', sans-serif;

            font-size: 14px;

            color: #1e293b;

            background: #ffffff;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            outline: none;

            box-shadow: none;
        }

        /* =========================================================
           PASSWORD TOGGLE
        ========================================================= */

        .btn-toggle-password {
            width: 48px;

            height: 48px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;

            background: transparent;

            color: #94a3b8;

            cursor: pointer;

            transition: color 0.2s ease;
        }

        .btn-toggle-password:hover {
            color: #0284c7;
        }

        .btn-toggle-password:focus {
            outline: none;
        }

        /* =========================================================
           REMEMBER ME
        ========================================================= */

        .remember-wrapper {
            display: flex;

            align-items: center;

            margin-top: 2px;

            margin-bottom: 20px;
        }

        .remember-wrapper input {
            width: 16px;
            height: 16px;

            margin: 0 8px 0 0;

            accent-color: #0284c7;

            cursor: pointer;
        }

        .remember-wrapper label {
            margin: 0;

            font-size: 13px;

            color: #475569;

            cursor: pointer;

            user-select: none;
        }

        /* =========================================================
           LOGIN BUTTON
        ========================================================= */

        .btn-login {
            width: 100%;

            height: 50px;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 9px;

            border: none;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    #0284c7 0%,
                    #0369a1 100%
                );

            color: #ffffff;

            font-family: 'Montserrat', sans-serif;

            font-size: 13px;
            font-weight: 700;

            letter-spacing: 1px;

            cursor: pointer;

            box-shadow:
                0 6px 15px rgba(2, 132, 199, 0.25);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);

            background:
                linear-gradient(
                    135deg,
                    #0369a1 0%,
                    #075985 100%
                );

            box-shadow:
                0 9px 20px rgba(2, 132, 199, 0.32);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:focus {
            outline: none;

            box-shadow:
                0 0 0 4px rgba(2, 132, 199, 0.15),
                0 6px 15px rgba(2, 132, 199, 0.25);
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .login-footer {
            text-align: center;

            padding: 17px 28px 20px;

            border-top: 1px solid #f1f5f9;

            background: #f8fafc;
        }

        .login-footer-brand {
            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 8px;
        }

        .login-footer-brand span {
            height: 1px;

            flex: 1;

            background: #e2e8f0;
        }

        .login-footer-brand strong {
            font-family: 'Montserrat', sans-serif;

            font-size: 9px;
            font-weight: 700;

            letter-spacing: 2px;

            color: #64748b;

            white-space: nowrap;
        }

        .login-footer p {
            margin: 0;

            font-size: 11px;

            color: #94a3b8;
        }

        /* =========================================================
           ERROR STATE
        ========================================================= */

        .is-invalid-group {
            border-color: #ef4444 !important;
        }

        .is-invalid-group:focus-within {
            box-shadow:
                0 0 0 4px rgba(239, 68, 68, 0.10) !important;
        }

        .error-message {
            display: flex;

            align-items: center;

            gap: 5px;

            margin-top: 5px;

            font-size: 12px;

            color: #ef4444;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 576px) {

            body {
                padding: 16px;
            }

            .login-card {
                max-width: 100%;

                border-radius: 16px;
            }

            .login-header {
                padding: 24px 20px 26px;
            }

            .brand-logo-wrapper {
                width: 58px;
                height: 58px;

                margin-bottom: 12px;
            }

            .forklift-logo {
                width: 43px;
                height: 43px;
            }

            .login-header h1 {
                font-size: 27px;
            }

            .login-header p {
                font-size: 10px;
            }

            .login-tagline {
                font-size: 8px;

                letter-spacing: 2px;
            }

            .login-body {
                padding: 26px 20px 22px;
            }

            .welcome-text h2 {
                font-size: 20px;
            }

            .login-footer {
                padding-left: 20px;
                padding-right: 20px;
            }
        }

        @media (max-width: 360px) {

            body {
                padding: 10px;
            }

            .login-header h1 {
                font-size: 25px;
            }

            .login-header p {
                font-size: 9px;
            }

            .login-tagline {
                display: none;
            }

            .login-body {
                padding: 22px 16px 20px;
            }
        }

    </style>
</head>

<body>

    <div class="login-card">

        {{-- =====================================================
             HEADER / BRAND
        ====================================================== --}}

        <div class="login-header">

            <div class="brand-logo-wrapper">
                <img
                    src="{{ asset('images/logo/forklift-logo.png') }}"
                    alt="Forklift"
                    class="forklift-logo"
                >
            </div>

            <h1>FIMS</h1>

            <p>
                Forklift Inspection &amp; Monitoring System
            </p>

            <span class="login-tagline">
                INSPECTION TODAY, SAFER TOMORROW
            </span>

        </div>


        {{-- =====================================================
             LOGIN BODY
        ====================================================== --}}

        <div class="login-body">

            {{-- Welcome --}}

            <div class="welcome-text">

                <h2>Selamat Datang</h2>

                <p>
                    Silakan masuk untuk mengakses sistem
                </p>

            </div>


            {{-- =================================================
                 ERROR SESSION
            ================================================== --}}

            @if(session('error'))

                <div
                    class="alert alert-danger"
                    role="alert"
                >
                    <i class="fas fa-exclamation-circle"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="close"
                        onclick="this.parentElement.remove()"
                        aria-label="Close"
                    >
                        &times;
                    </button>
                </div>

            @endif


            {{-- =================================================
                 SUCCESS SESSION
            ================================================== --}}

            @if(session('success'))

                <div
                    class="alert alert-success"
                    role="alert"
                >
                    <i class="fas fa-check-circle"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="close"
                        onclick="this.parentElement.remove()"
                        aria-label="Close"
                    >
                        &times;
                    </button>
                </div>

            @endif


            {{-- =================================================
                 INFORMATION SESSION
            ================================================== --}}

            @if(session('info'))

                <div
                    class="alert alert-success"
                    role="alert"
                >
                    <i class="fas fa-qrcode"></i>

                    {{ session('info') }}

                    <button
                        type="button"
                        class="close"
                        onclick="this.parentElement.remove()"
                        aria-label="Close"
                    >
                        &times;
                    </button>
                </div>

            @endif


            {{-- =================================================
                 LOGIN FORM
            ================================================== --}}

            <form
                action="{{ route('login.process') }}"
                method="POST"
                autocomplete="off"
            >

                @csrf


                {{-- NIK --}}

                <div class="form-group">

                    <label for="employee_number">
                        Nomor Pegawai / NIK
                    </label>

                    <div
                        class="input-group @error('employee_number') is-invalid-group @enderror"
                    >

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>

                        </div>

                        <input
                            type="text"
                            name="employee_number"
                            id="employee_number"
                            class="form-control"
                            placeholder="Masukkan NIK Karyawan"
                            value="{{ old('employee_number') }}"
                            required
                            autofocus
                            autocomplete="username"
                        >

                    </div>

                    @error('employee_number')

                        <div class="error-message">

                            <i class="fas fa-circle-exclamation"></i>

                            <span>{{ $message }}</span>

                        </div>

                    @enderror

                </div>


                {{-- PASSWORD --}}

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div
                        class="input-group @error('password') is-invalid-group @enderror"
                    >

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>

                        </div>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan Password Anda"
                            required
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="btn-toggle-password"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                        >

                            <i
                                class="fas fa-eye"
                                id="eyeIcon"
                            ></i>

                        </button>

                    </div>

                    @error('password')

                        <div class="error-message">

                            <i class="fas fa-circle-exclamation"></i>

                            <span>{{ $message }}</span>

                        </div>

                    @enderror

                </div>


                {{-- REMEMBER ME --}}

                <div class="remember-wrapper">

                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        value="1"
                    >

                    <label for="remember">
                        Ingat Saya
                    </label>

                </div>


                {{-- LOGIN BUTTON --}}

                <button
                    type="submit"
                    class="btn-login"
                >

                    <i class="fas fa-sign-in-alt"></i>

                    <span>LOGIN</span>

                </button>

            </form>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="login-footer">

            <div class="login-footer-brand">

                <span></span>

                <strong>KKP FORKLIFT</strong>

                <span></span>

            </div>

            <p>
                &copy; {{ date('Y') }} FIMS. All rights reserved.
            </p>

        </div>

    </div>


    {{-- =========================================================
         PASSWORD TOGGLE
    ========================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const togglePassword =
                document.getElementById('togglePassword');

            const passwordInput =
                document.getElementById('password');

            const eyeIcon =
                document.getElementById('eyeIcon');


            if (
                togglePassword &&
                passwordInput &&
                eyeIcon
            ) {

                togglePassword.addEventListener(
                    'click',
                    function () {

                        const isPassword =
                            passwordInput.type === 'password';


                        passwordInput.type =
                            isPassword ? 'text' : 'password';


                        if (isPassword) {

                            eyeIcon.classList.remove(
                                'fa-eye'
                            );

                            eyeIcon.classList.add(
                                'fa-eye-slash'
                            );

                            togglePassword.setAttribute(
                                'aria-label',
                                'Sembunyikan password'
                            );

                        } else {

                            eyeIcon.classList.remove(
                                'fa-eye-slash'
                            );

                            eyeIcon.classList.add(
                                'fa-eye'
                            );

                            togglePassword.setAttribute(
                                'aria-label',
                                'Tampilkan password'
                            );

                        }

                    }
                );

            }

        });

    </script>

</body>
</html>