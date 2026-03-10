@extends('layouts.app')

@section('title', 'Stok Gudang Global')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    Stok Gudang Global
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Rekapitulasi gabungan stok gudang dari seluruh lokasi (Demak & Jasa Cuci)
                </p>
            </div>
            <a href="{{ route('stok-gudang.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>

        {{-- Filters Section --}}
        <form method="GET" action="{{ route('stok-gudang.global') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="flex-grow w-full relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama barang..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-900 placeholder-gray-400 bg-gray-50 transition-colors hover:bg-white">
                </div>
                <div class="w-full sm:w-auto flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none justify-center inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Terapkan
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('stok-gudang.global') }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-6 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative z-10">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Box</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pcs</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Berat (Gr)</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($globalStock as $index => $item)
                            <tr class="hover:bg-purple-50 hover:bg-opacity-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs uppercase mr-3">
                                            {{ substr($item->nama_barang, 0, 2) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item->total_box ?? 0, 0, ',', '.') }} Box</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item->total_pcs ?? 0, 0, ',', '.') }} Pcs</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ number_format($item->total_gram ?? 0, 2, ',', '.') }} gr
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('stok-gudang.global.show', $item->id) }}" class="inline-flex items-center px-3 py-1.5 border border-purple-200 text-purple-600 bg-purple-50 hover:bg-purple-600 hover:border-purple-600 hover:text-white rounded text-xs transition-all duration-200 shadow-sm whitespace-nowrap group">
                                        <svg class="w-4 h-4 mr-1.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Buka Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                        <p class="text-base font-medium">Tidak ada data stok global yang ditemukan.</p>
                                        <p class="text-sm mt-1 text-gray-400">Coba ubah kata kunci pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($globalStock->count() > 0)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-sm text-gray-500 text-right">
                    Menampilkan <span class="font-medium text-gray-900">{{ $globalStock->count() }}</span> jenis barang
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
