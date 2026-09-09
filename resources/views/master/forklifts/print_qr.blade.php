<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        QR Code - {{ $forklift->forklift_code }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
        }

        .print-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            background: white;
            border: 2px solid #222;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
        }

        .company-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .system-title {
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        .qr-wrapper {
            margin: 20px auto;
        }

        .qr-wrapper img {
            width: 260px;
            height: 260px;
            object-fit: contain;
        }

        .forklift-code {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .forklift-info {
            font-size: 14px;
            line-height: 1.6;
            margin-top: 10px;
        }

        .scan-info {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #555;
        }

        .token {
            word-break: break-all;
            font-family: monospace;
            font-size: 10px;
            color: #777;
            margin-top: 10px;
        }

        .actions {
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-print {
            background: #007bff;
            color: white;
        }

        .btn-close {
            background: #6c757d;
            color: white;
            margin-left: 5px;
        }

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .print-container {
                max-width: none;
                border: 2px solid #222;
                border-radius: 0;
                margin: 0;
                page-break-inside: avoid;
            }

            .actions {
                display: none;
            }

        }

    </style>

</head>

<body>

<div class="print-container">

    <div class="company-title">
        KKP FORKLIFT
    </div>

    <div class="system-title">
        Forklift Inspection & Monitoring System
    </div>


    {{-- =========================================================
         QR CODE
    ========================================================== --}}

    <div class="qr-wrapper">

        <img
            src="{{ $forklift->qr_code_image_url }}"
            alt="QR Code {{ $forklift->forklift_code }}"
        >

    </div>


    {{-- =========================================================
         IDENTITAS FORKLIFT
    ========================================================== --}}

    <div class="forklift-code">
        {{ $forklift->forklift_code }}
    </div>

    <div class="forklift-info">

        <strong>
            {{ $forklift->brand }}
            {{ $forklift->model }}
        </strong>

        <br>

        Kapasitas:
        {{ $forklift->capacity }}
        Ton

        <br>

        Area:
        {{ $forklift->location?->location_name ?? '-' }}

    </div>


    {{-- =========================================================
         PETUNJUK
    ========================================================== --}}

    <div class="scan-info">

        <strong>
            SCAN QR CODE UNTUK INSPEKSI
        </strong>

        <br>

        Gunakan kamera smartphone untuk
        membuka halaman inspeksi forklift.

        <div class="token">

            Token:
            {{ $forklift->qr_token }}

        </div>

    </div>


    {{-- =========================================================
         BUTTON
    ========================================================== --}}

    <div class="actions">

        <button
            onclick="window.print()"
            class="btn btn-print"
        >
            🖨 Cetak QR
        </button>

        <button
            onclick="window.close()"
            class="btn btn-close"
        >
            Tutup
        </button>

    </div>

</div>

</body>

</html>