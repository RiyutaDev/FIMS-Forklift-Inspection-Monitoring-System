@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('page_title', 'Profil Pengguna: ' . $user->name)
@section('page_subtitle', 'Informasi lengkap dan rekam jejak aktivitas pengguna sistem')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.users.index') }}">Master User</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-5 col-12">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
                        <img class="profile-user-img img-fluid img-circle elevation-2" src="{{ asset('storage/' . $user->photo) }}" alt="User profile picture" style="width: 110px; height: 110px; object-fit: cover;">
                    @else
                        <img class="profile-user-img img-fluid img-circle elevation-2" src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" style="width: 110px; height: 110px; object-fit: cover;">
                    @endif
                </div>

                <h3 class="profile-username text-center font-weight-bold mt-3">{{ $user->name }}</h3>
                <p class="text-muted text-center text-sm mb-1">
                    <i class="fas fa-id-card mr-1 text-primary"></i> NIK: <strong>{{ $user->employee_number }}</strong>
                </p>
                <p class="text-center mb-3">
                    @if($user->isAdmin())
                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-user-shield mr-1"></i> {{ $user->role_name }}</span>
                    @elseif($user->isSupervisor())
                        <span class="badge badge-info px-2 py-1"><i class="fas fa-user-tie mr-1"></i> {{ $user->role_name }}</span>
                    @else
                        <span class="badge badge-success px-2 py-1"><i class="fas fa-hard-hat mr-1"></i> {{ $user->role_name }}</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered mb-3 text-sm">
                    <li class="list-group-item">
                        <b>Username</b> <span class="float-right text-dark font-weight-bold">{{ $user->username }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <span class="float-right text-dark font-weight-bold">{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Telepon / WA</b> <span class="float-right text-dark font-weight-bold">{{ $user->phone ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Area Kerja</b> <span class="float-right text-dark font-weight-bold">{{ $user->location?->location_name ?? 'Global / Semua Area' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Status Akun</b> 
                        <span class="float-right">
                            @if($user->is_active)
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                            @else
                                <span class="badge badge-secondary"><i class="fas fa-minus-circle"></i> Non-Aktif</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <b>Terakhir Login</b> <span class="float-right text-muted text-xs">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum pernah' }}</span>
                    </li>
                </ul>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('master.users.index') }}" class="btn btn-default btn-sm btn-block mr-1">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <a href="{{ route('master.users.edit', $user->id) }}" class="btn btn-warning btn-sm btn-block ml-1 mt-0 font-weight-bold">
                        <i class="fas fa-edit mr-1"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7 col-12">
        
        <div class="card card-outline card-info shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-clipboard-list mr-2 text-info"></i> 5 Histori Inspeksi Terakhir
                </h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped align-middle text-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>No. Inspeksi</th>
                            <th>Forklift</th>
                            <th>Tanggal & Shift</th>
                            <th class="text-center">Hasil</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->inspections ?? [] as $inspection)
                            <tr>
                                <td class="font-weight-bold text-primary">{{ $inspection->inspection_number }}</td>
                                <td>{{ $inspection->forklift->forklift_code ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d/m/Y') }} ({{ $inspection->inspection_shift }})</td>
                                <td class="text-center">
                                    @if($inspection->overall_result === 'Ready')
                                        <span class="badge badge-success">Ready</span>
                                    @else
                                        <span class="badge badge-danger">Not Ready</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($inspection->status === 'Approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($inspection->status === 'Rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-warning">Submitted</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">
                                    Belum ada catatan histori inspeksi yang dilakukan oleh pengguna ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-history mr-2 text-secondary"></i> Log Aktivitas Sistem Terakhir
                </h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle text-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 25%;">Waktu</th>
                            <th style="width: 20%;">Modul / Aksi</th>
                            <th style="width: 55%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->activityLogs ?? [] as $log)
                            <tr>
                                <td class="text-muted text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <span class="badge badge-light border text-dark">{{ $log->module }}</span>
                                    <span class="badge badge-primary">{{ $log->action }}</span>
                                </td>
                                <td>{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    Belum ada catatan aktivitas sistem untuk akun ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection