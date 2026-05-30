<?php

namespace App\Services\Admin\Transaksi\BarangMasuk;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDemak;
use App\Models\JenisBarangMasuk;
use App\Models\BarangKeluarDemak;
use Illuminate\Support\Facades\DB;

class BarangMasukDemakService
{
    public function storeData(array $step1, array $selectedItems)
    {
        return DB::transaction(function () use ($step1, $selectedItems) {
            $jenisBarangMasuk = JenisBarangMasuk::firstOrCreate(['nama' => 'Demak']);

            $barangMasuk = BarangMasuk::create([
                'jenis_barang_masuk_id' => $jenisBarangMasuk->id,
                'tanggal_terima' => $step1['tanggal_terima'],
            ]);

            // BUG-1: Ambil lokasi ID "Gudang Utama" secara dinamis
            $lokasiPusat = \App\Models\Lokasi::where('nama_lokasi', 'like', '%Gudang Utama%')->first();
            if (!$lokasiPusat) {
                $lokasiPusat = \App\Models\Lokasi::first();
            }
            if (!$lokasiPusat) {
                throw new \Exception('Lokasi Gudang Utama tidak ditemukan. Harap setup master data lokasi terlebih dahulu.');
            }

            $totalSusut = 0;

            foreach ($selectedItems as $item) {
                $outItem = BarangKeluarDemak::with('partai')->findOrFail($item['barang_keluar_demak_id']);

                $susut = $outItem->gram - $item['gram_diterima'];
                $totalSusut += $susut;

                BarangMasukDemak::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_keluar_demak_id' => $outItem->id,
                    'barang_id' => $item['barang_id'],
                    'partai' => $item['partai'],
                    'transaksi_dari_id' => $step1['transaksi_dari_id'],
                    'transaksi_ke_id' => $lokasiPusat->id,  
                    'gram' => $item['gram_diterima'],
                    'pcs' => $item['pcs'],
                    'box' => $item['box'],
                    'keterangan' => $outItem->kode_lp,
                    'susut_per_item' => $susut,
                ]);
            }

            // Update parent susut
            $barangMasuk->update(['total_susut_batch' => $totalSusut]);

            return $barangMasuk;
        });
    }

    public function destroyData(BarangMasuk $barangMasukBatch)
    {
        return DB::transaction(function () use ($barangMasukBatch) {
            foreach ($barangMasukBatch->barangMasukDemak as $item) {
                $item->delete();
            }
            $barangMasukBatch->delete();
        });
    }
}