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
            'items.*.is_checked' => 'nullable',
            'items.*.stok_id' => 'required_with:items.*.is_checked|exists:stok_barang_jadi_details,id',
            'items.*.jumlah_box' => 'required_with:items.*.is_checked|integer|min:1',
            'items.*.berat' => 'required_with:items.*.is_checked|numeric|min:0',
            'items.*.harga' => 'nullable|numeric|min:0',
        ]);

        $processedItems = [];
        foreach ($validated['items'] as $item) {
            if (!isset($item['is_checked']) || $item['is_checked'] != '1') {
                continue;
            }

            $jumlahBox = intval($item['jumlah_box'] ?? 0);
            if ($jumlahBox <= 0) {
                continue;
            }

            $beratPerBox = floatval($item['berat'] ?? 0);
            $totalBerat = $jumlahBox * $beratPerBox;

            $processedItems[] = [
                'stok_id' => $item['stok_id'],
                'jumlah_box' => $jumlahBox,
                'berat' => $totalBerat,
                'berat_per_box' => $beratPerBox,
                'harga' => $jumlahBox * floatval($item['harga'] ?? 0),
            ];
        }

        if (empty($processedItems)) {
            return redirect()->back()->withInput()->with('error', 'Silakan pilih minimal 1 box untuk diekspor.');
        }

        // Use transaction via Service
        $saveData = [
            'tanggal_transfer' => $validated['tanggal_transfer'],
            'keterangan' => $validated['keterangan'],
            'items' => $processedItems
        ];

        try {
            $this->exportService->storeExport($saveData);
            return redirect()->route('export.index')->with('success', 'Transaksi Export Berhasil Disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $export = Export::with('exportDetails.stokBarangJadiDetail')->findOrFail($id);
        return view('admin.export.show', compact('export'));
    }

    public function destroy($id)
    {
        try {
            $this->exportService->delete($id);
            return redirect()->route('export.index')->with('success', 'Data export berhasil dibatalkan (void). Stok gudang telah kembali secara otomatis.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
