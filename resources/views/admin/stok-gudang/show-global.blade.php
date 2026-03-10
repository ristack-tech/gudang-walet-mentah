@extends('layouts.app')

@section('title', 'Detail Stok Gudang Global')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </span>
                    Detail Stok Gudang: <span class="text-blue-600">{{ $namaBarang->nama_barang }}</span>
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Histori asal-usul barang (Gabungan Demak & Jasa Cuci)
                </p>
            </div>
            <a href="{{ route('stok-gudang.global') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Box</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary->total_box ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Pcs</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary->total_pcs ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Berat</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($summary->total_gram ?? 0, 2, ',', '.') }} <span class="text-sm text-green-700 font-semibold">Gr</span></p>
                </div>
            </div>
        </div>

        {{-- Detail Table Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative z-10">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    Histori Stok
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Terima</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Partai / Kode Bahan</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Box</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Pcs</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Berat (Gr)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($details as $index => $detail)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($detail->tanggal_terima)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                     <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $detail->sumber == 'Demak' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $detail->sumber }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono tracking-tight bg-gray-50">{{ $detail->identifier }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($detail->total_box, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($detail->total_pcs, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                     <span class="font-medium text-gray-900">
                                        {{ number_format($detail->total_gram, 2, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    Tidak ada detail stok untuk barang ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection
