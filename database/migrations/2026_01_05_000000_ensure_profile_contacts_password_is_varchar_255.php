<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('profile_contacts')) {
            return;
        }

        // Ensure the column exists first.
        if (!Schema::hasColumn('profile_contacts', 'password')) {
            Schema::table('profile_contacts', function (Blueprint $table) {
                $table->string('password')->nullable();
            });
            return;
        }

        // Enlarge the column without requiring doctrine/dbal.
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `profile_contacts` MODIFY `password` VARCHAR(255) NULL");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE profile_contacts ALTER COLUMN password TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE profile_contacts ALTER COLUMN password DROP NOT NULL');
        } elseif ($driver === 'sqlite') {
            // SQLite can't alter column type easily; skip here.
            // For SQLite, adjust schema manually or recreate table.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: we don't want to shrink the column and risk truncation.
    }
};
