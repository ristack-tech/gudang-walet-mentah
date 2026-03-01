@extends('layouts.app')

@section('title', 'Tambah Grading - Step 2')

@section('content')
<div class="bg-white min-h-screen">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Grading - Step 2</h1>
                <p class="mt-1 text-sm text-gray-600">Lengkapi detail grading untuk Batch: <span class="font-bold text-blue-600">{{ $batchId }}</span></p>
            </div>
            <a href="{{ route('stok-grading.create.step1') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
                Kembali
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8 bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between max-w-xl mx-auto">
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-semibold text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-green-600">Pilih Stok</span>
                </div>
                <div class="flex-1 h-0.5 bg-blue-200 mx-2 sm:mx-4 -mt-6"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold text-sm shadow-sm">
                        2</div>
                    <span class="mt-2 text-xs sm:text-sm font-medium text-blue-600">Input Grading</span>
                </div>
            </div>
        </div>

        <div class="mb-6 flex space-x-4">
            <button type="button" id="tabLempeng" onclick="switchTab('lempeng')" class="px-6 py-2 bg-blue-50 border border-blue-200 text-blue-600 font-medium rounded-md shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Lempeng
            </button>
            <button type="button" id="tabMangkok" onclick="switchTab('mangkok')" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 shadow-sm transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Mangkok
            </button>
        </div>

        <form method="POST" action="{{ route('stok-grading.store') }}" id="mainForm">
            @csrf
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6">
                    <!-- Invisible Table Structure for Layout -->
                    <table class="w-full" id="tableLempeng">
                        <thead>
                            <tr>
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider w-16 px-4 pb-4">No</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/4 pb-4">Pilih Grade</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Berat</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Box</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradesLempeng as $index => $grade)
                            <tr>
                                <td class="whitespace-nowrap text-sm text-gray-800 px-4 py-3">{{ $index + 1 }}.</td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/4">
                                    <div class="w-full px-4 py-2 border border-gray-300 rounded-md text-center text-gray-700 font-medium bg-gray-50">
                                        {{ $grade }}
                                    </div>
                                    <input type="hidden" name="grades[{{ $index }}][name]" value="{{ $grade }}">
                                    <input type="hidden" name="grades[{{ $index }}][type]" value="lempeng">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][berat]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][box]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="w-full hidden" id="tableMangkok">
                        <thead>
                            <tr>
                                <th class="text-left text-sm font-medium text-gray-600 tracking-wider w-16 px-4 pb-4">No</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/4 pb-4">Pilih Grade</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Berat</th>
                                <th class="text-center text-sm font-medium text-gray-600 tracking-wider w-1/3 pb-4">Box</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradesMangkok as $idx => $grade)
                            @php $index = count($gradesLempeng) + $idx; @endphp
                            <tr>
                                <td class="whitespace-nowrap text-sm text-gray-800 px-4 py-3">{{ $idx + 1 }}.</td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/4">
                                    <div class="w-full px-4 py-2 border border-gray-300 rounded-md text-center text-gray-700 font-medium bg-gray-50">
                                        {{ $grade }}
                                    </div>
                                    <input type="hidden" name="grades[{{ $index }}][name]" value="{{ $grade }}">
                                    <input type="hidden" name="grades[{{ $index }}][type]" value="mangkok">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][berat]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0.00">
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 w-1/3">
                                    <input type="text" name="grades[{{ $index }}][box]" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" placeholder="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-700 shadow-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Grading
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function switchTab(tab) {
        const btnLempeng = document.getElementById('tabLempeng');
        const btnMangkok = document.getElementById('tabMangkok');
        const tableLempeng = document.getElementById('tableLempeng');
        const tableMangkok = document.getElementById('tableMangkok');

        // Styling for active state
        const activeClass = ['bg-blue-50', 'border-blue-200', 'text-blue-600'];
        const inactiveClass = ['bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50'];

        if (tab === 'lempeng') {
            tableLempeng.classList.remove('hidden');
            tableMangkok.classList.add('hidden');
            
            btnLempeng.classList.remove(...inactiveClass);
            btnLempeng.classList.add(...activeClass);
            
            btnMangkok.classList.remove(...activeClass);
            btnMangkok.classList.add(...inactiveClass);
        } else {
            tableLempeng.classList.add('hidden');
            tableMangkok.classList.remove('hidden');
            
            btnMangkok.classList.remove(...inactiveClass);
            btnMangkok.classList.add(...activeClass);
            
            btnLempeng.classList.remove(...activeClass);
            btnLempeng.classList.add(...inactiveClass);
        }
    }
</script>
@endsection
