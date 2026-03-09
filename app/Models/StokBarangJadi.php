<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokBarangJadi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch',
        'tanggal',
        'type',
        'keterangan',
    ];

    public function details()
    {
        return $this->hasMany(StokBarangJadiDetail::class, 'stok_barang_jadi_id');
    }
}
