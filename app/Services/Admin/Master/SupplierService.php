<?php

namespace App\Services\Admin\Master;

use App\Models\Supplier;

class SupplierService
{
    /**
     * Get all supplier with pagination and search.
     *
     * @param string|null $search
     * @param int $perPage
     */
    public function getAllSupplier($search = null, $perPage = 10)
    {
        $query = Supplier::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new supplier.
     *
     * @param array $data
     * @return Supplier
     */
    public function createSupplier(array $data)
    {
        return Supplier::create($data);
    }

    /**
     * Get a supplier by ID.
     *
     * @param int $id
     * @return Supplier
     */
    public function getSupplierById($id)
    {
        return Supplier::findOrFail($id);
    }

    /**
     * Update an existing supplier.
     *
     * @param int $id
     * @param array $data
     * @return Supplier
     */
    public function updateSupplier($id, array $data)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);

        return $supplier;
    }

    /**
     * Delete a supplier.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return $supplier->delete();
    }
}
