@extends('layouts.app')

@section('title', 'Detail Grading - Barang')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            
            <!-- Header Section -->
            <div class="p-6 sm:p-10 border-b border-gray-200 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Detail Grading - Barang: {{ $namaBarang }}</h1>
                        <p class="text-sm text-gray-600">ID Grading: SG-{{ str_pad($gradingBatch['id'], 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                <div class="flex gap-2">
                    <a href="{{ route('stok-grading.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 rounded hover:bg-gray-200 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- ✅ Informasi Stok Awal (Dari Step 1) -->
            <div class="bg-white shadow rounded-lg border mb-6 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Stok Gudang Awal</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Grading</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($gradingBatch['tanggal'])->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Partai</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $gradingBatch['partai'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Pekerja</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $gradingBatch['pekerja'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Barang Stok Sumber</dt>
                        <dd class="mt-1 text-sm text-blue-600 font-medium">
                            {{ $namaBarang }}
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-2 {{ ($gradingBatch['sumber_stok'] ?? '') == 'demak' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ($gradingBatch['sumber_stok'] ?? '') == 'demak' ? 'Demak' : 'Jasa Cuci' }}
                            </span>
                        </dd>
                    </div>
                    <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-100">
                        <dt class="text-sm font-medium text-gray-700">Jumlah Awal Sebelum Digrading</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            Pcs Awal Digrading: <span class="font-bold">{{ number_format($gradingBatch['pcs_awal'] ?? 0, 0, ',', '.') }}</span> Pcs
                        </dd>
                    </div>
                </div>
            </div>

            <!-- ✅ Detail Item Grading Lempeng Bersih -->
            <div class="bg-white shadow rounded-lg border overflow-hidden mb-6">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-blue-900">Hasil Grading - Lempeng Bersih</h2>
                        <span class="text-sm text-blue-800">{{ count($gradingBatch['lempeng_bersih']) }} grade terisi</span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="text-left text-xs font-medium text-gray-500 bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 rounded-tl-md">No</th>
                                    <th class="px-4 py-3">Pilih Grade</th>
                                    <th class="px-4 py-3 text-right">Berat (Gram)</th>
                                    <th class="px-4 py-3 text-right">Box</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($gradingBatch['lempeng_bersih'] as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-900 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-gray-700 align-top text-sm font-medium">
                                            {{ $item['grade'] }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 text-right align-top text-sm">
                                            {{ number_format($item['berat'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 text-right align-top text-sm">
                                            {{ number_format($item['box'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 text-sm border-t border-gray-200">Tidak ada output lempeng bersih.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ✅ Detail Item Grading Lempeng Kotor -->
            <div class="bg-white shadow rounded-lg border overflow-hidden mb-6">
                <div class="px-6 py-4 border-b bg-yellow-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-yellow-900">Hasil Grading - Lempeng Kotor</h2>
                        <span class="text-sm text-yellow-800">{{ count($gradingBatch['lempeng_kotor']) }} grade terisi</span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="text-left text-xs font-medium text-gray-500 bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 rounded-tl-md">No</th>
                                    <th class="px-4 py-3">Pilih Grade</th>
                                    <th class="px-4 py-3 text-right">Berat (Gram)</th>
                                    <th class="px-4 py-3 text-right">Box</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($gradingBatch['lempeng_kotor'] as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-900 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-gray-700 align-top text-sm font-medium">
                                            {{ $item['grade'] }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 text-right align-top text-sm">
                                            {{ number_format($item['berat'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 text-right align-top text-sm">
                                            {{ number_format($item['box'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 text-sm border-t border-gray-200">Tidak ada output lempeng kotor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ✅ Detail Item Grading Mangkok -->
            <div class="bg-white shadow rounded-lg border overflow-hidden mb-6">
                <div class="px-6 py-4 border-b bg-green-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-green-900">Hasil Grading - Mangkok</h2>
                        <span class="text-sm text-green-800">{{ count($gradingBatch['mangkok']) }} grade terisi</span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="text-left text-xs font-medium text-gray-500 bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 rounded-tl-md">No</th>
                                    <th class="px-4 py-3">Pilih Grade</th>
                                    <th class="px-4 py-3 text-right">Berat (Gram)</th>
                                    <th class="px-4 py-3 text-right">Box</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($gradingBatch['mangkok'] as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-900 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-gray-700 align-top text-sm font-medium">
                                            {{ $item['grade'] }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 text-right align-top text-sm">
                                            {{ number_format($item['berat'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 text-right align-top text-sm">
                                            {{ number_format($item['box'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 text-sm border-t border-gray-200">Tidak ada output mangkok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ✅ Ringkasan Total -->
            @php
                $totalGramLempengBersih = collect($gradingBatch['lempeng_bersih'])->sum('berat');
                $totalGramLempengKotor = collect($gradingBatch['lempeng_kotor'])->sum('berat');
                $totalGramMangkok = collect($gradingBatch['mangkok'])->sum('berat');
                
                $totalBoxLempengBersih = collect($gradingBatch['lempeng_bersih'])->sum('box');
                $totalBoxLempengKotor = collect($gradingBatch['lempeng_kotor'])->sum('box');
                $totalBoxMangkok = collect($gradingBatch['mangkok'])->sum('box');

                $totalGramKeseluruhan = $totalGramLempengBersih + $totalGramLempengKotor + $totalGramMangkok;
                $totalBoxKeseluruhan = $totalBoxLempengBersih + $totalBoxLempengKotor + $totalBoxMangkok;
            @endphp
            <div class="bg-white shadow rounded-lg border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Output Grading</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                    <!-- Lempeng Bersih Gram -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H9m4 8H9m-4-4h.01M6 16h.01" />
                            </svg>
                            <div>
                                <div class="text-sm text-blue-600 font-medium">Total Berat Lempeng Bersih</div>
                                <div class="text-2xl font-bold text-blue-900">{{ number_format($totalGramLempengBersih, 2, ',', '.') }}<span class="text-sm font-medium ml-1">gr</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Lempeng Kotor Gram -->
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H9m4 8H9m-4-4h.01M6 16h.01" />
                            </svg>
                            <div>
                                <div class="text-sm text-yellow-600 font-medium">Total Berat Lempeng Kotor</div>
                                <div class="text-2xl font-bold text-yellow-900">{{ number_format($totalGramLempengKotor, 2, ',', '.') }}<span class="text-sm font-medium ml-1">gr</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Mangkok Gram -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l-3-9l3-9z" />
                            </svg>
                            <div>
                                <div class="text-sm text-green-600 font-medium">Total Berat Mangkok</div>
                                <div class="text-2xl font-bold text-green-900">
                                    {{ number_format($totalGramMangkok, 2, ',', '.') }}<span class="text-sm font-medium ml-1">gr</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Total Keseluruhan Gram -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <div>
                                <div class="text-sm text-purple-600 font-medium">Total Gram Keseluruhan</div>
                                <div class="text-2xl font-bold text-purple-900">
                                    {{ number_format($totalGramKeseluruhan, 2, ',', '.') }}<span class="text-sm ml-1">gr</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Box -->
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <div>
                                <div class="text-sm text-orange-600 font-medium">Total Box Keseluruhan</div>
                                <div class="text-2xl font-bold text-orange-900">
                                    {{ number_format($totalBoxKeseluruhan, 0, ',', '.') }}<span class="text-sm ml-1">bx</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
