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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Card 1: Stok Gudang Demak --}}
                <a href="#"
                    class="group bg-white rounded-xl shadow-md p-8 border-2 border-transparent hover:border-indigo-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-14 h-14 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-500 transition-all duration-300">
                            <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition-all duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                        </div>
                        <h3
                            class="ml-4 text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition-all duration-300">
                            Stok Gudang Demak
                        </h3>
                    </div>
                    <p class="text-gray-600 mb-4">Lihat dan kelola ketersediaan stok barang di gudang Demak.</p>
                    <div
                        class="flex items-center text-indigo-600 font-medium group-hover:translate-x-2 transition-all duration-300">
                        Lihat Stok
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>

                {{-- Card 2: Stok Gudang Jasa Cuci --}}
                <a href="#"
                    class="group bg-white rounded-xl shadow-md p-8 border-2 border-transparent hover:border-teal-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-14 h-14 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-500 transition-all duration-300">
                            <svg class="w-8 h-8 text-teal-600 group-hover:text-white transition-all duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        <h3
                            class="ml-4 text-xl font-bold text-gray-800 group-hover:text-teal-600 transition-all duration-300">
                            Stok Gudang Jasa Cuci
                        </h3>
                    </div>
                    <p class="text-gray-600 mb-4">Lihat dan kelola ketersediaan stok barang di gudang Jasa Cuci.</p>
                    <div
                        class="flex items-center text-teal-600 font-medium group-hover:translate-x-2 transition-all duration-300">
                        Lihat Stok
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>

            </div>
        </div>
    </div>
@endsection