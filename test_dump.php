<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::enableQueryLog();
$service = app('App\Services\StokGudang\StokGudangService');
$summary = $service->getDemakSummary();
print_r($summary->toArray());
print_r(DB::getQueryLog());
