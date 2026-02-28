<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\SupplierService;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $supplier = $this->supplierService->getAllSupplier($search, 10);
        return view('admin.supplier.index', compact('supplier', 'search'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $this->supplierService->createSupplier($validatedData);

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $this->supplierService->updateSupplier($id, $validatedData);

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $this->supplierService->deleteSupplier($id);

        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
