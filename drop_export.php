<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Schema::dropIfExists('export_barang');
    DB::table('migrations')->where('migration', 'like', '%export%')->delete();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "Drop success\n";
} catch (\Exception $e) {
    echo "Error dropping table: " . $e->getMessage() . "\n";
}
