<?php

namespace App\Services\Admin\Transaksi\StokBarangJadi;

use App\Models\StokBarangJadi;
use App\Models\StokBarangJadiDetail;
use Illuminate\Support\Facades\DB;

class StokBarangJadiService
{
    /**
     * Get Paginated Stok Barang Jadi
     */
    public function getPaginatedStok($type = 'global', $perPage = 10)
    {
        return StokBarangJadi::with('details')
            ->where('type', $type)
            ->orderBy('tanggal', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Store a new Stok Barang Jadi record.
     */
    public function storeStok(array $data)
    {
        return DB::transaction(function () use ($data) { // Keep data if making a manual generic form
            // Save parent record
            $stok = StokBarangJadi::create([
                'batch' => $data['batch'] ?? 'SBJ-' . time(),
                'tanggal' => $data['tanggal'] ?? now()->toDateString(),
                'type' => $data['type'] ?? 'global',
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Save details
            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    StokBarangJadiDetail::create([
                        'stok_barang_jadi_id' => $stok->id,
                        'nama_barang' => $item['nama_barang'],
                        'jumlah_pcs' => $item['jumlah_pcs'] ?? 0,
                        'berat' => $item['berat'] ?? 0,
                    ]);
                }
            }

            return $stok;
        });
    }

    /**
     * Transfer stock from global to clop. 
     */
    public function transferToClop($stokGradingDetailId, $jumlah, $berat)
    {
        return DB::transaction(function () use ($stokGradingDetailId, $jumlah, $berat) {
            
            // Find the specific graded detail
            $stokGradingDetail = \App\Models\StokGradingDetail::with('stokGrading')->findOrFail($stokGradingDetailId);
            
            if ($stokGradingDetail->berat < $berat || $stokGradingDetail->box < $jumlah) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$stokGradingDetail->berat}g, {$stokGradingDetail->box} pcs.");
            }

            // Deduct from the detail record
            $stokGradingDetail->update([
                'berat' => $stokGradingDetail->berat - $berat,
                'box' => $stokGradingDetail->box - $jumlah,
            ]);

            // Deduct from the overall global parent record
            $stokGrading = $stokGradingDetail->stokGrading;
            $stokGrading->update([
                'total_gram_keseluruhan' => max(0, $stokGrading->total_gram_keseluruhan - $berat),
                'total_box_keseluruhan' => max(0, $stokGrading->total_box_keseluruhan - $jumlah),
            ]);

            // Add new "clop" record 
            $clopStok = StokBarangJadi::create([
                'batch' => 'TRF-CLOP-' . time(),
                'tanggal' => now()->toDateString(),
                'type' => 'clop',
                'keterangan' => 'Transfer dari Global Stok Grading No Kartu: ' . $stokGrading->no_kartu . ' / ' . $stokGradingDetail->grade,
            ]);

            StokBarangJadiDetail::create([
                'stok_barang_jadi_id' => $clopStok->id,
                'nama_barang' => $stokGrading->no_kartu . ' - ' . $stokGradingDetail->grade, // Auto-named based on source
                'jumlah_pcs' => $jumlah,
                'berat' => $berat,
            ]);

            return $clopStok;
        });
    }

    /**
     * Delete a Stok Barang Jadi record.
     */
    public function deleteStok($id)
    {
        return DB::transaction(function () use ($id) {
            $stok = StokBarangJadi::findOrFail($id);
            $stok->details()->delete();
            $stok->delete();
            
            return true;
        });
    }
}
