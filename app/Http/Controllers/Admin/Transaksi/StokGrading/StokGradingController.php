<?php

namespace App\Http\Controllers\Admin\Transaksi\StokGrading;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\Transaksi\StokGrading\StokGradingService;
use App\Models\StokGrading;

class StokGradingController extends Controller
{
    protected $stokGradingService;
    protected $stokGudangService;

    public function __construct(
        StokGradingService $stokGradingService,
        \App\Services\StokGudang\StokGudangService $stokGudangService
    ) {
        $this->stokGradingService = $stokGradingService;
        $this->stokGudangService = $stokGudangService;
    }

    public function index(Request $request)
    {
        $query = StokGrading::with('namaBarang')->orderBy('tanggal', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('namaBarang', function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            })->orWhere('no_kartu', 'like', "%{$search}%");
        }

        $stokGradingBatch = $query->paginate(10);

        return view('admin.stok-grading.index', compact('stokGradingBatch'));
    }

    public function createStep1(Request $request)
    {
        $demakSummary = $this->stokGudangService->getDemakSummary($request->search);
        $jasaCuciSummary = $this->stokGudangService->getJasaCuciSummary($request->search);

        return view('admin.stok-grading.create-step-1', compact('demakSummary', 'jasaCuciSummary'));
    }

    public function createStep2(Request $request)
    {
        $allItems = null;
        
        // Handle new checklist pattern with items_data JSON
        if ($request->has('items_data')) {
            $itemsData = json_decode($request->input('items_data'), true);

            if (empty($itemsData)) {
                return redirect()->route('stok-grading.create.step1')->with('error', 'Silahkan pilih minimal satu item terlebih dahulu.');
            }

            // Get first item for single-item grading flow
            $firstItem = $itemsData[0];
            $stokId = $firstItem['id'];
            $boxAwal = $firstItem['gram'];
            $sumberStok = $firstItem['sumber'];
            $namaBarang = $firstItem['nama'];
            $allItems = $itemsData;
        } elseif (!$request->has('stok_id') || (!$request->has('box_awal')) || !$request->has('sumber_stok')) {
            return redirect()->route('stok-grading.create.step1')->with('error', 'Silahkan pilih stok, cantumkan jumlah Box, dan sumber stok terlebih dahulu.');
        } else {
            // Legacy single-item pattern
            $stokId = $request->query('stok_id');
            $boxAwal = $request->query('box_awal');
            $sumberStok = $request->query('sumber_stok');

            $namaBarangModel = \App\Models\NamaBarang::find($stokId);
            $namaBarang = $namaBarangModel ? $namaBarangModel->nama_barang : 'Unknown';
        }
        $gradesLempengBersih = [
            'ZT', 'ZT (B)', 'YT', 'YT (B)', 'MB', 'MB (B)', 'SB', 'SB B', 'SB 2', 'SB 2 (B)', 'SB 3'
        ];

        $gradesLempengKotor = [
            'MBS', 'MBS (B)', 'SBS', 'SBS (B)', 'SBS 2', 'SBS 2 (B)', 'SBS 3', 'SBS 3 (B)'
        ];

        $gradesMangkok = [
            'MK R' => ['S3', 'S4', 'S5', 'S6'],
            'PM' => ['KECIL', 'SEDANG', 'BESAR', 'JUMBO'],
            'MK HC' => ['JUMBO', 'SUPER', 'SUPER G']
        ];

        // box_awal parameter now represents true Gram amount inputted in Step 1 for both sources
        $gramAwal = $boxAwal;

        return view('admin.stok-grading.create-step-2', compact('stokId', 'namaBarang', 'boxAwal', 'sumberStok', 'gramAwal', 'gradesLempengBersih', 'gradesLempengKotor', 'gradesMangkok', 'allItems'));
    }

    public function store(Request $request)
    {
        // Handle new checklist pattern with items_data JSON
        if ($request->has('items_data')) {
            $itemsData = json_decode($request->input('items_data'), true);

            if (empty($itemsData)) {
                return redirect()->route('stok-grading.create.step1')->with('error', 'Silahkan pilih minimal satu item terlebih dahulu.');
            }

            $validatedData = $request->validate([
                'grades' => 'required|array',
                'grades.*.name' => 'required|string',
                'grades.*.type' => 'required|string',
                'grades.*.berat' => 'nullable|numeric',
                'grades.*.box' => 'nullable|integer',
            ]);

            $grades = $request->input('grades');

            // Calculate total gram dari semua grades
            $totalGramGrades = 0;
            foreach ($grades as $grade) {
                if (!empty($grade['berat'])) {
                    $totalGramGrades += (float) $grade['berat'];
                }
            }

            if ($totalGramGrades <= 0) {
                return back()->withErrors(['grades' => 'Minimal harus ada satu grade yang ditambahkan dan memiliki berat (Gram)-nya.'])->withInput();
            }

            // Loop melalui setiap item yang dipilih
            $createdCount = 0;
            foreach ($itemsData as $item) {
                $stokId = $item['id'];
                $sumberStok = $item['sumber'];
                $gramInput = (float) $item['gram'];

                // Validasi: total gram dari grades tidak boleh melebihi gram yang dipilih dari item
                if (round($totalGramGrades, 2) > round($gramInput, 2)) {
                    continue; // Skip item ini karena exceeds
                }

                // Get nama barang
                $namaBarangModel = \App\Models\NamaBarang::find($stokId);
                $namaBarang = $namaBarangModel ? $namaBarangModel->nama_barang : '-';

                $data = [
                    'barang_id' => $stokId,
                    'sumber_stok' => $sumberStok,
                    'partai' => $namaBarang,
                    'box_awal' => 0,
                    'grades' => $grades,
                ];

                $this->stokGradingService->storeGrading($data);
                $createdCount++;
            }

            return redirect()->route('stok-grading.index')
                ->with('success', "Grading berhasil disimpan untuk {$createdCount} item.");

        } else {
            // Legacy single-item pattern
            $validatedData = $request->validate([
                'stok_id' => 'required|string',
                'sumber_stok' => 'required|string|in:demak,jasa_cuci',
                'box_awal' => 'required|numeric|min:0',
                'grades' => 'required|array',
                'grades.*.name' => 'required|string',
                'grades.*.type' => 'required|string',
                'grades.*.berat' => 'nullable|numeric|required_with:grades.*.box',
                'grades.*.box' => 'nullable|integer',
            ], [
                'grades.*.berat.required_with' => 'Berat (Gram) wajib diisi jika Anda mengisi nilai Box pada Grade tersebut.'
            ]);

            $stokId = $request->input('stok_id');

            $totalGramSubmit = 0;
            foreach($request->input('grades') as $grade) {
                if(!empty($grade['berat'])) {
                    $totalGramSubmit += (float) $grade['berat'];
                }
            }

            $stokSummary = $this->stokGudangService->getGlobalSummaryForBarang($stokId);
            $availableGram = $stokSummary ? $stokSummary->total_gram : 0;

            if ($totalGramSubmit <= 0) {
                return back()->withErrors(['grades' => 'Minimal harus ada satu grade yang ditambahkan dan memiliki berat (Gram)-nya.'])->withInput();
            }

            if (round($totalGramSubmit, 2) > round($availableGram, 2)) {
                return back()->withErrors(['grades' => 'Total berat ('.$totalGramSubmit.' Gr) melebihi sisa stok awal yang tersedia ('.$availableGram.' Gr).'])->withInput();
            }

            $namaBarangModel = \App\Models\NamaBarang::find($stokId);
            $partai = $namaBarangModel ? $namaBarangModel->nama : '-';

            $data = [
                'barang_id' => $stokId,
                'sumber_stok' => $request->input('sumber_stok'),
                'partai' => $partai,
                'box_awal' => 0,
                'grades' => $request->input('grades'),
            ];

            $grading = $this->stokGradingService->storeGrading($data);

            return redirect()->route('stok-grading.index')->with('success', 'Grading berhasil disimpan dengan ID: SG-' . str_pad($grading->id, 3, '0', STR_PAD_LEFT));
        }
    }


    public function show($id)
    {
        $gradingBatch = $this->stokGradingService->getGradingFormatted($id);
        
        $namaBarangModel = \App\Models\NamaBarang::find($gradingBatch['barang_id']);
        $namaBarang = $namaBarangModel ? $namaBarangModel->nama_barang : 'Unknown';

        return view('admin.stok-grading.show', compact('gradingBatch', 'namaBarang'));
    }

    public function edit($id)
    {
        $gradingBatch = $this->stokGradingService->getGradingFormatted($id);
        $stokSummary = $this->stokGudangService->getGlobalSummaryForBarang($gradingBatch['barang_id']);
        $gramAwal = $stokSummary ? $stokSummary->total_gram : 0;
        
        $namaBarangModel = \App\Models\NamaBarang::find($gradingBatch['barang_id']);
        $namaBarang = $namaBarangModel ? $namaBarangModel->nama_barang : 'Unknown';

        // Setup grades variables similar to createStep2
        $gradesLempengBersih = [
            'ZT', 'ZT (B)', 'YT', 'YT (B)', 'MB', 'MB (B)', 'SB', 'SB B', 'SB 2', 'SB 2 (B)', 'SB 3'
        ];

        $gradesLempengKotor = [
            'MBS', 'MBS (B)', 'SBS', 'SBS (B)', 'SBS 2', 'SBS 2 (B)', 'SBS 3', 'SBS 3 (B)'
        ];

        $gradesMangkok = [
            'MK R' => ['S3', 'S4', 'S5', 'S6'],
            'PM' => ['KECIL', 'SEDANG', 'BESAR', 'JUMBO'],
            'MK HC' => ['JUMBO', 'SUPER', 'SUPER G']
        ];

        return view('admin.stok-grading.edit', compact(
            'gradingBatch', 
            'namaBarang',
            'gramAwal', 
            'gradesLempengBersih', 
            'gradesLempengKotor', 
            'gradesMangkok'
        ));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'box_awal' => 'required|integer|min:1',
            'grades' => 'required|array',
            'grades.*.name' => 'required|string',
            'grades.*.type' => 'required|string',
            'grades.*.berat' => 'nullable|numeric|required_with:grades.*.box',
            'grades.*.box' => 'nullable|integer',
        ], [
            'grades.*.berat.required_with' => 'Berat (Gram) wajib diisi jika Anda mengisi nilai Box pada Grade tersebut.'
        ]);

        $grading = StokGrading::findOrFail($id);
        
        $totalGramSubmit = 0;
        foreach($request->input('grades') as $grade) {
            if(!empty($grade['berat'])) {
                $totalGramSubmit += (float) $grade['berat'];
            }
        }
        
        $stokSummary = $this->stokGudangService->getGlobalSummaryForBarang($grading->barang_id);
        $availableGram = ($stokSummary ? $stokSummary->total_gram : 0) + $grading->total_gram_keseluruhan;
        
        if ($totalGramSubmit <= 0) {
            return back()->withErrors(['grades' => 'Minimal harus ada satu grade yang diisi berat (Gram)-nya untuk mengupdate.'])->withInput();
        }

        if (round($totalGramSubmit, 2) > round($availableGram, 2)) {
            return back()->withErrors(['grades' => 'Total berat ('.$totalGramSubmit.' Gr) melebihi sisa stok awal yang tersedia ('.$availableGram.' Gr).'])->withInput();
        }

        $data = [
            'box_awal' => $request->input('box_awal'),
            'grades' => $request->input('grades'),
        ];

        $this->stokGradingService->updateGrading($id, $data);

        return redirect()->route('stok-grading.index')->with('success', 'Grading berhasil diupdate.');
    }

    public function destroy($id)
    {
        // Penghapusan kini diserahkan sepenuhnya ke Service untuk Smart Delete / Partial Rollback
        $this->stokGradingService->deleteGrading($id);

        return redirect()->route('stok-grading.index')->with('success', 'Sisa Stok Grading berhasil dikerjakan (dihapus/dikembalikan) dan bahan mentahnya sukses membal mundur ke Gudang Awal.');
    }
}
