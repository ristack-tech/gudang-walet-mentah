<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Export extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exports';

    protected $fillable = [
        'batch',
        'tanggal_transfer',
        'keterangan',
    ];

    public function exportDetails()
    {
        return $this->hasMany(ExportDetail::class, 'export_id');
    }
}
