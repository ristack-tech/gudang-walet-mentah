@extends('layouts.app')

@section('title', 'Stok Gudang')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card: Stok Gudang Demak -->
                <a href="#" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-indigo-500 transition-all duration-200 overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-200">
                            <!-- Icon: Document/Clipboard/Arrow-Down -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Stok Gudang Demak</h2>
                        <p class="text-gray-500 text-sm">Lihat dan kelola ketersediaan stok barang di gudang Demak.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm font-medium text-indigo-600">Lihat Stok &rarr;</span>
                    </div>
                </a>

                <!-- Card: Stok Gudang Jasa Cuci -->
                <a href="#" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-teal-500 transition-all duration-200 overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-200">
                            <!-- Icon: Document/Wash/Arrow-Down -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">Stok Gudang Jasa Cuci</h2>
                        <p class="text-gray-500 text-sm">Lihat dan kelola ketersediaan stok barang di gudang Jasa Cuci.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm font-medium text-teal-600">Lihat Stok &rarr;</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

