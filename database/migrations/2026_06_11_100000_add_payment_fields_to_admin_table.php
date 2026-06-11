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
        if (!Schema::hasTable('admin')) {
            return;
        }

        Schema::table('admin', function (Blueprint $table) {
            if (!Schema::hasColumn('admin', 'nomor_ewallet')) {
                $table->string('nomor_ewallet', 100)->nullable()->after('email');
            }
            if (!Schema::hasColumn('admin', 'nomor_bank')) {
                $table->string('nomor_bank', 100)->nullable()->after('nomor_ewallet');
            }
            if (!Schema::hasColumn('admin', 'qris')) {
                $table->string('qris')->nullable()->after('nomor_bank');
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
        if (!Schema::hasTable('admin')) {
            return;
        }

        Schema::table('admin', function (Blueprint $table) {
            if (Schema::hasColumn('admin', 'qris')) {
                $table->dropColumn('qris');
            }
            if (Schema::hasColumn('admin', 'nomor_bank')) {
                $table->dropColumn('nomor_bank');
            }
            if (Schema::hasColumn('admin', 'nomor_ewallet')) {
                $table->dropColumn('nomor_ewallet');
            }
        });
    }
};
