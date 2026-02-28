@extends('layouts.app')

@section('title', 'Edit Barang Keluar Jasa Cuci')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Edit Barang Keluar Jasa Cuci
                    </h3>
                    <a href="{{ route('barang-keluar.jasa-cuci.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">
                        &larr; Kembali
                    </a>
                </div>
                <div class="px-6 py-5">
                    <form action="{{ route('barang-keluar.jasa-cuci.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            
                            <!-- Bagian 1: Data Utama -->
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h4 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Umum</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_kirim" class="block text-sm font-medium text-gray-700">Tanggal Pemberian Bahan</label>
                                        <input type="date" name="tanggal_kirim" id="tanggal_kirim" required
                                            value="{{ old('tanggal_kirim', $data->barangKeluar->tanggal_kirim) }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                    </div>
        
                                    <div>
                                        <label for="lokasi_id" class="block text-sm font-medium text-gray-700">Nama (Jasa Cuci)</label>
                                        <select name="lokasi_id" id="lokasi_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                            <option value="">-- Pilih Jasa Cuci --</option>
                                            @foreach($lokasis as $lokasi)
                                                <option value="{{ $lokasi->id }}" {{ (old('lokasi_id', $data->barangKeluar->lokasi_id ?? '') == $lokasi->id) ? 'selected' : '' }}>
                                                    {{ $lokasi->nama_lokasi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian 2: Data Detail -->
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h4 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Detail Bahan</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="kode_bahan_id" class="block text-sm font-medium text-gray-700">Kode Bahan</label>
                                        <select name="kode_bahan_id" id="kode_bahan_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                            <option value="">-- Pilih Kode Bahan --</option>
                                            @foreach($kodeBahans as $kode)
                                                <option value="{{ $kode->id }}" {{ (old('kode_bahan_id', $data->kode_bahan_id) == $kode->id) ? 'selected' : '' }}>
                                                    {{ $kode->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div>
                                        <label for="keterangan_cetak" class="block text-sm font-medium text-gray-700">Keterangan Cetak</label>
                                        <textarea name="keterangan_cetak" id="keterangan_cetak" rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">{{ old('keterangan_cetak', $data->keterangan_cetak) }}</textarea>
                                    </div>
        
                                    <div>
                                        <label for="berat_per_pcs" class="block text-sm font-medium text-gray-700">Berat Per Pcs</label>
                                        <input type="number" step="0.01" name="berat_per_pcs" id="berat_per_pcs" required
                                            value="{{ old('berat_per_pcs', $data->berat_per_pcs) }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                    </div>
        
                                    <div>
                                        <label for="kadar_air" class="block text-sm font-medium text-gray-700">Kadar Air (%)</label>
                                        <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                            value="{{ old('kadar_air', $data->kadar_air) }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('barang-keluar.jasa-cuci.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Batal
                            </a>
                            <button type="submit" class="bg-teal-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
