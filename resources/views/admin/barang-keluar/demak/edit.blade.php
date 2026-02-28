@extends('layouts.app')

@section('title', 'Edit Data Batch Barang Keluar Demak')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Data Batch Barang Keluar Demak</h1>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi batch BKD-{{ \Carbon\Carbon::parse($data->tanggal_kirim)->format('Ymd') . '-' . strtoupper(substr(md5($data->id), 0, 3)) }}.</p>
            </div>
            <a href="{{ route('barang-keluar.demak.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition duration-200">
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

        <form method="POST" action="{{ route('barang-keluar.demak.update', $data->id) }}" id="editForm">
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
                                Tanggal Kirim <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kirim" id="tanggal_kirim" required
                                value="{{ old('tanggal_kirim', $data->tanggal_kirim ? \Carbon\Carbon::parse($data->tanggal_kirim)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm shadow-sm transition duration-200">
                        </div>

                        <div>
                            <label for="partai_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Partai <span class="text-red-500">*</span>
                            </label>
                            <select id="partai_id" name="partai_id" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition duration-200">
                                <option value="">-- Pilih Partai --</option>
                                @foreach($partais as $partai)
                                    <option value="{{ $partai->id }}" {{ old('partai_id', $batchPartaiId) == $partai->id ? 'selected' : '' }}>
                                        {{ $partai->nama_partai }}
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
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Item
                    </button>
                </div>
                
                <div class="p-6">
                    <div id="itemsContainer" class="space-y-4">
                        @php
                            $formItems = old('items', $data->barangKeluarDemak->toArray());
                        @endphp

                        @foreach($formItems as $index => $item)
                        <div class="item-row border border-gray-200 rounded-xl p-6 bg-white relative transition hover:border-blue-200 hover:shadow-sm" data-index="{{ $index }}">
                            <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium" onclick="removeItem(this)">
                                Hapus Item
                            </div>
                            <h4 class="font-medium text-blue-600 mb-4 item-number">Item {{ $index + 1 }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode LP <span class="text-red-500">*</span></label>
                                    <input type="text" name="items[{{ $index }}][kode_lp]" required value="{{ $item['kode_lp'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Warna</label>
                                    <select name="items[{{ $index }}][warna_id]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Pilih Warna --</option>
                                        @foreach($warnas as $warna)
                                            <option value="{{ $warna->id }}" {{ ($item['warna_id'] ?? '') == $warna->id ? 'selected' : '' }}>
                                                {{ $warna->kode_warna }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gram <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][gram]" required value="{{ $item['gram'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm item-weight" oninput="calculateTotal()">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pcs <span class="text-red-500">*</span></label>
                                    <input type="number" name="items[{{ $index }}][pcs]" required value="{{ $item['pcs'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm item-pcs" oninput="calculateTotal()">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%)</label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][kadar_air]" value="{{ $item['kadar_air'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Timbangan Ulang (Gr)</label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][timbangan_ulang]" value="{{ $item['timbangan_ulang'] ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                    <textarea name="items[{{ $index }}][keterangan]" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">{{ $item['keterangan'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totals Summary Box -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 flex justify-between items-center">
                        <span class="text-sm font-medium text-blue-800">Total Keseluruhan:</span>
                        <div class="text-right text-sm">
                            <span class="font-bold text-blue-900" id="displayTotalGram">0</span> gr &middot; 
                            <span class="font-bold text-blue-900" id="displayTotalPcs">0</span> pcs
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-lg transition duration-200 shadow-sm inline-flex items-center">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<template id="itemTemplate">
    <div class="item-row border border-gray-200 rounded-xl p-6 bg-white relative transition hover:border-blue-200 hover:shadow-sm" data-index="__INDEX__">
        <div class="absolute top-4 right-4 text-red-500 hover:text-red-700 cursor-pointer text-sm font-medium" onclick="removeItem(this)">
            Hapus Item
        </div>
        <h4 class="font-medium text-blue-600 mb-4 item-number">Item __NUMBER__</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode LP <span class="text-red-500">*</span></label>
                <input type="text" name="items[__INDEX__][kode_lp]" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Warna</label>
                <select name="items[__INDEX__][warna_id]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">-- Pilih Warna --</option>
                    @foreach($warnas as $warna)
                        <option value="{{ $warna->id }}">{{ $warna->kode_warna }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gram <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="items[__INDEX__][gram]" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm item-weight" oninput="calculateTotal()">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pcs <span class="text-red-500">*</span></label>
                <input type="number" name="items[__INDEX__][pcs]" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm item-pcs" oninput="calculateTotal()">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kadar Air (%)</label>
                <input type="number" step="0.01" name="items[__INDEX__][kadar_air]" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Timbangan Ulang (Gr)</label>
                <input type="number" step="0.01" name="items[__INDEX__][timbangan_ulang]" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="items[__INDEX__][keterangan]" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
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
        let totalGram = 0;
        let totalPcs = 0;

        document.querySelectorAll('.item-weight').forEach(el => totalGram += parseFloat(el.value) || 0);
        document.querySelectorAll('.item-pcs').forEach(el => totalPcs += parseInt(el.value, 10) || 0);

        document.getElementById('displayTotalGram').textContent = totalGram.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('displayTotalPcs').textContent = totalPcs.toLocaleString('id-ID');
    }

    // Run calculation on load
    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush
@endsection
