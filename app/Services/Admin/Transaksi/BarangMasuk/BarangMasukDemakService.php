<?php

namespace App\Services\Admin\Transaksi\BarangMasuk;


use App\Models\BarangMasuk;
use App\Models\BarangMasukDemak;
use App\Models\JenisBarangMasuk;
use Illuminate\Support\Facades\DB;

class BarangMasukDemakService
{
    public function storeData(array $step1, array $items)
    {
        return DB::transaction(function () use ($step1, $items) {
            $jenisBarangMasuk = JenisBarangMasuk::firstOrCreate(['nama' => 'Demak']);

            $barangMasuk = BarangMasuk::create([
                'jenis_barang_masuk_id' => $jenisBarangMasuk->id,
                'tanggal_terima' => $step1['tanggal_terima'],
            ]);

            foreach ($items as $item) {
                BarangMasukDemak::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $item['barang_id'],
                    'partai' => $item['partai'],
                    'transaksi_dari_id' => $step1['transaksi_dari_id'],
                    'transaksi_ke_id' => $step1['transaksi_ke_id'],
                    'gram' => $item['gram'],
                    'pcs' => $item['pcs'],
                    'box' => $item['box'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            return $barangMasuk;
        });
    }

    public function updateData(BarangMasukDemak $barangMasukDemak, array $data)
    {
        return DB::transaction(function () use ($barangMasukDemak, $data) {
            if (isset($data['tanggal_terima'])) {
                $barangMasukDemak->barangMasuk->update([
                    'tanggal_terima' => $data['tanggal_terima'],
                ]);
            }

            $barangMasukDemak->update([
                'barang_id' => $data['barang_id'] ?? $barangMasukDemak->barang_id,
                'partai' => $data['partai'] ?? $barangMasukDemak->partai,
                'transaksi_dari_id' => $data['transaksi_dari_id'] ?? $barangMasukDemak->transaksi_dari_id,
                'transaksi_ke_id' => $data['transaksi_ke_id'] ?? $barangMasukDemak->transaksi_ke_id,
                'gram' => $data['gram'] ?? $barangMasukDemak->gram,
                'pcs' => $data['pcs'] ?? $barangMasukDemak->pcs,
                'box' => $data['box'] ?? $barangMasukDemak->box,
                'keterangan' => $data['keterangan'] ?? $barangMasukDemak->keterangan,
            ]);

            return $barangMasukDemak;
        });
    }

    public function destroyData(BarangMasukDemak $barangMasukDemak)
    {
        return DB::transaction(function () use ($barangMasukDemak) {
            $barangMasuk = $barangMasukDemak->barangMasuk;
            $barangMasukDemak->delete();

            if ($barangMasuk && $barangMasuk->barangMasukDemak()->count() === 0) {
                $barangMasuk->delete();
            }

            return true;
        });
    }
}