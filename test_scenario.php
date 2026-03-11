<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$barangLama = DB::table('nama_barang')->first();
if (!$barangLama) {
    DB::table('nama_barang')->insert(['nama_barang' => 'TEST_BARANG']);
    $barangLama = DB::table('nama_barang')->first();
}

DB::table('barang_masuk_demak')->insert([
    'barang_masuk_id' => 1,
    'barang_id' => $barangLama->id,
    'transaksi_dari_id' => 1,
    'transaksi_ke_id' => 1,
    'gram' => 5000,
    'pcs' => 100,
    'box' => 5
]);

$service = app('App\Services\StokGudang\StokGudangService');
echo "Initial:\n";
print_r($service->getDemakSummary()->toArray());

$grading_id = DB::table('stok_gradings')->insertGetId([
    'barang_id' => $barangLama->id,
    'sumber_stok' => 'demak',
    'no_kartu' => 'TEST-001',
    'tanggal' => now(),
    'partai' => 'T',
    'pekerja' => 'P',
    'pcs_awal' => 10,
    'total_gram_keseluruhan' => 1000,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "\nAfter Grading inserted (1000 deducted):\n";
print_r($service->getDemakSummary()->toArray());

$g = \App\Models\StokGrading::find($grading_id);
if ($g) $g->delete();

echo "\nAfter Grading Deleted:\n";
print_r($service->getDemakSummary()->toArray());

DB::table('barang_masuk_demak')->where('gram', 5000)->delete();
