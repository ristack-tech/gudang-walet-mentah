<?php

namespace App\Services\Admin\Transaksi\Export;

use App\Models\Export;
use App\Models\ExportDetail;
use Illuminate\Support\Facades\DB;

class ExportService
{
    public function getAll($search = null)
    {
        $query = Export::with('exportDetails.stokBarangJadiDetail')->latest();

        if ($search) {
            $query->where('batch', 'like', "%{$search}%")
                  ->orWhereHas('exportDetails.stokBarangJadiDetail', function($q) use ($search) {
                      $q->where('nama_barang', 'like', "%{$search}%");
                  });
        }

        return $query->paginate(10);
    }

    public function storeExport(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate batch number EX-YYYYMMDD-His
            $batch = 'EXP-' . date('Ymd-His') . '-' . \Illuminate\Support\Str::random(4);

            $export = Export::create([
                'batch' => $batch,
                'tanggal_transfer' => $data['tanggal_transfer'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $stokDetail = \App\Models\StokBarangJadiDetail::where('id', $item['stok_id'])->lockForUpdate()->first();
                if (!$stokDetail) {
                    throw new \Exception("Stok dengan ID {$item['stok_id']} tidak ditemukan.");
                }

                $jumlahBox = intval($item['jumlah_box'] ?? 0);
                $totalBerat = floatval($item['berat'] ?? 0);

                // Check sufficiency
                if ($stokDetail->jumlah_box < $jumlahBox || $stokDetail->berat < $totalBerat) {
                    throw new \Exception("Ketersediaan Stok Box atau Berat untuk {$stokDetail->nama_barang} tidak mencukupi.");
                }

                // Reduce stock
                $stokDetail->update([
                    'jumlah_box' => max(0, $stokDetail->jumlah_box - $jumlahBox),
                    'berat' => max(0, $stokDetail->berat - $totalBerat)
                ]);

                ExportDetail::create([
                    'export_id' => $export->id,
                    'stok_barang_jadi_detail_id' => $item['stok_id'],
                    'box' => $jumlahBox,
                    'pcs' => $jumlahBox,
                    'berat' => $totalBerat,
                    'harga' => $item['harga'] ?? 0,
                ]);
            }

            return $export;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $export = Export::with('exportDetails')->where('id', $id)->first();
            if (!$export) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Data Export tidak ditemukan.");
            }

            // Restore stock
            foreach ($export->exportDetails as $detail) {
                $stokDetail = \App\Models\StokBarangJadiDetail::where('id', $detail->stok_barang_jadi_detail_id)->lockForUpdate()->first();
                if ($stokDetail) {
                    $stokDetail->update([
                        'jumlah_box' => $stokDetail->jumlah_box + $detail->pcs,
                        'berat' => $stokDetail->berat + $detail->berat
                    ]);
                }
            }

            // This will cascade soft deletes to details due to Model events if setup, 
            // but in Laravel we need to do it explicitly unless we use packages or DB onDelete cascade handles hard deletes.
            // Since we use soft deletes, let's explicitly soft delete children.
            $export->exportDetails()->delete();
            return $export->delete();
        });
    }
}
