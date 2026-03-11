<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First change type and name
        DB::statement('ALTER TABLE stok_gradings CHANGE batch_utama barang_id bigint unsigned DEFAULT NULL');
        
        Schema::table('stok_gradings', function (Blueprint $table) {
            $table->foreign('barang_id')->references('id')->on('nama_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_gradings', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
        });
        
        DB::statement('ALTER TABLE stok_gradings CHANGE barang_id batch_utama varchar(255) DEFAULT NULL');
    }
};
