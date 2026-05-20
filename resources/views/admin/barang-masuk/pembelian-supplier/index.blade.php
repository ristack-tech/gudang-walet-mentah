@extends('layouts.app')

@section('title', 'Data Barang Masuk (Pembelian Supplier)')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">Data Barang Masuk (Pembelian Supplier)</h1>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <a href="{{ route('barang-masuk.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Daftar Barang Masuk
                    </a>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <!-- Search Form -->
                        <form action="{{ route('barang-masuk.pembelian-supplier.index') }}" method="GET"
                            class="flex w-full sm:w-auto">
                            <div class="relative flex-grow sm:flex-grow-0">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama supplier..."
                                    class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <button type="submit"
                                class="ml-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-md text-sm font-medium transition duration-200">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('barang-masuk.pembelian-supplier.create.step1') }}"
                            class="flex justify-center items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Batch No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Terima</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Supplier</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($barangMasukBatch as $index => $batch)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $barangMasukBatch->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                            {{ \Carbon\Carbon::parse($batch->tanggal_terima)->format('Ymd') . '-' . strtoupper(substr(md5($batch->id), 0, 3)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($batch->tanggal_terima)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $batch->barangMasukSupplier->first()?->supplier->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('barang-masuk.pembelian-supplier.show', $batch->id) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition duration-200">
                                                    Detail Batch
                                                </a>

                                                <button type="button"
                                                    onclick="openEditModal({{ $batch->id }}, '{{ $batch->tanggal_terima }}')"
                                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 font-bold transition duration-200">
                                                    Edit
                                                </button>

                                                @php
                                                    // Check if any item in this batch has been graded
                                                    $hasGraded = false;
                                                    foreach($batch->barangMasukSupplier as $bmItem) {
                                                        $graded = \App\Models\StokGrading::where('sumber_stok', 'pembelian_supplier')
                                                            ->where('barang_id', $bmItem->barang_id)
                                                            ->where('partai', optional($bmItem->supplier)->nama)
                                                            ->exists();
                                                        if ($graded) {
                                                            $hasGraded = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp

                                                @if($hasGraded)
                                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-400 rounded cursor-not-allowed" title="Tidak dapat dihapus karena item dari supplier ini telah di-grading">
                                                        Hapus Batch
                                                    </span>
                                                @else
                                                    <button
                                                        onclick="confirmDelete({{ $batch->id }}, '{{ \Carbon\Carbon::parse($batch->tanggal_terima)->format('Ymd') . '-' . strtoupper(substr(md5($batch->id), 0, 3)) }}')"
                                                        class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition duration-200">
                                                        Hapus Batch
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                            Tidak ada data batch.
                                        </td>
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

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Data</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Apakah Anda yakin ingin menghapus data supplier <span id="dataName"
                            class="font-semibold text-gray-900"></span>?
                    </p>
                    <form id="deleteForm" method="POST" class="mt-6">
                        @csrf
                        @method('DELETE')
                        <div class="flex gap-3 justify-center">
                            <button type="button" onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition duration-200">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm font-medium transition duration-200">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

         <!-- Edit Confirmation Modal -->
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
                 document.getElementById('editForm').action = '{{ route("barang-masuk.pembelian-supplier.update", ":id") }}'.replace(':id', id);
                 document.getElementById('editModal').classList.remove('hidden');
             }

             function closeEditModal() {
                 document.getElementById('editModal').classList.add('hidden');
             }

             document.getElementById('editModal')?.addEventListener('click', function(e) {
                 if (e.target === this) {
                     closeEditModal();
                 }
             });

             function confirmDelete(id, name) {
                 document.getElementById('dataName').textContent = name;
                 var url = '{{ route('barang-masuk.pembelian-supplier.destroy', ':id') }}';
                 url = url.replace(':id', id);
                 document.getElementById('deleteForm').action = url;
                 document.getElementById('deleteModal').classList.remove('hidden');
             }

             function closeDeleteModal() {
                 document.getElementById('deleteModal').classList.add('hidden');
             }

             document.getElementById('deleteModal')?.addEventListener('click', function (event) {
                 if (event.target === this) {
                     closeDeleteModal();
                 }
             });

             setTimeout(() => {
                 const alerts = document.querySelectorAll('[id^="alert-"]');
                 alerts.forEach(alert => {
                     if (alert) {
                         alert.style.transition = 'opacity 0.5s ease-out';
                         alert.style.opacity = '0';
                         setTimeout(() => alert.remove(), 500);
                     }
                 });
             }, 5000);
         </script>
@endsection