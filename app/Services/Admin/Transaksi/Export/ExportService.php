<?php

namespace App\Services\Admin\Transaksi\Export;

use App\Models\ExportBarang;

class ExportService
{
    public function getAll($search = null)
    {
        $query = ExportBarang::with('barang')->latest();

        if ($search) {
            $query->whereHas('barang', function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            });
        }

        return $query->paginate(10);
    }

    public function create(array $data)
    {
        return ExportBarang::create($data);
    }

    public function update($id, array $data)
    {
        $export = ExportBarang::findOrFail($id);
        $export->update($data);
        return $export;
    }

    public function delete($id)
    {
        $export = ExportBarang::findOrFail($id);
        return $export->delete();
    }
}
