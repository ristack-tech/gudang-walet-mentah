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
        // Require stok_id, pcs_awal, and sumber_stok from step 1
        if (!$request->has('stok_id') || !$request->has('pcs_awal') || !$request->has('sumber_stok')) {
            return redirect()->route('stok-grading.create.step1')->with('error', 'Silahkan pilih stok, cantumkan jumlah Pcs, dan sumber stok terlebih dahulu.');
        }

        $stokId = $request->query('stok_id');
        $pcsAwal = $request->query('pcs_awal');
        $sumberStok = $request->query('sumber_stok');
        
        $namaBarangModel = \App\Models\NamaBarang::find($stokId);
        $namaBarang = $namaBarangModel ? $namaBarangModel->nama_barang : 'Unknown';

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

        $stokSummary = $this->stokGudangService->getGlobalSummaryForBarang($stokId);
        // Note: For display purposes on the overall stock limit, we still use the global summary or adapt to specific source summary
        // Due to complexity of partial edits, keeping global validation is safer, but strictly we deduct per source later
        $gramAwal = $stokSummary ? $stokSummary->total_gram : 0;

        return view('admin.stok-grading.create-step-2', compact('stokId', 'namaBarang', 'pcsAwal', 'sumberStok', 'gramAwal', 'gradesLempengBersih', 'gradesLempengKotor', 'gradesMangkok'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stok_id' => 'required|string',
            'sumber_stok' => 'required|string|in:demak,jasa_cuci',
            'pcs_awal' => 'required|integer|min:1',
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

        // Mendapatkan referensi partai dari Barang Masuk (jika ada)
        $namaBarangModel = \App\Models\NamaBarang::find($stokId);
        $partai = $namaBarangModel ? $namaBarangModel->nama : '-';

        $data = [
            'barang_id' => $stokId, // Stores the NamaBarang ID
            'sumber_stok' => $request->input('sumber_stok'),
            'partai' => $partai,
            'pcs_awal' => $request->input('pcs_awal'),
            'grades' => $request->input('grades'),
        ];

        $grading = $this->stokGradingService->storeGrading($data);

        return redirect()->route('stok-grading.index')->with('success', 'Grading berhasil disimpan dengan ID: SG-' . str_pad($grading->id, 3, '0', STR_PAD_LEFT));
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
            'pcs_awal' => 'required|integer|min:1',
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
            'pcs_awal' => $request->input('pcs_awal'),
            'grades' => $request->input('grades'),
        ];

        $this->stokGradingService->updateGrading($id, $data);

        return redirect()->route('stok-grading.index')->with('success', 'Grading berhasil diupdate.');
    }

    public function destroy($id)
    {
        $this->stokGradingService->deleteGrading($id);

        return redirect()->route('stok-grading.index')->with('success', 'Data grading berhasil dihapus.');
    }
}
