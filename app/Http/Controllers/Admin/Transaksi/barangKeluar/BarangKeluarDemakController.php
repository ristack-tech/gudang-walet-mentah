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
        $jenisDemak = \App\Models\JenisBarangKeluar::firstOrCreate(['nama' => 'Demak']);

        $query = BarangKeluar::where('jenis_barang_keluar_id', $jenisDemak->id)
            ->with(['barangKeluarDemak.partai']);

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('barangKeluarDemak', function ($q) use ($request) {
                $q->where('kode_lp', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->latest()->paginate(10);

        return view('admin.barang-keluar.demak.index', compact('data'));
    }

    public function step1()
    {
        $partais = Partai::all();
        $step1Data = session('demak_step1', []);
        return view('admin.barang-keluar.demak.step1', compact('partais', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'partai_id' => 'required|exists:partai,id'
        ]);

        session(['demak_step1' => $request->only('tanggal_kirim', 'partai_id')]);

        if (!session()->has('demak_items')) {
            session(['demak_items' => []]);
        }

        return redirect()->route('barang-keluar.demak.step2');
    }

    public function step2()
    {
        if (!session()->has('demak_step1')) {
            return redirect()->route('barang-keluar.demak.step1')->with('error', 'Silakan isi step 1 terlebih dahulu.');
        }

        $step1Data = session('demak_step1');
        $partai = Partai::find($step1Data['partai_id']);
        $warnas = Warna::all();
        $items = session('demak_items', []);

        return view('admin.barang-keluar.demak.step2', compact('warnas', 'items', 'partai', 'step1Data'));
    }

    public function addStep2Item(Request $request)
    {
        $validated = $request->validate([
            'kode_lp' => 'required|string',
            'gram' => 'required|numeric',
            'pcs' => 'required|integer',
            'kadar_air' => 'nullable|numeric',
            'warna_id' => 'nullable|exists:warna,id',
            'timbangan_ulang' => 'nullable|numeric',
            'keterangan' => 'nullable|string'
        ]);

        $items = session('demak_items', []);

        if (!empty($validated['warna_id'])) {
            $validated['warna'] = Warna::find($validated['warna_id'])->kode_warna ?? '-';
        } else {
            $validated['warna'] = '-';
        }

        $items[] = $validated;
        session(['demak_items' => $items]);

        return redirect()->route('barang-keluar.demak.step2')->with('success', 'Barang ditambahkan ke daftar.');
    }

    public function removeStep2Item(Request $request, $index)
    {
        $items = session('demak_items', []);

        if (isset($items[$index])) {
            unset($items[$index]);
            $items = array_values($items);
            session(['demak_items' => $items]);
            return redirect()->route('barang-keluar.demak.step2')->with('success', 'Barang dihapus dari daftar.');
        }

        return redirect()->route('barang-keluar.demak.step2')->with('error', 'Barang tidak ditemukan.');
    }

    public function storeStep2(Request $request)
    {
        $step1Data = session('demak_step1');
        $items = session('demak_items', []);

        if (!$step1Data) {
            return redirect()->route('barang-keluar.demak.step1')->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        if (empty($items)) {
            return redirect()->route('barang-keluar.demak.step2')->with('error', 'Tidak ada barang di dalam daftar, silakan tambah minimal 1 barang.');
        }

        try {
            $this->service->store($step1Data, $items);
            session()->forget('demak_step1');
            session()->forget('demak_items');
            return redirect()->route('barang-keluar.demak.index')->with('success', 'Data Batch berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = BarangKeluar::with(['barangKeluarDemak.partai', 'barangKeluarDemak.warna'])->findOrFail($id);
        return view('admin.barang-keluar.demak.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BarangKeluar::with(['barangKeluarDemak'])->findOrFail($id);
        $partais = Partai::all();
        $warnas = Warna::all();
        
        // Eager load partais inside the edit view if needed, or get the batch's partai_id from its first Demak relation if exists
        $batchPartaiId = optional($data->barangKeluarDemak->first())->partai_id;

        return view('admin.barang-keluar.demak.edit', compact('data', 'partais', 'warnas', 'batchPartaiId'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kirim' => 'required|date',
            'partai_id' => 'required|exists:partai,id',
            'items' => 'required|array|min:1',
            'items.*.kode_lp' => 'required|string',
            'items.*.gram' => 'required|numeric',
            'items.*.pcs' => 'required|integer',
            'items.*.kadar_air' => 'nullable|numeric',
            'items.*.warna_id' => 'nullable|exists:warna,id',
            'items.*.timbangan_ulang' => 'nullable|numeric',
            'items.*.keterangan' => 'nullable|string'
        ]);

        try {
            $this->service->update($id, $request->all());
            return redirect()->route('barang-keluar.demak.index')->with('success', 'Data Batch berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('barang-keluar.demak.index')->with('success', 'Data Batch berhasil dihapus.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}