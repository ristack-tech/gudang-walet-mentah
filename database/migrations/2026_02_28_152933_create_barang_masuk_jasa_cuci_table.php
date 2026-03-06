<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_masuk_jasa_cuci', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('lokasi_jasa_cuci_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('nama_barang')->onDelete('cascade');
            $table->foreignId('kode_bahan_id')->constrained('kode_bahan')->onDelete('cascade');
            $table->decimal('berat_per_pcs', 10, 2);
            $table->decimal('persentase_susut', 8, 2);
            $table->decimal('kadar_air', 8, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_jasa_cuci');
    }
};
