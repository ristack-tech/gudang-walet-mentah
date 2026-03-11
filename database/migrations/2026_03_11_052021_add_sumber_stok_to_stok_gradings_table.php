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
        Schema::table('stok_gradings', function (Blueprint $table) {
            $table->string('sumber_stok')->nullable()->after('barang_id')->comment('demak or jasa_cuci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_gradings', function (Blueprint $table) {
            $table->dropColumn('sumber_stok');
        });
    }
};
