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
        Schema::create('stok_barang_jadi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_barang_jadi_id')->constrained('stok_barang_jadis')->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('jumlah_pcs')->default(0);
            $table->decimal('berat', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barang_jadi_details');
    }
};
