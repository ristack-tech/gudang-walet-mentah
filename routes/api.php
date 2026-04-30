<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\StokBarangJadiController;
use App\Http\Controllers\Api\StokGradingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for Transaksi modules
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Export endpoints
    Route::get('/exports', [ExportController::class, 'index']);
    Route::post('/exports', [ExportController::class, 'store']);
    Route::get('/exports/{id}', [ExportController::class, 'show']);
    Route::delete('/exports/{id}', [ExportController::class, 'destroy']);

    // Stok Barang Jadi endpoints
    Route::get('/stok-barang-jadi', [StokBarangJadiController::class, 'index']);
    Route::get('/stok-barang-jadi/{id}', [StokBarangJadiController::class, 'show']);
    Route::get('/stok-barang-jadi/clop/available', [StokBarangJadiController::class, 'availableClopStocks']);

    // Stok Grading endpoints
    Route::get('/stok-grading', [StokGradingController::class, 'index']);
    Route::get('/stok-grading/{id}', [StokGradingController::class, 'show']);
});
