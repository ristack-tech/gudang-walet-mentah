<?php

use App\Http\Controllers\Admin\Master\KodeBahanController;
use App\Http\Controllers\Admin\Master\LokasiController;
use App\Http\Controllers\Admin\Master\NamaBarangController;
use App\Http\Controllers\Admin\Master\PartaiController;
use App\Http\Controllers\Admin\Master\SupplierController;
use App\Http\Controllers\Admin\Master\WarnaController;
use App\Http\Controllers\Admin\Transaksi\barangKeluar\BarangKeluarDemakController;
use App\Http\Controllers\Admin\Transaksi\barangKeluar\BarangKeluarJasaCuciController;
use App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukDemakController;
use App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukJasaCuciController;
use App\Http\Controllers\Admin\Transaksi\BarangMasuk\BarangMasukPembelianSupplierController;
use App\Http\Controllers\Admin\Transaksi\StokGrading\StokGradingController;
use App\Http\Controllers\Admin\Transaksi\StokBarangJadi\StokBarangJadiController;
use App\Http\Controllers\Admin\Transaksi\Export\ExportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get("/", [AuthController::class, "showLogin"])->name('login');
    Route::post("/", [AuthController::class, "login"])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get("/dashboard", [DashboardController::class, "index"])->name('dashboard');

    Route::prefix('admin')->group(function () {
        // Barang Keluar
        Route::prefix('barang-keluar')->name('barang-keluar.')->group(function () {
            Route::get('/', fn() => view('admin.barang-keluar.index'))->name('index');

            Route::prefix('demak')->name('demak.')->controller(BarangKeluarDemakController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/step1', 'step1')->name('step1');
                Route::post('/step1', 'storeStep1')->name('store-step1');
                Route::get('/step2', 'step2')->name('step2');
                Route::post('/step2', 'storeStep2')->name('store-step2');
                Route::post('/step2/add-item', 'addStep2Item')->name('add.step2.item');
                Route::delete('/step2/remove-item/{index}', 'removeStep2Item')->name('remove.step2.item');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::prefix('jasa-cuci')->name('jasa-cuci.')->controller(BarangKeluarJasaCuciController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/step1', 'step1')->name('step1');
                Route::post('/step1', 'storeStep1')->name('store-step1');
                Route::get('/step2', 'step2')->name('step2');
                Route::post('/step2', 'storeStep2')->name('store-step2');
                Route::post('/step2/add-item', 'addStep2Item')->name('add.step2.item');
                Route::delete('/step2/remove-item/{index}', 'removeStep2Item')->name('remove.step2.item');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        });

        // Barang Masuk
        Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
            Route::get('/', fn() => view('admin.barang-masuk.index'))->name('index');

            Route::prefix('pembelian-supplier')->name('pembelian-supplier.')->controller(BarangMasukPembelianSupplierController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create/step-1', 'createStep1')->name('create.step1');
                Route::post('/create/step-1', 'storeStep1')->name('store.step1');
                Route::get('/create/step-2', 'createStep2')->name('create.step2');
                Route::post('/create/step-2', 'storeStep2')->name('store.step2');
                Route::post('/create/step-2/add-item', 'addStep2Item')->name('add.step2.item');
                Route::delete('/create/step-2/remove-item/{index}', 'removeStep2Item')->name('remove.step2.item');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::prefix('demak')->name('demak.')->controller(BarangMasukDemakController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create/step-1', 'createStep1')->name('create.step1');
                Route::post('/create/step-1', 'storeStep1')->name('store.step1');
                Route::get('/create/step-2', 'createStep2')->name('create.step2');
                Route::post('/create/step-2', 'storeStep2')->name('store.step2');
                Route::post('/create/step-2/add-item', 'addStep2Item')->name('add.step2.item');
                Route::delete('/create/step-2/remove-item/{index}', 'removeStep2Item')->name('remove.step2.item');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::prefix('jasa-cuci')->name('jasa-cuci.')->controller(BarangMasukJasaCuciController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create/step-1', 'createStep1')->name('create.step1');
                Route::post('/create/step-1', 'storeStep1')->name('store.step1');
                Route::get('/create/step-2', 'createStep2')->name('create.step2');
                Route::post('/create/step-2', 'storeStep2')->name('store.step2');
                Route::post('/create/step-2/add-item', 'addStep2Item')->name('add.step2.item');
                Route::delete('/create/step-2/remove-item/{index}', 'removeStep2Item')->name('remove.step2.item');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        });

        // Transaksi Lainnya
        Route::prefix('stok-grading')->name('stok-grading.')->controller(StokGradingController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create/step-1', 'createStep1')->name('create.step1');
            Route::get('/create/step-2', 'createStep2')->name('create.step2');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('stok-gudang')->name('stok-gudang.')->controller(\App\Http\Controllers\Admin\Transaksi\StokGudang\StokGudangController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/demak', 'demak')->name('demak');
            Route::get('/demak/{id}', 'showDemak')->name('demak.show');
            Route::get('/jasa-cuci', 'jasaCuci')->name('jasa-cuci');
            Route::get('/jasa-cuci/{id}', 'showJasaCuci')->name('jasa-cuci.show');
            Route::get('/pembelian-supplier', 'pembelianSupplier')->name('pembelian-supplier');
            Route::get('/pembelian-supplier/{id}', 'showPembelianSupplier')->name('pembelian-supplier.show');
            Route::get('/global', 'global')->name('global');
            Route::get('/global/{id}', 'showGlobal')->name('global.show');
        });

        Route::prefix('stok-barang-jadi')->name('stok-barang-jadi.')->controller(StokBarangJadiController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/transfer', 'transfer')->name('transfer');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('export')->name('export.')->controller(ExportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create/step-1', 'createStep1')->name('create.step1');
            Route::get('/create/step-2', 'createStep2')->name('create.step2');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // Master Data
        Route::resource('lokasi', LokasiController::class);
        Route::resource('nama-barang', NamaBarangController::class)->parameters(['nama-barang' => 'nama_barang']);
        /**
         * Route::resource('kode-bahan', KodeBahanController::class)->parameters(['kode-bahan' => 'kode_bahan']); Merubah Kode Bahan tidak memiliki data master, file COntroller dan service di rubah file nya menjadi .md 
         * */
        Route::resource('partai', PartaiController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('warna', WarnaController::class);
    });
});

