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
        // 1. Add is_admin column to users table
        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('password');
            });
        }

        // Set all existing seeded admin users to is_admin = true
        DB::table('users')->update(['is_admin' => true]);

        // 2. Add softDeletes (deleted_at) to rfqs table
        if (! Schema::hasColumn('rfqs', 'deleted_at')) {
            Schema::table('rfqs', function (Blueprint $table) {
                $table->softDeletes()->after('notes');
            });
        }

        // 3. Add softDeletes (deleted_at) to rfq_items table
        if (! Schema::hasColumn('rfq_items', 'deleted_at')) {
            Schema::table('rfq_items', function (Blueprint $table) {
                $table->softDeletes()->after('quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }

        if (Schema::hasColumn('rfqs', 'deleted_at')) {
            Schema::table('rfqs', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('rfq_items', 'deleted_at')) {
            Schema::table('rfq_items', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
