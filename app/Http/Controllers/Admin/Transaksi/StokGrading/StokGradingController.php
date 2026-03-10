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
        $stokGradingBatch = StokGrading::orderBy('tanggal', 'desc')->paginate(10);

        return view('admin.stok-grading.index', compact('stokGradingBatch'));
    }

    public function createStep1(Request $request)
    {
        // Require StokGudangService globally if not already set up. We already have it injected.
        $stokGudang = $this->stokGudangService->getGlobalSummary($request->search);

        return view('admin.stok-grading.create-step-1', compact('stokGudang'));
    }

    public function createStep2(Request $request)
    {
        // Mock step 2 form for a specific batch/stok
        $batchId = $request->query('stok_id', 'A-1');

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

        return view('admin.stok-grading.create-step-2', compact('batchId', 'gradesLempengBersih', 'gradesLempengKotor', 'gradesMangkok'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stok_id' => 'nullable|string',
            'grades' => 'required|array',
            'grades.*.name' => 'required|string',
            'grades.*.type' => 'required|string',
            'grades.*.berat' => 'nullable|numeric',
            'grades.*.box' => 'nullable|integer',
        ]);

        $batchId = $request->input('stok_id', 'A-1');
        $partai = 'Partai Mock'; // get from stok_id if applicable

        $data = [
            'batch_utama' => $batchId,
            'partai' => $partai,
            'grades' => $request->input('grades'),
        ];

        $grading = $this->stokGradingService->storeGrading($data);

        return redirect()->route('stok-grading.index')->with('success', 'Grading berhasil disimpan dengan ID: ' . $grading->id);
    }

    public function show($id)
    {
        $gradingBatch = $this->stokGradingService->getGradingFormatted($id);

        return view('admin.stok-grading.show', compact('gradingBatch'));
    }

    public function destroy($id)
    {
        $this->stokGradingService->deleteGrading($id);

        return redirect()->route('stok-grading.index')->with('success', 'Data grading berhasil dihapus.');
    }
}
