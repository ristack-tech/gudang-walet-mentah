@extends('layouts.app')

@section('title', 'Detail Pembelian Supplier')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Detail Pembelian Supplier</h1>
            <a href="{{ route('barang-masuk.pembelian-supplier.index') }}" class="text-blue-600 hover:text-blue-800 bg-white px-4 py-2 rounded-md border border-blue-200 shadow-sm transition duration-200 flex items-center">
                &laquo; Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-blue-800">Informasi Utama</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Terima</p>
                    <p class="font-medium text-gray-900">{{ $barangMasukSupplier->barangMasuk->tanggal_terima ? \Carbon\Carbon::parse($barangMasukSupplier->barangMasuk->tanggal_terima)->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Supplier</p>
                    <p class="font-medium text-gray-900">{{ $barangMasukSupplier->supplier->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Barang</p>
                    <p class="font-medium text-gray-900">{{ $barangMasukSupplier->barang->nama_barang ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-blue-50 px-6 py-4 border-y border-gray-100">
                <h2 class="text-lg font-medium text-blue-800">Detail Kuantitas</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Berat</p>
                    <p class="font-medium text-gray-900">{{ number_format($barangMasukSupplier->berat, 2, ',', '.') }} kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pcs</p>
                    <p class="font-medium text-gray-900">{{ number_format($barangMasukSupplier->pcs, 0, ',', '.') }} pcs</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Box</p>
                    <p class="font-medium text-gray-900">{{ number_format($barangMasukSupplier->box, 0, ',', '.') }} box</p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                <a href="{{ route('barang-masuk.pembelian-supplier.edit', $barangMasukSupplier->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-200">
                    Edit Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
