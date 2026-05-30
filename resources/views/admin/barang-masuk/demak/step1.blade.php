@extends('layouts.app')

@section('title', 'Tambah Data Demak - Step 1')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Data Masuk Demak</h1>
                <p class="mt-1 text-sm text-gray-600">Pilih tanggal terima dan lokasi asal barang.</p>
            </div>
            <a href="{{ route('barang-masuk.demak.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                Kembali
            </a>
        </div>

        <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between max-w-xl mx-auto">
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                        1</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Data Awal</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 mx-2 sm:mx-4 -mt-6"></div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 font-semibold text-sm">
                        2</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-gray-400">Pilih & Input Barang</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('barang-masuk.demak.store.step1') }}" id="mainForm">
            @csrf
            <div class="bg-white shadow-sm border rounded-lg">
                <div class="p-6 border-b border-gray-100">
                    <div class="space-y-6">
                        <!-- Tanggal Terima -->
                        <div>
                            <label for="tanggal_terima" class="block text-sm font-medium text-gray-700">Tanggal Terima <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_terima" id="tanggal_terima" value="{{ old('tanggal_terima', $step1Data['tanggal_terima'] ?? date('Y-m-d')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3" required>
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label for="transaksi_dari_id" class="block text-sm font-medium text-gray-700">Pilih Lokasi <span class="text-red-500">*</span></label>
                            <select id="transaksi_dari_id" name="transaksi_dari_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3" required>
                                <option value="">-- Pilih Lokasi Asal --</option>
                                @foreach($lokasiList as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ old('transaksi_dari_id', $step1Data['transaksi_dari_id'] ?? '') == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                            Lanjut ke Step 2
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
