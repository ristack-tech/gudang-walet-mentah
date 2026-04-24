@extends('layouts.app')

@section('title', 'Detail Batch Jasa Cuci')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Detail Batch Jasa Cuci</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Terhubung Referensi Batch Keluar: <span
                            class="font-medium text-blue-600">{{ \Carbon\Carbon::parse($barangMasukBatch->barangKeluar->tanggal_kirim ?? $barangMasukBatch->tanggal_terima)->format('Ymd') . '-' . strtoupper(substr(md5($barangMasukBatch->barang_keluar_id ?? $barangMasukBatch->id), 0, 3)) }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('barang-masuk.jasa-cuci.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 rounded hover:bg-gray-200 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Search Section -->
            <div class="mb-6">
                <div class="relative max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="tableSearch" placeholder="Cari barang atau kode bahan..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                </div>
            </div>

            <!-- Informasi Batch -->
            <div class="bg-white shadow rounded-lg border mb-6 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Batch Transaksi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Tanggal Terima</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($barangMasukBatch->tanggal_terima)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Lokasi Jasa Cuci</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $barangMasukBatch->barangMasukJasaCuci->first()->lokasiJasaCuci->nama_lokasi ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Item -->
            <div class="bg-white shadow rounded-lg border overflow-hidden mb-6">
                <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Item Barang Masuk</h2>
                        <span class="text-sm text-gray-600 font-medium px-2.5 py-0.5 bg-blue-50 text-blue-700 rounded-full">
                            {{ $barangMasukBatch->barangMasukJasaCuci->count() }} item
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full text-sm" id="itemTable">
                            <thead class="text-left text-xs font-medium text-gray-500 bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 w-12 text-center">No</th>
                                    <th class="px-4 py-3">Barang & Kode Bahan</th>
                                    <th class="px-4 py-3 text-right">Berat Diterima</th>
                                    <th class="px-4 py-3 text-right">Susut (gr)</th>
                                    <th class="px-4 py-3 text-right">Kadar Air</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php
                                    $totalBeratPcs = 0;
                                    $totalSusut = 0;
                                    $totalKA = 0;
                                    $itemCount = $barangMasukBatch->barangMasukJasaCuci->count();
                                @endphp
                                @forelse($barangMasukBatch->barangMasukJasaCuci as $index => $item)
                                    @php
                                        $totalBeratPcs += $item->berat_per_pcs;
                                        $totalSusut += $item->persentase_susut;
                                        $totalKA += $item->kadar_air;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}
                                            </div>
                                            <div class="text-xs text-blue-600 font-medium mt-1">
                                                {{ $item->kodeBahan->nama ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-gray-700">
                                            {{ number_format($item->berat_per_pcs, 2, ',', '.') }} <span
                                                class="text-[10px] text-gray-400">gr</span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-red-600">
                                            {{ number_format($item->susut_per_item, 2, ',', '.') }} <span
                                                class="text-[10px] text-gray-400">gr</span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-gray-700">
                                            {{ number_format($item->kadar_air, 2, ',', '.') }} <span
                                                class="text-[10px] text-gray-400">%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada item dalam batch
                                            ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Kuantitas -->
            <div class="bg-white shadow rounded-lg border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Kuantitas (Rata-rata)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Avg Berat -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <div class="text-sm text-blue-600 font-medium">Rata-rata Berat/Pcs</div>
                        <div class="text-2xl font-bold text-blue-900">
                            {{ $itemCount > 0 ? number_format($totalBeratPcs / $itemCount, 2, ',', '.') : '0' }}<span
                                class="text-sm font-medium ml-1">gr</span>
                        </div>
                    </div>
                    <!-- Avg Susut -->
                    <div class="bg-gradient-to-r from-red-50 to-red-100 p-4 rounded-lg border border-red-200">
                        <div class="text-sm text-red-600 font-medium">Rata-rata Susut</div>
                        <div class="text-2xl font-bold text-red-900">
                            {{ $itemCount > 0 ? number_format($totalSusut / $itemCount, 2, ',', '.') : '0' }}<span
                                class="text-sm font-medium ml-1">%</span>
                        </div>
                    </div>
                    <!-- Avg Kadar Air -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                        <div class="text-sm text-green-600 font-medium">Rata-rata Kadar Air</div>
                        <div class="text-2xl font-bold text-green-900">
                            {{ $itemCount > 0 ? number_format($totalKA / $itemCount, 2, ',', '.') : '0' }}<span
                                class="text-sm font-medium ml-1">%</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('tableSearch').addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#itemTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (row.cells.length > 1) { // Skip empty row
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    }
                });
            });
        </script>
    @endpush

@endsection