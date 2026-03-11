<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportBarang extends Model
{
    use HasFactory;

    protected $table = 'export_barang';

    protected $fillable = [
        'tanggal_transfer',
        'stok_barang_jadi_detail_id',
        'berat',
        'pcs',
        'box',
        'harga',
    ];

    public function stokBarangJadiDetail()
    {
        return $this->belongsTo(StokBarangJadiDetail::class, 'stok_barang_jadi_detail_id');
    }
}
