<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formulir', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('nomor_telepon', 20);
            $table->string('nomor_telepon_2', 100)->comment('Nomor pihak lain (misal orang tua)');
            $table->string('nama_kostum', 100);
            $table->date('tanggal_pemakaian');
            $table->date('tanggal_pengembalian');
            $table->decimal('total_harga', 12, 2)->comment('Kostum + ongkir');
            $table->string('metode_pembayaran', 50);
            $table->string('kartu_identitas', 50)->comment('Jenis identitas (KTP/SIM/KTM)');
            $table->string('foto_kartu_identitas', 255)->comment('Path foto kartu identitas');
            $table->string('selfie_kartu_identitas', 255)->comment('Path selfie dengan kartu identitas');
            $table->text('pernyataan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulir');
    }
};
