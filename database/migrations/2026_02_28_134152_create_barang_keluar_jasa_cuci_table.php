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
        Schema::create('barang_keluar_jasa_cuci', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_keluar_id')->constrained('barang_keluar')->onDelete('cascade');
            $table->foreignId('kode_bahan_id')->constrained('kode_bahan')->onDelete('cascade');
            $table->text('keterangan_cetak')->nullable();
            $table->decimal('berat_per_pcs', 10, 2);
            $table->decimal('kadar_air', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar_jasa_cuci');
    }
};
