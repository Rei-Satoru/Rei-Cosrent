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
        if (!Schema::hasTable('profile_contacts')) {
            return;
        }

        Schema::table('profile_contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('profile_contacts', 'nomor_ewallet')) {
                $table->string('nomor_ewallet', 50)->nullable()->after('email');
            }
            if (!Schema::hasColumn('profile_contacts', 'nomor_bank')) {
                $table->string('nomor_bank', 50)->nullable()->after('nomor_ewallet');
            }
            if (!Schema::hasColumn('profile_contacts', 'qris')) {
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
        if (!Schema::hasTable('profile_contacts')) {
            return;
        }

        Schema::table('profile_contacts', function (Blueprint $table) {
            if (Schema::hasColumn('profile_contacts', 'qris')) {
                $table->dropColumn('qris');
            }
            if (Schema::hasColumn('profile_contacts', 'nomor_bank')) {
                $table->dropColumn('nomor_bank');
            }
            if (Schema::hasColumn('profile_contacts', 'nomor_ewallet')) {
                $table->dropColumn('nomor_ewallet');
            }
        });
    }
};
