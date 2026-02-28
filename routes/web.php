<?php

use App\Http\Controllers\Admin\Master\LokasiController;
use App\Http\Controllers\Admin\Master\NamaBarangController;
use App\Http\Controllers\Admin\Master\KodeBahanController;
use App\Http\Controllers\Admin\Master\PartaiController;
use App\Http\Controllers\Admin\Master\SupplierController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Transaksi\barangKeluar\BarangKeluarDemakController;
use App\Http\Controllers\Admin\Transaksi\barangKeluar\BarangKeluarJasaCuciController;

Route::get("/", [AuthController::class , "showLogin"]);
Route::get("/dashboard", [DashboardController::class , "index"])->name('dashboard');

Route::prefix('admin')->group(function () {

    Route::get('barang-keluar', function () {
            return view('admin.barang-keluar.index');
        })->name('barang-keluar.index');

    Route::prefix('barang-keluar')->group(function () {

        Route::prefix('demak')->controller(BarangKeluarDemakController::class)->group(function () {
            Route::get('/', 'index')->name('barang-keluar.demak.index');
            Route::get('/step1', 'step1')->name('barang-keluar.demak.step1');
            Route::post('/step1', 'storeStep1')->name('barang-keluar.demak.store-step1');
            Route::get('/step2', 'step2')->name('barang-keluar.demak.step2');
            Route::post('/step2', 'storeStep2')->name('barang-keluar.demak.store-step2');
            Route::get('/{id}', 'show')->name('barang-keluar.demak.show');
            Route::get('/{id}/edit', 'edit')->name('barang-keluar.demak.edit');
            Route::put('/{id}', 'update')->name('barang-keluar.demak.update');
            Route::delete('/{id}', 'destroy')->name('barang-keluar.demak.destroy');
        });

        Route::prefix('jasa-cuci')->controller(BarangKeluarJasaCuciController::class)->group(function () {
            Route::get('/', 'index')->name('barang-keluar.jasa-cuci.index');
            Route::get('/step1', 'step1')->name('barang-keluar.jasa-cuci.step1');
            Route::post('/step1', 'storeStep1')->name('barang-keluar.jasa-cuci.store-step1');
            Route::get('/step2', 'step2')->name('barang-keluar.jasa-cuci.step2');
            Route::post('/step2', 'storeStep2')->name('barang-keluar.jasa-cuci.store-step2');
            Route::get('/{id}', 'show')->name('barang-keluar.jasa-cuci.show');
            Route::get('/{id}/edit', 'edit')->name('barang-keluar.jasa-cuci.edit');
            Route::put('/{id}', 'update')->name('barang-keluar.jasa-cuci.update');
            Route::delete('/{id}', 'destroy')->name('barang-keluar.jasa-cuci.destroy');
        });

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
