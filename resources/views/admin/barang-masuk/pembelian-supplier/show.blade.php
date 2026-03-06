@extends('layouts.app')

@section('title', 'Detail Batch Pembelian Supplier')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Detail Batch Pembelian Supplier</h1>
            <a href="{{ route('barang-masuk.pembelian-supplier.index') }}" class="text-blue-600 hover:text-blue-800 bg-white px-4 py-2 rounded-md border border-blue-200 shadow-sm transition duration-200 flex items-center">
                &laquo; Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-blue-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-blue-800">Informasi Batch</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Batch No</p>
                    <p class="font-medium text-gray-900">BMP-{{ str_pad($barangMasukBatch->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Terima</p>
                    <p class="font-medium text-gray-900">{{ $barangMasukBatch->tanggal_terima ? \Carbon\Carbon::parse($barangMasukBatch->tanggal_terima)->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Supplier</p>
                    <p class="font-medium text-gray-900">{{ $barangMasukBatch->barangMasukSupplier->first()->supplier->nama ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-blue-800">Daftar Item Barang</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (Kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pcs</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Box</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($barangMasukBatch->barangMasukSupplier as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item->berat, 2, ',', '.') }} kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item->pcs, 0, ',', '.') }} pcs</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item->box, 0, ',', '.') }} box</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                <a href="{{ route('barang-masuk.pembelian-supplier.edit', $barangMasukBatch->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-200 shadow-sm">
                    Edit Batch Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
