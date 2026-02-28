<?php

namespace App\Services\Admin\Transaksi\barangKeluar;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarJasaCuci;
use Illuminate\Support\Facades\DB;
use App\Models\JenisBarangKeluar;
use App\Models\KodeBahan;

class BarangKeluarJasaCuciService
{
    public function store($step1Data, $step2Data)
    {
        return DB::transaction(function () use ($step1Data, $step2Data) {
            $jenis = JenisBarangKeluar::firstOrCreate(['nama' => 'Jasa Cuci']);
            $jenisId = $jenis->id;

            $barangKeluar = BarangKeluar::create([
                'jenis_barang_keluar_id' => $jenisId,
                'tanggal_kirim' => $step1Data['tanggal_kirim'],
                'lokasi_id' => $step1Data['lokasi_id'],
            ]);

            return BarangKeluarJasaCuci::create([
                'barang_keluar_id' => $barangKeluar->id,
                'kode_bahan_id' => $step2Data['kode_bahan_id'],
                'keterangan_cetak' => $step2Data['keterangan_cetak'],
                'berat_per_pcs' => $step2Data['berat_per_pcs'],
                'kadar_air' => $step2Data['kadar_air'],
            ]);
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $jasaCuci = BarangKeluarJasaCuci::findOrFail($id);

            $jasaCuci->barangKeluar->update([
                'tanggal_kirim' => $data['tanggal_kirim'],
                'lokasi_id' => $data['lokasi_id'],
            ]);

            $jasaCuci->update([
                'kode_bahan_id' => $data['kode_bahan_id'],
                'keterangan_cetak' => $data['keterangan_cetak'],
                'berat_per_pcs' => $data['berat_per_pcs'],
                'kadar_air' => $data['kadar_air'],
            ]);

            return $jasaCuci;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jasaCuci = BarangKeluarJasaCuci::findOrFail($id);
            if ($jasaCuci->barangKeluar) {
                $jasaCuci->barangKeluar->delete();
            }
            return $jasaCuci->delete();
        });
    }
}