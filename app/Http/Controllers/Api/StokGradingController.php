<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StokGrading;
use App\Services\Admin\Transaksi\StokGrading\StokGradingService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class StokGradingController extends Controller
{
    use ApiResponse;

    protected $stokGradingService;

    public function __construct(StokGradingService $stokGradingService)
    {
        $this->stokGradingService = $stokGradingService;
    }

    public function index(Request $request)
    {
        $query = StokGrading::with('namaBarang')->orderBy('tanggal', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('namaBarang', function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            })->orWhere('no_kartu', 'like', "%{$search}%");
        }

        $stokGradingBatch = $query->paginate(10);
        return $this->paginate($stokGradingBatch, 'Stok Grading list retrieved');
    }

    public function show($id)
    {
        try {
            $gradingBatch = $this->stokGradingService->getGradingFormatted($id);
            return $this->success($gradingBatch, 'Stok Grading detail retrieved');
        } catch (\Exception $e) {
            return $this->error('Stok Grading not found', 404);
        }
    }
}
