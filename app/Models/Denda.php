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
        'bukti_foto_1',
        'bukti_foto_2',
        'bukti_foto_3',
        'bukti_foto_4',
        'bukti_foto_5',
        'bukti_pembayaran',
    ];
}
