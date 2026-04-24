<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukDemak extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_demak';

    protected $fillable = [
        'barang_masuk_id',
        'barang_keluar_demak_id',
        'barang_id',
        'partai',
        'gram',
        'pcs',
        'box',
        'transaksi_dari_id',
        'transaksi_ke_id',
        'keterangan',
        'susut_per_item',
    ];

    public function barangKeluarDemak()
    {
        return $this->belongsTo(BarangKeluarDemak::class, 'barang_keluar_demak_id');
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class , 'barang_masuk_id');
    }

    public function barang()
    {
        return $this->belongsTo(NamaBarang::class , 'barang_id');
    }

    public function transaksiDari()
    {
        return $this->belongsTo(Lokasi::class , 'transaksi_dari_id');
    }

    public function transaksiKe()
    {
        return $this->belongsTo(Lokasi::class , 'transaksi_ke_id');
    }
}
