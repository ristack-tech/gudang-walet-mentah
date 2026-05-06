@extends('layouts.app')

@section('title', 'Detail Barang Keluar Jasa Cuci')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detail Batch Barang Keluar Jasa Cuci
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Batch: <span class="font-semibold text-teal-600">{{ \Carbon\Carbon::parse($data->tanggal_kirim)->format('Ymd') . '-' . strtoupper(substr(md5($data->id), 0, 3)) }}</span>
                        </p>
                    </div>
                    <a href="{{ route('barang-keluar.jasa-cuci.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">
                        &larr; Kembali
                    </a>
                </div>
                
                <div class="border-t border-gray-200 px-6 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Pemberian Bahan</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-medium">
                                {{ $data->tanggal_kirim ? \Carbon\Carbon::parse($data->tanggal_kirim)->format('d M Y') : '-' }}
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                            <dt class="text-sm font-medium text-gray-500">Nama Jasa Cuci / Lokasi Tujuan</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-medium">
                                {{ $data->lokasi->nama_lokasi ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Barang (Jasa Cuci)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Bahan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pcs</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (Gr)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kadar Air</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan Cetak</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($data->barangKeluarJasaCuci as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">
                                            {{ $item->kodeBahan->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $item->pcs ? number_format($item->pcs, 2, ',', '.') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($item->berat, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->kadar_air ? $item->kadar_air . ' %' : '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->keterangan_cetak ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada item dalam batch ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($data->barangKeluarJasaCuci->count() > 0)
                            <tfoot class="bg-gray-50 font-bold text-gray-900 border-t border-gray-200">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-right text-sm">Total Berat:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($data->barangKeluarJasaCuci->sum('berat'), 2, ',', '.') }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            

        </div>
    </div>
@endsection
