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

    Route::prefix('barang-keluar')->name('barang-keluar.')->group(function () {
            Route::get('/', function () {
                    return view('admin.barang-keluar.index');
                }
                )->name('index');

                Route::prefix('demak')->name('demak.')->group(function () {
                    Route::get('/', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'index'])->name('index');
                    Route::get('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'createStep1'])->name('create.step1');
                    Route::post('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'storeStep1'])->name('store.step1');
                    Route::get('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'createStep2'])->name('create.step2');
                    Route::post('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'storeStep2'])->name('store.step2');
                    Route::get('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'show'])->name('show');
                    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'edit'])->name('edit');
                    Route::put('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'update'])->name('update');
                    Route::delete('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangKeluarDemakController::class , 'destroy'])->name('destroy');
                }
                );
            }
            );

            Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
            Route::get('/', function () {
                    return view('admin.barang-masuk.index');
                }
                )->name('index');

                Route::prefix('pembelian-supplier')->name('pembelian-supplier.')->group(function () {
                    Route::get('/', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'index'])->name('index');
                    Route::get('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'createStep1'])->name('create.step1');
                    Route::post('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'storeStep1'])->name('store.step1');
                    Route::get('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'createStep2'])->name('create.step2');
                    Route::post('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'storeStep2'])->name('store.step2');
                    Route::post('/create/step-2/add-item', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'addStep2Item'])->name('add.step2.item');
                    Route::delete('/create/step-2/remove-item/{index}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'removeStep2Item'])->name('remove.step2.item');
                    Route::get('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'show'])->name('show');
                    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'edit'])->name('edit');
                    Route::put('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'update'])->name('update');
                    Route::delete('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController::class , 'destroy'])->name('destroy');
                }
                );

                Route::prefix('demak')->name('demak.')->group(function () {
                    Route::get('/', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'index'])->name('index');
                    Route::get('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'createStep1'])->name('create.step1');
                    Route::post('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'storeStep1'])->name('store.step1');
                    Route::get('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'createStep2'])->name('create.step2');
                    Route::post('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'storeStep2'])->name('store.step2');
                    Route::post('/create/step-2/add-item', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'addStep2Item'])->name('add.step2.item');
                    Route::delete('/create/step-2/remove-item/{index}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'removeStep2Item'])->name('remove.step2.item');
                    Route::get('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'show'])->name('show');
                    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'edit'])->name('edit');
                    Route::put('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'update'])->name('update');
                    Route::delete('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController::class , 'destroy'])->name('destroy');
                }
                );

                Route::prefix('jasa-cuci')->name('jasa-cuci.')->group(function () {
                    Route::get('/', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'index'])->name('index');
                    Route::get('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'createStep1'])->name('create.step1');
                    Route::post('/create/step-1', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'storeStep1'])->name('store.step1');
                    Route::get('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'createStep2'])->name('create.step2');
                    Route::post('/create/step-2', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'storeStep2'])->name('store.step2');
                    Route::post('/create/step-2/add-item', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'addStep2Item'])->name('add.step2.item');
                    Route::delete('/create/step-2/remove-item/{index}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'removeStep2Item'])->name('remove.step2.item');
                    Route::get('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'show'])->name('show');
                    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'edit'])->name('edit');
                    Route::put('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'update'])->name('update');
                    Route::delete('/{id}', [\App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController::class , 'destroy'])->name('destroy');
                }
                );
            }
            );

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
