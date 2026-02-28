<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang_keluar';

    protected $fillable = [
        'nama',
    ];

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'jenis_barang_keluar_id');
    }
}
