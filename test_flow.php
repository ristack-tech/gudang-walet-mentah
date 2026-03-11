<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app('App\Services\StokGudang\StokGudangService');
$gradingService = app('App\Services\Admin\Transaksi\StokGrading\StokGradingService');

// Get stock before grading
echo "--- BEFORE GRADING ---\n";
print_r($service->getDemakSummary()->toArray());

// Create a grading
$grading = $gradingService->createGrading([
    'barang_id' => 1,
    'sumber_stok' => 'demak',
    'partai' => 'TEST',
    'pcs_awal' => 10,
    'grades' => [
        ['type' => 'lempeng_bersih', 'name' => 'ZT', 'berat' => 100, 'box' => 1]
    ]
]);

echo "\n--- AFTER GRADING CREATED (ID: {$grading->id}) ---\n";
print_r($service->getDemakSummary()->toArray());

// Delete the grading
$gradingService->deleteGrading($grading->id);

echo "\n--- AFTER GRADING DELETED ---\n";
print_r($service->getDemakSummary()->toArray());
