@extends('layouts.app')

@section('title', 'Export Barang - Pilih Box')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-24">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Pilih Barang Export (Box)</h1>
                    <p class="mt-1 text-sm text-gray-600">Centang baris untuk memilih box individual yang akan diexport.</p>
                </div>
                <a href="{{ route('export.create.step1') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm shadow-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Progress Indicator --}}
            <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between max-w-xl mx-auto">
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-bold text-xs ring-4 ring-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="mt-2 text-[10px] font-bold uppercase text-green-600 tracking-wider">Info Dasar</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-blue-500 mx-2 -mt-4"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold text-xs ring-4 ring-white shadow-sm">
                            2</div>
                        <span class="mt-2 text-[10px] font-bold uppercase text-blue-600 tracking-wider">Pilih Box</span>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <ul class="list-disc pl-5 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('export.store') }}" method="POST" id="exportForm">
                @csrf
                <input type="hidden" name="tanggal_transfer" value="{{ $tanggalTransfer }}">
                <input type="hidden" name="keterangan" value="{{ $keterangan ?? '' }}">

                {{-- Toolbar: Search --}}
                <div
                    class="bg-white rounded-lg shadow-sm border p-4 mb-6 sticky top-4 z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative w-full md:w-96">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" id="boxSearch" placeholder="Cari box, grade, atau barang..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="selectAllVisible()"
                            class="text-xs font-bold text-blue-600 hover:underline">Pilih Semua Terlihat</button>
                        <div class="text-xs text-gray-500 font-medium">
                            Tersedia: <span class="text-gray-900 font-bold" id="totalTersediaLabel">-</span> Box
                        </div>
                    </div>
                </div>
                
                {{-- Table List --}}
                <div class="bg-white shadow-xl shadow-gray-200/50 border border-gray-100 rounded-2xl">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Box Tersedia</h3>
                        <p class="text-sm text-gray-500 mt-1">Hanya box yang dicentang yang akan diproses masuk ke dalam transaksi ekspor.</p>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table class="min-w-full border-separate border-spacing-0" style="border-spacing: 0 8px;">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50/50">
                                    <th scope="col" class="py-3 px-4 text-center rounded-l-lg border-y border-l border-gray-200">#</th>
                                    <th scope="col" class="py-3 px-4 text-left border-y border-gray-200">Identitas Box</th>
                                    <th scope="col" class="py-3 px-4 text-right border-y border-gray-200">Berat Aktual</th>
                                    <th scope="col" class="py-3 px-4 text-right text-blue-600 border-y border-r border-gray-200 rounded-r-lg bg-blue-50/30">Harga Jual (Rp) <span class="text-red-500">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableStocks as $id => $stock)
                                    @php
                                        $beratPerBox = $stock['available_pcs'] > 0 ? (float)$stock['available_berat'] / $stock['available_pcs'] : (float)$stock['available_berat'];
                                    @endphp
                                    @for($i = 1; $i <= $stock['available_pcs']; $i++)
                                    @php
                                        $boxIndex = $id . '_' . $i;
                                    @endphp
                                    <tr class="bg-white hover:bg-blue-50/20 transition duration-150 ease-in-out group row-item" 
                                        data-id="{{ $boxIndex }}" 
                                        data-berat="{{ $beratPerBox }}" 
                                        data-search="{{ strtolower($stock['nama_barang'] . ' box ' . $i) }}">
                                        
                                        <!-- Checkbox Column -->
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-l border-gray-100 group-hover:border-blue-100 rounded-l-xl text-center relative">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-hover:bg-blue-500 rounded-l-full transition-colors"></div>
                                            <div class="flex items-center justify-center">
                                                <input type="checkbox" name="items[{{ $boxIndex }}][is_checked]" value="1" 
                                                       class="item-checkbox h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer transition duration-150 ease-in-out hover:scale-110">
                                                <input type="hidden" name="items[{{ $boxIndex }}][stok_id]" value="{{ $id }}" disabled class="input-id">
                                                <input type="hidden" name="items[{{ $boxIndex }}][box]" value="{{ $i }}" disabled class="input-box">
                                                <input type="hidden" name="items[{{ $boxIndex }}][berat]" value="{{ $beratPerBox }}" disabled class="input-berat">
                                            </div>
                                        </td>
                                        
                                        <!-- Nama Barang Column -->
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-gray-100 group-hover:border-blue-100">
                                            <div class="flex flex-col">
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold bg-blue-100 text-blue-800 shadow-sm border border-blue-200 uppercase">
                                                        {{ $stock['nama_barang'] }} - BOX {{ $i }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Berat Column -->
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-gray-100 group-hover:border-blue-100 text-right">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                                                {{ number_format($beratPerBox, 2, ',', '.') }}
                                                <span class="ml-1 text-gray-500 text-xs font-normal">g</span>
                                            </span>
                                        </td>

                                        <!-- Harga Column -->
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-r border-gray-100 group-hover:border-blue-100 rounded-r-xl group-hover:bg-blue-50/10 text-right">
                                            <div class="flex items-center justify-end">
                                                <div class="relative rounded-md shadow-sm">
                                                    <input type="number" step="0.01" min="0" name="items[{{ $boxIndex }}][harga]"
                                                        class="input-harga block w-32 sm:text-sm sm:leading-6 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right pr-6 disabled:bg-gray-100 disabled:text-gray-400"
                                                        placeholder="0" disabled>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endfor
                                @endforeach

                                @if(count($availableStocks) == 0)
                                <tr>
                                    <td colspan="4" class="py-12 px-4 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Stok Box Kosong</h3>
                                        <p class="mt-1 text-sm text-gray-500">Tidak ada stok barang jadi (Clop) yang siap diexport.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>

        {{-- Sticky Bottom Action Bar --}}
        <div id="actionBar"
            class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-sm border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] transform translate-y-full transition-transform duration-300 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex gap-8">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Box Dipilih</span>
                            <span class="text-xl font-black text-blue-600" id="selectedCountLabel">0 <span
                                    class="text-xs font-normal text-gray-500 uppercase">Unit</span></span>
                        </div>
                        <div class="flex flex-col border-l border-gray-100 pl-8">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Berat</span>
                            <span class="text-xl font-black text-gray-900" id="totalBeratLabel">0.00 <span
                                    class="text-xs font-normal text-gray-500 uppercase">Gram</span></span>
                        </div>
                    </div>
                    <div class="flex gap-4 w-full sm:w-auto">
                        <button type="button" onclick="resetSelection()"
                            class="px-4 py-2 text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">Batalkan
                            Semua</button>
                        <button type="button" onclick="document.getElementById('exportForm').submit()"
                            class="flex-grow sm:flex-grow-0 px-12 py-3 bg-blue-600 text-white font-black rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 tracking-wide uppercase text-sm">
                            Simpan Transaksi
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const rowItems = document.querySelectorAll('.row-item');
                const boxSearch = document.getElementById('boxSearch');
                const actionBar = document.getElementById('actionBar');
                const selectedCountLabel = document.getElementById('selectedCountLabel');
                const totalBeratLabel = document.getElementById('totalBeratLabel');
                const totalTersediaLabel = document.getElementById('totalTersediaLabel');
                
                // Initialize total items available
                totalTersediaLabel.innerText = rowItems.length;

                // Search logic
                boxSearch.addEventListener('input', function (e) {
                    const query = e.target.value.toLowerCase();
                    rowItems.forEach(row => {
                        const searchText = row.getAttribute('data-search');
                        if(searchText.includes(query)) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    });
                });

                // Row click handles checkbox
                rowItems.forEach(row => {
                    const checkbox = row.querySelector('.item-checkbox');
                    
                    // Click on the row (excluding input) toggles checkbox
                    row.addEventListener('click', function(e) {
                        if(e.target.tagName !== 'INPUT') {
                            checkbox.checked = !checkbox.checked;
                            handleCheckboxChange(row, checkbox);
                        }
                    });

                    // Direct checkbox click
                    checkbox.addEventListener('change', function() {
                        handleCheckboxChange(row, this);
                    });
                });

                function handleCheckboxChange(row, checkbox) {
                    const hargaInput = row.querySelector('.input-harga');
                    const idInput = row.querySelector('.input-id');
                    const boxInput = row.querySelector('.input-box');
                    const beratInput = row.querySelector('.input-berat');

                    if (checkbox.checked) {
                        row.classList.add('bg-blue-50');
                        hargaInput.disabled = false;
                        idInput.disabled = false;
                        boxInput.disabled = false;
                        beratInput.disabled = false;
                    } else {
                        row.classList.remove('bg-blue-50');
                        hargaInput.disabled = true;
                        idInput.disabled = true;
                        boxInput.disabled = true;
                        beratInput.disabled = true;
                    }
                    updateUI();
                }

                function updateUI() {
                    let count = 0;
                    let totalBerat = 0;

                    rowItems.forEach(row => {
                        const checkbox = row.querySelector('.item-checkbox');
                        if (checkbox.checked) {
                            count++;
                            totalBerat += parseFloat(row.getAttribute('data-berat'));
                        }
                    });

                    // Show/Hide Sticky Bar
                    if (count > 0) {
                        actionBar.classList.remove('translate-y-full');
                    } else {
                        actionBar.classList.add('translate-y-full');
                    }

                    selectedCountLabel.innerHTML = `${count} <span class="text-xs font-normal text-gray-500 uppercase">Unit</span>`;
                    totalBeratLabel.innerHTML = `${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(totalBerat)} <span class="text-xs font-normal text-gray-500 uppercase">Gram</span>`;
                }

                window.resetSelection = function () {
                    rowItems.forEach(row => {
                        const checkbox = row.querySelector('.item-checkbox');
                        checkbox.checked = false;
                        handleCheckboxChange(row, checkbox);
                    });
                };

                window.selectAllVisible = function () {
                    rowItems.forEach(row => {
                        if (!row.classList.contains('hidden')) {
                            const checkbox = row.querySelector('.item-checkbox');
                            if (!checkbox.checked) {
                                checkbox.checked = true;
                                handleCheckboxChange(row, checkbox);
                            }
                        }
                    });
                };
            });
        </script>
    @endpush
@endsection