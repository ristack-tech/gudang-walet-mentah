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
                'kode_bahan.nama as kode_bahan',
                'barang_masuk_jasa_cuci.berat_per_pcs',
                'barang_masuk_jasa_cuci.persentase_susut',
                'barang_masuk_jasa_cuci.kadar_air'
            )
            ->where('barang_masuk_jasa_cuci.barang_id', $barangId)
            ->orderBy('barang_masuk.tanggal_terima', 'desc')
            ->get();
    }

    /**
     * Get aggregated stock summary from all locations (Global).
     *
     * @param string|null $search Search keyword for item name
     * @return \Illuminate\Support\Collection
     */
    public function getGlobalSummary(?string $search = null)
    {
        $demak = DB::table('barang_masuk_demak')
            ->select(
                'barang_id',
                DB::raw('SUM(gram) as total_gram'),
                DB::raw('SUM(pcs) as total_pcs'),
                DB::raw('SUM(box) as total_box')
            )
            ->groupBy('barang_id');

        $jasaCuci = DB::table('barang_masuk_jasa_cuci')
            ->select(
                'barang_id',
                DB::raw('SUM(berat_per_pcs) as total_gram'),
                DB::raw('COUNT(id) as total_pcs'),
                DB::raw('0 as total_box')
            )
            ->groupBy('barang_id');

        $unionQuery = $demak->unionAll($jasaCuci);

        $query = DB::query()
            ->fromSub($unionQuery, 'combined')
            ->join('nama_barang', 'combined.barang_id', '=', 'nama_barang.id')
            ->select(
                'combined.barang_id as id',
                'nama_barang.nama_barang',
                DB::raw('SUM(combined.total_gram) as total_gram'),
                DB::raw('SUM(combined.total_pcs) as total_pcs'),
                DB::raw('SUM(combined.total_box) as total_box')
            )
            ->groupBy('combined.barang_id', 'nama_barang.nama_barang');

        if ($search) {
            $query->where('nama_barang.nama_barang', 'like', "%{$search}%");
        }

        return $query->get();
    }

    /**
     * Get isolated global summary for a specific barang.
     */
    public function getGlobalSummaryForBarang($barangId)
    {
        $demak = DB::table('barang_masuk_demak')
            ->select(
                'barang_id',
                DB::raw('SUM(gram) as total_gram'),
                DB::raw('SUM(pcs) as total_pcs'),
                DB::raw('SUM(box) as total_box')
            )
            ->where('barang_id', $barangId)
            ->groupBy('barang_id');

        $jasaCuci = DB::table('barang_masuk_jasa_cuci')
            ->select(
                'barang_id',
                DB::raw('SUM(berat_per_pcs) as total_gram'),
                DB::raw('COUNT(id) as total_pcs'),
                DB::raw('0 as total_box')
            )
            ->where('barang_id', $barangId)
            ->groupBy('barang_id');

        $unionQuery = $demak->unionAll($jasaCuci);

        return DB::query()
            ->fromSub($unionQuery, 'combined')
            ->select(
                DB::raw('SUM(total_gram) as total_gram'),
                DB::raw('SUM(total_pcs) as total_pcs'),
                DB::raw('SUM(total_box) as total_box')
            )
            ->first();
    }

    /**
     * Get detail of incoming global stock for a specific material combining Demak and Jasa Cuci.
     * We use a unified alias structure so the blade file can iterate them uniformly.
     *
     * @param int $barangId
     * @return \Illuminate\Support\Collection
     */
    public function getGlobalDetail($barangId)
    {
        $demak = DB::table('barang_masuk_demak')
            ->join('barang_masuk', 'barang_masuk_demak.barang_masuk_id', '=', 'barang_masuk.id')
            ->select(
                'barang_masuk.tanggal_terima',
                DB::raw("'Demak' as sumber"),
                'barang_masuk_demak.partai as identifier',
                'barang_masuk_demak.gram as total_gram',
                'barang_masuk_demak.pcs as total_pcs',
                'barang_masuk_demak.box as total_box'
            )
            ->where('barang_masuk_demak.barang_id', $barangId);

        $jasaCuci = DB::table('barang_masuk_jasa_cuci')
            ->join('barang_masuk', 'barang_masuk_jasa_cuci.barang_masuk_id', '=', 'barang_masuk.id')
            ->join('kode_bahan', 'barang_masuk_jasa_cuci.kode_bahan_id', '=', 'kode_bahan.id')
            ->select(
                'barang_masuk.tanggal_terima',
                DB::raw("'Jasa Cuci' as sumber"),
                'kode_bahan.nama as identifier',
                'barang_masuk_jasa_cuci.berat_per_pcs as total_gram',
                DB::raw("1 as total_pcs"),
                DB::raw("0 as total_box")
            )
            ->where('barang_masuk_jasa_cuci.barang_id', $barangId);

        // Union both histories together and sort newest first
        return $demak->unionAll($jasaCuci)
            ->orderBy('tanggal_terima', 'desc')
            ->get();
    }
}
