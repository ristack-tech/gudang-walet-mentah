@extends('layouts.app')

@section('title', 'Edit Batch Jasa Cuci')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Batch Jasa Cuci</h1>
                    <p class="mt-1 text-sm text-gray-600">Perbarui informasi batch
                        {{ str_pad($barangMasukBatch->id, 5, '0', STR_PAD_LEFT) }}.</p>
                </div>
                <a href="{{ route('barang-masuk.jasa-cuci.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
                    Kembali
                </a>
            </div>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="text-red-700 text-sm">{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="text-red-700 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('barang-masuk.jasa-cuci.update', $barangMasukBatch->id) }}" id="editForm">
                @csrf
                @method('PUT')

                <!-- SECTION 1: INFORMASI TRANSAKSI -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900">1. Informasi Transaksi Batch</h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_terima" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Terima <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_terima" id="tanggal_terima" required
                                    value="{{ old('tanggal_terima', $barangMasukBatch->tanggal_terima) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm">
                            </div>

                            <div>
                                <label for="lokasi_jasa_cuci_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Lokasi Jasa Cuci <span class="text-red-500">*</span>
                                </label>
                                <select id="lokasi_jasa_cuci_id" name="lokasi_jasa_cuci_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white text-sm">
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach($lokasiList as $l)
                                        <option value="{{ $l->id }}" {{ old('lokasi_jasa_cuci_id', $barangMasukBatch->barangMasukJasaCuci->first()->lokasi_jasa_cuci_id ?? '') == $l->id ? 'selected' : '' }}>
                                            {{ $l->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DETAIL ITEM -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-base font-semibold text-gray-900">2. Detail Item Barang Masuk</h2>
                        <button type="button" onclick="addNewItem()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-medium transition duration-150">
                            + Tambah Item
                        </button>
                    </div>

                    <div class="p-6">
                        <div id="itemsContainer" class="space-y-4">
                            @php
                                $formItems = old('items', $barangMasukBatch->barangMasukJasaCuci->toArray());
                            @endphp

                            @foreach($formItems as $index => $item)
                                <div class="item-row border border-gray-200 rounded-lg p-4 bg-white relative"
                                    data-index="{{ $index }}">
                                    <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium"
                                        onclick="removeItem(this)">
                                        Hapus
                                    </div>
                                    <h4 class="font-medium text-sm text-gray-900 mb-4 item-number">Item {{ $index + 1 }}</h4>

                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Barang <span
                                                    class="text-red-500">*</span></label>
                                            <select name="items[{{ $index }}][barang_id]" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                                                <option value="">Pilih</option>
                                                @foreach($barang as $b)
                                                    <option value="{{ $b->id }}" {{ ($item['barang_id'] ?? '') == $b->id ? 'selected' : '' }}>
                                                        {{ $b->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kode Bahan <span
                                                    class="text-red-500">*</span></label>
                                            <select name="items[{{ $index }}][kode_bahan_id]" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                                                <option value="">Pilih</option>
                                                @foreach($kodeBahan as $kb)
                                                    <option value="{{ $kb->id }}" {{ ($item['kode_bahan_id'] ?? '') == $kb->id ? 'selected' : '' }}>
                                                        {{ $kb->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Berat/Pcs (gr) <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][berat_per_pcs]" required
                                                value="{{ $item['berat_per_pcs'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-weight"
                                                oninput="calculateTotal()">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Susut (%) <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][persentase_susut]"
                                                required value="{{ $item['persentase_susut'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-susut"
                                                oninput="calculateTotal()">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kdr Air (%) <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][kadar_air]" required
                                                value="{{ $item['kadar_air'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-ka"
                                                oninput="calculateTotal()">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totals Summary Box -->
                        <div
                            class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-800">Rata-rata Keseluruhan:</span>
                            <div class="text-right text-sm">
                                <span class="font-bold text-blue-900" id="displayAvgWeight">0</span> gr &middot;
                                <span class="font-bold text-blue-900" id="displayAvgSusut">0</span> % &middot;
                                <span class="font-bold text-blue-900" id="displayAvgKA">0</span> %
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition duration-200 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Simpan Perubahan Batch
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <template id="itemTemplate">
        <div class="item-row border border-gray-200 rounded-lg p-4 bg-white relative" data-index="__INDEX__">
            <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium"
                onclick="removeItem(this)">
                Hapus
            </div>
            <h4 class="font-medium text-sm text-gray-900 mb-4 item-number">Item __NUMBER__</h4>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Barang <span
                            class="text-red-500">*</span></label>
                    <select name="items[__INDEX__][barang_id]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="">Pilih</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode Bahan <span
                            class="text-red-500">*</span></label>
                    <select name="items[__INDEX__][kode_bahan_id]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="">Pilih</option>
                        @foreach($kodeBahan as $kb)
                            <option value="{{ $kb->id }}">{{ $kb->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Berat/Pcs (gr) <span
                            class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="items[__INDEX__][berat_per_pcs]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-weight"
                        oninput="calculateTotal()">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Susut (%) <span
                            class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="items[__INDEX__][persentase_susut]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-susut"
                        oninput="calculateTotal()">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kdr Air (%) <span
                            class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="items[__INDEX__][kadar_air]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-ka"
                        oninput="calculateTotal()">
                </div>
            </div>
        </div>
    </template>

    @push('scripts')
        <script>
            let itemIndexCounter = {{ count($formItems) }};

            function addNewItem() {
                const container = document.getElementById('itemsContainer');
                let template = document.getElementById('itemTemplate').innerHTML;

                template = template.replace(/__INDEX__/g, itemIndexCounter);
                template = template.replace(/__NUMBER__/g, itemIndexCounter + 1);

                container.insertAdjacentHTML('beforeend', template);
                itemIndexCounter++;
                reorderItems();
            }

            function removeItem(button) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length <= 1) {
                    alert('Minimal harus ada 1 item dalam batch.');
                    return;
                }
                button.closest('.item-row').remove();
                reorderItems();
            }

            function reorderItems() {
                const rows = document.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    row.querySelector('.item-number').textContent = `Item ${index + 1}`;
                });
                calculateTotal();
            }

            function calculateTotal() {
                let totalWeight = 0;
                let totalSusut = 0;
                let totalKA = 0;
                const rows = document.querySelectorAll('.item-row');
                const count = rows.length;

                rows.forEach(row => {
                    totalWeight += parseFloat(row.querySelector('.item-weight').value) || 0;
                    totalSusut += parseFloat(row.querySelector('.item-susut').value) || 0;
                    totalKA += parseFloat(row.querySelector('.item-ka').value) || 0;
                });

                const avgWeight = count > 0 ? (totalWeight / count) : 0;
                const avgSusut = count > 0 ? (totalSusut / count) : 0;
                const avgKA = count > 0 ? (totalKA / count) : 0;

                document.getElementById('displayAvgWeight').textContent = avgWeight.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('displayAvgSusut').textContent = avgSusut.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('displayAvgKA').textContent = avgKA.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Run calculation on load
            document.addEventListener('DOMContentLoaded', calculateTotal);
        </script>
    @endpush
@endsection