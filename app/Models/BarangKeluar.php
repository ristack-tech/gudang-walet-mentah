<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'jenis_barang_keluar_id',
        'tanggal_kirim',
        'lokasi_id',
    ];

    public function jenisBarangKeluar()
    {
        return $this->belongsTo(JenisBarangKeluar::class, 'jenis_barang_keluar_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function barangKeluarDemak()
    {
        return $this->hasMany(BarangKeluarDemak::class, 'barang_keluar_id');
    }

    public function barangKeluarJasaCuci()
    {
        return $this->hasMany(BarangKeluarJasaCuci::class, 'barang_keluar_id');
    }
}
