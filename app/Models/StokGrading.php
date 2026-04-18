<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Keep SoftDeletes if it's not explicitly removed by the instruction, but the snippet removes it from the `use` statement. The snippet also removes it from the `use` trait. I will follow the snippet's implied removal.

class StokGrading extends Model
{
    use HasFactory; // SoftDeletes removed as per snippet

    protected $table = 'stok_gradings';

    protected $guarded = ['id'];
    
    // Explicitly listing fillables for safety (optional if guarded is used, but good practice)
    protected $fillable = [
        'barang_id', // Changed from 'batch_utama'
        'sumber_stok',
        'no_kartu',
        'tanggal',
        'partai',
        'pekerja',
        'box_awal',
        'total_gram_lempeng_bersih',
        'total_pcs_lempeng_bersih',
        'total_box_lempeng_bersih',
        'total_gram_lempeng_kotor',
        'total_pcs_lempeng_kotor',
        'total_box_lempeng_kotor',
        'total_gram_mangkok',
        'total_pcs_mangkok',
        'total_box_mangkok',
        'total_gram_keseluruhan',
        'total_pcs_keseluruhan',
        'total_box_keseluruhan',
        'keterangan',
    ];

    public function namaBarang()
    {
        return $this->belongsTo(NamaBarang::class, 'barang_id');
    }

    public function details()
    {
        return $this->hasMany(StokGradingDetail::class, 'stok_grading_id');
    }
}
