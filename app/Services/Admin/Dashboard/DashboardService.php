<?php

namespace App\Services\Admin\Dashboard;

use App\Models\StokGrading;
use App\Models\StokGradingDetail;
use App\Models\StokBarangJadi;
use App\Models\StokBarangJadiDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData()
    {
        // 1. Total Stok Grading (Gram) - Sisa stok yang masih ada di grading
        $stokGradingTotalGram = StokGradingDetail::sum('berat');
        
        // 2. Total Stok Bahan Jadi (Gram) - Semua bahan jadi yang ada (termasuk clop)
        $stokBahanJadiTotalGram = StokBarangJadiDetail::sum('berat');

        // 3. Total Stok Siap Export (Box) - Total box dari bahan jadi
        $stokSiapExportTotalBox = StokBarangJadiDetail::sum('jumlah_box');

        // Fetch Data for Tables (Detailed)
        $gradings = StokGradingDetail::with(['stokGrading.namaBarang'])
            ->orderBy('id', 'desc')
            ->take(500)
            ->get();
            
        // For Bahan Jadi table, we fetch all StokBarangJadi
        $bahanJadi = StokBarangJadiDetail::with(['stokBarangJadi', 'namaBarang'])
            ->orderBy('id', 'desc')
            ->take(500)
            ->get();
            
        // For Export table (Clop/Ready to export)
        $clop = StokBarangJadiDetail::with(['stokBarangJadi', 'namaBarang'])
            ->orderBy('id', 'desc')
            ->take(500)
            ->get();

        return [
            'totals' => [
                'grading_gram' => $stokGradingTotalGram,
                'bahan_jadi_gram' => $stokBahanJadiTotalGram,
                'siap_export_box' => $stokSiapExportTotalBox,
            ],
            'tables' => [
                'gradings' => $gradings,
                'bahan_jadi' => $bahanJadi,
                'clop' => $clop,
            ],
            'chart' => $this->getChartData()
        ];
    }

    private function getChartData()
    {
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->toDateString());
        }

        $gradingData = [];
        $bahanJadiData = [];
        $exportData = []; 

        foreach ($dates as $date) {
            $gradingData[] = StokGrading::whereDate('tanggal', $date)->sum('total_gram_keseluruhan');
            
            $bahanJadiData[] = StokBarangJadiDetail::whereHas('stokBarangJadi', function ($q) use ($date) {
                $q->whereDate('tanggal', $date);
            })->sum('berat');

            $exportData[] = StokBarangJadiDetail::whereHas('stokBarangJadi', function ($q) use ($date) {
                $q->whereDate('tanggal', $date);
            })->sum('jumlah_box');
        }

        return [
            'labels' => $dates->map(function($date) { return Carbon::parse($date)->format('d M'); })->toArray(),
            'datasets' => [
                'grading' => $gradingData,
                'bahan_jadi' => $bahanJadiData,
                'export' => $exportData,
            ]
        ];
    }
}
