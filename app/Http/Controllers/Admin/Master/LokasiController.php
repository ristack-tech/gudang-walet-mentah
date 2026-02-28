<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\Master\LokasiService;

class LokasiController extends Controller
{
    protected $lokasiService;

    public function __construct(LokasiService $lokasiService)
    {
        $this->lokasiService = $lokasiService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $lokasi = $this->lokasiService->getAllLokasi($search, 10);
        return view('admin.lokasi.index', compact('lokasi', 'search'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'is_jasa_cuci' => 'required|boolean',
        ]);

        $this->lokasiService->createLokasi($validatedData);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi created successfully.');
    }

    public function edit($id)
    {
        $lokasi = $this->lokasiService->getLokasiById($id);
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'is_jasa_cuci' => 'required|boolean',
        ]);

        $this->lokasiService->updateLokasi($id, $validatedData);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi updated successfully.');
    }

    public function destroy($id)
    {
        $this->lokasiService->deleteLokasi($id);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi deleted successfully.');
    }
}
