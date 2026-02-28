<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Services\Admin\Master\KodeBahanService;
use Illuminate\Http\Request;
class KodeBahanController extends Controller
{
    protected $kodeBahanService;

    public function __construct(KodeBahanService $kodeBahanService)
    {
        $this->kodeBahanService = $kodeBahanService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $kode_bahan = $this->kodeBahanService->getAllKodeBahan($search, 10);
        return view('admin.kode_bahan.index', compact('kode_bahan', 'search'));
    }

    public function create()
    {
        return view('admin.kode_bahan.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $this->kodeBahanService->createKodeBahan($validatedData);

        return redirect()->route('kode-bahan.index')->with('success', 'Kode Bahan created successfully.');
    }

    public function edit($id)
    {
        $kode_bahan = $this->kodeBahanService->getKodeBahanById($id);
        return view('admin.kode_bahan.edit', compact('kode_bahan'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $this->kodeBahanService->updateKodeBahan($id, $validatedData);

        return redirect()->route('kode-bahan.index')->with('success', 'Kode Bahan updated successfully.');
    }

    public function destroy($id)
    {
        $this->kodeBahanService->deleteKodeBahan($id);

        return redirect()->route('kode-bahan.index')->with('success', 'Kode Bahan deleted successfully.');
    }
}
