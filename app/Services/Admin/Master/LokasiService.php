<?php

namespace App\Services\Admin\Master;

use App\Models\Lokasi;

class LokasiService
{
    /**
     * Get all lokasi.
     *
     * @param string|null $search
     * @param int $perPage
     */
    public function getAllLokasi($search = null, $perPage = 10)
    {
        $query = Lokasi::query();

        if ($search) {
            $query->where('nama_lokasi', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new lokasi.
     *
     * @param array $data
     * @return Lokasi
     */
    public function createLokasi(array $data)
    {
        return Lokasi::create($data);
    }

    /**
     * Get a lokasi by ID.
     *
     * @param int $id
     * @return Lokasi
     */
    public function getLokasiById($id)
    {
        return Lokasi::findOrFail($id);
    }

    /**
     * Update an existing lokasi.
     *
     * @param int $id
     * @param array $data
     * @return Lokasi
     */
    public function updateLokasi($id, array $data)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($data);

        return $lokasi;
    }

    /**
     * Delete a lokasi.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteLokasi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return $lokasi->delete();
    }
}
