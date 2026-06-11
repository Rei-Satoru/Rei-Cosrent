<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileContact extends Model
{
    use HasFactory;

    protected $table = 'admin';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'title',
        'photo',
        'vision',
        'address',
        'phone',
        'instagram',
        'email',
        'password',
        // Payment fields
        'nomor_ewallet',
        'nomor_bank',
        'qris',
        // Origin for RajaOngkir
        'origin_province_id',
        'origin_city_id',
        'origin_postal_code',
    ];

    protected $hidden = [
        'password',
    ];
}
