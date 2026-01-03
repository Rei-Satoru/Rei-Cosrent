<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';

    protected $fillable = [
        'rating',
        'review',
        'order_id',
    ];

    public $timestamps = true;
}
