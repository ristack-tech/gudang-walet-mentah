<?php

namespace App\Http\Controllers\Admin\Transaksi\barangKeluar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluarDemak;
use App\Models\BarangKeluar;
use App\Models\Partai;
use App\Models\Warna;
use App\Services\Admin\Transaksi\barangKeluar\BarangKeluarDemakService;

class BarangKeluarDemakController extends Controller
{
    protected $service;

    public function __construct(BarangKeluarDemakService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = BarangKeluarDemak::with(['barangKeluar', 'partai']);

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_lp', 'like', '%' . $request->search . '%');
        }

        $data = $query->latest()->paginate(10);

        return view('admin.barang-keluar.demak.index', compact('data'));
    }

    public function step1()
    {
        $partais = Partai::all();
        return view('admin.barang-keluar.demak.step1', compact('partais'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'partai_id' => 'required|exists:partai,id'
        ]);

        session(['demak_step1' => $request->only('tanggal_kirim', 'partai_id')]);

        return redirect()->route('barang-keluar.demak.step2');
    }

    public function step2()
    {
        if (!session()->has('demak_step1')) {
            return redirect()->route('barang-keluar.demak.step1')->with('error', 'Silakan isi step 1 terlebih dahulu.');
        }

        $warnas = Warna::all();
        return view('admin.barang-keluar.demak.step2', compact('warnas'));
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'kode_lp' => 'required|string',
            'gram' => 'required|numeric',
            'pcs' => 'required|integer',
            'kadar_air' => 'nullable|numeric',
            'warna_id' => 'nullable|exists:warna,id',
            'timbangan_ulang' => 'nullable|numeric',
            'keterangan' => 'nullable|string'
        ]);

        $step1Data = session('demak_step1');

        try {
            $this->service->store($step1Data, $request->all());
            session()->forget('demak_step1');
            return redirect()->route('barang-keluar.demak.index')->with('success', 'Data berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = BarangKeluarDemak::with(['barangKeluar', 'partai', 'warna'])->findOrFail($id);
        return view('admin.barang-keluar.demak.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BarangKeluarDemak::with(['barangKeluar'])->findOrFail($id);
        $partais = Partai::all();
        $warnas = Warna::all();
        return view('admin.barang-keluar.demak.edit', compact('data', 'partais', 'warnas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'partai_id' => 'required|exists:partai,id',
            'kode_lp' => 'required|string',
            'gram' => 'required|numeric',
            'pcs' => 'required|integer',
            'kadar_air' => 'nullable|numeric',
            'warna_id' => 'nullable|exists:warna,id',
            'timbangan_ulang' => 'nullable|numeric',
            'keterangan' => 'nullable|string'
        ]);

        try {
            $this->service->update($id, $request->all());
            return redirect()->route('barang-keluar.demak.index')->with('success', 'Data berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('barang-keluar.demak.index')->with('success', 'Data berhasil dihapus.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}