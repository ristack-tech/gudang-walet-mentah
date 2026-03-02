<?php

namespace App\Http\Controllers\Admin\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokBarangJadiController extends Controller
{
    public function index(Request $request)
    {
        // Mock data logic for Stok Barang Jadi
        $tab = $request->query('tab', 'clop'); // default to clop

        $stokGlobal = [
            ['id' => 1, 'nama_barang' => 'Barang A', 'berat' => 100.5],
            ['id' => 2, 'nama_barang' => 'Barang B', 'berat' => 50.0],
            ['id' => 3, 'nama_barang' => 'Barang C', 'berat' => 200.2],
        ];

        $stokClop = [
            ['id' => 4, 'nama_barang' => 'Clop X', 'berat' => 25.5],
            ['id' => 5, 'nama_barang' => 'Clop Y', 'berat' => 10.0],
        ];

        // Ensure we pass the active collection down based on tab type
        $items = $tab === 'global' ? collect($stokGlobal) : collect($stokClop);

        return view('admin.stok-barang-jadi.index', compact('items', 'tab', 'stokGlobal'));
    }

    public function transfer(Request $request)
    {
        // In real execution, this might find a global stock property via DB
        // Using mock array filtering here
        $globalId = $request->input('global_id');
        $jumlah = $request->input('jumlah');
        $berat = $request->input('berat');
        
        return redirect()->route('stok-barang-jadi.index', ['tab' => 'clop'])->with('success', 'Transfer mock berhasil. Jumlah: ' . $jumlah . ', Berat: ' . $berat);
    }

    public function destroy($id)
    {
        return redirect()->back()->with('success', 'Data stok barang jadi berhasil dihapus.');
    }
}
