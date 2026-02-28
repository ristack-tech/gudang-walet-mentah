<?php

namespace App\Services\Admin\Transaksi\barangKeluar;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarDemak;
use Illuminate\Support\Facades\DB;
use App\Models\JenisBarangKeluar;

class BarangKeluarDemakService
{
    public function store($step1Data, $step2Data)
    {
        return DB::transaction(function () use ($step1Data, $step2Data) {
            $jenis = JenisBarangKeluar::firstOrCreate(['nama' => 'Demak']);
            $jenisId = $jenis->id;

            $barangKeluar = BarangKeluar::create([
                'jenis_barang_keluar_id' => $jenisId,
                'tanggal_kirim' => $step1Data['tanggal_kirim'],
            ]);

            return BarangKeluarDemak::create([
                'barang_keluar_id' => $barangKeluar->id,
                'partai_id' => $step1Data['partai_id'],
                'warna_id' => $step2Data['warna_id'],
                'kode_lp' => $step2Data['kode_lp'],
                'gram' => $step2Data['gram'],
                'pcs' => $step2Data['pcs'],
                'kadar_air' => $step2Data['kadar_air'],
                'timbangan_ulang' => $step2Data['timbangan_ulang'],
                'keterangan' => $step2Data['keterangan'],
            ]);
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $demak = BarangKeluarDemak::findOrFail($id);

            $demak->barangKeluar->update([
                'tanggal_kirim' => $data['tanggal_kirim'],
            ]);

            $demak->update([
                'partai_id' => $data['partai_id'],
                'warna_id' => $data['warna_id'],
                'kode_lp' => $data['kode_lp'],
                'gram' => $data['gram'],
                'pcs' => $data['pcs'],
                'kadar_air' => $data['kadar_air'],
                'timbangan_ulang' => $data['timbangan_ulang'],
                'keterangan' => $data['keterangan'],
            ]);

            return $demak;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $demak = BarangKeluarDemak::findOrFail($id);
            if ($demak->barangKeluar) {
                $demak->barangKeluar->delete();
            }
            return $demak->delete();
        });
    }
}
