<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'formulir_id',
        'gambar1',
        'gambar2',
        'gambar3',
        'status',
        'catatan',
        'catatan_admin',
    ];

    public function formulir()
    {
        return $this->belongsTo(Formulir::class, 'formulir_id');
    }
}