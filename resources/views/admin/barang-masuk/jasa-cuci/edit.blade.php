@extends('layouts.app')

@section('title', 'Edit Batch Jasa Cuci')

@section('content')
<div class="bg-gray-50 min-h-screen pb-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Edit Batch Jasa Cuci</h1>
                <p class="text-sm text-gray-500 mt-1">Batch ID: {{ str_pad($barangMasukBatch->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('barang-masuk.jasa-cuci.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition duration-200">
                &laquo; Batal & Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="text-red-700">{{ session('error') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('barang-masuk.jasa-cuci.update', $barangMasukBatch->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <!-- Section 1: Informasi Transaksi -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">1</span>
                        Informasi Transaksi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_terima" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terima <span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_terima" name="tanggal_terima"
                                value="{{ old('tanggal_terima', $barangMasukBatch->tanggal_terima) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('tanggal_terima') border-red-500 @enderror"
                                required>
                            @error('tanggal_terima')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="lokasi_jasa_cuci_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Jasa Cuci <span class="text-red-500">*</span></label>
                            <select id="lokasi_jasa_cuci_id" name="lokasi_jasa_cuci_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('lokasi_jasa_cuci_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($lokasiList as $l)
                                    <option value="{{ $l->id }}" {{ old('lokasi_jasa_cuci_id', $barangMasukBatch->barangMasukJasaCuci->first()->lokasi_jasa_cuci_id ?? '') == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi_jasa_cuci_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Daftar Item -->
                <div class="p-6 md:p-8 bg-gray-50">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">2</span>
                            Daftar Item Barang
                        </h2>
                        <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4">
                        @php
                            $items = old('items', $barangMasukBatch->barangMasukJasaCuci->toArray());
                        @endphp

                        @foreach($items as $index => $item)
                        <div class="item-row bg-white border border-gray-200 rounded-lg p-5 shadow-sm relative group transition-all duration-200 hover:border-blue-300" data-index="{{ $index }}">
                            @if(count($items) > 1)
                            <button type="button" class="remove-item-btn absolute -top-3 -right-3 bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-1.5 shadow-sm transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Barang <span class="text-red-500">*</span></label>
                                    <select name="items[{{ $index }}][barang_id]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach($barang as $b)
                                            <option value="{{ $b->id }}" {{ ($item['barang_id'] ?? '') == $b->id ? 'selected' : '' }}>{{ $b->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Kode Bahan <span class="text-red-500">*</span></label>
                                    <select name="items[{{ $index }}][kode_bahan_id]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach($kodeBahan as $kb)
                                            <option value="{{ $kb->id }}" {{ ($item['kode_bahan_id'] ?? '') == $kb->id ? 'selected' : '' }}>{{ $kb->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Berat/Pcs (gr) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][berat_per_pcs]" value="{{ $item['berat_per_pcs'] ?? '' }}" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Susut (%) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][persentase_susut]" value="{{ $item['persentase_susut'] ?? '' }}" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Kdr Air (%) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][kadar_air]" value="{{ $item['kadar_air'] ?? '' }}" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($errors->has('items') || $errors->has('items.*'))
                        <div class="mt-4 p-3 bg-red-50 text-red-600 text-sm rounded-md border border-red-100">
                            Mohon periksa kembali isian item Anda. Pastikan semua field wajib terisi dengan format yang benar.
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end items-center sticky bottom-0 z-20 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition duration-200 shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template for new item row -->
<template id="itemTemplate">
    <div class="item-row bg-white border border-gray-200 rounded-lg p-5 shadow-sm relative group transition-all duration-200 hover:border-blue-300" data-index="__INDEX__">
        <button type="button" class="remove-item-btn absolute -top-3 -right-3 bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-1.5 shadow-sm transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100 z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Barang <span class="text-red-500">*</span></label>
                <select name="items[__INDEX__][barang_id]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih --</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Kode Bahan <span class="text-red-500">*</span></label>
                <select name="items[__INDEX__][kode_bahan_id]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih --</option>
                    @foreach($kodeBahan as $kb)
                        <option value="{{ $kb->id }}">{{ $kb->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Berat/Pcs (gr) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="items[__INDEX__][berat_per_pcs]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Susut (%) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="items[__INDEX__][persentase_susut]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Kdr Air (%) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="items[__INDEX__][kadar_air]" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('itemsContainer');
        const addButton = document.getElementById('addItemBtn');
        const template = document.getElementById('itemTemplate');
        let itemIndex = {{ count($items) }};

        // Add Item
        addButton.addEventListener('click', function() {
            const html = template.innerHTML.replace(/__INDEX__/g, itemIndex);

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const newRow = tempDiv.firstElementChild;

            container.appendChild(newRow);
            itemIndex++;
            updateRemoveButtons();
        });

        // Remove Item
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item-btn')) {
                const rows = container.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                    updateRemoveButtons();
                } else {
                    alert('Minimal harus ada satu item dalam batch.');
                }
            }
        });

        // Toggle Remove Buttons Visibility
        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.item-row');
            const btns = container.querySelectorAll('.remove-item-btn');

            if (rows.length <= 1) {
                btns.forEach(btn => btn.style.display = 'none');
            } else {
                btns.forEach(btn => btn.style.display = 'flex');
            }
        }

        // Initial setup
        updateRemoveButtons();
    });
</script>
@endsection
