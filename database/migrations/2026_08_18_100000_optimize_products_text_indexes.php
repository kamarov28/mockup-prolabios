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
        // Drop legacy B-tree description index if it exists in DB
        // ponytail: Schema::hasIndex is available in Laravel 11+, otherwise fallback via try-catch
        if (method_exists(Schema::class, 'hasIndex') && Schema::hasIndex('products', 'products_description_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_description_index');
            });
        }

        // Add MySQL FULLTEXT index for optimized title & description searches
        $hasFulltext = method_exists(Schema::class, 'hasIndex')
            ? Schema::hasIndex('products', 'products_fulltext_index')
            : false;

        if (! $hasFulltext) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->fullText(['title', 'description'], 'products_fulltext_index');
                });
            } catch (\Throwable $e) {
                // Ignore if fulltext already exists or driver unsupported
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (method_exists(Schema::class, 'hasIndex') && Schema::hasIndex('products', 'products_fulltext_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_fulltext_index');
            });
        }
    }
};
