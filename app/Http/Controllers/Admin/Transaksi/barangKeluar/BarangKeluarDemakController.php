<?php

namespace App\Http\Controllers\Admin\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluarDemak;

class BarangKeluarDemakController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluarDemak::with(['barangKeluar', 'partai', 'warna'])->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('partai', function ($q) use ($search) {
                $q->where('nama_partai', 'like', "%{$search}%");
            });
        }

        $barangKeluarDemak = $query->paginate(10);

        return view('admin.barang-keluar.demak.index', compact('barangKeluarDemak'));
    }

    public function createStep1(Request $request)
    {
        $partai = \App\Models\Partai::all();
        $step1Data = session('barang_keluar_demak_step1', []);

        return view('admin.barang-keluar.demak.step1', compact('partai', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_kirim' => 'required|date',
            'partai_id' => 'required|exists:partai,id',
        ]);

        session(['barang_keluar_demak_step1' => $validated]);

        return redirect()->route('barang-keluar.demak.create.step2');
    }

    public function createStep2(Request $request)
    {
        if (!session()->has('barang_keluar_demak_step1')) {
            return redirect()->route('barang-keluar.demak.create.step1')
                ->with('error', 'Silakan lengkapi Data Awal terlebih dahulu.');
        }

        $warna = \App\Models\Warna::all();
        $step1Data = session('barang_keluar_demak_step1');
        $partai = \App\Models\Partai::find($step1Data['partai_id']);

        return view('admin.barang-keluar.demak.step2', compact('warna', 'step1Data', 'partai'));
    }

    public function storeStep2(Request $request, \App\Services\Admin\Transaksi\BarangKeluarDemakService $service)
    {
        $validated = $request->validate([
            'warna_id' => 'required|exists:warna,id',
            'kode_lp' => 'nullable|string|max:255',
            'gram' => 'required|numeric',
            'pcs' => 'required|numeric',
            'kadar_air' => 'required|numeric',
            'timbangan_ulang' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $step1Data = session('barang_keluar_demak_step1');

        if (!$step1Data) {
            return redirect()->route('barang-keluar.demak.create.step1')
                ->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        $data = array_merge($step1Data, $validated);

        try {
            $service->storeData($data);
            session()->forget('barang_keluar_demak_step1');
            return redirect()->route('barang-keluar.demak.index')
                ->with('success', 'Data Barang Keluar (Demak) berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangKeluarDemak = BarangKeluarDemak::with(['barangKeluar', 'partai', 'warna'])->findOrFail($id);
        return view('admin.barang-keluar.demak.show', compact('barangKeluarDemak'));
    }

    public function edit($id)
    {
        $barangKeluarDemak = BarangKeluarDemak::with(['barangKeluar'])->findOrFail($id);
        $partai = \App\Models\Partai::all();
        $warna = \App\Models\Warna::all();

        return view('admin.barang-keluar.demak.edit', compact('barangKeluarDemak', 'partai', 'warna'));
    }

    public function update(Request $request, $id, \App\Services\Admin\Transaksi\BarangKeluarDemakService $service)
    {
        $validated = $request->validate([
            'tanggal_kirim' => 'required|date',
            'partai_id' => 'required|exists:partai,id',
            'warna_id' => 'required|exists:warna,id',
            'kode_lp' => 'nullable|string|max:255',
            'gram' => 'required|numeric',
            'pcs' => 'required|numeric',
            'kadar_air' => 'required|numeric',
            'timbangan_ulang' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $barangKeluarDemak = BarangKeluarDemak::findOrFail($id);

        try {
            $service->updateData($barangKeluarDemak, $validated);
            return redirect()->route('barang-keluar.demak.index')
                ->with('success', 'Data Barang Keluar (Demak) berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id, \App\Services\Admin\Transaksi\BarangKeluarDemakService $service)
    {
        $barangKeluarDemak = BarangKeluarDemak::findOrFail($id);

        try {
            $service->destroyData($barangKeluarDemak);
            return redirect()->route('barang-keluar.demak.index')
                ->with('success', 'Data Barang Keluar (Demak) berhasil dihapus.');
        }
        catch (\Exception $e) {
            return redirect()->route('barang-keluar.demak.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
