<?php

namespace App\Services\Admin\Transaksi\BarangMasuk;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDemak;
use App\Models\JenisBarangMasuk;
use App\Models\BarangKeluar;
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
                'barang_keluar_id' => $step1['barang_keluar_id'],
                'tanggal_terima' => $step1['tanggal_terima'],
            ]);

            $totalSusut = 0;
            $barangKeluar = BarangKeluar::findOrFail($step1['barang_keluar_id']);

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
                    'transaksi_ke_id' => 1, // Central Warehouse usually  
                    'gram' => $item['gram_diterima'],
                    'pcs' => $outItem->pcs,
                    'box' => 0, // default
                    'keterangan' => 'Retur dari batch BK-'.$barangKeluar->id,
                    'susut_per_item' => $susut,
                ]);

                // Update original status
                $outItem->update(['status_kembali' => true]);
            }

            // Update parent susut
            $barangMasuk->update(['total_susut_batch' => $totalSusut]);

            // Check completely returned
            $remaining = $barangKeluar->barangKeluarDemak()->where('status_kembali', false)->count();
            if ($remaining === 0) {
                $barangKeluar->update(['status_lunas' => true]);
            }

            return $barangMasuk;
        });
    }
}