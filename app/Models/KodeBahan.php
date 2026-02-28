<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeBahan extends Model
{
    use HasFactory;

    protected $table = 'kode_bahan';

    protected $fillable = [
        'nama',
    ];

    public function barangKeluarJasaCuci()
    {
        return $this->hasMany(BarangKeluarJasaCuci::class, 'kode_bahan_id');
    }
}
