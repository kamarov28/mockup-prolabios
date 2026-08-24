<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {
            if (! Schema::hasColumn('rfqs', 'status')) {
                $table->string('status', 32)->default('new')->after('notes');
            }
            if (! Schema::hasColumn('rfqs', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
        });

        try {
            Schema::table('rfqs', function (Blueprint $table) {
                $table->index('status', 'rfqs_status_index');
            });
        } catch (\Throwable $e) {
            // index may already exist
        }
    }

    public function down(): void
    {
        try {
            Schema::table('rfqs', function (Blueprint $table) {
                $table->dropIndex('rfqs_status_index');
            });
        } catch (\Throwable $e) {
        }

        Schema::table('rfqs', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('rfqs', 'admin_notes')) {
                $cols[] = 'admin_notes';
            }
            if (Schema::hasColumn('rfqs', 'status')) {
                $cols[] = 'status';
            }
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};
