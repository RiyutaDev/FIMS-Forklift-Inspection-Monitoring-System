@extends('layouts.app')

@section('title', 'Tambah Unit Forklift')
@section('page_title', 'Tambah Unit Forklift Baru')
@section('page_subtitle', 'Formulir pendaftaran spesifikasi unit dan generate QR token otomatis')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('master.forklifts.index') }}">
            Master Forklift
        </a>
    </li>

    <li class="breadcrumb-item active">
        Tambah
    </li>
@endsection

@section('content')

<div class="row">

    <div class="col-lg-8 col-md-10 col-12 mx-auto">

        <div class="card card-outline card-primary shadow-sm">

            <div class="card-header">

                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-truck-monster mr-2 text-primary"></i>
                    Form Pendaftaran Unit Forklift
                </h3>

            </div>


            <form
                action="{{ route('master.forklifts.store') }}"
                method="POST"
                id="forkliftCreateForm"
            >

                @csrf

                <div class="card-body">

                    {{-- =====================================================
                         VALIDATION ERROR
                    ====================================================== --}}

                    @if ($errors->any())

                        <div
                            class="alert alert-danger alert-dismissible fade show text-sm mb-4"
                            role="alert"
                        >

                            <i class="icon fas fa-exclamation-triangle mr-1"></i>

                            <strong>Perhatian!</strong>

                            Terdapat beberapa kesalahan pada input Anda.
                            Silakan periksa kembali form di bawah.

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


                    {{-- =====================================================
                         KODE FORKLIFT + ASSET NUMBER
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="forklift_code">
                                Kode Unit Forklift
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text">
                                        <i class="fas fa-barcode"></i>
                                    </span>

                                </div>

                                <input
                                    type="text"
                                    name="forklift_code"
                                    id="forklift_code"
                                    class="form-control @error('forklift_code') is-invalid @enderror"
                                    value="{{ old('forklift_code') }}"
                                    placeholder="Contoh: FL-001"
                                    required
                                    autofocus
                                >

                            </div>

                            @error('forklift_code')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        <div class="col-md-6 form-group">

                            <label for="asset_number">
                                Nomor Aset (Asset No)
                            </label>

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>

                                </div>

                                <input
                                    type="text"
                                    name="asset_number"
                                    id="asset_number"
                                    class="form-control @error('asset_number') is-invalid @enderror"
                                    value="{{ old('asset_number') }}"
                                    placeholder="Contoh: AST-98765"
                                >

                            </div>

                            @error('asset_number')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- =====================================================
                         BRAND + MODEL
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="brand">
                                Merek (Brand)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="brand"
                                id="brand"
                                class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand') }}"
                                placeholder="Contoh: Toyota, Komatsu, TCM"
                                required
                            >

                            @error('brand')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        <div class="col-md-6 form-group">

                            <label for="model">
                                Model / Seri
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="model"
                                id="model"
                                class="form-control @error('model') is-invalid @enderror"
                                value="{{ old('model') }}"
                                placeholder="Contoh: 8FD25 / FB25"
                                required
                            >

                            @error('model')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- =====================================================
                         SERIAL + LOCATION
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="serial_number">
                                Nomor Seri Pabrik (Serial Number)
                            </label>

                            <input
                                type="text"
                                name="serial_number"
                                id="serial_number"
                                class="form-control @error('serial_number') is-invalid @enderror"
                                value="{{ old('serial_number') }}"
                                placeholder="Contoh: SN-2023-XYZ"
                            >

                            @error('serial_number')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        <div class="col-md-6 form-group">

                            <label for="location_id">
                                Area / Lokasi Penempatan
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="location_id"
                                id="location_id"
                                class="form-control @error('location_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- Pilih Area Kerja --
                                </option>

                                @foreach($locations as $location)

                                    <option
                                        value="{{ $location->id }}"
                                        {{ old('location_id') == $location->id ? 'selected' : '' }}
                                    >
                                        {{ $location->location_name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('location_id')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- =====================================================
                         CAPACITY + FUEL + YEAR
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-4 form-group">

                            <label for="capacity">
                                Kapasitas (Ton)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                name="capacity"
                                id="capacity"
                                class="form-control @error('capacity') is-invalid @enderror"
                                value="{{ old('capacity', '2.5') }}"
                                placeholder="2.5"
                                required
                            >

                            @error('capacity')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        <div class="col-md-4 form-group">

                            <label for="fuel_type">
                                Tipe Bahan Bakar
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="fuel_type"
                                id="fuel_type"
                                class="form-control @error('fuel_type') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- Pilih Tipe --
                                </option>

                                <option
                                    value="Electric"
                                    {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}
                                >
                                    Electric (Baterai)
                                </option>

                                <option
                                    value="Diesel"
                                    {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}
                                >
                                    Diesel (Solar)
                                </option>

                                <option
                                    value="LPG"
                                    {{ old('fuel_type') == 'LPG' ? 'selected' : '' }}
                                >
                                    LPG / Gas
                                </option>

                            </select>

                            @error('fuel_type')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        <div class="col-md-4 form-group">

                            <label for="manufacture_year">
                                Tahun Pembuatan
                            </label>

                            <input
                                type="number"
                                name="manufacture_year"
                                id="manufacture_year"
                                class="form-control @error('manufacture_year') is-invalid @enderror"
                                value="{{ old('manufacture_year', date('Y')) }}"
                                placeholder="2022"
                            >

                            @error('manufacture_year')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- =====================================================
                         COMMISSIONING + VENDOR
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="commissioning_date">
                                Tanggal Commissioning / Operasional
                            </label>

                            <input
                                type="date"
                                name="commissioning_date"
                                id="commissioning_date"
                                class="form-control @error('commissioning_date') is-invalid @enderror"
                                value="{{ old('commissioning_date') }}"
                            >

                            @error('commissioning_date')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        <div class="col-md-6 form-group">

                            <label for="vendor_name">
                                Vendor / Partner Pemeliharaan
                            </label>

                            <input
                                type="text"
                                name="vendor_name"
                                id="vendor_name"
                                class="form-control @error('vendor_name') is-invalid @enderror"
                                value="{{ old('vendor_name') }}"
                                placeholder="Contoh: PT. Forklift Mitra Mandiri"
                            >

                            @error('vendor_name')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- =====================================================
                         STATUS OPERASIONAL
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label class="d-block">
                                Status Operasional Unit
                            </label>

                            <div class="custom-control custom-switch mt-2">

                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >

                                <label
                                    class="custom-control-label font-weight-normal"
                                    for="is_active"
                                >
                                    Unit Aktif
                                    (Siap Dioperasikan & Diinspeksi)
                                </label>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                         DESCRIPTION
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-12 form-group">

                            <label for="description">
                                Keterangan / Catatan Spesifikasi
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Catatan tambahan mengenai unit forklift..."
                            >{{ old('description') }}</textarea>

                            @error('description')

                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                     FOOTER
                ========================================================== --}}

                <div class="card-footer bg-light d-flex justify-content-between">

                    <a
                        href="{{ route('master.forklifts.index') }}"
                        class="btn btn-default px-4"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary px-4 font-weight-bold"
                        id="btnSubmitForklift"
                    >

                        <i class="fas fa-save mr-1"></i>

                        Simpan Unit Forklift

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =====================================================================
     SWEETALERT2
====================================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | REFERENSI FORM UTAMA
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById(
        'forkliftCreateForm'
    );

    const submitButton = document.getElementById(
        'btnSubmitForklift'
    );


    /*
    |--------------------------------------------------------------------------
    | SWEETALERT RESTORE FORKLIFT
    |--------------------------------------------------------------------------
    |
    | store() akan mengirim:
    |
    | restore_forklift_id
    | restore_forklift_code
    |
    | menggunakan redirect()->with().
    |
    | Data form sendiri tetap berasal dari old().
    |--------------------------------------------------------------------------
    */

    @if(session('restore_forklift_id'))

        const restoreForkliftId =
            @json(session('restore_forklift_id'));

        const restoreForkliftCode =
            @json(session('restore_forklift_code'));


        /*
        |--------------------------------------------------------------------------
        | URL RESTORE
        |--------------------------------------------------------------------------
        |
        | URL dibuat langsung oleh Laravel.
        | Tidak menggunakan placeholder __ID__.
        |--------------------------------------------------------------------------
        */

        const restoreUrl =
            @json(
                route(
                    'master.forklifts.restore',
                    [
                        'forklift' => session('restore_forklift_id')
                    ]
                )
            );


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN SWEETALERT
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            icon: 'warning',

            title: 'Forklift Pernah Dihapus',

            html: `
                <div style="
                    font-size: 15px;
                    line-height: 1.6;
                    text-align: center;
                ">

                    <p class="mb-3">

                        Kode forklift

                        <strong>
                            ${restoreForkliftCode}
                        </strong>

                        ditemukan dalam data yang
                        sebelumnya telah dihapus.

                    </p>


                    <div
                        class="alert alert-info text-left mb-3"
                        style="
                            font-size: 13px;
                            line-height: 1.6;
                        "
                    >

                        <i class="fas fa-info-circle mr-1"></i>

                        <strong>Informasi:</strong>

                        Data yang Anda isi pada form ini
                        akan digunakan saat forklift dipulihkan.

                        <br><br>

                        <strong>
                            ID forklift, QR Code lama,
                            dan riwayat inspeksi
                            tetap dipertahankan.
                        </strong>

                    </div>


                    <p class="mb-0">
                        Apakah Anda ingin memulihkan
                        forklift ini?
                    </p>

                </div>
            `,

            showCancelButton: true,

            confirmButtonText:
                '<i class="fas fa-undo mr-1"></i> Ya, Pulihkan',

            cancelButtonText:
                '<i class="fas fa-times mr-1"></i> Batal',

            confirmButtonColor: '#28a745',

            cancelButtonColor: '#6c757d',

            reverseButtons: true,

            focusCancel: true,

            allowOutsideClick: false,

            allowEscapeKey: false,

            width: '500px'

        }).then(function (result) {


            /*
            |--------------------------------------------------------------------------
            | JIKA USER MEMILIH BATAL
            |--------------------------------------------------------------------------
            */

            if (!result.isConfirmed) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PASTIKAN FORM TERSEDIA
            |--------------------------------------------------------------------------
            */

            if (!form) {

                Swal.fire({

                    icon: 'error',

                    title: 'Form Tidak Ditemukan',

                    text:
                        'Form forklift tidak dapat diproses.',

                    confirmButtonColor: '#dc3545'

                });

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | BUAT FORM RESTORE BARU
            |--------------------------------------------------------------------------
            */

            const restoreForm =
                document.createElement('form');


            restoreForm.method = 'POST';

            restoreForm.action = restoreUrl;


            /*
            |--------------------------------------------------------------------------
            | CSRF TOKEN
            |--------------------------------------------------------------------------
            */

            const csrfInput =
                document.createElement('input');

            csrfInput.type = 'hidden';

            csrfInput.name = '_token';

            csrfInput.value =
                @json(csrf_token());

            restoreForm.appendChild(
                csrfInput
            );


            /*
            |--------------------------------------------------------------------------
            | AMBIL SEMUA DATA DARI FORM UTAMA
            |--------------------------------------------------------------------------
            |
            | INI PERBAIKAN UTAMA.
            |
            | Sebelumnya hanya _token yang dikirim.
            |
            | Sekarang seluruh field dikirim:
            |
            | forklift_code
            | asset_number
            | brand
            | model
            | serial_number
            | location_id
            | capacity
            | fuel_type
            | manufacture_year
            | commissioning_date
            | vendor_name
            | is_active
            | description
            |--------------------------------------------------------------------------
            */

            const formData =
                new FormData(form);


            formData.forEach(function (
                value,
                key
            ) {

                /*
                |--------------------------------------------------------------------------
                | Jangan kirim CSRF dua kali.
                |--------------------------------------------------------------------------
                */

                if (key === '_token') {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Buat hidden input untuk setiap field.
                |--------------------------------------------------------------------------
                */

                const input =
                    document.createElement('input');

                input.type = 'hidden';

                input.name = key;

                input.value = value;

                restoreForm.appendChild(
                    input
                );

            });


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN FORM RESTORE KE BODY
            |--------------------------------------------------------------------------
            */

            document.body.appendChild(
                restoreForm
            );


            /*
            |--------------------------------------------------------------------------
            | LOADING SWEETALERT
            |--------------------------------------------------------------------------
            */

            Swal.fire({

                title: 'Memulihkan Forklift...',

                html: `
                    <div
                        style="
                            font-size: 14px;
                            line-height: 1.6;
                        "
                    >

                        Data forklift sedang dipulihkan.

                        <br>

                        <small class="text-muted">

                            QR Code lama dan
                            riwayat inspeksi
                            tetap dipertahankan.

                        </small>

                    </div>
                `,

                allowOutsideClick: false,

                allowEscapeKey: false,

                showConfirmButton: false,

                didOpen: function () {

                    Swal.showLoading();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | SUBMIT RESTORE
            |--------------------------------------------------------------------------
            */

            restoreForm.submit();

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | SWEETALERT ERROR VALIDASI
    |--------------------------------------------------------------------------
    |
    | Tetap mempertahankan alert validasi yang sudah ada.
    |--------------------------------------------------------------------------
    */

    @if($errors->any())

        setTimeout(function () {

            Swal.fire({

                icon: 'error',

                title: 'Data Belum Lengkap',

                html: `
                    <div
                        class="text-left"
                        style="
                            font-size: 14px;
                            line-height: 1.6;
                        "
                    >

                        <p>
                            Silakan periksa kembali
                            beberapa bagian berikut:
                        </p>

                        <ul
                            style="
                                padding-left: 20px;
                                margin-bottom: 0;
                            "
                        >

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>
                `,

                confirmButtonText:
                    'Periksa Form',

                confirmButtonColor:
                    '#007bff',

                width: '500px'

            });

        }, 300);

    @endif


    /*
    |--------------------------------------------------------------------------
    | PROTEKSI DOUBLE SUBMIT
    |--------------------------------------------------------------------------
    */

    if (form && submitButton) {

        form.addEventListener(
            'submit',
            function () {

                submitButton.disabled =
                    true;


                submitButton.innerHTML =
                    '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                    ' Menyimpan...';

            }
        );

    }

});

</script>

@endsection