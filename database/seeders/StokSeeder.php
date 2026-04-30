<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\StokGudang; // BUG-4: Use correct model name
use App\Models\Lokasi;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // BUG-4: Use correct model name instead of non-existent Stok
        if (class_exists(StokGudang::class)) {
            StokGudang::truncate();
        }

        // Cari ID lokasi untuk Demak dan Jasa Cuci
        $lokasiDemak = Lokasi::where('nama_lokasi', 'like', '%Demak%')->first();
        $lokasiJasaCuci = Lokasi::where('nama_lokasi', 'like', '%Jasa Cuci%')->first();

        // Array penyimpanan sementara untuk di-insert ke DB
        // Format: [ lokasi_id => [ barang_id => [ 'total_gram' => X, 'total_pcs' => Y, ... ] ] ]
        $stokAkhir = [];

        // ----------------------------------------------------
        // A. HITUNG BARANG MASUK & KELUAR DEMAK
        // ----------------------------------------------------
        if ($lokasiDemak) {
            $locId = $lokasiDemak->id;

            // 1. Barang Masuk Demak (+)
            $masukDemak = DB::table('barang_masuk_demak')
                ->select('barang_id', DB::raw('SUM(gram) as sum_gram'), DB::raw('SUM(pcs) as sum_pcs'), DB::raw('SUM(box) as sum_box'))
                ->groupBy('barang_id')->get();

            foreach ($masukDemak as $item) {
                if (!isset($stokAkhir[$locId][$item->barang_id])) {
                    $stokAkhir[$locId][$item->barang_id] = ['total_gram' => 0, 'total_pcs' => 0, 'total_box' => 0];
                }
                $stokAkhir[$locId][$item->barang_id]['total_gram'] += $item->sum_gram;
                $stokAkhir[$locId][$item->barang_id]['total_pcs'] += $item->sum_pcs;
                $stokAkhir[$locId][$item->barang_id]['total_box'] += $item->sum_box;
            }

            // 2. Barang Keluar Demak (-)
            // Asumsi table barang_keluar_demak punya kolom gram, pcs, box
            if (Schema::hasTable('barang_keluar_demak')) {
                $keluarDemak = DB::table('barang_keluar_demak')
                    ->select('barang_id', DB::raw('SUM(gram) as sum_gram'), DB::raw('SUM(pcs) as sum_pcs'), DB::raw('SUM(box) as sum_box'))
                    ->groupBy('barang_id')->get();

                foreach ($keluarDemak as $item) {
                    if (isset($stokAkhir[$locId][$item->barang_id])) {
                        $stokAkhir[$locId][$item->barang_id]['total_gram'] -= $item->sum_gram;
                        $stokAkhir[$locId][$item->barang_id]['total_pcs'] -= $item->sum_pcs;
                        $stokAkhir[$locId][$item->barang_id]['total_box'] -= $item->sum_box;
                    }
                }
            }
        }

        // ----------------------------------------------------
        // B. HITUNG BARANG MASUK & KELUAR JASA CUCI
        // ----------------------------------------------------
        if ($lokasiJasaCuci) {
            $locId = $lokasiJasaCuci->id;

            // 1. Barang Masuk Jasa Cuci (+)
            // Asumsi jc pakai berat_per_pcs, jumlah baris (count) sebagai pcs
            $masukJasaCuci = DB::table('barang_masuk_jasa_cuci')
                ->select('barang_id', DB::raw('SUM(berat_per_pcs) as sum_gram'), DB::raw('COUNT(id) as sum_pcs'))
                ->groupBy('barang_id')->get();

            foreach ($masukJasaCuci as $item) {
                if (!isset($stokAkhir[$locId][$item->barang_id])) {
                    $stokAkhir[$locId][$item->barang_id] = ['total_gram' => 0, 'total_pcs' => 0, 'total_box' => 0];
                }
                $stokAkhir[$locId][$item->barang_id]['total_gram'] += $item->sum_gram;
                $stokAkhir[$locId][$item->barang_id]['total_pcs'] += $item->sum_pcs;
            }

            // 2. Barang Keluar Jasa Cuci (-)
            if (Schema::hasTable('barang_keluar_jasa_cuci')) {
                // Konsep pengurangannya harus disesuaikan dengan schema barang_keluar_jasa_cuci
                // Misal asumsikan kolomnya sama: berat_per_pcs
             /*   $keluarJasaCuci = DB::table('barang_keluar_jasa_cuci')
                    ->select('barang_id', DB::raw('SUM(berat_per_pcs) as sum_gram'), DB::raw('COUNT(id) as sum_pcs'))
                    ->groupBy('barang_id')->get();

                foreach ($keluarJasaCuci as $item) {
                    if (isset($stokAkhir[$locId][$item->barang_id])) {
                        $stokAkhir[$locId][$item->barang_id]['total_gram'] -= $item->sum_gram;
                        $stokAkhir[$locId][$item->barang_id]['total_pcs'] -= $item->sum_pcs;
                    }
                } */
            }
        }

        // ----------------------------------------------------
        // C. MASUKKAN DATA FINAL KE TABEL STOKS
        // ----------------------------------------------------
        $inserts = [];
        $now = now();
        foreach ($stokAkhir as $lok_id => $barangs) {
            foreach ($barangs as $brg_id => $qty) {
                if ($qty['total_gram'] > 0 || $qty['total_pcs'] > 0) {
                    $inserts[] = [
                        'lokasi_id' => $lok_id,
                        'barang_id' => $brg_id,
                        'total_gram' => $qty['total_gram'],
                        'total_pcs' => $qty['total_pcs'],
                        'total_box' => $qty['total_box'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        // BUG-4: Use correct model name
        StokGudang::insert($inserts);

        $this->command->info("Seed Stok berhasil dijalankan. Total saldo akhir dimasukkan: " . count($inserts) . " record.");
    }
}
