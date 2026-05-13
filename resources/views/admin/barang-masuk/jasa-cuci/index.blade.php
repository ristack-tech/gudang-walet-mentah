@extends('layouts.app')

@section('title', 'Data Barang Masuk (Jasa Cuci)')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">Data Barang Masuk dari Jasa Cuci</h1>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <a href="{{ route('barang-masuk.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                        Kembali ke Menu Utama
                    </a>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

                    <a href="{{ route('barang-masuk.jasa-cuci.create.step1') }}"
                        class="flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 whitespace-nowrap">
                        + Tambah Penerimaan Baru
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referensi Keluar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terima</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Susut Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Susut (%)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($barangMasukBatch as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $barangMasukBatch->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                        {{ \Carbon\Carbon::parse($item->barangKeluar->tanggal_kirim ?? $item->tanggal_terima)->format('Ymd') . '-' . strtoupper(substr(md5($item->barang_keluar_id ?? $item->id), 0, 3)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold text-red-600">
                                        {{ number_format($item->total_susut_batch, 2, ',', '.') }} gr
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold text-orange-600">
                                        @php
                                            $itemCount = $item->barangMasukJasaCuci->count();
                                            $totalSusut = $item->barangMasukJasaCuci->sum('persentase_susut');
                                        @endphp
                                        {{ $itemCount > 0 ? number_format($totalSusut / $itemCount, 2, ',', '.') : '0' }} %
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('barang-masuk.jasa-cuci.show', $item->id) }}" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 font-bold transition duration-200">Detail Penerimaan</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Tidak ada data retur dari Jasa Cuci.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($barangMasukBatch->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $barangMasukBatch->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
