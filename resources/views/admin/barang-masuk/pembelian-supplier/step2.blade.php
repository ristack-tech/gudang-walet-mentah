@extends('layouts.app')

@section('title', 'Tambah Data Pembelian Supplier - Step 2')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-blue-600">
                    <div class="rounded-full bg-blue-600 text-white h-8 w-8 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="ml-2 font-medium">Data Awal</span>
                </div>
                <div class="flex-1 border-t-2 border-blue-600 mx-4"></div>
                <div class="flex items-center text-blue-600">
                    <div class="rounded-full bg-blue-600 text-white h-8 w-8 flex items-center justify-center font-bold">2</div>
                    <span class="ml-2 font-medium">Detail Barang</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="mb-6 border-b border-gray-100 pb-4 flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                <div>
                    <p class="text-sm text-gray-500">Supplier Terpilih:</p>
                    <p class="font-semibold text-lg text-gray-800">{{ $supplier->nama }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Tanggal Terima:</p>
                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($step1Data['tanggal_terima'])->format('d M Y') }}</p>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="text-red-700">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('barang-masuk.pembelian-supplier.store.step2') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" step="0.01" id="berat" name="berat" value="{{ old('berat') }}"
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
                            <input type="number" id="pcs" name="pcs" value="{{ old('pcs') }}"
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
                            <input type="number" id="box" name="box" value="{{ old('box') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('box') border-red-500 @enderror" 
                                required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500">box</div>
                        </div>
                        @error('box')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center border-t border-gray-100 pt-6">
                    <a href="{{ route('barang-masuk.pembelian-supplier.create.step1') }}" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200 flex items-center">
                        &laquo; Kembali ke Langkah 1
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-8 rounded-md transition duration-200 shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
