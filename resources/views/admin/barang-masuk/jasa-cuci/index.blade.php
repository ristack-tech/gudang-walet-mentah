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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Selisih (%)</th>
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
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        @if($item->total_susut_batch < 0)
                                            <span class="text-green-600">+{{ number_format(abs($item->total_susut_batch), 2, ',', '.') }} gr</span>
                                        @else
                                            <span class="text-red-600">{{ number_format($item->total_susut_batch, 2, ',', '.') }} gr</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        @php
                                            $itemCount = $item->barangMasukJasaCuci->count();
                                            $totalSelisih = $item->barangMasukJasaCuci->sum('persentase_selisih');
                                            $avgSelisih = $itemCount > 0 ? ($totalSelisih / $itemCount) : 0;
                                        @endphp
                                        @if($avgSelisih < 0)
                                            <span class="text-green-600">+{{ number_format(abs($avgSelisih), 2, ',', '.') }} %</span>
                                        @else
                                            <span class="text-red-600">{{ number_format($avgSelisih, 2, ',', '.') }} %</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button type="button"
                                                onclick="openEditModal({{ $item->id }}, '{{ $item->tanggal_terima }}')"
                                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 font-bold transition duration-200">
                                                Edit
                                            </button>
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

    <!-- Modal Edit Tanggal Terima -->
    <div id="editModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Edit Tanggal Terima</h3>
                            <div class="mt-4">
                                <label for="edit_tanggal_terima" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terima</label>
                                <input type="date" name="tanggal_terima" id="edit_tanggal_terima" required
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto sm:justify-end transition-colors">Simpan</button>
                            <button type="button" onclick="closeEditModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:justify-end transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, tanggalTerima) {
            document.getElementById('edit_tanggal_terima').value = tanggalTerima;
            document.getElementById('editForm').action = '{{ route("barang-masuk.jasa-cuci.update", ":id") }}'.replace(':id', id);
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
@endsection
