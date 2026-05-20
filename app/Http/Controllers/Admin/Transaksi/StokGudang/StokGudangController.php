<?php

namespace App\Http\Controllers\Admin\Transaksi\StokGudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokGudangController extends Controller
{
    protected $stokGudangService;

    public function __construct(\App\Services\Admin\Transaksi\StokGudang\StokGudangService $stokGudangService)
    {
        $this->stokGudangService = $stokGudangService;
    }

    public function index()
    {
        // Use service methods that account for grading deductions,
        // so fully-graded items are no longer counted
        $demakCount = collect($this->stokGudangService->getDemakSummary())->count();
        $jasaCuciCount = collect($this->stokGudangService->getJasaCuciSummary())->count();
        $globalCount = collect($this->stokGudangService->getGlobalSummary())->count();

        return view('admin.stok-gudang.index', compact('demakCount', 'jasaCuciCount', 'globalCount'));
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

    public function global(\Illuminate\Http\Request $request)
    {
        $globalStock = $this->stokGudangService->getGlobalSummary($request->search);

        return view('admin.stok-gudang.global', compact('globalStock'));
    }

    public function showGlobal($id)
    {
        $namaBarang = \App\Models\NamaBarang::findOrFail($id);
        $details = $this->stokGudangService->getGlobalDetail($id);
        $summary = $this->stokGudangService->getGlobalSummaryForBarang($id);

        return view('admin.stok-gudang.show-global', compact('namaBarang', 'details', 'summary'));
    }
}
