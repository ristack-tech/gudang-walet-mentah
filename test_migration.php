<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Schema::table('export_barang', function (Blueprint $table) {
        $table->dropForeign('export_barang_barang_id_foreign');
    });
    echo "Success dropping foreign key\n";
} catch (\Exception $e) {
    echo "Error dropping foreign key: " . $e->getMessage() . "\n";
}
