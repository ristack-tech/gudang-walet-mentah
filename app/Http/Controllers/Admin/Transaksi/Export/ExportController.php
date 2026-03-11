<?php

namespace App\Http\Controllers\Admin\Transaksi\Export;

use App\Http\Controllers\Controller;
use App\Models\NamaBarang;
use App\Models\ExportBarang;
use App\Services\Admin\Transaksi\Export\ExportService;
use App\Services\Admin\Transaksi\StokBarangJadi\StokBarangJadiService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $exportService;
    protected $stokBarangJadiService;

    public function __construct(ExportService $exportService, StokBarangJadiService $stokBarangJadiService)
    {
        $this->exportService = $exportService;
        $this->stokBarangJadiService = $stokBarangJadiService;
    }

    public function index(Request $request)
    {
        $exports = $this->exportService->getAll($request->search);
        return view('admin.export.index', compact('exports'));
    }

    public function create()
    {
        $availableStocks = collect($this->stokBarangJadiService->getAvailableClopStocks());
        return view('admin.export.create', compact('availableStocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'stok_barang_jadi_detail_id' => 'required|exists:stok_barang_jadi_details,id',
            'berat' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $availableStocks = $this->stokBarangJadiService->getAvailableClopStocks();
        if (!isset($availableStocks[$validated['stok_barang_jadi_detail_id']])) {
            return redirect()->back()->withInput()->with('error', 'Stok Clop untuk barang ini tidak tersedia.');
        }

        $stock = $availableStocks[$validated['stok_barang_jadi_detail_id']];
        if ($validated['berat'] > $stock['available_berat']) {
            return redirect()->back()->withInput()->with('error', "Berat yang diminta ({$validated['berat']}g) melebihi stok Clop yang tersedia ({$stock['available_berat']}g).");
        }
        if ($validated['pcs'] > $stock['available_pcs']) {
            return redirect()->back()->withInput()->with('error', "Pcs yang diminta ({$validated['pcs']}) melebihi stok Clop yang tersedia ({$stock['available_pcs']} pcs).");
        }

        $this->exportService->create($validated);

        return redirect()->route('export.index')->with('success', 'Data export berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $export = ExportBarang::findOrFail($id);
        $availableStocks = collect($this->stokBarangJadiService->getAvailableClopStocks());
        
        // Add current export amount back to available for editing
        if ($availableStocks->has($export->stok_barang_jadi_detail_id)) {
            $stock = $availableStocks[$export->stok_barang_jadi_detail_id];
            $stock['available_berat'] += $export->berat;
            $stock['available_pcs'] += $export->pcs;
            $availableStocks[$export->stok_barang_jadi_detail_id] = $stock;
        } else {
            // If it wasn't available at all, recreate it with just this export's amount
            $availableStocks[$export->stok_barang_jadi_detail_id] = [
                'id' => $export->stok_barang_jadi_detail_id,
                'nama_barang' => $export->stokBarangJadiDetail->nama_barang ?? 'Unknown',
                'available_berat' => $export->berat,
                'available_pcs' => $export->pcs,
            ];
        }

        return view('admin.export.edit', compact('export', 'availableStocks'));
    }

    public function update(Request $request, $id)
    {
        $export = ExportBarang::findOrFail($id);

        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'stok_barang_jadi_detail_id' => 'required|exists:stok_barang_jadi_details,id',
            'berat' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $availableStocks = collect($this->stokBarangJadiService->getAvailableClopStocks());
        $currentAvailableBerat = 0;
        $currentAvailablePcs = 0;

        if ($availableStocks->has($validated['stok_barang_jadi_detail_id'])) {
            $currentAvailableBerat = $availableStocks[$validated['stok_barang_jadi_detail_id']]['available_berat'];
            $currentAvailablePcs = $availableStocks[$validated['stok_barang_jadi_detail_id']]['available_pcs'];
        }

        // if editing same barang, add current amounts back to available limits
        if ($export->stok_barang_jadi_detail_id == $validated['stok_barang_jadi_detail_id']) {
            $currentAvailableBerat += $export->berat;
            $currentAvailablePcs += $export->pcs;
        }

        if ($validated['berat'] > $currentAvailableBerat) {
            return redirect()->back()->withInput()->with('error', "Berat yang diminta ({$validated['berat']}g) melebihi stok Clop yang tersedia ({$currentAvailableBerat}g).");
        }
        if ($validated['pcs'] > $currentAvailablePcs) {
            return redirect()->back()->withInput()->with('error', "Pcs yang diminta ({$validated['pcs']}) melebihi stok Clop yang tersedia ({$currentAvailablePcs} pcs).");
        }

        $this->exportService->update($id, $validated);

        return redirect()->route('export.index')->with('success', 'Data export berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->exportService->delete($id);

        return redirect()->route('export.index')->with('success', 'Data export berhasil dihapus.');
    }
}
