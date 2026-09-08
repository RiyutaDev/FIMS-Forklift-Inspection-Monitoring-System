@extends('layouts.app')

@section('title', 'Master Forklift')
@section('page_title', 'Master Data Forklift & QR')
@section('page_subtitle', 'Kelola unit operasional forklift, lokasi area kerja, dan token QR code')

@section('breadcrumb')
    <li class="breadcrumb-item active">Master Forklift</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-filter mr-2 text-primary"></i> Filter & Pencarian Unit</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('master.forklifts.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="search" class="text-sm">Kata Kunci</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Kode / No Aset / Brand..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="location_id" class="text-sm">Area Kerja</label>
                            <select name="location_id" id="location_id" class="form-control">
                                <option value="">-- Semua Area --</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="fuel_type" class="text-sm">Tipe Bahan Bakar</label>
                            <select name="fuel_type" id="fuel_type" class="form-control">
                                <option value="">-- Semua Tipe --</option>
                                <option value="Electric" {{ request('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                                <option value="Diesel" {{ request('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="LPG" {{ request('fuel_type') == 'LPG' ? 'selected' : '' }}>LPG</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="is_active" class="text-sm">Status Operasional</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="">-- Semua Status --</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-search mr-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('master.forklifts.index') }}" class="btn btn-default btn-sm px-3 ml-2">
                                <i class="fas fa-sync-alt mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-dark shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-truck-monster mr-2 text-secondary"></i> Daftar Unit Forklift
                </h3>
                <div class="card-tools">
                    <a href="{{ route('master.forklifts.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Unit Forklift
                    </a>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle text-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">Kode & No. Aset</th>
                                <th style="width: 20%;">Brand & Model</th>
                                <th style="width: 15%;">Area / Lokasi</th>
                                <th class="text-center" style="width: 12%;">Bahan Bakar</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 18%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($forklifts as $index => $forklift)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{ $forklifts->firstItem() + $index }}
                                    </td>
                                    
                                    <td class="align-middle">
                                        <span class="font-weight-bold text-dark d-block">
                                            <i class="fas fa-barcode text-primary mr-1"></i> {{ $forklift->forklift_code }}
                                        </span>
                                        <small class="text-muted">Aset: {{ $forklift->asset_number ?? '-' }}</small>
                                    </td>
                                    
                                    <td class="align-middle">
                                        <span class="font-weight-bold">{{ $forklift->brand }} - {{ $forklift->model }}</span>
                                        <small class="d-block text-muted">Kapasitas: {{ $forklift->capacity }} Ton</small>
                                    </td>

                                    <td class="align-middle">
                                        <span class="font-weight-bold text-dark">{{ $forklift->location?->location_name ?? '-' }}</span>
                                    </td>

                                    <td class="text-center align-middle">
                                        @if($forklift->fuel_type === 'Electric')
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-bolt mr-1"></i> Electric</span>
                                        @elseif($forklift->fuel_type === 'Diesel')
                                            <span class="badge badge-dark px-2 py-1"><i class="fas fa-gas-pump mr-1"></i> Diesel</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1"><i class="fas fa-fire mr-1"></i> LPG</span>
                                        @endif
                                    </td>

                                    <td class="text-center align-middle">
                                        @if($forklift->is_active)
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="fas fa-minus-circle"></i> Non-Aktif</span>
                                        @endif
                                    </td>

                                    <td class="text-center align-middle">
                                        <a href="{{ route('master.forklifts.show', $forklift->id) }}" class="btn btn-xs btn-info" title="Detail & QR">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('master.forklifts.print_qr', $forklift->id) }}" target="_blank" class="btn btn-xs btn-secondary" title="Cetak Label QR">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        <a href="{{ route('master.forklifts.edit', $forklift->id) }}" class="btn btn-xs btn-warning" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('master.forklifts.destroy', $forklift->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit forklift ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" title="Hapus Data">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-truck-monster fa-3x mb-3 d-block text-light"></i>
                                        <h5>Data Unit Forklift Tidak Ditemukan</h5>
                                        <p class="text-sm">Silakan sesuaikan filter pencarian atau tambahkan unit baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer clearfix bg-white border-top">
                <div class="float-left text-muted text-sm mt-2">
                    Menampilkan {{ $forklifts->firstItem() ?? 0 }} sampai {{ $forklifts->lastItem() ?? 0 }} dari {{ $forklifts->total() }} unit
                </div>
                <div class="float-right">
                    {{ $forklifts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection