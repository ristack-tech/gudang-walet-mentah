@extends('layouts.app')

@section('title', 'Stok Barang Jadi')

@section('content')
    <div class="bg-gray-50 min-h-[calc(100vh-4rem)]" x-data="{
        showTransferModal: false,
        selectedGlobalId: null,
        selectedGlobalName: ''
    }">
        <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
            
            <div class="sm:flex sm:items-center sm:justify-between mb-8">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl md:text-3xl text-gray-800 font-bold tracking-tight">Stok Barang Jadi</h1>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6 flex space-x-4">
                <a href="{{ route('stok-barang-jadi.index', ['tab' => 'clop']) }}"
                    class="px-6 py-2 font-medium rounded-md shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    {{ $tab === 'clop' ? 'bg-blue-50 border border-blue-200 text-blue-600' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                    Clop
                </a>
                <a href="{{ route('stok-barang-jadi.index', ['tab' => 'global']) }}"
                    class="px-6 py-2 font-medium rounded-md shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    {{ $tab === 'global' ? 'bg-blue-50 border border-blue-200 text-blue-600' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                    Global
                </a>
            </div>

            <!-- Messages/Alerts -->
            @if (session('success'))
                <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table Actions Container -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-4 rounded-t-lg border border-b-0 border-gray-200 shadow-sm gap-4">
                <div class="w-full sm:w-auto flex-1">
                    <form action="{{ route('stok-barang-jadi.index') }}" method="GET" class="flex w-full sm:w-auto">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="relative flex-grow sm:flex-grow-0">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari nama barang..." 
                                class="w-full sm:w-64 pl-4 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-md text-sm font-medium transition duration-200">
                            Cari
                        </button>
                    </form>
                </div>
                
                <div class="w-full sm:w-auto">
                    <button type="button" @click="showTransferModal = true"
                        class="w-full sm:w-auto flex justify-center items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 whitespace-nowrap shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Transfer Global Ke Clop
                    </button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white border border-gray-200 rounded-b-lg overflow-hidden shadow-sm">
                <div class="overflow-x-auto min-h-[400px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">NO</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (Gram)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->batch }} - {{ $item->tanggal }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                        {{ number_format($item->details->sum('berat'), 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center items-center space-x-2">
                                            <button type="button" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded text-xs font-medium border border-blue-200 transition duration-200">
                                                Edit
                                            </button>
                                            <form action="{{ route('stok-barang-jadi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded text-xs font-medium border border-red-200 transition duration-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 border-t border-gray-200">
                                        Tidak ada data stok barang jadi ({{ ucfirst($tab) }}).
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Transfer Modal via Alpine.js -->
            <div x-show="showTransferModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <div x-show="showTransferModal" 
                        x-transition:enter="ease-out duration-300" 
                        x-transition:enter-start="opacity-0" 
                        x-transition:enter-end="opacity-100" 
                        x-transition:leave="ease-in duration-200" 
                        x-transition:leave-start="opacity-100" 
                        x-transition:leave-end="opacity-0" 
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showTransferModal" 
                        x-transition:enter="ease-out duration-300" 
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                        x-transition:leave="ease-in duration-200" 
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                        
                        <div class="bg-white border-b border-gray-200 px-4 py-4 sm:px-6 flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Transfer Global Ke Clop
                            </h3>
                            <button @click="showTransferModal = false; selectedGlobalId = null" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="px-4 py-5 sm:p-6 bg-gray-50">
                            
                            <!-- Selection Table for Transfer -->
                            <div x-show="!selectedGlobalId" class="overflow-x-auto border border-gray-200 rounded-lg bg-white">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase w-16">NO</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nama Barang (Global)</th>
                                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase w-32">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($stokGlobal as $idx => $globalItem)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 text-gray-500">{{ $idx + 1 }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $globalItem->batch }} - (Tersedia: {{ number_format($globalItem->details->sum('berat'), 2, ',', '.') }}gr)</td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" 
                                                    @click="selectedGlobalId = {{ $globalItem->details->first()->id ?? 0 }}; selectedGlobalName = '{{ $globalItem->batch }}'" 
                                                    class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors duration-200">
                                                    Pilih
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Transfer Input Form -->
                            <div x-show="selectedGlobalId" class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm mt-4">
                                
                                <div class="mb-4 bg-blue-50 p-4 rounded-md border border-blue-100 flex justify-between items-center">
                                    <div>
                                        <span class="text-xs text-blue-600 font-bold uppercase tracking-wider block mb-1">Terpilih:</span>
                                        <span class="font-medium text-blue-900 text-lg" x-text="selectedGlobalName"></span>
                                    </div>
                                    <button type="button" @click="selectedGlobalId = null" class="text-sm text-blue-600 hover:text-blue-800 underline">Ganti Barang</button>
                                </div>

                                <form action="{{ route('stok-barang-jadi.transfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="global_id" :value="selectedGlobalId">
                                    
                                    <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Barang (Hasil Clop)</label>
                                            <input type="text" name="nama_barang_clop" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: Clop X">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jumlah (Pcs)</label>
                                            <input type="number" name="jumlah" required min="1"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="10">
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Berat (Gram)</label>
                                            <input type="number" step="0.01" name="berat" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="50.00">
                                        </div>
                                    </div>

                                    <div class="mt-8 flex justify-end space-x-3 border-t border-gray-200 pt-5">
                                        <button type="button" @click="showTransferModal = false; selectedGlobalId = null"
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            Transfer
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
