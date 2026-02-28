<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarJasaCuci extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_jasa_cuci';

    protected $fillable = [
        'barang_keluar_id',
        'nama_barang_id',
        'kode_bahan_id',
        'keterangan_cetak',
        'berat_per_pcs',
        'kadar_air',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class , 'barang_keluar_id');
    }

    public function namaBarang()
    {
        return $this->belongsTo(NamaBarang::class , 'nama_barang_id');
    }

    public function kodeBahan()
    {
        return $this->belongsTo(KodeBahan::class , 'kode_bahan_id');
    }
}