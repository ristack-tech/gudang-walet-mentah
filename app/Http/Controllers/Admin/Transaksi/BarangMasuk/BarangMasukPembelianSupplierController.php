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
            ->with(['barangMasukPembelianSupplier.barang', 'barangMasukPembelianSupplier.supplier'])
            ->latest('tanggal_terima');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('barangMasukPembelianSupplier.barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            })->orWhereHas('barangMasukPembelianSupplier.supplier', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $barangMasukSupplier = $query->paginate(10);

        return view('admin.barang-masuk.pembelian-supplier.index', compact('barangMasukSupplier'));
    }

    public function createStep1(Request $request)
    {
        $barang = NamaBarang::all();
        $supplier = Supplier::all();
        $step1Data = session('barang_masuk_supplier_step1', []);

        return view('admin.barang-masuk.pembelian-supplier.step1', compact('barang', 'supplier', 'step1Data'));
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
        }
        elseif (isset($items[$index])) {
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
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukSupplier = BarangMasukPembelianSupplier::with(['barangMasuk', 'barang', 'supplier'])->findOrFail($id);
        return view('admin.barang-masuk.pembelian-supplier.show', compact('barangMasukSupplier'));
    }

    public function edit($id)
    {
        $barangMasukSupplier = BarangMasukPembelianSupplier::with(['barangMasuk'])->findOrFail($id);
        $barang = NamaBarang::all();
        $supplier = Supplier::all();

        return view('admin.barang-masuk.pembelian-supplier.edit', compact('barangMasukSupplier', 'barang', 'supplier'));
    }

    public function update(Request $request, $id, BarangMasukPembelianSupplierService $service)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'barang_id' => 'required|exists:nama_barang,id',
            'supplier_id' => 'required|exists:supplier,id',
            'berat' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
        ]);

        $barangMasukSupplier = BarangMasukPembelianSupplier::findOrFail($id);

        try {
            $service->updateData($barangMasukSupplier, $validated);
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('success', 'Data Barang Masuk (Pembelian Supplier) berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id, BarangMasukPembelianSupplierService $service)
    {
        $barangMasukSupplier = BarangMasukPembelianSupplier::findOrFail($id);

        try {
            $service->destroyData($barangMasukSupplier);
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('success', 'Data Barang Masuk (Pembelian Supplier) berhasil dihapus.');
        }
        catch (\Exception $e) {
            return redirect()->route('barang-masuk.pembelian-supplier.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
