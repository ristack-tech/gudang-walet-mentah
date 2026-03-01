@extends('layouts.app')

@section('title', 'Tambah Grading - Step 1')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Grading - Step 1</h1>
                <p class="mt-1 text-sm text-gray-600">Pilih stok gudang yang akan digrading.</p>
            </div>
            <a href="{{ route('stok-grading.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between max-w-xl mx-auto">
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                        1</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Pilih Stok</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 mx-2 sm:mx-4 -mt-6"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 font-semibold text-sm">
                        2</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-gray-400">Input Grading</span>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                Filter
            </button>
        </div>

        <form method="GET" action="{{ route('stok-grading.create.step2') }}" id="mainForm">
            <!-- Hidden input for stok_id -->
            <input type="hidden" name="stok_id" id="stok_id" value="">

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto min-h-[350px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Gram</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Pcs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Box</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Partai</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($stokGudang as $index => $item)
                            <tr class="hover:bg-gray-50 {{ request('stok_id') == $item->kode_partai ? 'bg-blue-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}.</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->item_description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->qty_gram }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->qty_pcs }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->qty_box }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->kode_partai }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <button type="button" onclick="selectStok('{{ $item->kode_partai }}', this)" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-md hover:bg-blue-100 hover:border-blue-300 transition duration-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="button" onclick="return checkSelection()"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                        Lanjut ke Step 2
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function selectStok(stokId, btn) {
        document.getElementById('stok_id').value = stokId;
        
        // Remove active class from all rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.classList.remove('bg-blue-50');
        });
        
        // Add active class to selected row
        btn.closest('tr').classList.add('bg-blue-50');
    }

    function checkSelection() {
        if(!document.getElementById('stok_id').value) {
            alert('Pilih stok terlebih dahulu');
            return false;
        }
        document.getElementById('mainForm').submit();
    }
</script>
@endsection
