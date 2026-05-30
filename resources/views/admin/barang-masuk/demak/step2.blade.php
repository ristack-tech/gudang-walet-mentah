@extends('layouts.app')

@section('title', 'Tambah Data Demak - Step 2')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto">

        <!-- Header Start -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Form Barang Masuk Demak</h1>
                <p class="mt-2 text-sm text-gray-500">Pilih barang yang akan dimasukkan dan isi data aktual.</p>
            </div>
            <a href="{{ route('barang-masuk.demak.create.step1') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 ease-in-out focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>
        <!-- Header End -->

        <!-- Highlight Card / Informasi Batch -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
            <!-- Decorative background shape -->
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                <div>
                    <h3 class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Tanggal Terima</h3>
                    <p class="text-2xl font-bold">{{ \Carbon\Carbon::parse($step1Data['tanggal_terima'])->format('d F Y') }}</p>
                </div>
                <div>
                    <h3 class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Lokasi Asal</h3>
                    <p class="text-2xl font-bold">{{ \App\Models\Lokasi::find($step1Data['transaksi_dari_id'])->nama_lokasi ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Buttons + Search -->
        <div class="bg-white shadow-lg rounded-2xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <!-- Filter Buttons -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-medium text-gray-600">Filter Partai:</span>
                    <a href="{{ route('barang-masuk.demak.create.step2', array_merge(request()->except('partai'), ['partai' => 'all'])) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('partai', 'all') == 'all' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        All
                    </a>
                    @foreach($partaiList as $partai)
                        <a href="{{ route('barang-masuk.demak.create.step2', array_merge(request()->except('partai'), ['partai' => $partai->nama_partai])) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('partai') == $partai->nama_partai ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ ucfirst($partai->nama_partai) }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <div class="flex-1"></div>
                <form method="GET" action="{{ route('barang-masuk.demak.create.step2') }}" class="flex items-center gap-2">
                    <input type="hidden" name="partai" value="{{ request('partai', 'all') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode LP..."
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 w-48">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('barang-masuk.demak.create.step2', ['partai' => request('partai', 'all')]) }}" class="text-gray-500 hover:text-gray-700 text-sm">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Form Items -->
        <div class="bg-white shadow-xl shadow-gray-200/50 border border-gray-100 rounded-2xl">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Daftar Barang</h3>
                <p class="text-sm text-gray-500 mt-1">Centang barang yang akan dimasukkan ke gudang.</p>
            </div>

            <form id="receiveForm" action="{{ route('barang-masuk.demak.store.step2') }}" method="POST">
                @csrf
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full border-separate border-spacing-0" style="border-spacing: 0 8px;">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50/50">
                                <th class="py-3 px-4 text-center rounded-l-lg border-y border-l border-gray-200">#</th>
                                <th class="py-3 px-4 text-left border-y border-gray-200">Kode LP</th>
                                <th class="py-3 px-4 text-left border-y border-gray-200">Partai</th>
                                <th class="py-3 px-4 text-left text-blue-600 border-y border-gray-200 bg-blue-50/30">Nama Barang <span class="text-red-500">*</span></th>
                                <th class="py-3 px-4 text-left text-blue-600 border-y border-gray-200 bg-blue-50/30">Partai Baru <span class="text-red-500">*</span></th>
                                <th class="py-3 px-4 text-right text-blue-600 border-y border-gray-200 bg-blue-50/30">Pcs <span class="text-red-500">*</span></th>
                                <th class="py-3 px-4 text-right text-blue-600 border-y border-gray-200 bg-blue-50/30">Box <span class="text-red-500">*</span></th>
                                <th class="py-3 px-4 text-right text-blue-600 border-y border-r border-gray-200 rounded-r-lg bg-blue-50/30">Gram Aktual <span class="text-red-500">*</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr class="bg-white hover:bg-blue-50/20 transition duration-150 ease-in-out group">
                                <td class="px-4 py-4 border-y border-l border-gray-100 text-center relative">
                                    <input type="checkbox" name="items[{{ $idx }}][is_checked]" value="1" class="h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <input type="hidden" name="items[{{ $idx }}][barang_keluar_demak_id]" value="{{ $item->id }}">
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $item->kode_lp ?? '-' }}</span>
                                        <div class="text-xs text-gray-500">{{ number_format($item->gram, 2, ',', '.') }} gr</div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $item->partai->nama_partai ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100">
                                    <select name="items[{{ $idx }}][barang_id]" class="block w-full min-w-[150px] bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150">
                                        <option value="">Pilih Barang...</option>
                                        @foreach($barangList as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100">
                                    <input type="text" name="items[{{ $idx }}][partai]"
                                        class="block w-full min-w-[140px] bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150"
                                        value="{{ $item->partai->nama_partai ?? '' }}"
                                        placeholder="Ketik partai...">
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100">
                                    <input type="number" name="items[{{ $idx }}][pcs]"
                                        class="block w-full min-w-[80px] bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 text-right"
                                        value="{{ $item->pcs }}">
                                </td>
                                <td class="px-4 py-4 border-y border-gray-100">
                                    <input type="number" name="items[{{ $idx }}][box]"
                                        class="block w-full min-w-[80px] bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 text-right"
                                        value="0" min="0">
                                </td>
                                <td class="px-4 py-4 border-y border-r border-gray-100">
                                    <input type="number" step="0.01" name="items[{{ $idx }}][gram_diterima]"
                                        class="form-input block w-32 sm:text-sm sm:leading-6 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right"
                                        placeholder="0.00">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 px-4 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination & Show Entries - Outside form -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span>Show</span>
                            <select id="perPageSelect" class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="10" {{ $items->perPage() == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $items->perPage() == 20 ? 'selected' : '' }}>20</option>
                                <option value="30" {{ $items->perPage() == 30 ? 'selected' : '' }}>30</option>
                                <option value="40" {{ $items->perPage() == 40 ? 'selected' : '' }}>40</option>
                                <option value="50" {{ $items->perPage() == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span>of {{ $items->total() }} entries</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            Page {{ $items->currentPage() }} of {{ $items->lastPage() }}
                        </div>
                    </div>
                    <!-- Page Numbers -->
                    <div class="flex items-center justify-center gap-1 flex-wrap">
                        @for ($i = 1; $i <= $items->lastPage(); $i++)
                            @if ($i == $items->currentPage())
                                <span class="px-3 py-1 border border-blue-500 rounded text-sm font-medium text-blue-600 bg-blue-50">{{ $i }}</span>
                            @else
                                <a href="javascript:void(0)" data-page="{{ $i }}" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 pagination-link">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>
                </div>

                <!-- Footer Summary/Action -->
                <div class="bg-gray-50 px-6 py-5 rounded-b-2xl border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 sm:mb-0 text-sm text-gray-500">
                        <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Pastikan data input sudah tepat sebelum menyimpan.
                    </div>
                    <button type="button" onclick="openConfirmModal()" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Simpan Penerimaan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Konfirmasi Simpan</h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Yakin simpan data penerimaan ini? Data bisa<strong class="text-blue-600"> diedit</strong> atau <strong class="text-blue-600">dihapus</strong> selama belum di-grading. <br><br>
                                    Periksa kembali angka <span class="font-semibold text-blue-600">Gram Aktual</span> dan <span class="font-semibold text-blue-600">Partai</span> sebelum melanjutkan!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" onclick="submitForm()" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto transition-colors">Ya, Simpan</button>
                    <button type="button" onclick="closeConfirmModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal, Cek Lagi</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openConfirmModal() {
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function submitForm() {
        document.getElementById('receiveForm').submit();
    }

    // Build URL with current params
    function buildPageUrl(page) {
        const params = new URLSearchParams(window.location.search);
        params.set('page', page);
        return window.location.pathname + '?' + params.toString();
    }

    // Pagination - per page
    document.getElementById('perPageSelect').addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('per_page', this.value);
        params.delete('page');
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Update pagination links
    document.querySelectorAll('.pagination-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.dataset.page;
            window.location.href = buildPageUrl(page);
        });
    });
</script>
@endsection
