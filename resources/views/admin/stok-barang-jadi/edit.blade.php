@extends('layouts.app')

@section('title', 'Edit Stok Barang Jadi')

@section('content')
<div class="px-6 py-6 pb-20">
    <div class="max-w-4xl mx-auto">
        {{-- Header & Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('stok-barang-jadi.index', ['tab' => 'clop']) }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 mb-4 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Stok
            </a>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Stok Barang Jadi (Clop)</h1>
            <p class="text-sm text-gray-500">Edit data transaksi dengan batch <span class="font-semibold text-blue-600">{{ $stokBarangJadi->batch }}</span>.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan pada inputan Anda:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @php
            $isTransferGlobal = str_starts_with($stokBarangJadi->keterangan, 'Transfer dari Global Stok Grading');
        @endphp

        @if($isTransferGlobal)
            <div class="mb-6 p-4 rounded-md bg-yellow-50 border border-yellow-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                           <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian: Data Hasil Transfer</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Kuantitas (Berat dan Pcs) pada stok hasil Transfer dari Global tidak dapat diedit secara manual demi menjaga integritas data lintas-tabel. Jika terdapat kesalahan pencatatan waktu transfer, silakan <strong>Hapus</strong> data ini dari tabel (untuk memulangkan angka stok), lalu buat Transfer ulang.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('stok-barang-jadi.update', $stokBarangJadi->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Main Form Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Utama</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $stokBarangJadi->tanggal) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" required>
                        </div>
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $stokBarangJadi->keterangan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border {{ $isTransferGlobal ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}" {{ $isTransferGlobal ? 'readonly' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Items Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Item Transfer</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-white border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold w-64">Nama Barang (Grade)</th>
                                <th scope="col" class="px-6 py-4 font-semibold w-40">Jumlah Pcs/Box</th>
                                <th scope="col" class="px-6 py-4 font-semibold w-48">Berat (Gram)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="detail-container">
                            @foreach($stokBarangJadi->details as $index => $detail)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id }}">
                                        <div class="font-medium text-gray-900 py-2">{{ $detail->nama_barang ?: ($detail->namaBarang->nama_barang ?? 'Unknown') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="details[{{ $index }}][jumlah_pcs]" value="{{ old('details.'.$index.'.jumlah_pcs', $detail->jumlah_pcs) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border {{ $isTransferGlobal ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}" required min="0" {{ $isTransferGlobal ? 'readonly' : '' }}>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.01" name="details[{{ $index }}][berat]" value="{{ old('details.'.$index.'.berat', $detail->berat) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border font-mono weight-input {{ $isTransferGlobal ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}" required min="0.01" {{ $isTransferGlobal ? 'readonly' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/50">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-right font-medium text-gray-700">Total Berat:</td>
                                <td class="px-6 py-4 font-bold text-blue-700 font-mono text-lg" id="total-berat">
                                    0,00
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex justify-end pt-4 mb-10">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 border border-transparent rounded-lg shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('.weight-input');
    const totalDisplay = document.getElementById('total-berat');

    function calculateTotal() {
        let total = 0;
        weightInputs.forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) {
                total += val;
            }
        });
        totalDisplay.textContent = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total);
    }

    weightInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endsection
