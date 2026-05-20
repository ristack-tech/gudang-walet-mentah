<?php

namespace App\Services\Admin\Transaksi\barangKeluar;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarDemak;
use Illuminate\Support\Facades\DB;
use App\Models\JenisBarangKeluar;

class BarangKeluarDemakService
{
    public function store($step1Data, $items)
    {
        return DB::transaction(function () use ($step1Data, $items) {
            $jenis = JenisBarangKeluar::firstOrCreate(['nama' => 'Demak']);
            $jenisId = $jenis->id;

            $barangKeluar = BarangKeluar::create([
                'jenis_barang_keluar_id' => $jenisId,
                'tanggal_kirim' => $step1Data['tanggal_kirim'],
            ]);

            foreach ($items as $item) {
                BarangKeluarDemak::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'partai_id' => $step1Data['partai_id'],
                    'warna_id' => $item['warna_id'] ?? null,
                    'kode_lp' => $item['kode_lp'],
                    'gram' => $item['gram'],
                    'pcs' => $item['pcs'],
                    'kadar_air' => $item['kadar_air'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            return $barangKeluar;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $barangKeluar = BarangKeluar::findOrFail($id);

            $barangKeluar->update([
                'tanggal_kirim' => $data['tanggal_kirim'],
            ]);

            // Clear old items
            $barangKeluar->barangKeluarDemak()->delete();

            // Insert new ones
            foreach ($data['items'] as $item) {
                BarangKeluarDemak::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'partai_id' => $data['partai_id'],
                    'warna_id' => $item['warna_id'] ?? null,
                    'kode_lp' => $item['kode_lp'],
                    'gram' => $item['gram'],
                    'pcs' => $item['pcs'],
                    'kadar_air' => $item['kadar_air'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            return $barangKeluar;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $barangKeluar->barangKeluarDemak()->delete();
            return $barangKeluar->delete();
        });
    }
}
