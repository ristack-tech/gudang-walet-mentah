@extends('layouts.app')

@section('title', 'Detail Stok Barang Jadi')

@section('content')
<div class="px-6 py-6 pb-20">
    <div class="max-w-7xl mx-auto">
        {{-- Header & Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('stok-barang-jadi.index', ['tab' => 'clop']) }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 mb-4 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Stok
            </a>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Stok Barang Jadi (Clop)</h1>
            <p class="text-sm text-gray-500">Menampilkan detail transaksi masuk barang jadi dengan batch <span class="font-semibold text-blue-600">{{ $stokBarangJadi->batch }}</span>.</p>
        </div>

        {{-- Info Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 mb-1">Tanggal Transaksi</p>
                    <p class="text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($stokBarangJadi->tanggal)->translatedFormat('d F Y') }}</p>
                </div>
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 mb-1">Keterangan</p>
                    <p class="text-base font-semibold text-gray-900">{{ $stokBarangJadi->keterangan ?: '-' }}</p>
                </div>
                <div class="p-6 bg-blue-50/50">
                    <p class="text-sm font-medium text-blue-600 mb-1">Total Berat Terakumulasi</p>
                    <p class="text-2xl font-bold text-blue-700">{{ number_format($stokBarangJadi->details->sum('berat'), 2, ',', '.') }} <span class="text-sm font-medium text-blue-600">Gram</span></p>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="bg-white px-6 py-5 border-b border-gray-100 rounded-t-xl shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Grade / Item</h2>
        </div>
        <div class="bg-white shadow-sm border border-t-0 border-gray-100 rounded-b-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">No</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nama Barang / Grade</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Jumlah (Pcs/Box)</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right">Berat (Gram)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($stokBarangJadi->details as $index => $detail)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $detail->nama_barang ?: ($detail->namaBarang->nama_barang ?? 'Unknown') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-gray-600">
                                    {{ number_format($detail->jumlah_box, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-medium text-gray-900">
                                    {{ number_format($detail->berat, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada item detail ditemukan.
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
