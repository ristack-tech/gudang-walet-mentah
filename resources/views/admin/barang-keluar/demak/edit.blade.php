@extends('layouts.app')

@section('title', 'Edit Barang Keluar Demak')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Edit Barang Keluar Demak
                    </h3>
                    <a href="{{ route('barang-keluar.demak.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        &larr; Kembali
                    </a>
                </div>
                <div class="px-6 py-5">
                    <form action="{{ route('barang-keluar.demak.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            
                            <!-- Bagian 1: Data Umum -->
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h4 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Umum</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_kirim" class="block text-sm font-medium text-gray-700">Tanggal Kirim</label>
                                        <input type="date" name="tanggal_kirim" id="tanggal_kirim" required
                                            value="{{ old('tanggal_kirim', $data->barangKeluar->tanggal_kirim) }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
        
                                    <div>
                                        <label for="partai_id" class="block text-sm font-medium text-gray-700">Partai</label>
                                        <select name="partai_id" id="partai_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">-- Pilih Partai --</option>
                                            @foreach($partais as $partai)
                                                <option value="{{ $partai->id }}" {{ (old('partai_id', $data->partai_id) == $partai->id) ? 'selected' : '' }}>
                                                    {{ $partai->nama_partai }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian 2: Data Detail -->
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h4 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Detail Barang</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="kode_lp" class="block text-sm font-medium text-gray-700">Kode LP</label>
                                        <input type="text" name="kode_lp" id="kode_lp" required
                                            value="{{ old('kode_lp', $data->kode_lp) }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
        
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="gram" class="block text-sm font-medium text-gray-700">Gram</label>
                                            <input type="number" step="0.01" name="gram" id="gram" required
                                                value="{{ old('gram', $data->gram) }}"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="pcs" class="block text-sm font-medium text-gray-700">Pcs</label>
                                            <input type="number" name="pcs" id="pcs" required
                                                value="{{ old('pcs', $data->pcs) }}"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="warna_id" class="block text-sm font-medium text-gray-700">Kode Warna</label>
                                        <select name="warna_id" id="warna_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">-- Pilih Kode Warna --</option>
                                            @foreach($warnas as $warna)
                                                <option value="{{ $warna->id }}" {{ (old('warna_id', $data->warna_id) == $warna->id) ? 'selected' : '' }}>
                                                    {{ $warna->kode_warna }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="kadar_air" class="block text-sm font-medium text-gray-700">Kadar Air (%)</label>
                                            <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                                value="{{ old('kadar_air', $data->kadar_air) }}"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="timbangan_ulang" class="block text-sm font-medium text-gray-700">Timbangan Ulang (Gram)</label>
                                            <input type="number" step="0.01" name="timbangan_ulang" id="timbangan_ulang"
                                                value="{{ old('timbangan_ulang', $data->timbangan_ulang) }}"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>
        
                                    <div>
                                        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('keterangan', $data->keterangan) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('barang-keluar.demak.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
