@extends('layouts.app')

@section('title', 'Edit Batch Pembelian Supplier')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Batch Pembelian Supplier</h1>
                    <p class="mt-1 text-sm text-gray-600">Perbarui informasi batch
                        {{ str_pad($barangMasukBatch->id, 5, '0', STR_PAD_LEFT) }}.</p>
                </div>
                <a href="{{ route('barang-masuk.pembelian-supplier.index') }}"
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

            <form method="POST" action="{{ route('barang-masuk.pembelian-supplier.update', $barangMasukBatch->id) }}"
                id="editForm">
                @csrf
                @method('PUT')

                <!-- SECTION 1: INFORMASI BATCH -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900">1. Informasi Batch</h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $firstItem = $barangMasukBatch->barangMasukSupplier->first();
                                $currentSupplierId = $firstItem ? $firstItem->supplier_id : '';
                            @endphp
                            <div>
                                <label for="tanggal_terima" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Terima <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_terima" id="tanggal_terima" required
                                    value="{{ old('tanggal_terima', $barangMasukBatch->tanggal_terima ? \Carbon\Carbon::parse($barangMasukBatch->tanggal_terima)->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm">
                            </div>

                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Supplier <span class="text-red-500">*</span>
                                </label>
                                <select id="supplier_id" name="supplier_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white text-sm">
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($supplier as $s)
                                        <option value="{{ $s->id }}" {{ old('supplier_id', $currentSupplierId) == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama }}
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
                        <h2 class="text-base font-semibold text-gray-900">2. Detail Item Barang</h2>
                        <button type="button" onclick="addNewItem()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-medium transition duration-150">
                            + Tambah Item
                        </button>
                    </div>

                    <div class="p-6">
                        <div id="itemsContainer" class="space-y-4">
                            @php
                                $formItems = old('items', $barangMasukBatch->barangMasukSupplier->toArray());
                            @endphp

                            @foreach($formItems as $index => $item)
                                <div class="item-row border border-gray-200 rounded-lg p-4 bg-white relative"
                                    data-index="{{ $index }}">
                                    <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium"
                                        onclick="removeItem(this)">
                                        Hapus
                                    </div>
                                    <h4 class="font-medium text-sm text-gray-900 mb-4 item-number">Item {{ $index + 1 }}</h4>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div class="col-span-1 sm:col-span-2 lg:col-span-1">
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
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Berat (Kg) <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][berat]" required
                                                value="{{ $item['berat'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-weight"
                                                oninput="calculateTotal()">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Pcs <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" name="items[{{ $index }}][pcs]" required
                                                value="{{ $item['pcs'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-pcs"
                                                oninput="calculateTotal()">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Box <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" name="items[{{ $index }}][box]" required
                                                value="{{ $item['box'] ?? 1 }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-box"
                                                oninput="calculateTotal()">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totals Summary Box -->
                        <div
                            class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-800">Total Keseluruhan:</span>
                            <div class="text-right text-sm">
                                <span class="font-bold text-blue-900" id="displayTotalBerat">0</span> kg &middot;
                                <span class="font-bold text-blue-900" id="displayTotalPcs">0</span> pcs &middot;
                                <span class="font-bold text-blue-900" id="displayTotalBox">0</span> box
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
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
                    <label class="block text-xs font-medium text-gray-700 mb-1">Berat (Kg) <span
                            class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="items[__INDEX__][berat]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-weight"
                        oninput="calculateTotal()">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pcs <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="items[__INDEX__][pcs]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-pcs"
                        oninput="calculateTotal()">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Box <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="items[__INDEX__][box]" value="1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm item-box"
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
                let totalBerat = 0;
                let totalPcs = 0;
                let totalBox = 0;

                document.querySelectorAll('.item-weight').forEach(el => totalBerat += parseFloat(el.value) || 0);
                document.querySelectorAll('.item-pcs').forEach(el => totalPcs += parseInt(el.value, 10) || 0);
                document.querySelectorAll('.item-box').forEach(el => totalBox += parseInt(el.value, 10) || 0);

                document.getElementById('displayTotalBerat').textContent = totalBerat.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('displayTotalPcs').textContent = totalPcs.toLocaleString('id-ID');
                document.getElementById('displayTotalBox').textContent = totalBox.toLocaleString('id-ID');
            }

            // Run calculation on load
            document.addEventListener('DOMContentLoaded', calculateTotal);
        </script>
    @endpush
@endsection