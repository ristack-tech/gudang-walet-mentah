@extends('layouts.app')

@section('title', 'Tambah Barang Keluar Demak - Step 2')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Barang Keluar Demak</h2>
                    <p class="text-gray-500 text-sm mt-1">Lengkapi data rincian barang keluar.</p>
                </div>
                <a href="{{ route('barang-keluar.demak.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Batal
                </a>
            </div>

            <!-- Flow Indicator Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-6">
                <div class="flex items-center justify-center">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold text-lg mb-2 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-sm font-medium text-green-600">Info Utama</span>
                    </div>
                    
                    <!-- Line -->
                    <div class="w-32 sm:w-48 border-t-2 border-green-500 mx-4 mb-6"></div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold text-lg mb-2 shadow-sm">2</div>
                        <span class="text-sm font-medium text-blue-600">Detail Barang</span>
                    </div>
                </div>
            </div>

            <!-- Form Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('barang-keluar.demak.store-step2') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            
                            <!-- First Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kode_lp" class="block text-sm font-medium text-gray-700 mb-1">Kode LP <span class="text-red-500">*</span></label>
                                    <input type="text" name="kode_lp" id="kode_lp" required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200">
                                </div>
                                <div>
                                    <label for="warna_id" class="block text-sm font-medium text-gray-700 mb-1">Kode Warna</label>
                                    <select name="warna_id" id="warna_id" class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200">
                                        <option value="">-- Pilih Kode Warna --</option>
                                        @foreach($warnas as $warna)
                                            <option value="{{ $warna->id }}">{{ $warna->kode_warna }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="gram" class="block text-sm font-medium text-gray-700 mb-1">Gram <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="gram" id="gram" required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200">
                                </div>
                                <div>
                                    <label for="pcs" class="block text-sm font-medium text-gray-700 mb-1">Pcs <span class="text-red-500">*</span></label>
                                    <input type="number" name="pcs" id="pcs" required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200">
                                </div>
                            </div>
                            
                            <!-- Third Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kadar_air" class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%)</label>
                                    <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200">
                                </div>
                                <div>
                                    <label for="timbangan_ulang" class="block text-sm font-medium text-gray-700 mb-1">Timbangan Ulang (Gram)</label>
                                    <input type="number" step="0.01" name="timbangan_ulang" id="timbangan_ulang"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200">
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('barang-keluar.demak.step1') }}" class="inline-flex items-center px-6 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <svg class="mr-2 -ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                Kembali ke Step 1
                            </a>
                            <button type="submit" class="bg-blue-600 border border-transparent rounded-lg shadow-sm py-2.5 px-8 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
@endsection
