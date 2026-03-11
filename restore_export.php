<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Schema::table('export_barang', function (Blueprint $table) {
        if (Schema::hasColumn('export_barang', 'stok_barang_jadi_detail_id')) {
            // First drop foreign key if it somehow exists
            try {
                $table->dropForeign(['stok_barang_jadi_detail_id']);
            } catch (\Exception $e) {}
            
            $table->dropColumn('stok_barang_jadi_detail_id');
        }
        
        if (!Schema::hasColumn('export_barang', 'barang_id')) {
            $table->foreignId('barang_id')->after('tanggal_transfer')->constrained('nama_barang')->onDelete('cascade');
        }
    });
    echo "Restoration success\n";
    
    // Also delete the migration record to allow fresh migrate
    \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', 'like', '%update_export_barang_to_use_detail_id%')
        ->delete();
        
} catch (\Exception $e) {
    echo "Error restoring table: " . $e->getMessage() . "\n";
}
