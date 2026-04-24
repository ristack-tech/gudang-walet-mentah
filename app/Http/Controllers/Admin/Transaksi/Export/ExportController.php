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
            'items.*.harga' => 'nullable|numeric|min:0',
        ]);

        // Re-validate against available stock
        $availableStocks = $this->stokBarangJadiService->getAvailableClopStocks();
        
        $processedItems = [];
        foreach ($validated['items'] as $stokId => $item) {
            if (!isset($availableStocks[$stokId])) {
                return redirect()->back()->withInput()->with('error', "Stok untuk barang berisikan ID {$stokId} sudah tidak tersedia.");
            }

            $currentStock = $availableStocks[$stokId];
            $availableBox = (int) $currentStock['available_pcs']; // for clop stocks, available_pcs represents Box count
            $availableBerat = (float) $currentStock['available_berat'];

            if ($availableBox < 1) {
                return redirect()->back()->withInput()->with('error', "Box pada {$currentStock['nama_barang']} sudah tidak tersedia.");
            }

            // Since we are exporting exactly 1 box per selected card
            $processedItems[] = [
                'stok_id' => $stokId,
                'box' => 1,
                'pcs' => 1, // Store 1 to reduce available_pcs record correctly
                'berat' => round($availableBerat, 2),
                'harga' => $item['harga'] ?? 0,
            ];
        }

        // Use transaction via Service
        $saveData = [
            'tanggal_transfer' => $validated['tanggal_transfer'],
            'keterangan' => $validated['keterangan'],
            'items' => $processedItems
        ];
        
        $this->exportService->storeExport($saveData);

        return redirect()->route('export.index')->with('success', 'Transaksi Export Berhasil Disimpan.');
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
