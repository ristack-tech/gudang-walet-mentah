<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pre = app('App\Services\StokGudang\StokGudangService')->getDemakSummary();
$grading = \App\Models\StokGrading::first();

if($grading) {
    echo "Deleting grading ID: " . $grading->id . "\n";
    $grading->delete();
} else {
    echo "No grading found.\n";
}

$post = app('App\Services\StokGudang\StokGudangService')->getDemakSummary();
echo "\nPRE:\n";
print_r($pre->toArray());
echo "\nPOST:\n";
print_r($post->toArray());
