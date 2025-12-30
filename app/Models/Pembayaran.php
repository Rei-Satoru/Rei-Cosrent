<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'nomor_ewallet',
        'nomor_bank',
        'qris',
        'bukti_pembayaran',
        'formulir_id',
    ];

    public function formulir()
    {
        return $this->belongsTo(\App\Models\Formulir::class, 'formulir_id');
    }
}
