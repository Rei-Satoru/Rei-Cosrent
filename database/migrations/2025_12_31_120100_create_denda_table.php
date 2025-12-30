<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_kostum')->nullable();
            $table->string('jenis_denda')->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('jumlah_denda', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('bukti_foto')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('denda');
    }
}
