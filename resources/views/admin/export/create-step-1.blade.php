@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Export Baru - Langkah 1</h1>
        <p class="text-gray-600 mt-2">Masukan tanggal pengiriman dan catatan untuk batch eksport ini.</p>
    </div>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl border border-gray-200">
        <form action="{{ route('export.create.step2') }}" method="GET">
            <div class="mb-4">
                <label for="tanggal_transfer" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengeluaran (Transfer) <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_transfer" name="tanggal_transfer" value="{{ old('tanggal_transfer', date('Y-m-d')) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan / Catatan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan (tujuan ekspor, nomor DO, dll)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('export.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md text-sm font-medium transition duration-200">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition duration-200">
                    Pilih Barang &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
