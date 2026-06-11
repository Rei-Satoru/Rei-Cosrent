<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDate extends Model
{
    use HasFactory;

    protected $table = 'booking_dates';

    protected $fillable = [
        'sheet_code',
        'sheet_title',
        'anime_title',
        'character_no',
        'character_name',
        'sort_order',
        'slot_1_label', 'slot_1_value',
        'slot_2_label', 'slot_2_value',
        'slot_3_label', 'slot_3_value',
        'slot_4_label', 'slot_4_value',
        'slot_5_label', 'slot_5_value',
        'slot_6_label', 'slot_6_value',
        'slot_7_label', 'slot_7_value',
        'slot_8_label', 'slot_8_value',
        'slot_9_label', 'slot_9_value',
        'slot_10_label', 'slot_10_value',
        'slot_11_label', 'slot_11_value',
        'slot_12_label', 'slot_12_value',
        'slot_13_label', 'slot_13_value',
    ];

    public static function slotIndexes(): array
    {
        return range(1, 13);
    }

    public static function slotLabelColumn(int $index): string
    {
        return 'slot_' . $index . '_label';
    }

    public static function slotValueColumn(int $index): string
    {
        return 'slot_' . $index . '_value';
    }
}