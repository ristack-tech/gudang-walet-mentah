@extends('layouts.app')

@section('content')
<!-- Add DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
<style>
    /* Custom styling for dataTables to match Tailwind better */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
        border-radius: 0.375rem !important; /* Tailwind rounded-md */
        font-weight: 500;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem !important;
        margin: 0 0.25rem;
    }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border-color: #d1d5db;
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
    }
    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        outline: none;
    }
    /* Hide scrollbar for tabs on mobile but keep functionality */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard Overview</h2>
            <p class="text-gray-500 mt-1 text-sm">Ringkasan stok grading, bahan jadi, dan barang siap export.</p>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stok Grading -->
        <div class="group bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden" onclick="window.location.href='{{ route('stok-grading.index') }}'">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3.5 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Stok Grading</h3>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($data['totals']['grading_gram'], 2) }}</span>
                            <span class="text-sm font-medium text-gray-500">Gram</span>
                        </div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>

        <!-- Stok Bahan Jadi -->
        <div class="group bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden" onclick="window.location.href='{{ route('stok-barang-jadi.index') }}'">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Bahan Jadi</h3>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($data['totals']['bahan_jadi_gram'], 2) }}</span>
                            <span class="text-sm font-medium text-gray-500">Gram</span>
                        </div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>

        <!-- Stok Siap Export -->
        <div class="group bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden" onclick="window.location.href='{{ route('export.index') }}'">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3.5 rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Siap Export</h3>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($data['totals']['siap_export_box'], 0) }}</span>
                            <span class="text-sm font-medium text-gray-500">Box</span>
                        </div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-purple-500 transform group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-2 mb-6">
            <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Grafik Penambahan Stok (7 Hari Terakhir)</h3>
        </div>
        <div class="relative h-[400px] w-full">
            <canvas id="dashboardChart"></canvas>
        </div>
    </div>

    <!-- Tables Area with Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 overflow-x-auto scrollbar-hide bg-gray-50/50">
            <ul class="flex flex-nowrap whitespace-nowrap text-sm font-medium text-center px-4 pt-4 gap-2" id="dashboard-tabs" role="tablist">
                <li role="presentation">
                    <button class="inline-flex items-center gap-2 px-6 py-3 border-b-2 rounded-t-xl transition-all duration-200 active-tab text-blue-600 border-blue-600 bg-white" id="grading-tab" type="button" role="tab" onclick="switchTab('grading')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Stok Grading Terbaru
                    </button>
                </li>
                <li role="presentation">
                    <button class="inline-flex items-center gap-2 px-6 py-3 border-b-2 border-transparent rounded-t-xl transition-all duration-200 inactive-tab text-gray-500 hover:text-gray-700 hover:bg-gray-50" id="bahan-jadi-tab" type="button" role="tab" onclick="switchTab('bahan-jadi')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Bahan Jadi Terbaru
                    </button>
                </li>
                <li role="presentation">
                    <button class="inline-flex items-center gap-2 px-6 py-3 border-b-2 border-transparent rounded-t-xl transition-all duration-200 inactive-tab text-gray-500 hover:text-gray-700 hover:bg-gray-50" id="export-tab" type="button" role="tab" onclick="switchTab('export')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        Siap Export Terbaru
                    </button>
                </li>
            </ul>
        </div>

        <div id="tab-contents" class="p-6">
            <!-- Stok Grading Content -->
            <div class="tab-pane block" id="grading-content" role="tabpanel">
                <table class="w-full text-sm text-left text-gray-500 datatable" id="grading-table">
                    <thead class="text-xs text-gray-600 uppercase bg-gray-100 rounded-lg">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-lg">Tanggal</th>
                            <th class="px-6 py-4">No Kartu</th>
                            <th class="px-6 py-4">Nama Barang</th>
                            <th class="px-6 py-4">Partai</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Grade</th>
                            <th class="px-6 py-4 text-center">Pcs</th>
                            <th class="px-6 py-4 text-center">Box</th>
                            <th class="px-6 py-4 text-right rounded-tr-lg">Berat (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['tables']['gradings'] as $item)
                        <tr class="bg-white border-b hover:bg-blue-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->stokGrading ? $item->stokGrading->tanggal : '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->stokGrading ? $item->stokGrading->no_kartu : '-' }}</td>
                            <td class="px-6 py-4">{{ $item->stokGrading && $item->stokGrading->namaBarang ? $item->stokGrading->namaBarang->nama_barang : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-medium">{{ $item->stokGrading ? $item->stokGrading->partai : '-' }}</span>
                            </td>
                            <td class="px-6 py-4">{{ ucwords(str_replace('_', ' ', $item->type)) }}</td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $item->grade }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->pcs }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->box }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 text-right">{{ number_format($item->berat, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bahan Jadi Content -->
            <div class="tab-pane hidden" id="bahan-jadi-content" role="tabpanel">
                <table class="w-full text-sm text-left text-gray-500 datatable" style="width:100%" id="bahan-jadi-table">
                    <thead class="text-xs text-gray-600 uppercase bg-gray-100 rounded-lg">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-lg">Tanggal</th>
                            <th class="px-6 py-4">Batch</th>
                            <th class="px-6 py-4">Nama Barang</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Jumlah Box</th>
                            <th class="px-6 py-4 text-right rounded-tr-lg">Berat (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['tables']['bahan_jadi'] as $item)
                        <tr class="bg-white border-b hover:bg-emerald-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->stokBarangJadi ? $item->stokBarangJadi->tanggal : '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->stokBarangJadi ? $item->stokBarangJadi->batch : '-' }}</td>
                            <td class="px-6 py-4 font-medium">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->stokBarangJadi ? $item->stokBarangJadi->keterangan : '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->jumlah_box }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 text-right">{{ number_format($item->berat, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Siap Export Content -->
            <div class="tab-pane hidden" id="export-content" role="tabpanel">
                <table class="w-full text-sm text-left text-gray-500 datatable" style="width:100%" id="export-table">
                    <thead class="text-xs text-gray-600 uppercase bg-gray-100 rounded-lg">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-lg">Tanggal</th>
                            <th class="px-6 py-4">Batch</th>
                            <th class="px-6 py-4">Nama Barang</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Jumlah Box</th>
                            <th class="px-6 py-4 text-right rounded-tr-lg">Berat (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['tables']['clop'] as $item)
                        <tr class="bg-white border-b hover:bg-purple-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->stokBarangJadi ? $item->stokBarangJadi->tanggal : '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->stokBarangJadi ? $item->stokBarangJadi->batch : '-' }}</td>
                            <td class="px-6 py-4 font-medium">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->stokBarangJadi ? $item->stokBarangJadi->keterangan : '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-md font-medium">{{ $item->jumlah_box }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 text-right">{{ number_format($item->berat, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let dTables = {};

    function switchTab(tabId) {
        // Tailwind class constants for tabs
        const activeClasses = ['text-blue-600', 'border-blue-600', 'bg-white'];
        const inactiveClasses = ['border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50'];

        // Reset all tabs
        document.querySelectorAll('#dashboard-tabs button').forEach(el => {
            el.classList.remove(...activeClasses);
            el.classList.add(...inactiveClasses);
        });
        
        // Hide all contents
        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });

        // Activate selected tab
        const selectedBtn = document.getElementById(tabId + '-tab');
        selectedBtn.classList.remove(...inactiveClasses);
        selectedBtn.classList.add(...activeClasses);
        
        const selectedContent = document.getElementById(tabId + '-content');
        selectedContent.classList.remove('hidden');
        selectedContent.classList.add('block');

        // Adjust DataTables columns to prevent width issues when showing a hidden table
        if(dTables[tabId + '-table']) {
            dTables[tabId + '-table'].columns.adjust().draw();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables
        $('.datatable').each(function() {
            let tableId = $(this).attr('id');
            dTables[tableId] = $(this).DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                responsive: true,
                scrollX: true, 
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
                columnDefs: [
                    { className: "dt-head-left", targets: "_all" }
                ]
            });
        });

        // Initialize Chart
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const chartData = @json($data['chart']);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Penambahan Grading (Gram)',
                        data: chartData.datasets.grading,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 0,
                        yAxisID: 'y',
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Penambahan Bahan Jadi (Gram)',
                        data: chartData.datasets.bahan_jadi,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 0,
                        yAxisID: 'y',
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Penambahan Siap Export (Box)',
                        data: chartData.datasets.export,
                        backgroundColor: 'rgba(139, 92, 246, 0.2)',
                        borderColor: 'rgb(139, 92, 246)',
                        borderWidth: 3,
                        type: 'line',
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointBackgroundColor: 'rgb(139, 92, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#4b5563',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: "'Inter', sans-serif" }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Berat (Gram)',
                            font: { weight: 'bold' }
                        },
                        grid: {
                            color: '#f3f4f6',
                            borderDash: [5, 5]
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Jumlah (Box)',
                            font: { weight: 'bold' }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush