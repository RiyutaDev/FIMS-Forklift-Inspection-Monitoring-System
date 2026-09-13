@extends('layouts.app')

@section('title', 'Edit Unit Forklift')
@section('page_title', 'Edit Data Forklift')
@section('page_subtitle', 'Perbarui spesifikasi dan status operasional unit forklift')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('master.forklifts.index') }}">
            Master Forklift
        </a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('master.forklifts.show', $forklift) }}">
            {{ $forklift->forklift_code }}
        </a>
    </li>

    <li class="breadcrumb-item active">
        Edit
    </li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8 col-md-10 col-12 mx-auto">

        <div class="card card-outline card-warning shadow-sm">

            <div class="card-header">

                <h3 class="card-title font-weight-bold">

                    <i class="fas fa-edit mr-2 text-warning"></i>

                    Edit Data Unit Forklift

                </h3>

            </div>


            <form
                action="{{ route('master.forklifts.update', $forklift) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="card-body">

                    @if ($errors->any())

                        <div class="alert alert-danger alert-dismissible fade show text-sm">

                            <i class="fas fa-exclamation-triangle mr-1"></i>

                            <strong>Perhatian!</strong>

                            Terdapat kesalahan pada data yang dimasukkan.

                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert"
                            >
                                <span>&times;</span>
                            </button>

                        </div>

                    @endif


                    {{-- IDENTITAS UNIT --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="forklift_code">
                                Kode Unit Forklift
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="forklift_code"
                                id="forklift_code"
                                class="form-control @error('forklift_code') is-invalid @enderror"
                                value="{{ old('forklift_code', $forklift->forklift_code) }}"
                                required
                            >

                            @error('forklift_code')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div class="col-md-6 form-group">

                            <label for="asset_number">
                                Nomor Aset
                            </label>

                            <input
                                type="text"
                                name="asset_number"
                                id="asset_number"
                                class="form-control @error('asset_number') is-invalid @enderror"
                                value="{{ old('asset_number', $forklift->asset_number) }}"
                            >

                            @error('asset_number')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- BRAND / MODEL --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="brand">
                                Merek
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="brand"
                                id="brand"
                                class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand', $forklift->brand) }}"
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
                                value="{{ old('model', $forklift->model) }}"
                                required
                            >

                            @error('model')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- SERIAL / LOCATION --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="serial_number">
                                Nomor Seri Pabrik
                            </label>

                            <input
                                type="text"
                                name="serial_number"
                                id="serial_number"
                                class="form-control @error('serial_number') is-invalid @enderror"
                                value="{{ old('serial_number', $forklift->serial_number) }}"
                            >

                            @error('serial_number')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div class="col-md-6 form-group">

                            <label for="location_id">
                                Area / Lokasi
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
                                        {{ old('location_id', $forklift->location_id) == $location->id ? 'selected' : '' }}
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


                    {{-- SPESIFIKASI --}}

                    <div class="row">

                        <div class="col-md-4 form-group">

                            <label for="capacity">
                                Kapasitas (Ton)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                min="0.1"
                                name="capacity"
                                id="capacity"
                                class="form-control @error('capacity') is-invalid @enderror"
                                value="{{ old('capacity', $forklift->capacity) }}"
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
                                    {{ old('fuel_type', $forklift->fuel_type) === 'Electric' ? 'selected' : '' }}
                                >
                                    Electric (Baterai)
                                </option>

                                <option
                                    value="Diesel"
                                    {{ old('fuel_type', $forklift->fuel_type) === 'Diesel' ? 'selected' : '' }}
                                >
                                    Diesel (Solar)
                                </option>

                                <option
                                    value="LPG"
                                    {{ old('fuel_type', $forklift->fuel_type) === 'LPG' ? 'selected' : '' }}
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
                                value="{{ old('manufacture_year', $forklift->manufacture_year) }}"
                            >

                            @error('manufacture_year')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- OPERASIONAL --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="commissioning_date">
                                Tanggal Commissioning
                            </label>

                            <input
                                type="date"
                                name="commissioning_date"
                                id="commissioning_date"
                                class="form-control @error('commissioning_date') is-invalid @enderror"
                                value="{{ old('commissioning_date', optional($forklift->commissioning_date)->format('Y-m-d')) }}"
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
                                value="{{ old('vendor_name', $forklift->vendor_name) }}"
                            >

                            @error('vendor_name')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- STATUS OPERASIONAL --}}

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label class="d-block">
                                Status Operasional
                            </label>

                            <div class="custom-control custom-switch mt-2">

                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', $forklift->is_active) ? 'checked' : '' }}
                                >

                                <label
                                    class="custom-control-label font-weight-normal"
                                    for="is_active"
                                >
                                    Unit Aktif / Siap Dioperasikan
                                </label>

                            </div>

                            <small class="form-text text-muted">
                                Jika dimatikan, unit tidak dapat digunakan untuk inspeksi.
                            </small>

                            @error('is_active')
                                <span class="text-danger d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="form-group">

                        <label for="description">
                            Keterangan
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $forklift->description) }}</textarea>

                        @error('description')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                <div class="card-footer bg-light d-flex justify-content-between">

                    <a
                        href="{{ route('master.forklifts.show', $forklift) }}"
                        class="btn btn-default px-4"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning px-4 font-weight-bold"
                    >
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#007bff'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: @json(session('error')),
            confirmButtonText: 'OK'
        });
    </script>
@endif

@endsection