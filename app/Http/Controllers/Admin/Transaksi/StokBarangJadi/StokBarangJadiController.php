<?php

namespace App\Http\Controllers\Admin\Transaksi\StokBarangJadi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\Transaksi\StokBarangJadi\StokBarangJadiService;
use App\Models\StokBarangJadi;

class StokBarangJadiController extends Controller
{
    protected $stokBarangJadiService;

    public function __construct(StokBarangJadiService $stokBarangJadiService)
    {
        $this->stokBarangJadiService = $stokBarangJadiService;
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'clop'); // default to clop

        // For the transfer modal, always provide available specific grading details
        $stokGlobal = \App\Models\StokGradingDetail::with('stokGrading')
            ->where('berat', '>', 0)
            ->get();

        if ($tab === 'global') {
            // Global items listed in the table comes from completed Stok Grading batches
            $items = \App\Models\StokGrading::with('details')->orderBy('tanggal', 'desc')->paginate(10);
        } else {
            // Clop items are handled normally via StokBarangJadi table
            $items = $this->stokBarangJadiService->getPaginatedStok('clop', 10);
        }

        return view('admin.stok-barang-jadi.index', compact('items', 'tab', 'stokGlobal'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'global_id' => 'required|exists:stok_grading_details,id',
            'jumlah' => 'required|integer|min:1',
            'berat' => 'required|numeric|min:0.1',
        ]);

        $stokGradingDetailId = $request->input('global_id');
        $jumlah = $request->input('jumlah');
        $berat = $request->input('berat');
        
        try {
            $this->stokBarangJadiService->transferToClop($stokGradingDetailId, $jumlah, $berat);
            return redirect()->route('stok-barang-jadi.index', ['tab' => 'clop'])->with('success', 'Transfer ke IDM berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->stokBarangJadiService->deleteStok($id);
        
        return redirect()->back()->with('success', 'Data stok barang jadi berhasil dihapus.');
    }
}
