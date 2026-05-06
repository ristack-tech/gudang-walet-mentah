<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk_jasa_cuci', function (Blueprint $table) {
            $table->decimal('kadar_air', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk_jasa_cuci', function (Blueprint $table) {
            $table->decimal('kadar_air', 8, 2)->nullable(false)->change();
        });
    }
};