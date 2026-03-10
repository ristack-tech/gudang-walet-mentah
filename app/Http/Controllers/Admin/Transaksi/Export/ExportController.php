<?php

namespace App\Http\Controllers\Admin\Transaksi\Export;

use App\Http\Controllers\Controller;
use App\Models\NamaBarang;
use App\Models\ExportBarang;
use App\Services\Admin\Transaksi\Export\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function index(Request $request)
    {
        $exports = $this->exportService->getAll($request->search);
        return view('admin.export.index', compact('exports'));
    }

    public function create()
    {
        $barangs = NamaBarang::all();
        return view('admin.export.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'barang_id' => 'required|exists:nama_barang,id',
            'berat' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $this->exportService->create($validated);

        return redirect()->route('export.index')->with('success', 'Data export berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $export = ExportBarang::findOrFail($id);
        $barangs = NamaBarang::all();
        return view('admin.export.edit', compact('export', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'barang_id' => 'required|exists:nama_barang,id',
            'berat' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $this->exportService->update($id, $validated);

        return redirect()->route('export.index')->with('success', 'Data export berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->exportService->delete($id);

        return redirect()->route('export.index')->with('success', 'Data export berhasil dihapus.');
    }
}
