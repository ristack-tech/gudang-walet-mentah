<?php

use App\Http\Controllers\Admin\Master\LokasiController;
use App\Http\Controllers\Admin\Master\NamaBarangController;
use App\Http\Controllers\Admin\Master\KodeBahanController;
use App\Http\Controllers\Admin\Master\PartaiController;
use App\Http\Controllers\Admin\Master\SupplierController;
use App\Http\Controllers\Admin\Master\WarnaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", [AuthController::class, "showLogin"]);
Route::get("/dashboard", [DashboardController::class, "index"])->name('dashboard');

Route::prefix('admin')->group(function () {
    Route::resource('lokasi', LokasiController::class);
    Route::resource('nama-barang', NamaBarangController::class)->parameters([
        'nama-barang' => 'nama_barang'
    ]);
    Route::resource('kode-bahan', KodeBahanController::class)->parameters([
        'kode-bahan' => 'kode_bahan'
    ]);
    Route::resource('warna', WarnaController::class);
    Route::resource('partai', PartaiController::class);
    Route::resource('supplier', SupplierController::class);
});
