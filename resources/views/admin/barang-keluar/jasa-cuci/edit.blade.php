@extends('layouts.app')

@section('title', 'Edit Data Batch Barang Keluar Jasa Cuci')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Data Batch Barang Keluar Jasa Cuci</h1>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi batch.</p>
            </div>
            <a href="{{ route('barang-keluar.jasa-cuci.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-sm transition duration-200">
                Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="text-red-700 text-sm">{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="text-red-700 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('barang-keluar.jasa-cuci.update', $data->id) }}" id="editForm">
            @csrf
            @method('PUT')
            
            <!-- 1. INFORMASI BATCH -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900">1. Informasi Batch</h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_kirim" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Pemberian Bahan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kirim" id="tanggal_kirim" required
                                value="{{ old('tanggal_kirim', $data->tanggal_kirim ? \Carbon\Carbon::parse($data->tanggal_kirim)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-gray-900 text-sm shadow-sm transition duration-200">
                        </div>

                        <div>
                            <label for="lokasi_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Jasa Cuci <span class="text-red-500">*</span>
                            </label>
                            <select id="lokasi_id" name="lokasi_id" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm shadow-sm transition duration-200">
                                <option value="">-- Pilih Jasa Cuci --</option>
                                @foreach($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $data->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. DETAIL ITEM BARANG -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">2. Detail Item Barang</h2>
                    <button type="button" onclick="addNewItem()"
                        class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Item
                    </button>
                </div>
                
                <div class="p-6">
                    <div id="itemsContainer" class="space-y-4">
                        @php
                            $formItems = old('items', $data->barangKeluarJasaCuci->toArray());
                        @endphp

                        @foreach($formItems as $index => $item)
                        <div class="item-row border border-gray-200 rounded-xl p-6 bg-white relative transition hover:border-teal-200 hover:shadow-sm" data-index="{{ $index }}">
                            <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium" onclick="removeItem(this)">
                                Hapus Item
                            </div>
                            <h4 class="font-medium text-teal-600 mb-4 item-number">Item {{ $index + 1 }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Bahan <span class="text-red-500">*</span></label>
                                    <select name="items[{{ $index }}][kode_bahan_id]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm">
                                        <option value="">-- Pilih Kode Bahan --</option>
                                        @foreach($kodeBahans as $kodeBahan)
                                            <option value="{{ $kodeBahan->id }}" {{ ($item['kode_bahan_id'] ?? '') == $kodeBahan->id ? 'selected' : '' }}>
                                                {{ $kodeBahan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Berat Per Pcs (Gr) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][berat_per_pcs]" required value="{{ $item['berat_per_pcs'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm item-weight" oninput="calculateTotal()">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%)</label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][kadar_air]" value="{{ $item['kadar_air'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Cetak</label>
                                    <textarea name="items[{{ $index }}][keterangan_cetak]" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm">{{ $item['keterangan_cetak'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totals Summary Box -->
                    <div class="mt-6 p-4 bg-teal-50 rounded-lg border border-teal-100 flex justify-between items-center">
                        <span class="text-sm font-medium text-teal-800">Total Berat Keseluruhan:</span>
                        <div class="text-right text-sm">
                            <span class="font-bold text-teal-900" id="displayTotalBerat">0</span> gr
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-8 rounded-lg transition duration-200 shadow-sm inline-flex items-center">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<template id="itemTemplate">
    <div class="item-row border border-gray-200 rounded-xl p-6 bg-white relative transition hover:border-teal-200 hover:shadow-sm" data-index="__INDEX__">
        <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium" onclick="removeItem(this)">
            Hapus Item
        </div>
        <h4 class="font-medium text-teal-600 mb-4 item-number">Item __NUMBER__</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Bahan <span class="text-red-500">*</span></label>
                <select name="items[__INDEX__][kode_bahan_id]" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm">
                    <option value="">-- Pilih Kode Bahan --</option>
                    @foreach($kodeBahans as $kodeBahan)
                        <option value="{{ $kodeBahan->id }}">{{ $kodeBahan->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Berat Per Pcs (Gr) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="items[__INDEX__][berat_per_pcs]" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm item-weight" oninput="calculateTotal()">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%)</label>
                <input type="number" step="0.01" name="items[__INDEX__][kadar_air]" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Cetak</label>
                <textarea name="items[__INDEX__][keterangan_cetak]" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm"></textarea>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    let itemIndexCounter = {{ count($formItems ?? []) }};

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

        document.querySelectorAll('.item-weight').forEach(el => totalBerat += parseFloat(el.value) || 0);

        document.getElementById('displayTotalBerat').textContent = totalBerat.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Run calculation on load
    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush
@endsection
