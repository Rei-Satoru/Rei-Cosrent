<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('profile_contacts') && !Schema::hasTable('admin')) {
            Schema::rename('profile_contacts', 'admin');
        }

        if (!Schema::hasTable('admin')) {
            return;
        }

        Schema::table('admin', function (Blueprint $table) {
            if (!Schema::hasColumn('admin', 'instagram')) {
                $table->string('instagram', 100)
                    ->nullable()
                    ->default('rei_cosrent')
                    ->after('phone');
            }
        });

        try {
            DB::table('admin')
                ->whereNull('instagram')
                ->orWhere('instagram', '')
                ->update(['instagram' => 'rei_cosrent']);
        } catch (\Throwable $e) {
            // Ignore update errors to keep migration safe across environments.
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('admin')) {
            Schema::table('admin', function (Blueprint $table) {
                if (Schema::hasColumn('admin', 'instagram')) {
                    $table->dropColumn('instagram');
                }
            });
        }

        if (Schema::hasTable('admin') && !Schema::hasTable('profile_contacts')) {
            Schema::rename('admin', 'profile_contacts');
        }
    }
};
