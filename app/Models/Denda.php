<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'nama',
        'nama_kostum',
        'jenis_denda',
        'keterangan',
        'jumlah_denda',
        'status',
        'bukti_foto',
        'bukti_pembayaran',
    ];
}
