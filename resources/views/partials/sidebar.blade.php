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
            $isNamaBarang = request()->routeIs('nama_barang.*');

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