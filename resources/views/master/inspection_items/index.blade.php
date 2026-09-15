@extends('layouts.app')

@section('title', 'Master Item Ceklis')

@section('content')
<style>
    .inspection-dashboard { padding: 28px; background: #f1f5f9; min-height: 100vh; }
    .inspection-hero { display: flex; justify-content: space-between; gap: 24px; align-items: center; padding: 28px; color: #fff; border-radius: 18px; background: linear-gradient(135deg, #0f172a, #1e3a8a 58%, #2563eb); box-shadow: 0 10px 25px rgba(15, 23, 42, .15); }
    .inspection-hero small { color: #bfdbfe; font-size: 12px; }
    .inspection-hero h1 { margin: 8px 0 0; font-size: 26px; font-weight: 800; }
    .inspection-hero p { margin: 8px 0 0; color: #dbeafe; font-size: 13px; }
    .inspection-hero-icon { display: grid; place-items: center; width: 64px; height: 64px; flex: 0 0 auto; border-radius: 18px; background: rgba(255,255,255,.15); font-size: 28px; }
    .inspection-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin: 20px 0; }
    .inspection-stat, .inspection-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 5px 18px rgba(15, 23, 42, .06); }
    .inspection-stat { padding: 18px 20px; }
    .inspection-stat-label { color: #64748b; font-size: 12px; font-weight: 700; }
    .inspection-stat-value { display: block; margin-top: 6px; color: #0f172a; font-size: 26px; font-weight: 800; }
    .inspection-panel { overflow: hidden; }
    .inspection-panel-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 20px 24px; border-bottom: 1px solid #e2e8f0; }
    .inspection-panel-head h2 { margin: 0; color: #0f172a; font-size: 17px; font-weight: 800; }
    .inspection-panel-head p { margin: 4px 0 0; color: #64748b; font-size: 12px; }
    .inspection-btn { display: inline-flex; align-items: center; gap: 8px; min-height: 40px; padding: 0 15px; color: #fff; background: #2563eb; border-radius: 10px; font-size: 12px; font-weight: 800; text-decoration: none; }
    .inspection-btn:hover { color: #fff; background: #1d4ed8; }
    .inspection-filters { display: grid; grid-template-columns: 2fr repeat(4, 1fr) auto; gap: 10px; padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .inspection-filter { min-width: 0; height: 38px; padding: 0 11px; color: #334155; border: 1px solid #cbd5e1; border-radius: 9px; background: #fff; font-size: 12px; }
    .inspection-filter:focus { outline: 0; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    .inspection-table-wrap { overflow-x: auto; }
    .inspection-table { width: 100%; min-width: 900px; border-collapse: collapse; font-size: 12px; }
    .inspection-table th { padding: 13px 16px; color: #64748b; background: #f8fafc; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; }
    .inspection-table td { padding: 14px 16px; color: #475569; border-top: 1px solid #f1f5f9; vertical-align: middle; }
    .inspection-table tr:hover td { background: #f8fbff; }
    .inspection-code { color: #2563eb; font-weight: 800; }
    .inspection-name { color: #0f172a; font-weight: 800; }
    .inspection-description { max-width: 250px; margin-top: 3px; overflow: hidden; color: #94a3b8; text-overflow: ellipsis; white-space: nowrap; }
    .inspection-badge { display: inline-flex; padding: 5px 9px; border-radius: 999px; font-size: 10px; font-weight: 800; }
    .inspection-badge-critical { color: #b91c1c; background: #fee2e2; }
    .inspection-badge-normal { color: #475569; background: #f1f5f9; }
    .inspection-badge-active { color: #047857; background: #d1fae5; }
    .inspection-badge-inactive { color: #b91c1c; background: #fee2e2; }
    .inspection-actions { display: flex; justify-content: flex-end; gap: 6px; }
    .inspection-action { padding: 7px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; text-decoration: none; }
    .inspection-action-detail { color: #1d4ed8; background: #dbeafe; }
    .inspection-action-edit { color: #a16207; background: #fef3c7; }
    .inspection-action-delete { color: #b91c1c; background: #fee2e2; border: 0; cursor: pointer; }
    .inspection-pagination { padding: 16px 24px; }
    @media (max-width: 900px) { .inspection-dashboard { padding: 15px; } .inspection-stats { grid-template-columns: 1fr; } .inspection-filters { grid-template-columns: 1fr 1fr; } .inspection-filters .inspection-filter:first-child { grid-column: 1 / -1; } .inspection-hero { padding: 22px; } }
</style>
<div class="inspection-dashboard">
    <section class="inspection-hero"><div><small>Master Data / Checklist</small><h1>Master Item Ceklis</h1><p>Kelola standar pemeriksaan forklift dalam satu dashboard terpusat.</p></div><div class="inspection-hero-icon">✓</div></section>
    @php $totalItems = $items->total(); $activeItems = $items->getCollection()->where('is_active', true)->count(); $criticalItems = $items->getCollection()->where('is_critical', true)->count(); @endphp
    <div class="inspection-stats"><div class="inspection-stat"><span class="inspection-stat-label">Total item</span><strong class="inspection-stat-value">{{ $totalItems }}</strong></div><div class="inspection-stat"><span class="inspection-stat-label">Aktif di halaman ini</span><strong class="inspection-stat-value">{{ $activeItems }}</strong></div><div class="inspection-stat"><span class="inspection-stat-label">Item kritis di halaman ini</span><strong class="inspection-stat-value">{{ $criticalItems }}</strong></div></div>
    @if (session('success'))<div style="margin-bottom: 20px; padding: 14px 18px; color: #047857; background: #d1fae5; border: 1px solid #a7f3d0; border-radius: 12px; font-size: 13px;">{{ session('success') }}</div>@endif
    <section class="inspection-panel">
        <div class="inspection-panel-head"><div><h2>Daftar Item Inspeksi</h2><p>Urut berdasarkan kategori dan urutan tampilan.</p></div><a href="{{ route('master.inspection-items.create') }}" class="inspection-btn">＋ Tambah Item</a></div>
        <form method="GET" class="inspection-filters"><input class="inspection-filter" type="search" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama item..."><select class="inspection-filter" name="category_id"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->category_name }}</option>@endforeach</select><select class="inspection-filter" name="applicable_fuel_type"><option value="">Semua bahan bakar</option>@foreach (['All', 'Electric', 'Diesel', 'LPG'] as $fuel)<option value="{{ $fuel }}" @selected(request('applicable_fuel_type') == $fuel)>{{ $fuel }}</option>@endforeach</select><select class="inspection-filter" name="is_critical"><option value="">Semua prioritas</option><option value="1" @selected(request('is_critical') === '1')>Kritis</option><option value="0" @selected(request('is_critical') === '0')>Normal</option></select><select class="inspection-filter" name="is_active"><option value="">Semua status</option><option value="1" @selected(request('is_active') === '1')>Aktif</option><option value="0" @selected(request('is_active') === '0')>Nonaktif</option></select><button class="inspection-btn" type="submit">Filter</button></form>
        <div class="inspection-table-wrap"><table class="inspection-table"><thead><tr><th>No</th><th>Item</th><th>Kategori</th><th>Bahan bakar</th><th>Tipe input</th><th>Prioritas</th><th>Status</th><th style="text-align:right">Aksi</th></tr></thead><tbody>
            @forelse ($items as $item)
                <tr><td>{{ $items->firstItem() + $loop->index }}</td><td><div class="inspection-code">{{ $item->item_code }}</div><div class="inspection-name">{{ $item->item_name }}</div>@if ($item->description)<div class="inspection-description">{{ $item->description }}</div>@endif</td><td>{{ $item->category?->category_name ?? '-' }}</td><td>{{ $item->applicable_fuel_type ?? '-' }}</td><td>{{ $item->input_type ?? '-' }}</td><td><span class="inspection-badge {{ $item->is_critical ? 'inspection-badge-critical' : 'inspection-badge-normal' }}">{{ $item->is_critical ? 'Kritis' : 'Normal' }}</span></td><td><span class="inspection-badge {{ $item->is_active ? 'inspection-badge-active' : 'inspection-badge-inactive' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td><div class="inspection-actions"><a class="inspection-action inspection-action-detail" href="{{ route('master.inspection-items.show', $item) }}">Detail</a><a class="inspection-action inspection-action-edit" href="{{ route('master.inspection-items.edit', $item) }}">Edit</a><form class="delete-item-form" action="{{ route('master.inspection-items.destroy', $item) }}" method="POST" data-item-name="{{ $item->item_name }}">@csrf @method('DELETE')<button class="inspection-action inspection-action-delete" type="submit">Hapus</button></form></div></td></tr>
            @empty
                <tr><td colspan="8" style="padding: 42px; text-align:center; color:#64748b;">Belum ada item inspeksi yang sesuai.</td></tr>
            @endforelse
        </tbody></table></div>
        @if ($items->hasPages())<div class="inspection-pagination">{{ $items->links() }}</div>@endif
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-item-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const itemName = form.dataset.itemName;

                Swal.fire({
                    title: 'Hapus Item Inspeksi?',
                    text: 'Item "' + itemName + '" akan dihapus dari daftar.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar.',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonText: 'OK',
                confirmButtonColor: '#007bff'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif
    });
</script>
@endpush
@endsection