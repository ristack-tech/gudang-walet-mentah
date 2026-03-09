<?php

namespace App\Http\Controllers\Admin\Transaksi\StokGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokGudangController extends Controller
{
    protected $stokGudangService;

    public function __construct(\App\Services\StokGudang\StokGudangService $stokGudangService)
    {
        $this->stokGudangService = $stokGudangService;
    }

    public function index()
    {
        $demakCount = \App\Models\BarangMasukDemak::count();
        $jasaCuciCount = \App\Models\BarangMasukJasaCuci::count();

        return view('admin.stok-gudang.index', compact('demakCount', 'jasaCuciCount'));
    }

    public function demak(\Illuminate\Http\Request $request)
    {
        $demakStock = $this->stokGudangService->getDemakSummary($request->search);

        return view('admin.stok-gudang.demak', compact('demakStock'));
    }

    public function jasaCuci(\Illuminate\Http\Request $request)
    {
        $jasaCuciStock = $this->stokGudangService->getJasaCuciSummary($request->search);

        return view('admin.stok-gudang.jasa-cuci', compact('jasaCuciStock'));
    }

    public function showDemak($id)
    {
        $namaBarang = \App\Models\NamaBarang::findOrFail($id);
        $details = $this->stokGudangService->getDemakDetail($id);

        return view('admin.stok-gudang.show-demak', compact('namaBarang', 'details'));
    }

    public function showJasaCuci($id)
    {
        $namaBarang = \App\Models\NamaBarang::findOrFail($id);
        $details = $this->stokGudangService->getJasaCuciDetail($id);

        return view('admin.stok-gudang.show-jasa-cuci', compact('namaBarang', 'details'));
    }
}
