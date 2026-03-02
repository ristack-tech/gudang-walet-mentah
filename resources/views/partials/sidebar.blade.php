<div class="bg-white text-gray-800 w-64 min-h-screen flex flex-col shadow-lg border-r border-gray-200">
    <div class="p-5 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-bold text-blue-600">
            Gudang Walet Mentah
        </h2>
        <button id="close-sidebar" class="md:hidden p-1 rounded-md hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

        @php
            $isDashboard = request()->routeIs('dashboard');
            $isLokasi = request()->routeIs('lokasi.*');
            $isNamaBarang = request()->routeIs('nama-barang.*');
            $isKodeBahan = request()->routeIs('kode-bahan.*');
            $isPartai = request()->routeIs('partai.*');
            $isSupplier = request()->routeIs('supplier.*');
            $isBarangKeluar = request()->routeIs('barang-keluar.*');
            $isBarangMasuk = request()->routeIs('barang-masuk.*');
            $isStokGrading = request()->routeIs('stok-grading.*');
            $isStokBarangJadi = request()->routeIs('stok-barang-jadi.*');
        @endphp

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isDashboard ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>


        <div class="pt-4">
            <div class="border-t border-gray-200 mb-4"></div>
            <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                Transaksi
            </h3>
            
            <a href="{{ route('barang-keluar.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isBarangKeluar ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-4h2a2 2 0 012 2v2H8V2a2 2 0 012-2z"></path>
                </svg>
                <span class="font-medium">Barang Keluar</span>
            </a>

            <a href="{{ route('barang-masuk.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isBarangMasuk ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-4h2a2 2 0 012 2v2H8V2a2 2 0 012-2z"></path>
                </svg>
                <span class="font-medium">Barang Masuk</span>
            </a>

            <a href="{{ route('stok-grading.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isStokGrading ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="font-medium">Stok Grading</span>
            </a>

            <a href="{{ route('stok-barang-jadi.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isStokBarangJadi ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <span class="font-medium">Stok Barang Jadi</span>
            </a>

        </div>

        <div class="pt-4">
            <div class="border-t border-gray-200 mb-4"></div>
            <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                Master Data
            </h3>
            <a href="{{ route('lokasi.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isLokasi ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Lokasi</span>
            </a>

            <a href="{{ route('nama-barang.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isNamaBarang ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                    </path>
                </svg>
                <span class="font-medium">Nama Barang</span>
            </a>

            <a href="{{ route('kode-bahan.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isKodeBahan ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                <span class="font-medium">Kode Bahan</span>
            </a>

            <a href="{{ route('partai.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isPartai ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Partai</span>
            </a>

            <a href="{{ route('supplier.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
            {{ $isSupplier ? 'bg-blue-50 text-blue-600 font-semibold shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-medium">Supplier</span>
            </a>

        </div>


    </nav>

    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center text-xs text-gray-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>v1.0.0</span>
        </div>
    </div>
</div>

@push('scripts')
    <script> function toggleSubmenu(submenuId, arrowId) {
            const submenu = document.getElementById(submenuId); const
                arrow = document.getElementById(arrowId); submenu.classList.toggle('hidden'); arrow.classList.toggle('rotate-90');
        }
    </script>
@endpush
