@extends('layouts.app')

@section('title', 'Master User')
@section('page_title', 'Master Data Pengguna')
@section('page_subtitle', 'Kelola data Admin, Supervisor, dan Driver/Operator')

@section('breadcrumb')
    <li class="breadcrumb-item active">Master User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-filter mr-2 text-primary"></i> Filter & Pencarian</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('master.users.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="search" class="text-sm">Kata Kunci</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" name="search" id="search" class="form-control" placeholder="NIK / Nama / Username..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="role_id" class="text-sm">Hak Akses (Role)</label>
                            <select name="role_id" id="role_id" class="form-control">
                                <option value="">-- Semua Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
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
                            <label for="is_active" class="text-sm">Status Akun</label>
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
                            <a href="{{ route('master.users.index') }}" class="btn btn-default btn-sm px-3 ml-2">
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
                    <i class="fas fa-users mr-2 text-secondary"></i> Daftar Pengguna Sistem
                </h3>
                <div class="card-tools">
                    <a href="{{ route('master.users.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Pengguna Baru
                    </a>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle text-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 25%;">Profil Pengguna</th>
                                <th style="width: 15%;">Role & Hak Akses</th>
                                <th style="width: 20%;">Area Kerja</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 25%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->photo_url }}" alt="Avatar" class="img-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="ml-3">
                                                <span class="d-block font-weight-bold text-dark">{{ $user->name }}</span>
                                                <span class="text-muted text-xs">
                                                    <i class="fas fa-id-card mr-1"></i> {{ $user->employee_number }}
                                                </span>
                                                <span class="text-muted text-xs d-block">
                                                    <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="align-middle">
                                        @if($user->isAdmin())
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-user-shield mr-1"></i> {{ $user->role_name }}</span>
                                        @elseif($user->isSupervisor())
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-user-tie mr-1"></i> {{ $user->role_name }}</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-hard-hat mr-1"></i> {{ $user->role_name }}</span>
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        @if($user->location)
                                            <span class="font-weight-bold text-dark">{{ $user->location->location_name }}</span>
                                            <span class="d-block text-xs text-muted">{{ $user->location->description ?? '-' }}</span>
                                        @else
                                            <span class="text-muted font-italic">Semua Area (Global)</span>
                                        @endif
                                    </td>

                                    <td class="text-center align-middle">
                                        @if($user->is_active)
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="fas fa-minus-circle"></i> Non-Aktif</span>
                                        @endif
                                    </td>

                                    <td class="text-center align-middle">
                                        <a href="{{ route('master.users.show', $user->id) }}" class="btn btn-xs btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('master.users.edit', $user->id) }}" class="btn btn-xs btn-warning" title="Edit Data">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('master.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus Data">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-xs btn-secondary disabled" title="Anda tidak dapat menghapus akun Anda sendiri">
                                                <i class="fas fa-ban"></i> Hapus
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 d-block text-light"></i>
                                        <h5>Data Pengguna Tidak Ditemukan</h5>
                                        <p class="text-sm">Silakan ubah kriteria pencarian atau tambahkan pengguna baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer clearfix bg-white border-top">
                <div class="float-left text-muted text-sm mt-2">
                    Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </div>
                <div class="float-right">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection