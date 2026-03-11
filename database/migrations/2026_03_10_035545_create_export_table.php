<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('export_barang');
        Schema::create('export_barang', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transfer');
            $table->foreignId('stok_barang_jadi_detail_id')->constrained('stok_barang_jadi_details')->onDelete('cascade');
            $table->decimal('berat', 10, 2);
            $table->integer('pcs');
            $table->integer('box');
            $table->decimal('harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_barang');
    }
};
