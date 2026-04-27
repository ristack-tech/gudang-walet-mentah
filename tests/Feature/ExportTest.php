<?php

namespace Tests\Feature;

use App\Models\Export;
use App\Models\StokBarangJadi;
use App\Models\StokBarangJadiDetail;
use App\Services\Admin\Transaksi\Export\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PENGUJIAN TRANSAKSI EXPORT DAN KONSISTENSI STOK CLOP
     * 
     * Test ini mensimulasikan proses Export untuk memverifikasi Bug-18 dan Bug-24 telah diperbaiki.
     * 
     * Skenario yang diuji:
     * 1. [CREATE] Memastikan bahwa ketika barang diexport (dikirim), jumlah kotak fisik (jumlah_box) 
     *    dan total berat pada database Clop (stok_barang_jadi_details) benar-benar dikurangi 
     *    sesuai dengan proporsi yang diexport (menghindari phantom read / stok tidak berkurang).
     * 
     * 2. [VOID / DELETE] Memastikan bahwa ketika Transaksi Export dibuat batal/void, jumlah 
     *    kotak (jumlah_box) dan total berat di Clop akan HANYA dikembalikan 
     *    seperti semula dengan metode rollback yang aman (menghindari hilangnya stok saat void).
     */

    protected ExportService $exportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exportService = new ExportService();
    }

    public function test_export_reduces_clop_stock()
    {
        // 1. Arrange: Create a fake Clop Stock Database Record
        $stok = StokBarangJadi::create([
            'batch' => 'TRF-CLOP-TEST01',
            'tanggal' => now()->toDateString(),
            'type' => 'clop',
            'keterangan' => 'Test Clop',
        ]);

        $barang = \App\Models\NamaBarang::create([
            'nama_barang' => 'Barang A'
        ]);

        $stokDetail = StokBarangJadiDetail::create([
            'stok_barang_jadi_id' => $stok->id,
            'barang_id' => $barang->id,
            'nama_barang' => 'Barang A - GRADE C',
            'jumlah_box' => 3,
            'berat' => 1500, // 500g per box initially
        ]);

        // Scenario Data for Export (Exporting 2 boxes)
        $data = [
            'tanggal_transfer' => now()->toDateString(),
            'keterangan' => 'Export 2 boxes to client',
            'items' => [
                [
                    'stok_id' => $stokDetail->id,
                    'box' => 1,
                    'berat' => 500,
                    'harga' => 100000,
                ],
                [
                    'stok_id' => $stokDetail->id,
                    'box' => 2,
                    'berat' => 500,
                    'harga' => 100000,
                ]
            ]
        ];

        // 2. Act: Run the storeExport method
        $exportData = $this->exportService->storeExport($data);

        // 3. Assert
        // Check Export Data created
        $this->assertInstanceOf(Export::class, $exportData);
        $this->assertDatabaseHas('exports', [
            'id' => $exportData->id,
            'keterangan' => 'Export 2 boxes to client'
        ]);

        // Check 2 export details inserted
        $this->assertDatabaseCount('export_details', 2);
        
        // Assert StokBarangJadiDetail is REDUCED!
        // Originally: 3 boxes, 1500 berat. Exported: 2 boxes, 1000 berat.
        // Result must be: 1 box, 500 berat.
        $this->assertDatabaseHas('stok_barang_jadi_details', [
            'id' => $stokDetail->id,
            'jumlah_box' => 1,
            'berat' => 500,
        ]);
        
        // --- Test Reverse (Delete / Void) ---
        
        // 4. Act: Void Export
        $this->exportService->delete($exportData->id);
        
        // 5. Assert: Stock has been restored back!
        // Expecting: 3 boxes, 1500 berat.
        $this->assertDatabaseHas('stok_barang_jadi_details', [
            'id' => $stokDetail->id,
            'jumlah_box' => 3,
            'berat' => 1500,
        ]);
    }
}
