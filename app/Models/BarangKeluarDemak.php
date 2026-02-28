<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarDemak extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_demak';

    protected $fillable = [
        'barang_keluar_id',
        'partai_id',
        'warna_id',
        'kode_lp',
        'gram',
        'pcs',
        'kadar_air',
        'timbangan_ulang',
        'keterangan',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    public function partai()
    {
        return $this->belongsTo(Partai::class, 'partai_id');
    }

    public function warna()
    {
        return $this->belongsTo(Warna::class, 'warna_id');
    }
}
