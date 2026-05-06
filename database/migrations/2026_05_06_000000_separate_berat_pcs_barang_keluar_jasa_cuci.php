<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns first (MySQL safer approach)
        Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->decimal('berat', 10, 2)->nullable()->after('keterangan_cetak');
            $table->decimal('pcs', 10, 2)->nullable()->after('berat');
        });

        // Copy data from berat_per_pcs to berat
        DB::statement('UPDATE barang_keluar_jasa_cuci SET berat = berat_per_pcs');

        // Drop old column
        Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->dropColumn('berat_per_pcs');
        });

        // Make kadar_air nullable
        Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->decimal('kadar_air', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Add back berat_per_pcs
        Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->decimal('berat_per_pcs', 10, 2)->nullable()->after('keterangan_cetak');
        });

        // Copy data back
        DB::statement('UPDATE barang_keluar_jasa_cuci SET berat_per_pcs = berat');

        // Drop new columns
        Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->dropColumn(['berat', 'pcs']);
        });

        // Make kadar_air not nullable
        Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->decimal('kadar_air', 8, 2)->nullable(false)->change();
        });
    }
};