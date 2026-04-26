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
            // Only show those that still have remaining stock
            $items = \App\Models\StokGrading::with(['details', 'namaBarang'])
                ->whereHas('details', function ($query) {
                    $query->where('berat', '>', 0);
                })
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        } else {
            // Clop items are handled normally via StokBarangJadi table
            $items = $this->stokBarangJadiService->getPaginatedStok('clop', 10);
        }

        return view('admin.stok-barang-jadi.index', compact('items', 'tab', 'stokGlobal'));
    }

    public function transfer(Request $request)
    {
        $tipeBox = $request->input('tipe_box');
        $request->validate([
            'global_id' => 'required|exists:stok_grading_details,id',
            'tipe_box' => 'required|integer|in:500,475',
            'berat' => 'required|numeric|min:' . ($tipeBox == 475 ? 475 : 500),
        ], [
            'berat.min' => 'Berat minimal untuk transfer jenis kemasan (Box ' . $tipeBox . 'gr) adalah ' . $tipeBox . ' gram.'
        ]);

        $stokGradingDetailId = $request->input('global_id');
        $tipeBox = $request->input('tipe_box');
        $berat = $request->input('berat');
        
        try {
            $stok = $this->stokBarangJadiService->transferToClop(
                $request->global_id,
                $request->tipe_box,
                $request->berat
            );
            return redirect()->route('stok-barang-jadi.index', ['tab' => 'clop'])->with('success', 'Transfer ke IDM berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $stokBarangJadi = StokBarangJadi::with(['details.namaBarang'])->findOrFail($id);
        return view('admin.stok-barang-jadi.show', compact('stokBarangJadi'));
    }

    public function edit($id)
    {
        $stokBarangJadi = StokBarangJadi::with(['details.namaBarang'])->findOrFail($id);
        return view('admin.stok-barang-jadi.edit', compact('stokBarangJadi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'details' => 'required|array',
            'details.*.id' => 'required|exists:stok_barang_jadi_details,id',
            'details.*.jumlah_box' => 'required|integer|min:0',
            'details.*.berat' => 'required|numeric|min:0',
        ]);

        try {
            $this->stokBarangJadiService->updateStok($id, $request->all());
            return redirect()->route('stok-barang-jadi.index', ['tab' => 'clop'])->with('success', 'Data stok barang jadi berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $this->stokBarangJadiService->deleteStok($id);
        
        return redirect()->back()->with('success', 'Data stok barang jadi berhasil dihapus.');
    }
}
