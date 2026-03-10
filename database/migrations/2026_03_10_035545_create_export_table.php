<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_barang', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transfer');
            $table->foreignId('barang_id')->constrained('nama_barang');
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
