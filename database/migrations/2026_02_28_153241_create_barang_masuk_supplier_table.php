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
        Schema::create('barang_masuk_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('nama_barang')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->decimal('berat', 10, 2);
            $table->integer('pcs');
            $table->integer('box');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_supplier');
    }
};
