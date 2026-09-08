<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $forklift->forklift_code }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        /* Tombol Aksi (Hanya muncul di layar, disembunyikan saat print) */
        .no-print {
            margin: 20px 0;
            text-align: center;
        }

        /* Desain Kartu / Lembar Stiker QR Code */
        .qr-label-card {
            width: 380px;
            max-width: 100%;
            margin: 30px auto;
            background: #ffffff;
            border: 2px dashed #007bff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .company-header {
            font-size: 13px;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .unit-code {
            font-size: 26px;
            font-weight: 800;
            color: #007bff;
            margin-bottom: 2px;
        }

        .unit-spec {
            font-size: 13px;
            color: #495057;
            margin-bottom: 15px;
        }

        .qr-wrapper {
            background: #fff;
            padding: 12px;
            display: inline-block;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        /* QR Code Placeholder / Real Image */
        .qr-wrapper img {
            width: 200px;
            height: 200px;
            object-fit: contain;
        }

        .scan-instruction {
            font-size: 12px;
            font-weight: 600;
            color: #dc3545;
            background: #f8d7da;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .meta-info {
            font-size: 11px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
            margin-top: 5px;
            text-align: left;
        }

        /* Pengaturan Khusus Saat Dicetak (Print Media) */
        @media print {
            body {
                background-color: #ffffff;
            }
            .no-print {
                display: none !important;
            }
            .qr-label-card {
                margin: 0 auto;
                box-shadow: none;
                border: 2px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container no-print">
        <div class="row">
            <div class="col-12 text-center">
                <button onclick="window.print()" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-print mr-2"></i> Cetak Stiker QR
                </button>
                <a href="{{ route('master.forklifts.show', $forklift->id) }}" class="btn btn-secondary px-4 ml-2 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
                </a>
            </div>
        </div>
    </div>

    <div class="qr-label-card">
        <div class="company-header">
            <i class="fas fa-shield-alt mr-1"></i> FIMS - Forklift Inspection System
        </div>
        
        <div class="unit-code">{{ $forklift->forklift_code }}</div>
        <div class="unit-spec">
            <strong>{{ $forklift->brand }}</strong> - {{ $forklift->model }} (Cap: {{ $forklift->capacity }} Ton)
        </div>

        <div class="qr-wrapper">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ route('inspector.inspection.create', ['token' => $forklift->qr_token ?? $forklift->id]) }}" alt="QR Code Unit {{ $forklift->forklift_code }}">
        </div>

        <div>
            <span class="scan-instruction">
                <i class="fas fa-qrcode mr-1"></i> SCAN UNTUK INSPEKSI HARIAN
            </span>
        </div>

        <div class="meta-info d-flex justify-content-between">
            <span><strong>Lokasi:</strong> {{ $forklift->location->location_name ?? '-' }}</span>
            <span><strong>Token:</strong> {{ substr($forklift->qr_token ?? 'TOKEN-FIMS', 0, 8) }}...</span>
        </div>
    </div>

</body>
</html>