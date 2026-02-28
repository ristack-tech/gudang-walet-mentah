<?php

namespace App\Services\Admin\Transaksi\BarangMasuk;


use App\Models\BarangMasuk;
use App\Models\BarangMasukPembelianSupplier;
use App\Models\JenisBarangMasuk;
use Illuminate\Support\Facades\DB;

class BarangMasukPembelianSupplierService
{
    public function storeData(array $step1Data, array $items)
    {
        return DB::transaction(function () use ($step1Data, $items) {
            $jenisBarangMasuk = JenisBarangMasuk::firstOrCreate(['nama' => 'Pembelian Supplier']);

            $barangMasukBatch = BarangMasuk::create([
                'jenis_barang_masuk_id' => $jenisBarangMasuk->id,
                'tanggal_terima' => $step1Data['tanggal_terima'],
            ]);

            foreach ($items as $item) {
                BarangMasukPembelianSupplier::create([
                    'barang_masuk_id' => $barangMasukBatch->id,
                    'barang_id' => $item['barang_id'],
                    'supplier_id' => $step1Data['supplier_id'],
                    'berat' => $item['berat'],
                    'pcs' => $item['pcs'],
                    'box' => $item['box'],
                ]);
            }

            return $barangMasukBatch;
        });
    }

    public function updateData(\App\Models\BarangMasuk $barangMasukBatch, array $data)
    {
        return DB::transaction(function () use ($barangMasukBatch, $data) {
            $barangMasukBatch->update([
                'tanggal_terima' => $data['tanggal_terima'],
            ]);

            // Delete existing items
            $barangMasukBatch->barangMasukPembelianSupplier()->delete();

            // Recreate items
            foreach ($data['items'] as $item) {
                BarangMasukPembelianSupplier::create([
                    'barang_masuk_id' => $barangMasukBatch->id,
                    'barang_id' => $item['barang_id'],
                    'supplier_id' => $data['supplier_id'],
                    'berat' => $item['berat'],
                    'pcs' => $item['pcs'],
                    'box' => $item['box'],
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