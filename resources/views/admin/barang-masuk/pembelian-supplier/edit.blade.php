@extends('layouts.app')

@section('title', 'Edit Pembelian Supplier')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Pembelian Supplier</h1>
            <a href="{{ route('barang-masuk.pembelian-supplier.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition duration-200">
                &laquo; Batal & Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="text-red-700">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form action="{{ route('barang-masuk.pembelian-supplier.update', $barangMasukSupplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Utama</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="tanggal_terima" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terima <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_terima" name="tanggal_terima" 
                            value="{{ old('tanggal_terima', $barangMasukSupplier->barangMasuk->tanggal_terima ?? '') }}"
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
                                <option value="{{ $b->id }}" {{ old('barang_id', $barangMasukSupplier->barang_id) == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                        <select id="supplier_id" name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('supplier_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($supplier as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id', $barangMasukSupplier->supplier_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Detail Kuantitas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" step="0.01" id="berat" name="berat" 
                                value="{{ old('berat', (float)$barangMasukSupplier->berat) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('berat') border-red-500 @enderror" 
                                required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500">kg</div>
                        </div>
                        @error('berat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pcs" class="block text-sm font-medium text-gray-700 mb-1">Pcs <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="pcs" name="pcs" 
                                value="{{ old('pcs', $barangMasukSupplier->pcs) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('pcs') border-red-500 @enderror" 
                                required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500">pcs</div>
                        </div>
                        @error('pcs')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="box" class="block text-sm font-medium text-gray-700 mb-1">Box <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="box" name="box" 
                                value="{{ old('box', $barangMasukSupplier->box) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('box') border-red-500 @enderror" 
                                required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500">box</div>
                        </div>
                        @error('box')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition duration-200 shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
