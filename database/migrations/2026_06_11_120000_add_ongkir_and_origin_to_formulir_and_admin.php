<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('formulir')) {
            Schema::table('formulir', function (Blueprint $table) {
                if (!Schema::hasColumn('formulir', 'ongkir')) {
                    $table->decimal('ongkir', 12, 2)->default(0)->after('total_harga');
                }
            });
        }

        if (Schema::hasTable('admin')) {
            Schema::table('admin', function (Blueprint $table) {
                if (!Schema::hasColumn('admin', 'origin_province_id')) {
                    $table->unsignedBigInteger('origin_province_id')->nullable()->after('alamat');
                }
                if (!Schema::hasColumn('admin', 'origin_city_id')) {
                    $table->unsignedBigInteger('origin_city_id')->nullable()->after('origin_province_id');
                }
                if (!Schema::hasColumn('admin', 'origin_postal_code')) {
                    $table->string('origin_postal_code', 20)->nullable()->after('origin_city_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('formulir')) {
            Schema::table('formulir', function (Blueprint $table) {
                if (Schema::hasColumn('formulir', 'ongkir')) {
                    $table->dropColumn('ongkir');
                }
            });
        }

        if (Schema::hasTable('admin')) {
            Schema::table('admin', function (Blueprint $table) {
                if (Schema::hasColumn('admin', 'origin_postal_code')) {
                    $table->dropColumn('origin_postal_code');
                }
                if (Schema::hasColumn('admin', 'origin_city_id')) {
                    $table->dropColumn('origin_city_id');
                }
                if (Schema::hasColumn('admin', 'origin_province_id')) {
                    $table->dropColumn('origin_province_id');
                }
            });
        }
    }
};
