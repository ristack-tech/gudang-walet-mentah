@extends('layouts.app')

@section('title', 'Tambah Data Jasa Cuci - Step 2')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Tambah Data Jasa Cuci - Step 2</h1>
                    <p class="mt-1 text-sm text-gray-600">Satu Batch Jasa Cuci dapat berisi beberapa item barang/kode bahan.</p>
                </div>
                <a href="{{ route('barang-masuk.jasa-cuci.create.step1') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
                    Kembali
                </a>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between max-w-xl mx-auto">
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-semibold text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-green-600">Data Awal</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-blue-200 mx-2 sm:mx-4 -mt-6"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                            2</div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Detail Barang</span>
                    </div>
                </div>
            </div>


            <!-- Informasi Transaksi (Step 1 Summary) -->
            <div class="bg-gray-50 shadow-sm border rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Transaksi</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Terima</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($step1Data['tanggal_terima'])->format('d/m/Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lokasi Jasa Cuci</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium text-blue-600">
                            {{ $lokasiJasaCuci->nama_lokasi ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Add Item Form and Cart -->
            <div class="bg-white shadow-sm border rounded-lg p-6">

                <!-- ADD ITEM SECTION -->
                <div class="mb-8 border border-gray-200 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Detail Barang</h3>

                    <form action="{{ route('barang-masuk.jasa-cuci.add.step2.item') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Barang -->
                            <div>
                                <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                                <select id="barang_id" name="barang_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white @error('barang_id') border-red-500 @enderror" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $b)
                                        <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('barang_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Bahan -->
                            <div>
                                <label for="kode_bahan_id" class="block text-sm font-medium text-gray-700 mb-1">Kode Bahan <span class="text-red-500">*</span></label>
                                <select id="kode_bahan_id" name="kode_bahan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white @error('kode_bahan_id') border-red-500 @enderror" required>
                                    <option value="">-- Pilih Kode Bahan --</option>
                                    @foreach($kodeBahan as $kb)
                                        <option value="{{ $kb->id }}" {{ old('kode_bahan_id') == $kb->id ? 'selected' : '' }}>
                                            {{ $kb->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_bahan_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Berat Per Pcs -->
                            <div>
                                <label for="berat_per_pcs" class="block text-sm font-medium text-gray-700 mb-1">Berat Per Pcs (gr) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" id="berat_per_pcs" name="berat_per_pcs" value="{{ old('berat_per_pcs') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('berat_per_pcs') border-red-500 @enderror" 
                                    placeholder="0.00" required>
                                @error('berat_per_pcs')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Susut -->
                            <div>
                                <label for="persentase_susut" class="block text-sm font-medium text-gray-700 mb-1">Susut (%) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" id="persentase_susut" name="persentase_susut" value="{{ old('persentase_susut') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('persentase_susut') border-red-500 @enderror" 
                                    placeholder="0.00" required>
                                @error('persentase_susut')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kadar Air -->
                            <div>
                                <label for="kadar_air" class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" id="kadar_air" name="kadar_air" value="{{ old('kadar_air') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('kadar_air') border-red-500 @enderror" 
                                    placeholder="0.00" required>
                                @error('kadar_air')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 shadow-sm">
                                + Tambahkan ke Daftar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CART TABLE -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Daftar Item (Dalam Batch)</h3>

                    @if(count($items) > 0)
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Bahan</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Spesifikasi</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 align-top">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 align-top">{{ $item['nama_barang'] ?? '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 align-top">
                                                <span class="text-blue-600 font-medium">{{ $item['nama_kode_bahan'] ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right align-top">
                                                <div class="grid grid-cols-1 gap-1">
                                                    <span>Berat: <span class="font-bold text-gray-800">{{ number_format($item['berat_per_pcs'], 2, ',', '.') }}</span> gr</span>
                                                    <span>Susut: <span class="font-bold text-gray-800">{{ number_format($item['persentase_susut'], 2, ',', '.') }}</span> %</span>
                                                    <span>KA: <span class="font-bold text-gray-800">{{ number_format($item['kadar_air'], 2, ',', '.') }}</span> %</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm align-top">
                                                <form action="{{ route('barang-masuk.jasa-cuci.remove.step2.item', $index) }}" method="POST" class="inline">
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
                    @else
                        <div class="text-center py-8 border border-gray-200 border-dashed rounded-lg bg-gray-50">
                            <p class="text-sm text-gray-500">Belum ada item di dalam daftar. Silakan tambahkan item melalui form di atas.</p>
                        </div>
                    @endif
                </div>

                <!-- Submit Button Form -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ count($items) }}</span> item barang dalam antrian batch
                        </div>

                        <form action="{{ route('barang-masuk.jasa-cuci.store.step2') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white rounded-md hover:bg-blue-700 font-medium shadow-sm transition-colors duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed" 
                                {{ empty($items) ? 'disabled' : '' }}>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Batch Jasa Cuci
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                const alerts = document.querySelectorAll('[id^="alert-"]');
                alerts.forEach(alert => {
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 3000);
        </script>
    @endpush
@endsection
