<?php

use App\Http\Controllers\Admin\Master\LokasiController;
use App\Http\Controllers\Admin\Master\NamaBarangController;
use App\Http\Controllers\Admin\Master\KodeBahanController;
use App\Http\Controllers\Admin\Master\PartaiController;
use App\Http\Controllers\Admin\Master\SupplierController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AuthController::class , "showLogin"]);
Route::get("/dashboard", [DashboardController::class , "index"])->name('dashboard');

Route::prefix('admin')->group(function () {

    Route::get('barang-keluar', function () {
            return view('admin.barang-keluar.index');
        }
        )->name('barang-keluar.index');

    });

    Route::resource('lokasi', LokasiController::class);
    Route::resource('nama-barang', NamaBarangController::class)->parameters([
        'nama-barang' => 'nama_barang'
    ]);
    Route::resource('kode-bahan', KodeBahanController::class)->parameters([
        'kode-bahan' => 'kode_bahan'
    ]);
    Route::resource('partai', PartaiController::class);
    Route::resource('supplier', SupplierController::class);
});
