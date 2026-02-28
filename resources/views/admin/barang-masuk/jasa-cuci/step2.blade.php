@extends('layouts.app')

@section('title', 'Tambah Data Jasa Cuci - Step 2')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Data Jasa Cuci - Step 2</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Satu Batch Jasa Cuci dapat berisi beberapa item barang/kode bahan.
                </p>
            </div>
            <a href="{{ route('barang-masuk.jasa-cuci.create.step1') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                Kembali
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between max-w-xl mx-auto">
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-semibold text-sm shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-green-600">Data Awal</span>
                </div>
                <div class="flex-1 h-0.5 bg-green-500 mx-2 sm:mx-4 -mt-6"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                        2</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Detail Barang</span>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Informasi Batch Header -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200 shadow-sm flex flex-wrap gap-6 items-center">
            <div>
                <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Terima</span>
                <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($step1Data['tanggal_terima'])->format('d M Y') }}</span>
            </div>
            <div class="h-8 w-px bg-gray-300 hidden sm:block"></div>
            <div>
                <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Lokasi Jasa Cuci</span>
                <span class="text-sm font-semibold text-gray-900">
                    {{ $lokasiJasaCuci->nama_lokasi ?? '-' }}
                </span>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Column: Form Tambah Item -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white shadow-sm border rounded-lg sticky top-6">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                        <h2 class="text-lg font-semibold text-gray-800">Tambah Item</h2>
                    </div>
                    <div class="p-5">
                        <form action="{{ route('barang-masuk.jasa-cuci.add.step2.item') }}" method="POST">
                            @csrf
                            <div class="space-y-4">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
                                    <select name="barang_id" required
                                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('barang_id') border-red-500 @enderror">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barang as $b)
                                            <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                                {{ $b->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('barang_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Bahan <span class="text-red-500">*</span></label>
                                    <select name="kode_bahan_id" required
                                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('kode_bahan_id') border-red-500 @enderror">
                                        <option value="">-- Pilih Kode Bahan --</option>
                                        @foreach($kodeBahan as $kb)
                                            <option value="{{ $kb->id }}" {{ old('kode_bahan_id') == $kb->id ? 'selected' : '' }}>
                                                {{ $kb->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_bahan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat Per Pcs (gr) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="berat_per_pcs" value="{{ old('berat_per_pcs') }}" required
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('berat_per_pcs') border-red-500 @enderror"
                                            placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Susut (%) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="persentase_susut" value="{{ old('persentase_susut') }}" required
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('persentase_susut') border-red-500 @enderror"
                                            placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="kadar_air" value="{{ old('kadar_air') }}" required
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('kadar_air') border-red-500 @enderror"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full mt-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out text-sm flex justify-center items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Daftar Item & Submit Batch -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-sm border rounded-lg flex flex-col h-full">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 rounded-t-lg flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Daftar Item dalam Batch</h2>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ count($items) }} Item</span>
                    </div>

                    <div class="p-0 overflow-x-auto flex-grow">
                        @if(count($items) > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesifikasi</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $index => $item)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-4 py-3 text-sm text-gray-500 align-top">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="text-sm font-semibold text-gray-900 mb-1">
                                                    {{ $item['nama_barang'] }} &bull; <span class="text-blue-600">{{ $item['nama_kode_bahan'] }}</span>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                                                    <div><span class="text-gray-400">Berat Pcs:</span> <span class="font-medium text-gray-800">{{ number_format($item['berat_per_pcs'], 2) }} gr</span></div>
                                                    <div><span class="text-gray-400">Susut:</span> <span class="font-medium text-gray-800">{{ number_format($item['persentase_susut'], 2) }}%</span></div>
                                                    <div><span class="text-gray-400">Kadar Air:</span> <span class="font-medium text-gray-800">{{ number_format($item['kadar_air'], 2) }}%</span></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium align-top">
                                                <form action="{{ route('barang-masuk.jasa-cuci.remove.step2.item', $index) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-pink-600 hover:text-pink-900 bg-pink-50 hover:bg-pink-100 p-1.5 rounded-md transition-colors" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">Belum ada item ditambahkan</h3>
                                <p class="mt-1 text-sm text-gray-500 max-w-sm">Gunakan form di sebelah kiri untuk menambahkan item ke dalam batch Jasa Cuci ini.</p>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 border-t border-gray-200 bg-gray-50 rounded-b-lg mt-auto">
                        <form action="{{ route('barang-masuk.jasa-cuci.store.step2') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors {{ count($items) == 0 ? 'opacity-50 cursor-not-allowed' : 'shadow-sm' }}" {{ count($items) == 0 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Batch Jasa Cuci
                            </button>
                            @if(count($items) == 0)
                                <p class="text-center text-xs text-gray-500 mt-2">Tambahkan minimal 1 item untuk dapat menyimpan batch.</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
