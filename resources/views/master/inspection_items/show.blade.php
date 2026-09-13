@extends('layouts.app')

@section('title', 'Detail Item Ceklis')

@section('content')
<style>
	.inspection-detail { min-height: 100vh; padding: 28px; background: #f1f5f9; }
	.detail-hero { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 28px; color: #fff; border-radius: 18px; background: linear-gradient(135deg, #0f172a, #1e3a8a 58%, #2563eb); box-shadow: 0 10px 25px rgba(15,23,42,.15); }
	.detail-hero small { color: #bfdbfe; font-size: 12px; }
	.detail-hero h1 { margin: 8px 0 0; font-size: 26px; font-weight: 800; }
	.detail-hero p { margin: 8px 0 0; color: #dbeafe; font-size: 13px; }
	.detail-code { color: #bfdbfe; font-size: 13px; font-weight: 800; }
	.detail-actions { display: flex; gap: 9px; }
	.detail-button { display: inline-flex; align-items: center; min-height: 40px; padding: 0 15px; border-radius: 10px; font-size: 12px; font-weight: 800; text-decoration: none; }
	.detail-button-edit { color: #1e3a8a; background: #dbeafe; }
	.detail-button-back { color: #e2e8f0; border: 1px solid rgba(255,255,255,.25); }
	.detail-panel { margin-top: 20px; padding: 24px; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 5px 18px rgba(15,23,42,.06); }
	.detail-panel h2 { margin: 0 0 18px; color: #0f172a; font-size: 17px; font-weight: 800; }
	.detail-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
	.detail-field { padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; }
	.detail-field-wide { grid-column: 1 / -1; }
	.detail-label { display: block; margin-bottom: 6px; color: #64748b; font-size: 11px; font-weight: 800; text-transform: uppercase; }
	.detail-value { color: #0f172a; font-size: 14px; font-weight: 700; }
	.detail-description { color: #475569; font-size: 13px; line-height: 1.6; }
	.detail-badge { display: inline-flex; padding: 5px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; }
	.detail-badge-green { color: #047857; background: #d1fae5; }
	.detail-badge-red { color: #b91c1c; background: #fee2e2; }
	@media (max-width: 700px) { .inspection-detail { padding: 15px; } .detail-hero { align-items: flex-start; flex-direction: column; padding: 22px; } .detail-grid { grid-template-columns: 1fr 1fr; } .detail-field-wide { grid-column: 1 / -1; } }
</style>
<div class="inspection-detail">
	<section class="detail-hero"><div><small>Master Data / Item Ceklis / Detail</small><div class="detail-code">{{ $inspectionItem->item_code }}</div><h1>{{ $inspectionItem->item_name }}</h1><p>{{ $inspectionItem->category?->category_name ?? 'Tanpa kategori' }}</p></div><div class="detail-actions"><a class="detail-button detail-button-back" href="{{ route('master.inspection-items.index') }}">Kembali</a><a class="detail-button detail-button-edit" href="{{ route('master.inspection-items.edit', $inspectionItem) }}">Edit Item</a></div></section>
	<section class="detail-panel"><h2>Konfigurasi Item</h2><div class="detail-grid"><div class="detail-field"><span class="detail-label">Kategori</span><span class="detail-value">{{ $inspectionItem->category?->category_name ?? '-' }}</span></div><div class="detail-field"><span class="detail-label">Bahan bakar</span><span class="detail-value">{{ $inspectionItem->applicable_fuel_type ?? '-' }}</span></div><div class="detail-field"><span class="detail-label">Tipe input</span><span class="detail-value">{{ $inspectionItem->input_type ?? '-' }}</span></div><div class="detail-field"><span class="detail-label">Satuan</span><span class="detail-value">{{ $inspectionItem->unit ?? '-' }}</span></div><div class="detail-field"><span class="detail-label">Urutan tampilan</span><span class="detail-value">{{ $inspectionItem->sort_order }}</span></div><div class="detail-field"><span class="detail-label">Status</span><span class="detail-badge {{ $inspectionItem->is_active ? 'detail-badge-green' : 'detail-badge-red' }}">{{ $inspectionItem->is_active ? 'Aktif' : 'Nonaktif' }}</span></div><div class="detail-field detail-field-wide"><span class="detail-label">Deskripsi</span><div class="detail-description">{{ $inspectionItem->description ?: 'Tidak ada deskripsi untuk item ini.' }}</div></div></div></section>
	<section class="detail-panel"><h2>Persyaratan Checklist</h2><div class="detail-grid"><div class="detail-field"><span class="detail-label">Prioritas</span><span class="detail-badge {{ $inspectionItem->is_critical ? 'detail-badge-red' : 'detail-badge-green' }}">{{ $inspectionItem->is_critical ? 'Item kritis' : 'Item normal' }}</span></div><div class="detail-field"><span class="detail-label">Foto</span><span class="detail-badge {{ $inspectionItem->requires_photo ? 'detail-badge-red' : 'detail-badge-green' }}">{{ $inspectionItem->requires_photo ? 'Wajib' : 'Tidak wajib' }}</span></div><div class="detail-field"><span class="detail-label">Catatan</span><span class="detail-badge {{ $inspectionItem->requires_note ? 'detail-badge-red' : 'detail-badge-green' }}">{{ $inspectionItem->requires_note ? 'Wajib' : 'Tidak wajib' }}</span></div></div></section>
</div>
@endsection
