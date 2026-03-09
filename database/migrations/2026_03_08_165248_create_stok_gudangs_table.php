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
        Schema::create('stok_gudangs', function (Blueprint $table) {
            $table->id();
            
            // Lokasi (Demak / Jasa Cuci)
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            
            // Barang (Mangkok, Lempeng, dsb)
            $table->foreignId('barang_id')->constrained('nama_barang')->onDelete('cascade');
            
            // Kuantitas Balance (Current Stock)
            $table->decimal('total_gram', 15, 2)->default(0);
            $table->integer('total_pcs')->default(0);
            $table->integer('total_box')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_gudangs');
    }
};
