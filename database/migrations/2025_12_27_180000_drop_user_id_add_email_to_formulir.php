<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add email column if not exists
        if (!Schema::hasColumn('formulir', 'email')) {
            Schema::table('formulir', function (Blueprint $table) {
                $table->string('email', 255)->nullable()->after('nama');
            });
        }

        // Drop foreign key and user_id if exist
        try {
            Schema::table('formulir', function (Blueprint $table) {
                // Some setups may have an FK; wrap in try/catch via Doctrine
                if (Schema::hasColumn('formulir', 'user_id')) {
                    // Drop FK if named conventionally, ignore failures silently
                    try { $table->dropForeign(['user_id']); } catch (\Throwable $e) { /* ignore */ }
                    $table->dropColumn('user_id');
                }
            });
        } catch (\Throwable $e) {
            // ignore if already dropped
        }
    }

    public function down(): void
    {
        Schema::table('formulir', function (Blueprint $table) {
            if (!Schema::hasColumn('formulir', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (Schema::hasColumn('formulir', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
