@extends('layouts.app')

@section('title', 'Tambah Barang Keluar Demak - Step 2')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Barang Keluar Demak</h2>
                    <p class="text-gray-500 text-sm mt-1">Lengkapi data rincian barang keluar untuk partai <strong>{{ $partai->nama_partai ?? '-' }}</strong>.</p>
                </div>
                <a href="{{ route('barang-keluar.demak.step1') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                    Kembali
                </a>
            </div>

            <!-- Flow Indicator Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-6">
                <div class="flex items-center justify-center">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold text-lg mb-2 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-sm font-medium text-green-600">Info Utama</span>
                    </div>
                    
                    <!-- Line -->
                    <div class="w-32 sm:w-48 border-t-2 border-green-500 mx-4 mb-6"></div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold text-lg mb-2 shadow-sm">2</div>
                        <span class="text-sm font-medium text-blue-600">Detail Barang</span>
                    </div>
                </div>
            </div>


            <!-- Form Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 sm:p-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Detail Barang</h3>
                    <form action="{{ route('barang-keluar.demak.add.step2.item') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            
                            <!-- First Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kode_lp" class="block text-sm font-medium text-gray-700 mb-1">Kode LP <span class="text-red-500">*</span></label>
                                    <input type="text" name="kode_lp" id="kode_lp" required value="{{ old('kode_lp') }}"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200 @error('kode_lp') border-red-500 @enderror">
                                    @error('kode_lp')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="warna_id" class="block text-sm font-medium text-gray-700 mb-1">Kode Warna</label>
                                    <select name="warna_id" id="warna_id" class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200 @error('warna_id') border-red-500 @enderror">
                                        <option value="">-- Pilih Kode Warna --</option>
                                        @foreach($warnas as $warna)
                                            <option value="{{ $warna->id }}" {{ old('warna_id') == $warna->id ? 'selected' : '' }}>{{ $warna->kode_warna }}</option>
                                        @endforeach
                                    </select>
                                    @error('warna_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="gram" class="block text-sm font-medium text-gray-700 mb-1">Gram <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0" name="gram" id="gram" required value="{{ old('gram') }}"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200 @error('gram') border-red-500 @enderror">
                                    @error('gram')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="pcs" class="block text-sm font-medium text-gray-700 mb-1">Pcs <span class="text-red-500">*</span></label>
                                    <input type="number" min="0" name="pcs" id="pcs" required value="{{ old('pcs') }}"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200 @error('pcs') border-red-500 @enderror">
                                    @error('pcs')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Third Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kadar_air" class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="kadar_air" id="kadar_air" value="{{ old('kadar_air') }}"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200 @error('kadar_air') border-red-500 @enderror">
                                    @error('kadar_air')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-200 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition duration-150">
                                + Tambahkan ke Daftar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CART TABLE -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6 sm:p-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Barang Keluar Demak</h3>
                @if(count($items) > 0)
                    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode LP</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warna</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gram / Pcs</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($items as $index => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $item['kode_lp'] }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $item['warna'] }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                            <span class="font-bold text-gray-800">{{ number_format($item['gram'], 2, ',', '.') }}</span> g / 
                                            {{ number_format($item['pcs'], 0, ',', '.') }} pcs
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                            <form action="{{ route('barang-keluar.demak.remove.step2.item', $index) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Total <span class="font-bold">{{ count($items) }}</span> item
                        </div>
                        <form action="{{ route('barang-keluar.demak.store-step2') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 border border-transparent rounded-lg shadow-sm py-2.5 px-8 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                Simpan Data Batch
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-8 border border-gray-200 border-dashed rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-500">Belum ada barang di dalam daftar. Silakan tambahkan barang melalui form di atas.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
