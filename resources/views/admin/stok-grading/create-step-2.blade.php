@extends('layouts.app')

@section('title', 'Tambah Grading - Step 2')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Form Retur Grading</h1>
                <p class="mt-2 text-sm text-gray-500">Centang item yang akan digrading dan masukkan data hasil grading.</p>
            </div>
            <a href="{{ route("stok-grading.create.step1") }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>

        <!-- Info Card -->
        @if(isset($allItems) && count($allItems) > 0)
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
            <div class="relative z-10">
                <h3 class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Item Dipilih di Step 1</h3>
                <p class="text-2xl font-bold">{{ count($allItems) }} item</p>
                <p class="text-blue-200 text-sm mt-1">
                    Total: <span id="infoTotalGram">0</span> Gram
                </p>
            </div>
        </div>
        @endif

        <!-- Items Checklist Table -->
        <div class="bg-white shadow-xl shadow-gray-200/50 border border-gray-100 rounded-2xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Pilih Item untuk Digrading</h3>
                <p class="text-sm text-gray-500 mt-1">Centang item yang akan diproses. Hanya item yang dicentang yang akan digrading.</p>
            </div>

            <div class="overflow-x-auto p-4">
                <table class="min-w-full border-separate border-spacing-0" style="border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50/50">
                            <th class="py-3 px-4 text-center rounded-l-lg border-y border-l border-gray-200 w-16">
                                <input type="checkbox" id="selectAllItems" class="h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" onchange="toggleAllItems(this)">
                            </th>
                            <th class="py-3 px-4 text-left border-y border-gray-200">Nama Barang</th>
                            <th class="py-3 px-4 text-center border-y border-gray-200">Sumber</th>
                            <th class="py-3 px-4 text-right border-y border-gray-200">Gram Tersedia</th>
                            <th class="py-3 px-4 text-right border-y border-gray-200 bg-blue-50/30 rounded-r-lg border-r">Gram Input <span class="text-red-500">*</span></th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        @if(isset($allItems) && count($allItems) > 0)
                            @foreach($allItems as $idx => $item)
                            <tr class="bg-white hover:bg-blue-50/20 transition duration-150 ease-in-out group" id="item_row_{{ $idx }}">
                                <td class="px-4 py-4 border-y border-l border-gray-100 rounded-l-xl text-center">
                                    <input type="checkbox" name="items[{{ $idx }}][is_checked]" value="1"
                                        class="item-checkbox h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer"
                                        data-idx="{{ $idx }}"
                                        data-gram-available="{{ $item["gram"] }}"
                                        onchange="updateItemSelection()">
                                    <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $item["id"] }}">
                                    <input type="hidden" name="items[{{ $idx }}][barang_id]" value="{{ $item["barang_id"] }}">
                                    <input type="hidden" name="items[{{ $idx }}][sumber]" value="{{ $item["sumber"] }}">
                                    <input type="hidden" name="items[{{ $idx }}][nama]" value="{{ $item["nama"] }}">
                                    @if($item["sumber"] == "demak")
                                    <input type="hidden" name="items[{{ $idx }}][partai]" value="{{ $item["partai"] }}">
                                    @elseif($item["sumber"] == "jasa_cuci")
                                    <input type="hidden" name="items[{{ $idx }}][kode_bahan]" value="{{ $item["kode_bahan"] }}">
                                    <input type="hidden" name="items[{{ $idx }}][kode_bahan_id]" value="{{ $item["kode_bahan_id"] }}">
                                    @elseif($item["sumber"] == "pembelian_supplier")
                                    <input type="hidden" name="items[{{ $idx }}][supplier_name]" value="{{ $item["supplier_name"] ?? '' }}">
                                    <input type="hidden" name="items[{{ $idx }}][supplier_id]" value="{{ $item["supplier_id"] ?? '' }}">
                                    @endif
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100 group-hover:border-blue-100">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $item["nama"] }}</span>
                                        <span class="text-xs text-gray-400">ID: {{ $item["id"] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium {{ $item["sumber"] == "demak" ? "bg-blue-100 text-blue-700" : ($item["sumber"] == "jasa_cuci" ? "bg-orange-100 text-orange-700" : "bg-teal-100 text-teal-700") }}">
                                        {{ $item["sumber"] == "demak" ? "Demak" : ($item["sumber"] == "jasa_cuci" ? "Jasa Cuci" : "Pembelian Supplier") }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100 text-right">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item["gram"], 2, ",", ".") }} gr</span>
                                </td>
                                <td class="px-4 py-4 border-y border-r border-gray-100 rounded-r-xl bg-blue-50/10 group-hover:bg-blue-50/20">
                                    <input type="number" step="0.01" name="items[{{ $idx }}][gram]"
                                        class="gram-input w-28 text-right border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm bg-white"
                                        value="{{ $item["gram"] }}"
                                        min="0.01"
                                        max="{{ $item["gram"] }}"
                                        placeholder="0.00"
                                        onchange="validateGram(this, {{ $item["gram"] }})">
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-12 px-4 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tidak ada item yang dipilih di step 1.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Selection Summary -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        <span id="selectedItemCount">0</span> item dipilih |
                        Total Gram: <span id="totalSelectedGram" class="font-semibold text-blue-600">0</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Grade Input Form -->
        <div class="bg-white shadow-xl shadow-gray-200/50 border border-gray-100 rounded-2xl">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Tambahkan Grade</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih tipe dan grade, masukkan berat. Grade yang ditambahkan akan diterapkan ke semua item yang dicentang di atas.</p>
            </div>

            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 itemsend">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Grade</label>
                        <select id="inputTipe" class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" onchange="updateGradeOptions()">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="lempeng_bersih">Lempeng Bersih</option>
                            <option value="lempeng_kotor">Lempeng Kotor</option>
                            <option value="mangkok">Mangkok</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Grade</label>
                        <select id="inputGradeName" class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" disabled>
                            <option value="">-- Pilih Grade --</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat (Gram)</label>
                        <input type="number" id="inputBerat" step="0.01" class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Box <span class="text-xs text-gray-400">(catatan)</span></label>
                        <input type="number" id="inputBox" class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" onclick="addGradeToList()"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-green-500 text-white font-medium rounded-md hover:bg-green-600 shadow-sm transition-colors duration-200">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grades Table -->
            <div class="p-6">
                <h4 class="text-sm font-medium text-gray-700 mb-4">Daftar Grade yang Akan Ditambahkan</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse" id="summaryTable">
                        <thead>
                            <tr class="bg-gray-50 border-y border-gray-200">
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider px-4 py-3 w-16">No</th>
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Tipe Grade</th>
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Nama Grade</th>
                                <th class="text-right text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Berat (Gr)</th>
                                <th class="text-right text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Box</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider px-4 py-3 w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="summaryTableBody" class="divide-y divide-gray-200">
                            <tr id="emptyStateRow">
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50/50">
                                    Belum ada grade yang ditambahkan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total Display -->
                <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Total Berat Keseluruhan:</span>
                        <span class="text-lg font-bold text-blue-600" id="displayTotalBerat">0,00 gram</span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        Total Kemasan: <span class="font-medium text-gray-700" id="displayTotalBox">0 box</span>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500">
                    <span id="gradeCountDisplay">0</span> grade | <span id="affectedItemsDisplay">0</span> item akan diproses
                </div>
                <button type="button" onclick="openConfirmModal()" id="btnSubmit"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Grading
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Konfirmasi Simpan Grading</h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Apakah Anda yakin ingin menyimpan grading ini? Data akan diterapkan ke <strong id="modalItemCount">0</strong> item yang dicentang.<br><br>
                                    Grade yang ditambahkan: <span id="modalGradeCount" class="font-semibold text-blue-600">0</span><br>
                                    Total Berat: <span id="modalTotalBerat" class="font-semibold text-blue-600">0</span> gram
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" onclick="submitForm()" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto transition-colors">Ya, Simpan</button>
                    <button type="button" onclick="closeConfirmModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="gradingForm" method="POST" action="{{ route("stok-grading.store") }}">
    @csrf
    <input type="hidden" name="items_data" id="itemsDataInput">
</form>

<script>
    const gradesData = {
        lempeng_bersih: @json($gradesLempengBersih),
        lempeng_kotor: @json($gradesLempengKotor),
        mangkok: []
    };

    const mangkokRaw = @json($gradesMangkok);
    for (const [kategori, subgrades] of Object.entries(mangkokRaw)) {
        subgrades.forEach(grade => {
            gradesData.mangkok.push(`${kategori} (${grade})`);
        });
    }

    const typeLabels = {
        lempeng_bersih: "Lempeng Bersih",
        lempeng_kotor: "Lempeng Kotor",
        mangkok: "Mangkok"
    };

    let gradeListIndex = 0;
    let addedGrades = new Set();
    let selectedItemCount = 0;

    // Initialize
    document.addEventListener("DOMContentLoaded", function() {
        updateItemSelection();
        calculateInfoTotal();
    });

    function toggleAllItems(checkbox) {
        document.querySelectorAll(".item-checkbox").forEach(cb => cb.checked = checkbox.checked);
        updateItemSelection();
    }

    function updateItemSelection() {
        const checkedBoxes = document.querySelectorAll(".item-checkbox:checked");
        selectedItemCount = checkedBoxes.length;
        document.getElementById("selectedItemCount").textContent = selectedItemCount;

        // Calculate total gram from checked items
        let totalGram = 0;
        checkedBoxes.forEach(cb => {
            const tr = cb.closest("tr");
            const gramInput = tr.querySelector(".gram-input");
            totalGram += parseFloat(gramInput.value) || 0;
        });
        document.getElementById("totalSelectedGram").textContent = new Intl.NumberFormat("id-ID").format(totalGram);

        // Update submit button state
        const btnSubmit = document.getElementById("btnSubmit");
        const hasGrades = document.querySelectorAll("#summaryTableBody tr:not(#emptyStateRow)").length > 0;
        btnSubmit.disabled = selectedItemCount === 0 || !hasGrades;

        document.getElementById("affectedItemsDisplay").textContent = selectedItemCount;
    }

    function calculateInfoTotal() {
        const allItems = document.querySelectorAll(".item-checkbox");
        let total = 0;
        allItems.forEach(cb => {
            total += parseFloat(cb.dataset.gramAvailable) || 0;
        });
        document.getElementById("infoTotalGram").textContent = new Intl.NumberFormat("id-ID").format(total);
    }

    function validateGram(input, max) {
        let val = parseFloat(input.value) || 0;
        if (val > max) {
            input.value = max;
            alert("Jumlah tidak boleh lebih dari " + max + " gram");
        }
        updateItemSelection();
    }

    function updateGradeOptions() {
        const typeSelect = document.getElementById("inputTipe");
        const gradeSelect = document.getElementById("inputGradeName");
        const selectedType = typeSelect.value;

        gradeSelect.innerHTML = "<option value=\"\">-- Pilih Grade --</option>";
        gradeSelect.disabled = true;

        if (selectedType && gradesData[selectedType]) {
            gradesData[selectedType].forEach(grade => {
                const option = document.createElement("option");
                option.value = grade;
                option.textContent = grade;
                gradeSelect.appendChild(option);
            });
            gradeSelect.disabled = false;
        }
    }

    function addGradeToList() {
        if (selectedItemCount === 0) {
            alert("Pilih minimal satu item di tabel atas terlebih dahulu.");
            return;
        }

        const typeSelect = document.getElementById("inputTipe");
        const gradeSelect = document.getElementById("inputGradeName");
        const inputBerat = document.getElementById("inputBerat");
        const inputBox = document.getElementById("inputBox");

        const typeVal = typeSelect.value;
        const gradeVal = gradeSelect.value;
        const beratVal = parseFloat(inputBerat.value);
        const boxVal = parseInt(inputBox.value) || 0;

        if (!typeVal || !gradeVal) {
            alert("Silakan pilih Tipe dan Nama Grade terlebih dahulu.");
            return;
        }

        if (isNaN(beratVal) || beratVal <= 0) {
            alert("Silakan masukkan Berat (Gram) yang valid (> 0).");
            return;
        }

        const uniqueKey = `${typeVal}_${gradeVal}`;
        if (addedGrades.has(uniqueKey)) {
            alert(`Grade ${gradeVal} (${typeLabels[typeVal]}) sudah ada di dalam daftar!`);
            return;
        }

        addedGrades.add(uniqueKey);

        const tbody = document.getElementById("summaryTableBody");
        const emptyState = document.getElementById("emptyStateRow");
        if (emptyState) emptyState.remove();

        const tr = document.createElement("tr");
        tr.className = "hover:bg-blue-50/50 transition-colors group";
        tr.id = `row_${gradeListIndex}`;
        tr.dataset.uniqueKey = uniqueKey;

        tr.innerHTML = `
            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-800 row-number text-center"></td>
            <td class="whitespace-nowrap px-4 py-3">
                <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                    ${typeLabels[typeVal]}
                </span>
                <input type="hidden" name="grades[${gradeListIndex}][type]" value="${typeVal}">
            </td>
            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">
                ${gradeVal}
                <input type="hidden" name="grades[${gradeListIndex}][name]" value="${gradeVal}">
            </td>
            <td class="whitespace-nowrap px-4 py-3 text-right">
                <input type="number" step="0.01" name="grades[${gradeListIndex}][berat]" value="${beratVal}" class="form-input-berat w-24 text-right border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-2 py-1 text-sm bg-white">
            </td>
            <td class="whitespace-nowrap px-4 py-3 text-right">
                <input type="number" name="grades[${gradeListIndex}][box]" value="${boxVal}" class="form-input-box w-20 text-right border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-2 py-1 text-sm bg-white">
            </td>
            <td class="whitespace-nowrap px-4 py-3 text-center">
                <button type="button" onclick="removeRow(${gradeListIndex}, '${uniqueKey}')" class="text-red-500 hover:text-red-700 p-1.5 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        gradeSelect.value = "";
        inputBerat.value = "";
        inputBox.value = "";

        gradeListIndex++;
        updateRowNumbers();
        calculateTotals();
        updateItemSelection(); // Update submit button state
    }

    function removeRow(index, uniqueKey) {
        document.getElementById(`row_${index}`).remove();
        addedGrades.delete(uniqueKey);

        const tbody = document.getElementById("summaryTableBody");
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyStateRow">
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50/50">
                        Belum ada grade yang ditambahkan.
                    </td>
                </tr>
            `;
        } else {
            updateRowNumbers();
        }
        calculateTotals();
        updateItemSelection();
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll("#summaryTableBody tr:not(#emptyStateRow)");
        rows.forEach((row, index) => {
            row.querySelector(".row-number").textContent = index + 1;
        });
    }

    function formatNumberID(num) {
        return new Intl.NumberFormat("id-ID", { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num);
    }

    function calculateTotals() {
        let totalBerat = 0;
        let totalBox = 0;
        let validGradeCount = 0;

        document.querySelectorAll("#summaryTableBody tr:not(#emptyStateRow)").forEach(function(row) {
            let beratInput = row.querySelector(".form-input-berat");
            let boxInput = row.querySelector(".form-input-box");

            if (beratInput || boxInput) {
                let beratVal = parseFloat(beratInput.value);
                let boxVal = parseInt(boxInput.value);
                let hasValue = false;

                if (!isNaN(beratVal) && beratVal > 0) {
                    totalBerat += beratVal;
                    hasValue = true;
                }

                if (!isNaN(boxVal) && boxVal > 0) {
                    totalBox += boxVal;
                    hasValue = true;
                }

                if (hasValue) validGradeCount++;
            }
        });

        document.getElementById("displayTotalBerat").textContent = formatNumberID(totalBerat) + " gram";
        document.getElementById("displayTotalBox").textContent = formatNumberID(totalBox) + " box";
        document.getElementById("gradeCountDisplay").textContent = validGradeCount;

        // Update submit button
        const btnSubmit = document.getElementById("btnSubmit");
        btnSubmit.disabled = selectedItemCount === 0 || validGradeCount === 0;
    }

    document.addEventListener("input", function(e) {
        if (e.target.classList.contains("form-input-berat") || e.target.classList.contains("form-input-box")) {
            calculateTotals();
        }
    });

    function openConfirmModal() {
        if (selectedItemCount === 0) {
            alert("Pilih minimal satu item terlebih dahulu.");
            return;
        }

        const gradeRows = document.querySelectorAll("#summaryTableBody tr:not(#emptyStateRow)");
        if (gradeRows.length === 0) {
            alert("Tambahkan minimal satu grade terlebih dahulu.");
            return;
        }

        let totalBerat = 0;
        document.querySelectorAll(".form-input-berat").forEach(input => {
            totalBerat += parseFloat(input.value) || 0;
        });

        document.getElementById("modalItemCount").textContent = selectedItemCount;
        document.getElementById("modalGradeCount").textContent = gradeRows.length;
        document.getElementById("modalTotalBerat").textContent = formatNumberID(totalBerat);

        document.getElementById("confirmModal").classList.remove("hidden");
    }

    function closeConfirmModal() {
        document.getElementById("confirmModal").classList.add("hidden");
    }

    function submitForm() {
        // Collect checked items data
        const checkedItems = [];
        document.querySelectorAll(".item-checkbox:checked").forEach(cb => {
            const tr = cb.closest("tr");
            const gramInput = tr.querySelector(".gram-input");
            const idx = cb.getAttribute("data-idx");

            const item = {
                id: tr.querySelector(`input[name='items[${idx}][id]']`).value,
                barang_id: tr.querySelector(`input[name='items[${idx}][barang_id]']`).value,
                sumber: tr.querySelector(`input[name='items[${idx}][sumber]']`).value,
                nama: tr.querySelector(`input[name='items[${idx}][nama]']`).value,
                gram: parseFloat(gramInput.value) || 0
            };

            // Add source-specific fields
            const partaiInput = tr.querySelector(`input[name='items[${idx}][partai]']`);
            const kodeBahanInput = tr.querySelector(`input[name='items[${idx}][kode_bahan]']`);
            const supplierNameInput = tr.querySelector(`input[name='items[${idx}][supplier_name]']`);

            if (partaiInput) {
                item.partai = partaiInput.value;
            } else if (kodeBahanInput) {
                item.kode_bahan = kodeBahanInput.value;
                item.kode_bahan_id = tr.querySelector(`input[name='items[${idx}][kode_bahan_id]']`).value;
            } else if (supplierNameInput) {
                item.supplier_name = supplierNameInput.value;
                item.supplier_id = tr.querySelector(`input[name='items[${idx}][supplier_id]']`).value;
            }

            checkedItems.push(item);
        });

        // Store in hidden input as JSON
        document.getElementById("itemsDataInput").value = JSON.stringify(checkedItems);

        // Also copy grades from summaryTable into hidden inputs
        const form = document.getElementById("gradingForm");
        
        // Remove any existing grade hidden inputs
        form.querySelectorAll("input[name^='grades']").forEach(el => el.remove());
        
        // Get all grade rows and create hidden inputs
        const gradeRows = document.querySelectorAll("#summaryTableBody tr:not(#emptyStateRow)");
        gradeRows.forEach((row, idx) => {
            const typeInput = row.querySelector("input[name*='[type]']");
            const nameInput = row.querySelector("input[name*='[name]']");
            const beratInput = row.querySelector(".form-input-berat");
            const boxInput = row.querySelector(".form-input-box");
            
            if (typeInput && nameInput && beratInput) {
                // Create hidden inputs for each grade field
                const createHidden = (name, value) => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = name;
                    input.value = value || "";
                    form.appendChild(input);
                };
                
                createHidden(`grades[${idx}][type]`, typeInput.value);
                createHidden(`grades[${idx}][name]`, nameInput.value);
                createHidden(`grades[${idx}][berat]`, beratInput.value);
                createHidden(`grades[${idx}][box]`, boxInput ? boxInput.value : "0");
            }
        });

        // Submit form
        form.submit();
    }
</script>
@endsection
