@extends('layouts.app')

@section('title', 'Detail Stok Gudang Jasa Cuci - ' . $namaBarang->nama_barang)

@section('content')
    <div class="px-6 py-6 pb-20">
        <div class="max-w-7xl mx-auto">
            
            {{-- Breadcrumb & Header --}}
            <div class="mb-6">
                <a href="{{ route('stok-gudang.jasa-cuci') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Stok Jasa Cuci
                </a>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2 uppercase">Histori Masuk Jasa Cuci: {{ $namaBarang->nama_barang }}</h1>
                <p class="text-sm text-gray-500">Berikut adalah daftar riwayat seluruh barang masuk (Inbound) untuk {{ $namaBarang->nama_barang }} di lokasi Jasa Cuci.</p>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold">Tanggal Masuk</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Kode Bahan</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right">Berat Setoran (Gram)</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right">Berat Timbangan Gudang (Gram)</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right">Persentase Selisih (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($details as $detail)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-gray-900 font-medium">
                                        {{ \Carbon\Carbon::parse($detail->tanggal_terima)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md font-medium text-xs">{{ $detail->kode_bahan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                                        {{ number_format($detail->berat_per_pcs, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-blue-600 font-medium">
                                        {{ number_format($detail->berat_timbangan_gudang, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium">
                                        @if($detail->persentase_selisih < 0)
                                            <span class="text-green-600 font-semibold">+{{ number_format(abs($detail->persentase_selisih), 2) }}%</span>
                                        @else
                                            <span class="text-orange-600 font-semibold">{{ number_format($detail->persentase_selisih, 2) }}%</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <p class="text-gray-500 font-medium">Tidak ada histori inbound ditemukan</p>
                                        </div>
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
