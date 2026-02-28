<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'nama',
    ];

    public function barangMasukSupplier()
    {
        return $this->hasMany(BarangMasukPembelianSupplier::class , 'supplier_id');
    }
}
