<?php

namespace App\Services\Admin\Transaksi\BarangMasuk;


use App\Models\BarangMasuk;
use App\Models\BarangMasukJasaCuci;
use App\Models\JenisBarangMasuk;
use Illuminate\Support\Facades\DB;

class BarangMasukJasaCuciService
{
    public function storeData(array $step1Data, array $items)
    {
        return DB::transaction(function () use ($step1Data, $items) {
            $jenisBarangMasuk = JenisBarangMasuk::firstOrCreate(['nama' => 'Jasa Cuci']);

            $barangMasuk = BarangMasuk::create([
                'jenis_barang_masuk_id' => $jenisBarangMasuk->id,
                'tanggal_terima' => $step1Data['tanggal_terima'],
            ]);

            foreach ($items as $item) {
                BarangMasukJasaCuci::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'lokasi_jasa_cuci_id' => $step1Data['lokasi_jasa_cuci_id'],
                    'barang_id' => $item['barang_id'],
                    'kode_bahan_id' => $item['kode_bahan_id'],
                    'berat_per_pcs' => $item['berat_per_pcs'],
                    'persentase_susut' => $item['persentase_susut'],
                    'kadar_air' => $item['kadar_air'],
                ]);
            }

            return $barangMasuk;
        });
    }

    public function updateData(\App\Models\BarangMasuk $barangMasukBatch, array $data)
    {
        return DB::transaction(function () use ($barangMasukBatch, $data) {
            $barangMasukBatch->update([
                'tanggal_terima' => $data['tanggal_terima'],
            ]);

            // Delete existing items
            $barangMasukBatch->barangMasukJasaCuci()->delete();

            // Recreate new items
            foreach ($data['items'] as $item) {
                BarangMasukJasaCuci::create([
                    'barang_masuk_id' => $barangMasukBatch->id,
                    'lokasi_jasa_cuci_id' => $data['lokasi_jasa_cuci_id'],
                    'barang_id' => $item['barang_id'],
                    'kode_bahan_id' => $item['kode_bahan_id'],
                    'berat_per_pcs' => $item['berat_per_pcs'],
                    'persentase_susut' => $item['persentase_susut'],
                    'kadar_air' => $item['kadar_air'],
                ]);
            }

            return $barangMasukBatch;
        });
    }

    public function destroyData(\App\Models\BarangMasuk $barangMasukBatch)
    {
        return DB::transaction(function () use ($barangMasukBatch) {
            $barangMasukBatch->delete();
            return true;
        });
    }
}