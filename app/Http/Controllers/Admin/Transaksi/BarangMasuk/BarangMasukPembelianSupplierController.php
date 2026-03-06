<?php

namespace App\Http\Controllers\Admin\Transaksi\BarangMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BarangMasukPembelianSupplier;
use App\Models\NamaBarang;
use App\Models\Supplier;
use App\Services\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierService;

class BarangMasukPembelianSupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\BarangMasuk::whereHas('jenisBarangMasuk', function ($q) {
            $q->where('nama', 'Pembelian Supplier');
        })
            ->with(['barangMasukSupplier.barang', 'barangMasukSupplier.supplier'])
            ->latest('tanggal_terima');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('barangMasukSupplier.barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            })->orWhereHas('barangMasukSupplier.supplier', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $barangMasukBatch = $query->paginate(10);

        return view('admin.barang-masuk.pembelian-supplier.index', compact('barangMasukBatch'));
    }

    public function createStep1(Request $request)
    {
        $supplier = Supplier::all();
        $step1Data = session('barang_masuk_supplier_step1', []);

        return view('admin.barang-masuk.pembelian-supplier.step1', compact('supplier', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
        ]);

        session(['barang_masuk_supplier_step1' => $validated]);

        if (!session()->has('barang_masuk_supplier_items')) {
            session(['barang_masuk_supplier_items' => []]);
        }

        return redirect()->route('barang-masuk.pembelian-supplier.create.step2');
    }

    public function createStep2(Request $request)
    {
        if (!session()->has('barang_masuk_supplier_step1')) {
            return redirect()->route('barang-masuk.pembelian-supplier.create.step1')
                ->with('error', 'Silakan lengkapi Data Awal terlebih dahulu.');
        }

        $step1Data = session('barang_masuk_supplier_step1');
        $supplier = Supplier::find($step1Data['supplier_id']);
        $barang = NamaBarang::all();
        $items = session('barang_masuk_supplier_items', []);

        return view('admin.barang-masuk.pembelian-supplier.step2', compact('step1Data', 'supplier', 'barang', 'items'));
    }

    public function addStep2Item(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:nama_barang,id',
            'berat' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
        ]);

        $barang = NamaBarang::find($validated['barang_id']);

        $item = [
            'barang_id' => $validated['barang_id'],
            'nama_barang' => $barang->nama_barang,
            'berat' => $validated['berat'],
            'pcs' => $validated['pcs'],
            'box' => $validated['box'],
        ];

        $items = session('barang_masuk_supplier_items', []);
        $items[] = $item;
        session(['barang_masuk_supplier_items' => $items]);

        return redirect()->route('barang-masuk.pembelian-supplier.create.step2')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function removeStep2Item($index)
    {
        $items = session('barang_masuk_supplier_items', []);

        if (strval($index) === 'all') {
            session(['barang_masuk_supplier_items' => []]);
        } elseif (isset($items[$index])) {
            unset($items[$index]);
            session(['barang_masuk_supplier_items' => array_values($items)]);
        }

        return redirect()->route('barang-masuk.pembelian-supplier.create.step2')
            ->with('success', strval($index) === 'all' ? 'Semua item berhasil dihapus.' : 'Item berhasil dihapus.');
    }

    public function storeStep2(Request $request, BarangMasukPembelianSupplierService $service)
    {
        $step1Data = session('barang_masuk_supplier_step1');
        $items = session('barang_masuk_supplier_items', []);

        if (!$step1Data || empty($items)) {
            return redirect()->route('barang-masuk.pembelian-supplier.create.step1')
                ->with('error', 'Data tidak lengkap atau sesi telah habis.');
        }

        try {
            $service->storeData($step1Data, $items);
            session()->forget(['barang_masuk_supplier_step1', 'barang_masuk_supplier_items']);
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('success', 'Data Batch Barang Masuk (Pembelian Supplier) berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukSupplier.barang', 'barangMasukSupplier.supplier'])->findOrFail($id);
        return view('admin.barang-masuk.pembelian-supplier.show', compact('barangMasukBatch'));
    }

    public function edit($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukSupplier.barang', 'barangMasukSupplier.supplier'])->findOrFail($id);
        $barang = NamaBarang::all();
        $supplier = Supplier::all();

        return view('admin.barang-masuk.pembelian-supplier.edit', compact('barangMasukBatch', 'barang', 'supplier'));
    }

    public function update(Request $request, $id, BarangMasukPembelianSupplierService $service)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:nama_barang,id',
            'items.*.berat' => 'required|numeric',
            'items.*.pcs' => 'required|integer',
            'items.*.box' => 'required|integer',
        ]);

        $barangMasukBatch = \App\Models\BarangMasuk::findOrFail($id);

        try {
            $service->updateData($barangMasukBatch, $validated);
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('success', 'Data Batch Barang Masuk (Pembelian Supplier) berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id, BarangMasukPembelianSupplierService $service)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::findOrFail($id);

        try {
            $service->destroyData($barangMasukBatch);
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('success', 'Data Batch Barang Masuk (Pembelian Supplier) berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
