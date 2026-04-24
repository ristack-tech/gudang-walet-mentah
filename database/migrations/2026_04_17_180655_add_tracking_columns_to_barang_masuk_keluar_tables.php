<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('barang_keluar', 'status_lunas')) {
            Schema::table('barang_keluar', function (Blueprint $table) {
                $table->boolean('status_lunas')->default(false);
            });
        }

        if (!Schema::hasColumn('barang_keluar_demak', 'status_kembali')) {
            Schema::table('barang_keluar_demak', function (Blueprint $table) {
                $table->boolean('status_kembali')->default(false);
            });
        }

        if (!Schema::hasColumn('barang_keluar_jasa_cuci', 'status_kembali')) {
            Schema::table('barang_keluar_jasa_cuci', function (Blueprint $table) {
                $table->boolean('status_kembali')->default(false);
            });
        }

        if (!Schema::hasColumn('barang_masuk', 'barang_keluar_id')) {
            Schema::table('barang_masuk', function (Blueprint $table) {
                $table->unsignedBigInteger('barang_keluar_id')->nullable();
                $table->foreign('barang_keluar_id')->references('id')->on('barang_keluar')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('barang_masuk', 'total_susut_batch')) {
            Schema::table('barang_masuk', function (Blueprint $table) {
                $table->float('total_susut_batch')->nullable();
            });
        }

        if (!Schema::hasColumn('barang_masuk_demak', 'barang_keluar_demak_id')) {
            Schema::table('barang_masuk_demak', function (Blueprint $table) {
                $table->unsignedBigInteger('barang_keluar_demak_id')->nullable();
                $table->foreign('barang_keluar_demak_id')->references('id')->on('barang_keluar_demak')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('barang_masuk_demak', 'susut_per_item')) {
            Schema::table('barang_masuk_demak', function (Blueprint $table) {
                $table->float('susut_per_item')->nullable();
            });
        }

        if (!Schema::hasColumn('barang_masuk_jasa_cuci', 'barang_keluar_jasa_cuci_id')) {
            Schema::table('barang_masuk_jasa_cuci', function (Blueprint $table) {
                $table->unsignedBigInteger('barang_keluar_jasa_cuci_id')->nullable();
                $table->foreign('barang_keluar_jasa_cuci_id')->references('id')->on('barang_keluar_jasa_cuci')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('barang_masuk_jasa_cuci', 'susut_per_item')) {
            Schema::table('barang_masuk_jasa_cuci', function (Blueprint $table) {
                $table->float('susut_per_item')->nullable();
            });
        }
    }

    public function down(): void
    {
        // 
    }
};
