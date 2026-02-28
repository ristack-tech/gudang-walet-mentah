<?php

namespace App\Http\Controllers\Admin\Transaksi\BarangMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BarangMasukJasaCuci;
use App\Models\NamaBarang;
use App\Services\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciService;

class BarangMasukJasaCuciController extends Controller
{
    public function index(Request $request)
    {
        $jenisJasaCuci = \App\Models\JenisBarangMasuk::firstOrCreate(['nama' => 'Jasa Cuci']);

        $query = \App\Models\BarangMasuk::where('jenis_barang_masuk_id', $jenisJasaCuci->id)
            ->with(['barangMasukJasaCuci.barang', 'barangMasukJasaCuci.lokasiJasaCuci', 'barangMasukJasaCuci.kodeBahan'])
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('barangMasukJasaCuci', function ($q) use ($search) {
                $q->whereHas('barang', function ($q2) use ($search) {
                        $q2->where('nama_barang', 'like', "%{$search}%");
                    }
                    );
                });
        }

        $barangMasukBatch = $query->paginate(10);

        return view('admin.barang-masuk.jasa-cuci.index', compact('barangMasukBatch'));
    }

    public function createStep1(Request $request)
    {
        $lokasiList = \App\Models\Lokasi::where('is_jasa_cuci', true)->get();
        $step1Data = session('barang_masuk_jasa_cuci_step1', []);

        return view('admin.barang-masuk.jasa-cuci.step1', compact('lokasiList', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'lokasi_jasa_cuci_id' => 'required|exists:lokasi,id',
        ]);

        session(['barang_masuk_jasa_cuci_step1' => $validated]);

        // Initialize empty session items array
        if (!session()->has('barang_masuk_jasa_cuci_items')) {
            session(['barang_masuk_jasa_cuci_items' => []]);
        }

        return redirect()->route('barang-masuk.jasa-cuci.create.step2');
    }

    public function createStep2(Request $request)
    {
        if (!session()->has('barang_masuk_jasa_cuci_step1')) {
            return redirect()->route('barang-masuk.jasa-cuci.create.step1')
                ->with('error', 'Silakan lengkapi Data Awal terlebih dahulu.');
        }

        $step1Data = session('barang_masuk_jasa_cuci_step1');
        $lokasiJasaCuci = \App\Models\Lokasi::find($step1Data['lokasi_jasa_cuci_id']);
        $items = session('barang_masuk_jasa_cuci_items', []);

        $barang = NamaBarang::all();
        $kodeBahan = \App\Models\KodeBahan::all();

        return view('admin.barang-masuk.jasa-cuci.step2', compact('step1Data', 'lokasiJasaCuci', 'items', 'barang', 'kodeBahan'));
    }

    public function addStep2Item(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:nama_barang,id',
            'kode_bahan_id' => 'required|exists:kode_bahan,id',
            'berat_per_pcs' => 'required|numeric',
            'persentase_susut' => 'required|numeric',
            'kadar_air' => 'required|numeric',
        ]);

        $items = session('barang_masuk_jasa_cuci_items', []);

        // Populate display data
        $namaBarang = NamaBarang::find($validated['barang_id'])->nama_barang;
        $namaKodeBahan = \App\Models\KodeBahan::find($validated['kode_bahan_id'])->nama;
        $validated['nama_barang'] = $namaBarang;
        $validated['nama_kode_bahan'] = $namaKodeBahan;

        $items[] = $validated;
        session(['barang_masuk_jasa_cuci_items' => $items]);

        return redirect()->route('barang-masuk.jasa-cuci.create.step2')->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function removeStep2Item(Request $request, $index)
    {
        $items = session('barang_masuk_jasa_cuci_items', []);

        if (isset($items[$index])) {
            unset($items[$index]);
            $items = array_values($items);
            session(['barang_masuk_jasa_cuci_items' => $items]);
            return redirect()->route('barang-masuk.jasa-cuci.create.step2')->with('success', 'Barang dihapus dari keranjang.');
        }

        return redirect()->route('barang-masuk.jasa-cuci.create.step2')->with('error', 'Barang tidak ditemukan.');
    }

    public function storeStep2(Request $request, BarangMasukJasaCuciService $service)
    {
        $step1Data = session('barang_masuk_jasa_cuci_step1');
        $items = session('barang_masuk_jasa_cuci_items', []);

        if (!$step1Data) {
            return redirect()->route('barang-masuk.jasa-cuci.create.step1')
                ->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        if (empty($items)) {
            return redirect()->route('barang-masuk.jasa-cuci.create.step2')
                ->with('error', 'Tidak ada barang di dalam keranjang, silakan tambah minimal 1 barang.');
        }

        try {
            $service->storeData($step1Data, $items);
            session()->forget('barang_masuk_jasa_cuci_step1');
            session()->forget('barang_masuk_jasa_cuci_items');
            return redirect()->route('barang-masuk.jasa-cuci.index')
                ->with('success', 'Data Batch Barang Masuk (Jasa Cuci) berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukJasaCuci.barang', 'barangMasukJasaCuci.lokasiJasaCuci', 'barangMasukJasaCuci.kodeBahan'])->findOrFail($id);
        return view('admin.barang-masuk.jasa-cuci.show', compact('barangMasukBatch'));
    }

    public function edit($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukJasaCuci.barang', 'barangMasukJasaCuci.lokasiJasaCuci', 'barangMasukJasaCuci.kodeBahan'])->findOrFail($id);
        $barang = NamaBarang::all();
        $kodeBahan = \App\Models\KodeBahan::all();
        $lokasiList = \App\Models\Lokasi::where('is_jasa_cuci', true)->get();

        return view('admin.barang-masuk.jasa-cuci.edit', compact('barangMasukBatch', 'barang', 'kodeBahan', 'lokasiList'));
    }

    public function update(Request $request, $id, BarangMasukJasaCuciService $service)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'lokasi_jasa_cuci_id' => 'required|exists:lokasi,id',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:nama_barang,id',
            'items.*.kode_bahan_id' => 'required|exists:kode_bahan,id',
            'items.*.berat_per_pcs' => 'required|numeric',
            'items.*.persentase_susut' => 'required|numeric',
            'items.*.kadar_air' => 'required|numeric',
        ]);

        $barangMasukBatch = \App\Models\BarangMasuk::findOrFail($id);

        try {
            $service->updateData($barangMasukBatch, $validated);
            return redirect()->route('barang-masuk.jasa-cuci.index')
                ->with('success', 'Data Batch Barang Masuk (Jasa Cuci) berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id, BarangMasukJasaCuciService $service)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::findOrFail($id);

        try {
            $service->destroyData($barangMasukBatch);
            return redirect()->route('barang-masuk.jasa-cuci.index')
                ->with('success', 'Data Batch Barang Masuk (Jasa Cuci) berhasil dihapus.');
        }
        catch (\Exception $e) {
            return redirect()->route('barang-masuk.jasa-cuci.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
