<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokGrading extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch_utama',
        'no_kartu',
        'tanggal',
        'partai',
        'pekerja',
        'total_gram_lempeng_bersih',
        'total_box_lempeng_bersih',
        'total_gram_lempeng_kotor',
        'total_box_lempeng_kotor',
        'total_gram_mangkok',
        'total_box_mangkok',
        'total_gram_keseluruhan',
        'total_box_keseluruhan',
        'keterangan',
    ];

    public function details()
    {
        return $this->hasMany(StokGradingDetail::class, 'stok_grading_id');
    }
}
