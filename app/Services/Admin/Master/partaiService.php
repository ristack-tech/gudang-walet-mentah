<?php

namespace App\Services\Admin\Master;

use App\Models\Partai;

class PartaiService
{
    /**
     * Get all partai with pagination and search.
     *
     * @param string|null $search
     * @param int $perPage
     */
    public function getAllPartai($search = null, $perPage = 10)
    {
        $query = Partai::query();

        if ($search) {
            $query->where('nama_partai', 'like', '%' . $search . '%')
                ->orWhere('keterangan', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new partai.
     *
     * @param array $data
     * @return Partai
     */
    public function createPartai(array $data)
    {
        return Partai::create($data);
    }

    /**
     * Get a partai by ID.
     *
     * @param int $id
     * @return Partai
     */
    public function getPartaiById($id)
    {
        return Partai::findOrFail($id);
    }

    /**
     * Update an existing partai.
     *
     * @param int $id
     * @param array $data
     * @return Partai
     */
    public function updatePartai($id, array $data)
    {
        $partai = Partai::findOrFail($id);
        $partai->update($data);

        return $partai;
    }

    /**
     * Delete a partai.
     *
     * @param int $id
     * @return bool|null
     */
    public function deletePartai($id)
    {
        $partai = Partai::findOrFail($id);
        return $partai->delete();
    }
}
