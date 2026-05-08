@extends('layouts.app')

@section('title', 'Tambah Grading - Step 1')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Grading - Step 1</h1>
                <p class="mt-1 text-sm text-gray-600">Pilih stok gudang yang akan digrading. Centang item yang akan diproses.</p>
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

        <form method="GET" action="{{ route('stok-grading.create.step2') }}" id="mainForm">
            <!-- Hidden inputs for step 2 -->
            <input type="hidden" name="items_data" id="items_data" value="">

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6 bg-white border-b border-gray-200 flex space-x-4 justify-center">
                    <button type="button" onclick="showTable('demak')" id="btn_tab_demak" class="px-6 py-3 bg-white border-2 border-blue-500 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Lihat Stok Gudang Demak
                    </button>
                    <button type="button" onclick="showTable('jasacuci')" id="btn_tab_jasacuci" class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-500 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Lihat Stok Jasa Cuci
                    </button>
                </div>

                <!-- Demak Table Container -->
                <div id="table_demak">
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-blue-900 flex items-center">
                            Sumber: Gudang Demak
                        </h3>
                        <label class="flex items-center text-sm text-blue-700 font-medium cursor-pointer hover:text-blue-900">
                            <input type="checkbox" id="selectAllDemak" onchange="toggleAllDemak(this)" class="w-4 h-4 mr-2 rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                            Pilih Semua
                        </label>
                    </div>
                    <div class="overflow-x-auto min-h-[200px]">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                        <span class="sr-only">Checkbox</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gram</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pcs</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Box</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Gram</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="tbody_demak">
                                @forelse($demakSummary as $index => $item)
                                <tr class="hover:bg-blue-50/30" id="tr_demak_{{ $item->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                            class="row-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                            data-sumber="demak"
                                            data-nama="{{ $item->nama_barang }}"
                                            data-gram="{{ $item->total_gram }}"
                                            data-pcs="{{ $item->total_pcs }}"
                                            data-box="{{ $item->total_box }}"
                                            onchange="updateSelection()">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}.</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ number_format($item->total_gram, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->total_pcs }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->total_box }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" step="0.01" min="0.01" max="{{ $item->total_gram }}"
                                            placeholder="Jml Gram"
                                            class="gram-input w-24 px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            data-max="{{ $item->total_gram }}"
                                            onchange="validateGramInput(this)">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
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
                        <label class="flex items-center text-sm text-purple-700 font-medium cursor-pointer hover:text-purple-900">
                            <input type="checkbox" id="selectAllJasaCuci" onchange="toggleAllJasaCuci(this)" class="w-4 h-4 mr-2 rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                            Pilih Semua
                        </label>
                    </div>
                    <div class="overflow-x-auto min-h-[200px]">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                        <span class="sr-only">Checkbox</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gram</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pcs</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Gram</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="tbody_jasacuci">
                                @forelse($jasaCuciSummary as $index => $item)
                                <tr class="hover:bg-purple-50/30" id="tr_jasacuci_{{ $item->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                            class="row-checkbox w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer"
                                            data-sumber="jasa_cuci"
                                            data-nama="{{ $item->nama_barang }}"
                                            data-gram="{{ $item->total_gram }}"
                                            data-pcs="{{ $item->total_pcs }}"
                                            data-box="0"
                                            onchange="updateSelection()">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}.</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ number_format($item->total_gram, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->total_pcs }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" step="0.01" min="0.01" max="{{ $item->total_gram }}"
                                            placeholder="Jml Gram"
                                            class="gram-input w-24 px-2 py-1 border border-gray-300 rounded focus:ring-purple-500 focus:border-purple-500 text-sm"
                                            data-max="{{ $item->total_gram }}"
                                            onchange="validateGramInput(this)">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Tidak ada ketersediaan stok Jasa Cuci yang bisa digrading.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Selection Summary -->
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="text-sm text-gray-600">
                            <span id="selectedCount">0</span> item dipilih |
                            Total: <span id="totalSelectedGram" class="font-semibold text-blue-600">0</span> Gram
                        </div>
                        <button type="button" onclick="return checkSelection()"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" id="btnLanjut" disabled>
                            Lanjut ke Step 2
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
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

            btnDemak.classList.add('border-blue-500', 'text-blue-600');
            btnDemak.classList.remove('border-gray-300', 'text-gray-500');

            btnJasaCuci.classList.add('border-gray-300', 'text-gray-500');
            btnJasaCuci.classList.remove('border-purple-500', 'text-purple-600');
        } else {
            tableJasaCuci.classList.remove('hidden');
            tableDemak.classList.add('hidden');

            btnJasaCuci.classList.add('border-purple-500', 'text-purple-600');
            btnJasaCuci.classList.remove('border-gray-300', 'text-gray-500');

            btnDemak.classList.add('border-gray-300', 'text-gray-500');
            btnDemak.classList.remove('border-blue-500', 'text-blue-600');
        }
    }

    function validateGramInput(input) {
        const max = parseFloat(input.dataset.max);
        const val = parseFloat(input.value);
        if (val > max) {
            input.value = max;
            alert('Jumlah tidak boleh lebih dari total stok (' + max + ' Gr)');
        }
        updateSelection();
    }

    function toggleAllDemak(checkbox) {
        const checkboxes = document.querySelectorAll('#tbody_demak .row-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
        updateSelection();
    }

    function toggleAllJasaCuci(checkbox) {
        const checkboxes = document.querySelectorAll('#tbody_jasacuci .row-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
        updateSelection();
    }

    function updateSelection() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;

        document.getElementById('selectedCount').textContent = selectedCount;

        const btn = document.getElementById('btnLanjut');
        btn.disabled = selectedCount === 0;

        // Calculate total gram
        let totalGram = 0;
        selectedCheckboxes.forEach(cb => {
            const tr = cb.closest('tr');
            const gramInput = tr.querySelector('.gram-input');
            if (gramInput && gramInput.value) {
                totalGram += parseFloat(gramInput.value) || 0;
            } else {
                totalGram += parseFloat(cb.dataset.gram) || 0;
            }
        });

        document.getElementById('totalSelectedGram').textContent =
            new Intl.NumberFormat('id-ID').format(totalGram);
    }

    function checkSelection() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Pilih minimal satu item yang akan digrading.');
            return false;
        }

        // Build selected items data
        const selectedItems = [];
        selectedCheckboxes.forEach(cb => {
            const tr = cb.closest('tr');
            const gramInput = tr.querySelector('.gram-input');
            let gram = 0;
            if (gramInput && gramInput.value) {
                gram = parseFloat(gramInput.value) || 0;
            } else {
                gram = parseFloat(cb.dataset.gram) || 0;
            }

            selectedItems.push({
                id: cb.value,
                sumber: cb.dataset.sumber,
                nama: cb.dataset.nama,
                gram: gram,
                pcs: cb.dataset.pcs,
                box: cb.dataset.box
            });
        });

        // Store as JSON in hidden input
        document.getElementById('items_data').value = JSON.stringify(selectedItems);
        document.getElementById('mainForm').submit();
    }
</script>
@endsection
