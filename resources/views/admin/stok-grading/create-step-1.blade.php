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

        <form method="GET" action="{{ route('stok-grading.create.step1') }}" class="mb-4 flex space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari item..." 
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
            <button type="submit" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                Filter
            </button>
        </form>

        <form method="GET" action="{{ route('stok-grading.create.step2') }}" id="mainForm">
            <!-- Hidden inputs -->
            <input type="hidden" name="stok_id" id="stok_id" value="">
            <input type="hidden" name="box_awal" id="box_awal_input" value="">
            <input type="hidden" name="sumber_stok" id="sumber_stok_input" value="">

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6 bg-white border-b border-gray-200 flex space-x-4 justify-center">
                    <button type="button" onclick="showTable('demak')" id="btn_tab_demak" class="px-6 py-3 bg-white border-2 border-blue-500 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Lihat Stok Gudang Demak
                    </button>
                    <button type="button" onclick="showTable('jasacuci')" id="btn_tab_jasacuci" class="px-6 py-3 bg-white border-2 border-purple-500 text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Lihat Stok Jasa Cuci
                    </button>
                </div>

                <!-- Demak Table Container -->
                <div id="table_demak" class="hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-blue-900 flex items-center">
                            Sumber: Gudang Demak
                        </h3>
                    </div>
                    <div class="overflow-x-auto min-h-[200px]">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gram</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pcs</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Box</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($demakSummary as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}.</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ number_format($item->total_gram, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->total_pcs }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->total_box }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center space-x-2">
                                            <input type="number" id="input_box_demak_{{ $item->id }}" min="1" max="{{ $item->total_box }}" placeholder="Jml Box" 
                                                class="w-24 px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" onclick="selectStok('{{ $item->id }}', 'demak', this, {{ $item->total_box }})" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-md hover:bg-blue-100 hover:border-blue-300 transition duration-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Pilih
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Tidak ada ketersediaan stok Demak yang bisa digrading.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Jasa Cuci Table Container -->
                <div id="table_jasacuci" class="hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-purple-50 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-purple-900 flex items-center">
                            Sumber: Jasa Cuci
                        </h3>
                    </div>
                    <div class="overflow-x-auto min-h-[200px]">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gram</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pcs</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jasaCuciSummary as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}.</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ number_format($item->total_gram, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->total_pcs }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center space-x-2">
                                            <!-- Untuk Jasa Cuci, input merupakan Gram -->
                                            <input type="number" step="0.01" id="input_box_jasacuci_{{ $item->id }}" min="0.01" max="{{ $item->total_gram }}" placeholder="Jml Gram" 
                                                class="w-24 px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" onclick="selectStok('{{ $item->id }}', 'jasa_cuci', this, {{ $item->total_box }})" 
                                                class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-600 border border-purple-200 rounded-md hover:bg-purple-100 hover:border-purple-300 transition duration-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                Pilih
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Tidak ada ketersediaan stok Jasa Cuci yang bisa digrading.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
    function showTable(type) {
        const tableDemak = document.getElementById('table_demak');
        const tableJasaCuci = document.getElementById('table_jasacuci');
        const btnDemak = document.getElementById('btn_tab_demak');
        const btnJasaCuci = document.getElementById('btn_tab_jasacuci');

        if (type === 'demak') {
            tableDemak.classList.remove('hidden');
            tableJasaCuci.classList.add('hidden');
            
            btnDemak.classList.add('bg-blue-50', 'border-blue-500');
            btnDemak.classList.remove('bg-white', 'border-gray-300', 'text-gray-500');
            
            btnJasaCuci.classList.add('bg-white', 'border-gray-300', 'text-gray-500');
            btnJasaCuci.classList.remove('bg-purple-50', 'border-purple-500', 'text-purple-600');
        } else {
            tableJasaCuci.classList.remove('hidden');
            tableDemak.classList.add('hidden');
            
            btnJasaCuci.classList.add('bg-purple-50', 'border-purple-500', 'text-purple-600');
            btnJasaCuci.classList.remove('bg-white', 'border-gray-300', 'text-gray-500');
            
            btnDemak.classList.add('bg-white', 'border-gray-300', 'text-gray-500');
            btnDemak.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-600');
        }
    }

    // Initialize with gray unselected state for both
    document.addEventListener('DOMContentLoaded', function() {
        const btnDemak = document.getElementById('btn_tab_demak');
        const btnJasaCuci = document.getElementById('btn_tab_jasacuci');
        
        btnDemak.classList.remove('border-blue-500');
        btnDemak.classList.add('border-gray-300', 'text-gray-500');
        
        btnJasaCuci.classList.remove('border-purple-500', 'text-purple-600');
        btnJasaCuci.classList.add('border-gray-300', 'text-gray-500');
    });

    function selectStok(stokId, sumberStok, btn, maxBox) {
        // Build the correct ID based on source logic
        const inputIdText = sumberStok === 'demak' ? 'demak' : 'jasacuci';
        const boxInput = document.getElementById(`input_box_${inputIdText}_${stokId}`);
        const boxValue = parseFloat(boxInput.value);

        if (sumberStok === 'demak') {
            if (isNaN(boxValue) || boxValue < 1) {
                alert('Masukkan jumlah Box yang valid minimal 1.');
                boxInput.focus();
                return;
            }

            if (boxValue > maxBox) {
                alert(`Jumlah Box tidak boleh lebih dari total stok yang tersedia (${maxBox} Box).`);
                boxInput.focus();
                return;
            }
        } else {
            // Untuk Jasa Cuci, maxBox yang dilempar aslinya adalah total_box (0). 
            // Kita harus mengambil batas max dari atribut input-nya langsung (yaitu total_gram)
            const maxGram = parseFloat(boxInput.getAttribute('max'));
            if (isNaN(boxValue) || boxValue <= 0) {
                alert('Masukkan jumlah Gram yang valid.');
                boxInput.focus();
                return;
            }
            if (boxValue > maxGram) {
                alert(`Jumlah Gram tidak boleh lebih dari total stok yang tersedia (${maxGram} Gr).`);
                boxInput.focus();
                return;
            }
        }

        document.getElementById('stok_id').value = stokId;
        document.getElementById('box_awal_input').value = boxValue;
        document.getElementById('sumber_stok_input').value = sumberStok;
        
        // Remove active class from all rows in all tbodys
        document.querySelectorAll('tbody tr').forEach(row => {
            row.classList.remove('bg-blue-50', 'bg-purple-50');
        });
        
        // Add active class to selected row based on source
        if(sumberStok === 'demak') {
            btn.closest('tr').classList.add('bg-blue-50');
        } else {
            btn.closest('tr').classList.add('bg-purple-50');
        }
    }

    function checkSelection() {
        if(!document.getElementById('stok_id').value || !document.getElementById('box_awal_input').value || !document.getElementById('sumber_stok_input').value) {
            alert('Pilih sumber stok (Demak/Jasa Cuci) dan masukkan jumlah Box terlebih dahulu');
            return false;
        }
        document.getElementById('mainForm').submit();
    }
</script>
@endsection
