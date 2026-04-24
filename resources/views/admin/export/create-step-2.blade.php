@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Export Baru - Langkah 2</h1>
        <p class="text-gray-600 mt-2">Pilih barang-barang Clop yang akan diekspor dalam batch ini.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Ada kesalahan pengisian:</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6">
        <div class="mb-6 bg-blue-50 p-4 rounded-md border border-blue-100 flex gap-6">
            <div>
                <span class="block text-xs font-semibold text-blue-800 uppercase">Tanggal Transfer</span>
                <span class="text-blue-900 font-medium">{{ \Carbon\Carbon::parse($tanggalTransfer)->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-blue-800 uppercase">Keterangan</span>
                <span class="text-blue-900">{{ $keterangan ?: '-' }}</span>
            </div>
        </div>

        <form action="{{ route('export.store') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" name="tanggal_transfer" value="{{ $tanggalTransfer }}">
            <input type="hidden" name="keterangan" value="{{ $keterangan }}">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Item Export</h2>
                <button type="button" id="addItemBtn" class="flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm font-medium transition duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 shadow-sm border border-gray-200 rounded-md">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Barang (Sisa Stok) <span class="text-red-500">*</span></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Box <span class="text-red-500">*</span></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Berat (gr) <span class="text-red-500">*</span></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Pcs</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Harga (Rp)</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsContainer" class="bg-white divide-y divide-gray-200">
                        <!-- Baris Pertama Default -->
                        <tr class="item-row">
                            <td class="px-4 py-3">
                                <select name="items[0][stok_id]" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 stok-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($availableStocks as $id => $stock)
                                        <option value="{{ $id }}" data-berat="{{ $stock['available_berat'] }}" data-pcs="{{ $stock['available_pcs'] }}">
                                            {{ $stock['nama_barang'] }} ({{ $stock['available_berat'] }}g / {{ $stock['available_pcs'] }}pcs)
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="items[0][box]" min="0" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Qty Box">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" step="0.01" name="items[0][berat]" min="0.01" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 berat-input" placeholder="Wajib (gram)">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="items[0][pcs]" min="0" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pcs-input" placeholder="Opsional">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" step="0.01" name="items[0][harga]" min="0" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" class="text-red-500 hover:text-red-700 remove-btn disabled:opacity-50" disabled title="Tidak bisa dihapus jika tinggal satu">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-between">
                <a href="{{ route('export.create.step1') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md text-sm font-medium transition duration-200">
                    &larr; Kembali
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition duration-200">
                    Simpan Keluar Batch
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsContainer = document.getElementById('itemsContainer');
        const addItemBtn = document.getElementById('addItemBtn');
        let itemIndex = 1;

        // Template untuk row baru
        const getRowHTML = (index) => `
            <tr class="item-row">
                <td class="px-4 py-3">
                    <select name="items[${index}][stok_id]" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 stok-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($availableStocks as $id => $stock)
                            <option value="{{ $id }}" data-berat="{{ $stock['available_berat'] }}" data-pcs="{{ $stock['available_pcs'] }}">
                                {{ $stock['nama_barang'] }} ({{ $stock['available_berat'] }}g / {{ $stock['available_pcs'] }}pcs)
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${index}][box]" min="0" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Qty Box">
                </td>
                <td class="px-4 py-3">
                    <input type="number" step="0.01" name="items[${index}][berat]" min="0.01" required class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 berat-input" placeholder="Wajib (gram)">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${index}][pcs]" min="0" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pcs-input" placeholder="Opsional">
                </td>
                <td class="px-4 py-3">
                    <input type="number" step="0.01" name="items[${index}][harga]" min="0" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700 remove-btn">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        `;

        // Update status tombol hapus
        const updateRemoveButtons = () => {
            const rows = itemsContainer.querySelectorAll('.item-row');
            const btns = itemsContainer.querySelectorAll('.remove-btn');
            if (rows.length === 1) {
                btns[0].disabled = true;
            } else {
                btns.forEach(btn => btn.disabled = false);
            }
        };

        // Tambah Row
        addItemBtn.addEventListener('click', () => {
            itemsContainer.insertAdjacentHTML('beforeend', getRowHTML(itemIndex));
            itemIndex++;
            updateRemoveButtons();
        });

        // Hapus Row
        itemsContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-btn');
            if (btn && !btn.disabled) {
                btn.closest('tr').remove();
                updateRemoveButtons();
            }
        });

        // Validasi max stok client side
        itemsContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('stok-select')) {
                const tr = e.target.closest('tr');
                const selectedOption = e.target.options[e.target.selectedIndex];
                const maxBerat = selectedOption.dataset.berat || '';
                const maxPcs = selectedOption.dataset.pcs || '';
                
                const beratInput = tr.querySelector('.berat-input');
                const pcsInput = tr.querySelector('.pcs-input');
                
                if (maxBerat) beratInput.max = maxBerat;
                if (maxPcs) pcsInput.max = maxPcs;
            }
        });
    });
</script>
@endpush
@endsection
