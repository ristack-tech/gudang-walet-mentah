<?php

namespace App\Http\Controllers\Admin\Transaksi\BarangMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BarangMasukDemak;
use App\Models\BarangKeluar;
use App\Models\JenisBarangMasuk;
use App\Models\JenisBarangKeluar;
use App\Models\NamaBarang;
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

    public function createStep1(Request $request)
    {
        $jenisDemakKeluar = JenisBarangKeluar::firstOrCreate(['nama' => 'Demak']);
        $outstandingBatches = BarangKeluar::where('jenis_barang_keluar_id', $jenisDemakKeluar->id)
            ->where('status_lunas', false)
            ->get();
        
        $lokasiList = \App\Models\Lokasi::where('nama_lokasi', 'like', '%Demak%')->get();
            
        $step1Data = session('barang_masuk_demak_step1', []);

        return view('admin.barang-masuk.demak.step1', compact('outstandingBatches', 'lokasiList', 'step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'barang_keluar_id' => 'required|exists:barang_keluar,id',
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
        
        $barangKeluar = BarangKeluar::with([
            'barangKeluarDemak' => function($q) {
                // Hanya ambil yang belum kembali
                $q->where('status_kembali', false);
            },
            'barangKeluarDemak.partai',
            'barangKeluarDemak.warna',
        ])->findOrFail($step1Data['barang_keluar_id']);

        $pendingItems = $barangKeluar->barangKeluarDemak;
        $barangList = NamaBarang::all();

        return view('admin.barang-masuk.demak.step2', compact('step1Data', 'barangKeluar', 'pendingItems', 'barangList'));
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
            return redirect()->back()->with('error', 'Anda harus mencentang minimal 1 barang yang dikembalikan.');
        }

        try {
            $service->storeData($step1Data, $selectedItems);
            session()->forget('barang_masuk_demak_step1');
            return redirect()->route('barang-masuk.demak.index')
                ->with('success', 'Data Barang Masuk Demak berhasil disimpan. Tidak bisa diedit.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $barangMasukBatch = \App\Models\BarangMasuk::with(['barangMasukDemak.barangKeluarDemak.partai', 'barangKeluar'])->findOrFail($id);
        return view('admin.barang-masuk.demak.show', compact('barangMasukBatch'));
    }
}
