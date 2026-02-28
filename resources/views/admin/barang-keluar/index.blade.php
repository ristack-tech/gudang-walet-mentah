@extends('layouts.app')

@section('title', 'Manajemen Barang Keluar')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Barang Keluar</h1>
                <p class="text-gray-600">Pusat pengaturan data untuk pengeluaran barang menuju berbagai lokasi.</p>
            </div>

            {{-- Menu Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Card 1: Barang Keluar Demak --}}
                <a href="{{ route('barang-keluar.demak.index') }}"
                    class="group bg-white rounded-xl shadow-md p-8 border-2 border-transparent hover:border-purple-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-500 transition-all duration-300">
                            <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-all duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <h3
                            class="ml-4 text-xl font-bold text-gray-800 group-hover:text-purple-600 transition-all duration-300">
                            Barang Keluar Demak
                        </h3>
                    </div>
                    <p class="text-gray-600 mb-4">Kelola proses dan data barang yang keluar menuju fasilitas Demak.</p>
                    <div
                        class="flex items-center text-purple-600 font-medium group-hover:translate-x-2 transition-all duration-300">
                        Kelola Data
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>

                {{-- Card 2: Barang Keluar Jasa Cuci --}}
                <a href="{{ route('barang-keluar.jasa-cuci.index') }}"
                    class="group bg-white rounded-xl shadow-md p-8 border-2 border-transparent hover:border-teal-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-14 h-14 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-500 transition-all duration-300">
                            <svg class="w-8 h-8 text-teal-600 group-hover:text-white transition-all duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <h3
                            class="ml-4 text-xl font-bold text-gray-800 group-hover:text-teal-600 transition-all duration-300">
                            Barang Keluar Jasa Cuci
                        </h3>
                    </div>
                    <p class="text-gray-600 mb-4">Kelola proses dan pencatatan barang yang dikirim ke Jasa Cuci.</p>
                    <div
                        class="flex items-center text-teal-600 font-medium group-hover:translate-x-2 transition-all duration-300">
                        Kelola Data
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
