<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Export;
use App\Services\Admin\Transaksi\Export\ExportService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ExportController extends Controller
{
    use ApiResponse;

    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $exports = $this->exportService->getAll($search);
        return $this->paginate($exports, 'Export list retrieved');
    }

    public function show($id)
    {
        $export = Export::with('exportDetails.stokBarangJadiDetail')->find($id);
        if (!$export) {
            return $this->error('Export not found', 404);
        }
        return $this->success($export, 'Export detail retrieved');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal_transfer' => 'required|date',
                'keterangan' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.stok_id' => 'required|exists:stok_barang_jadi_details,id',
                'items.*.box' => 'required|string',
                'items.*.berat' => 'required|numeric|min:0',
                'items.*.harga' => 'nullable|numeric',
            ]);

            $export = $this->exportService->storeExport($validated);
            return $this->success($export, 'Export created successfully', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->exportService->delete($id);
            return $this->success(null, 'Export deleted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
