@extends('layouts.app')

@section('title', 'Tambah Data Pembelian Supplier - Step 1')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-blue-600">
                    <div class="rounded-full bg-blue-600 text-white h-8 w-8 flex items-center justify-center font-bold">1</div>
                    <span class="ml-2 font-medium">Data Awal</span>
                </div>
                <div class="flex-1 border-t-2 border-gray-300 mx-4"></div>
                <div class="flex items-center text-gray-400">
                    <div class="rounded-full bg-gray-300 text-white h-8 w-8 flex items-center justify-center font-bold">2</div>
                    <span class="ml-2 font-medium">Detail Barang</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="mb-6 border-b border-gray-100 pb-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Langkah 1: Data Awal</h2>
                <a href="{{ route('barang-masuk.pembelian-supplier.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Batal & Kembali</a>
            </div>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="text-red-700">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('barang-masuk.pembelian-supplier.store.step1') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="tanggal_terima" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terima <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_terima" name="tanggal_terima" 
                            value="{{ old('tanggal_terima', $step1Data['tanggal_terima'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('tanggal_terima') border-red-500 @enderror" 
                            required>
                        @error('tanggal_terima')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
                        <select id="barang_id" name="barang_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('barang_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" {{ old('barang_id', $step1Data['barang_id'] ?? '') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                        <select id="supplier_id" name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('supplier_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($supplier as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id', $step1Data['supplier_id'] ?? '') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 shadow-sm">
                        Lanjut ke Langkah 2 &raquo;
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
