<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukPembelianSupplier extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_supplier';

    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'supplier_id',
        'berat',
        'pcs',
        'box',
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class , 'barang_masuk_id');
    }

    public function barang()
    {
        return $this->belongsTo(NamaBarang::class , 'barang_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class , 'supplier_id');
    }
}
