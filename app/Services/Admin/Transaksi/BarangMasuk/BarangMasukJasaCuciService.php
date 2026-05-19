<?php

namespace App\Services\Admin\Transaksi\BarangMasuk;

use App\Models\BarangMasuk;
use App\Models\BarangMasukJasaCuci;
use App\Models\JenisBarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarJasaCuci;
use Illuminate\Support\Facades\DB;

class BarangMasukJasaCuciService
{
    public function storeData(array $step1, array $selectedItems)
    {
        return DB::transaction(function () use ($step1, $selectedItems) {
            $jenisBarangMasuk = JenisBarangMasuk::firstOrCreate(['nama' => 'Jasa Cuci']);

            $barangMasuk = BarangMasuk::create([
                'jenis_barang_masuk_id' => $jenisBarangMasuk->id,
                'barang_keluar_id' => $step1['barang_keluar_id'],
                'tanggal_terima' => $step1['tanggal_terima'],
            ]);

            $totalSusut = 0;
            $barangKeluar = BarangKeluar::findOrFail($step1['barang_keluar_id']);

            foreach ($selectedItems as $item) {
                $outItem = BarangKeluarJasaCuci::findOrFail($item['barang_keluar_jasa_cuci_id']);
                
                $beratSetoran = $item['gram_diterima'];
                $beratTambangan = $item['berat_timbangan_gudang'];
                
                $susut = $beratSetoran - $beratTambangan;
                $totalSusut += $susut;

                // Calculate persentase_selisih from the fields (beratSetoran and beratTambangan)
                $persentase = $beratSetoran > 0 ? ($susut / $beratSetoran) * 100 : 0;

                BarangMasukJasaCuci::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_keluar_jasa_cuci_id' => $outItem->id,
                    'lokasi_jasa_cuci_id' => $step1['lokasi_jasa_cuci_id'],
                    'barang_id' => $item['barang_id'],
                    'kode_bahan_id' => $outItem->kode_bahan_id,
                    'berat_per_pcs' => $beratSetoran,
                    'persentase_selisih' => $persentase,
                    'berat_timbangan_gudang' => $beratTambangan,
                    'susut_per_item' => $susut,
                ]);

                // Update original status
                $outItem->update(['status_kembali' => true]);
            }

            // Update parent susut
            $barangMasuk->update(['total_susut_batch' => $totalSusut]);

            // Check completely returned
            $remaining = $barangKeluar->barangKeluarJasaCuci()->where('status_kembali', false)->count();
            if ($remaining === 0) {
                $barangKeluar->update(['status_lunas' => true]);
            }

            return $barangMasuk;
        });
    }
}