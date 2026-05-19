<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukJasaCuci extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_jasa_cuci';

    protected $fillable = [
        'barang_masuk_id',
        'barang_keluar_jasa_cuci_id',
        'lokasi_jasa_cuci_id',
        'barang_id',
        'kode_bahan_id',
        'berat_per_pcs',
        'persentase_selisih',
        'berat_timbangan_gudang',
        'susut_per_item',
    ];

    public function barangKeluarJasaCuci()
    {
        return $this->belongsTo(BarangKeluarJasaCuci::class, 'barang_keluar_jasa_cuci_id');
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class , 'barang_masuk_id');
    }

    public function barang()
    {
        return $this->belongsTo(NamaBarang::class , 'barang_id');
    }

    public function lokasiJasaCuci()
    {
        return $this->belongsTo(Lokasi::class , 'lokasi_jasa_cuci_id');
    }

    public function kodeBahan()
    {
        return $this->belongsTo(KodeBahan::class , 'kode_bahan_id');
    }
}
