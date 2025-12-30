<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'nick_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nick_name')->nullable()->default(null)->after('username');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'nick_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('nick_name');
            });
        }
    }
};
