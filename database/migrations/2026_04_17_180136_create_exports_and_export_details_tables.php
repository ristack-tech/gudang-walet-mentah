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
        // Hapus tabel lama
        Schema::dropIfExists('export_barang');
        
        // Buat tabel parent (Batch Export)
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->string('batch')->unique();
            $table->date('tanggal_transfer');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Buat tabel child (Detail Barang di dalam Export)
        Schema::create('export_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('export_id')->constrained('exports')->onDelete('cascade');
            $table->foreignId('stok_barang_jadi_detail_id')->constrained('stok_barang_jadi_details')->onDelete('cascade');
            $table->decimal('berat', 10, 2);
            $table->integer('pcs')->default(0);
            $table->integer('box')->default(0);
            $table->decimal('harga', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_details');
        Schema::dropIfExists('exports');
        
        // Jika rollback, buat ulang tabel lama agar tidak error
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
};
