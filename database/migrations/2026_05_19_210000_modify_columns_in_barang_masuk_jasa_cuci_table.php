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
        Schema::table('barang_masuk_jasa_cuci', function (Blueprint $table) {
            if (Schema::hasColumn('barang_masuk_jasa_cuci', 'kadar_air')) {
                $table->dropColumn('kadar_air');
            }
            if (!Schema::hasColumn('barang_masuk_jasa_cuci', 'berat_timbangan_gudang')) {
                $table->decimal('berat_timbangan_gudang', 10, 2)->nullable();
            }
            if (Schema::hasColumn('barang_masuk_jasa_cuci', 'persentase_susut')) {
                $table->renameColumn('persentase_susut', 'persentase_selisih');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk_jasa_cuci', function (Blueprint $table) {
            if (!Schema::hasColumn('barang_masuk_jasa_cuci', 'kadar_air')) {
                $table->decimal('kadar_air', 8, 2)->nullable();
            }
            if (Schema::hasColumn('barang_masuk_jasa_cuci', 'berat_timbangan_gudang')) {
                $table->dropColumn('berat_timbangan_gudang');
            }
            if (Schema::hasColumn('barang_masuk_jasa_cuci', 'persentase_selisih')) {
                $table->renameColumn('persentase_selisih', 'persentase_susut');
            }
        });
    }
};
