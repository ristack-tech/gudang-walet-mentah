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
        Schema::table('stok_barang_jadi_details', function (Blueprint $table) {
            $table->renameColumn('jumlah_pcs', 'jumlah_box');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_barang_jadi_details', function (Blueprint $table) {
            $table->renameColumn('jumlah_box', 'jumlah_pcs');
        });
    }
};
