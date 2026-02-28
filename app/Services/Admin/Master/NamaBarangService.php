<?php

namespace App\Services\Admin\Master;

use App\Models\NamaBarang;

class NamaBarangService
{
    /**
     * Get all nama barang with pagination and search.
     * 
     * @param string|null $search
     * @param int $perPage
     */
    public function getAllNamaBarang($search = null, $perPage = 10)
    {
        $query = NamaBarang::query();

        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new nama barang.
     *
     * @param array $data
     * @return NamaBarang
     */
    public function createNamaBarang(array $data)
    {
        return NamaBarang::create($data);
    }

    /**
     * Get a nama barang by ID.
     *
     * @param int $id
     * @return NamaBarang
     */
    public function getNamaBarangById($id)
    {
        return NamaBarang::findOrFail($id);
    }

    /**
     * Update an existing nama barang.
     *
     * @param int $id
     * @param array $data
     * @return NamaBarang
     */
    public function updateNamaBarang($id, array $data)
    {
        $namaBarang = NamaBarang::findOrFail($id);
        $namaBarang->update($data);

        return $namaBarang;
    }

    /**
     * Delete a nama barang.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteNamaBarang($id)
    {
        $namaBarang = NamaBarang::findOrFail($id);
        return $namaBarang->delete();
    }
}
