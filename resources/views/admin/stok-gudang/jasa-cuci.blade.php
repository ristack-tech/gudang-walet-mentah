@extends('layouts.app')

@section('title', 'Detail Stok Gudang - Jasa Cuci')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            {{-- Breadcrumb & Back --}}
            <div class="mb-4">
                <a href="{{ route('stok-gudang.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Stok Gudang
                </a>
            </div>

            <!-- Header & Search -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Stok Gudang Jasa Cuci</h1>
                <p class="text-gray-500 text-sm mb-6">Pilih jenis barang untuk melihat detail stok dari Jasa Cuci.</p>

                <div class="bg-white p-4 rounded-xl border border-gray-200">
                    <form action="{{ route('stok-gudang.jasa-cuci') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Cari Jenis Barang...">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Cari
                            </button>
                            <a href="{{ route('stok-gudang.jasa-cuci') }}"
                                class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cards Grid -->
            @if($jasaCuciStock->isEmpty())
                <div class="flex flex-col items-center justify-center bg-white border border-gray-200 rounded-lg p-12 min-h-[300px] text-center w-full">
                    <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-base font-semibold text-gray-900">Tidak ada data stok</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada barang masuk dari Jasa Cuci.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($jasaCuciStock as $item)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-6">
                                {{-- Icon --}}
                                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                </div>
                                {{-- Pcs Badge --}}
                                <span class="bg-orange-50 text-orange-600 text-xs font-semibold px-3 py-1.5 rounded">
                                    {{ number_format($item->total_pcs) }} Item
                                </span>
                            </div>
                            
                            <h3 class="text-base font-bold text-gray-900 mb-1 uppercase">{{ $item->nama_barang }}</h3>
                            <p class="text-sm text-gray-500 mb-6 flex-grow">
                                Total Berat: {{ number_format($item->total_gram, 2) }} Gr
                            </p>
                            
                            <a href="{{ route('stok-gudang.jasa-cuci.show', $item->id) }}" class="w-full text-center px-4 py-2 bg-transparent hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-200 transition-colors">
                                Buka Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
