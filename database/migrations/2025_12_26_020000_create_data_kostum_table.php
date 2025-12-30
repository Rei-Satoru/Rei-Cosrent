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
        Schema::create('data_kostum', function (Blueprint $table) {
            $table->id('id_kostum');
            $table->string('kategori');
            $table->string('nama_kostum');
            $table->string('judul');
            $table->decimal('harga_sewa', 10, 2);
            $table->string('durasi_penyewaan', 100);
            $table->string('ukuran_kostum', 100);
            $table->enum('jenis_kelamin', ['Pria', 'Wanita', 'Unisex']);
            $table->text('include')->nullable();
            $table->text('exclude')->nullable();
            $table->string('domisili');
            $table->string('brand')->nullable();
            $table->text('gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kostum');
    }
};
