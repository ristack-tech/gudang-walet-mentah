@extends('layouts.app')

@section('title', 'Manajemen Barang Keluar')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card: Barang Keluar Demak -->
                <a href="#" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition-all duration-200 overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-200">
                            <!-- Icon: Document/Clipboard -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Barang Keluar Demak</h2>
                        <p class="text-gray-500 text-sm">Kelola proses dan data barang yang keluar menuju fasilitas Demak.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm font-medium text-blue-600">Kelola Data &rarr;</span>
                    </div>
                </a>

                <!-- Card: Barang Keluar Jasa Cuci -->
                <a href="#" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-teal-500 transition-all duration-200 overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-200">
                            <!-- Icon: Beaker/Wash -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">Barang Keluar Jasa Cuci</h2>
                        <p class="text-gray-500 text-sm">Kelola proses dan pencatatan barang yang dikirim ke Jasa Cuci.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm font-medium text-teal-600">Kelola Data &rarr;</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
