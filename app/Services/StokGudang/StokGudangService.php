<?php

namespace App\Services\StokGudang;

use Illuminate\Support\Facades\DB;

class StokGudangService
{
    /**
     * Get aggregated stock summary for Demak.
     *
     * @param string|null $search Search keyword for item name
     * @return \Illuminate\Support\Collection
     */
    public function getDemakSummary(?string $search = null)
    {
        $query = DB::table('barang_masuk_demak')
            ->join('nama_barang', 'barang_masuk_demak.barang_id', '=', 'nama_barang.id')
            ->select(
                'nama_barang.id',
                'nama_barang.nama_barang',
                DB::raw('SUM(barang_masuk_demak.gram) as total_gram'),
                DB::raw('SUM(barang_masuk_demak.pcs) as total_pcs')
            )
            ->groupBy('nama_barang.id', 'nama_barang.nama_barang');

        if ($search) {
            $query->where('nama_barang.nama_barang', 'like', "%{$search}%");
        }

        return $query->get();
    }

    /**
     * Get aggregated stock summary for Jasa Cuci.
     *
     * @param string|null $search Search keyword for item name
     * @return \Illuminate\Support\Collection
     */
    public function getJasaCuciSummary(?string $search = null)
    {
        $query = DB::table('barang_masuk_jasa_cuci')
            ->join('nama_barang', 'barang_masuk_jasa_cuci.barang_id', '=', 'nama_barang.id')
            ->select(
                'nama_barang.id',
                'nama_barang.nama_barang',
                DB::raw('SUM(barang_masuk_jasa_cuci.berat_per_pcs) as total_gram'),
                DB::raw('COUNT(barang_masuk_jasa_cuci.id) as total_pcs')
            )
            ->groupBy('nama_barang.id', 'nama_barang.nama_barang');

        if ($search) {
            $query->where('nama_barang.nama_barang', 'like', "%{$search}%");
        }

        return $query->get();
    }

    /**
     * Get detail of incoming Demak stock for a specific material.
     *
     * @param int $barangId
     * @return \Illuminate\Support\Collection
     */
    public function getDemakDetail($barangId)
    {
        return DB::table('barang_masuk_demak')
            ->join('barang_masuk', 'barang_masuk_demak.barang_masuk_id', '=', 'barang_masuk.id')
            ->select(
                'barang_masuk.tanggal_terima',
                'barang_masuk_demak.partai',
                'barang_masuk_demak.gram',
                'barang_masuk_demak.pcs',
                'barang_masuk_demak.box',
                'barang_masuk_demak.keterangan'
            )
            ->where('barang_masuk_demak.barang_id', $barangId)
            ->orderBy('barang_masuk.tanggal_terima', 'desc')
            ->get();
    }

    /**
     * Get detail of incoming Jasa Cuci stock for a specific material.
     *
     * @param int $barangId
     * @return \Illuminate\Support\Collection
     */
    public function getJasaCuciDetail($barangId)
    {
        return DB::table('barang_masuk_jasa_cuci')
            ->join('barang_masuk', 'barang_masuk_jasa_cuci.barang_masuk_id', '=', 'barang_masuk.id')
            ->join('kode_bahan', 'barang_masuk_jasa_cuci.kode_bahan_id', '=', 'kode_bahan.id')
            ->select(
                'barang_masuk.tanggal_terima',
                'kode_bahan.kode_bahan',
                'barang_masuk_jasa_cuci.berat_per_pcs',
                'barang_masuk_jasa_cuci.persentase_susut',
                'barang_masuk_jasa_cuci.kadar_air'
            )
            ->where('barang_masuk_jasa_cuci.barang_id', $barangId)
            ->orderBy('barang_masuk.tanggal_terima', 'desc')
            ->get();
    }
}
