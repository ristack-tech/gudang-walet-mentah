<?php

namespace App\Services\Admin\Master;

use App\Models\KodeBahan;

class KodeBahanService
{
    /**
     * Get all kode bahan with pagination and search.
     *
     * @param string|null $search
     * @param int $perPage
     */
    public function getAllKodeBahan($search = null, $perPage = 10)
    {
        $query = KodeBahan::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new kode bahan.
     *
     * @param array $data
     * @return KodeBahan
     */
    public function createKodeBahan(array $data)
    {
        return KodeBahan::create($data);
    }

    /**
     * Get a kode bahan by ID.
     *
     * @param int $id
     * @return KodeBahan
     */
    public function getKodeBahanById($id)
    {
        return KodeBahan::findOrFail($id);
    }

    /**
     * Update an existing kode bahan.
     *
     * @param int $id
     * @param array $data
     * @return KodeBahan
     */
    public function updateKodeBahan($id, array $data)
    {
        $kodeBahan = KodeBahan::findOrFail($id);
        $kodeBahan->update($data);

        return $kodeBahan;
    }

    /**
     * Delete a kode bahan.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteKodeBahan($id)
    {
        $kodeBahan = KodeBahan::findOrFail($id);
        return $kodeBahan->delete();
    }
}
