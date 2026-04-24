<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'export_details';

    protected $fillable = [
        'export_id',
        'stok_barang_jadi_detail_id',
        'berat',
        'pcs',
        'box',
        'harga',
    ];

    public function export()
    {
        return $this->belongsTo(Export::class, 'export_id');
    }

    public function stokBarangJadiDetail()
    {
        return $this->belongsTo(StokBarangJadiDetail::class, 'stok_barang_jadi_detail_id');
    }
}
