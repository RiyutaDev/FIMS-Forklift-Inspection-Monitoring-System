<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard.index') }}" class="brand-link text-center">
        <img src="/images/logo/forklift-logo.png" alt="FIMS" class="brand-image" style="height:46px; width:46px; object-fit:contain; border-radius:8px; margin-right:8px;">
        <span class="brand-text font-weight-bold">FIMS</span>
        <small class="d-block text-xs font-weight-normal">Forklift Inspection & Monitoring</small>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ auth()->user()->photo_url }}" class="img-circle elevation-2" alt="User Image" style="width: 38px; height: 38px; object-fit: cover;">
            </div>
            <div class="info">
                <a href="#" class="d-block font-weight-bold text-truncate" style="max-width: 160px;">{{ auth()->user()->name }}</a>
                <span class="badge badge-info text-capitalize">{{ auth()->user()->role?->role_name }}</span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if(auth()->user()->isDriver())
                    <li class="nav-header text-uppercase text-xs font-weight-bold">Operasional Inspeksi</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('inspections.create') }}" class="nav-link {{ request()->routeIs('inspections.create') || request()->routeIs('inspections.checklist') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-qrcode text-warning"></i>
                            <p>Inspeksi Baru (Scan/Pilih)</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('inspections.index') }}" class="nav-link {{ request()->routeIs('inspections.index') || request()->routeIs('inspections.show') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Saya</p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                    <li class="nav-header text-uppercase text-xs font-weight-bold">Validasi & Monitoring</li>

                    <li class="nav-item">
                        <a href="{{ route('approvals.index') }}" class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check text-info"></i>
                            <p>
                                Approval Inspeksi
                                @php
                                    $pendingCount = \App\Models\Inspection::where('status', 'Submitted')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="badge badge-warning right">{{ $pendingCount }}</span>
                                @endif
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('inspections.index') }}" class="nav-link {{ request()->routeIs('inspections.index') || request()->routeIs('inspections.show') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Seluruh Data Inspeksi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-pdf text-danger"></i>
                            <p>Laporan & Export PDF</p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-header text-uppercase text-xs font-weight-bold">Master Data</li>

                    <li class="nav-item">
                        <a href="{{ route('master.users.index') }}" class="nav-link {{ request()->routeIs('master.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Master User</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('master.forklifts.index') }}" class="nav-link {{ request()->routeIs('master.forklifts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-monster"></i>
                            <p>Master Forklift & QR</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('master.inspection-items.index') }}" class="nav-link {{ request()->routeIs('master.inspection-items.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>Master Item Checklist</p>
                        </a>
                    </li>
                @endif

                <li class="nav-header">AKUN</li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Keluar</p>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>