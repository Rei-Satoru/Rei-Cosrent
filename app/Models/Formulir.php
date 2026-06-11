<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Schema;

class Formulir extends Model
{
    use HasFactory;

    protected $table = 'formulir';

    protected $fillable = [
        'nama',
        'email',
        'alamat',
        'nomor_telepon',
        'nomor_telepon_2',
        'nama_kostum',
        'tanggal_pemakaian',
        'tanggal_pengembalian',
        'total_harga',
        'metode_pembayaran',
        'bukti_pembayaran',
        'kartu_identitas',
        'foto_kartu_identitas',
        'selfie_kartu_identitas',
        'pernyataan',
        'status',
        'ongkir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pemakaian' => 'date',
        'tanggal_pengembalian' => 'date',
        'total_harga' => 'decimal:2',
        'ongkir' => 'decimal:2',
    ];

    // No user relation anymore; email is stored directly on the order

    public function pembayarans()
    {
        return $this->hasMany(\App\Models\Pembayaran::class, 'formulir_id');
    }

    public function pembayaran()
    {
        // latest pembayaran for this formulir
        return $this->hasOne(\App\Models\Pembayaran::class, 'formulir_id')->latestOfMany();
    }

    public function pengembalian()
    {
        return $this->hasOne(\App\Models\Pengembalian::class, 'formulir_id')->latestOfMany();
    }

    public function getPengembalianSafeAttribute()
    {
        try {
            if (Schema::hasColumn('pengembalian', 'formulir_id')) {
                return $this->pengembalian()->first();
            }

            return Pengembalian::latest()->first();
        } catch (\Exception $e) {
            return Pengembalian::latest()->first();
        }
    }

    /**
     * Safe accessor for latest pembayaran that won't throw if the column doesn't exist yet.
     * Usage: $formulir->pembayaran_safe
     */
    public function getPembayaranSafeAttribute()
    {
        try {
            if (!Schema::hasColumn('pembayaran', 'formulir_id')) {
                return null;
            }

            return $this->pembayaran()->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
