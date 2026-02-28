<?php

namespace App\Http\Controllers\Admin\Transaksi\BarangMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BarangMasukDemak;
use App\Models\NamaBarang;
use App\Models\Partai;
use App\Services\Admin\Transaksi\BarangMasuk\BarangMasukDemakService;

class BarangMasukDemakController extends Controller
{
    public function index(Request $request)
    {
        // Query unique BarangMasuk based on Demak type
        $jenisDemak = \App\Models\JenisBarangMasuk::firstOrCreate(['nama' => 'Demak']);

        $query = \App\Models\BarangMasuk::where('jenis_barang_masuk_id', $jenisDemak->id)
            ->with(['barangMasukDemak.barang', 'barangMasukDemak.transaksiDari', 'barangMasukDemak.transaksiKe'])
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('barangMasukDemak', function ($q) use ($search) {
                // Modified search since partai is now string input
                $q->where('partai', 'like', "%{$search}%");
            });
        }

        $barangMasukBatch = $query->paginate(10);

        return view('admin.barang-masuk.demak.index', compact('barangMasukBatch'));
    }

    public function createStep1(Request $request)
    {
        $lokasi = \App\Models\Lokasi::where('is_jasa_cuci', false)->get();
        $step1Data = session('barang_masuk_demak_step1', []);

        return view('admin.barang-masuk.demak.step1', compact('lokasi', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'transaksi_dari_id' => 'required|exists:lokasi,id',
            'transaksi_ke_id' => 'required|exists:lokasi,id',
        ]);

        session(['barang_masuk_demak_step1' => $validated]);

        // Initialize an empty items array if not present
        if (!session()->has('barang_masuk_demak_items')) {
            session(['barang_masuk_demak_items' => []]);
        }

        return redirect()->route('barang-masuk.demak.create.step2');
    }

    public function createStep2(Request $request)
    {
        if (!session()->has('barang_masuk_demak_step1')) {
            return redirect()->route('barang-masuk.demak.create.step1')
                ->with('error', 'Silakan lengkapi Data Awal terlebih dahulu.');
        }

        $step1Data = session('barang_masuk_demak_step1');
        $transaksiDari = \App\Models\Lokasi::find($step1Data['transaksi_dari_id']);
        $transaksiKe = \App\Models\Lokasi::find($step1Data['transaksi_ke_id']);
        $items = session('barang_masuk_demak_items', []);

        $barang = NamaBarang::all();

        return view('admin.barang-masuk.demak.step2', compact('step1Data', 'transaksiDari', 'transaksiKe', 'items', 'barang'));
    }

    public function addStep2Item(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:nama_barang,id',
            'partai' => 'required|string',
            'gram' => 'required|numeric',
            'pcs' => 'required|integer',
            'box' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $items = session('barang_masuk_demak_items', []);

        // Populate display data (join with NamaBarang for display purposes in view)
        $namaBarang = NamaBarang::find($validated['barang_id'])->nama_barang;
        $validated['nama_barang'] = $namaBarang;

        $items[] = $validated;
        session(['barang_masuk_demak_items' => $items]);

        return redirect()->route('barang-masuk.demak.create.step2')->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function removeStep2Item(Request $request, $index)
    {
        $items = session('barang_masuk_demak_items', []);

        if (isset($items[$index])) {
            unset($items[$index]);
            // Re-index array
            $items = array_values($items);
            session(['barang_masuk_demak_items' => $items]);
            return redirect()->route('barang-masuk.demak.create.step2')->with('success', 'Barang dihapus dari keranjang.');
        }

        return redirect()->route('barang-masuk.demak.create.step2')->with('error', 'Barang tidak ditemukan.');
    }

    public function storeStep2(Request $request, BarangMasukDemakService $service)
    {
        $step1Data = session('barang_masuk_demak_step1');
        $items = session('barang_masuk_demak_items', []);

        if (!$step1Data) {
            return redirect()->route('barang-masuk.demak.create.step1')
                ->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        if (empty($items)) {
            return redirect()->route('barang-masuk.demak.create.step2')
                ->with('error', 'Tidak ada barang di dalam keranjang, silakan tambah minimal 1 barang.');
        }

        try {
            $service->storeData($step1Data, $items);
            session()->forget('barang_masuk_demak_step1');
            session()->forget('barang_masuk_demak_items');
            return redirect()->route('barang-masuk.demak.index')
                ->with('success', 'Data Batch Barang Masuk (Demak) berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukDemak.barang', 'barangMasukDemak.transaksiDari', 'barangMasukDemak.transaksiKe'])->findOrFail($id);
        return view('admin.barang-masuk.demak.show', compact('barangMasukBatch'));
    }

    public function edit($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukDemak.barang'])->findOrFail($id);
        $lokasi = \App\Models\Lokasi::where('is_jasa_cuci', false)->get();
        $barang = NamaBarang::all();

        return view('admin.barang-masuk.demak.edit', compact('barangMasukBatch', 'lokasi', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_terima' => 'required|date',
            'transaksi_dari_id' => 'required|exists:lokasi,id',
            'transaksi_ke_id' => 'required|exists:lokasi,id',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:nama_barang,id',
            'items.*.partai' => 'required|string',
            'items.*.gram' => 'required|numeric',
            'items.*.pcs' => 'required|integer',
            'items.*.box' => 'required|integer',
            'items.*.keterangan' => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $barangMasukBatch = \App\Models\BarangMasuk::findOrFail($id);
            $barangMasukBatch->update([
                'tanggal_terima' => $request->tanggal_terima,
            ]);

            // Clear existing items and recreate to handle additions/removals simply
            $barangMasukBatch->barangMasukDemak()->delete();

            foreach ($request->items as $itemData) {
                BarangMasukDemak::create([
                    'barang_masuk_id' => $barangMasukBatch->id,
                    'barang_id' => $itemData['barang_id'],
                    'partai' => $itemData['partai'],
                    'transaksi_dari_id' => $request->transaksi_dari_id,
                    'transaksi_ke_id' => $request->transaksi_ke_id,
                    'gram' => $itemData['gram'],
                    'pcs' => $itemData['pcs'],
                    'box' => $itemData['box'],
                    'keterangan' => $itemData['keterangan'] ?? null,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('barang-masuk.demak.index')
                ->with('success', 'Data Batch Barang Masuk (Demak) berhasil diperbarui.');
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $barangMasuk = \App\Models\BarangMasuk::findOrFail($id);

        try {
            $barangMasuk->delete(); // This will cascade delete the related BarangMasukDemak records
            return redirect()->route('barang-masuk.demak.index')
                ->with('success', 'Data Batch Barang Masuk (Demak) berhasil dihapus beserta seluruh isinya.');
        }
        catch (\Exception $e) {
            return redirect()->route('barang-masuk.demak.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
