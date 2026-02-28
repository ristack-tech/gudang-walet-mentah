@extends('layouts.app')

@section('title', 'Detail Barang Keluar Demak')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detail Batch Barang Keluar Demak
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Batch: <span class="font-semibold text-blue-600">BKD-{{ \Carbon\Carbon::parse($data->tanggal_kirim)->format('Ymd') . '-' . strtoupper(substr(md5($data->id), 0, 3)) }}</span>
                        </p>
                    </div>
                    <a href="{{ route('barang-keluar.demak.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        &larr; Kembali
                    </a>
                </div>
                
                <div class="border-t border-gray-200 px-6 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Kirim</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-medium">
                                {{ $data->tanggal_kirim ? \Carbon\Carbon::parse($data->tanggal_kirim)->format('d M Y') : '-' }}
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                            <dt class="text-sm font-medium text-gray-500">Partai</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-medium">
                                {{ optional($data->barangKeluarDemak->first())->partai->nama_partai ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Barang</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode LP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warna</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gram</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pcs</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">T. Ulang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($data->barangKeluarDemak as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->kode_lp }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->warna->kode_warna ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($item->gram, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ number_format($item->pcs, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">{{ $item->timbangan_ulang ? number_format($item->timbangan_ulang, 2, ',', '.') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada item dalam batch ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($data->barangKeluarDemak->count() > 0)
                            <tfoot class="bg-gray-50 font-bold text-gray-900 border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm">Total:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($data->barangKeluarDemak->sum('gram'), 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($data->barangKeluarDemak->sum('pcs'), 0, ',', '.') }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('barang-keluar.demak.edit', $data->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition duration-150">
                    Edit Batch Data
                </a>
            </div>
        </div>
    </div>
@endsection
