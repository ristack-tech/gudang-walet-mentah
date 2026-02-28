@extends('layouts.app')

@section('title', 'Barang Keluar Jasa Cuci')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">Barang Keluar Jasa Cuci</h1>
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <a href="{{ route('barang-keluar.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Daftar Barang Keluar
                    </a>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <!-- Search Form -->
                        <form action="{{ route('barang-keluar.jasa-cuci.index') }}" method="GET" class="flex w-full sm:w-auto">
                            <div class="relative flex-grow sm:flex-grow-0">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Cari Kode Bahan..." 
                                    class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="ml-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-md text-sm font-medium transition duration-200">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('barang-keluar.jasa-cuci.step1') }}"
                            class="flex justify-center items-center bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 whitespace-nowrap shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Barang Keluar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Batch
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Kirim
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Jasa Cuci
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($data as $index => $item)
                                @php
                                    $batchStr = \Carbon\Carbon::parse($item->tanggal_kirim)->format('Ymd') . '-' . strtoupper(substr(md5($item->id), 0, 3));
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $data->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-teal-600">
                                        {{ $batchStr }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->tanggal_kirim)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                        {{ $item->lokasi->nama_lokasi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('barang-keluar.jasa-cuci.show', $item->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-md border border-green-200 hover:bg-green-100 transition duration-200 font-medium">
                                                Detail Batch
                                            </a>
                                            <a href="{{ route('barang-keluar.jasa-cuci.edit', $item->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md border border-blue-200 hover:bg-blue-100 transition duration-200 font-medium">
                                                Edit Batch
                                            </a>
                                            <button onclick="confirmDelete({{ $item->id }}, '{{ $batchStr }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md border border-red-200 hover:bg-red-100 transition duration-200 font-medium">
                                                Hapus Batch
                                            </button>
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
                @if(isset($data) && $data->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $data->withQueryString()->links() }}
                </div>
                @endif
            </div>
            
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Data Batch</h3>
                <p class="text-sm text-gray-500 mt-2 px-4">
                    Apakah Anda yakin ingin menghapus seluruh data batch <span id="itemCode" class="font-bold text-gray-900"></span> dan isinya? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3 justify-center px-4">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition duration-200 shadow-sm">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, code) {
            document.getElementById('itemCode').textContent = code;
            var url = '{{ route('barang-keluar.jasa-cuci.destroy', ':id') }}';
            url = url.replace(':id', id);
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
