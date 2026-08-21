<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Index deleted_at for SoftDeletes queries in RFQ tables
        if (Schema::hasTable('rfqs')) {
            try {
                Schema::table('rfqs', function (Blueprint $table) {
                    if (Schema::hasColumn('rfqs', 'deleted_at')) {
                        $table->index('deleted_at', 'rfqs_deleted_at_index');
                    }
                });
            } catch (\Throwable $e) {
                // Ignore if index already exists
            }
        }

        if (Schema::hasTable('rfq_items')) {
            try {
                Schema::table('rfq_items', function (Blueprint $table) {
                    if (Schema::hasColumn('rfq_items', 'deleted_at')) {
                        $table->index('deleted_at', 'rfq_items_deleted_at_index');
                    }
                });
            } catch (\Throwable $e) {
                // Ignore if index already exists
            }
        }

        // 2. Index posts by date and status
        if (Schema::hasTable('posts')) {
            try {
                Schema::table('posts', function (Blueprint $table) {
                    if (Schema::hasColumn('posts', 'date')) {
                        $table->index('date', 'posts_date_index');
                    }
                });
            } catch (\Throwable $e) {
                // Ignore if index already exists
            }
        }

        // 3. Index products by created_at for admin sorting
        if (Schema::hasTable('products')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    if (Schema::hasColumn('products', 'created_at')) {
                        $table->index('created_at', 'products_created_at_index');
                    }
                });
            } catch (\Throwable $e) {
                // Ignore if index already exists
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rfqs')) {
            try {
                Schema::table('rfqs', function (Blueprint $table) {
                    $table->dropIndex('rfqs_deleted_at_index');
                });
            } catch (\Throwable $e) {}
        }

        if (Schema::hasTable('rfq_items')) {
            try {
                Schema::table('rfq_items', function (Blueprint $table) {
                    $table->dropIndex('rfq_items_deleted_at_index');
                });
            } catch (\Throwable $e) {}
        }

        if (Schema::hasTable('posts')) {
            try {
                Schema::table('posts', function (Blueprint $table) {
                    $table->dropIndex('posts_date_index');
                });
            } catch (\Throwable $e) {}
        }

        if (Schema::hasTable('products')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropIndex('products_created_at_index');
                });
            } catch (\Throwable $e) {}
        }
    }
};
