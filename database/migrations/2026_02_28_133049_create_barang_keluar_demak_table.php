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
        Schema::create('barang_keluar_demak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_keluar_id')->constrained('barang_keluar')->onDelete('cascade');
            $table->foreignId('partai_id')->constrained('partai')->onDelete('cascade')->nullable();
            $table->foreignId('warna_id')->constrained('warna')->onDelete('cascade');
            $table->string('kode_lp');
            $table->decimal('gram', 10, 2);
            $table->integer('pcs');
            $table->decimal('kadar_air', 8, 2);
            $table->decimal('timbangan_ulang', 10, 2)->nullable();
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
        Schema::dropIfExists('barang_keluar_demak');
    }
};
