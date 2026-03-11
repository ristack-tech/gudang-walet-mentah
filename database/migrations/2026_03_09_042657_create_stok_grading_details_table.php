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
        Schema::create('stok_grading_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_grading_id')->constrained('stok_gradings')->onDelete('cascade');
            $table->string('type');
            $table->string('grade');
            $table->decimal('berat', 15, 2)->default(0);
            $table->integer('pcs')->default(0);
            $table->integer('box')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_grading_details');
    }
};
