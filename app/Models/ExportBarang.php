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
        'barang_id',
        'berat',
        'pcs',
        'box',
        'harga',
    ];

    public function barang()
    {
        return $this->belongsTo(NamaBarang::class, 'barang_id');
    }
}
