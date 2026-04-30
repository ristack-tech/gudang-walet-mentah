<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Admin\Transaksi\StokBarangJadi\StokBarangJadiService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class StokBarangJadiController extends Controller
{
    use ApiResponse;

    protected $stokBarangJadiService;

    public function __construct(StokBarangJadiService $stokBarangJadiService)
    {
        $this->stokBarangJadiService = $stokBarangJadiService;
    }

    public function index(Request $request)
    {
        $type = $request->input('type', 'global');
        $perPage = $request->input('per_page', 10);
        $stok = $this->stokBarangJadiService->getPaginatedStok($type, $perPage);
        return $this->paginate($stok, 'Stok Barang Jadi list retrieved');
    }

    public function show($id)
    {
        $stok = \App\Models\StokBarangJadi::with('details')->find($id);
        if (!$stok) {
            return $this->error('Stok Barang Jadi not found', 404);
        }
        return $this->success($stok, 'Stok Barang Jadi detail retrieved');
    }

    public function availableClopStocks()
    {
        $stok = $this->stokBarangJadiService->getAvailableClopStocks();
        return $this->success($stok, 'Available Clop stocks retrieved');
    }
}
