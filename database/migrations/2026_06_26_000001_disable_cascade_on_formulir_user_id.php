<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure formulir.user_id uses SET NULL (no cascade delete) so
        // deleting/anonymizing users never removes admin recap data.
        if (!Schema::hasTable('formulir') || !Schema::hasColumn('formulir', 'user_id')) {
            return;
        }

        // Drop existing foreign key (if any), then re-add with nullOnDelete.
        Schema::table('formulir', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
                // ignore if FK name differs / already dropped
            }
        });

        Schema::table('formulir', function (Blueprint $table) {
            // Re-create FK with SET NULL
            try {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            } catch (\Throwable $e) {
                // ignore if cannot add (e.g. already exists with same constraints)
            }
        });
    }

    public function down(): void
    {
        // Revert to default Laravel FK behavior if needed.
        // Here we keep it conservative: down() does not reintroduce cascade.
    }
};

