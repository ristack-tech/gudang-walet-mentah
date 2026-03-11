@extends('layouts.app')

@section('title', 'Tambah Grading - Step 2')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Grading - Step 2</h1>
                <p class="mt-1 text-sm text-gray-600">Lengkapi detail grading untuk Barang: <span class="font-bold text-blue-600">{{ $namaBarang }}</span></p>
            </div>
            <a href="{{ route('stok-grading.create.step1') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
                Kembali
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between max-w-xl mx-auto">
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-semibold text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-green-600">Pilih Stok</span>
                </div>
                <div class="flex-1 h-0.5 bg-blue-200 mx-2 sm:mx-4 -mt-6"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                        2</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Input Grading</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="text-red-700 text-sm">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('success'))
            <div id="alert-success" class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                <div class="text-green-700 text-sm">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Item Info (Step 1 Summary) -->
        <div class="bg-gray-50 shadow-sm border rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Barang Grading</h3>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Barang (Stok Gudang)</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-medium text-blue-600">
                        {{ $namaBarang }}
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-2 {{ $sumberStok == 'demak' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ $sumberStok == 'demak' ? 'Demak' : 'Jasa Cuci' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pcs Dialokasikan (Awal)</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-medium">
                        {{ number_format($pcsAwal, 0, ',', '.') }} Pcs
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Sisa Stok Gudang</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-medium">
                        {{ number_format($gramAwal, 2, ',', '.') }} Gram
                    </dd>
                </div>
            </dl>
        </div>

        <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm p-6 relative overflow-visible z-10">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Tambahkan Grade</h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Grade</label>
                    <select id="inputTipe" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" onchange="updateGradeOptions()">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="lempeng_bersih">Lempeng Bersih</option>
                        <option value="lempeng_kotor">Lempeng Kotor</option>
                        <option value="mangkok">Mangkok</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Grade</label>
                    <select id="inputGradeName" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" disabled>
                        <option value="">-- Pilih Grade --</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berat (Gram)</label>
                    <input type="number" id="inputBerat" step="0.01" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Box (Opsional)</label>
                    <input type="number" id="inputBox" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                </div>
                <div class="md:col-span-2">
                    <button type="button" onclick="addGradeToList()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-500 text-white font-medium rounded-md hover:bg-green-600 shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah
                    </button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('stok-grading.store') }}" id="gradingForm">
            @csrf
            <input type="hidden" name="stok_id" value="{{ $stokId }}">
            <input type="hidden" name="pcs_awal" value="{{ $pcsAwal }}">
            <input type="hidden" name="sumber_stok" value="{{ $sumberStok }}">
            <input type="hidden" id="max_gram" value="{{ $gramAwal }}">
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Hasil Grading</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse" id="summaryTable">
                            <thead>
                                <tr class="bg-gray-50 border-y border-gray-200">
                                    <th class="text-left text-sm font-medium text-gray-600 tracking-wider px-4 py-3 w-16">No</th>
                                    <th class="text-left text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Tipe Grade</th>
                                    <th class="text-left text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Nama Grade</th>
                                    <th class="text-right text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Berat (Gr)</th>
                                    <th class="text-right text-sm font-medium text-gray-600 tracking-wider px-4 py-3">Jumlah Box</th>
                                    <th class="text-center text-sm font-medium text-gray-600 tracking-wider px-4 py-3 w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="summaryTableBody" class="divide-y divide-gray-200">
                                <!-- Dynamic rows inserted here via JS -->
                                <tr id="emptyStateRow">
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50/50">
                                        Belum ada grade yang ditambahkan. Gunakan form di atas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total Quantities Display -->
                <div class="px-6 pb-6 w-full">
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Berat Keseluruhan:</span>
                            <span class="text-lg font-bold text-blue-600" id="displayTotalBerat">0,00 gram</span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            Total Kemasan: <span class="font-medium text-gray-700" id="displayTotalBox">0 box</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Form -->
                <div class="p-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium" id="gradeCountDisplay">0</span> grade diisi valid
                        </div>
                        
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white rounded-md hover:bg-blue-700 font-medium shadow-sm transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Hasil Batch
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    const gradesData = {
        lempeng_bersih: @json($gradesLempengBersih),
        lempeng_kotor: @json($gradesLempengKotor),
        mangkok: []
    };
    
    // Flatten Mangkok array for dropdown mapping
    const mangkokRaw = @json($gradesMangkok);
    for (const [kategori, subgrades] of Object.entries(mangkokRaw)) {
        subgrades.forEach(grade => {
            gradesData.mangkok.push(`${kategori} (${grade})`);
        });
    }

    const typeLabels = {
        lempeng_bersih: 'Lempeng Bersih',
        lempeng_kotor: 'Lempeng Kotor',
        mangkok: 'Mangkok'
    };

    let gradeListIndex = 0;
    let addedGrades = new Set(); // to prevent duplicate grade selections

    function updateGradeOptions() {
        const typeSelect = document.getElementById('inputTipe');
        const gradeSelect = document.getElementById('inputGradeName');
        const selectedType = typeSelect.value;
        
        // Reset grade options
        gradeSelect.innerHTML = '<option value="">-- Pilih Grade --</option>';
        gradeSelect.disabled = true;

        if (selectedType && gradesData[selectedType]) {
            gradesData[selectedType].forEach(grade => {
                const option = document.createElement('option');
                option.value = grade;
                option.textContent = grade;
                gradeSelect.appendChild(option);
            });
            gradeSelect.disabled = false;
        }
    }

    function addGradeToList() {
        const typeSelect = document.getElementById('inputTipe');
        const gradeSelect = document.getElementById('inputGradeName');
        const inputBerat = document.getElementById('inputBerat');
        const inputBox = document.getElementById('inputBox');

        const typeVal = typeSelect.value;
        const gradeVal = gradeSelect.value;
        const beratVal = parseFloat(inputBerat.value);
        const boxVal = parseInt(inputBox.value) || 0;

        if (!typeVal || !gradeVal) {
            alert('Silakan pilih Tipe dan Nama Grade terlebih dahulu.');
            return;
        }

        if (isNaN(beratVal) || beratVal <= 0) {
            alert('Silakan masukkan Berat (Gram) yang valid (> 0).');
            return;
        }

        // Check duplicate
        const uniqueKey = `${typeVal}_${gradeVal}`;
        if (addedGrades.has(uniqueKey)) {
            alert(`Grade ${gradeVal} (${typeLabels[typeVal]}) sudah ada di dalam daftar! Silakan edit baris yang sudah ada atau pilih grade lain.`);
            return;
        }

        // Add to Set
        addedGrades.add(uniqueKey);

        const tbody = document.getElementById('summaryTableBody');
        const emptyState = document.getElementById('emptyStateRow');
        if (emptyState) emptyState.remove();

        // Create row
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-blue-50/50 transition-colors group';
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
        
        // Reset inputs
        gradeSelect.value = '';
        inputBerat.value = '';
        inputBox.value = '';
        
        gradeListIndex++;
        updateRowNumbers();
        calculateTotals();
    }

    function removeRow(index, uniqueKey) {
        document.getElementById(`row_${index}`).remove();
        addedGrades.delete(uniqueKey);
        
        const tbody = document.getElementById('summaryTableBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyStateRow">
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50/50">
                        Belum ada grade yang ditambahkan. Gunakan form di atas.
                    </td>
                </tr>
            `;
        } else {
            updateRowNumbers();
        }
        calculateTotals();
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#summaryTableBody tr:not(#emptyStateRow)');
        rows.forEach((row, index) => {
            row.querySelector('.row-number').textContent = index + 1;
        });
    }

    function formatNumberID(num) {
        return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num);
    }

    function calculateTotals() {
        let totalBerat = 0;
        let totalBox = 0;
        let validGradeCount = 0;

        document.querySelectorAll('#summaryTableBody tr:not(#emptyStateRow)').forEach(function(row) {
            let beratInput = row.querySelector('.form-input-berat');
            let boxInput = row.querySelector('.form-input-box');
            
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
                
                if (hasValue) {
                    validGradeCount++;
                }
            }
        });

        document.getElementById('displayTotalBerat').textContent = formatNumberID(totalBerat) + ' gram';
        document.getElementById('displayTotalBox').textContent = formatNumberID(totalBox) + ' box';
        document.getElementById('gradeCountDisplay').textContent = validGradeCount;
    }

    // Recalculate if user edits the input values inside the table directly
    document.addEventListener('input', function(e) {
        if(e.target.classList.contains('form-input-berat') || e.target.classList.contains('form-input-box')) {
            calculateTotals();
        }
    });

    document.getElementById('gradingForm').addEventListener('submit', function(e) {
        let totalGram = 0;
        document.querySelectorAll('.form-input-berat').forEach(function(input) {
            let val = parseFloat(input.value);
            if(!isNaN(val)) totalGram += val;
        });

        let maxGram = parseFloat(document.getElementById('max_gram').value);

        totalGram = Math.round(totalGram * 100) / 100;
        maxGram = Math.round(maxGram * 100) / 100;

        if (totalGram <= 0) {
            e.preventDefault();
            alert('Minimal harus ada satu grade yang ditambahkan dan memiliki berat (Gram).');
            return;
        }

        if (totalGram > maxGram) {
            e.preventDefault();
            alert('Total berat (' + totalGram + ' Gr) melebihi sisa stok awal yang tersedia (' + maxGram + ' Gr).');
            return;
        }

        let isBoxWithoutBerat = false;
        document.querySelectorAll('#summaryTableBody tr:not(#emptyStateRow)').forEach(function(row) {
            let beratInput = row.querySelector('.form-input-berat');
            let boxInput = row.querySelector('.form-input-box');
            
            if (beratInput && boxInput) {
                let boxVal = parseInt(boxInput.value);
                let beratVal = parseFloat(beratInput.value);
                
                if (boxVal > 0 && (isNaN(beratVal) || beratVal <= 0)) {
                    isBoxWithoutBerat = true;
                }
            }
        });

        if (isBoxWithoutBerat) {
            e.preventDefault();
            alert('Berat (Gram) wajib diisi jika Anda mengisi nilai Box pada sebuah Grade.');
        }
    });
</script>
@endsection
