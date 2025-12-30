<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKostum extends Model
{
    protected $table = 'data_kostum';
    protected $primaryKey = 'id_kostum';
    public $timestamps = false;

    protected $fillable = [
        'kategori',
        'nama_kostum',
        'judul',
        'harga_sewa',
        'durasi_penyewaan',
        'ukuran_kostum',
        'jenis_kelamin',
        'include',
        'exclude',
        'domisili',
        'brand',
        'gambar'
    ];
}
