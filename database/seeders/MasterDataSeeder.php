<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;
use App\Models\NamaBarang;
use App\Models\Partai;
use App\Models\Supplier;
use App\Models\Warna;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lokasi
        $lokasi = [
            ['nama_lokasi' => 'Gudang Demak', 'is_jasa_cuci' => 0],
            ['nama_lokasi' => 'Jasa Cuci A', 'is_jasa_cuci' => 1],
            ['nama_lokasi' => 'Jasa Cuci B', 'is_jasa_cuci' => 1],
            ['nama_lokasi' => 'Gudang Utama', 'is_jasa_cuci' => 0],
        ];

        foreach ($lokasi as $l) {
            Lokasi::updateOrCreate(['nama_lokasi' => $l['nama_lokasi']], $l);
        }

        // Nama Barang
        $barang = [
            ['nama_barang' => 'PLK', 'deskripsi' => 'Plontos Kecil'],
            ['nama_barang' => 'PLB', 'deskripsi' => 'Plontos Besar'],
            ['nama_barang' => 'MANGKOK', 'deskripsi' => 'Bentuk Mangkok'],
            ['nama_barang' => 'SUDUT', 'deskripsi' => 'Bentuk Sudut'],
            ['nama_barang' => 'PATAHAN', 'deskripsi' => 'Barang Patahan'],
            ['nama_barang' => 'HANCURAN', 'deskripsi' => 'Barang Hancuran'],
        ];

        foreach ($barang as $b) {
            NamaBarang::updateOrCreate(['nama_barang' => $b['nama_barang']], $b);
        }

        // Warna
        $warna = [
            ['kode_warna' => 'W01', 'warna' => 'Putih'],
            ['kode_warna' => 'W02', 'warna' => 'Kuning'],
            ['kode_warna' => 'W03', 'warna' => 'Bulu Ringan'],
            ['kode_warna' => 'W04', 'warna' => 'Bulu Berat'],
        ];

        foreach ($warna as $w) {
            Warna::updateOrCreate(['kode_warna' => $w['kode_warna']], $w);
        }

        // Partai
        $partai = [
            ['nama_partai' => 'P-001', 'keterangan' => 'Partai Perdana'],
            ['nama_partai' => 'P-002', 'keterangan' => 'Partai Reguler'],
            ['nama_partai' => 'P-003', 'keterangan' => 'Partai Premium'],
        ];

        foreach ($partai as $p) {
            Partai::updateOrCreate(['nama_partai' => $p['nama_partai']], $p);
        }

        // Supplier
        $supplier = [
            ['nama' => 'Supplier Kalimantan', 'keterangan' => 'Kualitas Tinggi'],
            ['nama' => 'Supplier Sumatera', 'keterangan' => 'Harga Bersaing'],
            ['nama' => 'Supplier Lokal', 'keterangan' => 'Pengiriman Cepat'],
        ];

        foreach ($supplier as $s) {
            Supplier::updateOrCreate(['nama' => $s['nama']], $s);
        }
    }
}
