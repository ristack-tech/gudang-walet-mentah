<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\WarnaService;

class WarnaController extends Controller
{
    protected $warnaService;

    public function __construct(WarnaService $warnaService)
    {
        $this->warnaService = $warnaService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $warna = $this->warnaService->getAllWarna($search, 10);
        return view('admin.warna.index', compact('warna', 'search'));
    }

    public function create()
    {
        return view('admin.warna.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_warna' => 'required|string|max:255|unique:warna,kode_warna',
            'warna'      => 'required|string|max:255',
        ]);

        $this->warnaService->createWarna($validatedData);

        return redirect()->route('warna.index')->with('success', 'Warna created successfully.');
    }

    public function edit($id)
    {
        $warna = $this->warnaService->getWarnaById($id);
        return view('admin.warna.edit', compact('warna'));
    }

    public function update(Request $request, $id)
    {
        $warna = $this->warnaService->getWarnaById($id);
        
        $validatedData = $request->validate([
            'kode_warna' => 'required|string|max:255|unique:warna,kode_warna,' . $warna->id,
            'warna'      => 'required|string|max:255',
        ]);

        $this->warnaService->updateWarna($id, $validatedData);

        return redirect()->route('warna.index')->with('success', 'Warna updated successfully.');
    }

    public function destroy($id)
    {
        $this->warnaService->deleteWarna($id);

        return redirect()->route('warna.index')->with('success', 'Warna deleted successfully.');
    }
}
