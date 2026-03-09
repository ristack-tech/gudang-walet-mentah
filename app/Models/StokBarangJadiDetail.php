<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokBarangJadiDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stok_barang_jadi_id',
        'nama_barang',
        'jumlah_pcs',
        'berat',
    ];

    public function stokBarangJadi()
    {
        return $this->belongsTo(StokBarangJadi::class, 'stok_barang_jadi_id');
    }
}
