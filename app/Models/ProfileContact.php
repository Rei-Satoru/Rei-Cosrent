<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileContact extends Model
{
    use HasFactory;

    protected $table = 'profile_contacts';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'title',
        'photo',
        'vision',
        'address',
        'phone',
        'email',
        // Payment fields
        'nomor_ewallet',
        'nomor_bank',
        'qris',
    ];
}
