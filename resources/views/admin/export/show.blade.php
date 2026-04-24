@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Detail Export Batch: {{ $export->batch }}</h1>
            <p class="text-gray-600 mt-1">Tanggal Transfer: {{ \Carbon\Carbon::parse($export->tanggal_transfer)->format('d F Y') }}</p>
        </div>
        <a href="{{ route('export.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md text-sm font-medium transition duration-200">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 mb-8 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Umum</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Keterangan / Catatan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $export->keterangan ?: 'Tidak ada keterangan.' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Record Timestamp</dt>
                <dd class="mt-1 text-sm text-gray-900">Dibuat: {{ $export->created_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Rincian Item yang Dikeluarkan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang (Sisa Stok Awal)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Box</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (gr)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($export->exportDetails as $index => $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $detail->stokBarangJadiDetail->nama_barang ?? 'Unknown (Terhapus)' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ $detail->box }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium text-red-600">
                            -{{ $detail->berat }}g
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ number_format($detail->harga, 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Data rincian tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-right text-sm text-gray-700 uppercase">Total Keseluruhan:</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">{{ $export->exportDetails->sum('box') }}</td>
                        <td class="px-6 py-4 text-right text-sm text-red-600">-{{ $export->exportDetails->sum('berat') }}g</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">{{ number_format($export->exportDetails->sum('harga'), 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
