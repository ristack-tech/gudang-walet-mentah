@extends('layouts.app')

@section('title', 'Tambah Export Baru - Step 1')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Tambah Export Baru - Step 1</h1>
                    <p class="mt-1 text-sm text-gray-600">Lengkapi data awal transaksi untuk Batch Export ini.</p>
                </div>
                <a href="{{ route('export.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between max-w-xl mx-auto">
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                            1</div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Data Awal</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-2 sm:mx-4 -mt-6"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 font-semibold text-sm">
                            2</div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-gray-400">Pilih Barang</span>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('export.create.step2') }}" id="mainForm" novalidate>
                <div class="bg-white shadow-sm border rounded-lg">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-base font-semibold text-gray-900 mb-4">Informasi Export</h2>

                        <div class="space-y-6">
                            <!-- Tanggal Pengeluaran -->
                            <div>
                                <label for="tanggal_transfer" class="block text-sm font-medium text-gray-700">
                                    Tanggal Pengeluaran (Transfer) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative mt-1.5 shadow-sm">
                                    <input type="date" name="tanggal_transfer" id="tanggal_transfer"
                                        value="{{ old('tanggal_transfer', date('Y-m-d')) }}"
                                        class="block w-full sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900 font-semibold pl-4 pr-10 py-2.5"
                                        required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">
                                    Keterangan / Catatan (Opsional)
                                </label>
                                <div class="mt-1.5">
                                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan (tujuan ekspor, nomor DO, dll)"
                                        class="block w-full sm:text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-gray-900">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-lg flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-700 w-full sm:w-auto transition-colors duration-200 shadow-sm">
                            Pilih Barang &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
    </style>
@endsection
