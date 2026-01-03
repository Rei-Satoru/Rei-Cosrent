<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'rating',
        'review',
        'balasan',
        'gambar_1',
        'gambar_2',
        'gambar_3',
        'gambar_4',
        'gambar_5',
    ];

    /**
     * Get the order (formulir) that this review belongs to.
     */
    public function formulir()
    {
        return $this->belongsTo(Formulir::class, 'id', 'id');
    }
}
