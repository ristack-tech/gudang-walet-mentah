<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaBarang extends Model
{
    use HasFactory;

    protected $table = 'nama_barang';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
    ];

    public function barangMasukJasaCuci()
    {
        return $this->hasMany(BarangMasukJasaCuci::class , 'barang_id');
    }

    public function barangMasukDemak()
    {
        return $this->hasMany(BarangMasukDemak::class , 'barang_id');
    }

    public function barangMasukSupplier()
    {
        return $this->hasMany(BarangMasukPembelianSupplier::class , 'barang_id');
    }

    public function barangKeluarJasaCuci()
    {
        return $this->hasMany(BarangKeluarJasaCuci::class , 'nama_barang_id');
    }

    public function getNamaAttribute()
    {
        return $this->nama_barang;
    }
}
