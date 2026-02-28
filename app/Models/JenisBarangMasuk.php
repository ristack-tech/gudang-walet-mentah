<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang_masuk';

    protected $fillable = [
        'nama',
    ];

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class , 'jenis_barang_masuk_id');
    }
}
