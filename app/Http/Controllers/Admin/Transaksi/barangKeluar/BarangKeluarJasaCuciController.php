<?php

namespace App\Http\Controllers\Admin\Transaksi\barangKeluar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluarJasaCuci;
use App\Models\BarangKeluar;
use App\Models\Lokasi;
use App\Models\KodeBahan;
use App\Services\Admin\Transaksi\barangKeluar\BarangKeluarJasaCuciService;

class BarangKeluarJasaCuciController extends Controller
{
    protected $service;

    public function __construct(BarangKeluarJasaCuciService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $jenisJasaCuci = \App\Models\JenisBarangKeluar::firstOrCreate(['nama' => 'Jasa Cuci']);

        $query = BarangKeluar::where('jenis_barang_keluar_id', $jenisJasaCuci->id)
            ->with(['lokasi', 'barangKeluarJasaCuci']);

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('barangKeluarJasaCuci', function ($q) use ($request) {
                $q->whereHas('kodeBahan', function ($qb) use ($request) {
                    $qb->where('nama', 'like', '%' . $request->search . '%');
                });
            });
        }

        $data = $query->latest()->paginate(10);

        return view('admin.barang-keluar.jasa-cuci.index', compact('data'));
    }

    public function step1()
    {
        $lokasis = Lokasi::where('is_jasa_cuci', true)->get();
        $step1Data = session('jasa_cuci_step1', []);
        return view('admin.barang-keluar.jasa-cuci.step1', compact('lokasis', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'lokasi_id' => 'required|exists:lokasi,id'
        ]);

        session(['jasa_cuci_step1' => $request->only('tanggal_kirim', 'lokasi_id')]);

        if (!session()->has('jasa_cuci_items')) {
            session(['jasa_cuci_items' => []]);
        }

        return redirect()->route('barang-keluar.jasa-cuci.step2');
    }

    public function step2()
    {
        if (!session()->has('jasa_cuci_step1')) {
            return redirect()->route('barang-keluar.jasa-cuci.step1')->with('error', 'Silakan isi step 1 terlebih dahulu.');
        }

        $step1Data = session('jasa_cuci_step1');
        $lokasi = Lokasi::find($step1Data['lokasi_id']);
        $kodeBahans = KodeBahan::all();
        $items = session('jasa_cuci_items', []);

        return view('admin.barang-keluar.jasa-cuci.step2', compact('kodeBahans', 'items', 'lokasi', 'step1Data'));
    }

    public function addStep2Item(Request $request)
    {
        $validated = $request->validate([
            'kode_bahan' => 'required|string',
            'keterangan_cetak' => 'nullable|string',
            'berat' => 'required|numeric',
            'pcs' => 'nullable|numeric',
            'kadar_air' => 'nullable|numeric',
        ]);

        $items = session('jasa_cuci_items', []);

        $items[] = $validated;
        session(['jasa_cuci_items' => $items]);

        return redirect()->route('barang-keluar.jasa-cuci.step2')->with('success', 'Barang ditambahkan ke daftar.');
    }

    public function removeStep2Item(Request $request, $index)
    {
        $items = session('jasa_cuci_items', []);

        if (isset($items[$index])) {
            unset($items[$index]);
            $items = array_values($items);
            session(['jasa_cuci_items' => $items]);
            return redirect()->route('barang-keluar.jasa-cuci.step2')->with('success', 'Barang dihapus dari daftar.');
        }

        return redirect()->route('barang-keluar.jasa-cuci.step2')->with('error', 'Barang tidak ditemukan.');
    }

    public function storeStep2(Request $request)
    {
        $step1Data = session('jasa_cuci_step1');
        $items = session('jasa_cuci_items', []);

        if (!$step1Data) {
            return redirect()->route('barang-keluar.jasa-cuci.step1')->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        if (empty($items)) {
            return redirect()->route('barang-keluar.jasa-cuci.step2')->with('error', 'Tidak ada barang di dalam daftar, silakan tambah minimal 1 barang.');
        }

        try {
            $this->service->store($step1Data, $items);
            session()->forget('jasa_cuci_step1');
            session()->forget('jasa_cuci_items');
            return redirect()->route('barang-keluar.jasa-cuci.index')->with('success', 'Data Batch berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = BarangKeluar::with(['lokasi', 'barangKeluarJasaCuci'])->findOrFail($id);
        return view('admin.barang-keluar.jasa-cuci.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BarangKeluar::with(['barangKeluarJasaCuci'])->findOrFail($id);
        $lokasis = Lokasi::where('is_jasa_cuci', true)->get();
        $kodeBahans = KodeBahan::all();
        return view('admin.barang-keluar.jasa-cuci.edit', compact('data', 'lokasis', 'kodeBahans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'lokasi_id' => 'required|exists:lokasi,id',
            'items' => 'required|array|min:1',
            'items.*.kode_bahan' => 'required|string',
            'items.*.keterangan_cetak' => 'nullable|string',
            'items.*.berat' => 'required|numeric',
            'items.*.pcs' => 'nullable|numeric',
            'items.*.kadar_air' => 'nullable|numeric',
        ]);

        try {
            $this->service->update($id, $request->all());
            return redirect()->route('barang-keluar.jasa-cuci.index')->with('success', 'Data Batch berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $batch = BarangKeluar::with('barangKeluarJasaCuci')->findOrFail($id);
            $hasReturned = $batch->barangKeluarJasaCuci()->where('status_kembali', true)->exists();
            if ($hasReturned) {
                return back()->with('error', 'Gagal dihapus: Sebagian atau seluruh batch ini sudah diproses di Barang Masuk.');
            }

            $this->service->delete($id);
            return redirect()->route('barang-keluar.jasa-cuci.index')->with('success', 'Data Batch berhasil dihapus beserta seluruh isinya.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}