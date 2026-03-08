@extends('layouts.app')

@section('title', 'Stok Gudang')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Stok Gudang</h1>
                <p class="text-gray-600">Pusat pengaturan dan pemantauan data ketersediaan stok barang.</p>
            </div>

            {{-- Menu Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Card 1: Stok Gudang Demak --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                    <div class="flex justify-between items-start mb-6">
                        {{-- Icon --}}
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                        </div>
                        {{-- Badge --}}
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-md">
                            {{ $demakCount }} Item
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Stok Gudang Demak</h3>
                    <p class="text-sm text-gray-500 mb-6 flex-grow">Lihat data stok gudang demak</p>
                    
                    <a href="{{ route('stok-gudang.demak') }}" class="w-full text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg border border-gray-200 transition-colors">
                        Buka Detail
                    </a>
                </div>

                {{-- Card 2: Stok Gudang Jasa Cuci --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                    <div class="flex justify-between items-start mb-6">
                        {{-- Icon --}}
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        {{-- Badge --}}
                        <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-3 py-1 rounded-md">
                            {{ $jasaCuciCount }} Item
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Stok Gudang Jasa Cuci</h3>
                    <p class="text-sm text-gray-500 mb-6 flex-grow">Lihat data stok gudang jasa cuci</p>
                    
                    <a href="{{ route('stok-gudang.jasa-cuci') }}" class="w-full text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg border border-gray-200 transition-colors">
                        Buka Detail
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection