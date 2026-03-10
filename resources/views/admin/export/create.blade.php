@extends('layouts.app')

@section('title', 'Tambah Export Barang')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-6 flex justify-between items-center max-w-5xl mx-auto">
            <a href="{{ route('export.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Daftar Export
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-5xl mx-auto">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-900">Tambah Export Barang</h2>
            </div>
            
            <form action="{{ route('export.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6 mb-6">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal_transfer" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_transfer" id="tanggal_transfer"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm @error('tanggal_transfer') border-red-300 @enderror"
                            value="{{ old('tanggal_transfer', date('Y-m-d')) }}" required>
                        @error('tanggal_transfer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Barang -->
                    <div>
                        <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <select name="barang_id" id="barang_id"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm @error('barang_id') border-red-300 @enderror" required>
                            <option value="">-- Pilih Nama Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Berat -->
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">
                            Berat (Gram) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="berat" id="berat"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm @error('berat') border-red-300 @enderror"
                            value="{{ old('berat') }}" placeholder="" required>
                        @error('berat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pcs -->
                    <div>
                        <label for="pcs" class="block text-sm font-medium text-gray-700 mb-1">
                            Pcs <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="pcs" id="pcs"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm @error('pcs') border-red-300 @enderror"
                            value="{{ old('pcs') }}" placeholder="" required>
                        @error('pcs')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Box -->
                    <div>
                        <label for="box" class="block text-sm font-medium text-gray-700 mb-1">
                            Box <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="box" id="box"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm @error('box') border-red-300 @enderror"
                            value="{{ old('box') }}" placeholder="" required>
                        @error('box')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="harga" id="harga"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm @error('harga') border-red-300 @enderror"
                            value="{{ old('harga') }}" placeholder="" required>
                        @error('harga')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6 mt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition duration-200 shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambahkan Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
