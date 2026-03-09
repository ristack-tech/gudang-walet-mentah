@extends('layouts.app')

@section('title', 'Tambah Grading - Step 2')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Grading - Step 2</h1>
                <p class="mt-1 text-sm text-gray-600">Lengkapi detail grading untuk Batch: <span class="font-bold text-blue-600">{{ $batchId }}</span></p>
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

        <div class="mb-6 flex space-x-4">
            <button type="button" id="tabLempengBersih" onclick="switchTab('lempengBersih')" class="px-6 py-2 bg-blue-50 border border-blue-200 text-blue-600 font-medium rounded-md shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Lempeng Bersih
            </button>
            <button type="button" id="tabLempengKotor" onclick="switchTab('lempengKotor')" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Lempeng Kotor
            </button>
            <button type="button" id="tabMangkok" onclick="switchTab('mangkok')" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Mangkok
            </button>
        </div>

        <form method="POST" action="{{ route('stok-grading.store') }}" id="mainForm">
            @csrf
            <input type="hidden" name="stok_id" value="{{ $batchId }}">
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6">
                    <!-- Tables for Layout -->
                    <table class="w-full" id="tableLempengBersih">
                        <thead>
                            <tr>
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider w-16 px-4 pb-4">No</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/4 pb-4">Pilih Grade</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Berat</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Box</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradesLempengBersih as $index => $grade)
                            <tr>
                                <td class="whitespace-nowrap text-sm text-gray-800 px-4 py-3">{{ $index + 1 }}.</td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/4">
                                    <div class="w-full px-4 py-2 border border-gray-300 rounded-md text-center text-gray-700 font-medium bg-gray-50">
                                        {{ $grade }}
                                    </div>
                                    <input type="hidden" name="grades[{{ $index }}][name]" value="{{ $grade }}">
                                    <input type="hidden" name="grades[{{ $index }}][type]" value="lempeng_bersih">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][berat]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][box]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="w-full hidden" id="tableLempengKotor">
                        <thead>
                            <tr>
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider w-16 px-4 pb-4">No</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/4 pb-4">Pilih Grade</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Berat</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Box</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradesLempengKotor as $idx => $grade)
                            @php $index = count($gradesLempengBersih) + $idx; @endphp
                            <tr>
                                <td class="whitespace-nowrap text-sm text-gray-800 px-4 py-3">{{ $idx + 1 }}.</td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/4">
                                    <div class="w-full px-4 py-2 border border-gray-300 rounded-md text-center text-gray-700 font-medium bg-gray-50">
                                        {{ $grade }}
                                    </div>
                                    <input type="hidden" name="grades[{{ $index }}][name]" value="{{ $grade }}">
                                    <input type="hidden" name="grades[{{ $index }}][type]" value="lempeng_kotor">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][berat]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][box]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="hidden" id="containerMangkok">
                        <div class="mb-4 flex space-x-2">
                            @foreach($gradesMangkok as $kategori => $subgrades)
                                <button type="button" id="tabMangkok_{{ Str::slug($kategori) }}" onclick="switchMangkokTab('{{ Str::slug($kategori) }}')" class="px-4 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-50 shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ $kategori }}
                                </button>
                            @endforeach
                        </div>

                        @php $globalIndex = count($gradesLempengBersih) + count($gradesLempengKotor); @endphp
                        @foreach($gradesMangkok as $kategori => $subgrades)
                            <table class="w-full hidden mangkok-table" id="tableMangkok_{{ Str::slug($kategori) }}">
                                <thead>
                                    <tr>
                                        <th class="text-left text-sm font-medium text-gray-600 tracking-wider w-16 px-4 pb-4">No</th>
                                        <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/4 pb-4">Pilih Grade</th>
                                        <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Berat</th>
                                        <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Box</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subgrades as $idx => $grade)
                                    @php $index = $globalIndex++; @endphp
                                    <tr>
                                        <td class="whitespace-nowrap text-sm text-gray-800 px-4 py-3">{{ $idx + 1 }}.</td>
                                        <td class="whitespace-nowrap px-4 py-3 w-1/4">
                                            <div class="w-full px-4 py-2 border border-gray-300 rounded-md text-center text-gray-700 font-medium bg-gray-50">
                                                {{ $kategori }} ({{ $grade }})
                                            </div>
                                            <input type="hidden" name="grades[{{ $index }}][name]" value="{{ $kategori }} ({{ $grade }})">
                                            <input type="hidden" name="grades[{{ $index }}][type]" value="mangkok">
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                            <input type="text" name="grades[{{ $index }}][berat]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                            <input type="text" name="grades[{{ $index }}][box]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-700 shadow-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Grading
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function switchTab(tab) {
        const btnLempengBersih = document.getElementById('tabLempengBersih');
        const btnLempengKotor = document.getElementById('tabLempengKotor');
        const btnMangkok = document.getElementById('tabMangkok');
        
        const tableLempengBersih = document.getElementById('tableLempengBersih');
        const tableLempengKotor = document.getElementById('tableLempengKotor');
        const containerMangkok = document.getElementById('containerMangkok');

        // Reset all
        [tableLempengBersih, tableLempengKotor, containerMangkok].forEach(t => t.classList.add('hidden'));
        
        const activeClass = ['bg-blue-50', 'border-blue-200', 'text-blue-600'];
        const inactiveClass = ['bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50'];

        [btnLempengBersih, btnLempengKotor, btnMangkok].forEach(b => {
            b.classList.remove(...activeClass);
            b.classList.add(...inactiveClass);
        });

        // Set active
        if (tab === 'lempengBersih') {
            tableLempengBersih.classList.remove('hidden');
            btnLempengBersih.classList.remove(...inactiveClass);
            btnLempengBersih.classList.add(...activeClass);
        } else if (tab === 'lempengKotor') {
            tableLempengKotor.classList.remove('hidden');
            btnLempengKotor.classList.remove(...inactiveClass);
            btnLempengKotor.classList.add(...activeClass);
        } else {
            containerMangkok.classList.remove('hidden');
            btnMangkok.classList.remove(...inactiveClass);
            btnMangkok.classList.add(...activeClass);
            
            // Auto-select first mangkok tab if none selected
            const activeMangkokTables = document.querySelectorAll('.mangkok-table:not(.hidden)');
            if (activeMangkokTables.length === 0) {
                const firstMangkokBtn = document.querySelector('[id^="tabMangkok_"]');
                if (firstMangkokBtn) firstMangkokBtn.click();
            }
        }
    }

    function switchMangkokTab(subCategorySlug) {
        // Hide all mangkok tables
        document.querySelectorAll('.mangkok-table').forEach(t => t.classList.add('hidden'));
        
        // Reset all mangkok buttons
        const mangkokBtns = document.querySelectorAll('[id^="tabMangkok_"]');
        const activeClass = ['bg-blue-50', 'border-blue-200', 'text-blue-600'];
        const inactiveClass = ['bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50'];
        
        mangkokBtns.forEach(b => {
            b.classList.remove(...activeClass);
            b.classList.add(...inactiveClass);
        });

        // Show selected table and activate button
        const selectedTable = document.getElementById(`tableMangkok_${subCategorySlug}`);
        const selectedBtn = document.getElementById(`tabMangkok_${subCategorySlug}`);
        
        if (selectedTable) selectedTable.classList.remove('hidden');
        if (selectedBtn) {
            selectedBtn.classList.remove(...inactiveClass);
            selectedBtn.classList.add(...activeClass);
        }
    }
</script>
@endsection
