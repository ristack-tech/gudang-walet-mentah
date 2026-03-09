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
    public function transferToClop($globalId, $jumlah, $berat)
    {
        return DB::transaction(function () use ($globalId, $jumlah, $berat) {
            
            // This is a simplified transfer logic. 
            // Depending on the exact rules:
            // 1. You may need to deduct from the global stock record
            // 2. You may need to create a new "clop" stock record
            
            // Example deduction (assuming we are deducting from a specific detail of the global):
            $globalDetail = StokBarangJadiDetail::findOrFail($globalId);
            
            if ($globalDetail->jumlah_pcs < $jumlah || $globalDetail->berat < $berat) {
                throw new \Exception('Stok tidak mencukupi untuk ditransfer.');
            }

            // Deduct
            $globalDetail->update([
                'jumlah_pcs' => $globalDetail->jumlah_pcs - $jumlah,
                'berat' => $globalDetail->berat - $berat,
            ]);

            // Add new "clop" record 
            $clopStok = StokBarangJadi::create([
                'batch' => 'TRF-CLOP-' . time(),
                'tanggal' => now()->toDateString(),
                'type' => 'clop',
                'keterangan' => 'Transfer dari Global ID: ' . $globalDetail->id,
            ]);

            StokBarangJadiDetail::create([
                'stok_barang_jadi_id' => $clopStok->id,
                'nama_barang' => $globalDetail->nama_barang, // Cloned name
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
