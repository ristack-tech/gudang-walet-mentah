<?php

namespace App\Http\Controllers\Admin\Transaksi\BarangMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BarangMasukJasaCuci;
use App\Models\BarangKeluar;
use App\Models\JenisBarangMasuk;
use App\Models\JenisBarangKeluar;
use App\Models\NamaBarang;
use App\Services\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciService;

class BarangMasukJasaCuciController extends Controller
{
    public function index(Request $request)
    {
        $jenisJasaCuci = JenisBarangMasuk::firstOrCreate(['nama' => 'Jasa Cuci']);

        $query = \App\Models\BarangMasuk::where('jenis_barang_masuk_id', $jenisJasaCuci->id)
            ->with(['barangMasukJasaCuci', 'barangKeluar'])
            ->latest();

        $barangMasukBatch = $query->paginate(10);

        return view('admin.barang-masuk.jasa-cuci.index', compact('barangMasukBatch'));
    }

    public function createStep1(Request $request)
    {
        $jenisJasaCuciKeluar = JenisBarangKeluar::firstOrCreate(['nama' => 'Jasa Cuci']);
        $outstandingBatches = BarangKeluar::where('jenis_barang_keluar_id', $jenisJasaCuciKeluar->id)
            ->where('status_lunas', false)
            ->get();
            
        $lokasiList = \App\Models\Lokasi::where('is_jasa_cuci', true)->get();
        
        $step1Data = session('barang_masuk_jasa_cuci_step1', []);

        return view('admin.barang-masuk.jasa-cuci.step1', compact('outstandingBatches', 'lokasiList', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'barang_keluar_id' => 'required|exists:barang_keluar,id',
            'lokasi_jasa_cuci_id' => 'required|exists:lokasi,id',
        ]);

        session(['barang_masuk_jasa_cuci_step1' => $validated]);

        return redirect()->route('barang-masuk.jasa-cuci.create.step2');
    }

    public function createStep2(Request $request)
    {
        if (!session()->has('barang_masuk_jasa_cuci_step1')) {
            return redirect()->route('barang-masuk.jasa-cuci.create.step1')
                ->with('error', 'Silakan lengkapi Data Awal terlebih dahulu.');
        }

        $step1Data = session('barang_masuk_jasa_cuci_step1');
        
        $barangKeluar = BarangKeluar::with([
            'barangKeluarJasaCuci' => function($q) {
                // Hanya ambil yang belum kembali
                $q->where('status_kembali', false);
            },
            'barangKeluarJasaCuci.namaBarang',
            'barangKeluarJasaCuci.kodeBahan',
        ])->findOrFail($step1Data['barang_keluar_id']);

        $pendingItems = $barangKeluar->barangKeluarJasaCuci;
        $barangList = NamaBarang::all();

        return view('admin.barang-masuk.jasa-cuci.step2', compact('step1Data', 'barangKeluar', 'pendingItems', 'barangList'));
    }

    public function storeStep2(Request $request, BarangMasukJasaCuciService $service)
    {
        $step1Data = session('barang_masuk_jasa_cuci_step1');

        if (!$step1Data) {
            return redirect()->route('barang-masuk.jasa-cuci.create.step1')
                ->with('error', 'Sesi telah habis, silakan ulangi proses.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.is_checked' => 'nullable|boolean',
            'items.*.barang_keluar_jasa_cuci_id' => 'required|exists:barang_keluar_jasa_cuci,id',
            'items.*.gram_diterima' => 'nullable|numeric',
            'items.*.barang_id' => 'nullable|exists:nama_barang,id',
            'items.*.kadar_air' => 'nullable|numeric',
        ]);

        try {
            $selectedItems = collect($request->items)->filter(function ($item) {
                return isset($item['is_checked']) && $item['is_checked'] == '1';
            })->map(function ($item) {
                if (empty($item['gram_diterima']) || $item['gram_diterima'] <= 0) {
                    throw new \Exception('Gram diterima tidak boleh kosong atau <= 0 untuk item yang dicentang.');
                }
                if (empty($item['barang_id'])) {
                    throw new \Exception('Nama Barang tidak boleh kosong untuk item yang dicentang.');
                }
                return $item;
            })->toArray();

            if (empty($selectedItems)) {
                return redirect()->back()->with('error', 'Anda harus mencentang minimal 1 barang yang dikembalikan.');
            }

            $service->storeData($step1Data, $selectedItems);
            session()->forget('barang_masuk_jasa_cuci_step1');
            return redirect()->route('barang-masuk.jasa-cuci.index')
                ->with('success', 'Data Barang Masuk Jasa Cuci berhasil disimpan. Tidak bisa diedit.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukJasaCuci.barangKeluarJasaCuci.namaBarang', 'barangKeluar'])->findOrFail($id);
        return view('admin.barang-masuk.jasa-cuci.show', compact('barangMasukBatch'));
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
}
