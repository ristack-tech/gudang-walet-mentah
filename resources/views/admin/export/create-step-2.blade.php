@extends('layouts.app')

@section('title', 'Export Barang - Pilih Box')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-24">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Pilih Barang Export</h1>
                    <p class="mt-1 text-sm text-gray-600">Masukkan jumlah box yang ingin diexport per barang.</p>
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
                        <input type="text" id="boxSearch" placeholder="Cari barang..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-xs text-gray-500 font-medium">
                            Tersedia: <span class="text-gray-900 font-bold" id="totalTersediaLabel">-</span> Item
                        </div>
                    </div>
                </div>

                {{-- Table List --}}
                <div class="bg-white shadow-xl shadow-gray-200/50 border border-gray-100 rounded-2xl">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Barang Export</h3>
                        <p class="text-sm text-gray-500 mt-1">Masukkan jumlah box yang ingin diexport pada kolom "Box Export".</p>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table class="min-w-full border-separate border-spacing-0" style="border-spacing: 0 8px;">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50/50">
                                    <th scope="col" class="py-3 px-4 text-center rounded-l-lg border-y border-l border-gray-200 w-12">#</th>
                                    <th scope="col" class="py-3 px-4 text-left border-y border-gray-200">Nama Barang</th>
                                    <th scope="col" class="py-3 px-4 text-center border-y border-gray-200">Jenis Box</th>
                                    <th scope="col" class="py-3 px-4 text-center border-y border-gray-200">Stok Tersedia</th>
                                    <th scope="col" class="py-3 px-4 text-center border-y border-gray-200">Box diExport</th>
                                    <th scope="col" class="py-3 px-4 text-right border-y border-r border-gray-200 rounded-r-lg bg-blue-50/30 text-blue-600">Harga Jual/Box (Rp) <span class="text-red-500">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableStocks as $id => $stock)
                                    @php
                                        $beratPerBox = $stock['available_pcs'] > 0 ? (float)$stock['available_berat'] / $stock['available_pcs'] : (float)$stock['available_berat'];
                                        $jenisBox = '';
                                        // Extract jenis box from nama_barang if possible (e.g., "Lempeng Bersih (ZT) - 475/500")
                                        if (preg_match('/(\d+\/\d+)/', $stock['nama_barang'], $matches)) {
                                            $jenisBox = $matches[1];
                                        }
                                    @endphp
                                    <tr class="bg-white hover:bg-blue-50/20 transition duration-150 ease-in-out group row-item"
                                        data-id="{{ $id }}"
                                        data-berat-per-box="{{ $beratPerBox }}"
                                        data-max-box="{{ $stock['available_pcs'] }}"
                                        data-nama-barang="{{ strtolower($stock['nama_barang']) }}"
                                        data-jenis-box="{{ strtolower($jenisBox) }}">

                                        {{-- Checkbox Column --}}
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-l border-gray-100 group-hover:border-blue-100 rounded-l-xl text-center relative">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-hover:bg-blue-500 rounded-l-full transition-colors"></div>
                                            <div class="flex items-center justify-center">
                                                <input type="checkbox" name="items[{{ $id }}][is_checked]" value="1"
                                                       class="item-checkbox h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer transition duration-150 ease-in-out hover:scale-110"
                                                       id="checkbox_{{ $id }}">
                                                <input type="hidden" name="items[{{ $id }}][stok_id]" value="{{ $id }}" disabled class="input-id">
                                            </div>
                                        </td>

                                        {{-- Nama Barang Column --}}
                                        <td class="px-4 py-4 border-y border-gray-100 group-hover:border-blue-100">
                                            <label for="checkbox_{{ $id }}" class="cursor-pointer">
                                                <div class="flex flex-col">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold bg-blue-100 text-blue-800 shadow-sm border border-blue-200 uppercase">
                                                        {{ $stock['nama_barang'] }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 mt-1">
                                                        Berat per box: <span class="font-medium text-gray-700">{{ number_format($beratPerBox, 2, ',', '.') }}g</span>
                                                    </span>
                                                </div>
                                            </label>
                                        </td>

                                        {{-- Berat per Box Column --}}
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-gray-100 group-hover:border-blue-100 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ number_format($beratPerBox, 2, ',', '.') }}g
                                            </span>
                                        </td>

                                        {{-- Stok Tersedia Column --}}
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-gray-100 group-hover:border-blue-100 text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-md text-sm font-bold bg-green-100 text-green-800 border border-green-200">
                                                {{ $stock['available_pcs'] }} Box
                                            </span>
                                        </td>

                                        {{-- Box diExport Column --}}
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-gray-100 group-hover:border-blue-100 text-center">
                                            <input type="number"
                                                   name="items[{{ $id }}][jumlah_box]"
                                                   id="jumlah_box_{{ $id }}"
                                                   min="0"
                                                   max="{{ $stock['available_pcs'] }}"
                                                   value="0"
                                                   class="input-jumlah box-input w-20 text-center rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium disabled:bg-gray-100 disabled:text-gray-400"
                                                   placeholder="0">
                                            <input type="hidden" name="items[{{ $id }}][berat]" value="{{ $beratPerBox }}" disabled class="input-berat">
                                        </td>

                                        {{-- Harga Column --}}
                                        <td class="px-4 py-4 whitespace-nowrap border-y border-r border-gray-100 group-hover:border-blue-100 rounded-r-xl group-hover:bg-blue-50/10 text-right">
                                            <div class="flex items-center justify-end">
                                                <div class="relative rounded-md shadow-sm">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">Rp</span>
                                                    <input type="number" step="0.01" min="0"
                                                        name="items[{{ $id }}][harga]"
                                                        id="harga_{{ $id }}"
                                                        class="input-harga block w-36 sm:text-sm sm:leading-6 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right pr-3 disabled:bg-gray-100 disabled:text-gray-400"
                                                        placeholder="0" disabled>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if(count($availableStocks) == 0)
                                <tr>
                                    <td colspan="6" class="py-12 px-4 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Stok Kosong</h3>
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
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Item Dipilih</span>
                            <span class="text-xl font-black text-blue-600" id="selectedCountLabel">0 <span
                                    class="text-xs font-normal text-gray-500 uppercase">Item</span></span>
                        </div>
                        <div class="flex flex-col border-l border-gray-100 pl-8">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Box</span>
                            <span class="text-xl font-black text-blue-600" id="totalBoxLabel">0 <span
                                    class="text-xs font-normal text-gray-500 uppercase">Box</span></span>
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
                        <button type="button" onclick="showConfirmModal()"
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

    {{-- Confirmation Modal --}}
    <div id="confirmModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-500">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Konfirmasi Export</h3>
                        <button type="button" onclick="closeConfirmModal()" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-6">
                    {{-- Info Section --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Transfer</span>
                                <p class="text-sm font-semibold text-gray-900" id="modalTanggal">{{ $tanggalTransfer }}</p>
                            </div>
                            @if($keterangan)
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</span>
                                <p class="text-sm font-semibold text-gray-900">{{ $keterangan }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden mb-4">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr class="text-xs font-semibold text-gray-500 uppercase">
                                    <th class="px-4 py-3 text-left">Nama Barang</th>
                                    <th class="px-4 py-3 text-center">Box</th>
                                    <th class="px-4 py-3 text-right">Berat</th>
                                    <th class="px-4 py-3 text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsBody" class="divide-y divide-gray-100">
                                {{-- Filled by JS --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary --}}
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-blue-700">Total Box</span>
                            <span class="text-lg font-black text-blue-700" id="modalTotalBox">0</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm font-bold text-blue-700">Total Berat</span>
                            <span class="text-lg font-black text-blue-700" id="modalTotalBerat">0 g</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeConfirmModal()"
                        class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="submitExport()"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Konfirmasi
                    </button>
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
            const totalBoxLabel = document.getElementById('totalBoxLabel');
            const totalBeratLabel = document.getElementById('totalBeratLabel');
            const totalTersediaLabel = document.getElementById('totalTersediaLabel');

            // Initialize total items available
            totalTersediaLabel.innerText = rowItems.length;

            // Search logic
            boxSearch.addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase();
                rowItems.forEach(row => {
                    const searchText = row.getAttribute('data-nama-barang') + ' ' + row.getAttribute('data-jenis-box');
                    if (searchText.includes(query)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });

            // Row item change handlers
            rowItems.forEach(row => {
                const id = row.getAttribute('data-id');
                const checkbox = row.querySelector('.item-checkbox');
                const jumlahInput = row.querySelector('.input-jumlah');
                const hargaInput = row.querySelector('.input-harga');
                const idInput = row.querySelector('.input-id');
                const beratInput = row.querySelector('.input-berat');

                // Checkbox change
                checkbox.addEventListener('change', function() {
                    const hasValue = parseInt(jumlahInput.value) > 0;
                    if (this.checked && hasValue) {
                        row.classList.add('bg-blue-50');
                        hargaInput.disabled = false;
                        idInput.disabled = false;
                        beratInput.disabled = false;
                    } else {
                        row.classList.remove('bg-blue-50');
                        if (!hasValue) {
                            hargaInput.disabled = true;
                            idInput.disabled = true;
                            beratInput.disabled = true;
                        }
                    }
                    updateUI();
                });

                // Jumlah box change
                jumlahInput.addEventListener('input', function() {
                    const val = parseInt(this.value) || 0;
                    const max = parseInt(row.getAttribute('data-max-box'));

                    if (val > max) {
                        this.value = max;
                    } else if (val < 0) {
                        this.value = 0;
                    }

                    if (val > 0) {
                        checkbox.checked = true;
                        hargaInput.disabled = false;
                        idInput.disabled = false;
                        beratInput.disabled = false;
                        row.classList.add('bg-blue-50');
                    } else {
                        checkbox.checked = false;
                        hargaInput.disabled = true;
                        idInput.disabled = true;
                        beratInput.disabled = true;
                        row.classList.remove('bg-blue-50');
                    }
                    updateUI();
                });
            });

            function updateUI() {
                let count = 0;
                let totalBox = 0;
                let totalBerat = 0;

                rowItems.forEach(row => {
                    const checkbox = row.querySelector('.item-checkbox');
                    const jumlahInput = row.querySelector('.input-jumlah');
                    const beratPerBox = parseFloat(row.getAttribute('data-berat-per-box'));

                    if (checkbox.checked) {
                        count++;
                        const val = parseInt(jumlahInput.value) || 0;
                        totalBox += val;
                        totalBerat += val * beratPerBox;
                    }
                });

                // Show/Hide Sticky Bar
                if (count > 0) {
                    actionBar.classList.remove('translate-y-full');
                } else {
                    actionBar.classList.add('translate-y-full');
                }

                selectedCountLabel.innerHTML = `${count} <span class="text-xs font-normal text-gray-500 uppercase">Item</span>`;
                totalBoxLabel.innerHTML = `${totalBox} <span class="text-xs font-normal text-gray-500 uppercase">Box</span>`;
                totalBeratLabel.innerHTML = `${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(totalBerat)} <span class="text-xs font-normal text-gray-500 uppercase">Gram</span>`;
            }

            window.resetSelection = function () {
                rowItems.forEach(row => {
                    const checkbox = row.querySelector('.item-checkbox');
                    const jumlahInput = row.querySelector('.input-jumlah');
                    const hargaInput = row.querySelector('.input-harga');
                    const idInput = row.querySelector('.input-id');
                    const beratInput = row.querySelector('.input-berat');

                    checkbox.checked = false;
                    jumlahInput.value = 0;
                    row.classList.remove('bg-blue-50');
                    hargaInput.disabled = true;
                    idInput.disabled = true;
                    beratInput.disabled = true;
                });
                updateUI();
            };

            // Modal functions
            window.showConfirmModal = function () {
                const modal = document.getElementById('confirmModal');
                const itemsBody = document.getElementById('modalItemsBody');
                let totalBox = 0;
                let totalBerat = 0;

                itemsBody.innerHTML = '';

                rowItems.forEach(row => {
                    const checkbox = row.querySelector('.item-checkbox');
                    if (!checkbox.checked) return;

                    const namaBarang = row.querySelector('label span:first-child').textContent;
                    const jumlahInput = row.querySelector('.input-jumlah');
                    const hargaInput = row.querySelector('.input-harga');
                    const beratPerBox = parseFloat(row.getAttribute('data-berat-per-box'));

                    const val = parseInt(jumlahInput.value) || 0;
                    const harga = parseFloat(hargaInput.value) || 0;
                    const berat = val * beratPerBox;

                    if (val <= 0) return;

                    totalBox += val;
                    totalBerat += berat;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-3 text-sm text-gray-900">${namaBarang}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-center font-medium">${val}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-right">${new Intl.NumberFormat('id-ID').format(berat)} g</td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-right">Rp ${new Intl.NumberFormat('id-ID').format(harga)}</td>
                    `;
                    itemsBody.appendChild(tr);
                });

                if (totalBox === 0) {
                    alert('Silakan masukkan jumlah box yang ingin diexport.');
                    return;
                }

                document.getElementById('modalTotalBox').textContent = totalBox;
                document.getElementById('modalTotalBerat').textContent = new Intl.NumberFormat('id-ID').format(totalBerat) + ' g';

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeConfirmModal = function () {
                const modal = document.getElementById('confirmModal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            };

            window.submitExport = function () {
                document.getElementById('exportForm').submit();
            };
        });
    </script>
    @endpush
@endsection