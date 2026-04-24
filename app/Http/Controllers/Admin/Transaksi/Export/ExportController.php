<?php

namespace App\Http\Controllers\Admin\Transaksi\Export;

use App\Http\Controllers\Controller;
use App\Models\Export;
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

    public function createStep1(Request $request)
    {
        return view('admin.export.create-step-1');
    }

    public function createStep2(Request $request)
    {
        if (!$request->has('tanggal_transfer')) {
            return redirect()->route('export.create.step1')->with('error', 'Silahkan tentukan tanggal pengeluaran export terlebih dahulu.');
        }

        $tanggalTransfer = $request->query('tanggal_transfer');
        $keterangan = $request->query('keterangan');

        $availableStocks = $this->stokBarangJadiService->getAvailableClopStocks();

        return view('admin.export.create-step-2', compact('tanggalTransfer', 'keterangan', 'availableStocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.stok_id' => 'required|exists:stok_barang_jadi_details,id',
            'items.*.box' => 'required|integer|min:0',
            'items.*.pcs' => 'nullable|integer|min:0',
            'items.*.berat' => 'required|numeric|min:0.01',
            'items.*.harga' => 'nullable|numeric|min:0',
        ]);

        // Re-validate against available stock
        $availableStocks = $this->stokBarangJadiService->getAvailableClopStocks();
        
        foreach ($validated['items'] as $index => $item) {
            $stokId = $item['stok_id'];
            if (!isset($availableStocks[$stokId])) {
                return redirect()->back()->withInput()->with('error', "Stok untuk barang baris ke-" . ($index + 1) . " tidak tersedia.");
            }

            $currentStock = $availableStocks[$stokId];
            if ($item['berat'] > $currentStock['available_berat']) {
                return redirect()->back()->withInput()->with('error', "Berat yang diminta pada {$currentStock['nama_barang']} ({$item['berat']}g) melebihi stok yang tersedia ({$currentStock['available_berat']}g).");
            }
            if (isset($item['pcs']) && $item['pcs'] > $currentStock['available_pcs']) {
                return redirect()->back()->withInput()->with('error', "Pcs yang diminta pada {$currentStock['nama_barang']} ({$item['pcs']}) melebihi stok yang tersedia ({$currentStock['available_pcs']} pcs).");
            }
        }

        $export = $this->exportService->create($validated);

        return redirect()->route('export.index')->with('success', "Data export {$export->batch} berhasil ditambahkan secara batch.");
    }

    public function show($id)
    {
        $export = Export::with('exportDetails.stokBarangJadiDetail')->findOrFail($id);
        return view('admin.export.show', compact('export'));
    }

    public function destroy($id)
    {
        $this->exportService->delete($id);

        return redirect()->route('export.index')->with('success', 'Data export berhasil dibatalkan (void). Stok gudang telah kembali secara otomatis.');
    }
}
