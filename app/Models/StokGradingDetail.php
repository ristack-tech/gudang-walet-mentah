<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokGradingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stok_grading_id',
        'type',
        'grade',
        'berat',
        'box',
    ];

    public function stokGrading()
    {
        return $this->belongsTo(StokGrading::class, 'stok_grading_id');
    }
}
