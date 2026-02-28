<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\NamaBarangService;

class NamaBarangController extends Controller
{
    protected $namaBarangService;

    public function __construct(NamaBarangService $namaBarangService)
    {
        $this->namaBarangService = $namaBarangService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $nama_barang = $this->namaBarangService->getAllNamaBarang($search, 10);
        return view('admin.nama_barang.index', compact('nama_barang', 'search'));
    }

    public function create()
    {
        return view('admin.nama_barang.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $this->namaBarangService->createNamaBarang($validatedData);

        return redirect()->route('nama-barang.index')->with('success', 'Nama Barang created successfully.');
    }

    public function edit($id)
    {
        $nama_barang = $this->namaBarangService->getNamaBarangById($id);
        return view('admin.nama_barang.edit', compact('nama_barang'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $this->namaBarangService->updateNamaBarang($id, $validatedData);

        return redirect()->route('nama-barang.index')->with('success', 'Nama Barang updated successfully.');
    }

    public function destroy($id)
    {
        $this->namaBarangService->deleteNamaBarang($id);

        return redirect()->route('nama-barang.index')->with('success', 'Nama Barang deleted successfully.');
    }
}
