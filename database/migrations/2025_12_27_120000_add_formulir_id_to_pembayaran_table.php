<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran', 'formulir_id')) {
                $table->unsignedBigInteger('formulir_id')->nullable()->after('bukti_pembayaran');
                $table->foreign('formulir_id')->references('id')->on('formulir')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'formulir_id')) {
                $table->dropForeign(['formulir_id']);
                $table->dropColumn('formulir_id');
            }
        });
    }
};
