<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix nick_name to have a default NULL value
        if (Schema::hasColumn('users', 'nick_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nick_name')->nullable()->default(null)->change();
            });
        }
    }

    public function down(): void
    {
        // No action needed
    }
};
