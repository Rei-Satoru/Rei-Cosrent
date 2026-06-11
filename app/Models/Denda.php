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

    /**
     * Relationship to Formulir model
     * Note: This assumes there's a formulir_id column or we match by nama_kostum
     */
    public function formulir()
    {
        // If formulir_id exists, use it; otherwise relationship returns null
        if ($this->hasColumn('formulir_id')) {
            return $this->belongsTo(Formulir::class, 'formulir_id');
        }
        
        // Fallback: try to match by nama_kostum
        return $this->belongsTo(Formulir::class, 'nama_kostum', 'nama_kostum');
    }

    /**
     * Helper to check if column exists
     */
    private function hasColumn($column)
    {
        return \Illuminate\Support\Facades\Schema::hasColumn($this->table, $column);
    }
}
