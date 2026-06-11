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
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
