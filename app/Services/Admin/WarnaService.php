<?php

namespace App\Services\Admin;

use App\Models\Warna;

class WarnaService
{
    /**
     * Get all warna with pagination and search.
     * 
     * @param string|null $search
     * @param int $perPage
     */
    public function getAllWarna($search = null, $perPage = 10)
    {
        $query = Warna::query();

        if ($search) {
            $query->where('kode_warna', 'like', '%' . $search . '%')
                  ->orWhere('warna', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new warna.
     *
     * @param array $data
     * @return Warna
     */
    public function createWarna(array $data)
    {
        return Warna::create($data);
    }

    /**
     * Get a warna by ID.
     *
     * @param int $id
     * @return Warna
     */
    public function getWarnaById($id)
    {
        return Warna::findOrFail($id);
    }

    /**
     * Update an existing warna.
     *
     * @param int $id
     * @param array $data
     * @return Warna
     */
    public function updateWarna($id, array $data)
    {
        $warna = Warna::findOrFail($id);
        $warna->update($data);

        return $warna;
    }

    /**
     * Delete a warna.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteWarna($id)
    {
        $warna = Warna::findOrFail($id);
        return $warna->delete();
    }
}
