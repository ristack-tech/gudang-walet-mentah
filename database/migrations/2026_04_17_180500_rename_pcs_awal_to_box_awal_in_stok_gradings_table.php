<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_gradings', function (Blueprint $table) {
            $table->renameColumn('pcs_awal', 'box_awal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_gradings', function (Blueprint $table) {
            $table->renameColumn('box_awal', 'pcs_awal');
        });
    }
};
