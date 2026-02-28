<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'jenis_barang_masuk_id',
        'tanggal_terima',
    ];

    public function jenisBarangMasuk()
    {
        return $this->belongsTo(JenisBarangMasuk::class , 'jenis_barang_masuk_id');
    }

    public function barangMasukDemak()
    {
        return $this->hasMany(BarangMasukDemak::class , 'barang_masuk_id');
    }

    public function barangMasukJasaCuci()
    {
        return $this->hasMany(BarangMasukJasaCuci::class , 'barang_masuk_id');
    }

    public function barangMasukSupplier()
    {
        return $this->hasMany(BarangMasukPembelianSupplier::class , 'barang_masuk_id');
    }
}
