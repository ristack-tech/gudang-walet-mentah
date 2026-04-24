@extends('layouts.app')

@section('title', 'Export Barang - Pilih Box')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-24">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Pilih Barang Export</h1>
                    <p class="mt-1 text-sm text-gray-600">Klik pada kartu untuk memilih box yang akan diexport.</p>
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
                            Tersedia: <span class="text-gray-900 font-bold">{{ count($availableStocks) }}</span> Box
                        </div>
                    </div>
                </div>
                // nah disini kan dia gabisa input box karena udah otomatis per 1 box
                {{-- Grid Cards --}}
                <div id="catalogGrid"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                    @foreach($availableStocks as $id => $stock)
                        <div class="box-card-small relative flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md cursor-pointer group"
                            data-id="{{ $id }}" data-name="{{ $stock['nama_barang'] }}"
                            data-berat="{{ $stock['available_berat'] }}" data-search="{{ strtolower($stock['nama_barang']) }}">

                            {{-- Checkmark Overlay --}}
                            <div
                                class="selected-overlay absolute -top-2 -right-2 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center opacity-0 transform scale-50 transition-all z-20 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <div class="p-4 flex-grow">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                    {{ $stock['available_berat'] }}g</div>
                                <h3
                                    class="text-xs font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition-colors uppercase">
                                    {{ $stock['nama_barang'] }}</h3>
                            </div>

                            {{-- Price Input Field (Initially Hidden) --}}
                            <div class="price-field hidden border-t border-blue-100 bg-blue-50 p-2 rounded-b-xl px-3 pb-3">
                                <label class="block text-[9px] font-black text-blue-400 uppercase mb-1">Harga Jual (Rp)</label>
                                <div class="relative">
                                    <input type="number" name="items[{{ $id }}][harga]" step="0.01" min="0" placeholder="0"
                                        disabled onclick="event.stopPropagation()"
                                        class="input-harga block w-full px-2 py-1 text-xs border-blue-200 rounded focus:ring-1 focus:ring-blue-500 font-mono text-right bg-white">
                                    <input type="hidden" name="items[{{ $id }}][stok_id]" value="{{ $id }}" disabled
                                        class="input-id">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($availableStocks) == 0)
                    <div class="py-24 text-center bg-white rounded-2xl border-2 border-dashed border-gray-100">
                        <p class="text-gray-400 font-bold uppercase tracking-widest">Stok Box Clop Kosong</p>
                    </div>
                @endif
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
                        <button type="submit" form="exportForm"
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

    <style>
        .box-card-small.selected {
            border-color: #3b82f6;
            border-width: 2px;
        }

        .box-card-small.selected .selected-overlay {
            opacity: 1;
            transform: scale(1);
        }

        .box-card-small.selected .price-field {
            display: block;
        }

        .box-card-small.selected h3 {
            color: #2563eb;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const boxCards = document.querySelectorAll('.box-card-small');
                const boxSearch = document.getElementById('boxSearch');
                const actionBar = document.getElementById('actionBar');
                const selectedCountLabel = document.getElementById('selectedCountLabel');
                const totalBeratLabel = document.getElementById('totalBeratLabel');

                let selectedItems = new Map();

                // Search logic
                boxSearch.addEventListener('input', function (e) {
                    const query = e.target.value.toLowerCase();
                    boxCards.forEach(card => {
                        const searchText = card.getAttribute('data-search');
                        card.style.display = searchText.includes(query) ? 'flex' : 'none';
                    });
                });

                // Click selection
                boxCards.forEach(card => {
                    card.addEventListener('click', function () {
                        toggleCard(this);
                    });
                });

                function toggleCard(card) {
                    const id = card.getAttribute('data-id');
                    const name = card.getAttribute('data-name');
                    const berat = parseFloat(card.getAttribute('data-berat'));
                    const hargaInput = card.querySelector('.input-harga');
                    const idInput = card.querySelector('.input-id');

                    if (selectedItems.has(id)) {
                        // Deselect
                        selectedItems.delete(id);
                        card.classList.remove('selected');
                        hargaInput.disabled = true;
                        idInput.disabled = true;
                    } else {
                        // Select
                        selectedItems.set(id, { id, name, berat });
                        card.classList.add('selected');
                        hargaInput.disabled = false;
                        idInput.disabled = false;
                    }

                    updateUI();
                }

                function updateUI() {
                    const count = selectedItems.size;

                    // Show/Hide Sticky Bar
                    if (count > 0) {
                        actionBar.classList.remove('translate-y-full');
                    } else {
                        actionBar.classList.add('translate-y-full');
                    }

                    // Totals
                    let totalBerat = 0;
                    selectedItems.forEach(item => totalBerat += item.berat);

                    selectedCountLabel.innerHTML = `${count} <span class="text-xs font-normal text-gray-500 uppercase">Unit</span>`;
                    totalBeratLabel.innerHTML = `${new Intl.NumberFormat('id-ID').format(totalBerat)} <span class="text-xs font-normal text-gray-500 uppercase">Gram</span>`;
                }

                window.resetSelection = function () {
                    boxCards.forEach(card => {
                        const id = card.getAttribute('data-id');
                        const hargaInput = card.querySelector('.input-harga');
                        const idInput = card.querySelector('.input-id');

                        card.classList.remove('selected');
                        hargaInput.disabled = true;
                        hargaInput.value = '';
                        idInput.disabled = true;
                    });
                    selectedItems.clear();
                    updateUI();
                };

                window.selectAllVisible = function () {
                    boxCards.forEach(card => {
                        if (card.style.display !== 'none' && !card.classList.contains('selected')) {
                            toggleCard(card);
                        }
                    });
                };
            });
        </script>
    @endpush
@endsection