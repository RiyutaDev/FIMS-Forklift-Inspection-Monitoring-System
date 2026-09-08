@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg bg-white p-6 shadow">
        <h1 class="text-2xl font-semibold text-slate-800">Master Forklift</h1>
        <p class="mt-2 text-sm text-slate-500">Kelola data forklift</p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-slate-500">
                        <th class="py-2">Kode</th>
                        <th class="py-2">Lokasi</th>
                        <th class="py-2">Fuel Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($forklifts as $forklift)
                        <tr class="border-b">
                            <td class="py-2">{{ $forklift->forklift_code }}</td>
                            <td class="py-2">{{ $forklift->location?->location_name ?? '-' }}</td>
                            <td class="py-2">{{ $forklift->fuel_type }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
