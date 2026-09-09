@extends('layouts.app')

@section('title', 'Detail Forklift')
@section('page_title', 'Detail Unit Forklift')
@section('page_subtitle', 'Informasi unit, status operasional, dan QR Code')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('master.forklifts.index') }}">
            Master Forklift
        </a>
    </li>

    <li class="breadcrumb-item active">
        Detail
    </li>
@endsection

@section('content')

<div class="row">

    {{-- =========================================================
         INFORMASI FORKLIFT
    ========================================================== --}}
    <div class="col-lg-8">

        <div class="card card-outline card-primary shadow-sm">

            <div class="card-header">

                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-truck-monster mr-2 text-primary"></i>
                    Informasi Unit Forklift
                </h3>

                <div class="card-tools">

                    <a
                        href="{{ route('master.forklifts.edit', $forklift->id) }}"
                        class="btn btn-warning btn-sm"
                    >
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Kode --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Kode Unit
                        </small>

                        <strong class="text-primary">
                            {{ $forklift->forklift_code }}
                        </strong>

                    </div>


                    {{-- Asset --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Nomor Aset
                        </small>

                        <strong>
                            {{ $forklift->asset_number }}
                        </strong>

                    </div>


                    {{-- Brand --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Brand
                        </small>

                        <strong>
                            {{ $forklift->brand }}
                        </strong>

                    </div>


                    {{-- Model --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Model
                        </small>

                        <strong>
                            {{ $forklift->model ?? '-' }}
                        </strong>

                    </div>


                    {{-- Serial --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Serial Number
                        </small>

                        <strong>
                            {{ $forklift->serial_number ?? '-' }}
                        </strong>

                    </div>


                    {{-- Location --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Area Kerja
                        </small>

                        <strong>
                            {{ $forklift->location?->location_name ?? '-' }}
                        </strong>

                    </div>


                    {{-- Capacity --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Kapasitas
                        </small>

                        <strong>
                            {{ $forklift->capacity }}
                            Ton
                        </strong>

                    </div>


                    {{-- Fuel --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Tipe
                        </small>

                        <strong>
                            {{ $forklift->fuel_type }}
                        </strong>

                    </div>


                    {{-- Vendor --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Vendor
                        </small>

                        <strong>
                            {{ $forklift->vendor_name ?? '-' }}
                        </strong>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Status Operasional
                        </small>

                        @if($forklift->is_active)

                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>

                        @else

                            <span class="badge badge-secondary">
                                <i class="fas fa-minus-circle mr-1"></i>
                                Non-Aktif
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         QR CODE
    ========================================================== --}}
    <div class="col-lg-4">

        <div class="card card-outline card-dark shadow-sm">

            <div class="card-header">

                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-qrcode mr-2"></i>
                    QR Code Forklift
                </h3>

            </div>

            <div class="card-body text-center">

                <div class="mb-3">

                    <img
                        src="{{ $forklift->qr_code_image_url }}"
                        alt="QR Code {{ $forklift->forklift_code }}"
                        class="img-fluid"
                        style="max-width: 240px;"
                    >

                </div>

                <h4 class="font-weight-bold mb-1">
                    {{ $forklift->forklift_code }}
                </h4>

                <p class="text-muted text-sm mb-3">
                    Scan QR Code untuk membuka proses inspeksi.
                </p>


                {{-- =================================================
                     CETAK ULANG QR
                ================================================== --}}
                <form
                    action="{{ route('master.forklifts.regenerate_qr', $forklift->id) }}"
                    method="POST"
                    class="mb-2"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary btn-block"
                    >
                        <i class="fas fa-print mr-1"></i>
                        Cetak Ulang QR
                    </button>

                </form>


                {{-- PRINT LANGSUNG --}}
                <a
                    href="{{ route('master.forklifts.print_qr', $forklift->id) }}"
                    target="_blank"
                    class="btn btn-outline-secondary btn-block"
                >
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Buka Halaman Cetak
                </a>


                <div class="alert alert-info text-left text-sm mt-3 mb-0">

                    <i class="fas fa-info-circle mr-1"></i>

                    <strong>Catatan:</strong>

                    Mencetak ulang QR tidak mengubah
                    <code>qr_token</code>.
                    QR Code lama tetap dapat digunakan.

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     NAVIGATION
========================================================== --}}

<div class="mt-3">

    <a
        href="{{ route('master.forklifts.index') }}"
        class="btn btn-default"
    >
        <i class="fas fa-arrow-left mr-1"></i>
        Kembali
    </a>

</div>

@endsection