<?php

namespace App\Services\Admin\Transaksi\StokGudang;

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
        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram'),
                DB::raw('SUM(box_awal) as used_box')
            )
            ->where('sumber_stok', 'demak')
            ->whereNull('deleted_at')
            ->groupBy('barang_id');

        $query = DB::table('barang_masuk_demak')
            ->join('nama_barang', 'barang_masuk_demak.barang_id', '=', 'nama_barang.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('barang_masuk_demak.barang_id', '=', 'graded.barang_id');
            })
            ->select(
                'nama_barang.id',
                'nama_barang.nama_barang',
                DB::raw('SUM(barang_masuk_demak.gram) - COALESCE(MAX(graded.used_gram), 0) as total_gram'),
                DB::raw('SUM(barang_masuk_demak.pcs) as total_pcs'),
                DB::raw('SUM(barang_masuk_demak.box) - COALESCE(MAX(graded.used_box), 0) as total_box')
            )
            ->groupBy('nama_barang.id', 'nama_barang.nama_barang')
            ->having(DB::raw('SUM(barang_masuk_demak.gram) - COALESCE(MAX(graded.used_gram), 0)'), '>', 0);

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
        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram'),
                DB::raw('SUM(box_awal) as used_box')
            )
            ->where('sumber_stok', 'jasa_cuci')
            ->whereNull('deleted_at')
            ->groupBy('barang_id');

        $query = DB::table('barang_masuk_jasa_cuci')
            ->join('nama_barang', 'barang_masuk_jasa_cuci.barang_id', '=', 'nama_barang.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('barang_masuk_jasa_cuci.barang_id', '=', 'graded.barang_id');
            })
            ->select(
                'nama_barang.id',
                'nama_barang.nama_barang',
                DB::raw('SUM(barang_masuk_jasa_cuci.berat_timbangan_gudang) - COALESCE(MAX(graded.used_gram), 0) as total_gram'),
                DB::raw('COUNT(barang_masuk_jasa_cuci.id) as total_pcs'),
                DB::raw('0 - COALESCE(MAX(graded.used_box), 0) as total_box')
            )
            ->groupBy('nama_barang.id', 'nama_barang.nama_barang')
            ->having(DB::raw('SUM(barang_masuk_jasa_cuci.berat_timbangan_gudang) - COALESCE(MAX(graded.used_gram), 0)'), '>', 0);

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
                'barang_masuk_jasa_cuci.persentase_selisih',
                'barang_masuk_jasa_cuci.berat_timbangan_gudang'
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
    /**
     * Get Demak stock grouped by partai (not aggregated by barang).
     * Shows each unique partai with its remaining stock.
     */
    public function getDemakByPartai(?string $search = null)
    {
        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                'partai',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram')
            )
            ->where('sumber_stok', 'demak')
            ->whereNull('deleted_at')
            ->groupBy('barang_id', 'partai');

        $bmd = DB::table('barang_masuk_demak')
            ->select(
                'barang_id',
                'partai',
                DB::raw('SUM(gram) as total_raw_gram'),
                DB::raw('SUM(pcs) as total_raw_pcs'),
                DB::raw('SUM(box) as total_raw_box')
            )
            ->whereNotNull('partai')
            ->where('partai', '!=', '')
            ->groupBy('barang_id', 'partai');

        $query = DB::query()
            ->fromSub($bmd, 'bmd')
            ->join('nama_barang', 'bmd.barang_id', '=', 'nama_barang.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('bmd.barang_id', '=', 'graded.barang_id')
                     ->on('bmd.partai', '=', 'graded.partai');
            })
            ->select(
                'bmd.barang_id as id', // Dummy ID since we grouped
                'nama_barang.id as barang_id',
                'nama_barang.nama_barang',
                'bmd.partai',
                DB::raw('bmd.total_raw_gram - COALESCE(graded.used_gram, 0) as total_gram'),
                DB::raw('bmd.total_raw_pcs as total_pcs'),
                DB::raw('bmd.total_raw_box as total_box')
            )
            ->having('total_gram', '>', 0);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang.nama_barang', 'like', "%{$search}%")
                  ->orWhere('bmd.partai', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    /**
     * Get Jasa Cuci stock grouped by kode_bahan (not aggregated by barang).
     * Shows each unique kode_bahan with its remaining stock.
     */
    public function getJasaCuciByKodeBahan(?string $search = null)
    {
        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                'partai as kode_ref',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram')
            )
            ->where('sumber_stok', 'jasa_cuci')
            ->whereNull('deleted_at')
            ->groupBy('barang_id', 'partai');

        $bmjc = DB::table('barang_masuk_jasa_cuci')
            ->select(
                'barang_id',
                'kode_bahan_id',
                DB::raw('SUM(berat_per_pcs) as total_raw_gram'),
                DB::raw('COUNT(id) as total_raw_pcs')
            )
            ->groupBy('barang_id', 'kode_bahan_id');

        $query = DB::query()
            ->fromSub($bmjc, 'bmjc')
            ->join('nama_barang', 'bmjc.barang_id', '=', 'nama_barang.id')
            ->join('kode_bahan', 'bmjc.kode_bahan_id', '=', 'kode_bahan.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('bmjc.barang_id', '=', 'graded.barang_id')
                     ->on('kode_bahan.nama', '=', 'graded.kode_ref');
            })
            ->select(
                'bmjc.kode_bahan_id as id',
                'nama_barang.id as barang_id',
                'nama_barang.nama_barang',
                'kode_bahan.nama as kode_bahan',
                'kode_bahan.id as kode_bahan_id',
                DB::raw('bmjc.total_raw_gram - COALESCE(graded.used_gram, 0) as total_gram'),
                DB::raw('bmjc.total_raw_pcs as total_pcs'),
                DB::raw('0 as total_box')
            );

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang.nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_bahan.nama', 'like', "%{$search}%");
            });
        }

        return $query->having('total_gram', '>', 0)->get();
    }

    /**
     * Get aggregated stock summary for Pembelian Supplier.
     */
    public function getPembelianSupplierSummary(?string $search = null)
    {
        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram'),
                DB::raw('SUM(box_awal) as used_box')
            )
            ->where('sumber_stok', 'pembelian_supplier')
            ->whereNull('deleted_at')
            ->groupBy('barang_id');

        $query = DB::table('barang_masuk_supplier')
            ->join('nama_barang', 'barang_masuk_supplier.barang_id', '=', 'nama_barang.id')
            ->join('supplier', 'barang_masuk_supplier.supplier_id', '=', 'supplier.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('barang_masuk_supplier.barang_id', '=', 'graded.barang_id');
            })
            ->select(
                'nama_barang.id',
                'nama_barang.nama_barang',
                DB::raw('SUM(barang_masuk_supplier.berat) - COALESCE(MAX(graded.used_gram), 0) as total_gram'),
                DB::raw('SUM(COALESCE(barang_masuk_supplier.pcs, 0)) as total_pcs'),
                DB::raw('SUM(barang_masuk_supplier.box) - COALESCE(MAX(graded.used_box), 0) as total_box')
            )
            ->groupBy('nama_barang.id', 'nama_barang.nama_barang')
            ->having(DB::raw('SUM(barang_masuk_supplier.berat) - COALESCE(MAX(graded.used_gram), 0)'), '>', 0);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang.nama_barang', 'like', "%{$search}%")
                  ->orWhere('supplier.nama', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    /**
     * Get detail of incoming Pembelian Supplier stock for a specific material.
     */
    public function getPembelianSupplierDetail($barangId)
    {
        return DB::table('barang_masuk_supplier')
            ->join('barang_masuk', 'barang_masuk_supplier.barang_masuk_id', '=', 'barang_masuk.id')
            ->join('supplier', 'barang_masuk_supplier.supplier_id', '=', 'supplier.id')
            ->select(
                'barang_masuk.tanggal_terima',
                'supplier.nama as supplier_name',
                'barang_masuk_supplier.berat',
                'barang_masuk_supplier.pcs',
                'barang_masuk_supplier.box'
            )
            ->where('barang_masuk_supplier.barang_id', $barangId)
            ->orderBy('barang_masuk.tanggal_terima', 'desc')
            ->get();
    }

    /**
     * Get Pembelian Supplier stock grouped by supplier.
     */
    public function getPembelianSupplierBySupplier(?string $search = null)
    {
        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                'partai as supplier_name',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram'),
                DB::raw('SUM(box_awal) as used_box')
            )
            ->where('sumber_stok', 'pembelian_supplier')
            ->whereNull('deleted_at')
            ->groupBy('barang_id', 'partai');

        $bms = DB::table('barang_masuk_supplier')
            ->select(
                'barang_id',
                'supplier_id',
                DB::raw('SUM(berat) as total_raw_gram'),
                DB::raw('SUM(COALESCE(pcs, 0)) as total_raw_pcs'),
                DB::raw('SUM(box) as total_raw_box')
            )
            ->groupBy('barang_id', 'supplier_id');

        $query = DB::query()
            ->fromSub($bms, 'bms')
            ->join('nama_barang', 'bms.barang_id', '=', 'nama_barang.id')
            ->join('supplier', 'bms.supplier_id', '=', 'supplier.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('bms.barang_id', '=', 'graded.barang_id')
                     ->on('supplier.nama', '=', 'graded.supplier_name');
            })
            ->select(
                'bms.supplier_id as id',
                'nama_barang.id as barang_id',
                'nama_barang.nama_barang',
                'supplier.nama as supplier_name',
                'supplier.id as supplier_id',
                DB::raw('bms.total_raw_gram - COALESCE(graded.used_gram, 0) as total_gram'),
                DB::raw('bms.total_raw_pcs as total_pcs'),
                DB::raw('bms.total_raw_box - COALESCE(graded.used_box, 0) as total_box')
            )
            ->having('total_gram', '>', 0);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang.nama_barang', 'like', "%{$search}%")
                  ->orWhere('supplier.nama', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

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
                DB::raw('SUM(berat_timbangan_gudang) as total_gram'),
                DB::raw('COUNT(id) as total_pcs'),
                DB::raw('0 as total_box')
            )
            ->groupBy('barang_id');

        $pembelianSupplier = DB::table('barang_masuk_supplier')
            ->select(
                'barang_id',
                DB::raw('SUM(berat) as total_gram'),
                DB::raw('SUM(COALESCE(pcs, 0)) as total_pcs'),
                DB::raw('SUM(box) as total_box')
            )
            ->groupBy('barang_id');

        $unionQuery = $demak->unionAll($jasaCuci)->unionAll($pembelianSupplier);

        $graded = DB::table('stok_gradings')
            ->select(
                'barang_id',
                DB::raw('SUM(total_gram_keseluruhan) as used_gram'),
                DB::raw('SUM(box_awal) as used_box')
            )
            ->whereNull('deleted_at')
            ->groupBy('barang_id');

        $query = DB::query()
            ->fromSub($unionQuery, 'combined')
            ->join('nama_barang', 'combined.barang_id', '=', 'nama_barang.id')
            ->leftJoinSub($graded, 'graded', function ($join) {
                $join->on('combined.barang_id', '=', 'graded.barang_id');
            })
            ->select(
                'combined.barang_id as id',
                'nama_barang.nama_barang',
                DB::raw('SUM(combined.total_gram) - COALESCE(MAX(graded.used_gram), 0) as total_gram'),
                DB::raw('SUM(combined.total_pcs) as total_pcs'),
                DB::raw('SUM(combined.total_box) - COALESCE(MAX(graded.used_box), 0) as total_box')
            )
            ->groupBy('combined.barang_id', 'nama_barang.nama_barang')
            ->having(DB::raw('SUM(combined.total_gram) - COALESCE(MAX(graded.used_gram), 0)'), '>', 0);

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
            ->select('barang_id', DB::raw('SUM(gram) as total_gram'), DB::raw('SUM(pcs) as total_pcs'), DB::raw('SUM(box) as total_box'))
            ->where('barang_id', $barangId)->groupBy('barang_id');

        $jasaCuci = DB::table('barang_masuk_jasa_cuci')
            ->select('barang_id', DB::raw('SUM(berat_timbangan_gudang) as total_gram'), DB::raw('COUNT(id) as total_pcs'), DB::raw('0 as total_box'))
            ->where('barang_id', $barangId)->groupBy('barang_id');

        $supplier = DB::table('barang_masuk_supplier')
            ->select('barang_id', DB::raw('SUM(berat) as total_gram'), DB::raw('SUM(COALESCE(pcs, 0)) as total_pcs'), DB::raw('SUM(box) as total_box'))
            ->where('barang_id', $barangId)->groupBy('barang_id');

        $unionQuery = $demak->unionAll($jasaCuci)->unionAll($supplier);

        $graded = DB::table('stok_gradings')
            ->select(DB::raw('SUM(total_gram_keseluruhan) as used_gram'), DB::raw('SUM(box_awal) as used_box'))
            ->where('barang_id', $barangId)->whereNull('deleted_at')->first();
            
        $usedGram = $graded->used_gram ?? 0;
        $usedBox = $graded->used_box ?? 0;

        $result = DB::query()->fromSub($unionQuery, 'combined')
            ->select(DB::raw('SUM(total_gram) as total_gram'), DB::raw('SUM(total_pcs) as total_pcs'), DB::raw('SUM(total_box) as total_box'))
            ->first();
            
        if ($result) {
            $result->total_gram = max(0, $result->total_gram - $usedGram);
            $result->total_pcs = max(0, $result->total_pcs);
            $result->total_box = max(0, $result->total_box - $usedBox);
        }
        
        return $result;
    }

    /**
     * Get detail of incoming global stock for a specific material combining all sources.
     */
    public function getGlobalDetail($barangId)
    {
        $demak = DB::table('barang_masuk_demak')
            ->join('barang_masuk', 'barang_masuk_demak.barang_masuk_id', '=', 'barang_masuk.id')
            ->select(
                'barang_masuk.tanggal_terima', DB::raw("'Demak' as sumber"),
                'barang_masuk_demak.partai as identifier',
                'barang_masuk_demak.gram as total_gram', 'barang_masuk_demak.pcs as total_pcs', 'barang_masuk_demak.box as total_box'
            )->where('barang_masuk_demak.barang_id', $barangId);

        $jasaCuci = DB::table('barang_masuk_jasa_cuci')
            ->join('barang_masuk', 'barang_masuk_jasa_cuci.barang_masuk_id', '=', 'barang_masuk.id')
            ->join('kode_bahan', 'barang_masuk_jasa_cuci.kode_bahan_id', '=', 'kode_bahan.id')
            ->select(
                'barang_masuk.tanggal_terima', DB::raw("'Jasa Cuci' as sumber"),
                'kode_bahan.nama as identifier',
                'barang_masuk_jasa_cuci.berat_timbangan_gudang as total_gram', DB::raw("1 as total_pcs"), DB::raw("0 as total_box")
            )->where('barang_masuk_jasa_cuci.barang_id', $barangId);

        $supplier = DB::table('barang_masuk_supplier')
            ->join('barang_masuk', 'barang_masuk_supplier.barang_masuk_id', '=', 'barang_masuk.id')
            ->join('supplier', 'barang_masuk_supplier.supplier_id', '=', 'supplier.id')
            ->select(
                'barang_masuk.tanggal_terima', DB::raw("'Pembelian Supplier' as sumber"),
                'supplier.nama as identifier',
                'barang_masuk_supplier.berat as total_gram', DB::raw("COALESCE(barang_masuk_supplier.pcs, 0) as total_pcs"), 'barang_masuk_supplier.box as total_box'
            )->where('barang_masuk_supplier.barang_id', $barangId);

        return $demak->unionAll($jasaCuci)->unionAll($supplier)
            ->orderBy('tanggal_terima', 'desc')
            ->get();
    }
}
