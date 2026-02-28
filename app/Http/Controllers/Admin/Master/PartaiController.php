<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\PartaiService;

class PartaiController extends Controller
{
    protected $partaiService;

    public function __construct(PartaiService $partaiService)
    {
        $this->partaiService = $partaiService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $partai = $this->partaiService->getAllPartai($search, 10);
        return view('admin.partai.index', compact('partai', 'search'));
    }

    public function create()
    {
        return view('admin.partai.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_partai' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $this->partaiService->createPartai($validatedData);

        return redirect()->route('partai.index')->with('success', 'Partai created successfully.');
    }

    public function edit($id)
    {
        $partai = $this->partaiService->getPartaiById($id);
        return view('admin.partai.edit', compact('partai'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_partai' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $this->partaiService->updatePartai($id, $validatedData);

        return redirect()->route('partai.index')->with('success', 'Partai updated successfully.');
    }

    public function destroy($id)
    {
        $this->partaiService->deletePartai($id);

        return redirect()->route('partai.index')->with('success', 'Partai deleted successfully.');
    }
}
