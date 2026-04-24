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

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate batch number EX-YYYYMMDD-His
            $batch = 'EXP-' . date('Ymd-His');
            
            $export = Export::create([
                'batch' => $batch,
                'tanggal_transfer' => $data['tanggal_transfer'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                ExportDetail::create([
                    'export_id' => $export->id,
                    'stok_barang_jadi_detail_id' => $item['stok_id'],
                    'box' => $item['box'],
                    'pcs' => $item['pcs'] ?? 0,
                    'berat' => $item['berat'],
                    'harga' => $item['harga'] ?? 0,
                ]);
            }

            return $export;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $export = Export::findOrFail($id);
            // This will cascade soft deletes to details due to Model events if setup, 
            // but in Laravel we need to do it explicitly unless we use packages or DB onDelete cascade handles hard deletes.
            // Since we use soft deletes, let's explicitly soft delete children.
            $export->exportDetails()->delete();
            return $export->delete();
        });
    }
}
