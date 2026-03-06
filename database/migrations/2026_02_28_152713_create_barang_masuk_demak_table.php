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
        Schema::create('barang_masuk_demak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_masuk_id');
            $table->foreign('barang_masuk_id')->references('id')->on('barang_masuk')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('nama_barang')->onDelete('cascade');
            $table->string('partai');
            $table->decimal('gram', 10, 2);
            $table->integer('pcs');
            $table->integer('box');
            $table->foreignId('transaksi_dari_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('transaksi_ke_id')->constrained('lokasi')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_demak');
    }
};
