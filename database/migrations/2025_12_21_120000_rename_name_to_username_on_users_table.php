<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to avoid DBAL requirement; assumes MySQL
        DB::statement('ALTER TABLE `users` CHANGE `name` `username` VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `users` CHANGE `username` `name` VARCHAR(255) NOT NULL');
    }
};
