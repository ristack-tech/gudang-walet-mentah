<?php

namespace App\Services\Admin\Transaksi\barangKeluar;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarJasaCuci;
use Illuminate\Support\Facades\DB;
use App\Models\JenisBarangKeluar;
use App\Models\KodeBahan;

class BarangKeluarJasaCuciService
{
    public function store($step1Data, $items)
    {
        return DB::transaction(function () use ($step1Data, $items) {
            $jenis = JenisBarangKeluar::firstOrCreate(['nama' => 'Jasa Cuci']);
            $jenisId = $jenis->id;

            $barangKeluar = BarangKeluar::create([
                'jenis_barang_keluar_id' => $jenisId,
                'tanggal_kirim' => $step1Data['tanggal_kirim'],
                'lokasi_id' => $step1Data['lokasi_id'],
            ]);

            foreach ($items as $item) {
                $kodeBahan = KodeBahan::firstOrCreate(['nama' => $item['kode_bahan']]);

                BarangKeluarJasaCuci::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'kode_bahan_id' => $kodeBahan->id,
                    'keterangan_cetak' => $item['keterangan_cetak'] ?? null,
                    'berat_per_pcs' => $item['berat_per_pcs'],
                    'kadar_air' => $item['kadar_air'] ?? null,
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
                'lokasi_id' => $data['lokasi_id'],
            ]);

            // Clear old items
            $barangKeluar->barangKeluarJasaCuci()->delete();

            // Insert new ones
            foreach ($data['items'] as $item) {
                $kodeBahan = KodeBahan::firstOrCreate(['nama' => $item['kode_bahan']]);

                BarangKeluarJasaCuci::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'kode_bahan_id' => $kodeBahan->id,
                    'keterangan_cetak' => $item['keterangan_cetak'] ?? null,
                    'berat_per_pcs' => $item['berat_per_pcs'],
                    'kadar_air' => $item['kadar_air'] ?? null,
                ]);
            }

            return $barangKeluar;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $barangKeluar->barangKeluarJasaCuci()->delete();
            return $barangKeluar->delete();
        });
    }
}