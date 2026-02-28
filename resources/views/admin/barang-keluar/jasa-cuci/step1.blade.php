@extends('layouts.app')

@section('title', 'Tambah Barang Keluar Jasa Cuci - Step 1')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Barang Keluar Jasa Cuci</h2>
                    <p class="text-gray-500 text-sm mt-1">Pilih tanggal dan lokasi jasa cuci.</p>
                </div>
                <a href="{{ route('barang-keluar.jasa-cuci.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Flow Indicator Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-6">
                <div class="flex items-center justify-center">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-teal-600 text-white font-bold text-lg mb-2 shadow-sm">1</div>
                        <span class="text-sm font-medium text-teal-600">Info Utama</span>
                    </div>
                    
                    <!-- Line -->
                    <div class="w-32 sm:w-48 border-t-2 border-gray-200 mx-4 mb-6"></div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center opacity-60">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 font-bold text-lg mb-2 border-2 border-white shadow-sm ring-1 ring-gray-200">2</div>
                        <span class="text-sm font-medium text-gray-500">Detail Barang</span>
                    </div>
                </div>
            </div>

            <!-- Form Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('barang-keluar.jasa-cuci.store-step1') }}" method="POST">
                        @csrf
                        <div class="space-y-6 max-w-3xl">
                            <div>
                                <label for="tanggal_kirim" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemberian Bahan <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_kirim" id="tanggal_kirim" required
                                    value="{{ session('jasa_cuci_step1.tanggal_kirim', date('Y-m-d')) }}"
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition duration-200">
                            </div>

                            <div>
                                <label for="lokasi_id" class="block text-sm font-medium text-gray-700 mb-1">Nama (Jasa Cuci) <span class="text-red-500">*</span></label>
                                <select name="lokasi_id" id="lokasi_id" required class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition duration-200">
                                    <option value="">-- Pilih Jasa Cuci --</option>
                                    @foreach($lokasis as $lokasi)
                                        <option value="{{ $lokasi->id }}" {{ session('jasa_cuci_step1.lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                            {{ $lokasi->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="bg-teal-600 border border-transparent rounded-lg shadow-sm py-2.5 px-6 inline-flex justify-center text-sm font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-200">
                                Lanjut ke Step 2
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
@endsection
