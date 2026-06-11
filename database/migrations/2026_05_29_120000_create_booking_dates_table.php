<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_dates', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_code', 10);
            $table->string('sheet_title', 50);
            $table->string('anime_title', 100);
            $table->unsignedTinyInteger('character_no');
            $table->string('character_name', 150);
            $table->unsignedSmallInteger('sort_order')->default(0);

            for ($index = 1; $index <= 13; $index++) {
                $table->string('slot_' . $index . '_label', 50);
                $table->string('slot_' . $index . '_value', 100)->nullable();
            }

            $table->timestamps();
            $table->index(['sheet_code', 'sort_order']);
            $table->unique(['sheet_code', 'anime_title', 'character_no', 'character_name'], 'booking_dates_unique_row');
        });

        $timestamp = now();

        $slotHeadersQ3 = ['Tanggal 5-6', 'Tanggal 12-13', 'Tanggal 19-20', 'Tanggal 26-27', 'Tanggal 2-3', 'Tanggal 9-10', 'Tanggal 16-17', 'Tanggal 23-24', 'Tanggal 30-31', 'Tanggal 6-7', 'Tanggal 13-14', 'Tanggal 20-21', 'Tanggal 27-28'];
        $slotHeadersQ4 = ['Tanggal 4-5', 'Tanggal 11-12', 'Tanggal 18-19', 'Tanggal 25-26', 'Tanggal 1-2', 'Tanggal 8-9', 'Tanggal 15-16', 'Tanggal 22-23', 'Tanggal 29-30', 'Tanggal 6-7', 'Tanggal 13-14', 'Tanggal 20-21', 'Tanggal 27-28'];

        $buildRow = function (string $sheetCode, string $sheetTitle, string $animeTitle, int $characterNo, string $characterName, array $slotHeaders, array $slotValues, int $sortOrder) use ($timestamp): array {
            $row = [
                'sheet_code' => $sheetCode,
                'sheet_title' => $sheetTitle,
                'anime_title' => $animeTitle,
                'character_no' => $characterNo,
                'character_name' => $characterName,
                'sort_order' => $sortOrder,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            foreach ($slotHeaders as $index => $label) {
                $slotNumber = $index + 1;
                $row['slot_' . $slotNumber . '_label'] = $label;
                $row['slot_' . $slotNumber . '_value'] = $slotValues[$index] ?? null;
            }

            return $row;
        };

        $rows = [
            $buildRow('Q3', '2025 Q3', 'Jujutsu Kaisen', 1, 'Gojo Satoru', $slotHeadersQ3, [null, '@riyuzu_sama', null, null, null, null, null, null, null, null, null, null, null], 1),
            $buildRow('Q3', '2025 Q3', 'Jujutsu Kaisen', 2, 'Megumi Fushiguro', $slotHeadersQ3, [null, null, null, null, '@audricc.6', null, null, null, null, null, null, null, null], 2),
            $buildRow('Q3', '2025 Q3', 'Jujutsu Kaisen', 3, 'Yuta Okkotsu', $slotHeadersQ3, [null, null, null, null, null, null, null, null, null, null, null, null, null], 3),
            $buildRow('Q3', '2025 Q3', 'Owari No Seraph', 1, 'Guren Ichinose', $slotHeadersQ3, [null, null, null, null, null, null, null, null, null, null, null, null, null], 4),
            $buildRow('Q3', '2025 Q3', 'Owari No Seraph', 2, 'Shinya Hiiragi', $slotHeadersQ3, [null, null, null, null, null, null, null, null, null, null, null, null, null], 5),
            $buildRow('Q3', '2025 Q3', 'Chainsaw Man', 1, 'Denji', $slotHeadersQ3, [null, '@emu.sadily', null, null, null, null, null, null, null, null, null, null, null], 6),
            $buildRow('Q3', '2025 Q3', 'Chainsaw Man', 2, 'Kishibe', $slotHeadersQ3, [null, null, null, null, null, null, null, null, null, null, null, null, null], 7),
            $buildRow('Q4', '2025 Q4', 'Jujutsu Kaisen', 1, 'Gojo Satoru', $slotHeadersQ4, [null, null, null, null, null, null, null, null, null, null, null, null, null], 1),
            $buildRow('Q4', '2025 Q4', 'Jujutsu Kaisen', 2, 'Megumi Fushiguro', $slotHeadersQ4, [null, null, null, null, null, null, null, null, null, null, null, null, null], 2),
            $buildRow('Q4', '2025 Q4', 'Jujutsu Kaisen', 3, 'Yuta Okkotsu', $slotHeadersQ4, [null, null, null, null, null, null, null, null, null, null, null, null, null], 3),
            $buildRow('Q4', '2025 Q4', 'Owari No Seraph', 1, 'Guren Ichinose', $slotHeadersQ4, [null, null, '@ryyuu_taa', null, null, null, null, null, null, null, null, null, null], 4),
            $buildRow('Q4', '2025 Q4', 'Owari No Seraph', 2, 'Shinya Hiiragi', $slotHeadersQ4, [null, null, null, null, null, null, null, null, null, null, null, null, null], 5),
            $buildRow('Q4', '2025 Q4', 'Chainsaw Man', 1, 'Denji', $slotHeadersQ4, [null, null, '@bro.im08', null, null, null, null, null, null, null, null, null, null], 6),
            $buildRow('Q4', '2025 Q4', 'Chainsaw Man', 2, 'Kishibe', $slotHeadersQ4, [null, null, null, null, null, null, null, null, null, null, null, null, null], 7),
        ];

        DB::table('booking_dates')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_dates');
    }
};