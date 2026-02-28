@extends('layouts.app')

@section('title', 'Detail Batch Jasa Cuci')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Detail Batch Jasa Cuci</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Batch ID: <span class="font-medium text-blue-600">BMJC-{{ str_pad($barangMasukBatch->id, 5, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('barang-masuk.jasa-cuci.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <!-- Kiri: Info Batch (Header) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Transaksi</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Terima</span>
                            <span class="text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($barangMasukBatch->tanggal_terima)->format('d M Y') }}
                            </span>
                        </div>
                        
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Lokasi Jasa Cuci</span>
                            <span class="text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ $barangMasukBatch->barangMasukJasaCuci->first()->lokasiJasaCuci->nama_lokasi ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-100 p-6">
                    <h2 class="text-sm font-semibold text-blue-800 mb-3 uppercase tracking-wider">Ringkasan</h2>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-600 font-medium">Total Item Barang</span>
                        <span class="text-2xl font-bold text-blue-700">{{ $barangMasukBatch->barangMasukJasaCuci->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Kanan: Tabel Detail Item -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Daftar Item Barang Masuk</h2>
                        <a href="{{ route('barang-masuk.jasa-cuci.edit', $barangMasukBatch->id) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Batch
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesifikasi Barang</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Berat & Susut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($barangMasukBatch->barangMasukJasaCuci as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 mb-1">
                                                {{ $item->barang->nama_barang ?? '-' }}
                                            </div>
                                            <div class="text-sm text-blue-600 font-medium flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                {{ $item->kodeBahan->nama ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex flex-col items-end space-y-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Berat/Pcs: <strong class="ml-1">{{ number_format($item->berat_per_pcs, 2) }} gr</strong>
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                                    Susut: <strong class="ml-1">{{ number_format($item->persentase_susut, 2) }}%</strong>
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    Kadar Air: <strong class="ml-1">{{ number_format($item->kadar_air, 2) }}%</strong>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex justify-center items-center text-gray-400 mb-2">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <p class="text-sm text-gray-500">Tidak ada detail item yang ditemukan dalam batch ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
