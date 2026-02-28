<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaBarang extends Model
{
    use HasFactory;

    protected $table = 'nama_barang';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
    ];

    public function barangKeluarJasaCuci()
    {
        return $this->hasMany(BarangKeluarJasaCuci::class, 'barang_id');
    }
}
