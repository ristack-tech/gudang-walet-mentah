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
        $query = BarangKeluarJasaCuci::with(['barangKeluar.lokasi', 'kodeBahan']);

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('kodeBahan', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->latest()->paginate(10);

        return view('admin.barang-keluar.jasa-cuci.index', compact('data'));
    }

    public function step1()
    {
        $lokasis = Lokasi::where('is_jasa_cuci', true)->get();
        return view('admin.barang-keluar.jasa-cuci.step1', compact('lokasis'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'lokasi_id' => 'required|exists:lokasi,id'
        ]);

        session(['jasa_cuci_step1' => $request->only('tanggal_kirim', 'lokasi_id')]);

        return redirect()->route('barang-keluar.jasa-cuci.step2');
    }

    public function step2()
    {
        if (!session()->has('jasa_cuci_step1')) {
            return redirect()->route('barang-keluar.jasa-cuci.step1')->with('error', 'Silakan isi step 1 terlebih dahulu.');
        }

        $kodeBahans = KodeBahan::all();
        return view('admin.barang-keluar.jasa-cuci.step2', compact('kodeBahans'));
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'kode_bahan_id' => 'required|exists:kode_bahan,id',
            'keterangan_cetak' => 'nullable|string',
            'berat_per_pcs' => 'required|numeric',
            'kadar_air' => 'nullable|numeric',
        ]);

        $step1Data = session('jasa_cuci_step1');

        try {
            $this->service->store($step1Data, $request->all());
            session()->forget('jasa_cuci_step1');
            return redirect()->route('barang-keluar.jasa-cuci.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = BarangKeluarJasaCuci::with(['barangKeluar.lokasi', 'kodeBahan'])->findOrFail($id);
        return view('admin.barang-keluar.jasa-cuci.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BarangKeluarJasaCuci::with(['barangKeluar'])->findOrFail($id);
        $lokasis = Lokasi::where('is_jasa_cuci', true)->get();
        $kodeBahans = KodeBahan::all();
        return view('admin.barang-keluar.jasa-cuci.edit', compact('data', 'lokasis', 'kodeBahans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'lokasi_id' => 'required|exists:lokasi,id',
            'kode_bahan_id' => 'required|exists:kode_bahan,id',
            'keterangan_cetak' => 'nullable|string',
            'berat_per_pcs' => 'required|numeric',
            'kadar_air' => 'nullable|numeric',
        ]);

        try {
            $this->service->update($id, $request->all());
            return redirect()->route('barang-keluar.jasa-cuci.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('barang-keluar.jasa-cuci.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
