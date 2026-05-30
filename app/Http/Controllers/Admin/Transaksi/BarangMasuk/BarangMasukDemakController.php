<?php

namespace App\Http\Controllers\Admin\Transaksi\BarangMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BarangKeluar;
use App\Models\JenisBarangMasuk;
use App\Models\JenisBarangKeluar;
use App\Services\Admin\Transaksi\BarangMasuk\BarangMasukDemakService;

class BarangMasukDemakController extends Controller
{
    public function index(Request $request)
    {
        $jenisDemak = JenisBarangMasuk::firstOrCreate(['nama' => 'Demak']);

        $query = \App\Models\BarangMasuk::where('jenis_barang_masuk_id', $jenisDemak->id)
            ->with(['barangMasukDemak', 'barangKeluar'])
            ->latest();

        $barangMasukBatch = $query->paginate(10);

        return view('admin.barang-masuk.demak.index', compact('barangMasukBatch'));
    }

    public function createStep1()
    {
        $lokasiList = \App\Models\Lokasi::where('is_jasa_cuci', false)->get();
        $step1Data = session('barang_masuk_demak_step1', []);

        return view('admin.barang-masuk.demak.step1', compact('lokasiList', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'transaksi_dari_id' => 'required|exists:lokasi,id',
        ]);

        session(['barang_masuk_demak_step1' => $validated]);

        return redirect()->route('barang-masuk.demak.create.step2');
    }

    public function createStep2(Request $request)
    {
        if (!session()->has('barang_masuk_demak_step1')) {
            return redirect()->route('barang-masuk.demak.create.step1')
                ->with('error', 'Silakan lengkapi Data Awal terlebih dahulu.');
        }

        $step1Data = session('barang_masuk_demak_step1');

        $processedIds = \App\Models\BarangMasukDemak::pluck('barang_keluar_demak_id')->toArray();

        $query = \App\Models\BarangKeluarDemak::with(['barangKeluar', 'partai', 'warna'])
            ->whereHas('barangKeluar', function ($q) {
                $q->where('jenis_barang_keluar_id', \App\Models\JenisBarangKeluar::where('nama', 'Demak')->first()->id ?? 0);
            })
            ->whereNotIn('id', $processedIds);

        if ($request->filled('partai') && $request->partai !== 'all') {
            $query->whereHas('partai', function ($q) use ($request) {
                $q->where('nama_partai', $request->partai);
            });
        }

        if ($request->filled('search')) {
            $query->where('kode_lp', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->paginate($perPage);
        $barangList = \App\Models\NamaBarang::all();
        $partaiList = \App\Models\Partai::where('kategori', 'barang masuk')->get();

        return view('admin.barang-masuk.demak.step2', compact('step1Data', 'items', 'barangList', 'partaiList'));
    }

    public function storeStep2(Request $request, BarangMasukDemakService $service)
    {
        $step1Data = session('barang_masuk_demak_step1');

        if (!$step1Data) {
            return redirect()->route('barang-masuk.demak.create.step1')
                ->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.is_checked' => 'nullable|boolean',
            'items.*.barang_keluar_demak_id' => 'required|exists:barang_keluar_demak,id',
            'items.*.gram_diterima' => 'nullable|numeric',
            'items.*.partai' => 'nullable|string|max:255',
            'items.*.barang_id' => 'nullable|exists:nama_barang,id',
            'items.*.pcs' => 'nullable|integer|min:0',
            'items.*.box' => 'nullable|integer|min:0',
        ]);

        try {
            $selectedItems = collect($request->items)->filter(function ($item) {
                return isset($item['is_checked']) && $item['is_checked'] == '1';
            })->map(function ($item) {
                if (empty($item['gram_diterima']) || $item['gram_diterima'] <= 0) {
                    throw new \Exception('Gram diterima tidak boleh kosong atau <= 0 untuk item yang dicentang.');
                }
                if (empty($item['partai'])) {
                    throw new \Exception('Partai Khusus tidak boleh kosong untuk item yang dicentang.');
                }
                if (empty($item['barang_id'])) {
                    throw new \Exception('Nama Barang tidak boleh kosong untuk item yang dicentang.');
                }
                if (!isset($item['pcs']) || $item['pcs'] === '') {
                    throw new \Exception('PCS tidak boleh kosong untuk item yang dicentang.');
                }
                if (!isset($item['box']) || $item['box'] === '') {
                    throw new \Exception('Box tidak boleh kosong untuk item yang dicentang.');
                }
                return $item;
            })->toArray();

            if (empty($selectedItems)) {
                return redirect()->back()->with('error', 'Anda harus mencentang minimal 1 barang.');
            }

            $service->storeData($step1Data, $selectedItems);
            session()->forget('barang_masuk_demak_step1');
            return redirect()->route('barang-masuk.demak.index')
                ->with('success', 'Data Barang Masuk Demak berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukDemak.barangKeluarDemak.partai', 'barangKeluar'])->findOrFail($id);
        return view('admin.barang-masuk.demak.show', compact('barangMasukBatch'));
    }

    public function edit($id)
    {
        $barangMasuk = \App\Models\BarangMasuk::findOrFail($id);
        return response()->json([
            'id' => $barangMasuk->id,
            'tanggal_terima' => $barangMasuk->tanggal_terima,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
        ]);

        $barangMasuk = \App\Models\BarangMasuk::findOrFail($id);
        $barangMasuk->tanggal_terima = $validated['tanggal_terima'];
        $barangMasuk->save();

        return redirect()->back()->with('success', 'Tanggal terima berhasil diperbarui.');
    }

    public function destroy($id, BarangMasukDemakService $service)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with('barangMasukDemak')->findOrFail($id);

        try {
            $service->destroyData($barangMasukBatch);
            return redirect()->route('barang-masuk.demak.index')
                ->with('success', 'Data Batch Barang Masuk (Demak) berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
