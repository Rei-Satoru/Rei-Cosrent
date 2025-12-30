<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKatalog extends Model
{
    use HasFactory;

    protected $table = 'data_katalog';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'kategori',
        'description',
        'image',
    ];
}
