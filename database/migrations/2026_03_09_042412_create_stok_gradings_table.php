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
        Schema::create('stok_gradings', function (Blueprint $table) {
            $table->id();
            $table->string('batch_utama')->nullable();
            $table->string('no_kartu')->nullable();
            $table->date('tanggal');
            $table->string('partai')->nullable();
            $table->string('pekerja')->nullable();
            $table->integer('pcs_awal')->default(0);
            $table->decimal('total_gram_lempeng_bersih', 15, 2)->default(0);
            $table->integer('total_pcs_lempeng_bersih')->default(0);
            $table->integer('total_box_lempeng_bersih')->default(0);
            $table->decimal('total_gram_lempeng_kotor', 15, 2)->default(0);
            $table->integer('total_pcs_lempeng_kotor')->default(0);
            $table->integer('total_box_lempeng_kotor')->default(0);
            $table->decimal('total_gram_mangkok', 15, 2)->default(0);
            $table->integer('total_pcs_mangkok')->default(0);
            $table->integer('total_box_mangkok')->default(0);
            $table->decimal('total_gram_keseluruhan', 15, 2)->default(0);
            $table->integer('total_pcs_keseluruhan')->default(0);
            $table->integer('total_box_keseluruhan')->default(0);
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
        Schema::dropIfExists('stok_gradings');
    }
};
