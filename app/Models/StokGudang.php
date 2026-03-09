<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    use HasFactory;

    protected $table = 'stok_gudangs';

    protected $fillable = [
        'lokasi_id',
        'barang_id',
        'total_gram',
        'total_pcs',
        'total_box'
    ];
}
